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
$keyId     = h(filter_input(INPUT_POST, 'id'));
$type      = h(filter_input(INPUT_POST, 'type'));
$loginUser = $_SESSION['login'];

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */
/* -- 処理分け -------------------------------------------------- */

// 利用者(親)
if ($type == 'plan') {

    // 登録済み情報(親)
    $where = array();
    $where['unique_id'] = $keyId;
    $target = 'unique_id,protection_flg';
    $temp = select('dat_user_plan', $target, $where);
    $tgtData = $temp[0];

    // 更新フラグ(変更先)
    $tgtFlg = !empty($tgtData['protection_flg']) ? 0 : 1;

    // 更新配列(親)
    $upData = array();
    $upData['unique_id'] = $keyId;
    $upData['protection_flg'] = $tgtFlg;

    // 登録済み情報(子)
    $upSvc = array();
    $where['user_plan_id'] = $keyId;
    $target = 'unique_id,protection_flg';
    $temp = select($table, $target, $where);
    foreach ($temp as $val) {
        $dat = array();
        $dat['unique_id'] = $val['unique_id'];
        $dat['protection_flg'] = $tgtFlg;
        $upSvc[] = $dat;
    }

    // 更新処理(親)
    $res = upsert($loginUser, 'dat_user_plan', $upData);

    // ログテーブルに登録する
    setEntryLog($upData);

    // 更新処理(子)
    if ($upSvc) {
        $res = multiUpsert($loginUser, 'dat_user_plan_service', $upSvc);

        // ログテーブルに登録する
        setMultiEntryLog($upSvc);
    }
} else {

    // 対象テーブル
    $table = $type == 'service' ? 'dat_user_plan_service' : 'dat_staff_plan';

    // 登録済み情報
    $where = array();
    $where['unique_id'] = $keyId;
    $target = 'unique_id,protection_flg';
    $temp = select($table, $target, $where);
    $tgtData = $temp[0];

    // 更新配列
    $upData = array();
    $upData['unique_id'] = $keyId;
    $upData['protection_flg'] = !empty($tgtData['protection_flg']) ? 0 : 1;

    // 更新処理
    $res = upsert($loginUser, $table, $upData);

    // ログテーブルに登録する
    setEntryLog($upData);
}
// データ返却
echo '';
exit;
