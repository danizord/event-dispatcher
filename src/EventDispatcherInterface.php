<?php

namespace Squid\EventDispatcher;

interface EventDispatcherInterface
{
    /**
     * @param object $event
     */
    public function dispatch($event);

    /**
     * @param string   $eventName
     * @param callable $listener
     */
    public function addListener($eventName, callable $listener);
}
