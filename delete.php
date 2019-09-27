<?php
include_once 'common.php';
include_once 'pdo.class.php';

$id = isset($_GET['eid']) ? intval($_GET['eid']) : 0;

$openid = isset($_GET['openid']) ? $_GET['openid'] : '';

if ( !$openid ) out_fail(403);


if ( $id > 0 ) {
    $dbh    = DB::getInstance();
    $sql    = sprintf("DELETE FROM `account` WHERE id=%d AND openid = '%s'" , $id, $openid);
    $result = $dbh->deleteRow($sql);

    if ( $result ) {
        out_succ(['id' => $id]);
    }
}
out_fail('删除失败');
