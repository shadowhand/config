<?php
namespace Sinergi\Config;

class Loader
{
    /**
     * @param PathCollection $paths
     * @param string $env
     * @param string $file
     * @return array
     */
    public function loadFile(PathCollection $paths, $env, $file)
    {
        $array1 = $array2 = array();
        $file = "{$file}.php";

        $retval = array();
        foreach ($paths as $path) {
            if (file_exists($path . DIRECTORY_SEPARATOR . $file)) {
                $array1 = require $path . DIRECTORY_SEPARATOR . $file;
                if (!is_array($array1)) {
                    $array1 = array();
                }
            }
            if (null !== $env && file_exists($path . DIRECTORY_SEPARATOR . $env . DIRECTORY_SEPARATOR . $file)) {
                $array2 = require $path . DIRECTORY_SEPARATOR . $env . DIRECTORY_SEPARATOR . $file;
                if (!is_array($array2)) {
                    $array2 = array();
                }
            }
            $retval = $this->mergeArrays($retval, $array1, $array2);
        }

        return $retval;
    }

    /**
     * @param array $array1
     * @param array $array2
     * @param array $array3
     * @return array
     */
    public function mergeArrays(array $array1, array $array2, array $array3 = null)
    {
        $retval = $array1;
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($retval[$key])) {
                $retval[$key] = $this->mergeArrays($retval[$key], $value);
            } else {
                $retval[$key] = $value;
            }
        }
        if (null !== $array3) {
            $retval = $this->mergeArrays($retval, $array3);
        }
        return $retval;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getKey($name)
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
            $sub = array();
            foreach (array_slice($parts, 2) as $subkey) {
                $sub[] = $subkey;
            }
        }

        return array($file, $key, $sub);
    }

    /**
     * @param array $haystack
     * @param null|string $key
     * @param null|array $sub
     * @param null|mixed $default
     * @return mixed
     */
    public function getValue(array $haystack = null, $key = null, $sub = null, $default = null)
    {
        if (empty($key) && !isset($haystack)) {
            return $default;
        } elseif (empty($key)) {
            if (!isset($haystack) && null !== $default) {
                return $default;
            } elseif (isset($haystack)) {
                return $haystack;
            }
            return null;
        } elseif (!empty($key) && empty($sub)) {
            if (empty($haystack[$key]) && null !== $default) {
                return $default;
            } elseif (isset($haystack[$key])) {
                return $haystack[$key];
            }
            return null;
        } elseif (is_array($sub)) {
            $array = isset($haystack[$key]) ? $haystack[$key] : [];
            $value = $this->findInMultiArray($sub, $array);
            if (empty($value) && null !== $default) {
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
    private function findInMultiArray(array $needle, array $haystack)
    {
        $currentNeedle = current($needle);
        $needle = array_slice($needle, 1);
        if (isset($haystack[$currentNeedle]) && is_array($haystack[$currentNeedle]) && count($needle)) {
            return $this->findInMultiArray($needle, $haystack[$currentNeedle]);
        } elseif (isset($haystack[$currentNeedle]) && !is_array($haystack[$currentNeedle]) && count($needle)) {
            return null;
        } elseif (isset($haystack[$currentNeedle])) {
            return $haystack[$currentNeedle];
        }
        return null;
    }
}
