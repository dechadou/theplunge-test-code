<?php
/**
 * Created by PhpStorm.
 * User: rcatalano
 * Date: 9/6/2017
 * Time: 12:40 PM
 */

namespace App\AdminBundle\Controller;

use App\CoreBundle\Entity\Orders\Order;
use App\CoreBundle\Entity\Tienda;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;

/**
 * @Sensio\Route("/comboProducto", service="combo_producto_service")
 */
class ComboProductoController extends Controller
{

    /**
     * @var EngineInterface
     */
    private $templating;
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EngineInterface $templating, EntityManager $entityManager)
    {
        $this->templating = $templating;
        $this->entityManager = $entityManager;
    }

    /**
     * @Sensio\Route("/realod-products", name="reload_products")
     * @Sensio\Method({"POST","GET"})
     * @param Request $request
     * @return string
     */
    public function reloadProductsAction(Request $request)
    {
        $result = [];
        $store = $this->entityManager->getRepository(Tienda::class)->findOneBy(['name' => $request->get('name')]);

        if ($store) {
            $products = $store->getProducto();

            if ($products) {
                foreach ($products as $p) {
                    $result[$p->getName()] = $p->getId();
                }
            }

            if (!empty($result)) {
                return new JsonResponse($result);
            }
        }

        return new JsonResponse('no data');
    }
}