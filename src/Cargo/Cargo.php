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
use Symfony\Component\HttpKernel\KernelEvents;

use Silex\Application;
use Silex\SilexEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

use Cargo\Template\TemplateBuilder;
use Cargo\Template\Event\RouteListener;
use Cargo\Template\Theme;

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

    protected $themes = array();

    /**
     * Constructor.
     *
     * @param Application $app The silex application
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $app['dispatcher']->addSubscriber($this);
    }

    /**
     * Registers the themes if there is no defined cache path then the caching
     * will not works. If the caching is active then the files will loaded from
     * the cargo cache loader.
     *
     * @param array $themes The themes to register
     */
    public function registerThemes(array $themes = array())
    {
        $templateBuilder = $this->app['cargo.template.builder'];

        if (!isset($this->app['cargo.cache_dir'])) {
          foreach ($themes as $name => $dir) {
            $theme = new Theme($name, $dir);
            $templateBuilder->createTemplatesFromTheme($theme);
            $this->themes[] = $theme;
            return $this->loadTheme($name, $dir);
          }
        }

        foreach ($themes as $name => $dir) {
          $theme = new Theme($name, $dir);
          $this->app['cargo.cache_loader']->load($theme, function ($theme) use ($templateBuilder) {
            $templateBuilder->createTemplatesFromTheme($theme);
          });

          $this->themes[] = $theme;
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
        $route    = $request->get('_route');

        if (null === $template) {
            return;
        }

        $output = $this->app['twig']->render(
            $template->getName(),
            $request->attributes->all()
        );

        $event->setResponse(new Response($output));
    }

    public function getThemes()
    {
        return $this->themes;
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
