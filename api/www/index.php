<?php
define('ROOT_DIR', dirname(__FILE__) . '/../..');

require_once ROOT_DIR. "/common/lib/autoload.php";
require_once ROOT_DIR . '/common/lib/yiisoft/yii/framework/yii.php';

Yii::setPathOfAlias('root', ROOT_DIR);
Yii::setPathOfAlias('common', ROOT_DIR . DIRECTORY_SEPARATOR . 'common');

$config = CMap::mergeArray(
    require_once(ROOT_DIR . DIRECTORY_SEPARATOR . 'common/config/main.php'),
    require_once(ROOT_DIR . DIRECTORY_SEPARATOR . 'common/config/main.local.php'),
    require_once(ROOT_DIR . DIRECTORY_SEPARATOR . 'api/config/main.php'),
    require_once(ROOT_DIR . DIRECTORY_SEPARATOR . 'api/config/main.local.php')
);

try {
    Yii::createWebApplication($config)->run();
} catch (Exception $e) {
    var_dump($e);
    exit;
}
