<?php
include_once 'common.php';
include_once 'pdo.class.php';

$dbh = DB::getInstance();

$sql = 'SELECT * FROM account ';

# 是否获取单条记录，直接返回
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ( $id ) {
   $sql .= " WHERE id = {$id}";
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
$where = ' WHERE 1=1 ';

// 费用类型
$type = isset($_POST['goodsType']) ? intval($_POST['goodsType']) : 0;
if ( $type ) {
   $where .= ' AND cat = ' . $type;
}

// 关键字
$keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
if ( $keyword ) {
   $where .= " AND `title` LIKE '%{$keyword}%' ";
}

// 时间筛选
$start = isset($_POST['start']) ? $_POST['start'] : 0;
if ( $start ) {
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/',$start) ) {
        $where .= " AND `created_at` >= " . strtotime($start);
    }
}

$end = isset($_POST['end']) ? $_POST['end'] : 0;
if ( $end ) {
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/',$end) ) {
        $where .= " AND `created_at` <= " . strtotime($end);
    }
}


if ( $where ) {
   $sql = $sql . $where;
}

$sql .= ' ORDER BY created_at DESC';

// 分页
$pageSize = 10000;
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$offset = $pageSize * $page;

//$sql  .= ' LIMIT ' . $offset . ',' . $pageSize;

$total_pages = $dbh->getRow("SELECT count(*) AS total FROM `account` " . $where);

$pages = ceil($total_pages['total'] / $pageSize);

$data = $dbh->getAll($sql);

$new_data = [];
foreach ($data as $v) {
    $v['created_at'] = date('Y/m/d' , $v['created_at']);
    $new_data[] = $v;
}
$total_row = $dbh->getRow('SELECT SUM(ammount) AS total FROM account ' . $where);
$total = $total_row['total'];
$out_data = [
   'data' => $new_data,
   'total_fee' => $total + 0.0,
   'pages'   => $pages,
   'items'   => $total_pages['total']
];
out_succ($out_data , 200);
