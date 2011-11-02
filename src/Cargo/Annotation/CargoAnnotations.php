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

use Symfony\Component\Routing\Annotation\Route as BaseRoute;
use Doctrine\Common\Annotations\Annotation;

/**
 * Template
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * @Annotation
 */
class Template
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Constructor.
     *
     * @param array $data An array of key/value parameters.
     */
    public function __construct(array $data)
    {
        if (isset($data['value'])) {
            $data['name'] = $data['value'];
            unset($data['value']);
        }

        foreach ($data as $key => $value) {
            $method = 'set'.$key;
            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(
                    sprintf("Unknown property '%s' on annotation '%s'.", $key, get_class($this))
                );
            }

            $this->$method($value);
        }
    }

    /**
     * Sets the template name.
     *
     * @param string $name The template name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the template name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

/**
 * Route.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * @Annotation
 */
class Route extends BaseRoute
{
}
