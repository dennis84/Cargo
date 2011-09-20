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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

/**
 * TemplateRouteListener.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateRouteListener implements EventSubscriberInterface
{
    /**
     * @var RouteCollection
     */
    protected $routes;

    /**
     * Constructor.
     *
     * @param RouteCollection $routes The route collection
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Pre adding a template.
     *
     * @param Event $event The triggerd event
     *
     * @return null
     */
    public function preAdd(Event $event)
    {
        $template = $event->getTemplate();

        if ($template->hasRoutes()) {
            foreach ($template->getRoutes() as $templateRoute) {
                $route = new Route(
                    $templateRoute->getPattern(),
                    $templateRoute->getDefaults()
                );

                $this->routes->add($templateRoute->getName(), $route);
            }
        }
    }

    /**
     * Get the subscribed events.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            TemplateEvents::PRE_ADD => 'preAdd',
        );
    }
}