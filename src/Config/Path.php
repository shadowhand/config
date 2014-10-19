<?php
namespace Sinergi\Config;

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
        $this->path = $path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
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
