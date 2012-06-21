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
 * ErrorMatcher.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class ErrorMatcher implements MatcherInterface
{
    /**
     * Constructor.
     *
     * @param Application $app The silex application
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
      if ($template->hasAnnotation('Cargo\Annotation\Error')) {
            $app = $this->app;
            $this->app->error(function (\Exception $e, $code) use ($template, $app) {
                return $app['twig']->render($template->getName());
            });
        }
    }
}
