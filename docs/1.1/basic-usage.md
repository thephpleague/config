---
layout: default
title: Basic Usage
description: Basic usage of the league/config library
---

# Basic Usage

There are three steps to using this library:

- [Defining the configuration schema](/1.1/schemas/)
  - The overall structure of the configuration
  - Required options, validation constraints, and default values
- [Applying user-provided values](/1.1/setting-values/) against the schema
- [Reading the validated options](/1.1/reading-values/) and acting on them

## Example

Simply define your configuration schema, set the values, and then fetch them where needed:

```php
use League\Config\Configuration;
use Nette\Schema\Expect;

// Define your configuration schema
$config = new Configuration([
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

// Set the values somewhere
$userProvidedValues = [
    'database' => [
        'driver' => 'mysql',
        'port' => 3306,
        'host' => 'localhost',
        'database' => 'myapp',
        'username' => 'myappdotcom',
        'password' => 'hunter2',
    ],
    'logging' => [
        'path' => '/var/log/myapp.log',
    ],
];

// Merge those values into your configuration schema:
$config->merge($userProvidedValues);

// Read the values and do stuff with them
if ($config->get('logging.enabled')) {
    file_put_contents($config->get('logging.path'), 'Connecting to the database on ' . $config->get('database.host'));
}
```
