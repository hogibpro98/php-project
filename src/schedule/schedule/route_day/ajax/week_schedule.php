<?php

/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

$response = "";
$svcId = h(filter_input(INPUT_POST, 'schedule_id'));
$startTime = h(filter_input(INPUT_POST, 'start_time'));
$endTime = h(filter_input(INPUT_POST, 'end_time'));
$rootName = h(filter_input(INPUT_POST, 'root_name'));
$loginUser = $_SESSION['login'];

// 更新配列(サービス)
$upSvc = array();
if ($svcId) {
    $upSvc['unique_id']  = $svcId;
}
$upSvc['start_time'] = $startTime;
$upSvc['end_time']   = $endTime;
$upSvc['root_name']  = !empty($rootName) ? $rootName : '未割当';

// データ更新(サービス)
$res = upsert($loginUser, 'dat_user_plan', $upSvc);
if (isset($res['err'])) {
    $response = 'システムエラーが発生しました';
    throw new Exception();
} else {
    $response = $res;
}

// データ返却
echo $response;
exit;
