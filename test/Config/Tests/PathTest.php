<?php
namespace Config\Tests;

use Config;
use Exception;
use PHPUnit_Framework_TestCase;

class PathTest extends PHPUnit_Framework_TestCase
{
    public function testSetPath()
    {
        Config::setPath(__DIR__ . "/__files");
        $path = Config::getPath();
        $this->assertEquals(__DIR__ . "/__files", $path);
    }

    /**
     * @expectedException \Exception
     */
    public function testBadPath()
    {
        Config::setPath("/this/path/does/not/exists");
    }
}