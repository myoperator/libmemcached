# Myoperator Libmemcached

This library is intended to be used as a basic `memcached` connector with few adddons as required in myopeator projects. Otherwise, this is same as using `memcached` library.

## Features

* Namespaces and defines
* PSR-4 autoloading compliant structure
* Unit-Testing with PHPUnit
* Easy to use to any framework or even a plain php file

## Installation

You can easily install this package by adding following section in your composer.json:

```
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/myoperator/libmemcached.git"
        }
    ]
```
and then doing: `composer require myoperator/libmemcached:dev-master`

or by adding following to your composer.json:

```
  "require": {
        "myoperator/libmemcached": "dev-master"
  }
```

The `composer.json` will look like

```json
{
    "require": {
        "myoperator/libmemcached": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/myoperator/libmemcached.git"
        }
    ]
}
```

## Usage

1. Include `vendor/autoload` in your project
```php
   include_once 'vendor/autoload.php';
```

2. Configure the package

```php
  use \MyOperator\LibMemcached;

  $memcache = LibMemcached::getInstance(['host' => 'localhost', 'port' => '11211']); //defaults to 127.0.0.1:11211

  // Get value
  $val = $memcache->get('a'); // Null
  $memcache->set('a', 1); // a = 1
  $memcache->increment('a'); // a =2
  $memcache->decrement('a'); // a =1
```

## Mocking

Since this is a memcache library, hence any code using this library, tends to avoid actual network requests (which this library
makes) for its unit tests. 

To prevent those network scenarios, this library conviniently provides a mocking instance to test some of the memcached's most
used methods, which includes:

- get
- set
- increment
- decrement
- addServer
- getVersion

To mock this library in any framework, you can simple use this sample code

```php

$mockedMemcacheInstance = \MyOperator\LibMemcached::getMockInstance();

// Now you can pretty much do anything you'd expect with memcache, to simulate unit test behaviours
// For example, setting and getting values is easy :)

$mockedMemcacheInstance->set('key', 'val');

$mockedMemcacheInstance->get('key'); //returns 'val'
```

Please see the `tests/unit` directory to see what methods can be mocked and how to use the mocks.

## TODO

- Add `phpdoc` documentation
