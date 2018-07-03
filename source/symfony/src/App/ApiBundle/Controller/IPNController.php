<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\IPNLog;
use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\Orders\Order;
use App\CoreBundle\Entity\Orders\OrderUserData;
use App\CoreBundle\MercadoPago\MP;
use App\CoreBundle\Security\JWT\JWTManagerInterface;
use App\CoreBundle\Services\MercadoPagoo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @author Rodrigo Catalano <rodrigo.catalano@mediamonks.com>
 * @Sensio\Route("/ipn", service="api.controller.ipn")
 */
class IPNController extends BaseApiController
{
    /**
     * @param request $request
     * @return Response
     * @throws \Exception
     * @Sensio\Route("/notifications", name="api_ipn_notifications")
     * @Sensio\Method({"GET", "POST"})
     * @Sensio\Cache(smaxage=1)
     *
     */
    public function notificationsAction(Request $request)
    {

        $mp = new MP("53840784279429", "u2fvDNxrfdhYo2MR9F6CM52XbnhUAdVr");

        $id = $request->get('id');
        $topic = $request->get('topic');

        if (!isset($id, $topic) || !ctype_digit($id)) {
            return new Response('ok', Response::HTTP_BAD_REQUEST);
        }

        $merchant_order_info = $mp->get("/merchant_orders/" . $id);

// Get the payment and the corresponding merchant_order reported by the IPN.
        if ($topic == 'payment') {
            $payment_info = $mp->get("/collections/notifications/" . $id);
            $merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"]);
// Get the merchant_order reported by the IPN.
        } else if ($topic == 'merchant_order') {
            $merchant_order_info = $mp->get("/merchant_orders/" . $id);
        }


        if ($merchant_order_info) {
            if ($merchant_order_info["status"] == 200) {
                // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
                $external_reference = $merchant_order_info['response']['external_reference'];
                $order = $this->entityManager->getRepository(Order::class)->findOneBy(['woocomerce_order_id' => $external_reference]);
                if ($order) {
                    $this->createLog($merchant_order_info, $id, $order);
                    //$merchant_order_info['payments'][0]['id'];
                    $order->setMercadoPagoId($id);
                    $this->entityManager->persist($order);
                    $this->entityManager->flush();
                }
            }
        }

        return new Response('ok', Response::HTTP_OK);
    }

    public function createLog($log, $request, $order)
    {
        $manager = $this->entityManager;

        $entity = new IPNLog();
        $entity->setLog(json_encode($log));
        $entity->setRequest($request);
        $entity->setOrder($order);

        $manager->persist($entity);
        $manager->flush();

    }

}
