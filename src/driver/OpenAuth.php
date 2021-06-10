<?php


namespace auth\driver;


use auth\lib\Rsa;

class OpenAuth
{
    private static $redis;
    private static $key;
    /**
     * 构造函数
     * @access public
     */
    public function __construct($key, $debug, $redis)
    {
        if($debug == 'dev')
            return json();

        self::$key = $key;
        self::$redis = $redis;
    }

    public function check($params){
        $app_id = isset($params['app_id']) ? $params['app_id'] : '';
        if(!$app_id){
            return json(501, 'app_id参数不存在');
        }

        if(!isset($params['sign'])){
            return json(501, 'sign参数不存在');
        }

        self::$key .= 'merchant_public_key_'. $app_id;
        $public_key = self::$redis->get(self::$key);

        $sign = $params['sign'];
        unset($params['sign']);
        $result = Rsa::checkSignature($sign, $params, $public_key);
        if($result){
            return json();
        }

        return json(505, '签名错误');
    }
}
