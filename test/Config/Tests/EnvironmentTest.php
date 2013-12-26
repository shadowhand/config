<?php
namespace Config\Tests;

use Config;
use PHPUnit_Framework_TestCase;

class EnvironmentTest extends PHPUnit_Framework_TestCase
{
    public function testSetEnvironment()
    {
        Config::setEnvironment("tests");
        $env = Config::getEnvironment();
        $this->assertEquals("tests", $env);
    }
}