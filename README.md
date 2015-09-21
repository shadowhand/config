# Config

[![Build Status](https://travis-ci.org/sinergi/config.svg?branch=develop)](https://travis-ci.org/sinergi/config)
[![StyleCI](https://styleci.io/repos/15445499/shield)](https://styleci.io/repos/15445499)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sinergi/config/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sinergi/config/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/sinergi/config/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sinergi/config/?branch=master)
[![Code Climate](https://codeclimate.com/github/sinergi/config/badges/gpa.svg)](https://codeclimate.com/github/sinergi/config)
[![Latest Stable Version](http://img.shields.io/packagist/v/sinergi/config.svg?style=flat)](https://packagist.org/packages/sinergi/config)
[![Total Downloads](https://img.shields.io/packagist/dt/sinergi/config.svg?style=flat)](https://packagist.org/packages/sinergi/config)
[![License](https://img.shields.io/packagist/l/sinergi/config.svg?style=flat)](https://packagist.org/packages/sinergi/config)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/47b2ce95-a810-43dd-823e-482ab8e68ea3/mini.png)](https://insight.sensiolabs.com/projects/47b2ce95-a810-43dd-823e-482ab8e68ea3)
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/sinergi/config?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

PHP configurations loading library. It is made to enable your application to have different configurations depending on
the environment it is running in. For example, your application can have different configurations for unit tests, 
development, staging and production. 

A good practice would be to __not include__ your production or staging configurations in your version control.
To do this, Config supports [Dotenv](https://github.com/vlucas/phpdotenv).

## Requirements

This library uses PHP 5.6+.

## Installation

It is recommended that you install the Config library [through composer](http://getcomposer.org/). To do so, 
run the Composer command to install the latest stable version of Config:

```shell
composer require sinergi/config
```

## Usage

Use the factory to instanciate a Config collection class:

```php
use Sinergi\Config\Collection;

$config = Collection::factory([
    'path' => __DIR__ . "/configs"
]);
```

Optionally, you can also setup the environment. Setting up the environment will merge normal configurations 
with configurations in the environment directory. For example, if you setup the environment to be *prod*, 
the configurations from the directory ``configs/prod/*`` will be loaded on top of the configurations from the 
directory ``configs/*``. Consider the following example:

```php
use Sinergi\Config\Collection;

$config = Collection::factory([
    'path' => __DIR__ . "/configs"
    'environment' => 'prod'
]);
```
```

Optionally, you can also use dotenv to hide sensible information into a `.env` file. To do so, specify a directory
where the `.env` file. Like in this example:

```php
use Sinergi\Config\Collection;

$config = Collection::factory([
    'path' => __DIR__ . "/configs"
    'dotenv' => __DIR__,
    'environment' => 'prod'
]);
```

You can than use the configurations like this:

```php
$config->get('app.timezone');
```

## Getter

The configuration getter uses a simple syntax: ``file_name.array_key``.

For example:

```php
$config->get('app.timezone');
```

You can optionally set a default value like this:

```php
$config->get('app.timezone', "America/New_York");
```

You can use the getter to access multidimensional arrays in your configurations:

```php
$config->get('database.connections.default.host');
```

## Setter

Alternatively, you can set configurations from your application code:

```php
$config->set('app.timezone', "Europe/Berlin");
```

You can set entire arrays of configurations:

```php
$config->set('database', [
    'host' => "localhost",
    'dbname' => "my_database",
    'user' => "my_user",
    'password' => "my_password"
]);
```

## Examples

See more examples in the [examples folder](https://github.com/sinergi/config/tree/master/examples).

### PHP Configuration File

Example of a PHP configuration file:

```php
return [
    'timezone' => "America/New_York"
];
```

### Yaml Configuration File

Example of a YAML configuration file:

```php
timezone: America/New_York
```

### Dotenv

Example of using Dotenv in a PHP configuration file:

```php
return [
    'timezone' => env('TIMEZONE', "America/New_York")
];
```

And in the `.env` file:

```
TIMEZONE="America/Chicago"
```

## License

Config is licensed under The MIT License (MIT).