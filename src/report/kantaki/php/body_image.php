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
  require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

  /*--変数定義-------------------------------------------------*/

  // 初期化
  $tgtData = array();
  $keyId = h(filter_input(INPUT_GET, 'id'));
  $userId = h(filter_input(INPUT_GET, 'user'));
  $btnEntry =  h(filter_input(INPUT_POST, 'btnEntry'));
  $btnReturn =  h(filter_input(INPUT_POST, 'btnReturn'));

  if ($btnReturn) {
    // 画面遷移
    $nextPage = "/report/kantaki/index.php" . "?id=" . $keyId.'&user='. $userId;
    header("Location:" . $nextPage);
    exit();
  }

  // 対象テーブル(メイン)
  $table = 'doc_kantaki';

  $loginUser = isset($_SESSION['login']) ? $_SESSION['login'] : array();
  $staffId = "";
  if (!isset($loginUser['unique_id'])) {
    $staffId = 'system';
    $loginUser = array();
    $loginUser['unique_id'] = 'system';
  }
  // 登録済み情報取得
  $temp = array();
  $where = array();
  $where['unique_id'] = $keyId;
  $temp = select($table, '*', $where);
  if ($temp) {
    $tgtData = $temp[0];
  }else{
    $tgtData['unique_id'] = '';
    $tgtData['image_json'] = '';
  }

/* =================================================== 
 * 例外処理
 * ===================================================
 */
} catch (Exception $e) {
  if ($execEnv === 'pro' || $execEnv === 'stg') {
    $_SESSION['err'] = !empty($err) ? $err : array();
    header("Location:" . ERROR_PAGE);
    exit();
  } else {
    debug($e);
    exit();
  }
}
