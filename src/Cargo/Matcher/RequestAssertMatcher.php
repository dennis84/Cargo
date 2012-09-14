<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Matcher;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Cargo\Template\TemplateInterface;

/** 
 * RequestAssertMatcher.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class RequestAssertMatcher implements MatcherInterface
{
    /**
     * @var RouteCollection
     */
    protected $routes;

    /**
     * Constructor.
     *
     * @param RouteCollection
     */
    public function __construct(RouteCollection $routes, EventDispatcherInterface $dispatcher)
    {
        $this->routes = $routes;
        $dispatcher->addListener('kernel.request', array($this, 'onKernelRequest'));
    }

    /**
     * {@inheritDoc}
     */
    public function match(TemplateInterface $template)
    {
        if ($template->hasAnnotation('Cargo\Annotation\Route') && $template->hasAnnotation('Cargo\Annotation\RequestAssert')) {
            $routeAnnotation = $template->getAnnotation('Cargo\Annotation\Route');
            $assertAnnotation = $template->getAnnotation('Cargo\Annotation\RequestAssert');

            $r = $this->routes->get($routeAnnotation->getName());
            $r->addOptions(array(
                'request_assertions' => $assertAnnotation->getAssertions(),
            ));
        }
    }

    /**
     * Checks if the reuest matches the assertions of current route.
     * If the route has assertions and does not match to the request attributes
     * then an exceptoin will be thrown.
     *
     * @param GetResponseEvent $event The kernel request event
     *
     * @throws RuntimeException If the assertions does not match to the request attributes
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $route   = $this->routes->get($request->get('_route'));
        $options = $route->getOptions();

        if (isset($options['request_assertions'])) {
            foreach ($options['request_assertions'] as $key => $assertion) {
                if ($value = $request->get($key)) {
                    $match = false;

                    if (strpos($assertion, '|')) {
                        $assertion = explode('|', $assertion);
                        if (in_array($value, $assertion)) {
                            $match = true;
                        }
                    } else {
                        if ($value == $assertion) {
                            $match = true;
                        }
                    }

                    if (false === $match) {
                        throw new \RuntimeException('This request is not allowed to access this route.');
                    }
                }
            }
        }
    }
}
