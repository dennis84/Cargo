<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Provider;

use Doctrine\Common\Annotations\AnnotationRegistry;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Cargo\Cargo;
use Cargo\Matcher\TemplateMatcher;
use Cargo\Matcher\RouteMatcher;
use Cargo\Template\TemplateCollection;
use Cargo\Template\TemplateResolver;
use Cargo\Template\TemplateCompiler;

/**
 * CargoServiceProvider.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class CargoServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $loader = $app['autoloader'];

        AnnotationRegistry::registerLoader(function($class) use ($loader) {
            $loader->loadClass($class);
            return class_exists($class, false);
        });

        AnnotationRegistry::registerFile(
            __DIR__ . '/../Annotation/CargoAnnotations.php'
        );

        $app['cargo.templates'] = $app->share(function () use ($app) {
            if ($data = apc_fetch('templates')) {
                echo 'apc yo';
                return $data;
            }

            return new TemplateCollection();
        });

        $app['cargo.matcher.route'] = $app->share(function () use ($app) {
            return new RouteMatcher($app['routes']);
        });

        $app['cargo.matcher.template'] = $app->share(function () use ($app) {
            return new TemplateMatcher();
        });

        $app['cargo.template.compiler'] = $app->share(function () use ($app) {
            return new TemplateCompiler(array(
                $app['cargo.matcher.route'],
                $app['cargo.matcher.template'],
            ));
        });

        $app['cargo.templating'] = $app->share(function () use ($app) {
            if (!isset($app['cargo.templating.engine'])) {
                throw new \InvalidArgumentException('You have to set one templating engine.');
            }

            if (!isset($app[$app['cargo.templating.engine']])) {
                throw new \InvalidArgumentException(
                    'The template engine was never registered in your application.'
                );
            }

            return $app[$app['cargo.templating.engine']];
        });

        if (isset($app['twig'])) {
            $app['twig.templates'] = $app->share(function () use ($app) {
                return $app['cargo.templates']->toTwigCollection();
            });
        }

        $app['cargo'] = $app->share(function () use ($app) {
            return new Cargo($app);
        });
    }
}