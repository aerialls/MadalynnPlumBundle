<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\Bundle\DeployBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

use Plum\Server\Server;

class MadalynnDeployExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $plum = $container->getDefinition('madalynn.plum');

        $deployers = $config['deployers'];
        if (0 === count($deployers)) {
            throw new \RuntimeException('You must add at least one deployer.');
        }

        foreach($deployers as $deployer) {
            $obj  = new $deployer();
            $name = 'plum.deployer.' . $obj->getName();

            $def = $container->register($name, $deployer);
            $def->setPublic(false);

            $plum->addMethodCall('registerDeployer', array($container->findDefinition($name)));
        }

        $servers = $config['servers'];
        foreach($servers as $server => $value) {
            $name = 'plum.server.' . $server;

            $def = $container->register($name, 'Plum\\Server\\Server');
            $def->addArgument($value['host']);
            $def->addArgument($value['user']);
            $def->addArgument($value['dir']);
            $def->addArgument($value['port']);
            $def->setPublic(false);

            $plum->addMethodCall('addServer', array($server, $container->findDefinition($name)));

            // Server options
            $container->setParameter('plum.server.' . $server . '.options', $value['options']);
        }
    }

    public function getNamespace()
    {
        return 'http://www.madalynn.eu/schema/dic/deploy';
    }

    public function getAlias()
    {
        return 'madalynn_deploy';
    }
}