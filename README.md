<a href="https://aimeos.org/">
    <img src="https://aimeos.org/fileadmin/template/icons/logo.png" alt="Aimeos logo" title="Aimeos" align="right" height="60" />
</a>

# Aimeos Slim package
[![Build Status](https://travis-ci.org/aimeos/aimeos-slim.svg)](https://travis-ci.org/aimeos/aimeos-slim)
[![Coverage Status](https://coveralls.io/repos/aimeos/aimeos-slim/badge.svg?branch=master&service=github)](https://coveralls.io/github/aimeos/aimeos-slim?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/aimeos/aimeos-slim/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/aimeos/aimeos-slim/?branch=master)

The repository contains the shop package for Slim 3,
integrating the Aimeos e-commerce library into Slim. The package provides
controllers for e.g. faceted filter, product lists and detail views, for
searching products as well as baskets and the checkout process. A full set of
pages including routing is also available for a quick start.

## Table of content

- [Installation/Update](#installation-or-update)
- [Setup](#setup)
- [Admin](#admin)
- [Hints](#hints)
- [License](#license)
- [Links](#links)

## Installation or update

This document is for the latest Aimeos SlimPHP **2017.10 release and later**.

- LTS release: 2017.10

This tutorial assumes a directory layout as used in the Slim skeleton application
created by:

```composer create-project slim/slim-skeleton [my-app-name]```

The Aimeos Slim e-commerce package is a composer based library that can be
installed easiest by using [Composer](https://getcomposer.org). Add these lines
to your `composer.json` of your Slim project:

```
    "prefer-stable": true,
    "minimum-stability": "dev",
    "require": {
        "aimeos/aimeos-slim": "~2017.10",
        ...
    },
```

Afterwards, install the Aimeos shop package using

`composer update`

The next step is to **copy the required configuration and route files** to your `src/`
directory so you have your own copy you can modify according to your needs. When
you upgrade from a previous version, you should have a backup of these files. You
can then reapply the changes you've made in the past to the updated files.

```
cp vendor/aimeos/aimeos-slim/src/aimeos-settings.php src/
cp vendor/aimeos/aimeos-slim/src/aimeos-routes.php src/
```

To configure your database, you have to **adapt the configuration** in `src/aimeos-settings.php`
file and modify the settings in the resource section. If you want to use a
database server other than MySQL, please have a look into the article about
[supported database servers](https://aimeos.org/docs/Developers/Library/Database_support)
and their specific configuration.

Setting up or upgrading existing tables in the database is done via:

```
php vendor/aimeos/aimeos-core/setup.php --config=src/aimeos-settings.php --option=setup/default/demo:1
```

In a production environment or if you don't want that the demo data is
added, leave out the `--option=setup/default/demo:1` option.

You must also **copy the Aimeos templates** to the `templates/` directory of your Slim
application. Thus, you can modify them according to your needs and they won't be
overwritten by the next composer update:

```
cp -r vendor/aimeos/aimeos-slim/templates/* templates/
```

The last step is to **publish the Aimeos theme files** to the `public/` directory, so they
are available via HTTP:

```
mkdir -p public/aimeos/themes/ public/aimeos/admin/extjs/
cp -r vendor/aimeos/aimeos-slim/resources/mimeicons/ public/aimeos/
cp -r ext/ai-client-html/client/html/themes/* public/aimeos/themes/
cp -r ext/ai-admin-jqadm/admin/jqadm/themes/* public/aimeos/themes/
cp -r ext/ai-admin-extadm/admin/extjs/lib/ public/aimeos/admin/extjs/
cp -r ext/ai-admin-extadm/admin/extjs/resources/ public/aimeos/admin/extjs/
```

## Setup

Aimeos requires some objects to be available (like the Aimeos context) and the
routes for generating the URLs. Both are added automatically if you **add the lines
starting with $aimeos** right after the `$app = new \Slim\App($settings);` statement
in your `public/index.php` file:

```php
$app = new \Slim\App($settings);

$aimeos = new \Aimeos\Slim\Bootstrap( $app, require '../src/aimeos-settings.php' );
$aimeos->setup( '../ext' )->routes( '../src/aimeos-routes.php' );

// Set up dependencies
```

The Aimeos Slim package uses the Twig template engine to render the templates.
Therefore, you have to **setup the view object** with a configured Twig instance.
Copy the lines below at the end of your `src/dependencies.php` file:

```php
// Twig view + Aimeos templates
$container['view'] = function ($c) {
	$conf = ['cache' => '../cache'];
	$view = new \Slim\Views\Twig(__DIR__ . '/../templates', $conf);
	$view->addExtension(new \Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
	return $view;
};
```
Note: You can use the Slim PHP template engine as well if you reimplement the existing
templates in PHP, but Twig has one major advantage: Templates can inherit from
a common base template, so you don't have to copy the whole HTML page into each
template.

**Caution:** The Slim skeleton application contain a route for `/[{name}]` in `src/routes.php`
which you have to remove first. It's so generic that it shadows routes from Aimeos!

Then, you should be able to call the catalog list page in your browser. For a
quick start, you can use the integrated web server that is available since PHP 5.4.
Simply execute this command in the base directory of your application:

`php -S 127.0.0.1:8000 -t public`

Point your browser to the list page of the shop using:

http://127.0.0.1:8000/list

## Admin

The Aimeos package for the Slim PHP framework also contains an administration
interface for managing products and other content. If the internal PHP web server
(`php -S 127.0.0.1:8000 -t public`) is still running, you can find it at:

http://127.0.0.1:8000/admin

**Caution:** It's important to protect the administration interface with a
password or some other kind of authentication!

The easiest way is to add HTTP basic authentication (the browser is asking for
user name and password) to all `/admin` URLs. In Slim, there's a middleware
which you can add to your application. To install it, execute

`composer require tuupola/slim-basic-auth`

on the command line in your application directory. Afterwards, adapt your
`public/index.php` file and add these lines before `$app->run()`:

```php
$app->add(new \Slim\Middleware\HttpBasicAuthentication([
	"realm" => "Aimeos administration",
	"path" => "/admin",
	"users" => [
		"admin" => "secret",
	],
]));
```

**Note:** The "users" array can contain a list of user name / password
combinations and you need to use a **really secret password**!

## Hints

To simplify development, you should configure to use no content cache. You can
do this in the `src/aimeos-settings.php` file of your Slim application by adding
these lines at the bottom:

```php
    'madmin' => array(
        'cache' => array(
            'manager' => array(
                'name' => 'None',
            ),
        ),
    ),
```

If caching is enabled, you have to execute the following command to clear the
cache if you change e.g. configuration settings:

```
php vendor/aimeos/aimeos-slim/cache.php --config=src/aimeos-settings.php
```

## License

The Aimeos Slim package is licensed under the terms of the LGPLv3 license and
is available for free.

## Links

* [Web site](https://aimeos.org/)
* [Documentation](https://aimeos.org/docs/)
* [Forum](https://aimeos.org/help/)
* [Issue tracker](https://github.com/aimeos/aimeos-slim/issues)
* [Composer packages](https://packagist.org/packages/aimeos/aimeos-slim)
* [Source code](https://github.com/aimeos/aimeos-slim)
