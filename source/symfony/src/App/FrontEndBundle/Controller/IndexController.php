<?php

namespace App\FrontEndBundle\Controller;

use MediaMonks\RestApi\Response\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;

/**
 * @author Robert Slootjes <robert@mediamonks.com>
 */
class IndexController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Sensio\Route(path="/", name="front_end_index")
     * @Sensio\Cache(smaxage=300, maxage=300)
     */
    public function indexAction()
    {
        return $this->render('AppFrontEndBundle:Home:index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Sensio\Route(path="/checkout/", name="front_end_checkout")
     * @Sensio\Route(path="/checkout/{whatever}", name="front_end_checkout_1")
     * @Sensio\Route(path="/checkout/{whatever}/{whatever2}", name="front_end_checkout_2")
     * @Sensio\Route(path="/checkout/{whatever}/{whatever2}/{whatever3}", name="front_end_checkout_3")
     * @Sensio\Cache(smaxage=1, maxage=1)
     */
    public function checkoutAction()
    {
        return $this->render('AppFrontEndBundle:Checkout:index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Sensio\Route(path="/cuadernodeescritura/", name="front_end_stores")
     * @Sensio\Route(path="/cuadernodeescritura/{whatever}", name="front_end_stores_1")
     * @Sensio\Route(path="/cuadernodeescritura/{whatever}/{whatever2}", name="front_end_stores_2")
     * @Sensio\Cache(smaxage=1, maxage=1)
     */
    public function storesAction()
    {
        return $this->render('AppFrontEndBundle:Stores:index.html.twig');
    }
}
