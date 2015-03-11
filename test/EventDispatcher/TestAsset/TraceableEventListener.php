<?php

namespace Squid\Test\EventDispatcher\TestAsset;

class TraceableEventListener
{
    /**
     * @var bool
     */
    private $isCalled = false;

    /**
     * @var object|null
     */
    private $event;

    /**
     * @param object $event
     */
    public function __invoke($event)
    {
        $this->isCalled = true;
        $this->event    = $event;
    }

    /**
     * @return bool
     */
    public function isCalled()
    {
        return $this->isCalled;
    }

    /**
     * @return null|object
     */
    public function getEvent()
    {
        return $this->event;
    }
}
