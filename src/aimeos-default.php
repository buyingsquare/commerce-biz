<?php

return [

	'apc_enabled' => false,
	'apc_prefix' => 'slim:',
	'uploaddir' => '/',

	'page' => [
		'account-index' => ['account/profile', 'account/subscription', 'account/history', 'account/favorite', 'account/watch', 'basket/mini', 'catalog/session'],
		'basket-index' => ['basket/bulk', 'basket/standard', 'basket/related'],
		'catalog-count' => ['catalog/count'],
		'catalog-detail' => ['basket/mini', 'catalog/stage', 'catalog/detail', 'catalog/session'],
		'catalog-list' => ['basket/mini', 'catalog/filter', 'catalog/lists'],
		'catalog-stock' => ['catalog/stock'],
		'catalog-suggest' => ['catalog/suggest'],
		'catalog-tree' => ['basket/mini', 'catalog/filter', 'catalog/stage', 'catalog/lists'],
		'checkout-confirm' => ['checkout/confirm'],
		'checkout-index' => ['checkout/standard'],
		'checkout-update' => ['checkout/update'],
	],

	'resource' => [
		'db' => [
			'adapter' => 'mysql',
			'host' => 'localhost',
			'port' => '',
			'database' => 'slim',
			'username' => 'aimeos',
			'password' => 'aimeos',
			'stmt' => ["SET SESSION sort_buffer_size=2097144; SET NAMES 'utf8mb4'; SET SESSION sql_mode='ANSI'; SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED"],
			'opt-persistent' => 0,
			'limit' => 3,
			'defaultTableOptions' => [
				'charset' => 'utf8mb4',
				'collate' => 'utf8mb4_bin',
			],
		],
		'fs' => [
			'adapter' => 'Standard',
			'basedir' => './',
			'baseurl' => '/',
		],
		'fs-admin' => [
			'adapter' => 'Standard',
			'basedir' => './uploads',
		],
		'fs-import' => [
			'adapter' => 'Standard',
			'basedir' => '../secure/import',
		],
		'fs-secure' => [
			'adapter' => 'Standard',
			'basedir' => '../secure',
		],
		'mq' => [
			'adapter' => 'Standard',
			'db' => 'db',
		],
	],

	'admin' => [
		'jqadm' => [
			'url' => [
				'copy' => [
					'target' => 'aimeos_shop_jqadm_copy'
				],
				'create' => [
					'target' => 'aimeos_shop_jqadm_create'
				],
				'delete' => [
					'target' => 'aimeos_shop_jqadm_delete'
				],
				'export' => [
					'target' => 'aimeos_shop_jqadm_export'
				],
				'get' => [
					'target' => 'aimeos_shop_jqadm_get'
				],
				'save' => [
					'target' => 'aimeos_shop_jqadm_save'
				],
				'search' => [
					'target' => 'aimeos_shop_jqadm_search'
				],
			]
		],
		'jsonadm' => [
			'url' => [
				'target' => 'aimeos_shop_jsonadm_get',
				'config' => [
					'absoluteUri' => true,
				],
				'options' => [
					'target' => 'aimeos_shop_jsonadm_options',
					'config' => [
						'absoluteUri' => true,
					],
				],
			],
		],
	],
	'client' => [
		'html' => [
			'account' => [
				'index' => [
					'url' => [
						'target' => 'aimeos_shop_account',
					],
				],
				'subscription' => [
					'url' => [
						'target' => 'aimeos_shop_account',
					],
				],
				'history' => [
					'url' => [
						'target' => 'aimeos_shop_account',
					],
				],
				'favorite' => [
					'url' => [
						'target' => 'aimeos_shop_account_favorite',
					],
				],
				'watch' => [
					'url' => [
						'target' => 'aimeos_shop_account_watch',
					],
				],
				'download' => [
					'url' => [
						'target' => 'aimeos_shop_account_download',
					],
					'error' => [
						'url' => [
							'target' => 'aimeos_shop_account',
						],
					],
				],
			],
			'basket' => [
				'standard' => [
					'url' => [
						'target' => 'aimeos_shop_basket',
					],
				],
			],
			'catalog' => [
				'count' => [
					'url' => [
						'target' => 'aimeos_shop_count',
					],
				],
				'detail' => [
					'url' => [
						'target' => 'aimeos_shop_detail',
					],
				],
				'lists' => [
					'url' => [
						'target' => 'aimeos_shop_list',
					],
				],
				'session' => [
					'pinned' => [
						'url' => [
							'target' => 'aimeos_shop_session_pinned',
						],
					],
				],
				'stock' => [
					'url' => [
						'target' => 'aimeos_shop_stock',
					],
				],
				'suggest' => [
					'url' => [
						'target' => 'aimeos_shop_suggest',
					],
				],
				'tree' => [
					'url' => [
						'target' => 'aimeos_shop_tree',
					],
				],
			],
			'checkout' => [
				'confirm' => [
					'url' => [
						'target' => 'aimeos_shop_confirm',
					],
				],
				'standard' => [
					'url' => [
						'target' => 'aimeos_shop_checkout',
					],
					'summary' => [
						'option' => [
							'terms' => [
								'url' => [
									'target' => 'aimeos_shop_terms',
								],
								'cancel' => [
									'url' => [
										'target' => 'aimeos_shop_terms',
									],
								],
								'privacy' => [
									'url' => [
										'target' => 'aimeos_shop_privacy',
									],
								],
							],
						],
					],
				],
				'update' => [
					'url' => [
						'target' => 'aimeos_shop_update',
					],
				],
			],
			'common' => [
				'content' => [
					'baseurl' => '/',
				],
				'template' => [
					'baseurl' => './aimeos/elegance',
				],
			],
		],
		'jsonapi' => [
			'url' => [
				'target' => 'aimeos_shop_jsonapi_options',
			],
		],
	],


	'controller' => [
		'common' => [
			'media' => [
				'standard' => [
					'mimeicon' => [
						# Directory where icons for the mime types stored
						'directory' => 'aimeos/mimeicons',
						# File extension of mime type icons
						'extension' => '.png'
					],
				],
			],
		],
	],

	'mshop' => [
		'customer' => [
			'manager' => [
				'password' => [
					'name' => 'Bcrypt',
				],
			],
		],
		'index' => [
			'manager' => [
				'attribute' => [
					'name' => 'MySQL',
				],
				'catalog' => [
					'name' => 'MySQL',
				],
				'price' => [
					'name' => 'MySQL',
				],
				'supplier' => [
					'name' => 'MySQL',
				],
				'text' => [
					'name' => 'MySQL',
				],
			],
		],
	],

];
