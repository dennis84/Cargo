<?php

namespace Acme\Extension;

class AcmeTwigExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'variable' => new \Twig_Function_Method($this, 'variable', array('is_safe' => array('html'))),
        );
    }

    public function variable()
    {
        return 'foo';
    }

    public function getName()
    {
        return 'acme';
    }
}