<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2012 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\Bundle\PlumBundle;

use Madalynn\Bundle\PlumBundle\Loader\LoaderInterface;
use Plum\Plum as BasePlum;

class Plum extends BasePlum
{
    /**
     * Server loader
     *
     * @var Madalynn\Bundle\PlumBundle\Loader\LoaderInterface
     */
    protected $loader;

    /**
     * Constructor
     *
     * @param LoaderInterface $loader The loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Loads servers from an YAML file
     *
     * @param string $filename The filaname
     */
    public function loadServers($filename)
    {
        $this->setServers($this->loader->load($filename));
    }
}