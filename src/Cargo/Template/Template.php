<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Template;

use Cargo\Template\TemplateCompiler;

/**
 * Template.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Template implements TemplateInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @var array
     */
    protected $eventSubscriberServices = array();

    /**
     * @var boolean
     */
    protected $compiled = false;

    /**
     * Constructor.
     *
     * @param string $path The template path
     */
    public function __construct($path)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(
                sprintf('the template file "%s" does not exists', $path)
            );
        }

        $this->path = $path;
    }

    /**
     * Sets the name.
     *
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the path.
     *
     * @param string $path The template path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Gets the path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Adds a template route.
     *
     * @param TemplateRoute $route The template route
     */
    public function addRoute(TemplateRoute $route)
    {
        $this->routes[$route->getName()] = $route;
    }

    /**
     * Sets template routes.
     *
     * @param array $routes The template routes
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Returns true when template has routes.
     *
     * @return boolean
     */
    public function hasRoutes()
    {
        return count($this->routes) > 0;
    }

    /**
     * Gets the template routes.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Adds a template event subscriber service.
     *
     * @param string $id        The service id
     * @param string $class     The service class
     * @param array  $arguments The service arguments
     */
    public function addEventSubscriberService($id, $class = null, $arguments = null)
    {
        $this->eventSubscriberServices[$id] = array('class' => $class, 'arguments' => $arguments);
    }

    /**
     * Returns true when template has event subscriber service.
     *
     * @return boolean
     */
    public function hasEventSubscriberServices()
    {
        return count($this->eventSubscriberServices) > 0;
    }

    /**
     * Gets the template event subscriber services.
     *
     * @return array
     */
    public function getEventSubscriberServices()
    {
        return $this->eventSubscriberServices;
    }

    /**
     * Returns true when template is compiled.
     *
     * @return boolean
     */
    public function isCompiled()
    {
        return $this->compiled;
    }

    /**
     * Sets the template is compiled.
     */
    public function setCompiled()
    {
        $this->compiled = true;
    }

    /**
     * Compile the template.
     */
    public function compile()
    {
        $compiler = new TemplateCompiler();
        $compiler->compile($this);
    }

    /**
     * Gets the template file from path.
     *
     * @return TemplateFile
     */
    public function getFile()
    {
        return new TemplateFile($this->path);
    }
}