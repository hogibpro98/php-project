<?php
//=====================================================================
// 訪問看護1
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
$upFcl    = array();
$upFml    = array();
$userId   = NULL;

// 対象テーブル
$table1 = 'doc_visit1';
$table2 = 'doc_visit1_family';
$table3 = 'doc_visit1_facility';

// 初期値
$dispData = initTable($table1);
$dispFml  = array();
$dispFcl  = array();
$dispData['other_id']    = NULL;
$dispData['user_name']   = NULL;
$week = formatDateTime(NOW, 'w');
$weekDisp = '('.$weekAry[$week].')';
$dispData['disp_report'] = formatDateTime(NOW, 'Y年m月d日').$weekDisp;
$dispData['disp_first']  = $dispData['disp_report'];
$dispData['staff_name']  = NULL;
$dispData['create_day']  = NULL;
$dispData['create_time'] = NULL;
$dispData['create_name'] = NULL;
$dispData['update_day']  = NULL;
$dispData['update_time'] = NULL;
$dispData['update_name'] = NULL;
$dispData['staff_cd']    = NULL;

$selHour = ['','00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
$selMinutes = ['','00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55'];

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// KEY
$keyId = filter_input(INPUT_GET, 'id');

// 利用者ID
$userId = filter_input(INPUT_GET, 'user');
if (!$userId) {
    $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : NULL;
}

// 複製時のパラメータ
$copy = filter_input(INPUT_GET, 'copy');

/*-- 更新用パラメータ ---------------------------------------*/

// 更新ボタン
$btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

// 複製ボタン
$btnCopy = h(filter_input(INPUT_POST, 'btnCopy'));

// 削除ボタン
$btnDel = h(filter_input(INPUT_POST, 'btnDel'));

// 削除ボタン(訪問看護-施設)
$btnDelFcl = h(filter_input(INPUT_POST, 'btnDelFcl'));

// 削除ボタン(訪問看護-家族)
$btnDelFml = h(filter_input(INPUT_POST, 'btnDelFml'));

// 更新配列(訪問看護)
$upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

// 更新配列(訪問看護-家族)
$upFml = filter_input(INPUT_POST, 'upFml', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upFml = $upFml ? $upFml : array();

// 更新配列(訪問看護-施設)
$upFcl1 = filter_input(INPUT_POST, 'upFcl1', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upFcl1 = $upFcl1 ? $upFcl1 : array();

// その他
$upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();

// 更新配列(時刻)
$upTime = filter_input(INPUT_POST, 'upTime', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upTime = $upTime ? $upTime : array();

// サマリーボタン
$btnExcel = h(filter_input(INPUT_POST, 'btnExcel'));

/*-- その他パラメータ ---------------------------------------*/

// 戻るボタン
$btnReturn = h(filter_input(INPUT_POST, 'btnReturn'));

/* =================================================== 
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ----------------------------------------*/

// 更新配列
if (($btnEntry || $btnCopy || $btnDelFcl || $btnDelFml) && $upAry){
    
    // 利用者
    $userId = $upAry['common']['user_id'];
    
    // 対象KEY
    if ($keyId && $btnEntry){
        $upAry['common']['unique_id'] = $keyId;
    }
    
    // 作成日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['common']['report_day']){
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), $upAry['common']['report_day']);
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['common']['report_day'] = $tgtDayAry[0];
    }
    
    // 初回訪問日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['common']['first_day']){
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), $upAry['common']['first_day']);
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['common']['first_day'] = $tgtDayAry[0];
    }
    
    if(!empty($upTime['start_time_h']) 
        && !empty($upTime['start_time_m'])
        && !empty($upTime['end_time_h'])
        && !empty($upTime['end_time_m'])
        ){
        $upData['start_time']   = $upTime['start_time_h'].":".$upTime['start_time_m'];
        $upData['end_time']     = $upTime['end_time_h'].":".$upTime['end_time_m'];   
    }
    
    // 関係施設
    $upFcl2 = array();
    foreach ($upFcl1['contact'] as $seq => $planDay){
        if (!empty($upFcl1['contact'][$seq])){
            if (isset($upFcl1['unique_id'][$seq])){
                $upFcl2[$seq]['unique_id'] = $upFcl1['unique_id'][$seq];
            }
            $upFcl2[$seq]['contact']   = $upFcl1['contact'][$seq];
            $upFcl2[$seq]['person']    = $upFcl1['person'][$seq];
            $upFcl2[$seq]['remarks']   = $upFcl1['remarks'][$seq];
        }
    }

    // サービス利用状況
    if (!empty($upDummy['use_service'])){
        $upAry['common']['use_service'] = implode('^', $upDummy['use_service']);
    }
    
    // 医療機関名
    $upAry['common']['hospital'] = !empty($upDummy['hospital1']) ? $upDummy['hospital1'] : $upDummy['hospital2'];
    
    // 更新配列
    $careKb = $upAry['common']['care_kb'];
    if(!empty($upAry['common']['unique_id'])){
        $upData['unique_id'] = $upAry['common']['unique_id'];
    }
    $upData['user_id']          = $upAry['common']['user_id'];
    $upData['care_kb']          = $upAry['common']['care_kb'];
    $upData['report_day']       = $upAry['common']['report_day'];
    $upData['staff_id']         = $upAry['common']['staff_id'];
    $upData['visit_job']        = $upAry['common']['visit_job'];
    $upData['care_rank']        = $upAry['common']['care_rank'];
    $upData['first_day']        = $upAry['common']['first_day'];
//    $upData['start_time']       = $upAry['common']['start_time'];
//    $upData['end_time']         = isset($upAry['common']['end_time']) ? $upAry['common']['end_time'] : NULL;
    $upData['main_sickness']    = $upAry[$careKb]['sickness1'];
    $upData['sickness1']        = $upAry[$careKb]['sickness1'];
    $upData['sickness2']        = $upAry[$careKb]['sickness2'];
    $upData['sickness3']        = $upAry[$careKb]['sickness3'];
    $upData['sickness4']        = $upAry[$careKb]['sickness4'];
    $upData['sickness5']        = $upAry[$careKb]['sickness5'];
    $upData['sickness6']        = $upAry[$careKb]['sickness6'];
    $upData['sickness7']        = $upAry[$careKb]['sickness7'];
    $upData['sickness8']        = $upAry[$careKb]['sickness8'];
    $upData['sickness9']        = $upAry[$careKb]['sickness9'];
    $upData['sickness10']       = $upAry[$careKb]['sickness10'];
    $upData['medical_record']   = $upAry[$careKb]['medical_record'];
    $upData['past_history']     = $upAry[$careKb]['past_history'];
    $upData['treatment']        = $upAry[$careKb]['treatment'];
    $upData['care']             = $upAry[$careKb]['care'];
    $upData['life']             = $upAry[$careKb]['life'];
    $upData['main_caregiver']   = $upAry[$careKb]['main_caregiver'];
    $upData['living']           = $upAry[$careKb]['living'];
    $upData['purpose']          = $upAry[$careKb]['purpose'];
    $upData['adl1']             = isset($upAry[$careKb]['adl1']) ? $upAry[$careKb]['adl1'] : NULL;
    $upData['adl2']             = isset($upAry[$careKb]['adl2']) ? $upAry[$careKb]['adl2'] : NULL;
    $upData['adl3']             = isset($upAry[$careKb]['adl3']) ? $upAry[$careKb]['adl3'] : NULL;
    $upData['adl4']             = isset($upAry[$careKb]['adl4']) ? $upAry[$careKb]['adl4'] : NULL;
    $upData['adl5']             = isset($upAry[$careKb]['adl5']) ? $upAry[$careKb]['adl5'] : NULL;
    $upData['adl6']             = isset($upAry[$careKb]['adl6']) ? $upAry[$careKb]['adl6'] : NULL;
    $upData['adl7']             = isset($upAry[$careKb]['adl7']) ? $upAry[$careKb]['adl7'] : NULL;
    $upData['adl8']             = isset($upAry[$careKb]['adl8']) ? $upAry[$careKb]['adl8'] : NULL;
    $upData['adl9']             = isset($upAry[$careKb]['adl9']) ? $upAry[$careKb]['adl9'] : NULL;
    $upData['adl10']            = isset($upAry[$careKb]['adl10']) ? $upAry[$careKb]['adl10'] : NULL;
    $upData['notices']          = isset($upAry[$careKb]['notices']) ? $upAry[$careKb]['notices'] : NULL;
    $upData['remarks']          = isset($upAry[$careKb]['remarks']) ? $upAry[$careKb]['remarks'] : NULL;
    $upData['handicap_opinion'] = $upAry[$careKb]['handicap_opinion'];
    $upData['handicap_rank']    = $upAry[$careKb]['handicap_rank'];
    $upData['handicap_comment'] = $upAry[$careKb]['handicap_comment'];
    $upData['dementia_opinion'] = $upAry[$careKb]['dementia_opinion'];
    $upData['dementia_rank']    = $upAry[$careKb]['dementia_rank'];
    $upData['dementia_comment'] = $upAry[$careKb]['dementia_comment'];
    $upData['hospital']         = $upAry['common']['hospital'];
    $upData['doctor']           = $upAry['common']['doctor'];
    $upData['address1']         = $upAry['common']['address1'];
    $upData['tel1']             = $upAry['common']['tel1'];
    $upData['tel2']             = $upAry['common']['tel2'];
    $upData['fax1']             = $upAry['common']['fax1'];
    $upData['office']           = $upAry['common']['office'];
    $upData['person']           = $upAry['common']['person'];
    $upData['address2']         = $upAry['common']['address2'];
    $upData['tel3']             = $upAry['common']['tel3'];
    $upData['fax2']             = $upAry['common']['fax2'];
    $upData['use_service']      = $upAry['common']['use_service'];
    $upData['status']           = $upAry['common']['status'];
}

// 複製処理
if ($btnCopy){
    
    // 作成日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['common']['report_day']){
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), $upAry['common']['report_day']);
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['common']['report_day'] = $tgtDayAry[0];
    }
    
    // 初回訪問日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['common']['first_day']){
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), $upAry['common']['first_day']);
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['common']['first_day'] = $tgtDayAry[0];
    }
    
    // 関係施設
    $upFcl2 = array();
    foreach ($upFcl1['contact'] as $seq => $planDay){
        if (!empty($upFcl1['contact'][$seq])){
            if (isset($upFcl1['unique_id'][$seq])){
                $upFcl2[$seq]['unique_id'] = $upFcl1['unique_id'][$seq];
            }
            $upFcl2[$seq]['contact']   = $upFcl1['contact'][$seq];
            $upFcl2[$seq]['person']    = $upFcl1['person'][$seq];
            $upFcl2[$seq]['remarks']   = $upFcl1['remarks'][$seq];
        }
    }

    // サービス利用状況
    if (!empty($upDummy['use_service'])){
        $upAry['common']['use_service'] = implode('^', $upDummy['use_service']);
    }
    
    // 医療機関名
    $upAry['common']['hospital'] = !empty($upDummy['hospital1']) ? $upDummy['hospital1'] : $upDummy['hospital2'];
  
    // セッションに入力途中の情報を格納
    $_SESSION['input'] = array();
    $_SESSION['input']['upAry']   = $upAry;
    $_SESSION['input']['upDummy'] = $upDummy;
    $_SESSION['input']['upFcl']   = $upFcl2;
    
    // 画面遷移
    $nextPage = '/report/visit1/index.php?copy=true&user='.$userId;
    header("Location:". $nextPage);
    exit();
}

/* -- 削除用配列作成 ----------------------------------------*/

// 削除配列
if ($btnDel) {
    $upData['unique_id'] = $btnDel;
    $upData['delete_flg'] = '1';
}

if ($btnDelFcl) {
    $seq = count($upFcl2) + 1;
    $upFcl2[$seq]['unique_id'] = $btnDelFcl;
    $upFcl2[$seq]['delete_flg'] = '1';
}

if ($btnDelFml) {
    $seq = count($upFml) + 1;
    $upFml[$seq]['unique_id'] = $btnDelFml;
    $upFml[$seq]['delete_flg'] = '1';
}

/* =================================================== 
 * イベント本処理(データ登録)
 * ===================================================
 */

// 入力チェック
if (($btnEntry || $btnCopy || $btnDelFcl || $btnDelFml) && $upData){
  
//    // 名称
//    if (empty($upData['name'])){
//        $notice[] = '名称の指定がありません';
//    }
//    // セッションへ格納
//    if ($notice){
//        $_SESSION['notice']['error'] = $notice;
//        $btnEntry = NULL;
//    }
}

// 更新処理
if (($btnEntry || $btnDelFcl || $btnDelFml) && $upData){
    
    // DBへ格納
    $res = upsert($loginUser, $table1, $upData);
    if (isset($res['err'])){
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }

    // 親コード特定
    $keyId = $res;

    // ログテーブルに登録する
    setEntryLog($upData);

    // 家族
    if (!empty($upFml)){
        foreach ($upFml as $key => $val){
            $val['visit1_id'] = $keyId;
            $upFml[$key] = $val;
        }
        $res = multiUpsert($loginUser, $table2, $upFml);
        if (isset($res['err'])){
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        
        // ログテーブルに登録する
        setMultiEntryLog($upFml);
    }
    
    // 施設
    if (!empty($upFcl2)){
        foreach ($upFcl2 as $key => $val){
            $val['visit1_id'] = $keyId;
            $upFcl2[$key] = $val;
        }
        $res = multiUpsert($loginUser, $table3, $upFcl2);
        if (isset($res['err'])){
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upFcl2);
    }
    
    // 画面遷移
    $_SESSION['user'] = $userId;
    $nextPage = $server['scriptName'].'?id='.$keyId.'&user='.$userId;
    header("Location:". $nextPage);
    exit();
}

// データ削除
if ($btnDel && $upData) {
    // テーブルを更新
    $res = upsert($loginUser, $table1, $upData);
    if (isset($res['err'])) {
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }
    
    // ログテーブルに登録する
    setEntryLog($upData);

    // 自画面へ遷移
    $_SESSION['user'] = NULL;
    unset($_SESSION['user']);
    $nextPage = $server['scriptName'];
    header("Location:". $nextPage);
    exit();
}

// 戻るボタン
if ($btnReturn){
    $nextPage = '/report/visit1_list/index.php';
    header("Location:". $nextPage);
    exit();    
}

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- データ取得 --------------------------------------------*/

/* -- 汎用マスタ ---------------------------*/
$gnrList  = getCode('訪問看護記録Ⅰ_訪問看護');
$gnrList2 = getCode('訪問看護記録Ⅰ_精神科訪問看護');

/* -- 利用者マスタ -------------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,last_name,first_name,other_id';
$temp = select('mst_user',$target,$where);
foreach ($temp as $val){
    $tgtId      = $val['unique_id'];
    $lastName   = $val['last_name'];
    $firstName  = $val['first_name'];
    $val['name'] = $lastName.' '.$firstName;
    $userList[$tgtId] = $val;
}
if ($userId && isset($userList[$userId])){
    $dispData['other_id']  = $userList[$userId]['other_id'];
    $dispData['user_name'] = $userList[$userId]['name'];
}

/* -- スタッフマスタ -----------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,last_name,first_name';
$temp = select('mst_staff','*',$where);
foreach ($temp as $val){
    $tgtId       = $val['unique_id'];
    $val['name'] = $val['last_name'].' '.$val['first_name'];
    $staffList[$tgtId] = $val;
}

/* -- 訪問介護記録Ⅰ ------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id']  = $keyId;
    $temp = select($table1, '*', $where);
    
    if (isset($temp[0])){
        
        // テーブル値
        $tgtData = $temp[0];
        
        // スタッフ名称、スタッフ外部コード
        if ($tgtData['staff_id']){
            $stfId = $tgtData['staff_id'];
            $tgtData['staff_name'] = isset($staffList[$stfId])
                    ? $staffList[$stfId]['name']
                    : NULL;
            
            $tgtData['staff_cd'] = isset($staffList[$stfId]['staff_id'])
                    ? $staffList[$stfId]['staff_id']
                    : NULL;
        }
        
        // 初回登録
        $tgtDate = $tgtData['create_date'];  
        $tgtData['create_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['create_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['create_user'];
        $tgtData['create_name'] = isset($staffList[$tgtUser]['name'])
                ? $staffList[$tgtUser]['name']
                : NULL;

        // 更新情報
        $tgtDate = $tgtData['update_date'];
        $tgtData['update_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['update_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['update_user'];
        $tgtData['update_name'] = isset($staffList[$tgtUser]['name'])
                ? $staffList[$tgtUser]['name']
                : NULL;
        
        $tgtData['start_time'] = formatDateTime($tgtData['start_time'], 'H:i');
        $tgtData['end_time']   = formatDateTime($tgtData['end_time'], 'H:i');
        
        // 格納
        $dispData = array_merge($dispData,$tgtData);
    }
}
/* -- 家族情報 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['visit1_id']  = $keyId;
    $target = '*';
    $temp = select($table2,$target,$where);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];
        $dispFml[$tgtId] = $val;
    }
}
/* -- 施設情報 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['visit1_id']  = $keyId;
    $target = '*';
    $temp = select($table3,$target,$where);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];
        $dispFcl[$tgtId] = $val;
    }
}
/* -- その他 --------------------------------------------*/


// 複製ボタン押下時の表示情報
if (!$keyId && $copy){
//    $dispData = array();
//    $dispData = $_SESSION['input']['upAry'];
    $dispData = array_merge($dispData, $_SESSION['input']['upAry']);
    $dispData = array_merge($dispData, $_SESSION['input']['upDummy']);
    $dispFcl  = array_merge($dispFcl , $_SESSION['input']['upFcl']);
}

// エクセル帳票出力処理
if ($btnExcel && $dispData){
   $key = $dispData['care_kb'] === '訪問看護' ? '018' : '019';
   $search = array();
   $search['unique_id'] = $dispData['unique_id'];
   printExcel($key, $search);
}

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
