<?php
namespace Sinergi\Config\Tests;

use Sinergi\Config\Config;
use Exception;
use PHPUnit_Framework_TestCase;

class PathTest extends PHPUnit_Framework_TestCase
{
    public function testSetPath()
    {
        $config = new Config(__DIR__ . "/__files");
        $path = $config->getPath();
        $this->assertEquals(__DIR__ . "/__files", $path);
    }

    /**
     * @expectedException \Exception
     */
    public function testBadPath()
    {
        new Config("/this/path/does/not/exists");
    }
}
