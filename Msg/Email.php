<?php

namespace Msg;

use Base\Config;
use Log\Logger;
use Base\Exception;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;

class Email
{
    protected array $must_exist_key = array(
        'host',
        'port',
        'ssl',
        'username',
        'password',
        'nickname'
    );

    /**
     * @throws Exception
     */
    public function __construct($config)
    {
        if (!is_array($config)) {
            $config = Config::get($config);
        }
        if (!$config) {
            throw new Exception("mail {$config} config not find");
        }

        foreach ($this->must_exist_key as $key) {
            if (!array_key_exists($key, $config)) {
                throw new Exception("mail config must have $key");
            }
        }
        $config['nickname'] = $config['nickname'] ?: $config['username'];
        $this->config = $config;
    }

    /**
     * @param $mail
     * @param $object
     * @param $msg
     * @param array $files
     * @return bool
     * @throws Exception
     */
    public function send($mail, $object, $msg, array $files = []): bool
    {
        if (!is_array($mail)) {
            $mail_list[] = $mail;
        } else {
            $mail_list = $mail;
        }

        $ssl = $this->config['ssl'] ? 'ssl' : null;

        $transport = (new Swift_SmtpTransport($this->config['host'], $this->config['port'], $ssl))
            ->setUsername($this->config['username'])
            ->setPassword($this->config['password']);
        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message())
            ->setFrom([$this->config['username'] => $this->config['nickname']])
            ->setSubject($object)
            ->setTo($mail_list)
            ->setBody($msg, 'text/html');

        if (is_array($files) && !empty($files)) {
            foreach ($files as $file_name => $file_path) {
                $message->attach(Swift_Attachment::fromPath($file_path)->setFilename($file_name));
            }
        }

        try {
            $ret = $mailer->send($message);
        } catch (\Exception $e) {
            Logger::error('邮件发送失败错误信息', [$e->getCode(), $e->getMessage(), $e]);
            throw new Exception($e->getMessage());
        }
        return (bool)$ret;
    }
}