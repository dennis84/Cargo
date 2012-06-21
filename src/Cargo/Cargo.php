<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo;

use Silex\Application;
use Cargo\Template\TemplateBuilder;
use Cargo\Template\Event\RouteListener;
use Cargo\Template\Theme;

/**
 * Cargo.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Cargo
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $themes = array();

    /**
     * Constructor.
     *
     * @param Application $app The silex application
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Registers the themes if there is no defined cache path then the caching
     * will not works. If the caching is active then the files will loaded from
     * the cargo cache loader.
     *
     * @param array $themes The themes to register
     */
    public function registerThemes(array $themes = array())
    {
        $templateBuilder = $this->app['cargo.template.builder'];

        if (!isset($this->app['cargo.cache_dir'])) {
            foreach ($themes as $name => $dir) {
                $theme = new Theme($name, $dir);
                $templateBuilder->createTemplatesFromTheme($theme);
                $this->themes[] = $theme;
            }

            return;
        }

        foreach ($themes as $name => $dir) {
            $theme = new Theme($name, $dir);
            $this->app['cargo.cache_loader']->load($theme, function ($theme) use ($templateBuilder) {
                $templateBuilder->createTemplatesFromTheme($theme);
            });

            $this->themes[] = $theme;
        }
    }

    /**
     * Gets the registered themes.
     *
     * @return array
     */
    public function getThemes()
    {
        return $this->themes;
    }
}
