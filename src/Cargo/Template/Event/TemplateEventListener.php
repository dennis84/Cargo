<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Template\Event;

use Cargo\Event\EventSubscriberInterface;
use Cargo\Event\EventInterface;
use Cargo\Event\EventDispatcherInterface;
use Cargo\Application;

/**
 * TemplateEventListener.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateEventListener implements EventSubscriberInterface
{
    /**
     * @var Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var Cargo\Application
     */
    protected $application;

    public function __construct(EventDispatcherInterface $dispatcher, Application $application)
    {
        $this->dispatcher = $dispatcher;
        $this->container = $container;
    }

    public function preAdd(EventInterface $event)
    {
        $template = $event->getTemplate();

        if ($template->hasEventSubscriberServices()) {
            foreach ($template->getEventSubscriberServices() as $id => $service) {
                if (null !== $service['class']) {
                    $this->application['id'] = $service;
                }

                $this->dispatcher->addSubscriber($this->application[$id]);
            }
        }
    }

    public function getSubscribedEvents()
    {
        return array(
            TemplateEvents::PRE_ADD => 'preAdd',
        );
    }
}