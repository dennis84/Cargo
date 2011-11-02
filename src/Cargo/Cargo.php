<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Silex\Application;
use Silex\SilexEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

use Cargo\Template\TemplateBuilder;
use Cargo\Template\Collection\TemplateCollection;
use Cargo\Template\Event\RouteListener;

/**
 * Cargo.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Cargo implements EventSubscriberInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Constructor.
     *
     * @param Application $app The silex application
     */
    public function __construct(Application $app)
    {
        $app['dispatcher']->addSubscriber($this);

        $this->app = $app;
    }

    /**
     * Registers Theme paths.
     *
     * @param array $themes The themes to register
     */
    public function registerThemes(array $themes = array())
    {
        $builder = new TemplateBuilder(
            $this->app['cargo.templates'],
            $this->app['cargo.template.compiler']
        );

        foreach ($themes as $name => $dir) {
            $builder->createTemplatesFromDir($dir);
        }
    }

    /**
     * Handles on silex after.
     *
     * @param FilterResponseEvent $event The kernel event
     */
    public function onSilexAfter(FilterResponseEvent $event)
    {
        $request  = $event->getRequest();
        $template = $request->get('_template');

        if (null === $template) {
            return;
        }

        $output = $this->app['cargo.templating']->render(
            $template->getName(),
            $request->attributes->all()
        );

        $event->setResponse(new Response($output));
    }

    /**
     * The subscribed events.
     *
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return array(
            SilexEvents::AFTER => 'onSilexAfter',
        );
    }
}