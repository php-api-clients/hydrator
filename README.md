# Hydrator for API Clients for PHP 7.x

[![License](https://poser.pugx.org/api-clients/hydrator/license.png)](https://packagist.org/packages/api-clients/hydrator)
![Linux Build](https://blog.wyrihaximus.net/images/linux-logo-icon-20.png)[![Build Status](https://travis-ci.org/php-api-clients/hydrator.svg?branch=master)](https://travis-ci.org/php-api-clients/hydrator)
![Windows Build](https://blog.wyrihaximus.net/images/windows-logo-icon-20.png)[![Build status](https://ci.appveyor.com/api/projects/status/jp1hmn4wrcjnnwpl?svg=true)](https://ci.appveyor.com/project/WyriHaximus/hydrator)
[![Code Coverage](https://scrutinizer-ci.com/g/php-api-clients/hydrator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/php-api-clients/hydrator/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/api-clients/hydrator/v/stable.png)](https://packagist.org/packages/api-clients/hydrator)
[![Total Downloads](https://poser.pugx.org/api-clients/hydrator/downloads.png)](https://packagist.org/packages/api-clients/hydrator/stats)
[![PHP 7 ready](http://php7ready.timesplinter.ch/php-api-clients/hydrator/badge.svg)](https://travis-ci.org/php-api-clients/hydrator)

In a nutshell this package is a wrapper around [`ocramius/generated-hydrator`](https://github.com/Ocramius/GeneratedHydrator) adding some annotations for nesting, collections, and renaming properties.

# Install

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require api-clients/hydrator
```

# Preheating

In order to ensure the hydrator doesn't block the hydrator comes with a `preheat` method. Give it a the path of a namespace and the namespace it self, and it will create a hydrator for each resource it finds plus read the annotations for the given resource. This ensures all disk IO and heavy CPU operations have been completed before using the hydrator. When using the hydrator in async code the hydrator should, preferable, be created before running the loop.

# Set up

Before using the Hydrator it has to be set up, note that using this method of setting up it will also preheat the hydrator.

```php
$loop = LoopFactory::create();
$commandBus = new CommandBus(); // Implementation of ApiClients\Tools\CommandBus\CommandBusInterface
$options = []; // Options as described below
$hydrator = Factory::create($loop, $commandBus, $options);
```

# Hydrating

The hydrator offers two methods of hydrating. The first is a method that accepts FQCN (Fully Qualified Class Name), for example `ApiClients\Client\Github\Resource\Async\Emoji` or `Emoji::class` for short, and the JSON holding the resource contents.

```php
$resource = $hydrator->hydrateFQCN(Emoji::class, $json);
```

Or when you've configured `Options::NAMESPACE`, `Options::NAMESPACE_SUFFIX` you can do the same with the `hydrate` method, which internally uses `hydrateFQCN`:

```php
$resource = $hydrator->hydrate('Emoji', $json);
```

# Extracting

A resource can also be broken down again into JSON with the hydrator.

```php
$json = $hydrator->extractFQCN(Emoji::class, $resource);
```

Same magic as the `hydrate` method applies to the `extract` method, this does exactly the same as `extractFQCN` when `Options::NAMESPACE` and `Options::NAMESPACE_SUFFIX` are configured.
```php
$json = $hydrator->extract('Emoji', $resource);
```

# Options

## Options::ANNOTATIONS

Supply an array with extra annotations in the format key => annotation, value => handler.

## Options::ANNOTATION_CACHE_DIR

Cache directory for resource annotations.

## Options::NAMESPACE

Base namespace where the resources reside, required.

## Options::NAMESPACE_DIR

Filesystem path to the base namespace where the resources reside, required. 

## Options::NAMESPACE_SUFFIX

Namespaces suffix, useful for different types of the same resource.

## Options::RESOURCE_CACHE_DIR

Cache directory for resource generated resources.

## Options::RESOURCE_NAMESPACE

Namespace for generated resources.

# License

The MIT License (MIT)

Copyright (c) 2017 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
