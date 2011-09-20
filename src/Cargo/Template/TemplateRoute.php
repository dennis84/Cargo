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

/**
 * Application.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateRoute
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * Constructor.
     *
     * @param string $name     The route name
     * @param string $pattern  The route pattern
     * @param array  $defaults The route defaults
     */
    public function __construct($name, $pattern, $defaults = array())
    {
        $this->name     = $name;
        $this->pattern  = $pattern;
        $this->defaults = $defaults;
    }

    /**
     * Sets the name.
     *
     * @param string $name The route name
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
     * Sets the pattern.
     *
     * @param string $pattern The route pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Gets the pattern.
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Sets the defaults.
     *
     * @param array $defaults The route defaults
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;
    }

    /**
     * Gets the defaults.
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }
}