<?php
namespace Config\Tests;

use Config;
use Exception;
use PHPUnit_Framework_TestCase;

class NamespaceTest extends PHPUnit_Framework_TestCase
{
    public function testSetPath()
    {
        Config::n('test')->setPath(__DIR__ . "/__files");
        $path = Config::n('test')->getPath();
        $this->assertEquals(__DIR__ . "/__files", $path);
    }

    public function testSetEnvironment()
    {
        Config::n('test')->setEnvironment("tests");
        $env = Config::n('test')->getEnvironment();
        $this->assertEquals("tests", $env);
    }
}