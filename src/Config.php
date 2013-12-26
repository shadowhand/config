<?php

class Config
{
    /** @var array $configs */
    private static $configs = [];

    /** @var null|string $environment */
    public static $environment = null;

    /** @var null|string $path */
    public static $path = null;

    /**
     * @param string $file
     */
    private static function loadFile($file)
    {
        $key = $file;
        $array1 = $array2 = [];
        $file = "{$file}.php";

        if (file_exists(self::$path . DIRECTORY_SEPARATOR . $file)) {
            $array1 = require self::$path . DIRECTORY_SEPARATOR . $file;
            if (!is_array($array1)) {
                $array1 = [];
            }
        }
        if (null !== self::$environment && file_exists(self::$path . DIRECTORY_SEPARATOR . self::$environment . DIRECTORY_SEPARATOR . $file)) {
            $array2 = require self::$path . DIRECTORY_SEPARATOR . self::$environment . DIRECTORY_SEPARATOR . $file;
            if (!is_array($array2)) {
                $array2 = [];
            }
        }
        self::$configs[$key] = self::mergeArrays($array1, $array2);
    }

    /**
     * @param array $array1
     * @param array $array2
     * @return array
     */
    private static function mergeArrays(array $array1, array $array2)
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
    private static function getKey($name)
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
     * Set a config
     *
     * @param string $name
     * @param array $config
     * @return void
     */
    public static function set($name, $config)
    {
        if (!isset(self::$configs[$name])) {
            self::$configs[$name] = $config;
        } else {
            self::$configs[$name] = array_merge(self::$configs[$name], $config);
        }
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
        if (!is_string($name) || empty($name)) {
            throw new InvalidArgumentException("Parameter \$name passed to Config::get() is not a valid string ressource");
        }

        list($file, $key, $sub) = self::getKey($name);

        if (!isset(self::$configs[$file])) {
            self::loadFile($file);
        }

        return self::getValue($file, $key, $sub, $default);
    }

    /**
     * @param string $file
     * @param null|string $key
     * @param null|array $sub
     * @param null|mixed $default
     * @return mixed
     */
    private static function getValue($file, $key = null, $sub = null, $default = null)
    {
        if (empty($file) || !isset(self::$configs[$file])) {
            return $default;
        } elseif (empty($key)) {
            if (empty(self::$configs[$file]) && !empty($default)) {
                return $default;
            } elseif (isset(self::$configs[$file])) {
                return self::$configs[$file];
            }
            return null;
        } elseif (!empty($key) && empty($sub)) {
            if (empty(self::$configs[$file][$key]) && !empty($default)) {
                return $default;
            } elseif (isset(self::$configs[$file][$key])) {
                return self::$configs[$file][$key];
            }
            return null;
        } elseif (is_array($sub)) {
            $array = isset(self::$configs[$file][$key]) ? self::$configs[$file][$key] : [];
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
     * @param null|string $path
     * @throws Exception
     */
    public static function setPath($path)
    {
        $path = realpath($path);
        if (!is_dir($path)) {
            throw new Exception("Config path ({$path}) is not a valid directory");
        }
        self::$path = $path;
    }

    /**
     * @return null|string
     */
    public static function getPath()
    {
        return self::$path;
    }

    /**
     * @param null|string $environment
     */
    public static function setEnvironment($environment)
    {
        if (self::$environment !== $environment) {
            self::reset();
        }
        self::$environment = $environment;
    }

    public static function removeEnvironment()
    {
        if (self::$environment !== null) {
            self::reset();
        }
        self::$environment = null;
    }

    /**
     * @return null|string
     */
    public static function getEnvironment()
    {
        return self::$environment;
    }

    public static function reset()
    {
        self::$configs = null;
    }
}