<?php
$param['appid']  = 'wx850258dce379f1cf';
$param['secret'] = '1123546cf076ec7369560d0646aa823b';
$param['js_code'] = isset($_GET['code']) ? $_GET['code'] : '';
$param['grant_type'] = 'authorization_code';

if ( $param['js_code'] ) {
    $url  = 'https://api.weixin.qq.com/sns/jscode2session?';

    $url  .= http_build_query($param);

    $data  = file_get_contents($url);

    echo $data;
} 


