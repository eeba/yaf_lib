<?php
//初始化
date_default_timezone_set('Asia/Shanghai');
define('EXT', '.php');
define('DEBUG', false);

//session
define('SESSION_TYPE', 'file');

//路径定义
define('DS',            DIRECTORY_SEPARATOR);
define('ROOT_PATH',     dirname(dirname(__FILE__)));
define('APP_PATH',      ROOT_PATH.'/application');
define('CONF_PATH',     ROOT_PATH.'/conf');
define('LOG_PATH',      ROOT_PATH . '/data/logs');
define('VIEW_PATH',     APP_PATH.'/views/');
define('LIB',           '/data1/htdocs/yaf_lib');

//应用
define('APP_NAME', 'MX');//英文、数字、下划线
define('HOST', '3mx.cc');

// php.ini定义
ini_set('yaf.library', LIB);
ini_set('yaf.use_spl_autoload', 'On');