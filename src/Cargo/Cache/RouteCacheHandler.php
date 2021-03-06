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

use Symfony\Component\Config\ConfigCache;
use Silex\Application;
use Cargo\Template\Theme;

/**
 * RouteCacheHandler.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class RouteCacheHandler implements CacheHandlerInterface
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
        $content   = '<?php $app = $this->app; ';

        foreach ($this->app['cargo.routes'] as $routeName => $route) {
            $compiledRoute     = $route->compile();
            $routeVariables    = '';
            $templateVariables = '';

            foreach ($compiledRoute->getVariables() as $variable) {
                $routeVariables    .= '$'.$variable;
                $templateVariables .= sprintf('"%s" => $%s,', $variable, $variable);
            }

            $routeDefaults = $route->getDefaults();
            $template      = $routeDefaults['_template'];

            $content .= sprintf(<<<EOF

\$app->get('%s', function (%s) use (\$app) {
    return \$app['twig']->render('%s', array(
        %s
    ));
})->bind('%s');

EOF
                ,
                $route->getPattern(),
                $routeVariables,
                $template->getName(),
                $templateVariables,
                $routeName
            );
        }

        $cache->write($content);
    }

    /**
     * {@inheritDoc}
     */
    public function onFresh(Theme $theme, ConfigCache $cache)
    {
        include $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'routes';
    }
}
