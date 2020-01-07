#!/usr/bin/env php
<?php

/**
 * php yaf.php Demo
 */

define('DEBUG', false);

if(PHP_SAPI !== 'cli'){
    exit("不支持非cli模式运行");
}

if ($argc < 2) {
    die(<<<USAGE
Usage:
Need root permissions
php {$argv[0]} SCRIPTNAME
eg.
php {$argv[0]} Demo_Test\n
USAGE
    );
}

$app_name = strtolower($argv[1]);

define("ROOT_PATH",  realpath(dirname(__FILE__)));
include ROOT_PATH . '/conf/init.php';
$app = new \Yaf_Application(CONF_PATH . '/application.ini');
$app->bootstrap();

$class_path = APP_PATH .'/'. lcfirst(str_replace('_', '/', $argv[1])) . '.php';
$class_name = str_replace('_', '\\', $argv[1]);
if (!class_exists($class_name, false)) {
    \Yaf_Loader::import($class_path);
}


cli_set_process_title("PHP_" . strtoupper($argv[1]));
\Base\Env::setControllerName($class_name);

$job_obj = new $class_name();
$app->execute(array($job_obj, 'action'), array_slice($argv, 2));