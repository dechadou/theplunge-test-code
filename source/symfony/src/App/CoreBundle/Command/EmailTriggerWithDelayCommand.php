<?php

namespace App\CoreBundle\Command;

use App\CoreBundle\Entity\IPNLog;
use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\Orders\Nota;
use App\CoreBundle\Entity\Orders\Order;
use Carbon\Carbon;
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
class EmailTriggerWithDelayCommand extends ContainerAwareCommand
{

    private $mp;

    protected function configure()
    {
        $this
            ->setName('abre:email-triggers')
            ->setDescription('Send an email that has a delay setup');
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

        foreach ($manager->getRepository(Order::class)->findBy([], ['id' => 'DESC']) as $entity) {

            $store = $entity->getTienda();
            $this->mp = new MP($store->getMercadopagoKey(), $store->getMercadopagoSecret());
            $today = Carbon::now(new \DateTimeZone('America/Argentina/Buenos_Aires'));
            $orderDate = new Carbon($entity->getDate()->format('Y-m-d H:i:s'), 'America/Argentina/Buenos_Aires');
            $delay = $orderDate->diffInHours($today, false);

            $this->sendEmails($entity, $delay);
        }


        $manager->flush();
        $manager->clear();
    }

    /**
     * @param $order
     */
    public function sendEmails($order, $delay)
    {
        $trigger = $this->getEmailService()->checkTriggers($order->getTienda(), $order->getEnvio(), $order->getEstado(), $delay);
        if ($trigger) {
            $this->getEmailService()->sendEmail($trigger->getSubject(), 'tienda@abrecultura.com', $order->getUserEmail(), $trigger->getTemplate(), $order->getUserData()->first(), $order);
        }
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
}