<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // Framework
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            // Doctrine
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            // Sonata
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            // Other
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            // MediaMonks
            new MediaMonks\RestApiBundle\MediaMonksRestApiBundle(),
            // App
            new App\CoreBundle\AppCoreBundle(),
            new App\FrontEndBundle\AppFrontEndBundle(),
            new App\ApiBundle\AppApiBundle(),
            new App\AdminBundle\AppAdminBundle(),

            //Other
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new \Pix\SortableBehaviorBundle\PixSortableBehaviorBundle(),
            new Maxmind\Bundle\GeoipBundle\MaxmindGeoipBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),

            // MediaBundle
            new MediaMonks\SonataMediaBundle\MediaMonksSonataMediaBundle(),
            new Oneup\FlysystemBundle\OneupFlysystemBundle(),

        ];

        if (in_array($this->getEnvironment(), [Environment::ENV_LOCAL, Environment::ENV_TEST, Environment::ENV_DEVELOPMENT], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return $this->getRootDir() . '/../var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return $this->getRootDir() . '/../var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
