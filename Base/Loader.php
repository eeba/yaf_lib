<?php
namespace Base;
/**
 * 自动加载Yaf目录映射规则之外的文件
 */

class Loader {
    public static $local_namespaces = array();

    public static function autoLoader($class_name){
        if(strtolower(substr($class_name, 0, 3)) == 'job'){
            $library_path = 'job';
            $class_name = substr($class_name, 4);
        } else {
            $library_path = 'library';
        }
        $class_path = APP_PATH . DS .$library_path. DS . str_replace('\\', DS, $class_name) . EXT;
        if(is_file($class_path)) {
            \Yaf\Loader::import($class_path);
        }
        return false;
    }
}
