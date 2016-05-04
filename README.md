# WP Change Domain

[![Build Status](https://img.shields.io/travis/jpcercal/wp-change-domain/master.svg?style=square)](http://travis-ci.org/jpcercal/wp-change-domain)
[![Code Climate](https://codeclimate.com/github/jpcercal/wp-change-domain/badges/gpa.svg)](https://codeclimate.com/github/jpcercal/wp-change-domain)
[![Coverage Status](https://coveralls.io/repos/jpcercal/wp-change-domain/badge.svg)](https://coveralls.io/r/jpcercal/wp-change-domain)
[![license](https://img.shields.io/github/license/jpcercal/wp-change-domain.svg?style=square)](https://github.com/jpcercal/wp-change-domain)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3f5a5e97-7e66-4375-889c-0e011f8b08c8/mini.png)](https://insight.sensiolabs.com/projects/3f5a5e97-7e66-4375-889c-0e011f8b08c8)

The wp-change-domain application is used to build a set of SQL commands and with that, update all urls of a wordpress installation (with all methods covered by php unit tests).

## Installation

- The source files is [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) compatible.
- Autoloading is [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) compatible.

To install this web application you must run this commands on your terminal.

```sh
$ git clone https://github.com/jpcercal/wp-change-domain
$ cd wp-change-domain/
$ composer install
$ bower install
$ npm install
$ grunt production
$ cp .env.example .env
$ mkdir -p storage/cache
$ mkdir -p storage/logs
$ chmod -Rf 777 storage/
```

### Running a Web Server

After the steps that you followed to install this application, you must run a web server. So, type the following command on your terminal to create a web server.

```sh
$ php -S 0.0.0.0:8080 -t public public/index.php
```

Note that this command must be executed on root directory of this web application.

## Documentation

This web application was developed using AngularJS and with it you can change easily the domain of your wordpress installation.

Well, you can see below the HTTP routes where this application will handle your requests.

**GET** */* will load the web application form.

**POST** */api/change-domain* will receive a content type as `application/json` with the following content:

```json
{
    "tablePrefix": "wp_",
    "numberOfBlogs": 1,
    "domainTo": "http://www.your.old.domain",
    "domainFrom": "http://your.new.domain"
}
```

This request will create a response where the content type will be `application/json` with the following content:

```json
{
    "sql":[
        "UPDATE wp_options SET option_value = REPLACE(option_value, \u0027http:\/\/www.your.old.domain\u0027, \u0027http:\/\/your.new.domain\u0027) WHERE option_name = \u0027home\u0027 OR option_name = \u0027siteurl\u0027 OR option_name = \u0027ck_wp_panel_custom\u0027;",
        "UPDATE wp_posts SET guid = REPLACE(guid, \u0027http:\/\/www.your.old.domain\u0027, \u0027http:\/\/your.new.domain\u0027);",
        "UPDATE wp_posts SET post_content = REPLACE(post_content, \u0027http:\/\/www.your.old.domain\u0027, \u0027http:\/\/your.new.domain\u0027);",
        "UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, \u0027http:\/\/www.your.old.domain\u0027, \u0027http:\/\/your.new.domain\u0027);",
        "UPDATE wp_site SET domain = REPLACE(domain, \u0027http:\/\/www.your.old.domain\u0027, \u0027http:\/\/your.new.domain\u0027);",
        "UPDATE wp_blogs SET domain = REPLACE(domain, \u0027http:\/\/www.your.old.domain\u0027, \u0027http:\/\/your.new.domain\u0027);"
    ]
}
```

Contributing
------------

1. Give me a star **=)**
2. Fork it
3. Create your feature branch (`git checkout -b my-new-feature`)
4. Make your changes
5. Commit your changes (`git commit -am 'Added some feature'`)
6. Push to the branch (`git push origin my-new-feature`)
7. Create new Pull Request
