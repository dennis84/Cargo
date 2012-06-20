<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Cache;

use Symfony\Component\Config\ConfigCache;
use Cargo\Template\Theme;

/**
 * Loader.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Loader
{
    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @var boolean
     */
    protected $debug;

    /**
     * @var array
     */
    protected $handlers = array();

    /**
     * Constructor.
     *
     * @param string  $cacheDir The cache dir
     * @param boolean $debug    Debug mode or not
     * @param array   $handlers The cache handlers
     */
    public function __construct($cacheDir, $debug, $handlers)
    {
        $this->cacheDir = $cacheDir;
        $this->debug    = $debug;
        $this->handlers = $handlers;
    }

    /**
     * Loads the cache. You have to pass a initializing closure function to 
     * build a valid environment when the cache is not fresh.
     *
     * @param Theme   $theme       The theme object
     * @param Closure $initializer The non freshnes initializer
     */
    public function load(Theme $theme, \Closure $initializer)
    {
        foreach ($this->handlers as $handler) {
            $cache = new ConfigCache($this->cacheDir.'/'.$theme->getName().'_'.$handler->getName().'.php', $this->debug);
            if (!$cache->isFresh()) {
                $initializer($theme);
                $handler->onNonFresh($theme, $cache);
            } else {
                $handler->onFresh($theme, $cache);
            }
        }
    }
}
