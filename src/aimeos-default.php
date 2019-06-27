<?php

return array(

	'apc_enabled' => false,
	'apc_prefix' => 'slim:',
	'uploaddir' => '/',

	'page' => array(
		'account-index' => array( 'account/profile','account/subscription','account/history','account/favorite','account/watch','basket/mini','catalog/session' ),
		'basket-index' => array( 'basket/standard','basket/related' ),
		'catalog-count' => array( 'catalog/count' ),
		'catalog-detail' => array( 'basket/mini','catalog/stage','catalog/detail','catalog/session' ),
		'catalog-list' => array( 'basket/mini','catalog/filter','catalog/lists' ),
		'catalog-stock' => array( 'catalog/stock' ),
		'catalog-suggest' => array( 'catalog/suggest' ),
		'catalog-tree' => array( 'basket/mini','catalog/filter','catalog/stage','catalog/lists' ),
		'checkout-confirm' => array( 'checkout/confirm' ),
		'checkout-index' => array( 'checkout/standard' ),
		'checkout-update' => array( 'checkout/update'),
	),

	'resource' => array(
		'db' => array(
			'adapter' => 'mysql',
			'host' => 'localhost',
			'port' => '',
			'database' => 'slim',
			'username' => 'root',
			'password' => '',
			'stmt' => array("SET SESSION sort_buffer_size=2097144; SET NAMES 'utf8mb4'; SET SESSION sql_mode='ANSI'"),
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
			'baseurl' => '/',
		),
		'fs-admin' => array(
			'adapter' => 'Standard',
			'basedir' => './uploads',
		),
		'fs-import' => array(
			'adapter' => 'Standard',
			'basedir' => '../secure/import',
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

	'admin' => array(
		'jqadm' => array(
			'url' => array(
				'copy' => array(
					'target' => 'aimeos_shop_jqadm_copy'
				),
				'create' => array(
					'target' => 'aimeos_shop_jqadm_create'
				),
				'delete' => array(
					'target' => 'aimeos_shop_jqadm_delete'
				),
				'export' => array(
					'target' => 'aimeos_shop_jqadm_export'
				),
				'get' => array(
					'target' => 'aimeos_shop_jqadm_get'
				),
				'save' => array(
					'target' => 'aimeos_shop_jqadm_save'
				),
				'search' => array(
					'target' => 'aimeos_shop_jqadm_search'
				),
			)
		),
		'jsonadm' => array(
			'url' => array(
				'target' => 'aimeos_shop_jsonadm_get',
				'config' => array(
					'absoluteUri' => true,
				),
				'options' => array(
					'target' => 'aimeos_shop_jsonadm_options',
					'config' => array(
						'absoluteUri' => true,
					),
				),
			),
		),
	),
	'client' => array(
		'html' => array(
			'account' => array(
				'index' => array(
					'url' => array(
						'target' => 'aimeos_shop_account',
					),
				),
				'subscription' => array(
					'url' => array(
						'target' => 'aimeos_shop_account',
					),
				),
				'history' => array(
					'url' => array(
						'target' => 'aimeos_shop_account',
					),
				),
				'favorite' => array(
					'url' => array(
						'target' => 'aimeos_shop_account_favorite',
					),
				),
				'watch' => array(
					'url' => array(
						'target' => 'aimeos_shop_account_watch',
					),
				),
				'download' => array(
					'url' => array(
						'target' => 'aimeos_shop_account_download',
					),
					'error' => array(
						'url' => array(
							'target' => 'aimeos_shop_account',
						),
					),
				),
			),
			'basket' => array(
				'standard' => array(
					'url' => array(
						'target' => 'aimeos_shop_basket',
					),
				),
			),
			'catalog' => array(
				'count' => array(
					'url' => array(
						'target' => 'aimeos_shop_count',
					),
				),
				'detail' => array(
					'url' => array(
						'target' => 'aimeos_shop_detail',
					),
				),
				'lists' => array(
					'url' => array(
						'target' => 'aimeos_shop_list',
					),
				),
				'session' => array(
					'pinned' => array(
						'url' => array(
							'target' => 'aimeos_shop_session_pinned',
						),
					),
				),
				'stock' => array(
					'url' => array(
						'target' => 'aimeos_shop_stock',
					),
				),
				'suggest' => array(
					'url' => array(
						'target' => 'aimeos_shop_suggest',
					),
				),
				'tree' => array(
					'url' => array(
						'target' => 'aimeos_shop_tree',
					),
				),
			),
			'checkout' => array(
				'confirm' => array(
					'url' => array(
						'target' => 'aimeos_shop_confirm',
					),
				),
				'standard' => array(
					'url' => array(
						'target' => 'aimeos_shop_checkout',
					),
					'summary' => array(
						'option' => array(
							'terms' => array(
								'url' => array(
									'target' => 'aimeos_shop_terms',
								),
								'cancel' => array(
									'url' => array(
										'target' => 'aimeos_shop_terms',
									),
								),
								'privacy' => array(
									'url' => array(
										'target' => 'aimeos_shop_privacy',
									),
								),
							),
						),
					),
				),
				'update' => array(
					'url' => array(
						'target' => 'aimeos_shop_update',
					),
				),
			),
			'common' => array(
				'content' => array(
					'baseurl' => '/',
				),
				'template' => array(
					'baseurl' => './aimeos/elegance',
				),
			),
		),
		'jsonapi' => array(
			'url' => array(
				'target' => 'aimeos_shop_jsonapi_options',
			),
		),
	),


	'controller' => array(
		'common' => array(
			'media' => array(
				'standard' => array(
					'mimeicon' => array(
						# Directory where icons for the mime types stored
						'directory' => 'aimeos/mimeicons',
						# File extension of mime type icons
						'extension' => '.png'
					),
				),
			),
		),
	),

	'mshop' => array(
		'customer' => array(
			'manager' => array(
				'password' => array(
					'name' => 'Bcrypt',
				),
			),
		),
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
				'supplier' => array(
					'name' => 'MySQL',
				),
				'text' => array(
					'name' => 'MySQL',
				),
			),
		),
	),

);
