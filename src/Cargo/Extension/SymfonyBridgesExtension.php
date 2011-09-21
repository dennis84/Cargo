<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Extension;

use Cargo\Application;

/**
 * SymfonyBridgesExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class SymfonyBridgesExtension implements ExtensionInterface
{
    /**
     * Registers an extension.
     *
     * @param Application $app An appllication instance
     */
    public function register(Application $app)
    {
        $bridgePath = $app['symfony_bridges.class_path'];

        $twigPaths   = $app['twig.paths'];
        $twigPaths[] = $bridgePath . '/Symfony/Bridge/Twig/Resources/views/Form';
        $app['twig.paths'] = $twigPaths;

        $app['symfony_bridges'] = true;

        if ($app->has('symfony_bridges.class_path')) {
            $app['autoloader']->registerNamespace('Symfony\\Bridge', $bridgePath);
        }
    }
}
