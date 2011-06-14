<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\DeployBundle\Server;

class ServerManager
{
    protected $servers;
    
    public function __construct()
    {
        $this->servers = array();
    }

    public function add($key, Server $server)
    {
        if (true === array_key_exists($key, $this->servers)) {
            throw new \InvalidArgumentException(sprintf('The key "%s" is already registered', $key));
        }
        
        $this->servers[$key] = $server;
    }
    
    public function get($key) 
    {
        if (false === array_key_exists($key, $this->servers)) {
            return null;
        }
        
        return $this->servers[$key];
    }
    
    public function has($key)
    {
        return array_key_exists($key, $this->servers);
    }
}