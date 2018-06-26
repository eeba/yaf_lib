<?php
$_s = microtime(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

include dirname(__FILE__) . "/conf/init.php";


$app  = new Yaf\Application(CONF_PATH . "/application.ini");
$_ss = microtime(true);
$app->bootstrap()->run();
echo microtime(true)-$_s . PHP_EOL;
echo microtime(true)-$_ss . PHP_EOL;