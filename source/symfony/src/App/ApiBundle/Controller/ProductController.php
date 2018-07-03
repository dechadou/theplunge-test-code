<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Articulo;
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
 * @Sensio\Route("/product", service="api.controller.product")
 */
class ProductController extends BaseApiController
{
    /**
     * @param request $request
     * @return array
     * @throws \Exception
     * @Sensio\Route("/list", name="api_product_list")
     * @Sensio\Method({"POST","OPTIONS"})
     * @Sensio\Cache(smaxage=360, maxage=360)
     *
     * @ApiDoc(
     *  section="Productos",
     *  description="Obtiene la lista de Productos",
     *  resource=true,
     * )
     */
    public function listAction(Request $request)
    {
        $store = $this->getAuthenticatedStore($request);
        $products = $store->getProducto();
        $combos = $store->getCombo();

        $result = [];
        if ($products) {
            foreach ($products as $product) {

                $articulos = [];
                if ($product->getArticulo()) {
                    foreach ($product->getArticulo() as $ar) {
                        $articulos[$ar->getId()] = $ar->toArray();
                    }
                }


                $tipoProducto = null;
                if ($product->getTipoProducto()) {
                    $tipoProducto = $product->getTipoProducto()->toArray();
                }

                $categorias = [];
                if ($product->getCategoria()) {
                    foreach ($product->getCategoria() as $categoria) {
                        $categorias[] = [
                            'id' => $categoria->getId(),
                            'name' => $categoria->getName()
                        ];
                    }
                }

                $media = [];
                if ($product->getMedia()) {
                    foreach ($product->getMedia() as $m) {

                        if ($m->getMedia()) {
                            if ($m->getStatus()) {
                                $media[] = [
                                    'id' => $m->getMedia()->getId(),
                                    'primary_media' => $m->getPrimaryMedia(),
                                    'title' => $m->getMedia()->getTitle(),
                                    'description' => $m->getMedia()->getDescription(),
                                    'provider' => $m->getMedia()->getProvider(),
                                    'type' => $m->getMedia()->getType(),
                                    'metadata' => $m->getMedia()->getImageMetaData(),
                                ];
                            }
                        }
                    }
                }

                $result['productos'][$product->getId()] = [
                    'id' => $product->getId(),
                    'esperando_produccion' => $product->getInProduction(),
                    'tipo_producto_id' => $tipoProducto,
                    'categoria' => $categorias,
                    'name' => $product->getName(),
                    'description' => $product->getDescription(),
                    'slug' => $product->getSlug(),
                    'price' => $product->getPrice(),
                    'stock' => $product->getStock(),
                    'stock_minimo' => $product->getStockMinimo(),
                    'articulos' => $articulos,
                    'media' => $media
                ];
            }
        }

        if ($combos) {
            foreach ($combos as $combo) {

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
                        if ($m->getStatus()) {
                            $media[] = [
                                'id' => $m->getMedia()->getId(),
                                'primary_media' => $m->getPrimaryMedia(),
                                'title' => $m->getMedia()->getTitle(),
                                'description' => $m->getMedia()->getDescription(),
                                'provider' => $m->getMedia()->getProvider(),
                                'type' => $m->getMedia()->getType(),
                                'metadata' => $m->getMedia()->getImageMetaData(),
                            ];
                        }
                    }
                }

                $productos = [];
                if ($combo->getComboProducto()) {
                    foreach ($combo->getComboProducto() as $comboProducto) {
                        $productos[] = $comboProducto->getProducto()->toArray();
                    }
                }


                $result['combos'][$combo->getid()] = [
                    'id' => $combo->getId(),
                    'name' => $combo->getName(),
                    'price' => $combo->getPrice(),
                    'description' => $combo->getDescription(),
                    'categoria' => $categorias,
                    'productos' => $productos,
                    'media' => $media
                ];
            }
        }


        return $result;
    }


}
