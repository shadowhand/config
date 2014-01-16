<?php
namespace Config\Tests;

use Config\Config;
use PHPUnit_Framework_TestCase;

class EnvironmentTest extends PHPUnit_Framework_TestCase
{
    public function testSetEnvironment()
    {
        $config = new Config(null, 'tests');
        $env = $config->getEnvironment();
        $this->assertEquals("tests", $env);
    }
}