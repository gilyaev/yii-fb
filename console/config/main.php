<?php

Yii::setPathOfAlias('common', dirname(__FILE__) . "/../../common");
return CMap::mergeArray(
    require_once(dirname(__FILE__) . '/../../common/config/main.php'),
    require_once(dirname(__FILE__) . '/../../common/config/main.local.php'),
    [
        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'name' => 'Console Application',
        'commandMap' => [
            'migratemongo' => [
                'class' => 'EMigrateMongoCommand'
            ]
        ],
    ]
);

