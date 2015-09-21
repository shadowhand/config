<?php

namespace Sinergi\Config;

use Exception;
use InvalidArgumentException;
use ArrayAccess;

class Config implements ArrayAccess
{
    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var array
     */
    private $configs = array();

    /**
     * @var null|string
     */
    private $namespace = null;

    /**
     * @var null|string
     */
    private $environment = null;

    /**
     * @var PathCollection
     */
    private $paths = array();

    /**
     * @param null|array|string $path
     * @param null|string $environment
     */
    public function __construct($path = null, $environment = null)
    {
        $this->loader = new Loader;
        if (null !== $path) {
            if (is_string($path)) {
                $this->paths = new PathCollection();
                $this->paths->add($path);
            } elseif (is_array($path)) {
                $this->paths = new PathCollection($path);
            }
        } else {
            $this->paths = new PathCollection();
        }
        $this->setEnvironment($environment);
    }

    /**
     * @return PathCollection
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @param PathCollection $pathCollection
     * @return $this
     */
    public function setPaths(PathCollection $pathCollection)
    {
        $this->paths = $pathCollection;
        return $this;
    }

    /**
     * Set a config
     *
     * @param string $name
     * @param array $config
     * @return $this
     */
    public function set($name, $config)
    {
        if (!isset($this->configs[$name])) {
            $this->configs[$name] = $config;
        } else {
            $this->configs[$name] = array_merge($this->configs[$name], $config);
        }
        return $this;
    }

    /**
     * @param string $name
     */
    public function remove($name)
    {
        unset($this->configs[$name]);
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

        list($file, $key, $sub) = $this->loader->getKey($name);

        if (!isset($this->configs[$file])) {
            $this->configs[$file] = $this->loader->loadFile(
                $this->paths,
                $this->environment,
                $file
            );
        }

        return $this->loader->getValue($this->configs[$file], $key, $sub, $default);
    }

    /**
     * @param null|string $path
     * @throws Exception
     * @return $this
     * @deprecated use path collection
     */
    public function setPath($path = null)
    {
        $this->reset();
        $this->paths->removeAll();
        if (null !== $path) {
            $this->paths->add($path);
        }
        return $this;
    }

    /**
     * @return null|string
     * @deprecated use path collection
     */
    public function getPath()
    {
        return $this->paths->get(0);
    }

    /**
     * @param null|string $environment
     * @return $this
     */
    public function setEnvironment($environment)
    {
        if ($this->environment !== $environment) {
            $this->reset();
        }
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return $this
     */
    public function removeEnvironment()
    {
        if ($this->environment !== null) {
            $this->reset();
        }
        $this->environment = null;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param null|string $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->configs = null;
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
