<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Cache;

use Symfony\Component\Routing\RouteCollection;
use Silex\Application;
use Cargo\Template\TemplateCollection;

/**
 * TemplateCacheHandler.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateCacheHandler
{
    /**
     * Constructor.
     *
     * @param TemplateCollection $cargoTemplates The cargo template collection
     * @param Application        $app The silex application
     */
    public function __construct(TemplateCollection $cargoTemplates, Application $app)
    {
        $this->cargoTemplates = $cargoTemplates;
        $this->app            = $app;
    }

    /**
     * {@inheritDoc}
     */
    public function onNonFresh($cache)
    {
        $content = '<?php $templates = array(); ';

        foreach ($this->cargoTemplates as $template) {
            $content .= sprintf(<<<EOF
\$templates['%s'] = file_get_contents('%s');

EOF
                ,
                $template->getName(), 
                $template->getPath()
            );
        }

        $cache->write($content);
    }

    /**
     * {@inheritDoc}
     */
    public function onFresh($cache)
    {
        $templates = array();

        include $cache;
        $this->app['twig.templates'] = array_merge($this->app['twig.templates'], $templates);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'templates';
    }
}
