<?php

namespace Acme;

use Cargo\Routing\Event\RoutingEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContactFormListener implements EventSubscriberInterface
{
    public function process($event)
    {
        /*
        $request = $event->getRequest();
        if ('POST' === $request->getMethod()) {
        }
        */
    }

    public static function getSubscribedEvents()
    {
        return array(
            RoutingEvents::ON_ROUTE_FOUND => 'process',
        );
    }
}
