<?php

namespace Cargo\Template\Theme;

use Cargo\Template\TemplateBuilder;
use Cargo\Template\Collection\TemplateCollection;
use Symfony\Component\EventDispatcher\EventDispatcher;

class TemplateBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->builder = new TemplateBuilder(
            new TemplateCollection(),
            new EventDispatcher()
        );
    }

    /**
     * Tests create templates from dir.
     */
    public function testCreateTemplatesFromDir()
    {
        $this->builder->createTemplatesFromDir(__DIR__.'/../Fixures/Bla');
        $templates = $this->builder->getTemplates();
        $this->assertEquals(0, $templates->count());

        // adds more and more templates in same collection
        $this->builder->createTemplatesFromDir(__DIR__.'/../Fixures/Foo');
        $templates = $this->builder->getTemplates();
        $this->assertEquals(4, $templates->count());

        $this->builder->createTemplatesFromDir(__DIR__.'/../Fixures/Bar');
        $templates = $this->builder->getTemplates();
        $this->assertEquals(8, $templates->count());

        foreach ($templates as $template) {
            $this->assertInstanceOf('\Cargo\Template\Template', $template);
            $this->assertNotEquals(null, $template->getName());

            $fileName = basename($template->getPath());
            $this->assertNotEquals(false, strpos($fileName, '.html.twig'));

            $this->assertSame(true, $template->isCompiled());
        }
    }

    /**
     * Tests create templates with resetting.
     */
    public function testResetTemplates()
    {
        $this->builder->createTemplatesFromDir(__DIR__.'/../Fixures/Foo');
        $templates = $this->builder->getTemplates();
        $this->assertEquals(4, $templates->count());

        $this->builder->resetTemplates();
        $this->assertEquals(0, count($this->builder->getTemplates()));

        $this->builder->createTemplatesFromDir(__DIR__.'/../Fixures/Bar');
        $templates = $this->builder->getTemplates();
        $this->assertEquals(4, $templates->count());
    }

    /**
     * Tests create templates in invalid dir.
     *
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidDir()
    {
        $this->builder->createTemplatesFromDir(__DIR__.'/../Fixures/Hello');
    }
}
