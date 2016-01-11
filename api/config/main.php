<?php
return [
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Facebook Graph API Proxy',
    'defaultController' => 'index',

    'import' => [
        'common.models.*',
        'common.components.*',
        'common.extensions.Facebook.*',
        'common.extensions.Facebook.responses.*',
        'application.components.*',
    ],

    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'facebook' => [
            'class' => 'EFacebook',
        ],

        'urlManager' => [
            'urlFormat' => 'path',
            'rules' => [
                '<user:\d+>/feed' => 'index/feed',
                '<user>' => 'index/profile',
            ],
        ],

        'mongodb' => [
            'class' => 'EMongoClient',
        ],
    ]
];
