<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Template\Event;

use Cargo\Template\TemplateInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * FilterTemplateEvent.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class FilterTemplateEvent extends Event
{
    /**
     * Constructor.
     *
     * @param TemplateInterface $template The template object
     */
    public function __construct(TemplateInterface $template)
    {
        $this->template = $template;
    }

    /**
     * Sets the template.
     *
     * @param TemplateInterface $template The template object
     */
    public function setTemplate(TemplateInterface $template)
    {
        $this->template = $template;
    }

    /**
     * Gets the template.
     *
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }
}