<?php
//=====================================================================
// 記録一覧
//=====================================================================


try {
/* =================================================== 
 * 初期処理
 * ===================================================
 */
// Set max_input_vars
ini_set('max_input_vars', 3000); // Replace 1000 with your desired value

// Set post_max_size
ini_set('post_max_size', '200M'); // Replace '20M' with your desired value


// phpinfo();
// die;
/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'].'/common/php/com_start.php');

/*--変数定義-------------------------------------------------*/

// 初期化
$err      = array();
$_SESSION['notice']['error'] = array();
$dispData = array();
$dispFml  = array();
$dispFcl  = array();
$tgtData  = array();
$upData   = array();
$ktkList  = array();
$vst1List = array();
$vst2List = array();
$pgsList  = array();
$vst2PrbList  = array();

$userId = NULL;

// 対象テーブル
$table1 = 'doc_visit1';
$table2 = 'doc_visit1_family';
$table3 = 'doc_visit1_facility';

// 表示件数
$line = 20;


/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/
$placeId = filter_input(INPUT_GET, 'place');
if (!$placeId) {
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : NULL;
    // $where['delete_flg'] = 0;
    // $orderBy = 'unique_id ASC';
    // $temp = select('mst_place', 'unique_id,name', $where, $orderBy);
    // foreach ($temp as $val){
    //     $branch_staff_selected = $val['name'];
    //     $branch_selected[$val['unique_id']] = $val['name'];
    // }
}
//die($placeId);
// 検索配列
$search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
//$search = $search ? $search : array();
//echo "<pre>";print_r($_POST);die;
$search['kana']      = isset($search['kana'])      ? $search['kana']      : NULL;
$search['start_day'] = isset($search['start_day']) ? $search['start_day'] : NULL;
$search['end_day']   = isset($search['end_day'])   ? $search['end_day']   : NULL;
$search['status1']    = isset($search['status1'])    ? $search['status1']    : NULL;
$search['status2']    = isset($search['status2'])    ? $search['status2']    : NULL;
$search['status_user']    = isset($search['status_user'])    ? $search['status_user']    : NULL;
$search['importance']    = isset($search['importance'])    ? $search['importance']    : NULL;
$search['branchType_search']    = isset($_GET['branchType_search'])    ? $_GET['branchType_search']    : NULL;

$search['care_kb']    = isset($search['care_kb'])    ? $search['care_kb']    : NULL;
$search['care_kb_type1']    = isset($search['care_kb_type1'])    ? $search['care_kb_type1']    : NULL;
$search['care_kb_type2']    = isset($search['care_kb_type2'])    ? $search['care_kb_type2']    : NULL;
//echo "<pre>";print_r($search);die;
/*-- 更新用パラメータ ---------------------------------------*/


/*-- その他パラメータ ---------------------------------------*/
$btnSearch = h(filter_input(INPUT_GET, 'btnSearch'));
$btnSearch_all = h(filter_input(INPUT_GET, 'btnSearch_all'));

// ページャー
$page = h(filter_input(INPUT_GET, 'page'));



/* =================================================== 
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ----------------------------------------*/


/* =================================================== 
 * イベント本処理(データ登録)
 * ===================================================
 */


/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */



/* -- データ取得 --------------------------------------------*/

/* -- 汎用マスタ ---------------------------*/
$gnrList = getCode('記録一覧');
//echo "<pre>";print_r($search);die;

/* -- 利用者名称 ---------------------------*/
// $where = array();
// $where['mst_user.delete_flg'] = 0;
// $target = 'mst_user.unique_id,mst_user.first_name,mst_user.last_name,mst_user.first_kana,mst_user.last_kana,mst_user_office1.office_id';
// $tables = array('mst_user_office1');

// // The joinCol parameter should be an array. 
// // Assuming the join is on 'user_id' between 'mst_user' and 'mst_user_office1'.
// $joinCol = array('user_id');

// // Call the function with the corrected parameters
// $temp = getMultiData('mst_user', $tables, $target, $where, $joinCol);
/* -- 利用者名称 ---------------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,first_name,last_name,last_kana,first_kana';
$temp = select('mst_user', $target, $where);

foreach ($temp as $val){
    $tgtId = $val['unique_id'];
    $userName[$tgtId] = $val['last_name'].' '.$val['first_name'];
    $userkana[$tgtId] = @$val['last_kana'].'|'.@$val['first_kana'];

        
   
}
// 所属情報の取得
$where = array();
@$where['user_id'] != "";
$target = 'user_id,office_id,unique_id';
$temp = select('mst_user_office1',$target,$where);
foreach ($temp as $val)
{
    $tgtId = "";
    $tgtId = @$val['user_id'];
    $useroffice1[$tgtId] = @$val['office_id'];
    $useroffice_data[$tgtId]['office1'] = $val;
}
// 所属情報の取得
$where = array();
@$where['office1_id'] != "";
$target = 'office1_id,place_id,unique_id';
$temp = select('mst_staff_office',$target,$where);
foreach ($temp as $val)
{
    $tgtId = "";
    $tgtId = @$val['office1_id'];
    $userplace[$tgtId] = @$val['place_id'];
}


/* -- 看多機記録 -----------------------*/
$where = array();
$where['delete_flg'] = 0;
if (!empty($search['start_day'])) {
    $where['service_day >='] = $search['start_day'];
}
// 日付(To)絞込条件設定
if (!empty($search['end_day'])) {
    $where['service_day <='] = $search['end_day'];
}

//$target  = 'unique_id, create_date, user_id, service_day, start_time, end_time, ';
//$target .= 'important, service_kind, measures_contents, state_care, state_nurse, ';
//$target .= 'family_contact, staff_message, other';
$temp = select('doc_kantaki','*',$where);
foreach ($temp as $val){
   
    
   
    // ステータス、利用者名、スタッフ名
    $val['status'] = !empty($val['status'])
            ? $val['status']
            : '作成中';
    $userId = $val['user_id'] ? $val['user_id'] : 'dummy';
    $val['user_name'] = isset($userName[$userId]) ? $userName[$userId] : NULL;
    // サービスの種類
    $val['service_kind'] = isset($val['service_kind']) ? $val['service_kind'] : '未選択';





    if(@$search['branchType_search']!="" && @$search['branchType_search']=="branch")
    {
        if (@$userplace[@$useroffice1[@$userId]] !== $placeId) {
            continue; // Skip this record if place ID does not match
        }  
    }
    if (!empty($search['care_kb'])){
        if($search['care_kb'] == "経過記録" || $search['care_kb'] == "看護記録Ⅰ" || $search['care_kb'] == "看護記録Ⅱ"){
                continue;
        }
    }
    if (!empty($search['care_kb_type1'])){
                continue;
    }
    if (!empty($search['care_kb_type2'])){
                continue;
    }
    // 氏名(カナ)
    if (!empty($search['kana'])){
        $word = "";
        $word = @$userkana[$userId];
        if (strpos($word, @$search['kana']) === FALSE){
            continue;
        }
    }
    //契約中
    if (!empty($useroffice_data[$userId]['office1'])){
        $datsearch['status_user'] = '契約中';
        $datsearch['st_cls'] = 'status';
    } else {
        $datsearch['status_user'] = '全て';
        $datsearch['st_cls'] = 'status2';
    }
    if ($search['status_user'] == "契約中"){
        if ($search['status_user'] !== $datsearch['status_user']){
            continue;
        }
    }
    // 重要
    if (!empty($search['importance'])){
        if($search['importance'] == "1"){
            if ($val['important'] != "重要"){
                continue;
            }
        }
        else{
            if ($val['important'] != ""){
                continue;
            }
        }
    }
    // /作成状態
    if (!empty($search['status1']) && empty($search['status2'])){
        if ($val['status'] != "完成"){
            continue;
        }
    }
    if (!empty($search['status2']) && empty($search['status1'])){
        if ($val['status'] != "作成中"){
            continue;
        }
    }



    // 帳票種類、KEYID、作成日時
    $val['type'] = '看多機記録';
    $tgtId   = $val['unique_id'];

    $tgtDate = $val['create_date'];

    // 対象データ格納
    $ktkList[$tgtId]['main'] = $val;
    $tgtIds['kantaki'][] = $tgtId;
    
    // 描画用インデックス
    $dispData[$tgtDate]['type'] = $val['type'];
    $dispData[$tgtDate]['id']   = $tgtId;
}
//echo "<pre>".$search['start_day']."    ".$search['end_day'];print_r($dispData);print_r($ktkList);die;
/* -- 看多機記録（スタッフ） -----------------------*/
$where = array();
$where['delete_flg'] = 0;
//$target  = 'kantaki_id, name';
$temp = select('doc_kantaki_staff','*',$where);
if (!empty($tgtIds['kantaki_id'])){
    $where = array();
    $where['delete_flg'] = 0;
    $where['kantaki_id']  = $tgtIds['kantaki'];
    $temp = select('doc_kantaki_staff','*',$where);
    foreach ($temp as $val){
        $tgtId = $val['kantaki_id'];
        $val['name'] = isset($val['name'])
                ? $val['name']
                : '';
        
        $ktkStaffList[$tgtId][] = $val;
    }    
}

/* -- 看多機記録（バイタル） -----------------------*/
$where = array();
$where['delete_flg'] = 0;
$target  = 'kantaki_id, temperature, pulse, blood_pressure1, blood_pressure2, spo2';
$temp = select('doc_kantaki_vital',$target,$where);
foreach ($temp as $val){
    $tgtId       = $val['kantaki_id'];
    $ktkVitalList[$tgtId] = $val;
}

/* -- 看多機記録（排泄） -----------------------*/
$where = array();
$where['delete_flg'] = 0;
$target  = 'kantaki_id, urination_quantity, evacuation, evacuation_memo';
$temp = select('doc_kantaki_excretion',$target,$where);
foreach ($temp as $val){
    $tgtId       = $val['kantaki_id'];
    $ktkWaterList[$tgtId] = $val;
}

/* -- 経過記録 -----------------------*/
$where = array();
$where['delete_flg'] = 0;
if (!empty($search['start_day'])) {
    $where['create_date >='] = $search['start_day'];
}
// 日付(To)絞込条件設定
if (!empty($search['end_day'])) {
    $where['create_date <='] = $search['end_day'];
}

$temp = select('doc_progress','*',$where);
foreach ($temp as $val){
    
    
    $userId  = $val['user_id'];
   

    $val['user_name'] = isset($userName[$userId]) ? $userName[$userId] : NULL;





    if(@$search['branchType_search']!="" && @$search['branchType_search']=="branch")
    {
        if (@$userplace[@$useroffice1[@$userId]] !== $placeId) {
            continue; // Skip this record if place ID does not match
        }  
    }
    if (!empty($search['care_kb'])){
        if($search['care_kb'] == "看多機記録" || $search['care_kb'] == "看護記録Ⅰ" || $search['care_kb'] == "看護記録Ⅱ"){
                continue;
        }
    }
    
    if (!empty($search['care_kb_type1'])){
        if($val['type1'] != $search['care_kb_type1']){
            continue;
        }
    }
    if (!empty($search['care_kb_type2'])){
        if($val['type2'] != $search['care_kb_type2']){
            continue;
        }
    }
    // 氏名(カナ)
    if (!empty($search['kana'])){
        $word = "";
        $word = @$userkana[$userId];
        if (strpos($word, @$search['kana']) === FALSE){
            continue;
        }
    }
    //契約中
    if (!empty($useroffice_data[$userId]['office1'])){
        $datsearch['status_user'] = '契約中';
        $datsearch['st_cls'] = 'status';
    } else {
        $datsearch['status_user'] = '全て';
        $datsearch['st_cls'] = 'status2';
    }
    if ($search['status_user'] == "契約中"){
        if ($search['status_user'] !== $datsearch['status_user']){
            continue;
        }
    }
    // 重要
    if (!empty($search['importance'])){
        if($search['importance'] == "1"){
            if ($val['importantly'] != "重要"){
                continue;
            }
        }
        else{
            if ($val['importantly'] != ""){
                continue;
            }
        }
    }
    // /作成状態
    if (!empty($search['status1']) && empty($search['status2'])){
        if ($val['status'] != "完成"){
            continue;
        }
    }
    if (!empty($search['status2']) && empty($search['status1'])){
        if ($val['status'] != "作成中"){
            continue;
        }
    }
    // if(empty($search['status1']) && empty($search['status2']))
    // {
    //     if ($val['status'] != ""){
    //         continue;
    //     }
    // }





    
    // 帳票種類、KEYID、作成日時
    $val['type'] = '経過記録';
    $tgtId   = $val['unique_id'];
    $tgtDate = $val['create_date'];
    
    // 対象データ格納
    $pgsList[$tgtId]['main'] = $val;
    $tgtIds['progress'][] = $tgtId;
    
    // 描画用インデックス
    $dispData[$tgtDate]['type'] = $val['type'];
    $dispData[$tgtDate]['id']   = $tgtId;
}
//echo "<pre>".$search['start_day']."    ".$search['end_day'];print_r($pgsList);print_r($temp);die;
/* -- 訪問看護記録1 ------------------------*/
$where = array();
$where['delete_flg'] = 0;
if (!empty($search['start_day'])) {
    $where['create_date >='] = $search['start_day'];
}
// 日付(To)絞込条件設定
if (!empty($search['end_day'])) {
    $where['create_date <='] = $search['end_day'];
}
$temp = select('doc_visit1', '*', $where);
foreach ($temp as $val){
    
    
   
    
    // 利用者ID、利用者名称、作成日、要介護度、スタッフ名称
    $userId = $val['user_id'] ? $val['user_id'] : 'dummy';
    $val['user_name'] = isset($userName[$userId]) ? $userName[$userId] : NULL;
    $reportDay = $val['report_day'] && $val['report_day'] != '0000-00-00'
            ? $val['report_day']
            : TODAY;
    $val['care_kb']    = getCareRank($userId, $reportDay);
    $val['staff_name'] = getStaffName($val['staff_id']);





    if(@$search['branchType_search']!="" && @$search['branchType_search']=="branch")
    {
        if (@$userplace[@$useroffice1[@$userId]] !== $placeId) {
            continue; // Skip this record if place ID does not match
        }  
    }
    if (!empty($search['care_kb'])){
        if($search['care_kb'] == "看多機記録" || $search['care_kb'] == "経過記録" || $search['care_kb'] == "看護記録Ⅱ"){
                continue;
        }
    }
    if (!empty($search['care_kb_type1'])){
        continue;
    }
    if (!empty($search['care_kb_type2'])){
            continue;
    }
    // 氏名(カナ)
    if (!empty($search['kana'])){
        $word = "";
        $word = @$userkana[$userId];
        if (strpos($word, @$search['kana']) === FALSE){
            continue;
        }
    }
    //契約中
    if (!empty($useroffice_data[$userId]['office1'])){
        $datsearch['status_user'] = '契約中';
        $datsearch['st_cls'] = 'status';
    } else {
        $datsearch['status_user'] = '全て';
        $datsearch['st_cls'] = 'status2';
    }
    if ($search['status_user'] == "契約中"){
        if ($search['status_user'] !== $datsearch['status_user']){
            continue;
        }
    }

    // 重要
    if (!empty($search['importance'])){
        if($search['importance'] == "1"){
            continue;
        }
    }
     // /作成状態
    if (!empty($search['status1']) && empty($search['status2'])){
        if ($val['status'] != "完成"){
            continue;
        }
    }
    if (!empty($search['status2']) && empty($search['status1'])){
        if ($val['status'] != "作成中"){
            continue;
        }
    }
    // if(empty($search['status1']) && empty($search['status2']))
    // {
    //     if ($val['status'] != ""){
    //         continue;
    //     }
    // }

    // 帳票種類、KEYID、作成日時
    $val['type'] = '訪問看護記録1';
    $tgtId   = $val['unique_id'];
    $tgtDate = $val['create_date'];




    // 対象データ格納
    $vst1List[$tgtId]['main'] = $val;
    $tgtIds['visit1'][] = $tgtId;
    
    // 描画用インデックス
    $dispData[$tgtDate]['type'] = $val['type'];
    $dispData[$tgtDate]['id']   = $tgtId;
}

/* -- 訪問看護記録2 ------------------------*/
$where = array();
$where['delete_flg'] = 0;
if (!empty($search['start_day'])) {
    $where['service_day >='] = $search['start_day'];
}
// 日付(To)絞込条件設定
if (!empty($search['end_day'])) {
    $where['service_day <='] = $search['end_day'];
}
//$target  = 'unique_id, user_id, importantly, staff1_id, service_day, start_time, end_time,';
//$target .= 'temperature, pulse, blood_pressure1, blood_pressure2, pneusis, pneusis_right, pneusis_left, ';
//$target .= 'spo2, urination_frequency, evacuation_frequency, evacuation_memo';
$temp = select('doc_visit2', '*', $where);
foreach ($temp as $val){
    
  
    
    $userId  = isset($val['user_id'])   ? $val['user_id']   : NULL;
    $staffId = isset($val['staff1_id']) ? $val['staff1_id'] : NULL;

    // 利用者名、スタッフ名、ステータス
    $val['user_name']  = @$userId ? @$userName[$userId] : NULL;
    $val['staff_name'] = getStaffName($staffId);
    $val['status'] = !empty($val['status'])
            ? $val['status']
            : '作成中';
    
    // 体温、脈拍、血圧、SPO2、排尿、排便
    $val['condition'] = $val['temperature']
            ? '体温:'.$val['temperature'].'℃、'
            : '体温:℃、';
    $val['condition'] = $val['pulse']
            ? $val['condition'].'脈拍:'.$val['pulse'].'／分、'
            : $val['condition'].'脈拍:、';
    $val['condition'] = $val['blood_pressure1']
            ? $val['condition'].'血圧:'.$val['blood_pressure1'].'mmHg/'
            : $val['condition'].'血圧:mmHg/';
    $val['condition'] = $val['blood_pressure2']
            ? $val['condition'].$val['blood_pressure2'].'mmHg、'
            : $val['condition'].'mmHg、';
    $val['condition'] = $val['spo2']
            ? $val['condition'].'SPO2:'.$val['spo2'].'％、'
            : $val['condition'].'SPO2:％、';
    $val['condition'] = $val['urination_frequency']
            ? $val['condition'].'排尿:'.$val['urination_frequency'].'回、'
            : $val['condition'].'排尿:回 、';
    $val['condition'] = $val['evacuation_frequency']
            ? $val['condition'].'排便:'.$val['evacuation_frequency'].'回、'
            : $val['condition'].'排便:回 、';
    $val['condition'] = $val['evacuation_memo']
            ? $val['condition'].'(メモ)'.$val['evacuation_memo']
            : $val['condition'].'(メモ)';





    if(@$search['branchType_search']!="" && @$search['branchType_search']=="branch")
    {
        if (@$userplace[@$useroffice1[@$userId]] !== $placeId) {
            continue; // Skip this record if place ID does not match
        }  
    }
    if (!empty($search['care_kb'])){
        if($search['care_kb'] == "看多機記録" || $search['care_kb'] == "経過記録" || $search['care_kb'] == "看護記録Ⅰ"){
                continue;
        }
    }
    if (!empty($search['care_kb_type1'])){
        continue;
    }
    if (!empty($search['care_kb_type2'])){
            continue;
    }
    // 氏名(カナ)
    if (!empty($search['kana'])){
        $word = "";
        $word = @$userkana[$userId];
        if (strpos($word, @$search['kana']) === FALSE){
            continue;
        }
    }
    //契約中
    if (!empty($useroffice_data[$userId]['office1'])){
        $datsearch['status_user'] = '契約中';
        $datsearch['st_cls'] = 'status';
    } else {
        $datsearch['status_user'] = '全て';
        $datsearch['st_cls'] = 'status2';
    }
    if ($search['status_user'] == "契約中"){
        if ($search['status_user'] !== $datsearch['status_user']){
            continue;
        }
    }
    
    // 重要
    if (!empty($search['importance'])){
        if($search['importance'] == "1"){
            if ($val['importantly'] != "重要"){
                continue;
            }
        }
        else{
            if ($val['importantly'] != ""){
                continue;
            }
        }
    }
    // /作成状態
    if (!empty($search['status1']) && empty($search['status2'])){
        if ($val['status'] != "完成"){
            continue;
        }
    }
    if (!empty($search['status2']) && empty($search['status1'])){
        if ($val['status'] != "作成中"){
            continue;
        }
    }
    // if(empty($search['status1']) && empty($search['status2']))
    // {
    //     if ($val['status'] != ""){
    //         continue;
    //     }
    // }
        
        
    // 帳票種類、KEYID、作成日時、利用者ID、スタッフID
    $val['type'] = '訪問看護記録2';
    $tgtId   = $val['unique_id'];
    $tgtDate = $val['create_date'];   
        
    // 対象データ格納
    $vst2List[$tgtId]['main'] = $val;
    $tgtIds['visit2'][] = $tgtId;
    
    // 描画用インデックス
    $dispData[$tgtDate]['type'] = $val['type'];
    $dispData[$tgtDate]['id']   = $tgtId;
}
/* -- 訪問看護記録2（問題点） -----------------------*/
if (!empty($tgtIds['visit2'])){
    $where = array();
    $where['delete_flg'] = 0;
    $where['visit2_id']  = $tgtIds['visit2'];
    $target  = 'visit2_id, problem';
    $temp = select('doc_visit2_problem',$target,$where);
    foreach ($temp as $val){
        $tgtId = $val['visit2_id'];
        $cnt = isset($vst2PrbList[$tgtId]['cnt'])
                ? $vst2PrbList[$tgtId]['cnt'] + 1
                : 1;
        $vst2PrbList[$tgtId]['cnt'] = $cnt;
        $vst2PrbList[$tgtId]['problem'] = isset($vst2PrbList[$tgtId]['problem'])
                ? $vst2PrbList[$tgtId]['problem']."\n\n[問題点".$cnt."]\n ".$val['problem']
                : '[問題点'.$cnt."]\n ".$val['problem'];
    }    
}

/* -- ソート処理 -----------------------*/
krsort($dispData);
    
/* -- その他 --------------------------------------------*/

$dispData0 = getPager($dispData, $page, $line);
// if ($btnSearch_all)
// {
    // Store the current URL with search parameters in a session variable
    $_SESSION['search_url'] = $_SERVER['REQUEST_URI'];
//}

/* =================================================== 
 * 例外処理
 * ===================================================
 */    
} catch(Exception $e){
    if ($execEnv === 'pro' || $execEnv === 'stg'){
        $_SESSION['err'] = !empty($err) ? $err : array();
        header("Location:". ERROR_PAGE);
        exit();        
    } else {
        debug($e);
        exit();
    }
}
