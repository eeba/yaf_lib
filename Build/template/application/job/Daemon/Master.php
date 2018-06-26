<?php
namespace Job\Daemon;
/**
 * nohup /usr/local/php/bin/php job.php Job_Daemon_Master >> /tmp/nohup.Daemon.log 2>&1 &
 * kill `cat /var/run/PHP_THREAD_MASTER_PID.BASE_FW`
 */

class Master{

    public function action(){
        $master = new \Thread\Master();
        $config = new \Thread\Config();

        $config->setWorkerConfig("\\Job\\Daemon\\Demo\\Test", 10);

        $master->main();
    }

}