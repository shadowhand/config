<?php

namespace Sinergi\Config\Tests;

use Sinergi\Config\Collection;
use PHPUnit_Framework_TestCase;
use Sinergi\Config\Factory;

class FactoryTest extends PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $factory = new Factory();
        /** @var Collection $collection */
        $collection = $factory([]);
        $this->assertInstanceOf(Collection::class, $collection);
    }
}
