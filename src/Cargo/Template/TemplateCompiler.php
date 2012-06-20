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

use Symfony\Component\Routing\Route;
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
    public function __construct(array $matchers = array())
    {
        $this->matchers = $matchers;

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

        $template->setAnnotations($annotations);

        foreach ($this->matchers as $matcher) {
            $matcher->match($template);
        }

        $template->setCompiled();
    }
}
