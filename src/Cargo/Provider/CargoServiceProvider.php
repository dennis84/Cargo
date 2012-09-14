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
use Cargo\Template\TemplateCollection;
use Cargo\Template\TemplateResolver;
use Cargo\Template\TemplateCompiler;
use Cargo\Template\TemplateBuilder;

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
        
        AnnotationRegistry::registerAutoloadNamespace('Cargo\Annotation', __DIR__.'/../..');

        // Registers the cargo route collection class.
        $app['cargo.routes'] = $app->share(function () {
            return new RouteCollection();
        });

        // Registers the cargo template collection.
        $app['cargo.templates'] = $app->share(function () use ($app) {
            return new TemplateCollection();
        });

        $app['cargo.template.builder'] = $app->share(function () use ($app) {
            return new TemplateBuilder($app['cargo.template.compiler']);
        });

        // Registers the template compiler service.
        $app['cargo.template.compiler'] = $app->share(function () use ($app) {
            return new TemplateCompiler(array(
                new \Cargo\Matcher\RouteMatcher($app['cargo.routes']),
                new \Cargo\Matcher\RequestAssertMatcher($app['routes'], $app['cargo.routes'], $app['dispatcher']),
                new \Cargo\Matcher\TemplateMatcher(),
                new \Cargo\Matcher\ErrorMatcher($app),
                new \Cargo\Matcher\SecurityMatcher($app),
            ));
        });
 
        $app['cargo.cache_loader'] = $app->share(function () use ($app) {
            return new \Cargo\Cache\Loader($app['cargo.cache_dir'], $app['debug'], array(
                new \Cargo\Cache\RouteCacheHandler($app),
                new \Cargo\Cache\RequestAssertCacheHandler($app),
                new \Cargo\Cache\TemplateCacheHandler($app),
                new \Cargo\Cache\ErrorCacheHandler($app),
                new \Cargo\Cache\SecurityCacheHandler($app),
            ));
        });

        // Registers the cargo main application.
        $app['cargo'] = $app->share(function () use ($app) {
            return new Cargo($app);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {
        // Security provider fix. The security listener must be registered before the route listener.
        if (isset($app['security'])) {
            $app['dispatcher']->addListener('kernel.request', array($app['security.firewall'], 'onKernelRequest'), 64);
        }

        $templates = $app['twig.templates'];

        foreach ($app['cargo']->getThemes() as $theme) {
            $templates = array_merge($templates, $theme->getTemplates());
        }

        $app['twig.templates'] = $templates;

        foreach ($app['cargo.routes'] as $name => $route) {
            $template      = $route->getDefault('_template');
            $compiledRoute = $route->compile();

            $app->get($route->getPattern(), function () use ($app, $compiledRoute, $template) {
                return $app['twig']->render($template->getName(), $compiledRoute->getVariables());
            })
            ->bind($name)
            ->setOptions($route->getOptions());
        }
    }
}
