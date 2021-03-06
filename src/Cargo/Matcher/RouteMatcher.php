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

use Symfony\Component\Routing\RouteCollection;
use Silex\Route;
use Cargo\Template\TemplateInterface;

/** 
 * RouteMatcher.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class RouteMatcher implements MatcherInterface
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
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * {@inheritDoc}
     */
    public function match(TemplateInterface $template)
    {
        // Dont forget to got through all annotations, because one template can have
        // many routes.
        foreach ($template->getAnnotations() as $annotation) {
            if ($annotation instanceof \Cargo\Annotation\Route) {
                $defaults = array(
                    '_template'   => $template,
                    '_controller' => function () {
                        return null;
                    }
                );

                $route = new Route(
                    $annotation->getPattern(),
                    array_merge($annotation->getDefaults(), $defaults)
                );

                $this->routes->add($annotation->getName(), $route);
                $template->addRoute($route);
            }
        }
    }
}
