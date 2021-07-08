<?php declare(strict_types=1);

namespace ExEss;

use ExEss\Bundle\CmsBundle\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
        ];
    }

    protected function getContainerBaseClass(): string
    {
        return Container::class;
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $loader->load($confDir . '/*.yaml', 'glob');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
    }
}
