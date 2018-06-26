<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_START_TIME', microtime(1));
header("Content-type:text/html; charset=utf-8");
header('Pragma: no-cache', false);

include dirname(dirname(__FILE__)) . "/conf/init.php";
$app  = new Yaf\Application(CONF_PATH . "/application.ini");
$app->bootstrap()->run();