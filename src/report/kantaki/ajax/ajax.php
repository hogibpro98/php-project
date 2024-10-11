<?php
//=====================================================================
// [ajax]体重履歴取得
//=====================================================================
try {
/* =================================================== 
 * 初期処理
 * ===================================================
 */
 
/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'].'/common/php/com_start.php');

/*--変数定義-------------------------------------------------*/
$_SESSION['notice']['error'] = array();
$cstName  = NULL;
$upDummy  = array();
$upAry    = array();
$sendData = array();
$wghtList = array();

$lastWght  = 0;
$lastWght2 = 0;
$lastWght3 = 0;

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

// 更新内容
$upAry = filter_input(INPUT_GET, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */
/* -- データ取得 ----------------------------------------*/

// 入力内容がある時に検索実行
if (!empty($upAry['user_id'])){
    // 過去体重取得
    $res = getPastWeight($upAry['user_id'], $upAry['service_day']);
    $upDummy['service_day'] = $upAry['service_day'];
    $upDummy['last_wght']   = $res[0];
    $upDummy['last_wght2']  = $res[2];
    $upDummy['last_wght3']  = $res[3];
}

/* -- データ送信 ----------------------------------------*/
$sendData = array();
foreach ($upDummy as $key => $val){
    $sendData['upDummy['.$key.']'] = $val;
}
// -----------------------------------------------------------

// データ書き換え
// setMultiValueを使用しない形で実装
if ($sendData){
    echo jsonEncode($sendData);
}

// メッセージ送信
if ($_SESSION['notice']['error']){
    echo sprintf("noticeModal(%s);",jsonEncode($_SESSION['notice']['error']));
}
exit();

/* =================================================== 
 * 例外処理
 * ===================================================
 */
} catch(Exception $e){
    debug($e);
    exit();
    $_SESSION['err'] = !empty($err) ? $err : array();
    header("Location:". ERROR_PAGE);
    exit();
}
