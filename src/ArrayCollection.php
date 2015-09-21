<?php

namespace Sinergi\Config;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class ArrayCollection implements Countable, IteratorAggregate, ArrayAccess
{
    /**
     * @var array
     */
    protected $container = [];

    /**
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        $this->container = $elements;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->container;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->container;
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]) || array_key_exists($offset, $this->container);
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @param string $offset
     * @return null
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->container);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->container);
    }

    /**
     * @param string $key
     * @return null|mixed
     */
    public function get($key)
    {
        if (isset($this->container[$key])) {
            return $this->container[$key];
        }
        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->container[$key] = $value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function add($value)
    {
        $this->container[] = $value;
        return true;
    }

    /**
     * @param string $key
     * @return null|mixed
     */
    public function remove($key)
    {
        if (isset($this->container[$key]) || array_key_exists($key, $this->container)) {
            $removed = $this->container[$key];
            unset($this->container[$key]);

            return $removed;
        }
        return null;
    }

    public function removeAll()
    {
        $this->container = [];
    }
}
