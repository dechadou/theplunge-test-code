<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Articulo;
use App\CoreBundle\Entity\Orders\CompraArticulo;
use App\CoreBundle\Entity\Orders\CompraCombo;
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
 * @Sensio\Route("/article", service="api.controller.article")
 */
class ArticleController extends BaseApiController
{
    /**
     * @param request $request
     * @return array
     * @throws \Exception
     * @Sensio\Route("/{id}", name="api_article_get_id")
     * @Sensio\Method({"POST"})
     * @Sensio\Cache(smaxage=1)
     *
     * @ApiDoc(
     *  section="Articulos",
     *  description="Obtiene un producto por ID",
     *  resource=true,
     * )
     */
    public function getAction(Request $request)
    {
        $store = $this->getAuthenticatedStore($request);
        $articleId = $request->get('id');

        $article = $this->entityManager->getRepository(Articulo::class)->findOneBy([
            'id' => $articleId
        ]);

        if (!$article) {
            throw new BadRequestHttpException('Invalid article ID');
        }

        $media = [];

        if ($article->getProducto()->getMedia()) {
            $urlGenerator = $this->get('mediamonks.sonata_media.generator.url_generator.image');
            foreach ($article->getProducto()->getMedia() as $m) {
                if ($m->getMedia()) {
                    $media[] = [
                        'id' => $m->getMedia()->getId(),
                        'title' => $m->getMedia()->getTitle(),
                        'description' => $m->getMedia()->getDescription(),
                        'provider' => $m->getMedia()->getProvider(),
                        'type' => $m->getMedia()->getType(),
                        'metadata' => $m->getMedia()->getImageMetaData(),
                        'image' => $urlGenerator->generateImageUrl($m->getMedia(), $m->getMedia()->getImageMetaData()['width'], $m->getMedia()->getImageMetaData()['height'], [], null, 0)
                    ];
                }
            }
        }


        return [
            'id' => $article->getId(),
            'tienda' => $article->getProducto()->getTienda()->first()->getId(),
            'parent_product' => $article->getProducto()->getId(),
            'name' => $article->getName(),
            'price' => $article->getPrice(),
            'description' => $article->getDescription(),
            'stock' => $article->getStock(),
            'slug' => $article->getSlug(),
            'totalSold' => $article->getTotalSold(),
            'media' => $media
        ];


    }



}
