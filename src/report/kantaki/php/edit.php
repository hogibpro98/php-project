<?php
//=====================================================================
// 看多機記録
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
$userId   = NULL;
$upData = array();

// 対象テーブル
$table1 = 'doc_kantaki';

// 初期値
$dispData = initTable($table1);

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

$btnReturn = h(filter_input(INPUT_POST, 'btnReturn'));
if($btnReturn){
  // 画面遷移
  $nextPage = "/report/kantaki/index.php" . '?id=' . $keyId.'&user='.$userId;
  header("Location:" . $nextPage);
  exit();
}


/*-- 更新用パラメータ ---------------------------------------*/

// 更新ボタン
$btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

// 更新配列(看多機記録)
$upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

// 利用者ID
if (!empty($_SESSION['user_id'])){
    $userId = $_SESSION['user_id'];
}

/*-- その他パラメータ ---------------------------------------*/

/* =================================================== 
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ----------------------------------------*/

// 更新配列
if ($btnEntry && $upAry){

    // 利用者
    $userId = $upAry['user_id'];
    
    // 対象KEY
    if ($keyId){
        $upAry['unique_id'] = $keyId;
    }

    if($btnEntry){
        $upAry['unique_id'] = $keyId;
    }

    // 更新配列
    $upData = $upAry;
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
    
    // ログテーブルに登録する
    setEntryLog($upData);
  
    // 画面遷移
    $_SESSION['user'] = $userId;
    $nextPage = $server['scriptName'].'?id='.$keyId.'&user='.$userId;
    header("Location:". $nextPage);
    exit();
}

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- データ取得 --------------------------------------------*/

/* -- 看多機記録 ------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id']  = $keyId;
    $temp = select($table1, '*', $where);
    
    if (isset($temp[0])){
        
        // テーブル値
        $tgtData = $temp[0];
        
        // 初回登録
        $tgtDate = $tgtData['create_date'];  
        $tgtData['create_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['create_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['create_user'];
        $tgtData['create_name'] = getStaffName($tgtUser);

        // 更新情報
        $tgtDate = $tgtData['update_date'];
        $tgtData['update_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['update_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['update_user'];
        $tgtData['update_name'] = getStaffName($tgtUser);
        
        // 格納
        $dispData = array_merge($dispData,$tgtData);
    }
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
