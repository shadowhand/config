<?php

namespace Sinergi\Config;

use Sinergi\Config\Loader\LoaderInterface;
use Sinergi\Config\Loader\YamlLoader;
use Sinergi\Config\Path\PathCollection;
use Sinergi\Config\Loader\PhpLoader;

class Loader
{
    protected static $loaders = [
        PhpLoader::EXTENSION => PhpLoader::class,
        YamlLoader::EXTENSION => YamlLoader::class,
    ];

    /**
     * @param PathCollection $paths
     * @param string $env
     * @param string $file
     * @return array
     */
    public static function load(PathCollection $paths, $env, $file)
    {
        $retval = [];
        foreach ($paths as $path) {
            $array1 = self::loadFile($path, null, $file);
            $array2 = self::loadFile($path, $env, $file);
            $retval = self::mergeArrays($retval, $array1, $array2);
        }
        return $retval;
    }

    /**
     * @param string $path
     * @param string $env
     * @param string $file
     * @return array
     */
    public static function loadFile($path, $env, $file)
    {
        $retval = [];
        foreach (self::$loaders as $fileType => $loader) {
            $file = "{$file}.{$fileType}";

            if ($env) {
                $path = $path . DIRECTORY_SEPARATOR . $env . DIRECTORY_SEPARATOR . $file;
            } else {
                $path = $path . DIRECTORY_SEPARATOR . $file;
            }

            if (file_exists($path)) {
                if (is_string($loader)) {
                    $loader = self::$loaders[$fileType] = new $loader;
                }
                if ($loader instanceof LoaderInterface) {
                    $retval = $loader::load($path);
                    if (!is_array($retval)) {
                        $retval = [];
                    }
                }
            }
        }
        return $retval;
    }

    /**
     * @param array $array1
     * @param array $array2
     * @param array $array3
     * @return array
     */
    public static function mergeArrays(array $array1, array $array2, array $array3 = null)
    {
        $retval = $array1;
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($retval[$key])) {
                $retval[$key] = self::mergeArrays($retval[$key], $value);
            } else {
                $retval[$key] = $value;
            }
        }
        if (null !== $array3) {
            $retval = self::mergeArrays($retval, $array3);
        }
        return $retval;
    }
}
