<?php
namespace Sinergi\Config\Tests;

use Sinergi\Config\Config;
use PHPUnit_Framework_TestCase;

class EnvironmentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    private $config;

    public function setUp()
    {
        $this->config = new Config(__DIR__ . "/__files", "tests");
    }

    public function testSetEnvironment()
    {
        $env = $this->config->getEnvironment();
        $this->assertEquals("tests", $env);
    }

    public function testConfigGetWholeFile()
    {
        $test = $this->config->get('database');
        $this->assertEquals("pdo_mysql", $test['connections']['default']['driver']);
        $this->assertEquals("xxxxxxxxxxxxxxxxxxx", $test['connections']['default']['password']);
    }

    public function testEGetter()
    {
        $test = $this->config->get('app.timezone');
        $this->assertEquals("Europe/Berlin", $test);
    }

    public function testConfigGetterDefault()
    {
        $test = $this->config->get('app.test', "this a test");
        $this->assertEquals("this a test", $test);
    }

    public function testConfigGetterDefaultExists()
    {
        $test = $this->config->get('app.timezone', "this a test");
        $this->assertEquals("Europe/Berlin", $test);
    }

    public function testConfigGetterArray()
    {
        $test = $this->config->get('database.connections.default');
        $this->assertEquals("127.0.0.1", $test['host']);
        $this->assertEquals("xxxxxxxxxxxxxxxxxxx", $test['password']);
    }

    public function testConfigGetterArrayDefault()
    {
        $test = $this->config->get('database.connections.default.persistent', true);
        $this->assertTrue($test);
    }

    public function testConfigGetterArrayDefaultExists()
    {
        $test = $this->config->get('database.connections.default.host', 'localhost');
        $this->assertEquals("127.0.0.1", $test);
    }
}
