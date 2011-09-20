<?php

namespace Cargo\Test;

use Cargo\Application;
use Cargo\Template\Template;
use Cargo\Template\TemplateCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    protected function setUp()
    {
        $this->app = new Application();
        $this->app->registerThemes(array(
            __DIR__ . '/Fixures/Foo',
            __DIR__ . '/Fixures/Bar',
        ));
        $this->app->register(new \Cargo\Extension\TwigExtension());
    }

    /**
     * Tests initialize application with all dependencies.
     */
    public function testInitApplication()
    {
        $app = new Application();
        $this->assertInstanceOf('\Cargo\Application', $app);
        $this->assertInstanceOf('\Cargo\ApplicationContainer', $app);
        $this->assertInstanceOf('\Pimple', $app);

        $this->assertTrue($app->has('request.http_port'));
        $this->assertTrue($app->has('request.https_port'));
        $this->assertTrue($app->has('debug'));
        $this->assertTrue($app->has('charset'));

        $this->assertInstanceOf('\Symfony\Component\EventDispatcher\EventDispatcher', $app['dispatcher']);
        $this->assertInstanceOf('\Cargo\Template\Collection\TemplateCollection', $app['templates']);
        $this->assertInstanceOf('\Symfony\Component\Routing\RouteCollection', $app['routes']);
        $this->assertInstanceOf('\Cargo\Template\TemplateResolver', $app['resolver']);
    }

    /**
     * Tests load a config file.
     */
    public function testLoad()
    {
        $this->app->load(__DIR__.'/Fixures/config.php');
        $this->assertEquals('Foo', $this->app['foo']);
        $this->assertEquals('Bar', $this->app['bar']);
    }

    /**
     * Tests load a non existing config file.
     *
     * @expectedException InvalidArgumentException
     */
    public function testLoadInvalidFile()
    {
        $this->app->load(__DIR__.'/bla.php');
    }

    /**
     * Tests registering themes.
     */
    public function testRegisterThemes()
    {
        $this->app->registerThemes(array(
            __DIR__.'/Fixures/Foo',
        ));

        $templateNames = array(
            'Test:Foo:templateA',
            'Test:Foo:templateB',
            'Test:Foo:templateC',
            'Test:Foo:notFound',
            'Test:Bar:templateA',
            'Test:Bar:templateB',
            'Test:Bar:templateC',
            'Test:Bar:notFound',
        );

        $this->assertTrue(count($this->app['templates']) > 0);
        foreach ($this->app['templates'] as $template) {
            $this->assertInstanceOf('\Cargo\Template\Template', $template);
            $this->assertTrue(in_array($template->getName(), $templateNames));
            $this->assertTrue($template->isCompiled());
        }
    }

    /**
     * Tests run application.
     */
    public function testRun()
    {
        $response = $this->app->run(Request::create('http://test.com/foo/route-a'));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals('Test:Foo:templateA.html.twig', trim($response->getContent()));

        $response = $this->app->run(Request::create('http://test.com/foo/route-b'));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals('Test:Foo:templateB.html.twig', trim($response->getContent()));

        $response = $this->app->run(Request::create('http://test.com/foo/route-c'));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals('Test:Foo:templateC.html.twig', trim($response->getContent()));
    }

    /**
     * Tests run the application without a templating engine.
     *
     * @expectedException RuntimeException
     */
    public function testRunWithNoTemplating()
    {
        $app = new Application();
        $app->registerThemes(array(
            __DIR__ . '/Fixures/Foo',
            __DIR__ . '/Fixures/Bar',
        ));

        $app->run(Request::create('http://test.com/foo/route-a'));
    }

    /**
     * Tests not found route with fallback.
     */
    public function testNotFoundRunWithFallback()
    {
        $this->app['template.not_found'] = 'Test:Foo:notFound';
        $response = $this->app->run(Request::create('http://test.com/hello'));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals('Test:Foo:notFound.html.twig', trim($response->getContent()));
    }

    /**
     * Tests not found route with invalid fallback
     *
     * @expectedException Cargo\Template\Exception\TemplateNotFoundException
     */
    public function testNotFoundRunWithInvalidFallback()
    {
        $this->app['template.not_found'] = 'bla';
        $response = $this->app->run(Request::create('http://test.com/hello'));
    }

    /**
     * Tests not found route with exception.
     *
     * @expectedException Cargo\Template\Exception\TemplateNotFoundException
     */
    public function testNotFoundRun()
    {
        $response = $this->app->run(Request::create('http://test.com/hello'));
    }
}
