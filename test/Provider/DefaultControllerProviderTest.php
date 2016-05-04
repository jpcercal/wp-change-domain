<?php

namespace Cekurte\ResourceManager\Test\Provider;

use Cekurte\Tdd\ReflectionTestCase;
use Cekurte\Wordpress\ChangeDomain\Provider\DefaultControllerProvider;

class DefaultControllerProviderTest extends ReflectionTestCase
{
    public function testImplementsControllerProviderInterface()
    {
        $reflection = new \ReflectionClass(
            '\\Cekurte\\Wordpress\\ChangeDomain\\Provider\\DefaultControllerProvider'
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
                ->setMethods(['get', 'bind'])
                ->getMock()
            ;

            $service
                ->expects($this->once())
                ->method('get')
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
            (new DefaultControllerProvider())->connect($application)
        );
    }
}
