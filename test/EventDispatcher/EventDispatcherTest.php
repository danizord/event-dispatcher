<?php

namespace Squid\Test\EventDispatcher;

use PHPUnit_Framework_TestCase as TestCase;
use Squid\EventDispatcher\EventDispatcher;
use Squid\EventDispatcher\Exception\InvalidArgumentException;
use Squid\Test\EventDispatcher\TestAsset\BarEvent;
use Squid\Test\EventDispatcher\TestAsset\FooEvent;
use Squid\Test\EventDispatcher\TestAsset\TraceableEventListener;

class EventDispatcherTest extends TestCase
{
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * @var TraceableEventListener
     */
    private $listener1;

    /**
     * @var TraceableEventListener
     */
    private $listener2;

    public function setUp()
    {
        $this->dispatcher = new EventDispatcher();
        $this->listener1  = new TraceableEventListener();
        $this->listener2  = new TraceableEventListener();

        $this->assertFalse($this->listener1->isCalled());
        $this->assertFalse($this->listener2->isCalled());
    }

    public function testThrowsExceptionIfEventIsNotAnObject()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $this->dispatcher->dispatch('string');
    }

    public function testCallsSubscribedEventListeners()
    {
        $this->dispatcher->addListener(FooEvent::class, $this->listener1);
        $this->dispatcher->addListener(FooEvent::class, $this->listener2);

        $event = new FooEvent();

        $this->dispatcher->dispatch($event);

        $this->assertTrue($this->listener1->isCalled());
        $this->assertSame($event, $this->listener1->getEvent());
        $this->assertTrue($this->listener2->isCalled());
        $this->assertSame($event, $this->listener2->getEvent());
    }

    public function testOnlyCallsTheListenerSubscribedToAGivenEvent()
    {
        $this->dispatcher->addListener(FooEvent::class, $this->listener1);
        $this->dispatcher->addListener(BarEvent::class, $this->listener2);

        $event = new FooEvent();

        $this->dispatcher->dispatch($event);

        $this->assertTrue($this->listener1->isCalled());
        $this->assertSame($event, $this->listener1->getEvent());
        $this->assertFalse($this->listener2->isCalled());
    }
}
