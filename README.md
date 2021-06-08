# auth
接口鉴权

## 开源地址
https://github.com/JZhao1020/auth

##1.安装
```
composer require hao/auth
```

##2.实例化
```
$config = [
	// redis配置
    'host'       => '127.0.0.1',
    'port'       => 6379,
    'password'   => '',
    'select'     => 0,
    'timeout'    => 0,
    'expire'     => 0,
    'persistent' => false,
    'prefix'     => '',
    'serialize'  => true,
];
$auth = new \auth\Auth($config);
```

####redis缓存格式
```
开放平台 - 商户公钥
$key = auth:open_merchant_public_key_xxx （xxx：app_id值）
// 获取公钥内容（仅中间部分）
$public_key = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqW1D7YQPfa2pURezqBbB+Pk9Requx2RP5HV8bl9OBpTMohs5eu/pjCh4UpjlNv75M2v6wuQv6yXUAwYER/jGrFxQVl9rVVegGPHgIbIMTvC8lktIQfe9CURex7vq63oF/GUAWXU+rliE+hg/RcGI53tRRllH7Tt2nB4mKltcpt4OvvaiDeiAZEnBgmgoSpaa3DBazHl1VoBmTvH2jEoo4fH3asQfZbrdf+0IC/zNb0l+QUJTQ7m1M6nvLjXMJGeiqkuAW4szZLLtghUFqqD0uURo5Or9A57Gu7mtNypqRG/7g/qc7/cdrSPzraO2H8RGdU05mg3wsXQCn1rhYiCwXwIDAQAB';
redis()->set($key, $public_key);


商户后台 - token（仅需要校验token的接口）
$key = auth:api_1 (1：用户id)
redis()->set($key, $token);


总后台 - token（仅需要校验token的接口）
$key = auth:admin_1 (1：用户id)
redis()->set($key, $token);
```

####调用
```
$auth->gateway('api')->check($params);
```

####返回码
```
code值
200     - 成功
401     - 校验失败
402     - 必填参数异常
```