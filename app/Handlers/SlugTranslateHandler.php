<?php

namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;
use Illuminate\Support\Facades\Log;

/**
 *
 * Class SlugTranslateHandler
 * @package App\Handlers
 */
class SlugTranslateHandler
{
    public function translate($text)
    {
        // 实例化 HTTP 客户端
        $http   = new Client();

        // 初始化配置信息
        $api    = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $appid  = config('services.baidu_translate.appid');
        $key    = config('services.baidu_translate.key');

        $salt   = time();

        // 如果没有百度， 则用拼音方案
        if (empty($appid) || empty($key)){
            dump(11);
            return $this->pinyin($text);
        }

        $sign   = md5($appid . $text . $salt . $key);
        $query  = http_build_query([
            'q'     => $text,
            'from'  => 'zh',
            'to'    => 'en',
            'appid' => $appid,
            'salt'  => $salt,
            'sign'  => $sign,
        ]);

        $response   = $http->get($api.$query);

        $result     = json_decode($response->getBody(), true);

        if (isset($result['trans_result'][0]['dst'])) {
            //dump(111);

            return str_slug($result['trans_result'][0]['dst']);
        } else {
            Log::debug('没有翻译成功');
            Log::debug($response->getBody());
            return $this->pinyin($text);
        }
    }

    public function pinyin($text)
    {
        return str_slug(app(Pinyin::class)->permalink($text));
        //return str_slug(app(Pinyin::class)->permalink($text));
    }
}