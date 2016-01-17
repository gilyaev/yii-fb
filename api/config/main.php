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
                '<profile>/feed' => 'index/feed',
                '<profile>' => 'index/profile',
            ],
        ],
    ]
];
