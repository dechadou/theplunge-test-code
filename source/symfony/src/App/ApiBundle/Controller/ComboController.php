<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Articulo;
use App\CoreBundle\Entity\Combo;
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
 * @Sensio\Route("/combo", service="api.controller.combo")
 */
class ComboController extends BaseApiController
{
    /**
     * @param request $request
     * @return array
     * @throws \Exception
     * @Sensio\Route("/{id}", name="api_combo_get_id")
     * @Sensio\Method({"POST"})
     * @Sensio\Cache(smaxage=1)
     *
     * @ApiDoc(
     *  section="Combos",
     *  description="Obtiene un combo por ID",
     *  resource=true,
     * )
     */
    public function getAction(Request $request)
    {
        $store = $this->getAuthenticatedStore($request);
        $comboId = $request->get('id');

        $combo = $this->entityManager->getRepository(Combo::class)->findOneBy([
            'id' => $comboId
        ]);

        if (!$combo) {
            throw new BadRequestHttpException('Invalid article ID');
        }

        $categorias = [];
        if ($combo->getCategoria()) {
            foreach ($combo->getCategoria() as $categoria) {
                $categorias[] = [
                    'id' => $categoria->getId(),
                    'name' => $categoria->getName()
                ];
            }
        }

        $media = [];

        if ($combo->getMedia()) {
            foreach ($combo->getMedia() as $m) {
                $media[] = [
                    'id' => $m->getMedia()->getId(),
                    'title' => $m->getMedia()->getTitle(),
                    'description' => $m->getMedia()->getDescription(),
                    'provider' => $m->getMedia()->getProvider(),
                    'type' => $m->getMedia()->getType(),
                    'metadata' => $m->getMedia()->getImageMetaData(),
                ];
            }
        }

        $productos = [];
        if ($combo->getComboProducto()) {
            foreach ($combo->getComboProducto() as $comboProducto) {
                $productos[] = $comboProducto->getProducto()->toArray();
            }
        }


        $result[] = [
            'id' => $combo->getId(),
            'name' => $combo->getName(),
            'price' => $combo->getPrice(),
            'description' => $combo->getDescription(),
            'categoria' => $categorias,
            'productos' => $productos,
            'media' => $media
        ];

        return $result;
    }


}
