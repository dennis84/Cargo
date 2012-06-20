<?php

namespace Cargo\Matcher;

class TemplateMatcher
{
    public function match($template, $annotations)
    {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof \Cargo\Annotation\Template) {
                $template->setName($annotation->getName());
            }
        }
    }
}
