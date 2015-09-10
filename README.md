WP Change Domain
=============

The wordpress change domain is used to perform an update of url's in a wordpress database project.

Installation
------------

The installation process can be performed by the way:

### Cloning and Install

```sh
$ git clone https://github.com/jpcercal/wp-change-domain
$ cd wp-change-domain/
$ composer install
$ bower install
$ npm install
$ grunt production
```

### Setup

```sh
$ cp .env.example .env
$ mkdir -p storage/cache
$ mkdir -p storage/logs
$ chmod -Rf 777 storage/
```

### Running a WebServer

```sh
$ php -S 0.0.0.0:8080 -t public public/index.php
```

Documentation
-------------

This package is compatible with PSR-2 and PSR-4.

### Routes

- */api/change-domain*:
    - **POST**: Generate a set of sql queries.
- */*:
    - **GET**: Application with AngularJS.

License
-------

This package is under the MIT license. [See the complete license](https://github.com/jpcercal/wp-change-domain/blob/master/LICENSE).