<?php
include_once 'common.php';
include_once 'pdo.class.php';

$data = isset($_POST['data']) ? $_POST['data'] : [];


if (!empty($data)) {
    if ( preg_match('/^\d{4}-\d{2}-\d{2}$/',$data['created_at']) ) {
        //out_fail('ok');
        $data['created_at'] = strtotime($data['created_at']);
    }

    if ( $data['cat'] ) {
        if ( !is_numeric($data['cat']) ) {
            //out_fail('cat');
            $navss = array_flip($navs);
            $data['cat'] = $navss[$data['cat']];
        }
    } 

    $dbh = DB::getInstance();
    
    $lastId = $dbh->insert('account' , $data);

    if ( $lastId ) {
        out_succ(['rowId' => $lastId]);
    }
}
out_succ($data);
out_fail('增加失败');
