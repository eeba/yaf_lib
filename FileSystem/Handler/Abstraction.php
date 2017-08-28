<?php
namespace FileSystem\Handler;

abstract class Abstraction{

    /**
     * 存储通用put方法
     *
     * @param string $space 指定用
     * @param string $filename 文件名称
     * @param string $file_path 文件路径
     *
     * @return mixed 文件的链接地址
     */
    /**
     * @param string $remote_file_name 存储在文件系统中的文件名
     * @param string $local_file_name  本地文件名
     * @return mixed
     */
    abstract public function put($remote_file_name, $local_file_name);

    /**
     * 存储通用get方法
     *
     * @param string $space 文件空间
     * @param string $remote_file_name 存储在文件系统中的文件名
     *
     * @return mixed 文件内容
     */
    abstract public function get($space, $remote_file_name);

    /**
     * 存储通用delete方法
     *
     * 删除的文件不存在时，也会返回成功
     *
     * @param string $space 文件空间
     * @param string $remote_file_name 存储在文件系统中的文件名
     *
     * @return mixed 成功/失败
     */
    abstract public function delete($space, $remote_file_name);
}