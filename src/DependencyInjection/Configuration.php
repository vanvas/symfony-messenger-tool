<?php
declare(strict_types=1);

namespace Vim\MessengerTool\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('messenger_tool');

        $treeBuilder
            ->getRootNode()
                ->children()
                    ->scalarNode('lock_pool')->defaultNull()->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
