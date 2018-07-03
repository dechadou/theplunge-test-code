<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\Orders\Order;
use App\CoreBundle\Entity\Orders\OrderUserData;
use App\CoreBundle\Security\JWT\JWTManagerInterface;
use App\CoreBundle\Services\MercadoPagoo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @author Rodrigo Catalano <rodrigo.catalano@mediamonks.com>
 * @Sensio\Route("/checkout", service="api.controller.checkout")
 */
class CheckoutController extends BaseApiController
{
    /**
     * @param request $request
     * @return array
     * @throws \Exception
     * @Sensio\Route("/payment/process", name="api_checkout_payment_process")
     * @Sensio\Method({"POST"})
     * @Sensio\Cache(smaxage=1)
     *
     * @ApiDoc(
     *  section="Checkout",
     *  description="Procesa un pago",
     *  resource=true,
     * )
     */
    public function processPaymentAction(Request $request)
    {
        $store = $this->getAuthenticatedStore($request);

        $preference = $request->get('preference');
        $sandbox = false;
        /*if ($request->request->has('sandbox')) {
            $sandbox = true;
        }*/

        if ($preference) {
            $data = json_decode($preference, true);
            $envio = $this->getEnvioById($data['envio_id']);

            $this->mercadopago->configure($store, $sandbox);
            return $this->mercadopago->create_preference($preference, $envio);
        }

        throw new BadRequestHttpException('Invalid Preference');
    }

    /**
     * @param request $request
     * @return array
     * @throws \Exception
     * @Sensio\Route("/payment/store", name="api_checkout_payment_store")
     * @Sensio\Method({"POST"})
     * @Sensio\Cache(smaxage=1)
     *
     * @ApiDoc(
     *  section="Checkout",
     *  description="Actualiza un pago luego de obtener la respuesta de MercadoPago",
     *  resource=true,
     * )
     */
    public function saveMercadoPagoRequestAction(Request $request)
    {
        $store = $this->getAuthenticatedStore($request);
        $status = $request->get('collection_status');
        $paymentId = $request->get('collection_id');
        $mercadoPagoId = $request->get('preference_id');

        $order = $this->entityManager->getRepository(Order::class)->findOneBy(
            [
                'mercado_pago_preference_id' => $mercadoPagoId
            ]
        );

        if (!$order) {
            throw new BadRequestHttpException('Order does not exist');
        }


        if ($order->getEstado()->getName() != 'Pendiente de pago') {
            throw new BadRequestHttpException('Order already proccesed');
        }

        if ($status == 'approved') {
            $estado = $this->entityManager->getRepository(Estado::class)->findOneBy(
                [
                    'name' => 'Procesando'
                ]
            );
            $order->setEstado($estado);
            $order->setMercadoPagoId($paymentId);

            $this->updateProductStock($order);

            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }

        if ($status == 'canceled') {
            $estado = $this->entityManager->getRepository(Estado::class)->findOneBy(
                [
                    'name' => 'Cancelado'
                ]
            );
            $order->setEstado($estado);
            $order->setMercadoPagoId($paymentId);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }

        if ($status == 'pending') {
            $estado = $this->entityManager->getRepository(Estado::class)->findOneBy(
                [
                    'name' => 'Pendiente de pago'
                ]
            );
            $order->setEstado($estado);
            $order->setMercadoPagoId($paymentId);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }

        return ['site_url' => $store->getHostname(), 'order_id' => $order->getWoocomerceOrderId()];

    }

    /**
     * @param request $request
     * @return array
     * @throws \Exception
     * @Sensio\Route("/payment/get", name="api_checkout_payment_get")
     * @Sensio\Method({"POST"})
     * @Sensio\Cache(smaxage=1)
     *
     * @ApiDoc(
     *  section="Checkout",
     *  description="Obtiene el status de pago de MercadoPago",
     *  resource=true,
     * )
     */
    public function getMercadoPagoPaymentAction(Request $request)
    {
        $store = $this->getAuthenticatedStore($request);
        $preference_id = $request->get('preference_id');
        $sandbox = false;
        if ($request->request->has('sandbox')) {
            $sandbox = true;
        }


        $this->mercadopago->configure($store, $sandbox);
        $paymentInfo = $this->mercadopago->getPaymentById($preference_id);

        return $paymentInfo;
    }

    /**
     * @param $order
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateProductStock($order)
    {
        if ($order) {
            if ($order->getCompraArticulo()) {
                foreach ($order->getCompraArticulo() as $compraArticulo) {
                    $articulo = $compraArticulo->getArticulo();
                    $stock = $articulo->getStock();
                    $newStock = 0;
                    if ($stock > 0) {
                        $newStock = $stock - 1;
                    }
                    $articulo->setStock($newStock);
                    $this->entityManager->persist($articulo);
                }
            }
            if ($order->getCompraCombo()) {
                foreach ($order->getCompraCombo() as $compraCombo) {
                    $combo = $compraCombo->getCombo();
                    if ($combo) {
                        foreach ($combo->getComboProducto() as $comboProducto) {
                            $producto = $comboProducto->getProducto();
                            foreach ($producto->getArticulo() as $articulo) {
                                $stock = $articulo->getStock();
                                $newStock = 0;
                                if ($stock > 0) {
                                    $newStock = $stock - 1;
                                }
                                $articulo->setStock($newStock);
                                $this->entityManager->persist($articulo);
                            }
                        }
                    }
                }
            }

            $this->entityManager->flush();

        }
    }


}
