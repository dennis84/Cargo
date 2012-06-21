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

use Silex\Application;
use Cargo\Template\TemplateInterface;

/** 
 * SecurityMatcher.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class SecurityMatcher implements MatcherInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Constructor.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritDoc}
     */
    public function match(TemplateInterface $template)
    {
        $secured = $template->getAnnotation('Cargo\Annotation\Secured');
        $route   = $template->getAnnotation('Cargo\Annotation\Route');

        if ($secured && $route) {
            $rules = $this->app['security.access_rules'];
            $rules[] = array('^'.$route->getPattern(), $secured->getRole());

            $this->app['security.access_rules'] = $rules;
        }
    }
}
