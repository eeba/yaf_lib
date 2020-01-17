<?php
namespace Api;

class Word{
    const PATH = '/string/word';

    /**
     * 分词
     *
     * @param $word
     * @param int $timeout
     * @return string
     * @throws \Base\Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function analysis($word, $timeout = Util::TIME_OUT){
        $params = ['word' => $word];

        return (new Util())->request("get",self::PATH, $params, [], $timeout);
    }
}