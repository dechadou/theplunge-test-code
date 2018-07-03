<?php
namespace App\AdminBundle\Controller;

use App\CoreBundle\Command\DeployCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/deploy", service="deploy_service")
 */
class DeployController extends Controller
{
    /**
     * @Route("/", name="deploy_home")
     */
    public function indexAction()
    {
        return $this->render('AppAdminBundle:Deploy:deploy.html.twig');
    }

    /**
     * @Route("/init", name="deploy_action")
     */
    public function deployAction()
    {
        return $this->render('AppAdminBundle:Deploy:init.html.twig');
    }

    /**
     * @Route("/init/start", name="deploy_start")
     */
    public function deployStartAction()
    {
        $command = new DeployCommand();
        $command->setContainer($this->container);
        $input = new ArrayInput([]);
        $output = new NullOutput();
        $resultCode = $command->run($input, $output);


        return new Response($resultCode);

    }


}