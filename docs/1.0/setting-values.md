---
layout: default
title: Setting User-Provided Values
description: How to apply user-provided values against the configuration schema
---

# Setting User-Provided Values

Once your [schema](/1.0/schemas/) has been defined you can apply user-provided configuration values.  There are two methods available to do this:

- `set($key, $value)` - Define a single value
- `merge($values)` - Define multiple values in one call

## Example

Let's take the following configuration as an example:

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
```

You could set everything at once like this:

```php
$config->merge([
    'debug_mode' => false,
    'database' => [
        'driver' => 'mysql',
        'port' => 3306,
        'database' => 'myapp',
        'username' => 'myapp_user',
        'password' => 'hunter2',
    ],
    'logging' => [
        'enabled' => true,
        'file' => '/var/log/myapp.log',
    ],
]);
```

Maybe that array of data comes from a different file or method:

```php
$config->merge(getUserConfig());

// or

$config->merge(json_decode(file_get_contents('database.json'), true));
```

Options can also be set individually, if needed:

```php
// Load config from a json file...
$config->merge(json_decode(file_get_contents('database.json'), true));
// But override a certain setting
$config->set('debug_mode', false);
```
