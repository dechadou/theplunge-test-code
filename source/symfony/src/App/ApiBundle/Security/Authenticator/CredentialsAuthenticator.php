<?php

namespace App\ApiBundle\Security\Authenticator;

use App\CoreBundle\Repository\TiendaRepository;
use App\CoreBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class CredentialsAuthenticator extends AbstractGuardAuthenticator
{
    const ROUTE_NAME = 'api_auth_authenticate';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var TiendaRepository|null
     */
    private $tiendaRepository;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $userRepository
     * @param TiendaRepository|null $tiendaRepository
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository = null, TiendaRepository $tiendaRepository = null)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->tiendaRepository = $tiendaRepository;
    }

    /**
     * @param Request $request
     * @return string|null
     */
    public function getCredentials(Request $request)
    {
        if ($request->get('_route') !== self::ROUTE_NAME) {
            return null;
        }

        $username = $request->request->get('app_id');
        $password = $request->request->get('app_secret');

        if (empty($username) || empty($password)) {
            return null;
        }

        return [
            'username' => $username,
            'password' => $password,
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        $store = $this->tiendaRepository->findOneBy(
            [
                'app_id' => $credentials['username'],
                'app_secret' => $credentials['password']
            ]
        );

        if($store){
            if($store->getUser()){
                return $this->userRepository->findOneByUsername($store->getUser()->getUsername());
            }
        }
        return null;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new AccessDeniedHttpException(
            strtr($exception->getMessageKey(), $exception->getMessageData()),
            $exception
        );
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @throws UnauthorizedHttpException
     * @return void
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new UnauthorizedHttpException(null, 'A valid access token is required');
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
