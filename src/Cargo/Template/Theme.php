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
 * Theme.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Theme
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var array
     */
    protected $templates;

    /**
     * Constructor.
     *
     * @param string $name      The theme name
     * @param string $dir       The theme dir
     * @param array  $templates The twig templates
     *
     * @throws InvalidArgumentException If the theme dir does not exists
     */
    public function __construct($name, $dir, array $templates = array())
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" does not exist.', $dir));
        }

        $this->name      = $name;
        $this->dir       = $dir;
        $this->templates = $templates;
    }

    /**
     * Gets the theme name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the theme dir.
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Gets the twig templates.
     *
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * Sets the twig templates.
     *
     * @param array $templates A twig template collection
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }
}
