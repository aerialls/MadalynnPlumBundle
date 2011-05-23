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

class DeployerManager
{
    protected $servers;
    
    public function __construct(array $servers)
    {
        $this->servers = $servers;
    }
}
