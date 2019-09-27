<?php
include_once 'common.php';
include_once 'pdo.class.php';

$payload = json_decode(file_get_contents('php://input') , true);

if ( json_last_error() > 0 ) {
    out_fail(2001 , '出错了');
}
$openid = isset($payload['openid']) ? $payload['openid'] : '';
$userinfo  = isset($payload['userinfo']) ? $payload['userinfo'] : [];

if ( !$openid || !$userinfo ) {
    out_fail(2001 , '出错了');
}

$dbh = DB::getInstance();

$sql   = 'SELECT * FROM user WHERE openid = ? LIMIT 1';
$user  = $dbh->getRow($sql , [$openid]);

/**
 * 第一次授权，将用户信息存入user表
 */
if ( empty($user) ) {
    if ( empty($userinfo) ) out_fail(2002 , '出错了');
    $data = [
        'uid'      => md5(time() . uniqid()),
        'unicke'   => isset($userinfo['nickName']) ? $userinfo['nickName'] : '',
        'avatar'   => isset($userinfo['avatarUrl']) ? $userinfo['avatarUrl'] : '',
        'province' => isset($userinfo['province']) ? $userinfo['province'] : '',
        'city'     => isset($userinfo['city']) ? $userinfo['city'] : '',
        'gender'   => isset($userinfo['gender']) ? $userinfo['gender'] : '',
        'openid'   => $openid,
    ];

    $res = $dbh->insert('user' , $data);

    if ( !$res ) {
        out_succ($data , 200, '成功了');
    }
    out_fail(2003 , '出错了');
}

out_succ($user , 200, '成功了');



