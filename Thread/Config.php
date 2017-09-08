<?php

namespace Thread;
/**
 * Class Config
 *
 * @package     S\Thread
 * @description 进程配置类
 *
 * 子进程配置类，包括：
 *     子进程数量
 *     子进程生命周期
 *     子进程处理任务数量上限
 *
 * 配置一旦生效将保存入配置文件中，该文件默认存放路径为：/tmp/thread_config_file.conf
 *
 * 此类用于进行配置子进程，使用示例：
 *
 * $master = new \Thread\Master();
 * $config = new \Thread\Config();
 *
 * $config->setWorkerConfig("\\Service\\Attribute\\ClassI", 1);
 * $config->setWorkerConfig("\\Service\\Attribute\\ClassII", 2);
 *
 * $master->main();
 */
class Config {

    const THREAD_CONFIG_FILE_PREFIX = "/tmp/thread_config_file."; //子进程配置文件默认存放路径前缀
    const WORK_TTL = 8640;  //子进程1小时，回收
    const WORK_DEAL_NUM = 100000;  //子进程循环处理100000个，回收

    private static $_thread_config_file;  //子进程配置文件存放路径

    protected $_config = array();

    public function __construct() {
        self::$_thread_config_file = self::THREAD_CONFIG_FILE_PREFIX . APP_NAME . ".conf";
    }

    /**
     * 设置工作进程的配置
     *
     * @param string $class_name 类名(包括命名空间)
     * @param int    $work_num 工作进程数
     * @param int    $ttl 进程工作多少时间会被回收     默认一天
     * @param int    $deal_num 进程循环处理多少次会被回收   默认1000000次
     *
     * @return bool
     *
     */
    public function setWorkerConfig($class_name, $work_num, $ttl = 0, $deal_num = 0) {
        if ($ttl === 0) {
            $ttl = rand(self::WORK_TTL, self::WORK_TTL + 1000);
        }
        if ($deal_num === 0) {
            $deal_num = rand(self::WORK_DEAL_NUM, self::WORK_DEAL_NUM + 1000);
        }
        $this->_config[$class_name] = array(
            'work_num' => $work_num,
            'ttl' => $ttl,
            'deal_num' => $deal_num,
        );
        $this->_setConfigByFile();

        return true;
    }

    /**
     * 获取进程配置
     *
     * @return array
     */
    public function getWorkerConfig() {
        $this->_getConfigByFile();

        return $this->_config;
    }

    /**
     * 获取子进程生命周期
     *
     * @param string $class_name 子进程类名
     *
     * @return int
     */
    public function getWorkerTtl($class_name) {
        $this->_getConfigByFile();

        return $this->_config[$class_name]['ttl'];
    }

    /**
     * 获取子进程数量上限
     *
     * @param string $class_name 子进程类名
     *
     * @return int
     */
    public function getWorkerNum($class_name) {
        $this->_getConfigByFile();

        return $this->_config[$class_name]['work_num'];
    }

    /**
     * 获取子进程处理任务数量上限
     *
     * @param string $class_name 子进程类名
     *
     * @return int
     */
    public function getWorkerDealNum($class_name) {
        $this->_getConfigByFile();

        return $this->_config[$class_name]['deal_num'];
    }

    /**
     * 从配置文件中加载进程配置
     *
     * @return bool
     */
    private function _getConfigByFile() {
        $config = file_get_contents(self::$_thread_config_file);
        if ($config) {
            $this->_config = json_decode($config, true);
        } else {
            $this->_config = array();
        }

        return true;
    }

    /**
     * 更新进程配置文件
     *
     * @return bool
     */
    private function _setConfigByFile() {
        if (md5(json_encode($this->_config)) !== md5_file(self::$_thread_config_file)) {
            file_put_contents(self::$_thread_config_file, json_encode($this->_config));
        }

        return true;
    }

}