<?php

namespace Sinergi\Config;

require_once __DIR__ . "/DotenvHelper.php";

use InvalidArgumentException;
use ArrayAccess;

class Collection extends Configuration implements ArrayAccess
{
    /**
     * @param array|Configuration $config
     * @return Collection
     */
    public static function factory($config)
    {
        $factory = new Factory();
        return $factory($config);
    }

    /**
     * @var array
     */
    private $container = [];

    /**
     * Set a config
     *
     * @param string $name
     * @param array $config
     * @return $this
     */
    public function set($name, $config)
    {
        if (!isset($this->container[$name])) {
            $this->container[$name] = $config;
        } else {
            $this->container[$name] = array_merge($this->container[$name], $config);
        }
        return $this;
    }

    /**
     * @param string $name
     */
    public function remove($name)
    {
        unset($this->container[$name]);
    }

    /**
     * Get a config
     *
     * @param string $name
     * @param mixed $default
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (!is_string($name) || empty($name)) {
            throw new InvalidArgumentException("Parameter \$name passed to Config::get() is not a valid string ressource");
        }

        list($file, $key, $sub) = Parser::getKey($name);

        if (!isset($this->container[$file])) {
            $this->container[$file] = Loader::load(
                $this->paths,
                $this->environment,
                $file
            );
        }

        return Parser::getValue($this->container[$file], $key, $sub, $default);
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->container = null;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param string $name
     * @param array|null $args
     * @return mixed
     */
    public function __call($name, $args = null)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        $item = $this->get($offset);
        return isset($item);
    }

    /**
     * @param int $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param int $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
