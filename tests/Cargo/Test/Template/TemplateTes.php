<?php

namespace Cargo\Test\Theme;

use Cargo\Theme\Template;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $template = $this->createTemplate();
        $template->compile();

        $this->assertSame(true, $template->isCompiled());
    }

    public function testSetInvalidFile()
    {
        $this->setExpectedException('InvalidArgumentException');
        $template = new Template(__DIR__.'/../Fixures/Floppy/Home/hello.html.php');
    }

    private function createTemplate()
    {
        return new Template(__DIR__.'/../Fixures/Floppy/Home/foo.html.php');
    }
}