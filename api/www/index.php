<?php
define('ROOT_DIR', dirname(__FILE__) . '/../..');

require_once ROOT_DIR. "/common/lib/autoload.php";
require_once ROOT_DIR . '/common/lib/yiisoft/yii/framework/yii.php';

Yii::setPathOfAlias('root', ROOT_DIR);
Yii::setPathOfAlias('common', ROOT_DIR . DIRECTORY_SEPARATOR . 'common');

$base  = require_once(ROOT_DIR . DIRECTORY_SEPARATOR . 'api/config/main.php');
$local = require_once(ROOT_DIR . DIRECTORY_SEPARATOR . 'api/config/main.local.php');

$config = CMap::mergeArray($base, $local);

try {
    Yii::createWebApplication($config)->run();
} catch (Exception $e) {
    var_dump($e);
    exit;
}
