<?php
ini_set("display_errors","On");
error_reporting(E_ALL);

date_default_timezone_set('Asia/Shanghai');
// 导航
$navs = [
    0  =>  '全部费用',
    1  =>  '医疗费',
    2  =>  '住宿费',
    3  =>  '交通费',
    4  =>  '餐饮费',
    5  =>  '其他费用',
];

/**
 * 输出JSON
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function out_succ($data , $code = 0, $msg = '成功了') {
    header('Content-Type:application/json; charset=utf-8');
    $out = [
        'code'  =>  $code,
        'msg'   =>  $msg,
        'data'  =>  $data
    ];
    $data_json = json_encode($out , JSON_UNESCAPED_UNICODE);
    echo $data_json;
    exit;
}

function out_fail ( $code = 1, $msg = '出错了') {
    header('Content-Type:application/json; charset=utf-8');
    $out = [
        'code'  =>  $code,
        'msg'   =>  $msg
    ];
    $data_json = json_encode($out , JSON_UNESCAPED_UNICODE);
    echo $data_json;
    exit;
}

/**
 * 过滤
 */
function filterInjection ( array &$data ){
    array_walk($data , function($key , $val) use (&$data) {
        if ( !is_array($key) ) {
            $data[$val] = is_numeric($key) ? $key : str_ireplace(
                ["'",'%' , '--' , '#' , 'UNION' , 'ASCII' , 'CHAR' , 'SUBSTRING'],
                ["\'", '\%' , '' ,  ''  , ''      , ''      , ''       , ''],
                trim($key)
            );
        }
    });
}
