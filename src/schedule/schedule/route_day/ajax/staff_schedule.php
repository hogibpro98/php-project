<?php

/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

// try{
$response   = "";
$stfPlanId  = h(filter_input(INPUT_POST, 'id'));
$day        = h(filter_input(INPUT_POST, 'day'));
$startTime  = h(filter_input(INPUT_POST, 'start_time'));
$endTime    = h(filter_input(INPUT_POST, 'end_time'));
$work       = h(filter_input(INPUT_POST, 'work'));
$status     = h(filter_input(INPUT_POST, 'status'));
$rootName   = h(filter_input(INPUT_POST, 'root_name'));
$rootId     = h(filter_input(INPUT_POST, 'root_id'));
$placeId    = h(filter_input(INPUT_POST, 'place_id'));
$loginUser  = $_SESSION['login'] ;

// 対象テーブル(メイン)
$table = 'dat_staff_plan';

// 更新配列作成
$upData = array();
if (!empty($stfPlanId)) {
    $upData['unique_id'] = $stfPlanId;
}
$upData['staff_id']   = $loginUser['unique_id'];
$upData['target_day'] = !empty($day) ? $day : "";
$upData['start_time'] = $startTime;
$upData['end_time']   = $endTime;
if (!empty($work)) {
    $upData['work']   = $work;
}
$upData['root_name']  = !empty($rootName) ? $rootName : "未割当";
$upData['root_id']    = $rootId;
$upData['place_id']   = $placeId;

// DBへ格納
$res = array();
$res = upsert($loginUser, $table, $upData);
if (isset($res['err'])) {
    $response = 'システムエラーが発生しました';
    throw new Exception();
} else {
    $response = $res;
}

echo $response;
exit;
