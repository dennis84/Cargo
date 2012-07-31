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
use Symfony\Component\Config\ConfigCache;
use Silex\Application;
use Cargo\Template\Theme;

/**
 * TemplateCacheHandler.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateCacheHandler implements CacheHandlerInterface
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
    public function onNonFresh(Theme $theme, ConfigCache $cache)
    {
        $content = '<?php $templates = array(); ';

        foreach ($theme->getTemplates() as $name => $body) {
            $content .= sprintf('
              
$templates["%s"] = <<<EOF
%s
EOF;

'
                ,
                $name,
                str_replace('$', '\$', $body)
            );
        }

        $cache->write($content);
    }

    /**
     * {@inheritDoc}
     */
    public function onFresh(Theme $theme, ConfigCache $cache)
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
