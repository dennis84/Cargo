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

use Cargo\Template\Event\TemplateEvents;
use Cargo\Template\Event\FilterTemplateEvent;

use Symfony\Component\Finder\Finder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * TemplateBuilder.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateBuilder
{
    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var TemplateCollection
     */
    protected $templates;

    /**
     * Constructor.
     *
     * @param TemplateCollection $templates  The templates
     * @param EventDispatcher    $dispatcher The event dispatcher
     */
    public function __construct(TemplateCollection $templates, $compiler)
    {
        $this->templates  = $templates;
        $this->compiler = $compiler;
        
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

            $this->compiler->compile($template);
            $this->templates->add($template);
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
