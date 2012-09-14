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
 * RequestAssertCacheHandler.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class RequestAssertCacheHandler implements CacheHandlerInterface
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
        $content = '<?php $app = $this->app;';

        foreach ($this->app['cargo.routes'] as $routeName => $route) {
            if ($assertions = $route->getOption('request_assertions')) {
                $templateAssertions = '';
                foreach ($assertions as $key => $value) {
                    $templateAssertions .= sprintf('"%s" => "%s",', $key, $value);
                }

                $content .= sprintf(<<<EOF
\$assertions = array(%s);
\$app->before(function () use (\$app, \$assertions) {
    \$app['routes']->get('%s')->before(function (\$request, \$app) use (\$assertions) {
        foreach (\$assertions as \$key => \$assertion) {
            if (\$value = \$request->get(\$key)) {
                \$match = false;

                if (strpos(\$assertion, '|')) {
                    \$assertion = explode('|', \$assertion);
                    if (in_array(\$value, \$assertion)) {
                        \$match = true;
                    }
                } else {
                    if (\$value == \$assertion) {
                        \$match = true;
                    }
                }

                if (false === \$match) {
                    throw new \\RuntimeException('This request is not allowed to access this route.');
                }
            } else {
                throw new \\RuntimeException('This request is not allowed to access this route.');
            }
        }
    });
});

EOF
                    ,
                    $templateAssertions,
                    $routeName
                );
            }
            
            $cache->write($content);
        }
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
        return 'request_assert';
    }
}
