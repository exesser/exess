<?php declare(strict_types=1);

namespace ExEss\Bundle\DoctrineExtensionsBundle\DependencyInjection;

use ExEss\Bundle\DoctrineExtensionsBundle\Type;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DoctrineExtensionsExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/Config')
        );
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (!isset($bundles['DoctrineBundle'])) {
            throw new \LogicException('DoctrineExtensionsBundle without a loaded DoctrineBundle makes no sense');
        }

        $container->prependExtensionConfig(
            'doctrine',
            [
                'dbal' => [
                    'types' => [
                        'datetime_immutable_microseconds' => Type\DateTimeImmutableMicroseconds::class,
                        'enum_audit_operation' => Type\AuditOperationEnumType::class,
                    ]
                ]
            ]
        );
    }
}
