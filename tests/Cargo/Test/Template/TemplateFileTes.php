<?php

namespace Cargo\Test\Template;

use Cargo\Template\TemplateFile;

class TemplateFileTest extends \PHPUnit_Framework_TestCase
{
    public function testRead()
    {
        $file = new TemplateFile(__DIR__.'/../Fixures/Floppy/Home/bar.html.php');
        $this->assertEquals(true, is_string($file->read()));
        $this->assertNotSame(false, strpos($file->read(), 'bar'));
    }

    public function testWrite()
    {
        $file = new TemplateFile(__DIR__.'/../Fixures/Floppy/Home/writable.php');
        $file->write('hello');
        $this->assertNotSame(false, strpos($file->read(), 'hello'));
    }

    public function testReadDocComments()
    {
        $file = new TemplateFile(__DIR__.'/../Fixures/Floppy/Home/bar.html.php');
        $comment = $file->readDocComments();

        $this->assertSame(0, strpos($comment, '/*'));
        $this->assertSame(strlen($comment), strrpos($comment, '*/') +2); // adding two chars */
    }

    public function testInvalidAnnotations()
    {
        $this->setExpectedException('Exception');
        $file = new TemplateFile(__DIR__.'/../Fixures/Floppy/Home/wrong_annotations.php');
        $file->readDocComments();
    } 
}
