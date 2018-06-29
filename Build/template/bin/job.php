<?php
//todo 测试数据库长连接的问题
//ini_set("display_errors", 1);
//nohup /usr/bin/env php job.php Job_Daemon_Master >> /tmp/nohup.Daemon.log 2>&1 &

ini_set('display_errors', false);
error_reporting(E_ERROR);
ini_set('memory_limit', '512M');
if (php_sapi_name() !== 'cli') {
    trigger_error('this run not cli', E_USER_ERROR);
}

include dirname(__FILE__) . '/conf/init.php';
$app = new \Yaf\Application(CONF_PATH . '/application.ini');
$app->bootstrap();

$class_path = APP_PATH . '/' . lcfirst(str_replace('_', '/', $argv[1])) . '.php';
$class_name = str_replace('_', '\\', $argv[1]);

if (!class_exists($class_name, false)) {
    \Yaf\Loader::import($class_path);
}

\Base\Env::setCliClass($class_name);
cli_set_process_title("PHP_" . strtoupper(APP_NAME) . "_" . strtoupper($argv[1]));
$job_obj = new $class_name();
$app->execute(array($job_obj, 'action'), array_slice($argv, 2));