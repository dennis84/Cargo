<?php

namespace Cargo\Extension;

use Knp\Menu\MenuFactory;

class KnpMenuRouterAwareMenuFactory extends MenuFactory
{
    protected $generator;

    public function __construct($generator = null)
    {
        $this->generator = $generator;
    }

    public function createItem($name, array $options = array())
    {
        if (!empty($options['route'])) {
            $params = isset($options['routeParameters']) ? $options['routeParameters'] : array();
            $absolute = isset($options['routeAbsolute']) ? $options['routeAbsolute'] : false;
            $options['uri'] = $this->generator->generate($options['route'], $params, $absolute);
        }

        return parent::createItem($name, $options);
    }
}