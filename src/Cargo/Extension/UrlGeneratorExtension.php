<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Extension;

use Cargo\Application;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * UrlGeneratorExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class UrlGeneratorExtension implements ExtensionInterface
{
    /**
     * Registers an extension.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        $app['url_generator'] = $app->share(function () use ($app) {
            return new UrlGenerator($app['routes'], $app['request_context']);
        });
    }
}