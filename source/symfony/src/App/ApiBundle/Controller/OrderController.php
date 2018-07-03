<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Articulo;
use App\CoreBundle\Entity\Orders\CompraArticulo;
use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\Orders\Order;
use App\CoreBundle\Entity\Orders\OrderUserData;
use App\CoreBundle\Entity\Producto;
use App\CoreBundle\Security\JWT\JWTManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @author Rodrigo Catalano <rodrigo.catalano@mediamonks.com>
 * @Sensio\Route("/order", service="api.controller.order")
 */
class OrderController extends BaseApiController
{
    /**
     * @param request $request
     * @return array
     * @throws \Exception
     * @Sensio\Route("/update", name="api_order_update")
     * @Sensio\Method({"POST"})
     * @Sensio\Cache(smaxage=1)
     *
     * @ApiDoc(
     *  section="Compras",
     *  description="Actualiza una orden existente",
     *  resource=true,
     *  requirements={
     *     {"name"="debug", "requirement"="false", "dataType"="integer", "description":"If debug = 1 there is no need to base64 encode the json file"},
     *     {"name"="data", "requirement"="true", "dataType"="base64 encode / json_encode", "description"="Example Json File
            {
    'woocomerce_order_id': string,
    'buy_date': DateTimeObject (Y-m-d H:i:s),
    'estado_id': integer,
    'tracking_code': integer|optional,
    'mercado_pago_id': integer|optional,
    'importe': integer|decimal,
    'envio_id': integer
    }"},
     *  }
     * )
     */
    public function updateAction(Request $request)
    {
        $store = $this->getAuthenticatedStore($request);
        $data = $this->parseData($request);

        $order = $this->entityManager->getRepository(Order::class)->findOneBy(
            [
                'woocomerce_order_id' => $data->woocomerce_order_id,
                'tienda' => $store
            ]
        );

        if (!$order) {
            throw new BadRequestHttpException('invalid order');
        }

        if (isset($data->estado_id)) {
            $estadoId = $this->getEstadoById($data->estado_id);
            $order->setEstado($estadoId);
        }

        if (isset($data->envio_id)) {
            $envioId = $this->getEnvioById($data->envio_id);
            $order->setEnvio($envioId);
        }

        if (isset($data->buy_date)) {
            $buyDate = $this->isValidDate($data->buy_date);
            $order->setDate($buyDate);
        }

        if (isset($data->importe)) {
            $order->setImporte($data->importe);
        }

        if (isset($data->tracking_code)) {
            $order->setTrackingCode($data->tracking_code);
        }
        if (isset($data->mercado_pago_id)) {
            $order->setMercadoPagoId($data->mercado_pago_id);
        }

        $this->entityManager->persist($order);

        $this->entityManager->flush();

        return ['order updated'];


    }


    /**
     * @param request $request
     * @return array
     * @throws \Exception
     * @Sensio\Route("/store", name="api_order_store")
     * @Sensio\Method({"POST"})
     * @Sensio\Cache(smaxage=1)
     *
     * @ApiDoc(
     *  section="Compras",
     *  description="Crea una nueva orden",
     *  resource=true,
     *  requirements={
     *     {"name"="debug", "requirement"="false", "dataType"="integer", "description":"If debug = 1 there is no need to base64 encode the json file"},
     *     {"name"="data", "requirement"="true", "dataType"="base64 encode / json_encode", "description"="Example Json File
            {
    'woocomerce_order_id': string,
    'buy_date': DateTimeObject (Y-m-d H:i:s),
    'estado_id': integer,
    'tracking_code': integer|optional,
    'mercado_pago_id': integer|optional,
    'importe': integer|decimal,
    'envio_id': integer,
    'productos':[
     {
        'id': integer,
        'amount': integer,
        'price': decimal
    }
    {..}
    ]
    'user_data': {
    'billing_address': string / text,
    'billing_country': string,
    'billing_post_code': integer,
    'billing_state': string,
    'send_address': string,
    'email': string,
    'full_name': string,
    'phone_number': integer
    }
    }"},
     *  }
     * )
     */
    public function storeAction(Request $request)
    {
        $store = $this->getAuthenticatedStore($request);

        $data = $this->parseData($request);
        $exists = $this->entityManager->getRepository(Order::class)->findOneBy(
            [
                'woocomerce_order_id' => $data->woocomerce_order_id
            ]
        );

        if ($exists) {
            throw new BadRequestHttpException('woocomerce_order_id already in use');
        }



        $estadoId = $this->getEstadoById($data->estado_id);
        $envioId = $this->getEnvioById($data->envio_id);
        #$tiendaId = $this->getTiendaById($data->tienda_id);
        $buyDate = $this->isValidDate($data->buy_date);

        $order = new Order();

        if(isset($data->productos)){

            foreach($data->productos as $prod){
                $exists = $this->entityManager->getRepository(Articulo::class)->findOneBy(
                    ['id' => $prod->id]
                );
                if(!$exists){
                    throw new BadRequestHttpException('Invalid Producto');
                }

                $compraArticulo = new CompraArticulo();
                $compraArticulo->setOrder($order);
                $compraArticulo->setArticulo($exists);
                $compraArticulo->setCantidad($prod->amount);
                $compraArticulo->setImporte($prod->price);

                $this->entityManager->persist($compraArticulo);
                $order->addCompraArticulo($compraArticulo);

                $this->entityManager->persist($order);
            }
        }

        $order->setWoocomerceOrderId($data->woocomerce_order_id);
        $order->setTienda($store);
        $order->setDate($buyDate);

        $order->setEstado($estadoId);
        $order->setEnvio($envioId);
        $order->setImporte($data->importe);
        if (isset($data->tracking_code)) {
            $order->setTrackingCode($data->tracking_code);
        }
        if ($data->mercado_pago_id) {
            $order->setMercadoPagoId($data->mercado_pago_id);
        }

        $order->setStatus(true);

        $this->entityManager->persist($order);

        $orderUserData = new OrderUserData();
        $orderUserData->setOrder($order);
        $orderUserData->setBillingAddress($data->user_data->billing_address);
        $orderUserData->setBillingCountry($data->user_data->billing_country);
        $orderUserData->setBillingPostcode($data->user_data->billing_post_code);
        $orderUserData->setBillingState($data->user_data->billing_state);
        $orderUserData->setSendAddress($data->user_data->send_address);
        $orderUserData->setEmail($data->user_data->email);
        $orderUserData->setFullName($data->user_data->full_name);
        $orderUserData->setPhoneNumber($data->user_data->phone_number);


        $this->entityManager->persist($orderUserData);

        $estadoId->addOrder($order);

        $this->entityManager->persist($estadoId);


        $this->entityManager->flush();

        return ['orderId' => $order->getId()];
    }

}
