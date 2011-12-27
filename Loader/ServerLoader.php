<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\Bundle\PlumBundle\Loader;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

use Madalynn\Bundle\PlumBundle\DependencyInjection\ServerConfiguration;

use Plum\Server\Server;

class ServerLoader implements LoaderInterface
{
    /**
     * {@inheritDoc}
     */
    public function load($filename)
    {
        // The aim is not to throw an exception if the file is not found
        if (!file_exists($filename)) {
            return null;
        }

        $configs = Yaml::parse($filename);
        $processor = new Processor();
        $configuration = new ServerConfiguration();

        $servers = $processor->processConfiguration($configuration, $configs);

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