<?php

return function (array $env) {
    return [

        // 'apc_enabled' => false,
        // 'apc_prefix' => 'slim:',
        // 'uploaddir' => '/',

        'page' => [
            // 'account-index' => [ 'account/profile','account/subscription','account/history','account/favorite','account/watch','basket/mini','catalog/session' ],
            // 'basket-index' => [ 'basket/bulk', 'basket/standard','basket/related' ],
            // 'catalog-count' => [ 'catalog/count' ],
            // 'catalog-detail' => [ 'basket/mini','catalog/stage','catalog/detail','catalog/session' ],
            // 'catalog-list' => [ 'basket/mini','catalog/filter','catalog/lists' ],
            // 'catalog-stock' => [ 'catalog/stock' ],
            // 'catalog-suggest' => [ 'catalog/suggest' ],
            // 'catalog-tree' => [ 'basket/mini','catalog/filter','catalog/stage','catalog/lists' ],
            // 'checkout-confirm' => [ 'checkout/confirm' ],
            // 'checkout-index' => [ 'checkout/standard' ],
            // 'checkout-update' => [ 'checkout/update'],
        ],

        // route prefixes, e.g. {site}, {locale} and {currency} resp. {site} and {lang} for /admin/*
        'routes' => [
            // 'admin' => '/admin',
            // 'extadm' => '/admin/{site}/extadm',
            // 'jqadm' => '/admin/{site}/jqadm',
            // 'jsonadm' => '/admin/{site}/jsonadm',
            // 'jsonapi' => '/jsonapi',
            // 'account' => '/profile',
            // 'default' => '/shop',
            // 'update' => '',
        ],

        'resource' => [
            'db' => [
                'adapter' => $env['DB_CONNECTION'],
                'host' => $env['DB_HOST'],
                'port' => $env['DB_PORT'],
                'socket' => '',
                'database' => $env['DB_DATABASE'],
                'username' => $env['DB_USERNAME'],
                'password' => $env['DB_PASSWORD'],
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
                'baseurl' => '/', // change to https://<yourdomain>/
            ],
        ],

        'client' => [
            'html' => [
                'common' => [
                    'template' => [
                        // 'baseurl' => './aimeos/elegance',
                    ],
                ],
            ],
        ],

        'controller' => [
        ],

        'i18n' => [
        ],

        'madmin' => [
        ],

        'mshop' => [
            'customer' => [
                'manager' => [
                    'name' => 'Laravel',
                    'password' => [
                        'name' => 'Bcrypt',
                    ],
                    'salt' => $env['APP_KEY'],
                ]
            ]
        ],

        'command' => [
        ],

        'backend' => [
        ],

        'frontend' => [
        ],

    ];
};
