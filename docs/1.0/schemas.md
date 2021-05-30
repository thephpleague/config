---
layout: default
title: Schemas
description: Defining the structure of your configurations, along with validation, default values, and more
---

# Configuration Schemas

A "schema" defines the exact structure of arrays, keys, and values that users can configure.  By using a schema, you can:

- Enforce that certain expected options are set by marking them `required`
- Provide `default` values when none are provided by the user
- Validate that values are certain types, numbers within ranges, match regular expressions, or contain specific sets of values (like enums)
- Mark old options as `deprecated`
- Cast values to certain types, including both scalars and custom objects

This library includes [the `nette/schema` package](https://doc.nette.org/en/3.1/schema) to define and process the schemas. You use their `Expect` class to define the schemas, passing those into our library which handles all of the processing for you.  You can find some example of that below, but we highly recommending reading their documentation for complete details on all the options available to you.

## Example

```php
use League\Config\Configuration;
use Nette\Schema\Expect;

// Define your configuration schema
$config = new Configuration([
    'debug_mode' => Expect::bool(),
    'database' => Expect::structure([
        'driver' => Expect::anyOf('mysql', 'postgresql', 'sqlite')->required(),
        'host' => Expect::string()->default('localhost'),
        'port' => Expect::int()->min(1)->max(65535),
        'ssl' => Expect::bool(),
        'database' => Expect::string()->required(),
        'username' => Expect::string()->required(),
        'password' => Expect::string()->nullable(),
    ]),
    'logging' => Expect::structure([
        'enabled' => Expect::bool()->default($_ENV['DEBUG'] == true),
        'file' => Expect::string()->deprecated("use logging.path instead"),
        'path' => Expect::string()->assert(function ($path) { return \is_writeable($path); })->required(),
    ]),
]);
```

**Tip:** By default, `nette/schema` assumes that nested options (`Expect::structure`) will be cast to `stdClass` objects, but we automatically cast those to `array` instead, so don't worry about doing that yourself.

## Merging Schemas

You might have different systems or components that define their own schemas:

```php
use Nette\Schema\Expect;use Nette\Schema\Schema;

class DatabaseConnection
{
    public static function getSchema(): Schema
    {
        return Expect::structure([
            'driver' => Expect::anyOf('mysql', 'postgresql', 'sqlite')->required(),
            'host' => Expect::string()->default('localhost'),
            'port' => Expect::int()->min(1)->max(65535),
            'ssl' => Expect::bool(),
            'database' => Expect::string()->required(),
            'username' => Expect::string()->required(),
            'password' => Expect::string()->nullable(),
        ]);
    }
}

class Logger
{
    public static function getSchema(): Schema
    {
        return Expect::structure([
            'enabled' => Expect::bool()->default($_ENV['DEBUG'] == true),
            'file' => Expect::string()->deprecated("use logging.path instead"),
            'path' => Expect::string()->assert(function ($path) { return \is_writeable($path); })->required(),
        ]);
    }
}
```

You can combine these into a single `Configuration` using the constructor and/or the `addSchema()` method.  All three examples in the snippet below will achieve the same result:

```php
use League\Config\Configuration;

$config = new Configuration([
    'database' => DatabaseConnection::getSchema(),
    'logging' => Logger::getSchema(),
]);

// or

$config = new Configuration([
    'database' => DatabaseConnection::getSchema(),
]);

$config->addSchema('logging', Logger::getSchema());

// or

$config = new Configuration();
$config->addSchema('database', DatabaseConnection::getSchema());
$config->addSchema('logging', Logger::getSchema());
```

## Schema Types

See [the `nette/schema` documentation](https://doc.nette.org/en/3.1/schema) for full details on the different `Expect` options you can use to define different types of schemas.
