<?php
/* ===================================================
 * 利用者予定実績（保護フラグ変更)
 * ===================================================
 */
/* ===================================================
 * 初期処理
 * ===================================================
 */

/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

/*--変数定義-------------------------------------------------*/

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// 対象データID、処理タイプ(user/service/staff)
$userId     = h(filter_input(INPUT_POST, 'user_id'));
$remarks    = h(filter_input(INPUT_POST, 'remarks'));
$loginUser = $_SESSION['login'];

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */

// 対象テーブル
$table = 'mst_user';

if ($userId) {
    // 更新配列
    $upData = array();
    $upData['unique_id'] = $userId;
    $upData['remarks']   = $remarks;
}

if ($upData) {
    // 更新処理
    $res = upsert($loginUser, $table, $upData);
}
// データ返却
echo '';
exit;
