<?php

function env($key, $default = null)
{
    $dotenv = \Sinergi\Config\Configuration::$env;
    if (is_array($dotenv) && isset($dotenv[$key])) {
        return $dotenv[$key];
    }
    return $default;
}
