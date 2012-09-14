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
     * @var RouteCollection
     */
    protected $cargoRoutes;

    /**
     * Constructor.
     *
     * @param RouteCollection
     */
    public function __construct(RouteCollection $routes, RouteCollection $cargoRoutes, EventDispatcherInterface $dispatcher)
    {
        $this->cargoRoutes = $cargoRoutes;
    }

    /**
     * {@inheritDoc}
     */
    public function match(TemplateInterface $template)
    {
        if ($template->hasAnnotation('Cargo\Annotation\Route') && $template->hasAnnotation('Cargo\Annotation\RequestAssert')) {
            $routeAnnotation = $template->getAnnotation('Cargo\Annotation\Route');
            $assertAnnotation = $template->getAnnotation('Cargo\Annotation\RequestAssert');
            $assertions = $assertAnnotation->getAssertions();

            $route = $this->cargoRoutes->get($routeAnnotation->getName());
            $route->setOption('request_assertions', $assertions);
            $route->before(function ($request, $app) use ($assertions) {
                foreach ($assertions as $key => $assertion) {
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
                    } else {
                        throw new \RuntimeException('This request is not allowed to access this route.');
                    }
                }
            });
        }
    }
}
