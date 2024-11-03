<?php
/* ===================================================
 * 編集モーダル
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
$unInsMst = array();
$unInsInfo = array();
$unInsType = array();
$tgtData['main'] = initTable('dat_week_schedule');
$tgtData['main']['update_name'] = "";
$tgtData['main']['update_id'] = "";
$tgtData['main']['office_name'] = "";
$tgtData['add'] = array();
$tgtData['jippi'] = array();
$tgtData['service'] = array();
$unInsMst['type'] = array();
$unInsMst['zei_type'] = array();
$unInsMst['subsidy'] = array();
$unInsType['type'] = array();
$unInsType['zei_type'] = array();
$unInsType['subsidy'] = array();

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

// スケジュールID
$schId = null;

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

// 利用者指定なし
if (empty($userId)) {
    $_SESSION['notice']['error'][] = '利用者を指定していません';
    $btnEntry = null;
}

/* -- マスタ関連 -------------------------------------------- */

// 加算マスタ
$where = array();
$where['delete_flg'] = 0;
$temp = select('mst_add', '*', $where);
foreach ($temp as $val) {
    $type = $val['type'];
    $tgtId = $val['unique_id'];
    $addMst[$type][$tgtId] = $val['name'];
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
foreach ($ofcList as $ofcId => $dummy) {
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
    $uisList[$type][$tgtId] = $val;  // data cot 2
}

// 保険外マスタにデータが無いときはコードマスタから積む
if (empty($unInsType)) {

    // 種類を積む
    $codeType = $codeList['保険外マスタ']['種類'];
    foreach ($codeType as $code => $val) {
        $unInsType['type'][$val] = true;
    }

    // 税区分を積む
    $zeiType = $codeList['保険外マスタ']['税区分'];
    foreach ($zeiType as $code => $val) {
        $unInsType['zei_type'][$val] = true;
    }

    // 控除対象を積む
    $subsidy = $codeList['保険外マスタ']['控除対象'];
    foreach ($subsidy as $code => $val) {
        $unInsType['subsidy'][$val] = true;
    }
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
$where['unique_id'] = $schId;
$temp = select('dat_week_schedule', '*', $where);
foreach ($temp as $val) {

    // 曜日、開始・終了時刻
    $val['week_name'] = $weekAry[$val['week']];
    $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
    $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

    // 更新者名、事業所名
    $val['update_name'] = getStaffName($val['update_user']);
    $val['office_name'] = getOfficeName($val['office_id'], null, 'master');

    // 基本サービス名称
    $svcId = $val['service_id'] ? $val['service_id'] : 'dummy';
    $val['base_service'] = isset($svcInfo[$svcId]) ? $svcInfo[$svcId]['name'] . '(' . $svcInfo[$svcId]['code'] . ')' : '';

    // 格納
    $tgtData['main'] = $val;
}

// ユーザ情報取得
$userInfo = getUserInfo($userId);

// 週数がNULLの場合は全周を対象とする
if (empty($tgtData['main']['week_num'])) {
    $tgtData['main']['week_num'] = "第1週^第2週^第3週^第4週^第5週^第6週";
}

/* -- その他計画関連 ------------------------------ */
if (!empty($tgtData)) {

    /* -- 予定情報 ---------------------------- */

    // 予定（加減算）
    $where = array();
    $where['delete_flg'] = 0;
    $where['schedule_id'] = $schId;
    $temp = select('dat_week_schedule_add', '*', $where);
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
    $where['schedule_id'] = $schId;
    $temp = select('dat_week_schedule_jippi', '*', $where);
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
    $where['schedule_id'] = $schId;
    $temp = select('dat_week_schedule_service', '*', $where);
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

/* -- 画面表示データ格納 ---------------------------- */
$dispData = $tgtData;

$mainData = $dispData['main'];
$addData = $dispData['add'];
$jippiData = $dispData['jippi'];
$serviceData = $dispData['service'];
$careJobList = $codeList['従業員マスタ']['請求用資格'];
$visitorNumList = $codeList['従業員予定実績']['同一建物に訪問した利用者'];
$areaAddList = $codeList['従業員予定実績']['特別地域加算有無'];
$insStationList = $codeList['従業員予定実績']['緊急訪問看護を行った指示先ステーション名区分'];
$mainPrefix = "upAry";
$addPrefix = "upAdd";
$jpiPrefix = "upJippi";
$svcPrefix = "upSvc";
$detail_i = 1;
$jippi_i = 1;
