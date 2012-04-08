<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2012 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\Bundle\PlumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ServerConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('servers', 'array');

        $rootNode
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->children()
                ->scalarNode('host')->isRequired()->end()
                ->scalarNode('port')->defaultValue(22)->end()
                ->scalarNode('user')->isRequired()->end()
                ->scalarNode('password')->defaultValue(null)->end()
                ->scalarNode('dir')->isRequired()->end()
                ->arrayNode('options')
                    ->useAttributeAsKey('name')
                    ->prototype('variable')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}