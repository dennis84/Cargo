<?php

namespace Cargo\Matcher;

use Symfony\Component\Routing\Route;

class RouteMatcher
{
    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    public function match($template, $annotations)
    {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof \Cargo\Annotation\Route) {
                $defaults = array('_template' => $template);

                $route = new Route(
                    $annotation->getPattern(),
                    array_merge($annotation->getDefaults(), $defaults)
                );

                $this->routes->add(
                    $annotation->getName(),
                    $route
                );

                $template->addRoute($route);
            }
        }
    }
}