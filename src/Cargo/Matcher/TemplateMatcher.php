<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Matcher;

use Cargo\Template\TemplateInterface;

/** 
 * TemplateMatcher.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateMatcher implements MatcherInterface
{
    /**
     * {@inheritDoc}
     */
    public function match(TemplateInterface $template, array $annotations)
    {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof \Cargo\Annotation\Template) {
                $template->setName($annotation->getName());
            }
        }
    }
}
