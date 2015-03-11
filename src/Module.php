<?php

namespace Squid\EventDispatcher;

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
                'invokables' => [
                    EventDispatcher::class => EventDispatcher::class,
                ],
            ],
        ];
    }
}
