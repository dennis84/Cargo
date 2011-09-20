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

/**
 * ViewExtension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class ViewExtension implements ExtensionInterface
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

        $app['dispatcher']->addListener(ApplicationEvents::REQUEST, array($this, 'onApplicationRequest'));

        $app['view'] = $app->share(function () use ($app) {
            return new \Cargo\ApplicationContainer();
        });
    }

    /**
     * On appllication request.
     *
     * @param ApplicationEvent $event The appllication event
     */
    public function onApplicationRequest($event)
    {
        $event->getRequest()->attributes->set('view', $this->app['view']);
    }
}
