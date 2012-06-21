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
 * SecurityCacheHandler.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class SecurityCacheHandler implements CacheHandlerInterface
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
        $rules = '';

        foreach ($theme->getOriginalTemplates() as $template) {
          $secured = $template->getAnnotation('Cargo\Annotation\Secured');
          $route   = $template->getAnnotation('Cargo\Annotation\Route');

          if ($secured && $route) {
            $rules .= sprintf('array("^%s", "%s"),', $route->getPattern(), $secured->getRole());
          }
        }

        $content = sprintf(<<<EOF
<?php
\$app = \$this->app;
\$app['security.access_rules'] = array(
    %s
);

EOF
            ,
            $rules);

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
        return 'security';
    }
}
