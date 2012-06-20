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
 * ErrorCacheHandler.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class ErrorCacheHandler implements CacheHandlerInterface
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
        $content = '<?php $app = $this->app; ';
        foreach ($theme->getOriginalTemplates() as $template) {
          foreach ($template->getAnnotations() as $annotation) {
            if ($annotation instanceof \Cargo\Annotation\Error) {

              $content .= sprintf(<<<EOF

\$app->error(function (\\Exception \$exception, \$code) use (\$app) {
    return \$app['twig']->render('%s', array(
        'exception' => \$exception,
        'code'      => \$code,
    ));
});

EOF
                ,
                $template->getName());
            }
          }
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
        return 'error';
    }
}
