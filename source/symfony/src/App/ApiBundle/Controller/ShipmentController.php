<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Articulo;
use App\CoreBundle\Entity\Orders\Envio;
use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\Orders\Order;
use App\CoreBundle\Entity\Orders\OrderUserData;
use App\CoreBundle\Security\JWT\JWTManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @author Rodrigo Catalano <rodrigo.catalano@mediamonks.com>
 * @Sensio\Route("/shipment", service="api.controller.shipment")
 */
class ShipmentController extends BaseApiController
{
    /**
     * @param request $request
     * @return array
     * @throws \Exception
     * @Sensio\Route("/list", name="api_shipment_list")
     * @Sensio\Method({"POST"})
     * @Sensio\Cache(smaxage=1)
     *
     * @ApiDoc(
     *  section="Envio",
     *  description="Obtiene la lista de tipos de envio",
     *  resource=true,
     * )
     */
    public function listAction(Request $request)
    {
        $store = $this->getAuthenticatedStore($request);
        $enviosAll = $this->entityManager->getRepository(Envio::class)->findBy(['status' => true, 'tienda' => null],['id' => 'DESC']);
        $enviosTienda = $this->entityManager->getRepository(Envio::class)->findBy(['status' => true, 'tienda' => $store],['id' => 'DESC']);
        $envios = array_merge($enviosAll, $enviosTienda);
        if ($envios) {
            foreach ($envios as $envio) {
                $result[] = $envio->toArray();
            }
        }

        return $result;
    }


}
