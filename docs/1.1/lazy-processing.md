---
layout: default
title: Lazy Processing
description: Lazy processing defers the processing and validation until you try to read config values
---

# Lazy Processing

We delay the processing and validation of the [user-provided values](/1.1/setting-values/) until the first time you attempt to [read or check a value](/1.1/reading-values/).  This provides flexibility as to when the schemas and user-provided values are set.

For example, you might need to set the values from different sources. Thanks to lazy processing we won't actually validate the schema until you try to access a value.  Because of this, you might not know there's a validation issue until you attempt to read a value:

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

// Load configuration from a file.
// For the sake of this example, let's assume the user didn't set 'debug_mode'.
// Even though this is a required option, we don't validate the schema immediately so you can add it later.
$config->merge(json_decode(file_get_contents('database.json'), true));

if ($_ENV['APP_ENV'] === 'prod') {
    $config->set('debug_mode', false);
    $config->merge(json_decode(file_get_contents('config.prod.json'), true));
}

// This call below will throw an exception if "debug_mode" wasn't set at all
if ($config->get('debug_mode')) {
    // ...
}
```
