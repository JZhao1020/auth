<?php
/**
 * 生成sign
 * @param $params
 * @return string
 */
function sign($params){
    $token = '';
    if(isset($params['token'])) {
        $token = $params['token'];
        unset($params['token']);
    }

    $sign = isset($params['sign']) ? $params['sign'] : '';

    //按照键名对关联数组进行升序排序
    ksort($params);

    $signStrArr = [];
    foreach ($params as $key=>$val){
        if (is_array($val)){
            foreach ($val as $v){
                $signStrArr[] = $key . '=' . $v;
            }
        }else{
            $signStrArr[] = $key . '=' . $val;
        }
    }
    if($token) {
        $signStrArr[] = 'token=' . $token;
    }

    return strtoupper(md5(implode('&', $signStrArr))) == $sign ? true : false;
}

function json($code = 200, $message = 'success'){
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode([
        'code' => $code,
        'msg'  => $message,
    ]));
}
