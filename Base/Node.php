<?php
namespace Base;

class Node {

    private $path = '';
    private $file_list = [];

    public function __construct($path, $filter=[]) {
        $this->path = $path;
        $this->filter = $filter;
    }

    public function nodeList() {
        $this->fileList($this->path);
        $node = [];
        if (!$this->file_list) {
            return $node;
        }
        foreach ($this->file_list as $file) {

            \Yaf\Loader::import($file . EXT);
            $tmp_file = explode('controllers', $file);
            $file_name = str_replace('/','_',trim($tmp_file[1],'/'));
            $class_name = 'Controller_' . $file_name;
            if (!class_exists($class_name) || in_array($class_name, $this->filter)) {
                continue;
            }
            $class = new \ReflectionClass($class_name);
            $class_doc = $class->getDocComment();
            if ($class_doc && preg_match('/\@name\s+(.+)/i', $class_doc, $match)) {
                $class_name = strtolower(trim($match[1]));
            }
            $node[$file_name]['name'] = $class_name;

            $method_list = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($method_list as $value) {
                $method_name = $value->name;
                if(!stripos($method_name,'Action')){
                    continue;
                }
                $method_doc = $value->getDocComment();
                if ($method_doc && preg_match('/\@name\s+(.+)/i', $method_doc, $match)) {
                    $method_name = strtolower(trim($match[1]));
                }
                $node[$file_name]['method'][$value->name] = $method_name;
            }
        }
        return $node;
    }

    private function fileList($path) {
        $dir_handler = opendir($path);
        while ($file = readdir($dir_handler)) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $file = str_ireplace('.php', '', $file);
            if(is_dir($this->path . DS . $file)){
                $this->fileList($path . DS . $file);
            }else{
                if ($file !== get_class($this) || $file !== 'Error') {
                    $this->file_list[] = $path . DS . $file;
                }
            }
        }
    }
}