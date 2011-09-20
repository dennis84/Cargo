<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Annotation;

use Doctrine\Common\Annotations\Annotation;

/** @Annotation */
class Template extends Annotation
{
    public $name;
}

/** @Annotation */
class Route extends Annotation
{
    public $name;
    public $pattern;
    public $defaults = array();
}

/** @Annotation */
class EventSubscriber extends Annotation
{
    public $id;
    public $class;
    public $arguments;
}
