<?php
namespace Config\Tests;

use Config;
use PHPUnit_Framework_TestCase;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Config::setPath(__DIR__ . "/__files");
    }

    public function testGetWholeFile()
    {
        $test = Config::get('database');
        $this->assertEquals("pdo_mysql", $test['connections']['default']['driver']);
    }

    public function testGetter()
    {
        $test = Config::get('app.timezone');
        $this->assertEquals("America/New_York", $test);
    }

    public function testGetterDefault()
    {
        $test = Config::get('app.test', "this a test");
        $this->assertEquals("this a test", $test);
    }

    public function testGetterDefaultExists()
    {
        $test = Config::get('app.timezone', "this a test");
        $this->assertEquals("America/New_York", $test);
    }

    public function testGetterArray()
    {
        $test = Config::get('database.connections.default');
        $this->assertEquals("127.0.0.1", $test['host']);
    }

    public function testGetterArrayDefault()
    {
        $test = Config::get('database.connections.default.persistent', true);
        $this->assertTrue($test);
    }

    public function testGetterArrayDefaultExists()
    {
        $test = Config::get('database.connections.default.host', 'localhost');
        $this->assertEquals("127.0.0.1", $test);
    }
}