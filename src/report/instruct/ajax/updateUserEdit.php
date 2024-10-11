<?php
/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

// try{
$response       = "";
$userId         = h(filter_input(INPUT_POST, 'user_id'));
$hospital       = h(filter_input(INPUT_POST, 'hospital'));
$hospitalRece   = h(filter_input(INPUT_POST, 'hospital_rece'));
$doctor         = h(filter_input(INPUT_POST, 'doctor'));
$address1       = h(filter_input(INPUT_POST, 'address1'));
$tel1           = h(filter_input(INPUT_POST, 'tel1'));
$tel2           = h(filter_input(INPUT_POST, 'tel2'));
$fax            = h(filter_input(INPUT_POST, 'fax'));
$loginUser  = $_SESSION['login'] ;

// 対象テーブル(メイン)
$table = 'mst_user_hospital';

// 対象データの存在チェック
$temp  = array();
$where = array();
$tgtId = "";
$where['user_id'] = $userId;
$where['delete_flg'] = 0;
$orderBy = "unique_id ASC";
$temp = select($table, "*", $where, $orderBy);
foreach($temp as $val){
    $tgtId = $val['unique_id'];
}

// 更新配列作成
$upData = array();
if (!empty($tgtId)){
  $upData['unique_id'] = $tgtId;
}
$upData['name']      = !empty($hospital) ? $hospital : NULL;
$upData['disp_name'] = !empty($hospitalRece) ? $hospitalRece : "";
$upData['doctor']    = !empty($doctor)   ? $doctor : NULL;
$upData['address']   = !empty($address1) ? $address1 : NULL;
$upData['tel1']      = !empty($tel1)     ? $tel1 : NULL;
$upData['tel2']      = !empty($tel2)     ? $tel2 : NULL;
$upData['fax']       = !empty($fax)      ? $fax  : NULL;

// DBへ格納
$res = array();
$res = upsert($loginUser, $table, $upData);
if (isset($res['err'])) {
    $response = 'システムエラーが発生しました';
    throw new Exception();
}else{
    $response = $res;
  
    // ログテーブルに登録する
    setEntryLog($upData);
}

echo $response;
exit();
