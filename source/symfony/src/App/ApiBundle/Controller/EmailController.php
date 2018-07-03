<?php

namespace App\ApiBundle\Controller;

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
 * @Sensio\Route("/ses", service="api.controller.ses")
 */
class EmailController extends BaseApiController
{
    /**
     * @param request $request
     * @return array|string
     * @throws \Exception
     * @Sensio\Route("/send", name="api_ses_send")
     * @Sensio\Method({"POST"})
     * @Sensio\Cache(smaxage=1)
     *
     */
    public function sendAction(Request $request)
    {

        $subject = $request->get('subject');
        $from = $request->get('from');
        $to = $request->get('to');
        $content = $request->get('content');

        try {
            $email = $this->emailService->sendEmail($subject, $from, $to, $content);
            return ['Email sent'];
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }


}
