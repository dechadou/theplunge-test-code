<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Articulo;
use App\CoreBundle\Entity\Meta;
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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Rodrigo Catalano <rodrigo.catalano@mediamonks.com>
 * @Sensio\Route("/metas", service="api.controller.meta")
 */
class MetaController extends BaseApiController
{
    /**
     * @return array
     * @Sensio\Route("/list", name="api_meta_list")
     * @Sensio\Method({"GET"})
     * @Sensio\Cache(smaxage=1)
     *
     * @ApiDoc(
     *  section="Metas",
     *  description="Obtiene la lista de todos los meta",
     *  resource=true,
     * )
     */
    public function listAction()
    {
        $metas = $this->entityManager->getRepository(Meta::class)->findBy(['status' => true], ['id' => 'DESC']);
        $result = [];
        if ($metas) {
            foreach ($metas as $meta) {
                $result[] = $meta->toArray();
            }
        }

        return $result;
    }

    /**
     * @param $slug
     * @return array
     * @Sensio\Route("/slug/{slug}", name="api_meta_byslug")
     * @Sensio\Method({"GET"})
     * @Sensio\Cache(smaxage=1)
     *
     * @ApiDoc(
     *  section="Metas",
     *  description="Obtiene un meta por id",
     *  resource=true,
     * )
     */
    public function getBySlugAction($slug)
    {
        if ($slug == '') {
            throw new BadRequestHttpException('Invalid slug');
        }
        $metas = $this->entityManager->getRepository(Meta::class)->findOneBy(['slug' => $slug, 'status' => true]);
        if ($metas) {
            return $metas->toArray();
        }

        throw new NotFoundHttpException('Meta not found for slug ' . $slug);
    }


}
