---
layout: default
title: Reading Validated Options
description: Reading the validated, processed options
---

# Reading Validated Options

Once the [schema has been defined](/1.0/schemas/) and the [user options have been set](/1.0/setting-values/) you are ready to read them elsewhere in your application! Thanks to [lazy processing](/1.0/lazy-processing/), this library will apply the schemas and validations only if/when you attempt to read a value.

The processed, validated configuration options can be read using the `get()` method. Simply pass in the name of the option you wish to fetch:

```php
use League\Config\Configuration;
use Nette\Schema\Expect;

$config = new Configuration([
    'debug_mode' => Expect::bool()->required(),
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

// TODO: Set the configuration values
$config->merge([/* ... */]);

var_dump($config->get('debug_mode')); // a string
var_dump($config->get('database'));   // an array
```

You can access nested options using "dot access" expressions like this:

```php
var_dump($config->get('database.driver')); // a string

// slashes can also be used instead of dots:
var_dump($config->get('database/driver')); // a string
```

## Undefined Options

If you attempt to `get()` an option that was not defined in the schema an exception will be thrown.  This is probably not an issue in cases where you wrote the schema and know it should always contain certain options.  But in some cases (perhaps you're conditionally adding certain schemas) you might want to first check whether an option is defined before attempting to read it - you can do that with the `exists()` method.  It takes the same arguments as `get()` but will return `true` or `false` based on whether that item exists.
