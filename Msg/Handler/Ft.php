<?php
namespace Msg\Handler;

class Ft extends Abstraction {

    public function send($to, $title, $msg, $files = []) {
        $post_data = http_build_query(
            array(
                'text' => $title,
                'desp' => $msg
            )
        );
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            )
        );
        $context = stream_context_create($opts);
        return $result = file_get_contents('https://sc.ftqq.com/' . $to . '.send', false, $context);
    }
}