---
layout: homepage
---
<!-- markdownlint-disable first-line-heading -->
{% assign version = site.data.project.default_version %}
<!-- markdownlint-restore -->

# Features

## Expressive, Fluent API

```php
use League\Config\Configuration;
use Nette\Schema\Expect;

$config = new Configuration([
    'database' => Expect::structure([
        'driver' => Expect::anyOf('mysql', 'postgresql', 'sqlite')->required(),
        'host' => Expect::string()->default('localhost'),
        'port' => Expect::int()->min(1)->max(65535),
        'database' => Expect::string()->required(),
        'username' => Expect::string()->required(),
        'password' => Expect::string()->nullable(),
    ]),
]);
```

## Easily Access Nested Values

```php
echo $config->get('database.driver');

// or using slashes, if you prefer that syntax:
echo $config->get('database/driver');
```

## Set Options Individually Or Together

```php
use League\Config\Configuration;

$config = new Configuration([/*...*/]);

$config->merge([
    'database' => [
        'driver' => 'mysql',
        'port' => 3306,
        'host' => 'localhost',
        'database' => 'myapp',
        'username' => 'myappdotcom',
        'password' => 'hunter2',
    ],
]);

if ($_ENV['APP_ENV'] === 'prod') {
    $config->set('payment_gateway.test_mode', false);
}
```

## Combine Multiple Schemas Into One

```php
use League\Config\Configuration;

$config = new Configuration();
$config->addSchema('database', DB::getConfigSchema());
$config->addSchema('logging', Logger::getConfigSchema());
$config->addSchema('mailer', Mailer::getConfigSchema());
```
