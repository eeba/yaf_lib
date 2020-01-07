<?php
header('Pragma: no-cache', false);
define("ROOT_PATH",  realpath(dirname(dirname(__FILE__))));
define('DEBUG', true);
include ROOT_PATH . '/conf/init.php';

$app = new \Yaf_Application(CONF_PATH . '/application.ini');
$app->bootstrap()->run();