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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

/**
 * TwigCoreExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TwigCoreExtension extends \Twig_Extension
{
    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var Symfony\Component\Routing\RouteCollection
     */
    protected $routes;

    /**
     * Constructor.
     *
     * @param Request         $request The request
     * @param RouteCollection $routes  The route collection
     */
    public function __construct(Request $request, RouteCollection $routes)
    {
        $this->request = $request;
        $this->routes  = $routes;
    }

    /**
     * Gets twig functions.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'path' => new \Twig_Function_Method($this, 'getPath', array('is_safe' => array('html'))),
        );
    }

    /**
     * Gets the path of a route by name.
     *
     * @param string $name The route name
     *
     * @return string
     */
    public function getPath($name = null)
    {
        $route = $this->routes->get($name);

        if (null === $route) {
            throw new \InvalidArgumentException(sprintf(
                'The route with name "" does not exists.', $name
            ));
        }

        return $this->request->getUriForPath($route->getPattern());
    }

    /**
     * Gets the extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'cargo';
    }
}