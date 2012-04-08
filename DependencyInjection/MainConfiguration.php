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

class MainConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('madalynn_plum', 'array');

        $rootNode
            ->children()
                ->arrayNode('options')
                    ->useAttributeAsKey('name')
                    ->prototype('variable')
                    ->end()
                ->end()
                ->arrayNode('deployers')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')
                        ->validate()
                            ->ifTrue(function ($v) {
                                $class = new \ReflectionClass($v);

                                return !$class->implementsInterface('Plum\\Deployer\\DeployerInterface');
                            })
                            ->thenInvalid('The deployer %s must implements the Plum\Deployer\DeployerInterface')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('servers_file')->defaultValue('%kernel.root_dir%/config/deployment.yml')->end()
            ->end();

        return $treeBuilder;
    }
}