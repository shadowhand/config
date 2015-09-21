<?php
namespace Sinergi\Config;

use Exception;

class PathCollection extends ArrayCollection
{
    /**
     * @param string|Path $path
     * @return bool
     * @throws Exception
     */
    public function add($path)
    {
        if (!$path instanceof Path) {
            $path = realpath($path);
            if (!is_dir($path)) {
                throw new Exception("Config path ({$path}) is not a valid directory");
            }
            $path = new Path($path);
        }
        $this->container[] = $path;
        return true;
    }
}
