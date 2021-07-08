<?php declare(strict_types=1);

namespace Test\CmsBundle\Helper;

use ExEss\Bundle\CmsBundle\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \ExEss\Bundle\CmsBundle\CmsBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new \ExEss\Bundle\DoctrineExtensionsBundle\DoctrineExtensionsBundle(),
        ];
    }

    protected function getContainerBaseClass(): string
    {
        return Container::class;
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = __DIR__;

        $loader->load($confDir . '/Resources/config/*.yaml', 'glob');
        $loader->load($confDir . '/Resources/config/packages/*.yaml', 'glob');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir().'/config';
        // $routes->import($confDir.'/{routes}/'.$this->environment.'/*'.self::CONFIG_EXTS, 'glob');
        // $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, 'glob');
        // $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, 'glob');
    }
}
