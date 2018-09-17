<?php
namespace Base\Plugin;

class Session extends \Yaf\Plugin_Abstract{

    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        //启用session
        ini_set('session.name', APP_NAME . 'ID');
        if(strtolower(SESSION_TYPE) == 'redis') {
            ini_set('session.save_handler', 'redis');

            $session_config = \Base\Config::get('service.cache.redis');
            $session_path = 'tcp://' . $session_config['host'] . ':' . $session_config['port'];
            if (!empty($session_config['auth'])) {
                $password = isset($session_config['user']) ? "{$session_config['user']}:{$session_config['auth']}" : $session_config['auth'];
                $session_path .= "?auth={$password}";
            }
            ini_set('session.save_path', $session_path);
        }
        session_start();
    }

}
