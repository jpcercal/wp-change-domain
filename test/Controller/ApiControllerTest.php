<?php

namespace Cekurte\ResourceManager\Test\Controller;

use Cekurte\Tdd\ReflectionTestCase;
use Cekurte\Wordpress\ChangeDomain\Builder\SqlBuilder;
use Silex\Application;

class ApiControllerTest extends ReflectionTestCase
{
    public function testClassIsSubclassOf()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\ApiController'
        );

        $this->assertTrue($reflection->isSubclassOf(
            '\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\WebController'
        ));
    }

    public function testIndexAction()
    {
        $controller = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\ApiController')
            ->disableOriginalConstructor()
            ->setMethods(['getApp'])
            ->getMockForAbstractClass()
        ;

        $application = new Application();
        $application->register(new \Silex\Provider\ValidatorServiceProvider());

        $controller
            ->expects($this->once())
            ->method('getApp')
            ->will($this->returnValue($application))
        ;

        $request = $this
            ->getMockBuilder('\\Symfony\\Component\\HttpFoundation\\Request')
            ->disableOriginalConstructor()
            ->setMethods(['getContent'])
            ->getMock()
        ;

        $request
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue(json_encode([
                'tablePrefix'   => 'wp_',
                'numberOfBlogs' => 1,
                'domainTo'      => 'http://www.your.old.domain',
                'domainFrom'    => 'http://www.your.new.domain',
            ])))
        ;

        $response = $controller->indexAction($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIndexActionValidationErrors()
    {
        $controller = $this
            ->getMockBuilder('\\Cekurte\\Wordpress\\ChangeDomain\\Controller\\ApiController')
            ->disableOriginalConstructor()
            ->setMethods(['getApp'])
            ->getMockForAbstractClass()
        ;

        $application = new Application();
        $application->register(new \Silex\Provider\ValidatorServiceProvider());

        $controller
            ->expects($this->once())
            ->method('getApp')
            ->will($this->returnValue($application))
        ;

        $request = $this
            ->getMockBuilder('\\Symfony\\Component\\HttpFoundation\\Request')
            ->disableOriginalConstructor()
            ->setMethods(['getContent'])
            ->getMock()
        ;

        $request
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue(json_encode([
                'tablePrefix'   => 'wp_',
                'numberOfBlogs' => 1,
                'domainTo'      => 'domain-must-be-an-url',
                'domainFrom'    => 'domain-must-be-an-url',
            ])))
        ;

        $response = $controller->indexAction($request);

        $this->assertEquals(400, $response->getStatusCode());
    }
}
