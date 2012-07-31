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
     * Constructor.
     *
     * @param TemplateCollection $templates  The templates
     * @param EventDispatcher    $dispatcher The event dispatcher
     */
    public function __construct(TemplateCompiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Creates templates from theme.
     *
     * @param Theme $theme The theme object
     */
    public function createTemplatesFromTheme(Theme $theme)
    {
        $templates = new TemplateCollection();
        $finder    = $this->findTemplatesByDir($theme->getDir());

        foreach ($finder->getIterator() as $file) {
            $template = new Template($file->getRealPath());

            $this->compiler->compile($template);
            $templates->add($template);
        }

        $theme->setOriginalTemplates($templates);
        $theme->setTemplates($templates->toTwigCollection());
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
            ->name('*.twig')
            ->in($dir)
        ;

        return $finder;
    }
}
