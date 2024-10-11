<?php
//=====================================================================
// 経過記録
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
$otherWindowURL = array();

// 対象テーブル
$table1 = 'doc_progress';

// 初期値
$dispData = initTable($table1);
$dispData['other_id']    = NULL;
$dispData['user_name']   = NULL;
$week = formatDateTime(NOW, 'w');
$weekDisp = '('.$weekAry[$week].')';
$dispData['disp_report'] = formatDateTime(NOW, 'Y年m月d日').$weekDisp;
$dispData['disp_first']  = $dispData['disp_report'];
$dispData['staff_name']  = NULL;
$dispData['staff_cd']    = NULL;
$dispData['create_day']  = NULL;
$dispData['create_time'] = NULL;
$dispData['create_name'] = NULL;
$dispData['update_day']  = NULL;
$dispData['update_time'] = NULL;
$dispData['update_name'] = NULL;
$dispData['target_time'] = NULL;

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// KEY
$keyId = filter_input(INPUT_GET, 'id');

// 印刷フラグ
$prt = filter_input(INPUT_GET, 'prt');

// 利用者ID
$userId = filter_input(INPUT_GET, 'user');
if (!$userId) {
    $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : NULL;
}

/*-- 更新用パラメータ ---------------------------------------*/

// 更新ボタン
$btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

// 削除ボタン
$btnDel = h(filter_input(INPUT_POST, 'btnDel'));

// 更新配列
$upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

// その他
$upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();

/*-- その他パラメータ ---------------------------------------*/

// 戻るボタン
$btnReturn = h(filter_input(INPUT_POST, 'btnReturn'));

// 印刷ボタン
$btnPrint = h(filter_input(INPUT_POST, 'btnPrint'));

/* =================================================== 
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 入力チェック ------------------------------------------*/
if ($btnEntry && $upAry){
    
    // NGフラグ
    $dat = array();
    
    // 利用者ID
    if (empty($upAry['user_id'])){
        $dat[] = '利用者が入力されていません';
    }
    // 記入者ID
    if (empty($upAry['staff_id'])){
        $dat[] = '記入者が入力されていません';
    }
    // 記入日
    if (empty($upAry['record_day'])){
        $dat[] = '記入日が入力されていません';
    }
    // 発生日
    if (empty($upDummy['target_date'])){
        $dat[] = '発生日が入力されていません';
    }
    // 時刻
    if (empty($upDummy['target_time'])){
        $dat[] = '時刻が入力されていません';
    }
    // 件名
    if (empty($upAry['title'])){
        $dat[] = '件名が入力されていません';
    }
    // 状況・課題
    if (empty($upAry['problem'])){
        $dat[] = '状況・課題が入力されていません';
    }
    // フラグ判定
    if ($dat){
        $_SESSION['notice']['error'] = $dat;
        $btnEntry = NULL;
    }
}
/* -- 更新用配列作成 ----------------------------------------*/

// 更新配列
if ($btnEntry && $upAry){
    
    // 利用者
    $userId = $upAry['user_id'];
    
    // 対象KEY
    if ($keyId){
        $upAry['unique_id'] = $keyId;
    }
    
    // 作成日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['record_day']){
        $tgtDay = str_replace(array('年','月 ','日'), array('-','-',''), $upAry['record_day']);
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['record_day'] = $tgtDayAry[0];
    }
    
    // 発生日時
    if ($upDummy['target_date'] && $upDummy['target_time']){
        $tgtDay = str_replace(array('年','月 ','日'), array('-','-',''), $upDummy['target_date']);
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['target_date'] = $tgtDayAry[0].' '.$upDummy['target_time'];
    }
    
    // 更新配列
    $upData = $upAry;
}

/* -- 削除用配列作成 ----------------------------------------*/

// 削除配列
if ($btnDel) {
    $upData['unique_id'] = $btnDel;
    $upData['delete_flg'] = '1';
}

/* =================================================== 
 * イベント本処理(データ登録)
 * ===================================================
 */

// 更新処理
if ($btnEntry && $upData){
    
    // DBへ格納
    $res = upsert($loginUser, $table1, $upData);
    if (isset($res['err'])){
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }
    
    $keyId = $res;
    
    // ログテーブルに登録する
    setEntryLog($upData);
    
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
    $nextPage = '/report/progress_list/index.php';
    header("Location:". $nextPage);
    exit();    
}

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- データ取得 --------------------------------------------*/

/* -- 汎用マスタ ---------------------------*/
$gnrList = getCode('経過記録');

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
    $val['name'] = $val['last_name'].''.$val['first_name'];
    $staffList[$tgtId] = $val;
}

/* -- 経過記録 ------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id']  = $keyId;
    $temp = select($table1, '*', $where);
    
    if (isset($temp[0])){
        
        // テーブル値
        $tgtData = $temp[0];
        
        // スタッフ名称、スタッフコード
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
        
        // 記入日時
        $tgtDate = $tgtData['record_day'];
        $tgtData['record_day']  = formatDateTime($tgtDate, 'Y-m-d');
        
        // 発生日時
        $tgtDate = $tgtData['target_date'];
        $tgtData['target_date'] = formatDateTime($tgtDate, 'Y-m-d');
        $tgtData['target_time'] = formatDateTime($tgtDate, 'H:i');
        
        // 格納
        $dispData = array_merge($dispData,$tgtData);
    }
}

/* -- その他 --------------------------------------------*/

// 帳票印刷
if ($btnPrint && $keyId){
    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&prt=true';
}

// 印刷処理
if ($prt){
    
    // 出力条件
    $search = array();
    $search['unique_id'] = $keyId;
    
    $res = printPDF('017', $search);
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
