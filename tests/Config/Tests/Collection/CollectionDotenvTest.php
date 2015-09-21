<?php

namespace Sinergi\Config\Tests\Collection;

use PHPUnit_Framework_TestCase;
use Sinergi\Config\Collection;

class CollectionDotenvTest extends PHPUnit_Framework_TestCase
{
    public function testDotenv()
    {
        $config = Collection::factory([
            'path' => __DIR__ . "/../__files",
            'dotenv' => __DIR__ . "/../__files"
        ]);

        $this->assertEquals('hello', $config->get('dotenv.test_var'));
    }
}
