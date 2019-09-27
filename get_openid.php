<?php
include_once 'common.php';
$code = isset($_GET['code']) ? trim($_GET['code']) : '';
$appid = 'wx850258dce379f1cf';
$appsecret = "ed78849a4803d63970e447988fcf5c0c";

if ( $code ) {
    $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $appsecret .'&js_code=' . $code . '&grant_type=authorization_code';
    $request = file_get_contents($url);
    $open_id = json_decode($request)->openid;
    out_succ($open_id , 200, '成功了');
}
out_fail(2003 , '出错了');
