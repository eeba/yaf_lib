<?php
namespace Base;

class Node {

    private $path = '';
    private $file_list = [];

    public function __construct($path, $filter = []) {
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

            \Yaf_Loader::import($file . '.php');
            $tmp_file = explode('controllers', $file);
            $file_name = str_replace('/', '_', trim($tmp_file[1], '/'));
            $class_name = 'Controller_' . $file_name;
            $node_key = $class_name;
            $node[$node_key]['controller'] = $class_name;
            if (!class_exists($class_name) || in_array($class_name, $this->filter)) {
                continue;
            }
            $class = new \ReflectionClass($class_name);
            $class_doc = $class->getDocComment();
            if ($class_doc && preg_match('/\@funcname\s+(.+)/i', $class_doc, $match)) {
                $class_name = trim($match[1]);
            }
            $node[$node_key]['controller_name'] = $class_name;

            $method_list = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($method_list as $value) {
                $method_name = $value->name;
                if (!stripos($method_name, 'Action')) {
                    continue;
                }
                $method_doc = $value->getDocComment();
                if ($method_doc && preg_match('/\@funcname\s+(.+)/i', $method_doc, $match)) {
                    $method_name = trim($match[1]);
                }
                $node[$node_key]['method'][] = [
                    'action' => $value->name,
                    'action_name' => $method_name
                ];
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
            if (is_dir($this->path . DIRECTORY_SEPARATOR . $file)) {
                $this->fileList($path . DIRECTORY_SEPARATOR . $file);
            } else {
                if ($file !== get_class($this) || $file !== 'Error') {
                    $this->file_list[] = $path . DIRECTORY_SEPARATOR . $file;
                }
            }
        }
    }
}