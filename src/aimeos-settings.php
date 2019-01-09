<?php

return array(

	// 'apc_enabled' => false,
	// 'apc_prefix' => 'slim:',
	// 'uploaddir' => '/',

	'page' => array(
		// 'account-index' => array( 'account/profile','account/subscription','account/history','account/favorite','account/watch','basket/mini','catalog/session' ),
		// 'basket-index' => array( 'basket/standard','basket/related' ),
		// 'catalog-count' => array( 'catalog/count' ),
		// 'catalog-detail' => array( 'basket/mini','catalog/stage','catalog/detail','catalog/session' ),
		// 'catalog-list' => array( 'basket/mini','catalog/filter','catalog/stage','catalog/lists' ),
		// 'catalog-stock' => array( 'catalog/stock' ),
		// 'catalog-suggest' => array( 'catalog/suggest' ),
		// 'checkout-confirm' => array( 'checkout/confirm' ),
		// 'checkout-index' => array( 'checkout/standard' ),
		// 'checkout-update' => array( 'checkout/update'),
	),

	// route prefixes, e.g. {site}, {locale} and {currency} resp. {site} and {lang} for /admin/*
	'routes' => array(
		// 'admin' => '/admin',
		// 'extadm' => '/admin/{site}/extadm',
		// 'jqadm' => '/admin/{site}/jqadm',
		// 'jsonadm' => '/admin/{site}/jsonadm',
		// 'jsonapi' => '/jsonapi',
		// 'account' => '',
		// 'default' => '',
		// 'confirm' => '',
		// 'update' => '',
	),

	'resource' => array(
		'db' => array(
			'adapter' => 'mysql',
			'host' => 'localhost',
			'port' => '',
			'socket' => '',
			'database' => 'slim',
			'username' => 'root',
			'password' => '',
			'stmt' => ["SET SESSION sort_buffer_size=2097144; SET NAMES 'utf8mb4'; SET SESSION sql_mode='ANSI'"],
			'opt-persistent' => 0,
			'limit' => 3,
			'defaultTableOptions' => [
				'charset' => 'utf8mb4',
				'collate' => 'utf8mb4_bin',
			],
		),
		'fs' => array(
			'adapter' => 'Standard',
			'basedir' => './',
			'baseurl' => '/', // change to https://<yourdomain>/
		),
	),

	'client' => array(
		'html' => array(
			'common' => array(
				'template' => array(
					// 'baseurl' => './aimeos/elegance',
				),
			),
		),
	),

	'controller' => array(
	),

	'i18n' => array(
	),

	'madmin' => array(
	),

	'mshop' => array(
	),


	'command' => array(
	),

	'backend' => array(
	),

	'frontend' => array(
	),

);
