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
use Symfony\Component\Routing\RouteCollection;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Cargo\Cargo;
use Cargo\Matcher\TemplateMatcher;
use Cargo\Matcher\RouteMatcher;
use Cargo\Matcher\ControllerMatcher;
use Cargo\Template\TemplateCollection;
use Cargo\Template\TemplateResolver;
use Cargo\Template\TemplateCompiler;

/** 
 *  CargoServiceProvider.
 *
 *  @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class CargoServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        // Checks if the twig extension is installed else throws an exception.
        if (!isset($app['twig'])) {
            throw new \Exception('You must enable twig.');
        }

        // Registers the cargo annotations.
        $loader = $app['autoloader'];

        AnnotationRegistry::registerLoader(function($class) use ($loader) {
            $loader->loadClass($class);
            return class_exists($class, false);
        });

        AnnotationRegistry::registerFile(
            __DIR__ . '/../Annotation/CargoAnnotations.php'
        );

        // Registers the cargo route collection class.
        $app['cargo.routes'] = $app->share(function () {
            return new RouteCollection();
        });

        // Registers the cargo template collection.
        $app['cargo.templates'] = $app->share(function () use ($app) {
            return new TemplateCollection();
        });

        // Registers the template annotation matchers, at the moment is only 
        // the template and route annotations possible.
        $app['cargo.matcher.route'] = $app->share(function () use ($app) {
            return new RouteMatcher($app['cargo.routes']);
        });

        $app['cargo.matcher.template'] = $app->share(function () use ($app) {
            return new TemplateMatcher();
        });

        // Registers the template compiler service.
        $app['cargo.template.compiler'] = $app->share(function () use ($app) {
            return new TemplateCompiler(array(
                $app['cargo.matcher.route'],
                $app['cargo.matcher.template'],
            ));
        });

        // Sets all cargo templates to the twig template collection.
        $app['twig.templates'] = $app->share(function () use ($app) {
            return $app['cargo.templates']->toTwigCollection();
        });
 
        $app['cargo.cache_loader'] = $app->share(function () use ($app) {
          return new \Cargo\Cache\Loader($app['cargo.cache_dir'], $app['debug'], array(
            new \Cargo\Cache\RouteCacheHandler($app['cargo.routes'], $app),
            new \Cargo\Cache\TemplateCacheHandler($app['cargo.templates'], $app),
          ));
        });

        // Registers the cargo main application.
        $app['cargo'] = $app->share(function () use ($app) {
            return new Cargo($app);
        });
    }
}
