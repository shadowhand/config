<?php

namespace Sinergi\Config\Path;

class Path implements PathInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @param null|string $path
     */
    public function __construct($path = null)
    {
        if ($path) {
            $this->setPath($path);
        }
    }

    /**
     * @param string $path
     * @return $this
     * @throws PathNotFoundException
     */
    public function setPath($path)
    {
        $path = realpath($path);
        if (!is_dir($path)) {
            throw new PathNotFoundException("Config path ({$path}) is not a valid directory");
        }
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getPath();
    }
}
