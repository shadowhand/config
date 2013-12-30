<?php

class Config
{
    /** @var array $namespaces */
    private static $namespaces = [];

    /** @var Config\Instance $defaultClass */
    private static $defaultClass;

    /**
     * @param string $dir
     * @param string $env
     * @param string $file
     * @return array
     */
    public static function loadFile($dir, $env, $file)
    {
        $array1 = $array2 = [];
        $file = "{$file}.php";

        if (file_exists($dir . DIRECTORY_SEPARATOR . $file)) {
            $array1 = require $dir . DIRECTORY_SEPARATOR . $file;
            if (!is_array($array1)) {
                $array1 = [];
            }
        }
        if (null !== $env && file_exists($dir . DIRECTORY_SEPARATOR . $env . DIRECTORY_SEPARATOR . $file)) {
            $array2 = require $dir . DIRECTORY_SEPARATOR . $env . DIRECTORY_SEPARATOR . $file;
            if (!is_array($array2)) {
                $array2 = [];
            }
        }
        return self::mergeArrays($array1, $array2);
    }

    /**
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function mergeArrays(array $array1, array $array2)
    {
        $retval = $array1;
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($retval[$key])) {
                $retval[$key] = self::mergeArrays($retval[$key], $value);
            } else {
                $retval[$key] = $value;
            }
        }
        return $retval;
    }

    /**
     * @param string $name
     * @return array
     */
    public static function getKey($name)
    {
        $file = $key = $sub = null;
        $parts = explode('.', $name);
        if (isset($parts[0])) {
            $file = $parts[0];
        }
        if (isset($parts[1])) {
            $key = $parts[1];
        }
        if (isset($parts[2])) {
            $sub = [];
            foreach (array_slice($parts, 2) as $subkey) {
                $sub[] = $subkey;
            }
        }

        return [$file, $key, $sub];
    }

    /**
     * @param array $haystack
     * @param null|string $key
     * @param null|array $sub
     * @param null|mixed $default
     * @return mixed
     */
    public static function getValue(array $haystack = null, $key = null, $sub = null, $default = null)
    {
        if (empty($key) && !isset($haystack)) {
            return $default;
        } elseif (empty($key)) {
            if (!isset($haystack) && !empty($default)) {
                return $default;
            } elseif (isset($haystack)) {
                return $haystack;
            }
            return null;
        } elseif (!empty($key) && empty($sub)) {
            if (empty($haystack[$key]) && !empty($default)) {
                return $default;
            } elseif (isset($haystack[$key])) {
                return $haystack[$key];
            }
            return null;
        } elseif (is_array($sub)) {
            $array = isset($haystack[$key]) ? $haystack[$key] : [];
            $value = self::findInMultiArray($sub, $array);
            if (empty($value) && !empty($default)) {
                return $default;
            } elseif (isset($value)) {
                return $value;
            }
            return null;
        }
        return null;
    }

    /**
     * @param array $needle
     * @param array $haystack
     * @return mixed
     */
    private static function findInMultiArray(array $needle, array $haystack)
    {
        $currentNeedle = current($needle);
        $needle = array_slice($needle, 1);
        if (isset($haystack[$currentNeedle]) && is_array($haystack[$currentNeedle]) && count($needle)) {
            return self::findInMultiArray($needle, $haystack[$currentNeedle]);
        } elseif (isset($haystack[$currentNeedle]) && !is_array($haystack[$currentNeedle]) && count($needle)) {
            return null;
        } elseif (isset($haystack[$currentNeedle])) {
            return $haystack[$currentNeedle];
        }
        return null;
    }

    /**
     * @param string $namespace
     * @return Config\Instance
     */
    public static function n($namespace)
    {
        $key = strtolower($namespace);
        if (!isset(self::$namespaces[$key])) {
            if (isset(self::$defaultClass) && strtolower(self::$defaultClass->getNamespace()) == $key) {
                self::$namespaces[$key] = self::$defaultClass;
            } else {
                self::$namespaces[$key] = (new Config\Instance)->setNamespace($namespace);
            }
        }

        return self::$namespaces[$key];
    }

    /**
     * @param Config\Instance $obj
     * @param string $oldNamespace
     * @param string $newNamespace
     */
    public static function changeConfigNamespace($obj, $oldNamespace, $newNamespace)
    {
        $oldNamespace = strtolower($oldNamespace);
        $newNamespace = strtolower($newNamespace);
        if (isset(self::$namespaces[$oldNamespace])) {
            unset(self::$namespaces[$oldNamespace]);
        }
        self::$namespaces[$newNamespace] = $obj;
    }

    /**
     * Set a config
     *
     * @param string $name
     * @param array $config
     * @return Config\Instance
     */
    public static function set($name, $config)
    {
        if (!self::$defaultClass instanceof Config\Instance) {
            self::$defaultClass = new Config\Instance;
        }
        return self::$defaultClass->set($name, $config);
    }

    /**
     * Get a config
     *
     * @param string $name
     * @param mixed $default
     * @throws InvalidArgumentException
     * @return mixed
     */
    public static function get($name, $default = null)
    {
        if (!self::$defaultClass instanceof Config\Instance) {
            self::$defaultClass = new Config\Instance;
        }
        return self::$defaultClass->get($name, $default);
    }

    /**
     * @param null|string $path
     * @throws Exception
     * @return Config\Instance
     */
    public static function setPath($path)
    {
        if (!self::$defaultClass instanceof Config\Instance) {
            self::$defaultClass = new Config\Instance;
        }
        self::$defaultClass->setPath($path);
        return self::$defaultClass;
    }

    /**
     * @return null|string
     */
    public static function getPath()
    {
        if (!self::$defaultClass instanceof Config\Instance) {
            self::$defaultClass = new Config\Instance;
        }
        return self::$defaultClass->getPath();
    }

    /**
     * @param null|string $environment
     * @return Config\Instance
     */
    public static function setEnvironment($environment)
    {
        if (!self::$defaultClass instanceof Config\Instance) {
            self::$defaultClass = new Config\Instance;
        }
        return self::$defaultClass->setEnvironment($environment);
    }

    /**
     * @return Config\Instance
     */
    public static function removeEnvironment()
    {
        if (!self::$defaultClass instanceof Config\Instance) {
            self::$defaultClass = new Config\Instance;
        }
        return self::$defaultClass->removeEnvironment();
    }

    /**
     * @return null|string
     */
    public static function getEnvironment()
    {
        if (!self::$defaultClass instanceof Config\Instance) {
            self::$defaultClass = new Config\Instance;
        }
        return self::$defaultClass->getEnvironment();
    }

    /**
     * @return Config\Instance
     */
    public static function reset()
    {
        if (!self::$defaultClass instanceof Config\Instance) {
            self::$defaultClass = new Config\Instance;
        }
       return self::$defaultClass->reset();
    }
}