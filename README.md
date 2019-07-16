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

## TODO

- Add `phpdoc` documentation
