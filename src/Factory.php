<?php

namespace Sinergi\Config;

use Interop\Config\ConfigurationTrait;
use Interop\Config\RequiresMandatoryOptions;
use Interop\Config\RequiresConfig;

class Factory implements RequiresMandatoryOptions, RequiresConfig
{
    const VENDOR_NAME = 'sinergi';
    const PACKAGE_NAME = 'config';

    use ConfigurationTrait;

    /**
     * @param array|Configuration $config
     * @return Collection
     */
    public function __invoke($config)
    {
        if (is_array($config)) {
            $config = new Configuration($config);
        }

        return new Collection($config);
    }

    /**
     * @return string
     */
    public function vendorName()
    {
        return self::VENDOR_NAME;
    }

    /**
     * @return string
     */
    public function packageName()
    {
        return self::PACKAGE_NAME;
    }

    /**
     * @return iterable List with mandatory options
     */
    public function mandatoryOptions(): iterable
    {
        return [];
    }

    /**
     * @return string[] List with optional options
     */
    public function optionalOptions()
    {
        return [
            'path',
            'paths',
            'environment',
            'dotenv',
        ];
    }

    /**
     * @return iterable
     */
    public function dimensions(): iterable {
        return [];
    }
}
