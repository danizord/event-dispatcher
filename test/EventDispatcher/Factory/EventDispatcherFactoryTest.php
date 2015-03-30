<?php

namespace Squid\EventDispatcher\Factory;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Squid\EventDispatcher\EventDispatcher;
use Squid\EventDispatcher\Exception\InvalidArgumentException;
use Squid\Test\EventDispatcher\TestAsset\BarEvent;
use Squid\Test\EventDispatcher\TestAsset\FooEvent;
use Squid\Test\EventDispatcher\TestAsset\TraceableEventListener;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventDispatcherFactoryTest extends TestCase
{
    /**
     * @covers \Squid\EventDispatcher\Factory\EventDispatcherFactory
     */
    public function testThrowsExceptionIfMissingListenersConfig()
    {
        /** @var ServiceLocatorInterface|MockObject $serviceLocator */
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);
        $invalidConfig  = [];

        $serviceLocator->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn($invalidConfig);

        $this->setExpectedException(
            InvalidArgumentException::class,
            'Missing [\'squidfacil\'][\'event_dispatcher\'][\'listeners\'] key in config'
        );

        $factory = new EventDispatcherFactory();

        $factory($serviceLocator);
    }

    /**
     * @covers \Squid\EventDispatcher\Factory\EventDispatcherFactory
     */
    public function testThrowsExceptionIfInvalidListenersConfigGiven()
    {
        /** @var ServiceLocatorInterface|MockObject $serviceLocator */
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);
        $invalidConfig  = [
            'squidfacil' => [
                'event_dispatcher' => [
                    'listeners' => 'string',
                ],
            ],
        ];

        $serviceLocator->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn($invalidConfig);

        $this->setExpectedException(
            InvalidArgumentException::class,
            'Listeners config must be array, string given'
        );

        $factory = new EventDispatcherFactory();

        $factory($serviceLocator);
    }

    /**
     * @covers \Squid\EventDispatcher\Factory\EventDispatcherFactory
     */
    public function testFetchesListenersFromServiceLocator()
    {
        /** @var ServiceLocatorInterface|MockObject $serviceLocator */
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);

        $config = [
            'squidfacil' => [
                'event_dispatcher' => [
                    'listeners' => [
                        BarEvent::class => ['listener1', 'listener2'],
                        FooEvent::class => ['listener3'],
                    ],
                ],
            ],
        ];

        $serviceLocator->expects($this->at(0))
            ->method('get')
            ->willReturn($config);

        $listener1 = new TraceableEventListener();
        $serviceLocator->expects($this->at(1))
            ->method('get')
            ->with('listener1')
            ->willReturn($listener1);

        $listener2 = new TraceableEventListener();
        $serviceLocator->expects($this->at(2))
            ->method('get')
            ->with('listener2')
            ->willReturn($listener2);

        $listener3 = new TraceableEventListener();
        $serviceLocator->expects($this->at(3))
            ->method('get')
            ->with('listener3')
            ->willReturn($listener3);

        $factory = new EventDispatcherFactory();

        $result = $factory($serviceLocator);

        $this->assertInstanceOf(EventDispatcher::class, $result);
        $this->assertAttributeSame(
            [
                BarEvent::class => [$listener1, $listener2],
                FooEvent::class => [$listener3],
            ],
            'listeners',
            $result
        );
    }
}
