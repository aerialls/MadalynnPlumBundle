<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2012 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\Bundle\PlumBundle\Loader;

use Plum\Server\Server;
use Madalynn\Bundle\PlumBundle\DependencyInjection\ServerConfiguration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class ServerLoader implements LoaderInterface
{
    /**
     * The container
     *
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load($filename)
    {
        // The aim is not to throw an exception if the file is not found
        if (false === file_exists($filename)) {
            return array();
        }

        $yaml          = Yaml::parse($filename);
        $config        = $this->container->getParameterBag()->resolveValue($yaml);
        $processor     = new Processor();
        $configuration = new ServerConfiguration();

        $servers = $processor->processConfiguration($configuration, $config);

        $list = array();
        foreach ($servers as $name => $s) {
            $list[$name] = new Server(
                    $s['host'],
                    $s['user'],
                    $s['dir'],
                    $s['password'],
                    $s['port'],
                    $s['options']);
        }

        return $list;
    }
}