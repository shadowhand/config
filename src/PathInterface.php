<?php
namespace Sinergi\Config;

interface PathInterface
{
    /**
     * @param $path
     * @return $this
     */
    public function setPath($path);

    /**
     * @return string
     */
    public function getPath();

    /**
     * @return string
     */
    public function __toString();
}
