<?php

namespace Cekurte\ResourceManager\Test\Controller;

use Cekurte\Tdd\ReflectionTestCase;
use Cekurte\Wordpress\ChangeDomain\Builder\SqlBuilder;
use Silex\Application;

class WebControllerTest extends ReflectionTestCase
{
    public function testClassIsAbstract()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\WebController'
        );

        $this->assertTrue($reflection->isAbstract());
    }

    public function testGetApp()
    {
        $controller = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\WebController')
            ->setConstructorArgs([new Application()])
            ->getMockForAbstractClass()
        ;

        $this->assertInstanceOf(
            '\\Silex\\Application',
            $controller->getApp()
        );
    }

    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage The UrlGeneratorServiceProvider is not registered in this application
     */
    public function testGenerateUrlException()
    {
        $controller = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\WebController')
            ->disableOriginalConstructor()
            ->setMethods(['getApp'])
            ->getMockForAbstractClass()
        ;

        $controller
            ->expects($this->once())
            ->method('getApp')
            ->will($this->returnValue(new Application()))
        ;

        $controller->generateUrl('fakeRoute');
    }

    public function testGenerateUrl()
    {
        $controller = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\WebController')
            ->disableOriginalConstructor()
            ->setMethods(['getApp'])
            ->getMockForAbstractClass()
        ;

        $application = new Application();

        $application['url_generator'] = function () {
            $service = $this
                ->getMockBuilder('\\Silex\\Provider\\UrlGeneratorServiceProvider')
                ->disableOriginalConstructor()
                ->setMethods(['generate'])
                ->getMock()
            ;

            $service
                ->expects($this->once())
                ->method('generate')
                ->will($this->returnValue('working'))
            ;

            return $service;
        };

        $controller
            ->expects($this->once())
            ->method('getApp')
            ->will($this->returnValue($application))
        ;

        $this->assertEquals('working', $controller->generateUrl('fakeRoute'));
    }

    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage The TwigServiceProvider is not registered in this application
     */
    public function testRenderException()
    {
        $controller = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\WebController')
            ->disableOriginalConstructor()
            ->setMethods(['getApp'])
            ->getMockForAbstractClass()
        ;

        $controller
            ->expects($this->once())
            ->method('getApp')
            ->will($this->returnValue(new Application()))
        ;

        $controller->render('fakeView');
    }

    public function testRender()
    {
        $controller = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\WebController')
            ->disableOriginalConstructor()
            ->setMethods(['getApp'])
            ->getMockForAbstractClass()
        ;

        $application = new Application();

        $application['twig'] = function () {
            $service = $this
                ->getMockBuilder('\\Silex\\Provider\\TwigServiceProvider')
                ->disableOriginalConstructor()
                ->setMethods(['render'])
                ->getMock()
            ;

            $service
                ->expects($this->once())
                ->method('render')
                ->will($this->returnValue('working'))
            ;

            return $service;
        };

        $controller
            ->expects($this->once())
            ->method('getApp')
            ->will($this->returnValue($application))
        ;

        $this->assertEquals('working', $controller->render('fakeView'));
    }
}
