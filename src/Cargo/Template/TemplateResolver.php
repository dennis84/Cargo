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

use Cargo\Template\Collection\TemplateCollection;
use Cargo\Template\Exception\TemplateNotFoundException;

/**
 * TemplateResolver.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateResolver
{
    /**
     * TemplateCollection
     */
    protected $templates;

    /**
     * Constructor.
     *
     * @param TemplateCollection $templates The templates
     */
    public function __construct($templates)
    {
        $this->templates = $templates;
    }

    /**
     * Resolves a template by route pattern.
     *
     * @param string $pattern The route pattern
     *
     * @throws TemplateNotFoundException
     */
    public function resolveByPattern($pattern)
    {
        foreach ($this->templates as $template) {
            foreach ($template->getRoutes() as $route) {
                if ($pattern === $route->getPattern()) {
                    return $template;
                }
            }
        }

        throw new TemplateNotFoundException();
    }

    /**
     * Resolves a template by route name.
     *
     * @param string $routeName The route name
     *
     * @throws TemplateNotFoundException
     */
    public function resolveByRouteName($routeName)
    {
        foreach ($this->templates as $template) {
            foreach ($template->getRoutes() as $route) {
                if ($routeName === $route->getName()) {
                    return $template;
                }
            }
        }

        throw new TemplateNotFoundException();
    }

    /**
     * Resolves a template by name.
     *
     * @param string $name The template name
     *
     * @throws TemplateNotFoundException
     */
    public function resolveByName($name)
    {
        foreach ($this->templates as $template) {
            if ($name === $template->getName()) {
                return $template;
            }
        }

        throw new TemplateNotFoundException();
    }
}