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

use Cargo\Extension\ExtensionInterface;
use Cargo\Template\TemplateBuilder;
use Cargo\Template\Event\TemplateRouteListener;
use Cargo\Routing\RedirectableUrlMatcher;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ExceptionInterface as RoutingException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Application.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Application extends ApplicationContainer implements EventSubscriberInterface
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $app = $this;

        $this['request.http_port'] = 80;
        $this['request.https_port'] = 443;
        $this['debug'] = false;
        $this['charset'] = 'UTF-8';
        $this['template.not_found'] = 'notFound';

        $this['autoloader'] = $this->share(function () {
            $loader = new \Symfony\Component\ClassLoader\UniversalClassLoader();
            $loader->register();

            return $loader;
        });

        $this['dispatcher'] = $this->share(function($c) use ($app) {
            $dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
            $dispatcher->addSubscriber($app);

            return $dispatcher;
        });

        $this['templates'] = $this->share(function($c) {
            return new \Cargo\Template\Collection\TemplateCollection();
        });

        $this['routes'] = $this->share(function($c) {
            return new \Symfony\Component\Routing\RouteCollection();
        });

        $this['resolver'] = $this->share(function($c) {
            return new \Cargo\Template\TemplateResolver($c['templates']);
        });
    }

    /**
     * Registers an extension.
     *
     * @param ExtensionInterface $extension An ExtensionInterface instance
     * @param array              $values    An array of values that customizes the extension
     */
    public function register(ExtensionInterface $extension, array $values = array())
    {
        foreach ($values as $key => $value) {
            $this[$key] = $value;
        }

        $extension->register($this);
    }

    /**
     * Registers Theme paths.
     *
     * @param array $themes The themes to register
     */
    public function registerThemes(array $themes = array())
    {
        $builder = new TemplateBuilder(
            $this['templates'],
            $this['dispatcher']
        );

        $builder
            ->addEventSubscriber(
                new TemplateRouteListener($this['routes'])
            );

        foreach ($themes as $name => $dir) {
            $builder->createTemplatesFromDir($dir);
        }
    }

    /**
     * Runs the application.
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function run(Request $request)
    {
        $this['request'] = $request;
        $event = new ApplicationEvent($request);
        $this['dispatcher']->dispatch(ApplicationEvents::REQUEST, $event);
        $this['dispatcher']->dispatch(ApplicationEvents::RESPONSE, $event);

        return $event->getResponse();
    }

    /**
     * On apllication request.
     *
     * @param ApplicationEvent $event The application event
     */
    public function onApplicationRequest(ApplicationEvent $event)
    {
        $request = $event->getRequest();

        $this['request_context'] = new RequestContext(
            $this['request']->getBaseUrl(),
            $this['request']->getMethod(),
            $this['request']->getHost(),
            $this['request']->getScheme(),
            !$this['request']->isSecure() ? $this['request']->getPort() : $this['request.http_port'],
            $this['request']->isSecure() ? $this['request']->getPort() : $this['request.https_port']
        );

        $matcher = new RedirectableUrlMatcher($this['routes'], $this['request_context']);

        try {
            $attributes = $matcher->match($this['request']->getPathInfo());
            $template   = $this['resolver']->resolveByRouteName($attributes['_route']);

            $this['request']->attributes->add($attributes);
        } catch (RoutingException $error) {
            $template = $this['resolver']->resolveByName($this['template.not_found']);
        }

        $event->setTemplate($template);
    }

    /**
     * On apllication response.
     *
     * @param ApplicationEvent $event The application event
     */
    public function onApplicationResponse(ApplicationEvent $event)
    {
        if (!$this->has('templating')) {
            throw new \RuntimeException('The application must have a templating engine');
        }

        $output = $this['templating']->render(
            $event->getTemplate()->getName(),
            $event->getRequest()->attributes->all()
        );

        $event->setResponse(
            new Response($output)
        );
    }

    /**
     * The subscribed events.
     *
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return array(
            ApplicationEvents::REQUEST  => 'onApplicationRequest',
            ApplicationEvents::RESPONSE => 'onApplicationResponse',
        );
    }
}