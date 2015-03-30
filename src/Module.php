<?php

namespace Squid\EventDispatcher;

use Squid\EventDispatcher\Factory\EventDispatcherFactory;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return [
            'service_manager' => [
                'factories' => [
                    EventDispatcher::class => EventDispatcherFactory::class,
                ],
            ],
        ];
    }
}
