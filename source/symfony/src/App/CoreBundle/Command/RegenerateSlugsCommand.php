<?php

namespace App\CoreBundle\Command;

use App\CoreBundle\Entity\Articulo;
use App\CoreBundle\Entity\Atributo;
use App\CoreBundle\Entity\Orders\Envio;
use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\TipoProducto;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RegenerateSlugs
 * @package App\Command
 */
class RegenerateSlugsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('abre:regenerate-slugs')
            ->setDescription('Regenerate the slugs for all Performer and Venue entities.');
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getEntityManager();

        // Change the next line by your classes
        foreach ([Estado::class, TipoProducto::class, Atributo::class, Envio::class, Articulo::class] as $class) {
            foreach ($manager->getRepository($class)->findAll() as $entity) {
                $entity->setSlug(null);
                //$entity->slug = null; // If you use public properties
            }

            $manager->flush();
            $manager->clear();

            $output->writeln("Slugs of \"$class\" updated.");
        }
    }
}