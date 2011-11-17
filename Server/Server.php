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
    protected $excludeFromPath;

    public function __construct($host, $user, $dir, $port, $excludeFromPath)
    {
        if (substr($dir, -1) != '/') {
            $dir .= '/';
        }

        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->dir = $dir;
        $this->excludeFromPath = $excludeFromPath;
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

    public function getExcludeFromPath()
    {
        return $this->excludeFromPath;
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
        if ($this->excludeFromPath) {
            return sprintf('--exclude-from \'%s\'', $this->excludeFromPath);
        }

        return '';
    }
}
