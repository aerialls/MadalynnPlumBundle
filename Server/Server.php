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

class Server
{
    protected $host;
    protected $port;
    protected $user;
    protected $dir;
    protected $exclude_file;

    public function __construct($host, $user, $dir, $port = 22, $exclude_from)
    {
        if (substr($dir, -1) != '/') {
            $dir .= '/';
        }

        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->dir = $dir;
        $this->exclude_from = $exclude_from;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getDir()
    {
        return $this->dir;
    }
    
    public function getExcludeFrom()
    {
        return $this->exclude_from;
    }

    public function getSSHInformations()
    {
        return sprintf('"ssh -p%d"', $this->port);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getLoginInformations()
    {
        return sprintf('%s@%s:%s', $this->user, $this->host, $this->dir);
    }
    
    
    public function getRsyncExclude()
    {
        if ($this->getExcludeFrom()) {
            return sprintf('--exclude-from \'%s\'', $this->getExcludeFrom());
        }
        return '';
    }
}
