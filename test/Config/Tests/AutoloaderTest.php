<?php
namespace Config\Tests;

use PHPUnit_Framework_TestCase;
use Config\Autoloader;

class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public function testAutoload()
    {
        $declared = get_declared_classes();
        $declaredCount = count($declared);
        Autoloader::autoload('Foo');
        $this->assertEquals($declaredCount, count(get_declared_classes()), 'Config\\Autoloader::autoload() is trying to load classes outside of the Config namespace');
        Autoloader::autoload('Config');
        $this->assertTrue(in_array('Config', get_declared_classes()), 'Config\\Autoloader::autoload() failed to autoload the Config class');
    }
}