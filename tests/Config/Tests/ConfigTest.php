<?php
namespace Sinergi\Config\Tests;

use Sinergi\Config\Config;
use PHPUnit_Framework_TestCase;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    private $config;

    public function setUp()
    {
        $this->config = new Config(__DIR__ . "/__files");
    }

    public function testGetWholeFile()
    {
        $test = $this->config->get('database');
        $this->assertEquals("pdo_mysql", $test['connections']['default']['driver']);
    }

    public function testGetter()
    {
        $test = $this->config->get('app.timezone');
        $this->assertEquals("America/New_York", $test);
    }

    public function testGetterDefault()
    {
        $test = $this->config->get('app.test', []);
        $this->assertEquals([], $test);
    }

    public function testGetterEmptyDefault()
    {
        $test = $this->config->get('app.test', "this a test");
        $this->assertEquals("this a test", $test);
    }

    public function testGetterDefaultExists()
    {
        $test = $this->config->get('app.timezone', "this a test");
        $this->assertEquals("America/New_York", $test);
    }

    public function testGetterArray()
    {
        $test = $this->config->get('database.connections.default');
        $this->assertEquals("127.0.0.1", $test['host']);
    }

    public function testGetterArrayDefault()
    {
        $test = $this->config->get('database.connections.default.persistent', true);
        $this->assertTrue($test);
    }

    public function testGetterArrayDefaultExists()
    {
        $test = $this->config->get('database.connections.default.host', 'localhost');
        $this->assertEquals("127.0.0.1", $test);
    }

    public function testArrayAccess()
    {
        $test = $this->config['app']['timezone'];
        $this->assertEquals("America/New_York", $test);
    }
}
