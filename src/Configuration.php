<?php

namespace Sinergi\Config;

use Dotenv\Dotenv;
use Sinergi\Config\Path\PathCollection;

class Configuration
{
    /**
     * @var array
     */
    public static $env;

    /**
     * @var PathCollection
     */
    protected $paths;

    /**
     * @var Dotenv
     */
    protected $dotenv;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @param array|Configuration $config
     */
    public function __construct($config)
    {
        $this->paths = new PathCollection();
        if (is_array($config)) {
            if (isset($config['path'])) {
                $this->paths->add($config['path']);
            }
            if (isset($config['paths'])) {
                foreach ($config['paths'] as $path) {
                    $this->paths->add($path);
                }
            }
            if (isset($config['environment'])) {
                $this->setEnvironment($config['environment']);
            }
            if (isset($config['dotenv'])) {
                $this->setDotenv(new Dotenv($config['dotenv']));
            }
        } elseif ($config instanceof Configuration) {
            $this->setPaths($config->getPaths());
            $this->setEnvironment($config->getEnvironment());
            if ($config->getDotenv()) {
                $this->setDotenv($config->getDotenv());
            }
        }
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
     * @param null|string $environment
     * @return $this
     */
    public function setEnvironment($environment)
    {
        if ($this->environment !== $environment) {
            if (method_exists($this, 'reset')) {
                $this->reset();
            }
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
            if (method_exists($this, 'reset')) {
                $this->reset();
            }
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
     * @return Dotenv
     */
    public function getDotenv()
    {
        return $this->dotenv;
    }

    /**
     * @param Dotenv $dotenv
     * @return $this
     */
    public function setDotenv(Dotenv $dotenv)
    {
        $this->dotenv = $dotenv;
        $this->loadDotenv();
        return $this;
    }

    private function loadDotenv()
    {
        $retval = [];
        $values = $this->dotenv->load();
        foreach ($values as $value) {
            $parts = explode("=", $value, 2);
            $retval[$parts[0]] = eval('return ' . $parts[1] . ';');
        }
        return self::$env = $retval;
    }
}
