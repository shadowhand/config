<?php

namespace Sinergi\Config\Tests\Collection;

use Sinergi\Config\Collection;
use PHPUnit_Framework_TestCase;

class CollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    private $config;

    /**
     * @var Collection
     */
    private $configMultiplePaths;

    public function setUp()
    {
        $this->config = Collection::factory([
            'path' => __DIR__ . "/../__files"
        ]);
        $this->configMultiplePaths = Collection::factory([
            'paths' => [__DIR__ . "/../__files", __DIR__ . "/../__files/config2"]
        ]);
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

    public function testMultiplePaths()
    {
        $test = $this->configMultiplePaths->get('app.timezone');
        $this->assertEquals("Asia/Hong_Kong", $test);
        $test = $this->configMultiplePaths->get('config2.config2');
        $this->assertEquals("another config directory", $test);
    }
}
