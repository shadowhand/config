<?php
namespace Config;

use Exception;
use InvalidArgumentException;

/**
 * Config class
 *
 * @package Config
 */
class Config
{
    /** @var array $configs */
    private $configs = [];

    /** @var null|string $namespace */
    private $namespace = null;

    /** @var null|string $environment */
    private $environment = null;

    /** @var null|string $path */
    private $path = null;

    /**
     * @param null|string $path
     * @param null|string $environment
     */
    public function __construct($path = null, $environment = null)
    {
        $this->setPath($path);
        $this->setEnvironment($environment);
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

        list($file, $key, $sub) = Helper::getKey($name);

        if (!isset($this->configs[$file])) {
            $this->configs[$file] = Helper::loadFile(
                $this->path,
                $this->environment,
                $file
            );
        }

        return Helper::getValue($this->configs[$file], $key, $sub, $default);
    }

    /**
     * @param null|string $path
     * @throws Exception
     * @return $this
     */
    public function setPath($path = null)
    {
        if (null !== $path) {
            $path = realpath($path);
            if (!is_dir($path)) {
                throw new Exception("Config path ({$path}) is not a valid directory");
            }
        }
        $this->path = $path;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        return $this->path;
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
}