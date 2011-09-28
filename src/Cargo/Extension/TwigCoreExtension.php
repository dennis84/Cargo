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
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * TwigCoreExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TwigCoreExtension extends \Twig_Extension
{
    /**
     * @var UrlGenerator
     */
    protected $generator;

    /**
     * Constructor.
     *
     * @param UrlGenerator $generator The route generator
     */
    public function __construct(UrlGenerator $generator)
    {
        $this->generator = $generator;
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
     * @param string $name       The route name
     * @param array  $parameters The route parameters
     *
     * @return string
     */
    public function getPath($name = null, array $parameters = array())
    {
        return $this->generator->generate($name, $parameters);
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