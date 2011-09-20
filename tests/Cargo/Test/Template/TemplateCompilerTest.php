<?php

namespace Cargo\Template\Theme;

use Cargo\Template\Template;
use Cargo\Template\TemplateCompiler;

class TemplateCompilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests compile a template.
     */
    public function testCompile()
    {
        $compiler = new TemplateCompiler();

        $template = new Template(__DIR__.'/../Fixures/Foo/templateA.html.twig');
        $template->compile($template);
        $this->assertSame(true, $template->isCompiled());

        $this->assertEquals('Test:Foo:templateA', $template->getName());

        $this->assertEquals(1, count($template->getRoutes()));
        $route = current($template->getRoutes());
        $this->assertEquals('foo_template_a', $route->getName());
        $this->assertEquals('/foo/route-a', $route->getPattern());

        /*
        $this->assertEquals(2, count($template->getEventSubscriberServices()));
        $subscribers = $template->getEventSubscriberServices();

        $this->assertSame(true, array_key_exists('foo.form.listener.bla', $subscribers));
        $this->assertSame(true, array_key_exists('foo.form.listener.blubb', $subscribers));
        $this->assertEquals('BlubbService', $subscribers['foo.form.listener.blubb']['class']);

        $arguments = $subscribers['foo.form.listener.blubb']['arguments'];
        $this->assertEquals(2, count($arguments));
        $this->assertEquals('bla', $arguments[0]);
        $this->assertEquals('blubb', $arguments['hello']);
        */
    }
}
