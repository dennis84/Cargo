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
use Cargo\ApplicationEvent;
use Cargo\ApplicationEvents;

use Symfony\Component\HttpFoundation\SessionStorage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session;

/**
 * SessionExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class SessionExtension implements ExtensionInterface
{
    /**
     * @var Cargo\Application
     */
    protected $app;

    /**
     * Registers an extension.
     *
     * @param Application $app An appllication instance
     */
    public function register(Application $app)
    {
        $this->app = $app;

        $app['session'] = $app->share(function () use ($app) {
            return new Session(
                $app['session.storage'],
                $app['session.default_locale']
            );
        });

        $app['session.storage'] = $app->share(function () use ($app) {
            return new NativeSessionStorage($app['session.storage.options']);
        });

        $app['dispatcher']->addListener(ApplicationEvents::REQUEST, array($this, 'onApplicationRequest'), 128);

        if ($app->has('session.storage.options')) {
            $app['session.storage.options'] = array();
        }

        if ($app->has('session.default_locale')) {
            $app['session.default_locale'] = 'en';
        }
    }

    /**
     * On appllication request.
     *
     * @param ApplicationEvent $event The appllication event
     */
    public function onApplicationRequest(ApplicationEvent $event)
    {
        $request = $event->getRequest();
        $request->setSession($this->app['session']);

        // starts the session if a session cookie already exists in the request...
        if ($request->hasPreviousSession()) {
            $request->getSession()->start();
        }
    }
}
