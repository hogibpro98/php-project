<?php
/* ===================================================
 * 利用者（予定）新規追加モーダル
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
$dispData = array();
$tgtData = array();
$userIds = array();
$userList = array();
$userInfo = array();
$upAry = array();
$uisList = array();
$svcInfo = array();
$svcMst = array();
$svcDtlMst = array();
$addMst = array();
$addInfo = array();
$unInsMst = array();
$unInsType = array();
$unInsInfo = array();
$tgtData['main'] = initTable('dat_user_plan');
$tgtData['main']['update_name'] = "";
$tgtData['main']['update_id'] = "";
$tgtData['main']['office_name'] = "";
$tgtData['add'] = array();
$tgtData['jippi'] = array();
$tgtData['service'] = array();
$tgtData['span'] = array();
$unInsMst['type'] = array();
$unInsMst['zei_type'] = array();
$unInsMst['subsidy'] = array();
$unInsType['type']     = array();
$unInsType['zei_type'] = array();
$unInsType['subsidy']  = array();

$selHour = ['','00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
$selMinutes = ['','00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55'];

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

// 予定ID
$planId = filter_input(INPUT_GET, 'id');

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

// 加算マスタ
$where = array();
$where['delete_flg'] = 0;
$temp = select('mst_add', '*', $where);
foreach ($temp as $val) {
    $type = $val['type'];
    $tgtId = $val['unique_id'];
    $code  = $val['code'];
    $addInfo[$code] = $val['name'];
    if (!$val['span_flg'] && !$val['office_flg']) {
        $addMst[$type][$tgtId] = $val['name'];
    }
}

// サービスマスタ
$where = array();
$where['delete_flg'] = 0;
$temp = select('mst_service', '*', $where);
foreach ($temp as $val) {

    // 内容、名称
    $type = $val['type'];
    $tgtId = $val['unique_id'];
    $code = $val['code'];

    // 格納
    $dat = array();
    $dat['code'] = $val['code'];
    $dat['name'] = $val['name'];
    $svcMst[$type][$tgtId] = $dat;
    $svcInfo[$tgtId] = $dat;
}

// サービス詳細リスト取得
$where = array();
$where['delete_flg'] = 0;
$temp = select('mst_service_detail', '*', $where);
foreach ($temp as $val) {
    $type = $val['type'];
    $tgtId = $val['unique_id'];
    $svcDtlMst[$type][$tgtId] = $val;
}

// 事業所リスト取得
$offices = array();
$ofcList = getOfficeList($placeId);
foreach($ofcList as $ofcId => $dummy) {
    $offices[] = $ofcId;
}

// コードマスタ取得
$codeList = getCode();

// ユーザー情報取得
$temp = getUserList($placeId);
foreach ($temp as $val) {
    $tgtId = $val['unique_id'];
    $userIds[] = $tgtId;
    $userList[$tgtId] = $val;
}

// 保険外マスタ
$uisList = array();
$where = array();
$where['delete_flg'] = 0;
$where['link_office'] = $offices;
$temp = select('mst_uninsure', '*', $where);
foreach ($temp as $val) {
    $type = $val['type'];
    $tgtId = $val['unique_id'];
    $zeiType = $val['zei_type'];
    $subsidy = $val['subsidy'];
    $unInsType['type'][$type] = true;
    $unInsType['zei_type'][$zeiType] = true;
    $unInsType['subsidy'][$subsidy] = true;
    $uisList[$type][$tgtId] = $val;
}

/* -- 更新用配列作成 ---------------------------------------- */

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */

/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- データ取得 -------------------------------------------- */

/* -- 利用者予定(親) ----------------------------- */
$where = array();
$where['delete_flg'] = 0;
$where['unique_id'] = $planId;
$temp = select('dat_user_plan', '*', $where);
foreach ($temp as $val) {

    // 開始・終了時刻
    $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
    $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

    // 更新者名、事業所名
    $val['update_name'] = getStaffName($val['update_user']);
    $val['office_name'] = getOfficeName($val['office_id'], null, 'master');

    // 基本サービス名称
    $svcId = $val['service_id'] ? $val['service_id'] : 'dummy';
    $val['base_service'] = isset($svcInfo[$svcId])
    ? $svcInfo[$svcId]['name'] . '(' . $svcInfo[$svcId]['code'] . ')'
    : '';

    // 格納
    $tgtData['main'] = $val;
}

// ユーザ情報取得
$userInfo = getUserInfo($tgtData['main']['user_id']);

/* -- その他計画関連 ------------------------------ */
if (!empty($tgtData)) {

    /* -- 予定情報 ---------------------------- */

    // 予定（加減算）
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_plan_id'] = $planId;
    $temp = select('dat_user_plan_add', '*', $where);
    foreach ($temp as $val) {

        // 計画情報、加減算ID
        $tgtPlan = $tgtData['main'];
        $planAddId = $val['unique_id'];
        $val['start_day'] = $val['start_day'] === '0000-00-00' ? null : $val['start_day'];
        $val['end_day'] = $val['end_day'] === '0000-00-00' ? null : $val['end_day'];

        // 格納
        $tgtData['add'][$planAddId] = $val;
    }

    // 予定（実費）
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_plan_id'] = $planId;
    $temp = select('dat_user_plan_jippi', '*', $where);
    foreach ($temp as $val) {

        // 計画情報、実費ID
        $tgtPlan = $tgtData['main'];
        $planJpId = $val['unique_id'];

        // 格納
        $tgtData['jippi'][$planJpId] = $val;
    }

    // 予定（サービス詳細）
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_plan_id'] = $planId;
    $temp = select('dat_user_plan_service', '*', $where);
    foreach ($temp as $val) {

        // 計画情報、開始・終了時刻、サービス詳細ID
        $tgtPlan = $tgtData['main'];
        $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
        $val['end_time'] = formatDateTime($val['end_time'], 'H:i');
        $planSvcId = $val['unique_id'];

        // 格納
        $tgtData['service'][$planSvcId] = $val;
    }
}

// 事業所加算
foreach ($ofcList as $val) {

    // 初期化
    $dat = array();

    // 開始日、終了日
    $dat['start_day'] = @$stDay;
    $dat['end_day']   = @$edDay;

    // 入力があれば格納
    if ($val['add1_1_1']) {
        $dat['name'] = isset($addInfo[$val['add1_1_1']])
            ? $addInfo[$val['add1_1_1']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add1_1_2']) {
        $dat['name'] = isset($addInfo[$val['add1_1_2']])
            ? $addInfo[$val['add1_1_2']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add1_1_3']) {
        $dat['name'] = isset($addInfo[$val['add1_1_3']])
            ? $addInfo[$val['add1_1_3']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add1_1_4']) {
        $dat['name'] = isset($addInfo[$val['add1_1_4']])
            ? $addInfo[$val['add1_1_4']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_1_1']) {
        $dat['name'] = isset($addInfo[$val['add2_1_1']])
            ? $addInfo[$val['add2_1_1']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_1_2']) {
        $dat['name'] = isset($addInfo[$val['add2_1_2']])
            ? $addInfo[$val['add2_1_2']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_1_3']) {
        $dat['name'] = isset($addInfo[$val['add2_1_3']])
            ? $addInfo[$val['add2_1_3']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_1_4']) {
        $dat['name'] = isset($addInfo[$val['add2_1_4']])
            ? $addInfo[$val['add2_1_4']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_2_1']) {
        $dat['name'] = isset($addInfo[$val['add2_2_1']])
            ? $addInfo[$val['add2_2_1']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_2_2']) {
        $dat['name'] = isset($addInfo[$val['add2_2_2']])
            ? $addInfo[$val['add2_2_2']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_2_3']) {
        $dat['name'] = isset($addInfo[$val['add2_2_3']])
            ? $addInfo[$val['add2_2_3']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_2_4']) {
        $dat['name'] = isset($addInfo[$val['add2_2_4']])
            ? $addInfo[$val['add2_2_4']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_3_1']) {
        $dat['name'] = isset($addInfo[$val['add2_3_1']])
            ? $addInfo[$val['add2_3_1']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_3_2']) {
        $dat['name'] = isset($addInfo[$val['add2_3_2']])
            ? $addInfo[$val['add2_3_2']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_3_3']) {
        $dat['name'] = isset($addInfo[$val['add2_3_3']])
            ? $addInfo[$val['add2_3_3']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
    if ($val['add2_3_4']) {
        $dat['name'] = isset($addInfo[$val['add2_3_4']])
            ? $addInfo[$val['add2_3_4']]
            : null;
        if ($dat['name']) {
            $tgtData['span'][] = $dat;
        }
    }
}

// 期間指定加算
$where = array();
$where['delete_flg'] = 0;
$where['user_record_id'] = 'urec00000002';
$temp = select('dat_user_record_add', '*', $where);
foreach ($temp as $val) {
    $dat = array();
    $dat['start_day'] = $val['start_day'];
    $dat['end_day']   = $val['end_day'];
    $dat['name']      = $val['add_name'];
    if ($dat['name']) {
        $tgtData['span'][] = $dat;
    }
}

/* -- 画面表示データ格納 ---------------------------- */
$dispData = $tgtData;
$planId = !empty($planId) ? $planId : '0';
$jippi_i = 1;
$jpiPrefix = "upUserPlanJpi[" . $planId . "]";
?>
