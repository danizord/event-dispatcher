<?php

namespace Squid\EventDispatcherTest;

use PHPUnit_Framework_TestCase as TestCase;
use Squid\EventDispatcher\Module;

class ModuleTest extends TestCase
{
    public function testProvidesSerializableConfig()
    {
        $module = new Module();
        $config = $module->getConfig();

        $this->assertInternalType('array', $config);
        $this->assertSame($config, unserialize(serialize($config)));
    }
}
