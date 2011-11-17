<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\DeployBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('madalynn_deploy', 'array');

        $rootNode
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('host')->isRequired()->end()
                    ->scalarNode('port')->defaultValue(22)->end()
                    ->scalarNode('user')->isRequired()->end()
                    ->scalarNode('dir')->isRequired()->end()
                    ->scalarNode('exclude-from')->defaultValue('vendor/bundles/Madalynn/DeployBundle/Resources/config/rsync_exclude.txt')->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}