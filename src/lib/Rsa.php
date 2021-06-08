<?php


namespace auth\lib;


class Rsa{
    private static function getSignString($data)
    {
        unset($data['sign']);
        ksort($data);
        reset($data);
        $pairs = array();
        foreach ($data as $k => $v) {
            if (is_array($v)) $v = self::arrayToString($v);
            $pairs[] = "$k=$v";
        }
        return implode('&', $pairs);
    }

    private static function arrayToString($data)
    {
        $str = '';
        foreach ($data as $list) {
            if (is_array($list)) {
                $str .= self::arrayToString($list);
            } else {
                $str .= $list;
            }
        }
        return $str;
    }

    /**
     * 使用对方的公钥验签，并且判断签名是否匹配
     * @param $sign
     * @param $data
     * @param $public_key
     * @return bool
     */
    public static function checkSignature($sign, $data, $public_key)
    {
        $toSign = self::getSignString($data);
        $priPem = chunk_split($public_key, 64, "\n");
        $priPem = "-----BEGIN PUBLIC KEY-----\n" . $priPem . "-----END PUBLIC KEY-----";
        $publicKeyId = openssl_pkey_get_public($priPem);
        $result = openssl_verify($toSign, base64_decode($sign), $publicKeyId, OPENSSL_ALGO_MD5);
        openssl_free_key($publicKeyId);
        return $result === 1 ? true : false;
    }

}