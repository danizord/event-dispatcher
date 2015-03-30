<?php

namespace Squid\EventDispatcher\Factory;

use Squid\EventDispatcher\EventDispatcher;
use Squid\EventDispatcher\Exception\InvalidArgumentException;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventDispatcherFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return EventDispatcher
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $listenersConfig = $this->getListenersConfig($serviceLocator);
        $eventDispatcher = new EventDispatcher();

        foreach ($listenersConfig as $eventName => $listenerNames) {
            $listenerNames = (array) $listenerNames;

            foreach ($listenerNames as $listenerName) {
                $listener = $serviceLocator->get($listenerName);

                $eventDispatcher->addListener($eventName, $listener);
            }
        }

        return $eventDispatcher;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return array
     */
    private function getListenersConfig(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['squidfacil']['event_dispatcher']['listeners'])) {
            throw new InvalidArgumentException(
                'Missing [\'squidfacil\'][\'event_dispatcher\'][\'listeners\'] key in config'
            );
        }

        $listenersConfig = $config['squidfacil']['event_dispatcher']['listeners'];

        if (!is_array($listenersConfig)) {
            throw new InvalidArgumentException(sprintf(
                'Listeners config must be array, %s given',
                is_object($listenersConfig) ? get_class($listenersConfig) : gettype($listenersConfig)
            ));
        }

        return $listenersConfig;
    }
}
