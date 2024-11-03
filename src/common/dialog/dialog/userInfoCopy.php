<?php

/* ===================================================
 * スタッフ検索モーダル
 * ===================================================
 */

/* ===================================================
 * 初期処理
 * ===================================================
 */

/* --共通ファイル呼び出し------------------------------------- */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

/* --変数定義------------------------------------------------- */
// 初期化
$err = array();
$_SESSION['notice']['error'] = array();
$instList = array();
$notice   = array();

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/* -- 検索用パラメータ --------------------------------------- */

// 拠点ID
$placeId = filter_input(INPUT_GET, 'place');
if (!$placeId) {
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
}

// ユーザID
$userId = filter_input(INPUT_GET, 'user');
if (!$userId) {
    $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
}

/* -- 更新用パラメータ --------------------------------------- */

/* ===================================================
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ---------------------------------------- */

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */

/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- マスタ関連 -------------------------------------------- */
$where = array();
$userFmlList = array();
$userFmlList[0] = array();
$dispData = array();
$where['delete_flg'] = 0;
if ($userId) {
    $where['user_id'] = $userId;
}
$temp = select('mst_user_family', '*', $where);
foreach ($temp as $val) {
    $keyId = $val['unique_id'];
    $userFmlList[$keyId]['name'] = $val['name'];
    $userFmlList[$keyId]['relation_type'] = $val['relation_type'];
    $userFmlList[$keyId]['relation_memo'] = $val['relation_memo'];
    $userFmlList[$keyId]['business'] = $val['business'];
    $userFmlList[$keyId]['remarks'] = $val['remarks'];
    $userFmlList[$keyId]['unique_id'] = $val['unique_id'];

    $userFmlList[$keyId] = $val;

}

if ($userFmlList) {
    $where['delete_flg'] = 0;

}
/* -- データ送信 ----------------------------------------*/
$sendData = array();
$i = 0;
foreach ($userFmlList as $ary) {
    foreach ($ary as $key => $val) {
        $sendData['upFml[' . $i . '][' . $key . ']'] = $val;
    }
    $i++;
}

// -----------------------------------------------------------

if ($sendData) {
    echo sprintf("setMultiValue(%s);", jsonEncode($sendData));
}

// メッセージ送信
if ($notice) {
    echo sprintf("noticeModal(%s);", jsonEncode($notice));
}
exit;
