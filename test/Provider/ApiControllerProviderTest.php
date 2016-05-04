<?php

namespace Cekurte\ResourceManager\Test\Provider;

use Cekurte\Tdd\ReflectionTestCase;
use Cekurte\Wordpress\ChangeDomain\Provider\ApiControllerProvider;

class ApiControllerProviderTest extends ReflectionTestCase
{
    public function testImplementsControllerProviderInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Wordpress\\ChangeDomain\\Provider\\ApiControllerProvider'
        );

        $this->assertTrue($reflection->implementsInterface(
            '\\Silex\\ControllerProviderInterface'
        ));
    }

    public function testConnect()
    {
        $application = $this
            ->getMockBuilder('\\Silex\\Application')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;

        $application['controllers_factory'] = function () {
            $service = $this
                ->getMockBuilder('\\Silex\\Provider\\ServiceControllerServiceProvider')
                ->disableOriginalConstructor()
                ->setMethods(['post', 'bind'])
                ->getMock()
            ;

            $service
                ->expects($this->once())
                ->method('post')
                ->will($this->returnValue($service))
            ;

            $service
                ->expects($this->once())
                ->method('bind')
                ->will($this->returnValue(null))
            ;

            return $service;
        };

        $this->assertInstanceOf(
            '\\Silex\\Provider\\ServiceControllerServiceProvider',
            (new ApiControllerProvider())->connect($application)
        );
    }
}
