<?php
namespace Base\Plugin;


class Session extends \Yaf_Plugin_Abstract{

    /**
     * 启用session
     *
     * @param \Yaf_Request_Abstract $request
     * @param \Yaf_Response_Abstract $response
     * @return bool|void
     */
    public function dispatchLoopStartup(\Yaf_Request_Abstract $request, \Yaf_Response_Abstract $response) {
        //启用session
        if(defined('APP')) {
            ini_set('session.name', 'id'.substr(md5(APP),0,12));
        }
        if(defined('SESSION_TYPE') && strtolower(SESSION_TYPE) == 'redis') {
            ini_set('session.save_handler', 'redis');

            $session_config = \Base\Config::get('server.redis.session');
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
