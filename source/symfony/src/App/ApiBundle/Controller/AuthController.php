<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Tienda;
use App\CoreBundle\Entity\User;
use App\CoreBundle\Security\JWT\JWTManagerInterface;
use Doctrine\ORM\EntityManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @Sensio\Route("auth", service="api.controller.auth")
 */
class AuthController
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var JWTManagerInterface
     */
    protected $jwtManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param TokenStorage $tokenStorage
     * @param JWTManagerInterface $jwtManager
     * @param EntityManager $entityManager
     */
    public function __construct(
        TokenStorage $tokenStorage,
        JWTManagerInterface $jwtManager,
        EntityManager $entityManager
    ) {
        $this->tokenStorage  = $tokenStorage;
        $this->jwtManager    = $jwtManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @Sensio\Route("/authenticate", name="api_auth_authenticate")
     * @Sensio\Method({"POST","OPTIONS"})
     * @Sensio\Cache(smaxage=3600, maxage=3600)
     *
     * @ApiDoc(
     *  section="Auth",
     *  resource=true,
     *  description="Get an access token",
     *  filters={
     *      {"name"="username", "dataType"="string"},
     *      {"name"="password", "dataType"="string"}
     *  }
     * )
     * @return array
     */
    public function authenticateAction()
    {
        $user = $this->getCurrentApiKey();

        return [
            'accessToken' => $this->jwtManager->sign([
                'id'       => $user->getId(),
                'verifier' => $user->getJwtVerifier(),
                'username' => $user->getUsername()
            ])
        ];
    }

    /**
     * @Sensio\Route("/revoke", name="api_auth_revoke")
     * @Sensio\Method({"DELETE"})
     *
     * @ApiDoc(
     *  section="Auth",
     *  resource=true,
     *  description="Invalidate all previous tokens",
     *  filters={
     *      {"name"="username", "dataType"="string"},
     *      {"name"="password", "dataType"="string"}
     *  }
     * )
     */
    public function revokeAction()
    {
        $user = $this->getCurrentApiKey();
        $user->updateJwtVerifier();
        $this->entityManager->flush($user);
    }

    /**
     * @return User
     */
    protected function getCurrentApiKey()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
