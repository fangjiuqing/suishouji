<?php
include_once 'common.php';
include_once 'pdo.class.php';

$id = isset($_GET['eid']) ? intval($_GET['eid']) : 0;


if ( $id > 0 ) {

    $dbh    = DB::getInstance();
    $sql    = "DELETE FROM `account` WHERE id={$id}";
    $result = $dbh->deleteRow($sql);
    
    if ( $result ) {
        out_succ(['id' => $id]);
    }
}
out_fail('删除失败');
