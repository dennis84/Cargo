<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Twig;

/**
 * Application.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Engine extends \Twig_Environment
{
    /**
     * Constructor.
     *
     * @param array $templates The twig templates
     */
    public function __construct($templates)
    {
        $loaderArray = new \Twig_Loader_Array($templates);
        $loader      = new \Twig_Loader_Chain(array(
            $loaderArray
        ));

        parent::__construct($loader);
    }
}