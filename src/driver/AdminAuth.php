<?php


namespace auth\driver;


class AdminAuth
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
        $user_id = isset($params['user_id']) ? $params['user_id'] : '';
        if($user_id){
            self::$key .= $user_id;
            $token = self::$redis->get(self::$key);
            if(!$token){
                return json(999, '请重新登录');
            }
            $params['token'] = $token;
        }

        $result = sign($params);
        if($result){
            return json();
        }

        return json(505, '签名错误');
    }
}
