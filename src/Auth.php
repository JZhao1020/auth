<?php


namespace auth;

use auth\lib\Redis;

class Auth{
    private $config;

    private $gateways;

    /**
     * Pay constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * 指定操作网关
     * @param string $gateway
     * @return GatewayInterface
     */
    public function gateway($gateway = 'Api')
    {
        return $this->gateways = $this->createGateway($gateway);
    }

    /**
     * 创建操作网关
     * @param string $gateway
     * @return mixed
     */
    protected function createGateway($gateway)
    {
        if (!file_exists(__DIR__ . '/driver/'. ucfirst($gateway) . 'Auth.php')) {
            throw new \Exception("Gateway [$gateway] is not supported.");
        }
        $gateway_class = __NAMESPACE__ . '\\driver\\' . ucfirst($gateway) . 'Auth';

        if(!$this->config){
            $file = __DIR__ . '/config/Config.php';
            if (!file_exists($file)) {
                throw new \Exception("config [$gateway] is not supported.");
            }

            $this->config = include $file;
        }

        $redis = new Redis($this->config);

        $key = 'auth:'. strtolower($gateway). '_';
        return new $gateway_class($key, $this->config['debug'], $redis);
    }
}