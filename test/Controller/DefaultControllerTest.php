<?php

namespace Cekurte\ResourceManager\Test\Controller;

use Cekurte\Tdd\ReflectionTestCase;
use Cekurte\Wordpress\ChangeDomain\Builder\SqlBuilder;
use Silex\Application;

class DefaultControllerTest extends ReflectionTestCase
{
    public function testClassIsSubclassOf()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\DefaultController'
        );

        $this->assertTrue($reflection->isSubclassOf(
            '\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\WebController'
        ));
    }

    public function testIndexAction()
    {
        $controller = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\DefaultController')
            ->disableOriginalConstructor()
            ->setMethods(['getApp', 'render'])
            ->getMockForAbstractClass()
        ;

        $controller
            ->expects($this->once())
            ->method('getApp')
            ->will($this->returnValue([
                'debug' => false,
            ]))
        ;

        $controller
            ->expects($this->once())
            ->method('render')
            ->will($this->returnValue('working'))
        ;

        $request = $this->getMock('\\Symfony\\Component\\HttpFoundation\\Request');

        $this->assertEquals('working', $controller->indexAction($request));
    }
}
