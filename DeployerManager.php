<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2011 Julien Brochet <mewt.fr@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\DeployBundle;

use Madalynn\DeployBundle\Server\ServerManager;

class DeployerManager
{
    protected $serverManager;
    
    public function __construct(array $servers)
    {
        $this->serverManager = new ServerManager();
        
        foreach ($servers as $key => $server) {
            $this->add($key, $server);
        }
    }
    
    public function add($key, $server)
    {
        $this->serverManager->add($key, $server);
    }
    
    public function get($key, $server)
    {
        return $this->serverManager->get($key, $server);
    }
    
    public function has($key)
    {
        return $this->serverManager->has($key);
    }
}
