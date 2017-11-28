<?php
namespace Msg\Handler;

use Base\Exception;
use Base\Config;
use Base\Logger;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;

class Mail {
    protected $config = array();
    protected $need_config = array(
        'host',
        'port',
        'user',
        'password',
        'nickname'
    );

    public function __construct($key = 'system') {
        $config = Config::get('service.mail.' . $key);
        if (!$config) {
            throw new Exception("mail $key config not find");
        }

        foreach ($this->need_config as $key) {
            if (!array_key_exists($key, $config)) {
                throw new Exception("mail config must have $key");
            }
        }
        $this->config = $config;
    }

    public function send($mail, $object, $msg, $files = []) {
        if(!is_array($mail)){
            $mail_list[] = $mail;
        }else{
            $mail_list = $mail;
        }
        $transport = (new Swift_SmtpTransport($this->config['host'], $this->config['port']))
            ->setUsername($this->config['user'])
            ->setPassword($this->config['password']);
        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message())
            ->setFrom([$this->config['user'] => $this->config['nickname']])
            ->setSubject($object)
            ->setTo($mail_list)
            ->setBody($msg, 'text/html');

        if (is_array($files) && !empty($files)) {
            foreach ($files as $file_name => $file_path) {
                $message->attach(Swift_Attachment::fromPath($file_path)->setFilename($file_name));
            }
        }

        $ret = false;
        try {
            $ret = $mailer->send($message);
        } catch (\Exception $e) {
            Logger::getInstance()->error([$e->getCode(), $e->getMessage()]);
            throw new Exception($e->getMessage());
        }
        return $ret ? true : false;
    }
}