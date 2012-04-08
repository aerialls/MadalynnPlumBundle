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

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

use Plum\Server\Server;

/**
 * The Madalynn Plum extension
 */
class MadalynnPlumExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new MainConfiguration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $plum = $container->getDefinition('madalynn.plum');

        // Options
        $plum->addMethodCall('setOptions', array($config['options']));

        // Deployers
        $deployers = $config['deployers'];
        if (0 === count($deployers)) {
            throw new \RuntimeException('You must add at least one deployer.');
        }

        foreach ($deployers as $deployer) {
            $obj  = new $deployer();
            $name = 'plum.deployer.'.$obj->getName();

            $container->register($name, $deployer);

            $plum->addMethodCall('registerDeployer', array($container->findDefinition($name)));
        }

        // Servers
        $plum->addMethodCall('loadServers', array($config['servers_file']));
    }

    public function getNamespace()
    {
        return 'http://www.madalynn.eu/schema/dic/plum';
    }

    public function getAlias()
    {
        return 'madalynn_plum';
    }
}