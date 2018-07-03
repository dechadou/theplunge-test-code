<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Articulo;
use App\CoreBundle\Entity\Media;
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
 * @Sensio\Route("/media", service="api.controller.media")
 */
class MediaController extends BaseApiController
{
    /**
     * @param request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Sensio\Route("/{media_id}/{width}/{height}", name="api_media_get", defaults={"height" = null, "width"=null})
     * @Sensio\Method({"GET"})
     * @Sensio\Cache(smaxage=3600, maxage=3600)
     *
     * @ApiDoc(
     *  section="Media",
     *  description="Obtener media por id",
     *  resource=true,
     * )
     */
    public function getAction(Request $request)
    {
        $media_id = $request->get('media_id');
        if(!$media_id){
            throw new BadRequestHttpException('Invalid media');
        }
        $media = $this->entityManager->getRepository(Media::class)->findOneBy(['id' => $media_id]);
        if(!$media){
            throw new BadRequestHttpException('Media not found');
        }


        $height = ($request->get('height')) ? $request->get('height') : $media->getImageMetaData()['height'];
        $width = ($request->get('width')) ? $request->get('width') : $media->getImageMetaData()['width'];

        $urlGenerator = $this->get('mediamonks.sonata_media.generator.url_generator.image');
        $url = $urlGenerator->generateImageUrl($media, $height, $width, [], null, 0);
        return $this->redirect($url,301);
    }


}
