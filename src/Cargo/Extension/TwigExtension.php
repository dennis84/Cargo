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
use Cargo\ApplicationEvent;
use Cargo\ApplicationEvents;

use Symfony\Bridge\Twig\Extension\RoutingExtension as TwigRoutingExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension as TwigTranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension as TwigFormExtension;

/**
 * TwigExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TwigExtension implements ExtensionInterface
{
    /**
     * Registers an extension.
     *
     * @param Application $app An appllication instance
     */
    public function register(Application $app)
    {
        $app['twig.paths'] = array();
        $app['twig'] = true;

        $app['twig.loader.array'] = $app->share(function($c) use ($app) {
            return new \Twig_Loader_Array(
                $app['templates']->toTwigCollection()
            );
        });

        $app['twig.loader.filesystem'] = $app->share(function ($c) use ($app) {
            return new \Twig_Loader_Filesystem(
                $app['twig.paths']
            );
        });

        $app['twig.loader'] = $app->share(function($c) use ($app) {
            return new \Twig_Loader_Chain(array(
                $app['twig.loader.array'],
                $app['twig.loader.filesystem']
            ));
        });

        $app['templating'] = $app->share(function($c) use($app) {
            $twig = new \Twig_Environment($app['twig.loader']);
            $twig->addGlobal('app', $app);
            if ($app->has('symfony_bridges')) {
                $twig->addExtension(new TwigCoreExtension(
                    $app['request'],
                    $app['routes']
                ));

                $twig->addExtension(new TwigTranslationExtension($app['translator']));
                $twig->addExtension(new TwigFormExtension(array('form_div_layout.html.twig')));
            }

            return $twig;
        });
    }
}