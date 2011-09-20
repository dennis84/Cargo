<?php

/*
 * This file is part of the cargo framework
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cargo\Template;

use Cargo\Template\Collection\TemplateCollection;
use Cargo\Template\Event\TemplateEvents;
use Cargo\Template\Event\FilterTemplateEvent;

use Symfony\Component\Finder\Finder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * TemplateBuilder.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateBuilder
{
    /**
     * @var Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var Cargo\Template\Collection\TemplateCollection
     */
    protected $templates;

    /**
     * Constructor.
     *
     * @param TemplateCollection $templates
     */
    public function __construct(TemplateCollection $templates, $dispatcher)
    {
        $this->templates  = $templates;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Adds a event subscriber.
     *
     * @param EventSubscriber $eventSubscriber The event subscriber
     *
     * @return TemplateBuilder
     */
    public function addEventSubscriber(EventSubscriberInterface $eventSubscriber)
    {
        $this->dispatcher->addSubscriber($eventSubscriber);
        return $this;
    }

    /**
     * Creates templates from dir.
     *
     * @param string $dir The template dir
     */
    public function createTemplatesFromDir($dir)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException(
                sprintf('the directory "%s" does not exists', $dir)
            );
        }

        $finder = $this->findTemplatesByDir($dir);

        foreach ($finder->getIterator() as $file) {
            $template = new Template($file->getRealPath());
            $template->compile();

            $this->addTemplate($template);
        }
    }

    /**
     * Gets the templates.
     *
     * @return TemplateCollection
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * Adds a template.
     *
     * @param Template $template The template object
     */
    private function addTemplate(TemplateInterface $template)
    {
        $event = new FilterTemplateEvent($template);
        $this->dispatcher->dispatch(TemplateEvents::PRE_ADD, $event);
        $this->templates->add($template);
    }

    /**
     * Resets the template collection.
     */
    public function resetTemplates()
    {
        $this->templates = new TemplateCollection();
    }

    /**
     * Finds all templates from dir.
     *
     * @param string $dir The template dir
     *
     * @return Finder
     */
    private function findTemplatesByDir($dir)
    {
        $finder = Finder::create()
            ->ignoreDotFiles(true)
            ->files()
            ->name('*.html.twig')
            ->in($dir)
        ;

        return $finder;
    }
}
