<?php

namespace Thread;

/**
 * 工作进程基类
 *
 * 具体的工作进程类需要继承此类并实现process方法
 * 工作进程的业务流程需要实现在process()方法里
 * process()方法每隔一定时间会执行一次   由$whileSleep控制 默认10秒
 *
 * 工作进程受主进程控制
 * 并且预设了进程的生存时间和执行次数上限
 * 在超过生存时间或执行次数超过上限时，进程会被回收  然后主进程会启动一个新的进程
 *
 * worker进程停止场景：
 *     接收到SIGTERM信号
 *     关联文件发生改变
 *     处理任务数量达到上限
 *     达到生命周期
 *
 * process方法中的异常不会影响当前进程的中断退出
 */
abstract class Worker
{

    protected $isRunning = false;
    protected $includedFile = null;
    protected $runNum = 0;  //已处理任务数量
    protected $runStartTime = 0;  //进程已运行时间
    protected $whileSleep = 1;  //未处理任务时进程睡眠时间

    /**
     * 子进程开始处理任务
     *
     * @access public
     * @return void
     */
    public function doTask()
    {
        $this->runStartTime = time();
        $this->isRunning = true;
        $this->registerSigHandler();
        Utils::echoInfo(cli_get_process_title() . " start");
        //循环处理，直到接受到停止信号
        while (true) {
            try {
                $this->process();
            } catch (\Base\Exception $e) {
                Utils::echoInfo(cli_get_process_title() . " throw " . $e->getMessage());
                //Logger::getInstance()->error([$e->getCode(), $e->getMessage(), $e->getTraceAsString()]);
                $this->isRunning = false;
            } catch (\Exception $e) {
                //上层异常 退出进程
                Utils::echoInfo(cli_get_process_title() . " throw " . $e->getMessage());
                //Logger::getInstance()->error([$e->getCode(), $e->getMessage(), $e->getTraceAsString()]);
                $this->isRunning = false;
            }

            if (!$this->checkIncludedFiles()) {
                Utils::echoInfo(cli_get_process_title() . " included file md5 change");
                $this->isRunning = false;
            }

            if (!$this->checkRunNum()) {
                Utils::echoInfo(cli_get_process_title() . " run num over");
                $this->isRunning = false;
            }

            if (!$this->checkRunTtl()) {
                Utils::echoInfo(cli_get_process_title() . " run ttl over");
                $this->isRunning = false;
            }

            $this->runNum++;

            pcntl_signal_dispatch();
            if (!$this->isRunning)
                break;
            if ($this->whileSleep == 0) {
                usleep(10000);//10秒
            } else {
                sleep($this->whileSleep);
            }
            pcntl_signal_dispatch();
        }
        exit;
    }

    /**
     *  接受到SIGTERM信号时，结束子进程
     *
     * @access public
     * @return void
     */
    public function stop()
    {
        Utils::echoInfo(cli_get_process_title() . " revice stop sign");
        $this->isRunning = false;
    }

    /**
     * 注册子进程信息号
     *
     * @access protected
     * @return void
     */
    protected function registerSigHandler()
    {
        pcntl_signal(SIGINT, SIG_IGN);
        pcntl_signal(SIGHUP, SIG_IGN);
        pcntl_signal(SIGQUIT, SIG_IGN);
        pcntl_signal(SIGTERM, array($this, 'sigHandler'));
    }

    /**
     * 子进程信号处理
     *
     * @param int $sig 信号量
     *
     * @access protected
     * @return void
     */
    protected function sigHandler(int $sig)
    {
        switch ($sig) {
            case SIGTERM:
                $this->stop();
                break;
            default:
                break;
        }
    }

    /**
     * 检查文件变更
     */
    protected function checkIncludedFiles(): bool
    {
        if (!$this->includedFile) {
            $this->includedFile = Utils::getIncludedFilesMd5();

            return true;
        } else {
            if (!array_diff($this->includedFile, Utils::getIncludedFilesMd5())) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 检查循环次数
     */
    protected function checkRunNum(): bool
    {
        $thread_config = new Config();
        $class_name = "\\" . get_class($this);
        if ($this->runNum >= $thread_config->getWorkerDealNum($class_name)) {
            return false;
        }

        return true;
    }

    /**
     * 检查生存时长
     */
    protected function checkRunTtl(): bool
    {
        $thread_config = new Config();
        $class_name = "\\" . get_class($this);
        if ((time() - $this->runStartTime) >= $thread_config->getWorkerTtl($class_name)) {
            return false;
        }

        return true;
    }

    /**
     * 任务处理函数
     *
     * @abstract
     * @access public
     * @return void
     */
    abstract public function process();

}