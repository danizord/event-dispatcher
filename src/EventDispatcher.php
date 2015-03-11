<?php

namespace Squid\EventDispatcher;

use InvalidArgumentException;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var callable[]
     */
    private $listeners = [];

    /**
     * {@inheritDoc}
     */
    public function dispatch($event)
    {
        if (!is_object($event)) {
            throw new InvalidArgumentException(sprintf(
                '$event expected to be an object, %s given',
                gettype($event)
            ));
        }

        $eventName = get_class($event);

        if (!isset($this->listeners[$eventName])) {
            return;
        }

        foreach ($this->listeners[$eventName] as $listener) {
            $listener($event);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addListener($eventName, callable $listener)
    {
        if (!isset($this->listeners[(string) $eventName])) {
            $this->listeners[$eventName] = [];
        }

        $this->listeners[(string) $eventName][] = $listener;
    }
}
