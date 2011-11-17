<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\DeployBundle;

use Madalynn\DeployBundle\Server\ServerManager;
use Madalynn\DeployBundle\Server\Server;

class DeployerManager
{
    protected $serverManager;

    public function __construct(array $config)
    {
        $this->serverManager = new ServerManager();

        foreach ($config as $key => $server){
            $tmp = new Server($server['host'], $server['user'], $server['dir'], $server['port'], $server['exclude-from']);
            $this->addServer($key, $tmp);
        }
    }

    public function addServer($key, $server)
    {
        $this->serverManager->add($key, $server);
    }

    public function getServer($key)
    {
        return $this->serverManager->get($key);
    }

    public function hasServer($key)
    {
        return $this->serverManager->has($key);
    }
}
