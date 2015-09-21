<?php

namespace Sinergi\Config;

use Sinergi\Config\Path\PathCollection;

class Configuration
{
    /**
     * @var PathCollection
     */
    protected $paths;

    /**
     * @var string
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
                $this->setDotenv($config['dotenv']);
            }
        } else if ($config instanceof Configuration) {
            $this->setPaths($config->getPaths());
            $this->setEnvironment($config->getEnvironment());
            $this->setDotenv($config->getDotenv());
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
     * @return string
     */
    public function getDotenv()
    {
        return $this->dotenv;
    }

    /**
     * @param string $dotenv
     * @return $this
     */
    public function setDotenv($dotenv)
    {
        $this->dotenv = $dotenv;
        return $this;
    }
}
