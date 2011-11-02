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

use Doctrine\Common\Collections\ArrayCollection;
use Cargo\Template\TemplateInterface;

/**
 * TemplateCollection.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateCollection extends ArrayCollection
{
    /**
     * Adds a template.
     *
     * @param TemplateInterface $template The template object
     *
     * @return null
     *
     * @throws InvalidArgumentException|RuntimeException
     */
    public function add($template)
    {
        if (!$template instanceof TemplateInterface) {
            throw new \InvalidArgumentException('The template must implement TemplateInterface');
        }

        if (null === $template->getName() ||
            false === $template->isCompiled()) {
            throw new \RuntimeException(
                'the template must be compiled before they use it more.'
            );
        }

        $this->set($template->getName(), $template);
    }

    /**
     * Converts the collection to a usable twig collection.
     *
     * @return array
     */
    public function toTwigCollection()
    {
        $templates = array();
        foreach ($this->toArray() as $template) {
            $templates[$template->getName()] = $template->getFile()->read();
        }

        return $templates;
    }
}