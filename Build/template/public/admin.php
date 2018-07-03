<?php
echo '成功';exit;
header("Content-type:text/html; charset=utf-8");
header('Pragma: no-cache', false);


include dirname(dirname(__FILE__)) . "/conf/init.php";
$app  = new Yaf\Application(CONF_PATH . "/admin.ini");
$app->bootstrap()->run();