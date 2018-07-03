<?php
/**
 * Created by PhpStorm.
 * User: rcatalano
 * Date: 9/6/2017
 * Time: 12:40 PM
 */

namespace App\AdminBundle\Controller;

use App\CoreBundle\Entity\Orders\Order;
use App\CoreBundle\Services\EmailService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;

/**
 * @Sensio\Route("/testEmail", service="test_email_service")
 */
class TestEmailController extends Controller
{

    /**
     * @var EngineInterface
     */
    private $templating;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var EmailService
     */
    private $emailService;

    public function __construct(EngineInterface $templating, EntityManager $entityManager, EmailService $emailService)
    {
        $this->templating = $templating;
        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
    }

    /**
     * @Sensio\Route("/send", name="test_email_send_function")
     * @Sensio\Method({"POST"})
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request)
    {
        $email = $request->get('email');
        $content = $request->get('content');
        $subject = $request->get('subject');

        try {
            $this->emailService->sendEmail($subject, "tienda@abrecultura.com", $email, $content, null, null, true);
            return $this->render('AppAdminBundle:Emails:send.html.twig');
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}