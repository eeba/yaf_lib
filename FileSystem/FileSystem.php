<?php
namespace FileSystem;

/**
 * 文件系统类
 *
 * 当文件是私有的，不希望对外公开 请使用filesystem类上传文件
 * 私有的文件无法通过url来访问访问，需要通过提供的get方法来获得文件内容
 *
 * <demo>
 *
 * //存储文件 将文件/tmp/1.png(绝对路径)存储在文件系统的space空间中 存储目录为test 存储的文件名为1.png
 * $url ＝ \Base\FileSystem\FileSystem::getInstance()->put('space', 'test/1.png', '/tmp/1.png');
 * @return $url 文件的链接地址(如http://oss-cn-beijing.aliyuncs.com/test/1.png);
 *
 *
 * //获取文件 获取文件系统的space空间中 test目录下的1.png文件
 * $file ＝ \Base\FileSystem\FileSystem::getInstance()->get('space', 'test/1.png');
 * @return $file 文件内容
 *
 *
 * //删除文件 删除文件系统的space空间中 test目录下的1.png文件
 * $ret ＝ \Base\FileSystem\FileSystem::getInstance()->delete('space', 'test/1.png');
 * @return $ret bool 成功/失败
 * </demo>
 *
 * <code>
 *
 * 存储文件
 * $url = \Base\FileSystem\FileSystem::getInstance()->put($space, $remote_file_name, $local_file_name);
 * @space 文件空间 即存储文件的目录名 对应云存储上的bucket名
 * @remote_file_name 存储在文件系统里的文件名 如有目录则采用'test/1.png'的格式
 * @local_file_name  上传的本地文件 使用绝对路径 ('/home/root/1.png')
 * return $url 文件的url地址(http://oss-cn-beijing.aliyuncs.com/space/test/1.png)或绝对路径(/tmp/filestorage/test/1.png)
 *
 * 获取文件
 * $file = \Base\FileSystem\FileSystem::getInstance()->get($space, $remote_file_name);
 * @space 文件空间 即存储文件的目录名 对应云存储上的bucket名
 * @remote_file_name 存储在文件系统里的文件名 有目录则采用'test/1.png'的格式(可以使用通过put方法返回的$url来获取文件)
 * return $file 整个文件的数据
 *
 * 删除文件
 * $ret = \Base\FileSystem\FileSystem::getInstance()->delete($space, $remote_file_name);
 * @space 文件空间 即存储文件的目录名 对应云存储上的bucket名
 * @remote_file_name 存储在文件系统里的文件名 有目录则采用'test/1.png'的格式(可以使用通过put方法返回的$url来删除文件)
 * return $ret bool(删除的文件不存在时,也会返回true)
 * </code>
 */
class FileSystem {

    const STORE_TYPE_LOCAL = "local";
    const STORE_TYPE_QINIU = "qiniu";

	private static $instance = null;

    /**
     * 获取存储驱动实例
     * @param string $type   类型(cdn, filesystem)
     * @param string $config 类型(cdn, filesystem)
     * @return object Handler
     */
	public static function getInstance($type = self::STORE_TYPE_LOCAL, $config = 'default') {
		if (self::$instance === null) {
            $handler = __NAMESPACE__."\\Handler\\".ucfirst($type);
			self::$instance = new $handler($config);
		}
		return self::$instance;
	}

}
