<?php

namespace Sinergi\Config\Loader;

class PhpLoader implements LoaderInterface
{
    const EXTENSION = 'php';

    public static function load($file)
    {
        return require $file;
    }
}
