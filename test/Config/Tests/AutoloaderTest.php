<?php
namespace Sinergi\Config\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\Config\Autoloader;

class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public function testAutoload()
    {
        $declared = get_declared_classes();
        $declaredCount = count($declared);
        Autoloader::autoload('Foo');
        $this->assertEquals($declaredCount, count(get_declared_classes()), 'Config\\Autoloader::autoload() is trying to load classes outside of the Config namespace');
        Autoloader::autoload('Sinergi\Config\Config');
        $this->assertTrue(in_array('Sinergi\Config\Config', get_declared_classes()), 'Sinergi\\Config\\Autoloader::autoload() failed to autoload the Sinergi\\Config\\Config class');
    }
}