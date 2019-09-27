<?php
include_once 'common.php';
include_once 'pdo.class.php';

$openid = isset($_POST['openid']) ? $_POST['openid'] : '';


if ( !$openid ) {
    $openid = isset($_GET['openid']) ? $_GET['openid'] : '';

    if ( !$openid ) out_fail(404);
}

$dbh = DB::getInstance();

$sql = sprintf("SELECT * FROM account WHERE openid = '%s'" , $openid);

# 是否获取单条记录，直接返回
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ( $id ) {
   $sql .= " AND id = {$id}";
   $data = $dbh->getRow($sql);

   if ( !empty($data) ){
       $data['created_at'] = date('Y-m-d' , $data['created_at']);
       out_succ($data);
   }
   out_fail(404);
}

/**
 *获取列表并返回
 */

// 费用类型
$type = isset($_POST['goodsType']) ? intval($_POST['goodsType']) : 0;
if ( $type ) {
   $sql .= ' AND cat = ' . $type;
}

// 关键字
$keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
if ( $keyword ) {
   $sql .= " AND `title` LIKE '%{$keyword}%' ";
}

// 时间筛选
$start = isset($_POST['start']) ? $_POST['start'] : 0;
if ( $start ) {
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/',$start) ) {
        $sql .= " AND `created_at` >= " . strtotime($start);
    }
}

$end = isset($_POST['end']) ? $_POST['end'] : 0;
if ( $end ) {
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/',$end) ) {
        $sql .= " AND `created_at` <= " . strtotime($end);
    }
}



$sql .= ' ORDER BY created_at DESC';

// 分页
$pageSize = 10000;
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$offset = $pageSize * $page;

//$sql  .= ' LIMIT ' . $offset . ',' . $pageSize;

$total_sql   = str_ireplace("SELECT *" , "SELECT count(1) AS total" , $sql);
$total_pages = $dbh->getRow($total_sql);

$pages = ceil($total_pages['total'] / $pageSize);

$data = $dbh->getAll($sql);

$new_data = [];
foreach ($data as $v) {
    $v['created_at'] = date('Y/m/d' , $v['created_at']);
    $new_data[] = $v;
}

$total_sql   = str_ireplace("SELECT *" , "SELECT SUM(ammount) AS total" , $sql);
$total_row = $dbh->getRow($total_sql);
$total = $total_row['total'];
$out_data = [
   'data' => $new_data,
   'total_fee' => $total + 0.0,
   'pages'   => $pages,
   'items'   => $total_pages['total']
];
out_succ($out_data , 200);
