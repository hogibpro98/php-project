<?php

/* --共通ファイル呼び出し------------------------------------- */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

// try{
$response = "";
$uniqueId = h(filter_input(INPUT_POST, 'id'));
$startTime = h(filter_input(INPUT_POST, 'start_time'));
$endTime = h(filter_input(INPUT_POST, 'end_time'));
$loginUser = $_SESSION['login'];
$staff_id = $loginUser['unique_id'];

// 対象テーブル(メイン)
$table = 'dat_week_schedule_service';

// 初期値
$upData = array();
if (!empty($uniqueId)) {
    $upData['unique_id'] = $uniqueId;
}
$upData['start_time'] = $startTime;
$upData['end_time'] = $endTime;

// DBへ格納
$res = array();
$res = upsert($loginUser, $table, $upData);
if (isset($res['err'])) {
    $response = 'システムエラーが発生しました';
    throw new Exception();
} else {
    $response = $res;

    // ログテーブルに登録する
    setEntryLog($upData);
}

// $response = "正常";
//$response = $staff_id;
//echo json_encode($response);
echo $response;
exit;
