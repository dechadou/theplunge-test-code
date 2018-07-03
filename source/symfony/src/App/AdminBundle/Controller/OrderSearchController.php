<?php
/**
 * Created by PhpStorm.
 * User: rcatalano
 * Date: 9/6/2017
 * Time: 12:40 PM
 */

namespace App\AdminBundle\Controller;

use App\CoreBundle\Entity\Orders\Order;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;

/**
 * @Sensio\Route("/ordersearch", service="order_search_service")
 */
class OrderSearchController extends Controller
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
     * @Sensio\Route("/search", name="order_search_function")
     * @Sensio\Method({"POST","GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request)
    {
        $term = $request->get('term');

        if (strpos($term, '#') !== false) {
            $woTerm = substr($term, 1);
        }


        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('o')
            ->from('AppCoreBundle:Orders\Order', 'o')

            ->leftJoin('o.tienda', 't')
            ->addSelect('t')

            ->leftJoin('o.envio', 'e')
            ->addSelect('e')

            ->leftJoin('o.estado', 'es')
            ->addSelect('es')

            ->leftJoin('o.user_data', 'ud')
            ->addSelect('ud')

            ->leftJoin('o.compra_articulo', 'ca')
            ->addSelect('ca')

            ->leftJoin('ca.articulo', 'ar')
            ->addSelect('ar')

            ->andWhere('o.woocomerce_order_id = :term')
            ->orWhere('ud.email LIKE :email')
            ->orWhere('ud.full_name LIKE :name')
            ->setParameter('email', '%'.$term.'%')
            ->setParameter('name', '%'.$term.'%')
            ->setParameter('term', $woTerm)
            ;

        $result = $qb->getQuery()->getArrayResult();


        return  $this->render('AppAdminBundle:Order:search.html.twig', ['result' => $result]);
    }
}