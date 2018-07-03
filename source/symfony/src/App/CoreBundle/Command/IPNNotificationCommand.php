<?php

namespace App\CoreBundle\Command;

use App\CoreBundle\Entity\IPNLog;
use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\Orders\Nota;
use App\CoreBundle\Entity\Orders\Order;
use Doctrine\Common\Persistence\ManagerRegistry;
use MP;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class IPNNotification
 * @package App\Command
 */
class IPNNotificationCommand extends ContainerAwareCommand
{

    private $mp;

    protected function configure()
    {
        $this
            ->setName('abre:ipn-notification')
            ->setDescription('Updates all orders based on the payment id');
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    public function getEmailService()
    {
        return $this->getContainer()->get('email_service');
    }

    /**
     * @return object
     */
    public function getMercadoPagoService()
    {
        return $this->getContainer()->get('mercadopago_service');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \MercadoPagoException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getEntityManager();


        // Change the next line by your classes
        foreach ($manager->getRepository(Order::class)->findBy([], ['id' => 'DESC']) as $entity) {


            $store = $entity->getTienda();
            $this->mp = new MP($store->getMercadopagoKey(), $store->getMercadopagoSecret());

            //setup store
            if ($entity->getMercadoPagoId()) {
                $output->writeln("Checking Order #" . $entity->getWoocomerceOrderId());
                try {
                    $currentEstado = $entity->getEstado()->getName();

                    if (in_array($currentEstado, ['Procesando', 'Pendiente de pago'])) {
                        if (substr($entity->getMercadoPagoId(), 0, 1) === '7') {
                            $merchant_order_info = $this->mp->get("/merchant_orders/" . $entity->getMercadoPagoId());
                            if ($merchant_order_info) {
                                if (!empty($merchant_order_info['response']['payments'])) {

                                    if (isset($merchant_order_info['response']['payments'][0]['id'])) {
                                        $manager = $this->getEntityManager();
                                        $entity->setMercadoPagoId($merchant_order_info['response']['payments'][0]['id']);
                                        $manager->persist($entity);
                                        $manager->flush();

                                        $output->writeln("Payment #" . $entity->getMercadoPagoId() . " set new MercadoPagoId");
                                        #$this->createLog("Payment #" . $entity->getMercadoPagoId() . " set new MercadoPagoId", $entity->getMercadoPagoId(), $entity);
                                    }
                                }
                            }
                        } else {
                            $result = $this->mp->get_payment((int)$entity->getMercadoPagoId());
                            // create log db entry
                            #$this->createLog($result, $entity->getMercadoPagoId(), $entity);
                            // make update
                            $this->updateStatus($currentEstado, $result, $entity, $output);
                        }


                    }
                } catch (\Exception $e) {
                    $output->writeln("Payment #" . $entity->getMercadoPagoId());
                    $output->writeln($e->getMessage());
                    #$this->createLog($e->getMessage(), $entity->getMercadoPagoId(), $entity);
                }
            }
        }


        $manager->flush();
        $manager->clear();
    }

    /**
     * @param $result
     * @param $entity
     * @param $output
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateStatus($currentEstado, $result, $entity, $output)
    {
        $manager = $this->getEntityManager();
        $in_production = false;
        $readyToRetrieve = false;
        $buildingPackage = false;
        $waitingEvent = false;


        if ($entity->getCompraArticulo()) {
            foreach ($entity->getCompraArticulo() as $compraArticulo) {
                if ($compraArticulo->getArticulo()->getProducto()->getInProduction()) {
                    $in_production = true;
                    break;
                }
            }
        }


        if ($entity->getCompraCombo()) {
            foreach ($entity->getCompraCombo() as $compraCombo) {
                $combo = $compraCombo->getCombo();
                if ($combo) {
                    foreach ($combo->getComboProducto() as $comboProducto) {
                        $producto = $comboProducto->getProducto()->getInProduction();
                        if ($producto) {
                            $in_production = true;
                            break;
                        }
                    }
                }
            }
        }


        $envio = $entity->getEnvio()->getName();
        if ($envio == 'Retiro por Colegiales (CABA)') {
            $readyToRetrieve = true;
        }
        if ($envio == 'Envío a todo el país por Correo Argentino') {
            $buildingPackage = true;
        }
        if ($envio == 'Retiro en el evento de presentación (en CABA)') {
            $waitingEvent = true;
        }


        if ($result['status'] == 200) {
            $estado = null;

            switch ($result['response']['collection']['status']) {
                case 'pending':
                case 'in_process':
                    $estado = $this->getEstadoByName('Pendiente de pago');
                    break;
                case 'approved':

                    $estado = $this->getEstadoByName('Procesando');
                    if ($readyToRetrieve) {
                        $estado = $this->getEstadoByName('Listo para retirar');
                    }
                    if ($buildingPackage) {
                        $estado = $this->getEstadoByName('Armando tu pedido');
                    }
                    if ($in_production) {
                        $estado = $this->getEstadoByName('Esperando producción');
                    }
                    if ($waitingEvent) {
                        $estado = $this->getEstadoByName('Esperando evento');
                    }


                    // Reduce stock when status is approved
                    $this->updateProductStock($entity);

                    break;
                case 'refunded':
                    $estado = $this->getEstadoByName('Pago Devuelto');
                    break;
                case 'rejected':
                case 'cancelled':
                    $estado = $this->getEstadoByName('Cancelado');
                    break;
            };


            if ($estado) {
                if ($estado->getName() != $currentEstado) {
                    $entity->setEstado($estado);
                    $nota = new Nota();
                    $nota->setOrder($entity);
                    $nota->setEstado($estado);
                    $nota->setMailUsuario($entity->getUserEmail());
                    $nota->setNota('IPN: Estado cambiado a : ' . $estado->getName());
                    $manager->persist($nota);
                    $manager->persist($entity);

                    $this->sendEmails($entity);

                    $output->writeln("Payment #" . $entity->getMercadoPagoId() . " status updated to: " . $estado->getName());
                    #$this->createLog("Payment #" . $entity->getMercadoPagoId() . " status updated to: " . $estado->getName(), $entity->getMercadoPagoId(), $entity);
                }
            }
        }
    }

    /**
     * @param $order
     */
    public function sendEmails($order)
    {
        $trigger = $this->getEmailService()->checkTriggers($order->getTienda(), $order->getEnvio(), $order->getEstado());
        if ($trigger) {
            $this->getEmailService()->sendEmail($trigger->getSubject(), 'tienda@abrecultura.com', $order->getUserEmail(), $trigger->getTemplate(), $order->getUserData()->first(), $order);
        }
    }

    /**
     * @param $name
     * @return null|object
     */
    public function getEstadoByName($name)
    {
        $estado = $this->getEntityManager()->getRepository(Estado::class)->findOneBy(
            ['name' => $name]
        );
        if (!$estado) {
            throw new NotFoundHttpException('Estado not found!');
        }

        return $estado;

    }

    /**
     * @param $log
     * @param $request
     * @param $order
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createLog($log, $request, $order)
    {
        $manager = $this->getEntityManager();

        $entity = new IPNLog();
        $entity->setLog(json_encode($log));
        $entity->setRequest($request);
        $entity->setOrder($order);

        $manager->persist($entity);
        $manager->flush();

    }

    /**
     * @param $order
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateProductStock($order)
    {
        if ($order) {
            if ($order->getCompraArticulo()) {
                foreach ($order->getCompraArticulo() as $compraArticulo) {
                    $articulo = $compraArticulo->getArticulo();
                    $stock = $articulo->getStock();
                    $newStock = 0;
                    if ($stock > 0) {
                        $newStock = $stock - 1;
                    }
                    $articulo->setStock($newStock);
                    $articulo->setTotalSold((int)$articulo->getTotalSold() + 1);
                    $this->getEntityManager()->persist($articulo);
                }
            }
            if ($order->getCompraCombo()) {
                foreach ($order->getCompraCombo() as $compraCombo) {
                    $combo = $compraCombo->getCombo();
                    if ($combo) {
                        foreach ($combo->getComboProducto() as $comboProducto) {
                            $producto = $comboProducto->getProducto();
                            foreach ($producto->getArticulo() as $articulo) {
                                $stock = $articulo->getStock();
                                $newStock = 0;
                                if ($stock > 0) {
                                    $newStock = $stock - 1;
                                }
                                $articulo->setStock($newStock);
                                $articulo->setTotalSold((int)$articulo->getTotalSold() + 1);
                                $this->getEntityManager()->persist($articulo);
                            }
                        }
                    }
                }
            }

            $this->getEntityManager()->flush();

        }
    }
}