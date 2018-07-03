<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\Orders\CompraArticulo;
use App\CoreBundle\Entity\Orders\CompraCombo;
use App\CoreBundle\Entity\Orders\Envio;
use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\Tienda;
use App\CoreBundle\Entity\User;
use App\CoreBundle\Security\JWT\JWTManagerInterface;
use App\CoreBundle\Services\EmailService;
use App\CoreBundle\Services\MercadoPago;
use App\CoreBundle\Services\MercadoPagoo;
use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @author Rodrigo Catalano <rodrigo.catalano@mediamonks.com>
 * @Sensio\Route(service="main_api_controller")
 */
class BaseApiController extends BaseController
{
    /**
     * @var EntityManager
     */
    public $entityManager;

    public $jwtManager;
    public $emailService;
    public $mercadopago;

    /**
     * @param MercadoPago $mercadoPago
     */
    public function setMercadoPagoService(MercadoPago $mercadoPago)
    {
        $this->mercadopago = $mercadoPago;
    }


    /**
     * @param EmailService $emailService
     */
    public function setEmailService(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param JWTManagerInterface $jwtManager
     */
    public function setJWTVerifier(JWTManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function parseData($request)
    {
        $debug = $request->get('debug');
        $data = $request->get('data');

        /* If debug == 1, dont expect base64_encode */
        if ($debug) {
            return json_decode($data);
        }

        if ($data) {
            if ($this->isValidJason($data)) {
                return json_decode(base64_decode($data))->data;
            }
        }

        throw new BadRequestHttpException('Invalid json file');
    }

    /**
     * @param $json
     * @return bool
     */
    public function isValidJason($json)
    {
        $data = base64_decode($json);
        json_decode($data);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * @param $date
     * @return string
     */
    public function isValidDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if ($d && $d->format('Y-m-d H:i:s') === $date) {
            return $d;
        };
    }


    /**
     * @param $estado_id
     * @return null|object
     */
    public function getEstadoById($estado_id)
    {
        $estado = $this->entityManager->getRepository(Estado::class)->findOneBy(['id' => $estado_id]);
        if (!$estado) {
            throw new NotFoundHttpException('Invalid estado');
        }

        return $estado;
    }

    /**
     * @param $envio_id
     * @return null|object
     */
    public function getEnvioById($envio_id)
    {
        $envio = $this->entityManager->getRepository(Envio::class)->findOneBy(['id' => $envio_id]);
        if (!$envio) {
            throw new NotFoundHttpException('Invalid envio');
        }

        return $envio;
    }

    /**
     * @param $tienda_id
     * @return null|object
     */
    public function getTiendaById($tienda_id)
    {
        $tienda = $this->entityManager->getRepository(Tienda::class)->findOneBy(['id' => $tienda_id]);
        if (!$tienda) {
            throw new NotFoundHttpException('Invalid tienda');
        }

        return $tienda;
    }

    /**
     * @param $request
     * @return mixed
     * @throws Exception
     */
    public function getAuthenticatedStore($request)
    {
        $header = $request->headers->has('Token');
        if ($header) {
            $AuthToken = $request->headers->get('Token');
            $token = explode(' ', $AuthToken);
            if ($token) {
                $userToken = end($token);
                if ($userToken) {
                    $username = $this->jwtManager->parse($userToken)['username'];
                    $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
                    if ($user) {
                        $store = $user->getTienda();
                        if ($store) {
                            return $store;
                        }
                    }
                }
            }
        }
        throw new Exception('Invalid Token or not related to a store');
    }

    public function getTotalSold($article)
    {
        $dontCount = ['Pendiente de pago', 'Cancelado', 'Pago Devuelto'];
        $count = 0;
        $compraArticulo = $this->entityManager->getRepository(CompraArticulo::class)->findBy(['articulo' => $article]);

        foreach ($compraArticulo as $compra) {
            if (!in_array($compra->getOrder()->getEstado()->getName(), $dontCount)) {
                $count = $count + $compra->getCantidad();
            };
        }


        $producto = $article->getProducto();
        if ($producto->getComboProducto()) {
            foreach ($producto->getComboProducto() as $comboProducto) {
                $combo = $comboProducto->getCombo();
                if ($combo) {
                    $compraCombo = $this->entityManager->getRepository(CompraCombo::class)->findBy(['combo' => $combo]);
                    foreach ($compraCombo as $cC) {
                        if (!in_array($cC->getOrder()->getEstado()->getName(), $dontCount)) {
                            $count = $count + ($cC->getCantidad() * $comboProducto->getAmount());
                        };
                    }
                }
            }
        }

        return $count;
    }
}
