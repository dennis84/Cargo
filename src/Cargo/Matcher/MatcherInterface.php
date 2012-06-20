<?php

namespace Cargo\Matcher;

use Cargo\Template\TemplateInterface;

/** 
 * MatcherInterface.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
interface MatcherInterface
{
    /**
     * Match a template by individual conditions and do something
     * with the template or other parameters you passed in the 
     * constructor.
     *
     * @param TemplateInterface $template The template object
     */
    function match(TemplateInterface $template);
}
