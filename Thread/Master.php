<?php

namespace Thread;

/**
 * Class Master
 * @package Thread
 * @description 进程管理主进程类
 *
 * 此类用于进行进程管理的入口，使用示例：
 *
 * $master = new \Thread\Master();
 * $config = new \Thread\Config();
 *
 * $config->set_thread_config("\\Service\\Attribute\\ClassI", 1);
 * $config->set_thread_config("\\Service\\Attribute\\ClassII", 2);
 *
 * $master->main();
 *
 * 一个守护进程里只有一个主进程
 * 主进程负责启动、管理工作进程
 * 每10s主进程会检查工作进程的生存情况
 * 当进程数不足时, 会启动新的进程
 * 当进程数超过时, 按启动时间依次停止工作进程直到进程数符合要求
 */
class Master
{

    const MASTER_PID_FILE_PREFIX = "/var/run/PHP_THREAD_MASTER_PID.";
    const MASTER_SLEEP = 10;

    private static $_master_pid_file;  //保存主进程pid文件路径

    protected $isRunning = false;
    protected $pids = array();
    protected $work_proc = array();

    public function __construct()
    {
        self::$_master_pid_file = self::MASTER_PID_FILE_PREFIX . APP;

        //进程组和回话组组长
        posix_setsid();
        //执行时长
        set_time_limit(0);
        //cli模式
        if (php_sapi_name() != "cli") {
            Utils::echoInfo("only run in command line mode\n");
            exit();
        }
        $pid = file_get_contents(self::$_master_pid_file);

        if ($pid) {
            posix_kill($pid, SIGTERM);
        }
        //等待上一个进程死掉
        while (file_get_contents(self::$_master_pid_file)) {
            sleep(3);
        }
        //进程pid落地
        file_put_contents(self::$_master_pid_file, getmypid());
    }

    /**
     * 启动并且实时监控工作进程
     */
    public function main()
    {
        Utils::echoInfo("master start");
        $this->registerSigHandler();

        $this->isRunning = true;

        while (true) {
            $this->manageWorkers();
            pcntl_signal_dispatch();
            if (!$this->isRunning) break;
            sleep(self::MASTER_SLEEP);
            pcntl_signal_dispatch();
            if (!$this->isRunning) break;
        }

        //如果收到退出信号
        while (true) {
            pcntl_signal_dispatch();
            if (count($this->pids) == 0)
                break;
            sleep(5);
        }
        file_put_contents(self::$_master_pid_file, "");
        exit();
    }

    /**
     * 信号处理
     * @param int $sig
     * @access private
     * @return void
     */
    public function sigHandler($sig)
    {
        Utils::echoInfo("master receive $sig");
        switch (intval($sig)) {
            case SIGCHLD:
                //当子进程停止或退出时通知父进程
                $this->waitChild();
                break;
            case SIGINT:
                //中断进程
                $this->cleanup();
                break;
            case SIGQUIT:
                //终止进程，并且生成core文件
                break;
            case SIGHUP:
                //终端线路挂断
                break;
            case SIGTERM:
                //终止进程
                $this->cleanup();
                break;
            default:
                break;
        }
    }

    /**
     * 信号注册
     * @access protected
     * @return void
     */
    protected function registerSigHandler()
    {
        pcntl_signal(SIGTERM, array($this, 'sigHandler'));
        pcntl_signal(SIGHUP, array($this, 'sigHandler'));
        pcntl_signal(SIGCHLD, array($this, 'sigHandler'));
        pcntl_signal(SIGINT, array($this, 'sigHandler'));
        pcntl_signal(SIGQUIT, array($this, 'sigHandler'));
    }

    /**
     * 处理退出的子进程
     * @access protected
     * @return void
     */
    protected function waitChild()
    {
        while (($pid = pcntl_waitpid(-1, $status, WNOHANG)) > 0) {
            Utils::echoInfo("master waitpid $pid");
            unset($this->pids[$pid]);
        }
    }

    /**
     * 父进程接受到退出信号时，给子进程发送SIGTERM信号
     *
     * @param null $pid
     */
    protected function cleanup($pid = null)
    {
        Utils::echoInfo("clean up $pid");
        if (!$pid) {
            if (count($this->pids)) {
                foreach ($this->pids as $pid => $thread) {
                    Utils::echoInfo("master posix kill $pid");
                    posix_kill($pid, SIGTERM);
                }
            }
            Utils::echoInfo("master stop");
            $this->isRunning = false;
        } else {
            Utils::echoInfo("master posix kill $pid");
            posix_kill($pid, SIGTERM);
        }
    }

    /**
     * fork子进程
     *
     * @param string $class_name 子进程类名
     *
     * @return bool
     * @throws \Exception
     */
    protected function fork($class_name)
    {
        $pid = pcntl_fork();
        if ($pid == -1) {
            throw new \Exception("can not fork new process");
        } elseif ($pid) {
            $this->pids[$pid] = array(
                'class_name' => $class_name,
            );
        } else {
            Utils::setProcessTitle("THREAD_PHP_" . strtoupper(APP) . "_" . $class_name);
            $work = new $class_name;
            $work->doTask();
            exit();
        }
        return true;
    }

    /**
     * 管理进程
     */
    protected function manageWorkers()
    {
        $obj_thread_config = new Config();
        $thread_config = $obj_thread_config->getWorkerConfig();
        $this->work_proc = Utils::getWorkerProcessInfo($this->pids);
        //查看进程情况，按配置启动和减少进程
        foreach ($thread_config as $class_name => $v) {
            $work_proc_num = isset($this->work_proc[$class_name]) ? count($this->work_proc[$class_name]) : 0;
            $num = $work_proc_num - $v['work_num'];
            if ($num >= 0) {
                for ($i = 0; $i < $num; $i++) {
                    $this->cleanup($this->work_proc[$class_name][$i]);
                }
            } else {
                $this->fork($class_name);
            }
        }
    }

}