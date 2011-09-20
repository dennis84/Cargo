<?php

namespace Cargo\Test\Theme;

use Cargo\Theme\Collection\TemplateCollection;
use Cargo\Theme\Template;
use Cargo\Theme\TemplateResolver;

class TemplateResolverTest extends \PHPUnit_Framework_TestCase
{
    protected $resolver;

    public function setUp()
    {
        $templates = new TemplateCollection();
        $foo = new Template(__DIR__.'/../Fixures/Floppy/Home/foo.html.php');
        $bar = new Template(__DIR__.'/../Fixures/Floppy/Home/bar.html.php');
        $foo->compile();
        $bar->compile();

        $templates->add($foo);
        $templates->add($bar);
        
        $this->resolver = new TemplateResolver($templates);
    }

    public function testResolveByPattern()
    {
        $template = $this->resolver->resolveByPattern('/foo');
        $this->assertEquals('foo', $template->getName());
        
        $template = $this->resolver->resolveByPattern('/bar');
        $this->assertEquals('bar', $template->getName());

        $template = $this->resolver->resolveByPattern('/hello');
        $this->assertSame(false, $template);
    }

    public function testResolveByName()
    {
        $template = $this->resolver->resolveByName('foo');
        $route = current($template->getRoutes());
        $this->assertEquals('/foo', $route->getPattern());

        $template = $this->resolver->resolveByName('bar');
        $route = current($template->getRoutes());
        $this->assertEquals('/bar', $route->getPattern());

        $template = $this->resolver->resolveByName('hello');
        $this->assertSame(false, $template);
    }
}