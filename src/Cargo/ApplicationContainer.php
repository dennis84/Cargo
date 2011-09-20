<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo;

/**
 * Application.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class ApplicationContainer extends \Pimple
{
    /**
     * Loads a config file.
     *
     * @param string $file The config file
     */
    public function load($file)
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException(sprintf(
                'The config file "%s" does not exists.', $file
            ));
        }

        $container = $this;
        include $file;
    }

    /**
     * Sets a parameter on an object.
     *
     * @param string $id    The identifier
     * @param mixed  $value The value
     */
    public function set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * Checks if a parameter or an object is set.
     *
     * @param string $id The identifier
     *
     * @return boolean
     */
    public function has($id)
    {
        return $this->offsetExists($id);
    }

    /**
     * Gets a parameter on an object.
     *
     * @param string $id The identifier
     *
     * @return mixed
     */
    public function get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Creates a new container.
     *
     * @return ApplicationContainer
     */
    public function create()
    {
        return new self();
    }
}