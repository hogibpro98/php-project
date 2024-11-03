<?php
//=====================================================================
// 記録一覧
//=====================================================================
try {
/* =================================================== 
 * 初期処理
 * ===================================================
 */


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

// 検索配列
$search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$search = $search ? $search : array();

$search['kana']      = isset($search['kana'])      ? $search['kana']      : NULL;
$search['start_day'] = isset($search['start_day']) ? $search['start_day'] : NULL;
$search['end_day']   = isset($search['end_day'])   ? $search['end_day']   : NULL;
$search['status']    = isset($search['status'])    ? $search['status']    : NULL;

/*-- 更新用パラメータ ---------------------------------------*/

// その他
$upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();

/*-- その他パラメータ ---------------------------------------*/


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


/* -- 利用者名称 ---------------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,first_name,last_name';
$temp = select('mst_user', $target, $where);
foreach ($temp as $val){
    $tgtId = $val['unique_id'];
    $userName[$tgtId] = $val['last_name'].' '.$val['first_name'];
}

/* -- 看多機記録 -----------------------*/
$where = array();
$where['delete_flg'] = 0;
//$target  = 'unique_id, create_date, user_id, service_day, start_time, end_time, ';
//$target .= 'important, service_kind, measures_contents, state_care, state_nurse, ';
//$target .= 'family_contact, staff_message, other';
$temp = select('doc_kantaki','*',$where);
foreach ($temp as $val){
    
    // 帳票種類、KEYID、作成日時
    $val['type'] = '看多機記録';
    $tgtId   = $val['unique_id'];
    $tgtDate = $val['create_date'];
    
    // ステータス、利用者名、スタッフ名
    $val['status'] = !empty($val['status'])
            ? $val['status']
            : '作成中';
    $userId = $val['user_id'] ? $val['user_id'] : 'dummy';
    $val['user_name'] = isset($userName[$userId]) ? $userName[$userId] : NULL;
    
    // サービスの種類
    $val['service_kind'] = isset($val['service_kind']) ? $val['service_kind'] : '未選択';
    
    // 対象データ格納
    $ktkList[$tgtId]['main'] = $val;
    $tgtIds['kantaki'][] = $tgtId;
    
    // 描画用インデックス
    $dispData[$tgtDate]['type'] = $val['type'];
    $dispData[$tgtDate]['id']   = $tgtId;
}


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
$temp = select('doc_progress','*',$where);
foreach ($temp as $val){
    
    // 帳票種類、KEYID、作成日時
    $val['type'] = '経過記録';
    $tgtId   = $val['unique_id'];
    $userId  = $val['user_id'];
    $tgtDate = $val['create_date'];

    $val['user_name'] = isset($userName[$userId]) ? $userName[$userId] : NULL;
    
    
    // 対象データ格納
    $pgsList[$tgtId]['main'] = $val;
    $tgtIds['progress'][] = $tgtId;
    
    // 描画用インデックス
    $dispData[$tgtDate]['type'] = $val['type'];
    $dispData[$tgtDate]['id']   = $tgtId;
}

/* -- 訪問看護記録1 ------------------------*/
$where = array();
$where['delete_flg'] = 0;
$temp = select('doc_visit1', '*', $where);
foreach ($temp as $val){
    
    // 帳票種類、KEYID、作成日時
    $val['type'] = '訪問看護記録1';
    $tgtId   = $val['unique_id'];
    $tgtDate = $val['create_date'];
    
    // 利用者ID、利用者名称、作成日、要介護度、スタッフ名称
    $userId = $val['user_id'] ? $val['user_id'] : 'dummy';
    $val['user_name'] = isset($userName[$userId]) ? $userName[$userId] : NULL;
    $reportDay = $val['report_day'] && $val['report_day'] != '0000-00-00'
            ? $val['report_day']
            : TODAY;
    $val['care_kb']    = getCareRank($userId, $reportDay);
    $val['staff_name'] = getStaffName($val['staff_id']);
    
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
//$target  = 'unique_id, user_id, importantly, staff1_id, service_day, start_time, end_time,';
//$target .= 'temperature, pulse, blood_pressure1, blood_pressure2, pneusis, pneusis_right, pneusis_left, ';
//$target .= 'spo2, urination_frequency, evacuation_frequency, evacuation_memo';
$temp = select('doc_visit2', '*', $where);
foreach ($temp as $val){
    
    // 帳票種類、KEYID、作成日時、利用者ID、スタッフID
    $val['type'] = '訪問看護記録2';
    $tgtId   = $val['unique_id'];
    $tgtDate = $val['create_date'];
    $userId  = isset($val['user_id'])   ? $val['user_id']   : NULL;
    $staffId = isset($val['staff1_id']) ? $val['staff1_id'] : NULL;

    // 利用者名、スタッフ名、ステータス
    $val['user_name']  = $userId ? $userName[$userId] : NULL;
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
