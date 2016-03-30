<?php

return array(
	'apc_enabled' => false,
	'apc_prefix' => 'slim:',
	'uploaddir' => '/.',

	'page' => array(
		'account-index' => array( 'account/history','account/favorite','account/watch','basket/mini','catalog/session' ),
		'basket-index' => array( 'basket/standard','basket/related' ),
		'catalog-count' => array( 'catalog/count' ),
		'catalog-detail' => array( 'basket/mini','catalog/stage','catalog/detail','catalog/session' ),
		'catalog-list' => array( 'basket/mini','catalog/filter','catalog/stage','catalog/lists' ),
		'catalog-stock' => array( 'catalog/stock' ),
		'catalog-suggest' => array( 'catalog/suggest' ),
		'checkout-confirm' => array( 'checkout/confirm' ),
		'checkout-index' => array( 'checkout/standard' ),
		'checkout-update' => array( 'checkout/update'),
	),

	'routes' => array(
		// 'admin' => '/admin',
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
			'database' => 'slim',
			'username' => 'root',
			'password' => '',
			'stmt' => array( "SET NAMES 'utf8'", "SET SESSION sql_mode='ANSI'" ),
			'opt-persistent' => 0,
			'limit' => 2,
		),
		'fs' => array(
			'adapter' => 'Standard',
			'basedir' => './',
		),
		'fs-admin' => array(
			'adapter' => 'Standard',
			'basedir' => './uploads',
		),
		'fs-secure' => array(
			'adapter' => 'Standard',
			'basedir' => '../secure',
		),
		'mq' => array(
			'adapter' => 'Standard',
			'db' => 'db',
		),
	),

	'client' => array(
		'html' => array(
			'common' => array(
				'content' => array(
					'baseurl' => '/',
				),
				'template' => array(
					'baseurl' => './aimeos/elegance',
				),
			),
		),
	),

	'controller' => array(
		'extjs' => array(
			'attribute' => array(
				'export' => array(
					'text' => array(
						'default' => array(
							'downloaddir' => 'uploads',
						),
					),
				),
			),
			'catalog' => array(
				'export' => array(
					'text' => array(
						'default' => array(
							'downloaddir' => 'uploads',
						),
					),
				),
			),
			'media' => array(
				'default' => array(
					'mimeicon' => array(
						# Directory where icons for the mime types stored
						'directory' => './aimeos/mimeicons',
						# File extension of mime type icons
						'extension' => '.png'
					),
					# Parameters for images
					'files' => array(
						# Allowed image mime types, other image types will be converted
						# allowedtypes: [image/jpeg, image/png, image/gif ]
						# Image type to which all other image types will be converted to
						# defaulttype: jpeg
						# Maximum width of an image
						# Image will be scaled up or down to this size without changing the
						# width/height ratio. A value of "null" doesn't scale the image or
						# doesn't restrict the size of the image if it's scaled due to a value
						# in the "maxheight" parameter
						# maxwidth:
						# Maximum height of an image
						# Image will be scaled up or down to this size without changing the
						# width/height ratio. A value of "null" doesn't scale the image or
						# doesn't restrict the size of the image if it's scaled due to a value
						# in the "maxwidth" parameter
						# maxheight:
						# Parameter for preview images
					),
					# Parameters for preview images
					'preview' => array(
						# Allowed image mime types, other image types will be converted
						# allowedtypes: [image/jpeg, image/png, image/gif ]
						# Image type to which all other image types will be converted to
						# defaulttype: jpeg
						# Maximum width of a preview image
						# Image will be scaled up or down to this size without changing the
						# width/height ratio. A value of "null" doesn't scale the image or
						# doesn't restrict the size of the image if it's scaled due to a value
						# in the "maxheight" parameter
						# maxwidth: 360
						# Maximum height of a preview image
						# Image will be scaled up or down to this size without changing the
						# width/height ratio. A value of "null" doesn't scale the image or
						# doesn't restrict the size of the image if it's scaled due to a value
						# in the "maxwidth" parameter
						# maxheight: 280
					),
				),
			),
			'product' => array(
				'export' => array(
					'text' => array(
						'default' => array(
							'downloaddir' => 'uploads',
						),
					),
				),
			),
		),
	),

	'i18n' => array(
	),

	'madmin' => array(
	),

	'mshop' => array(
		'index' => array(
			'manager' => array(
				'name' => 'MySQL',
				'attribute' => array(
					'name' => 'MySQL',
				),
				'catalog' => array(
					'name' => 'MySQL',
				),
				'price' => array(
					'name' => 'MySQL',
				),
				'text' => array(
					'name' => 'MySQL',
				),
			),
		),
	),

);
