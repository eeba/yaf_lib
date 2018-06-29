<?php
/**
 * Code Generetor
 * 项目生成脚本
 */
if ($argc < 4) {
    echo "项目生成 1.0.0" . PHP_EOL;
    echo "说明：(需要管理员权限)" . PHP_EOL;
    echo "命令格式：/usr/local/bin/php {$argv[0]} APPNAME Domain ApplicationPath" . PHP_EOL;
    echo "使用示例：/usr/local/bin/php {$argv[0]} test test.com /data/htdocs/test.com" . PHP_EOL;
    exit;
}

$app_name = $argv[1];
$domain = $argv[2];
$app_path = rtrim($argv[3], '/');
$template_path = dirname(__FILE__) . '/template';
$yaf_lib_path = dirname(dirname(__FILE__));

if(!preg_match("/^[0-9a-zA-Z_]+$/", $app_name)){
    echo "应用名称格式：英文、数字、下划线" . PHP_EOL;
}

//需要替换的值
$replace = array(
    "@appname@" => $app_name,
    "@appdomain@" => $domain,
    "@yaflibpath@" => $yaf_lib_path,
);

//如果要使用的项目目录已经存在，进行备份
if (file_exists($app_path)) {
    $app_path_bak = $app_path . "." . date('YmdHis', time());
    rename($app_path, $app_path_bak);
    echo "{$app_path} 已经存在，备份为{$app_path_bak}";
}


$tpl_file = getFiles($template_path);
foreach ($tpl_file as $file) {
    $app_file = str_replace($template_path, $app_path, $file);
    if (!file_exists(dirname($app_file))) {
        mkdir(dirname($app_file), 0777, true);
    }
    $content = file_get_contents($file);
    foreach ($replace as $key => $value) {
        $content = str_replace($key, $value, $content);
    }
    file_put_contents($app_file, $content);
}
echo "完成" . PHP_EOL;

//获取所有的代码模板文件
function getFiles($dir) {
    $file_list = array();
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == "." || $file == ".." || $file == '.svn' || $file == '.git') {
            continue;
        }
        if (is_dir($dir . '/' . $file)) {
            $file_list = array_merge(getFiles($dir . '/' . $file), $file_list);
            continue;
        }
        if (is_file($dir . '/' . $file)) {
            $file_list[] = $dir . "/" . $file;
            continue;
        }
    }
    return $file_list;
}