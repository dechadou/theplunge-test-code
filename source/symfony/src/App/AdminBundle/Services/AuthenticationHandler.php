<?php
namespace App\AdminBundle\Services;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthenticationHandler implements  AuthenticationSuccessHandlerInterface {
    protected $container;

    public function __construct( $container ) {
        $this->container = $container;
    }

    public function onAuthenticationSuccess( Request $request, TokenInterface $token ) {
        $user = $token->getUser();

        if(in_array('ROLE_ENVIOS',$user->getRoles())){
            $url = $this->container->get( 'router' )->generate( 'admin_app_core_orders_order_list' );
        }else{
            $url = $this->container->get( 'router' )->generate( 'sonata_admin_dashboard' );
        }
        return new RedirectResponse( $url );

    }
}