---
layout: default
title: Philosophy
description: This library takes a highly-opinionated approach that fills a gap in the existing config library landscape
---

# Philosophy

There are lots of great configuration libraries out there, each one serving a slightly different purpose. As a result, this library aims to satisfy a particular niche not being served well by others by taking a **simple yet opinionated** approach to configuration with the following goals:

- The configuration should operate on **arrays with nested values** which are easily accessible
- The configuration structure should be **defined with strict schemas** defining the overall structure, allowed types, and allowed values
- Schemas should be defined using a **simple, fluent interface**
- You should be able to **add and combine schemas but never modify existing ones**
- Both the configuration values and the schema should be **defined and managed with PHP code**
- Schemas should be **immutable**; they should never change once they are set
- Configuration values should never define or influence the schemas

As a result, this library will likely **never** support features like:

- Loading and/or exporting configuration values or schemas using YAML, XML, or other files
  - You can still implement this yourself, if needed
- Parsing configuration values from a command line or other user interface
- Dynamically changing the schema, allowed values, or default values based on other configuration values

If you need that functionality you should check out other great libraries like:

- [symfony/config]
- [symfony/options-resolver]
- [hassankhan/config]
- [consolidation/config]
- [laminas/laminas-config]

## Dependencies

To help facilitate this approach, we heavily rely on the following two open-source libraries under-the-hood:

- [`nette/schema`](https://doc.nette.org/en/3.1/schema) to define and process the configuration schemas
- [`dflydev/dot-access-data`](https://github.com/dflydev/dflydev-dot-access-data) to simplify reading/writing the values

These were chosen specifically for being minimal, well-written, and unlikely to conflict with other dependencies you might have installed.

[symfony/config]: https://symfony.com/doc/current/components/config.html
[symfony/options-resolver]: https://symfony.com/doc/current/components/options_resolver.html
[hassankhan/config]: https://github.com/hassankhan/config
[consolidation/config]: https://github.com/consolidation/config
[laminas/laminas-config]: https://docs.laminas.dev/laminas-config/
