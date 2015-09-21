<?php

namespace Sinergi\Config\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\Config\Configuration;
use Sinergi\Config\Path\PathCollection;

class PathCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $pathCollection = new PathCollection();
        $pathCollection->add(__DIR__ . "/../__files");
        $this->assertEquals(realpath(__DIR__ . "/../__files"), $pathCollection->get(0)->getPath());
    }

    /**
     * @expectedException \Sinergi\Config\Path\PathNotFoundException
     */
    public function testAddBadPath()
    {
        $pathCollection = new PathCollection();
        $pathCollection->add("this/path/does/not/exists");
    }

    public function testRemoveAll()
    {
        $pathCollection = new PathCollection();
        $pathCollection->add(__DIR__ . "/../__files");
        $pathCollection->add(__DIR__ . "/../__files/config2");
        $pathCollection->removeAll();
        $this->assertCount(0, $pathCollection);
    }

    public function testConfigConstructor()
    {
        $config = new Configuration([
            'paths' => [
                __DIR__ . "/../__files",
                __DIR__ . "/../__files/config2",
            ]
        ]);
        $this->assertCount(2, $config->getPaths());
        $this->assertEquals(realpath(__DIR__ . "/../__files"), $config->getPaths()->get(0)->getPath());
        $this->assertEquals(realpath(__DIR__ . "/../__files/config2"), $config->getPaths()->get(1)->getPath());
    }
}
