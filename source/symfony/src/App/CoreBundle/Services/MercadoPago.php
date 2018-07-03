<?php

namespace App\CoreBundle\Services;

use App\CoreBundle\Entity\Articulo;
use App\CoreBundle\Entity\Combo;
use App\CoreBundle\Entity\Orders\CompraArticulo;
use App\CoreBundle\Entity\Orders\CompraCombo;
use App\CoreBundle\Entity\Orders\Envio;
use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\Orders\Order;
use App\CoreBundle\Entity\Orders\OrderUserData;
use App\CoreBundle\Entity\Subscriptor;
use App\CoreBundle\Entity\Tienda;
use App\CoreBundle\MercadoPago\MP;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Uuid;

class MercadoPago
{
    private $entityManager;
    /**
     * @var Tienda
     */
    private $tienda;
    public $mp;
    public $sandbox;

    /**
     * MercadoPago constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Tienda $tienda
     * @param bool $sandbox
     */
    public function configure(Tienda $tienda, $sandbox = false)
    {
        $this->tienda = $tienda;
        $this->mp = new MP($tienda->getMercadopagoKey(), $tienda->getMercadopagoSecret());
        $this->sandbox = $sandbox;
        $this->mp->sandbox_mode($this->sandbox);
    }

    /**
     * @param $preference
     * @return mixed
     */
    public function validatePreference($preference)
    {
        $preference = json_decode($preference, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('Invalid json file');
        };

        if (!is_array($preference)) {
            throw new BadRequestHttpException('Invalid Preference');
        }

        if (empty($preference)) {
            throw new BadRequestHttpException('Invalid Preference');
        }

        if (!array_key_exists('items', $preference)) {
            throw new BadRequestHttpException('No items in preference');
        }

        if (!array_key_exists('payer', $preference)) {
            throw new BadRequestHttpException('Invalid payer info');
        }


        return $preference;
    }


    /**
     * @param array $preference
     * @param null $envio
     * @return array|string
     */
    public function create_preference($preference = [], $envio = null)
    {

        $data = $this->validatePreference($preference);

        if (!array_key_exists('back_urls', $data)) {
            $data["back_urls"] = [
                "success" => $this->tienda->getHostname() . "/approved",
                "pending" => $this->tienda->getHostname() . "/pending",
                "failure" => $this->tienda->getHostname() . "/failed"
            ];
        }
        if (!array_key_exists('auto_return', $data)) {
            $data["auto_return"] = "approved";
        }

        $data['payer']['date_create'] = new \DateTime($data['payer']['date_create']);

        if ($envio) {
            if ($envio->getPrice() > 0) {
                $data['shipments'] = [
                    'mode' => 'not_specified',
                    'cost' => (int)$envio->getPrice(),
                    /*'receiver_address' => [
                        'zip_code' => '',
                        'street_name' => '',
                        'street_number' => '',
                        'floor' => '',
                        'apartment' => ''
                    ]*/
                ];
            }
        }

        $data['notification_url'] = 'https://www.abrecultura.com/api/ipn/notifications';



        /*$data2 = [
            "items" => [
                [
                    "title" => "Test",
                    "quantity" => 1,
                    "unit_price" => 123,
                    'item_id' => 29
                ],
                [
                    "title" => "Libro",
                    "quantity" => 1,
                    "unit_price" => 500,
                    'item_id' => 30
                ]
            ],
            "payer" => [
                "optin" => true | false,
                "name" => "Rodrigo",
                "surname" => "Catalano",
                "email" => "dechado2u@gmail.com",
                "date_create" => new \DateTime('now'),
                'billing_state' => 'Argentina',
                'billing_country' => 'Argentina',
                "phone" => [
                    "area_code" => "+54",
                    "number" => "1535192545"
                ],
                "address" => [
                    "street_name" => "El Salvador",
                    "street_number" => "5137",
                    "zip_code" => "1414"
                ]
            ],
            "back_urls" => [
                "success" => "http://www.google.com/a",
                "pending" => "http://www.google.com/b",
                "failure" => "http://www.google.com/c"
            ],
            "auto_return" => "approved",
            "envio_id" => 1
        ];*/


        try {
            $order = $this->createTemporaryOrder($data);
            $data['external_reference'] = $order->getWoocomerceOrderId();

            $mpPreference = $this->mp->create_preference($data);

            if ($mpPreference['status'] == 201) {

                // update preference
                $order->setMercadoPagoPreferenceId($mpPreference['response']['id']);
                $this->entityManager->persist($order);
                $this->entityManager->flush();

                if ($this->sandbox) {
                    return
                        [
                            'status' => $mpPreference['status'],
                            'payment_id' => $mpPreference['response']['id'],
                            'url' => $mpPreference['response']['sandbox_init_point']
                        ];
                }
                return
                    [
                        'status' => $mpPreference['status'],
                        'payment_id' => $mpPreference['response']['id'],
                        'url' => $mpPreference['response']['init_point']
                    ];
            }
        } catch (\Exception $e) {
            return $e->getMessage() . ' on Line:' . $e->getLine();
        }

        return $preference;
    }

    /**
     * @param $preference
     * @return Order
     */
    public function createTemporaryOrder($preference)
    {

        $total_compra = 0;
        $order = new Order();

        if (isset($preference['items'])) {

            foreach ($preference['items'] as $prod) {
                if ($prod['item_type'] == 'combo') {
                    $comboExists = $this->entityManager->getRepository(Combo::class)->findOneBy(
                        ['id' => $prod['item_id']]
                    );

                    if (!$comboExists) {
                        throw new BadRequestHttpException('Invalid Producto o combo');
                    }

                    $compraCombo = new CompraCombo();
                    $compraCombo->setOrder($order);
                    $compraCombo->setCombo($comboExists);
                    $compraCombo->setCantidad($prod['quantity']);
                    $compraCombo->setImporte($prod['unit_price']);


                    $total_compra = $total_compra + (int)($prod['unit_price'] * $prod['quantity']);

                    $this->entityManager->persist($compraCombo);
                    $order->addCompraCombo($compraCombo);

                    $this->entityManager->persist($order);
                }
                if ($prod['item_type'] == 'articulo') {
                    $prodExists = $this->entityManager->getRepository(Articulo::class)->findOneBy(
                        ['id' => $prod['item_id']]
                    );

                    if (!$prodExists) {
                        throw new BadRequestHttpException('Invalid Article > ' . $prod['item_id']);
                    }

                    $compraArticulo = new CompraArticulo();
                    $compraArticulo->setOrder($order);
                    $compraArticulo->setArticulo($prodExists);
                    $compraArticulo->setCantidad($prod['quantity']);
                    $compraArticulo->setImporte($prod['unit_price']);

                    $total_compra = $total_compra + (int)($prod['unit_price'] * $prod['quantity']);

                    $this->entityManager->persist($compraArticulo);
                    $order->addCompraArticulo($compraArticulo);

                    $this->entityManager->persist($order);
                }


            }


        }


        $order->setTienda($this->tienda);
        $today = new \DateTime();
        $order->setDate($today);

        // get estado object
        $estado = $this->entityManager->getRepository(Estado::class)->findOneBy(
            [
                'name' => 'Pendiente de pago'
            ]
        );
        $envio = $this->entityManager->getRepository(Envio::class)->findOneBy(
            ['id' => $preference['envio_id']]
        );
        if (!$envio) {
            throw new BadRequestHttpException('Invalid envio_id');
        }
        $order->setEstado($estado);
        $order->setEnvio($envio);
        $order->setImporte($total_compra);

        #$order->setMercadoPagoPreferenceId($mpPreference['response']['id']);
        $order->setStatus(true);

        $this->entityManager->persist($order);

        $order->setWoocomerceOrderId($this->tienda->getOrderPrefix() . '-' . uniqid());

        $orderUserData = new OrderUserData();
        $orderUserData->setOrder($order);
        $orderUserData->setBillingAddress($preference['payer']['address']['street_name']);
        $orderUserData->setBillingAddressNumber($preference['payer']['address']['street_number']);
        $orderUserData->setApartment($preference['payer']['address']['apartment']);
        $orderUserData->setFloor($preference['payer']['address']['floor']);
        $orderUserData->setBillingCountry($preference['payer']['billing_country']);
        $orderUserData->setBillingPostcode($preference['payer']['address']['zip_code']);
        $orderUserData->setBillingState($preference['payer']['billing_state']);
        $orderUserData->setBillingCity($preference['payer']['billing_city']);
        $orderUserData->setSendAddress($preference['payer']['address']['street_name'] . ' ' . $preference['payer']['address']['street_number']);
        $orderUserData->setEmail($preference['payer']['email']);
        $orderUserData->setFullName($preference['payer']['name'] . ' ' . $preference['payer']['surname']);
        $orderUserData->setPhoneNumber($preference['payer']['phone']['area_code'] . ' ' . $preference['payer']['phone']['number']);


        if ($preference['payer']['optin']) {
            $exists = $this->entityManager->getRepository(Subscriptor::class)->findOneBy(
                [
                    'email' => $preference['payer']['email']
                ]
            );
            if (!$exists) {
                $subscriptor = new Subscriptor();
                $subscriptor->setEmail($preference['payer']['email']);
                $this->entityManager->persist($subscriptor);
            }
        }


        $this->entityManager->persist($orderUserData);

        $estado->addOrder($order);

        $this->entityManager->persist($estado);

        $this->entityManager->flush();

        $order->setWoocomerceOrderId($this->tienda->getOrderPrefix() . '-' . $order->getId());
        $this->entityManager->persist($orderUserData);
        $this->entityManager->flush();

        return $order;

    }

    /**
     * @param $paymentId
     */
    public function getPaymentById($paymentId)
    {
        $paymentInfo = $this->mp->get_payment($paymentId);
        return $paymentInfo;
    }


}