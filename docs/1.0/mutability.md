---
layout: default
title: Mutability
description: When/how to update schemas and config values, and how to prevent others from doing so
---

# Mutability

Thanks to [lazy processing](/1.0/lazy-processing/), you can [define schemas](/1.0/schemas/) and [set user-provided values](/1.0/setting-values/) at any time and in any order.  This can be very convenient in many cases, but you might have times where you'd like to provide a read-only version of the `Configuration` to ensure nobody else can modify it.

## Read-Only Reader

To do this, simply call `$config->reader()`.  This will return an object that only has the `get()` and `exists()` methods, preventing others from further modifying the configuration:

```php
use League\Config\Configuration;

$config = new Configuration([/* ... */]);

$someOtherObject->setConfig($config->reader());
```

Because both the reader and the main `Configuration` implement `ConfigurationInterface`, you can type-hint against that anywhere you need to retrieve values but not necessarily modify things.
