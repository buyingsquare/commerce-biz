<a href="https://aimeos.org/">
    <img src="https://aimeos.org/fileadmin/template/icons/logo.png" alt="Aimeos logo" title="Aimeos" align="right" height="60" />
</a>

# Aimeos Slim package
[![Build Status](https://travis-ci.org/aimeos/aimeos-slim.svg)](https://travis-ci.org/aimeos/aimeos-slim)
[![Coverage Status](https://coveralls.io/repos/aimeos/aimeos-slim/badge.svg?branch=master&service=github)](https://coveralls.io/github/aimeos/aimeos-slim?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/aimeos/aimeos-slim/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/aimeos/aimeos-slim/?branch=master)
[![HHVM Status](http://hhvm.h4cc.de/badge/aimeos/aimeos-slim.svg)](http://hhvm.h4cc.de/package/aimeos/aimeos-slim)

The repository contains the shop package for Slim 3,
integrating the Aimeos e-commerce library into Slim. The package provides
controllers for e.g. faceted filter, product lists and detail views, for
searching products as well as baskets and the checkout process. A full set of
pages including routing is also available for a quick start.

## Table of content

- [Installation/Update](#installation-or-update)
- [Setup](#setup)
- [Hints](#hints)
- [License](#license)
- [Links](#links)

## Installation or update

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
        "aimeos/aimeos-slim": "dev-master",
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

To configure your database, you have to **adapt the configuration** in `src/aimeos-settings.php` file and
modify the settings in the resource section. Setting up or upgrading existing tables
in the database is done via:

```
php vendor/aimeos/aimeos-slim/setup.php --config=src/aimeos-settings.php --option=setup/default/demo:1
```

In a production environment or if you don't want that the demo data is
added, leave out the `--option=setup/default/demo:1` option.

You must also **copy the Aimeos templates** to the `templates/` directory of your Slim
application. Thus, you can modify them according to your needs and they won't be
overwritten by the next composer update:

```
cp -r vendor/aimeos/aimeos-slim/templates/ templates/
```

The last step is to **publish the Aimeos theme files** to the `public/` directory, so they
are available via HTTP:

```
mkdir public/aimeos
cp -r vendor/aimeos/aimeos-core/client/html/themes/ public/aimeos/
```

## Setup

Aimeos requires some objects to be available (like the Aimeos context) and the
routes for generating the URLs. Both are added automatically if you **add these
two lines** right before the `$app-run()` statement if your `public/index.php`
file:

```
$aimeos = new \Aimeos\Slim\Bootstrap( $app, require '../src/aimeos-settings.php' );
$aimeos->setup( '../ext' )->routes( '../src/aimeos-routes.php' );

// Run app
$app->run();
```

The Aimeos Slim package uses the Twig template engine to render the templates.
Therefore, you have to **setup the view object** with a configured Twig instance.
Copy the lines below at the end of your `src/dependencies.php` file:

```
// Twig view + Aimeos templates
$container['view'] = function ($c) {
	$conf = ['cache' => '../cache'];
	$view = new \Slim\Views\Twig('../templates', $conf);
	$view->addExtension(new \Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
	return $view;
};
```
Note: You can use the Slim PHP template engine as well if you reimplement the existing
templates in PHP, but Twig has one major advantage: Templates can inherit from
a common base template, so you don't have to copy the whole HTML page into each
template.

Then, you should be able to call the catalog list page in your browser. For a
quick start, you can use the integrated web server that is available since PHP 5.4.
Simply execute this command in the base directory of your application:

```php -S 127.0.0.1:8000 -t public```

Point your browser to the list page of the shop using:

http://127.0.0.1:8000/list

## Hints

To simplify development, you should configure to use no content cache. You can
do this in the `src/aimeos-settings.php` file of your Slim application by adding
these lines at the bottom:

```
    'madmin' => array(
        'cache' => array(
            'manager' => array(
                'name' => 'None',
            ),
        ),
    ),
```

## License

The Aimeos Slim package is licensed under the terms of the LGPLv3 license and
is available for free.

## Links

* [Web site](https://aimeos.org/)
* [Documentation](https://aimeos.org/docs/)
* [Help](https://aimeos.org/help/)
* [Issue tracker](https://github.com/aimeos/aimeos-slim/issues)
* [Composer packages](https://packagist.org/packages/aimeos/aimeos-slim)
* [Source code](https://github.com/aimeos/aimeos-slim)
