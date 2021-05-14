<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Ddd\DependencyInjection;

use Ecotone\SymfonyBundle\DepedencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class DddEcotoneExtension extends Extension implements PrependExtensionInterface
{
    private FileLocator $fileLocator;

    public function __construct()
    {
        $this->fileLocator = new FileLocator(__DIR__.'/../Resources/config');
    }

    public function prepend(ContainerBuilder $containerBuilder): void
    {
        $configuration = $this->processConfiguration(new Configuration(), $containerBuilder->getExtensionConfig('ecotone'));

        $containerBuilder->prependExtensionConfig('ecotone', [
            'namespaces' => array_merge($configuration['namespaces'], [
                'Tuzex\Bundle\Ddd\Messaging\Interceptor',
            ]),
        ]);
    }

    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $loader = new XmlFileLoader($containerBuilder, $this->fileLocator);
        $loader->load('services.xml');
    }
}
