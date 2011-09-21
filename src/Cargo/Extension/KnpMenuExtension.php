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
use Knp\Menu\Renderer\ListRenderer;

/**
 * KnpMenuExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class KnpMenuExtension implements ExtensionInterface
{
    /**
     * Registers an extension.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        if ($app->has('knp_menu.class_path')) {
            $app['autoloader']->registerNamespace('Knp\\Menu', $app['knp_menu.class_path']);
        }

        $app['knp_menu.factory'] = $app->share(function () use ($app) {
            return new KnpMenuRouterAwareMenuFactory(
                $app['url_generator']
            );
        });

        $app['knp_menu.renderer.list'] = $app->share(function () use ($app){
            return new ListRenderer($app['charset']);
        });
    }
}