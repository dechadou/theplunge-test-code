<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\Orders\Order;
use App\CoreBundle\Entity\Orders\OrderUserData;
use App\CoreBundle\Entity\Subscriptor;
use App\CoreBundle\Security\JWT\JWTManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @author Rodrigo Catalano <rodrigo.catalano@mediamonks.com>
 * @Sensio\Route("/users", service="api.controller.users")
 */
class UserController extends BaseApiController
{
    /**
     * @param request $request
     * @return array|string
     * @throws \Exception
     * @Sensio\Route("/subscribe", name="api_users_subscribe")
     * @Sensio\Method({"POST"})
     * @Sensio\Cache(smaxage=1)
     *
     */
    public function subscribeAction(Request $request)
    {
        $store = $this->getAuthenticatedStore($request);

        $email = $request->get('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['invalid data'];
        }

        if ($email) {
            $exists = $this->entityManager->getRepository(Subscriptor::class)->findOneBy(
                [
                    'email' => $email
                ]
            );
            if (!$exists) {
                $subscriber = new Subscriptor();
                $subscriber->setEmail($email);
                $this->entityManager->persist($subscriber);
                $this->entityManager->flush();
                return ['ok'];
            }

        }

        return ['already subscribed'];
    }


}
