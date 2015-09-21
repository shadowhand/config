<?php

namespace Sinergi\Config\Loader;

use Symfony\Component\Yaml\Parser;

class YamlLoader implements LoaderInterface
{
    const EXTENSION = 'yaml';

    /**
     * @var Parser
     */
    protected static $parser;

    /**
     * @return Parser
     */
    protected static function getParser()
    {
        if (null === self::$parser) {
            self::$parser = new Parser();
        }
        return self::$parser;
    }

    public static function load($file)
    {
        return self::getParser()->parse(file_get_contents($file));
    }
}
