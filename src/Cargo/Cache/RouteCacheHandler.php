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

/**
 * RouteCacheHandler.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class RouteCacheHandler
{
    /**
     * Constructor.
     *
     * @param RouteCollection $routes The cargo routes
     * @param Application     $app    The silex application
     */
    public function __construct(RouteCollection $routes, Application $app)
    {
        $this->routes = $routes;
        $this->app    = $app;
    }

    /**
     * {@inheritDoc}
     */
    public function onNonFresh(ConfigCache $cache)
    {
        $content   = '<?php $app = $this->app; ';

        foreach ($this->routes as $route) {
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
});

EOF
                ,
                $route->getPattern(),
                $routeVariables,
                $template->getName(),
                $templateVariables
            );
        }

        $cache->write($content);
    }

    /**
     * {@inheritDoc}
     */
    public function onFresh(ConfigCache $cache)
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
