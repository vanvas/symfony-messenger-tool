<?php
declare(strict_types=1);

namespace Vim\MessengerTool\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Vim\MessengerTool\Service\LockService;

class MessengerToolExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container,  new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
        $config = $this->processConfiguration(new Configuration(), $configs);

        if ($lockStore = $config['lock_pool']) {
            $container
                ->getDefinition(LockService::class)
                ->setArgument('$lockStore', new Reference($lockStore))
            ;
        }
    }
}
