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

use Doctrine\Common\Annotations\DocParser;
use Cargo\Template\TemplateRoute;

/**
 * TemplateCompiler.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class TemplateCompiler
{
    /**
     * @var DocParser
     */
    protected $parser;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parser = new DocParser();
        $this->parser->addNamespace('Cargo\Annotation');
    }

    /**
     * Compiles a template.
     *
     * @param Template $template The template object
     *
     * @return Template
     */
    public function compile($template)
    {
        $file        = new TemplateFile($template->getPath());
        $doc         = $file->readDocComments();
        $annotations = $this->parser->parse($doc);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof \Cargo\Annotation\Template) {
                $template->setName($annotation->name);

            } elseif ($annotation instanceof \Cargo\Annotation\Route) {
                $template->addRoute(new TemplateRoute(
                    $annotation->name,
                    $annotation->pattern,
                    $annotation->defaults
                ));

            } elseif ($annotation instanceof \Cargo\Annotation\EventSubscriber) {
                $template->addEventSubscriberService(
                    $annotation->id,
                    $annotation->class,
                    $annotation->arguments
                );

            } elseif ($annotation instanceof \Cargo\Annotation\Form) {
                $template->addForm(
                    $annotation->type,
                    $annotation->class
                );
            }
        }

        $template->setCompiled();
    }
}
