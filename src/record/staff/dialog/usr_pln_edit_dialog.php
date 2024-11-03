<?php
/* ===================================================
 * 利用者（予定）編集モーダル
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
$unInsType = array();
$unInsInfo = array();
$tgtData['main'] = initTable('dat_user_plan');
$tgtData['main']['update_name'] = "";
$tgtData['main']['update_id'] = "";
$tgtData['main']['office_name'] = "";
$tgtData['add'] = array();
$tgtData['jippi'] = array();
$tgtData['service'] = array();
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
    $addMst[$type][$tgtId] = $val['name'];
}

// サービスマスタ
$where = array();
$where['delete_flg'] = 0;
$temp = select('mst_service', '*', $where);
foreach ($temp as $val) {

    // 内容、名称
    $type  = $val['type'];
    $tgtId = $val['unique_id'];
    $code  = $val['code'];

    // 格納
    $dat = array();
    $dat['code']           = $val['code'];
    $dat['name']           = $val['name'];
    $svcMst[$type][$tgtId] = $dat;
    $svcInfo[$tgtId]       = $dat;
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
    $val['base_service'] = isset($svcInfo[$svcId]) ? $svcInfo[$svcId]['name'] . '(' . $svcInfo[$svcId]['code'] . ')' : '';

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

/* -- 画面表示データ格納 ---------------------------- */
$dispData = $tgtData;
?>

<div class="dynamic_modal new_default sched_default displayed_part cancel_act" style="height:600px;width:950px!important;overflow: scroll!important;overflow-y: auto;overscroll-behavior-y: contain;top:60%;">
    <style>
        .list_delete2 {
            width: 32px;
            height: 32px;
            position: absolute;
            border-radius: 5px;
            background: #FFF;
            text-align: center;
            cursor: pointer;
            text-indent: -9999px;
            border: 1px solid #A7A7A7;
        }
        .datalist {
            width: 123px;
            appearance: none;
            cursor: pointer;
            border-radius: 4px;
            background-size: 8px 6px;
            border: 1px solid #E8E9EC;
            padding: 8px 25px 8px 15px;
            font-family: "Noto Sans JP";
            background: #FFF url(/common/image/arrow_down2.png) no-repeat 90% center;
        }

        /* 時間選択コンボ */
        .select_time{
            width:60px;
        }

        /* サービス詳細 */
        .svc_dtl_name{
            width:200px;
        }
    </style>
    <div class="modal_close close close_part">✕<span class="modal_close">閉じる</span></div>
    <div class="sched_tit">利用者スケジュール予定編集</div>
    <!-- 基本情報表示エリア:start -->
    <div class="s_detail">
        <?php $mainData = $dispData['main']; ?>
        <?php $addData = $dispData['add']; ?>
        <?php $jippiData = $dispData['jippi']; ?>
        <?php $serviceData = $dispData['service']; ?>
        <?php $planId = !empty($planId) ? $planId : '0'; ?>
        <?php $careJobList = $codeList['従業員マスタ']['請求用資格']; ?>
        <?php $visitorNumList = $codeList['従業員予定実績']['同一建物に訪問した利用者']; ?>
        <?php $areaAddList = $codeList['従業員予定実績']['特別地域加算有無']; ?>
        <?php $insStationList = $codeList['従業員予定実績']['緊急訪問看護を行った指示先ステーション名区分']; ?>
        <?php $mainPrefix = "upUserPlan[" . $planId . "]"; ?>
        <?php $addPrefix = "upUserPlanAdd[" . $planId . "]"; ?>
        <?php $jpiPrefix = "upUserPlanJpi[" . $planId . "]"; ?>
        <?php $svcPrefix = "upUserPlanSvc[" . $planId . "]"; ?>
        <?php $detail_i = 1; ?>
        <?php $jippi_i = 1; ?>
        <input type="hidden" name="<?= $mainPrefix ?>[unique_id]" value="<?= $planId ?>">
        <div class="box1">
            <p class="mid" style="width:200px;">日付/時刻</p>
            <p>
                <input type="date" name="<?= $mainPrefix ?>[use_day]" class="" value="<?= $mainData['use_day'] ?>">
                <select name="<?= $mainPrefix ?>[start_time_h]">
                    <?php foreach ($selHour as $val) : ?>
                        <?php $selected = strpos($mainData['start_time'], $val . ":") !== false ? ' selected' : ""; ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
                <small>：</small>
                <select name="<?= $mainPrefix ?>[start_time_m]">
                    <?php foreach ($selMinutes as $val) : ?>
                        <?php $selected = strpos($mainData['start_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
                <small>～</small>
                <select name="<?= $mainPrefix ?>[end_time_h]" class="select_time">
                    <?php foreach ($selHour as $val) : ?>
                        <?php $selected = strpos($mainData['end_time'], $val . ":") !== false ? ' selected' : ""; ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
                <small>：</small>
                <select name="<?= $mainPrefix ?>[end_time_m]" class="select_time">
                    <?php foreach ($selMinutes as $val) : ?>
                        <?php $selected = strpos($mainData['end_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
        </div>
        <div class="box1">
            <p class="mid" style="width:200px;">利用者</p>
            <p>
                <span class="user_res"><?= $userInfo['user_name'] ?></span>
                <input type="hidden" name="<?= $mainPrefix ?>[user_id]" value="<?= $userInfo['unique_id'] ?>">
                <span class="label_t">(利用者ID: <?= $userInfo['other_id'] ?>)</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid" style="width:200px;">実施事業所</p>
            <select id="office" class="staff" name="<?= $mainPrefix ?>[office_id]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($ofcList as $ofcId => $val) : ?>
                    <?php $select = $mainData['office_id'] === $ofcId ? ' selected' : null; ?>
                    <option value="<?= $ofcId ?>" <?= $select ?>><?= $val['name'] . "(ID:" . $val['office_no'] . ")" ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="box1">
            <p class="mid" style="width:200px;">サービス内容</p>
            <select id="selServiceName" class="staff" name="<?= $mainPrefix ?>[service_name]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($svcMst as $type => $dummy) : ?>
                    <?php $select = $type === $mainData['service_name'] ? ' selected' : null; ?>
                    <option value="<?= $type ?>" <?= $select ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
            <p class="noins_checkbox">
                <span><label>自費</label><input type="checkbox" name="<?= $mainPrefix ?>[jihi_flg]" id="expense" value="1" <?= $mainData['jihi_flg'] == 1 ? 'checked' : '' ?>></span>
            </p>
            <span class="noins_inputprice">
                <input type="text" name="<?= $mainPrefix ?>[jihi_price]" maxlength="11" value="<?= $mainData['jihi_price'] ?>" style="width:80px;"><label>円</label>
            </span>
        </div>
        <div class="box1">
            <p class="mid" style="width:200px;">基本サービス<br class="pc">コード</p>
            <input type="hidden" id="hidServiceId"  name="<?= $mainPrefix ?>[service_id]" value="<?= $mainData['service_id'] ?>">
            <input id="dtlstServiceId" name="<?= $mainPrefix ?>[base_service]" list="serviceList" style="width:320px" class="datalist" value="<?= !empty($mainData['base_service']) ? $mainData['base_service'] : '' ?>">
            <datalist class="staff" id="serviceList">
                <option value="">選択してください</option>
                <?php foreach ($svcMst as $type => $svcMst2) : ?>
                    <?php foreach ($svcMst2 as $tgtId => $val) : ?>
                        <?php $select = $mainData['service_id'] === $tgtId ? ' selected' : null; ?>
                        <option class="cngService" data-value="<?= $tgtId ?>" data-service_name="<?= $type ?>" <?= $select ?> value="<?= $val['name'] . '(' . $val['code'] . ')' ?>"> </option>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </datalist>
        </div>
        <div class="box1">
            <p class="mid" style="width:200px;">訪問職種</p>
            <select class="staff" name="<?= $mainPrefix ?>[care_job]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($careJobList as $type => $job) : ?>
                    <?php $select = $job === $mainData['care_job'] ? ' selected' : null; ?>
                    <option value="<?= $job ?>" <?= $select ?>><?= $job ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="box1">
            <p class="mid" style="width:200px;">同一建物に訪問した利用者</p>
            <select class="staff" name="<?= $mainPrefix ?>[visitor_num]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($visitorNumList as $type => $job) : ?>
                    <?php $select = $job === $mainData['visitor_num'] ? ' selected' : null; ?>
                    <option value="<?= $job ?>" <?= $select ?>><?= $job ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="box1">
            <p class="mid" style="width:200px;">特別地域加算有無</p>
            <select class="staff" name="<?= $mainPrefix ?>[area_add]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($areaAddList as $type => $job) : ?>
                    <?php $select = $job === $mainData['area_add'] ? ' selected' : null; ?>
                    <option value="<?= $job ?>" <?= $select ?>><?= $job ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="box1">
            <p class="mid" style="width:200px;">緊急自訪問看護を行った指示先<br/>ステーション名区分</p>
            <select class="staff" name="<?= $mainPrefix ?>[ins_station]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($insStationList as $type => $job) : ?>
                    <?php $select = $job === $mainData['ins_station'] ? ' selected' : null; ?>
                    <option value="<?= $job ?>" <?= $select ?>><?= $job ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php $qualList = $codeList['ルート管理']['利用者スケジュール登録_サービスコード絞り込み_資格']; ?>
        <?php $peopleList = $codeList['ルート管理']['利用者スケジュール登録_サービスコード絞り込み_人数']; ?>
        <?php $condition1List = $codeList['ルート管理']['利用者スケジュール登録_サービスコード絞り込み_条件1']; ?>
        <div class="box1">
            <p class="mid" style="width:200px;">絞り込み 資格</p>
            <select class="staff" name="<?= $mainPrefix ?>[qualification]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($qualList as $cdName) : ?>
                    <?php $select = $cdName === $mainData['qualification'] ? ' selected' : null; ?>
                    <option value="<?= $cdName ?>" <?= $select ?>><?= $cdName ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="box1">
            <p class="mid" style="width:200px;">絞り込み 人数</p>
            <select class="staff" name="<?= $mainPrefix ?>[no_people]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($peopleList as $cdName) : ?>
                    <?php $select = $cdName === $mainData['no_people'] ? ' selected' : null; ?>
                    <option value="<?= $cdName ?>" <?= $select ?>><?= $cdName ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="box1">
            <p class="mid" style="width:200px;">絞り込み 条件１</p>
            <select class="staff" name="<?= $mainPrefix ?>[condition1]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($condition1List as $cdName) : ?>
                    <?php $select = $cdName === $mainData['condition1'] ? ' selected' : null; ?>
                    <option value="<?= $cdName ?>" <?= $select ?>><?= $cdName ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <!-- 基本情報表示エリア:end -->
    <!-- 加減算表示エリア:start -->
    <div class="add_sub content_add">
        <p class="mid">加減算</p>
        <ol id="add_root">
            <?php if (empty($dispData['add'])) : ?>
                <li>
                    <input type="hidden" name="<?= $addPrefix ?>[0][user_id]" value="<?= $userInfo['unique_id'] ?>">
                    <input type="hidden" name="<?= $addPrefix ?>[0]schedule_id]" value="<?= $mainData['unique_id'] ?>">
                    <select name="<?= $addPrefix ?>[0][add_id]" class="addList">
                        <option value="">選択してください</option>
                        <?php foreach ($addMst as $type => $addMst2) : ?>
                            <?php foreach ($addMst2 as $tgtId => $val) : ?>
                                <option value="<?= $tgtId ?>" class="cngService" data-value="<?= $val ?>" data-service_name="<?= $type ?>"><?= $val ?></option>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" name="<?= $addPrefix ?>[0][start_day]" value="<?= isset($addData['start_day']) ? $addData['start_day'] : '' ?>">
                    <small>～</small>
                    <input type="date" name="<?= $addPrefix ?>[0][end_day]" value="<?= isset($addData['end_day']) ? $addData['end_day'] : '' ?>">
                    <p class="list_delete row_delete">Delete</p>
                </li>
            <?php else : ?>
                <?php foreach ($dispData['add'] as $addData) : ?>
                    <li>
                        <?php $addId = $addData['unique_id']; ?>
                        <input type="hidden" name="<?= $addPrefix ?>[<?= $addId ?>][unique_id]" value="<?= $addId ?>">
                        <input type="hidden" name="<?= $addPrefix ?>[<?= $addId ?>][user_plan_id]" value="<?= $planId ?>">
                        <select name="<?= $addPrefix ?>[<?= $addId ?>][add_id]" class="addList">
                            <option value="">選択してください</option>
                            <?php foreach ($addMst as $type => $addMst2) : ?>
                                <?php foreach ($addMst2 as $tgtId => $val) : ?>
                                    <?php $select = $addData['add_id'] === $tgtId ? ' selected' : ''; ?>
                                    <option value="<?= $tgtId ?>" class="cngService" data-value="<?= $val ?>" data-service_name="<?= $type ?>" <?= $select ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </select>
                        <input type="date" name="<?= $addPrefix ?>[<?= $addId ?>][start_day]" value="<?= isset($addData['start_day']) ? $addData['start_day'] : '' ?>">
                        <small>～</small>
                        <input type="date" name="<?= $addPrefix ?>[<?= $addId ?>][end_day]" value="<?= isset($addData['end_day']) ? $addData['end_day'] : '' ?>">
                        <p class="list_delete row_delete">Delete</p>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ol>
        <p class="btn_append_add add_btn add_sub_btn">+</p>
    </div>
    <!-- 加減算表示エリア:end -->
    <!-- 実費表示エリア:start -->
    <?php $zeiTypeList = $codeList['保険外マスタ']['税区分']; ?>
    <?php $subsidyList = $codeList['保険外マスタ']['控除対象']; ?>
    <div class="box1">
        <p class="mid">実費</p>
    </div>
    <div class="add_sub content_jippi" style="border-top: 0;padding-top: 0;">
        <table>
            <tr>
                <th class="type">種類</th>
                <th class="item">項目名称</th>
                <th class="price" style="width:85px">単価<br>最大7桁</th>
                <th class="tax">消費税<br>区分</th>
                <th class="sales_tax" style="width:85px;" ;>消費税率</th>
                <th class="d_cate">控除区分</th>
                <th></th>
                <th></th>
            </tr>
            <?php if (empty($dispData['jippi'])) : ?>
                <tr>
                <input type="hidden" name="<?= $jpiPrefix ?>[0][user_id]" value="<?= $userInfo['unique_id'] ?>">
                <input type="hidden" name="<?= $jpiPrefix ?>[0][schedule_id]" value="<?= $mainData['unique_id'] ?>">
                <td class="type">
                    <b class="sm">種類</b>
                    <select id="selJippiType<?= $jippi_i ?>" data-index="<?= $jippi_i ?>" name="<?= $jpiPrefix ?>[0][type]" class="uis_type selJippiType cngJippi">
                        <option value="">選択してください</option>
                        <?php foreach ($unInsType['type'] as $type => $dummy) : ?>
                            <option value="<?= $type ?>"><?= $type ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="item">
                    <b class="sm">項目名称</b>
                    <select id="jippiFieldName<?= $jippi_i ?>" data-index="<?= $jippi_i ?>" class="cngOffice uis_name cngJippi" name="<?= $jpiPrefix ?>[0][uninsure_id]">
                        <option value="">選択してください</option>
                        <?php foreach ($uisList as $type => $uisList2) : ?>
                            <?php foreach ($uisList2 as $uisId => $uisData) : ?>
                                <option class="cngJippiType<?= $jippi_i ?>" value="<?= $uisId ?>" 
                                        data-office_id="<?= $uisData['link_office'] ?>"
                                        data-type="<?= $uisData['type'] ?>"
                                        data-zei_type="<?= $uisData['zei_type'] ?>"
                                        data-subsidy="<?= $uisData['subsidy'] ?>"
                                        data-rate="<?= $uisData['rate'] ?>"
                                        data-price="<?= $uisData['price'] ?>"
                                        data-name="<?= $uisData['name'] ?>"
                                        data-id="<?= $uisId ?>"
                                        ><?= $uisData['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="price" style="width:60px;">
                    <b class="sm">単価最大7桁</b>
                    <input id="jippiPrice<?= $jippi_i ?>" type="text" class="validate[maxSize[7],onlyNumberSp] uis_price" name="<?= $jpiPrefix ?>[0][price]" value="<?= isset($jippiList['price']) ? $jippiList['price'] : '' ?>" style="width:85px">
                </td>
                <td class="tax">
                    <b class="sm">消費税<br>区分</b>
                    <select id="jippiZeiType<?= $jippi_i ?>" name="<?= $jpiPrefix ?>[0][zei_type]" class="uis_zei_type">
                        <?php foreach ($unInsType['zei_type'] as $zeiType => $dummy) : ?>
                            <option value="<?= $zeiType ?>"><?= $zeiType ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="sales_tax">
                    <b class="sm">消費税率</b>
                    <input id="jippiUisRate<?= $jippi_i ?>" type="text" class="validate[maxSize[2],onlyNumberSp] uis_rate" name="<?= $jpiPrefix ?>[0][rate]" value="<?= isset($jippiList['rate']) ? $jippiList['rate'] : '' ?>" style="width:60px;"><span>%</span>
                </td>
                <td class="d_cate">
                    <b class="sm">控除区分</b>
                    <select id="jippiSubsidy<?= $jippi_i ?>" name="<?= $jpiPrefix ?>[0][subsidy]" class="uis_subsidy">
                        <?php foreach ($unInsType['subsidy'] as $subsidy => $dummy) : ?>
                            <option value="<?= $subsidy ?>"><?= $subsidy ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <button type="button" class="row_delete" style="width:32px;height:32px;background:#FFFFFF;border-radius: 5px;border: 1px solid #A7A7A7;">
                        <img src="/common/image/icon_trash2.png" />
                    </button>
                </td>
                <td></td>
                </tr>
                <?php $jippi_i = $jippi_i + 1; ?>
            <?php else : ?>
                <?php foreach ($dispData['jippi'] as $jippiList) : ?>
                    <tr>
                        <?php $jippiId = $jippiList['unique_id']; ?>
                    <input type="hidden" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][unique_id]" value="<?= $jippiId ?>">
                    <input type="hidden" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][user_plan_id]" value="<?= $planId ?>">
                    <td class="type">
                        <b class="sm">種類</b>
                        <select id="selJippiType<?= $jippi_i ?>" data-index="<?= $jippi_i ?>" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][type]" class="uis_type selJippiType cngJippi">
                            <option value="">選択してください</option>
                            <?php foreach ($unInsType['type'] as $type => $dummy) : ?>
                                <?php $select = $jippiList['type'] === $type ? ' selected' : null; ?>
                                <option value="<?= $type ?>" <?= $select ?>><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="item">
                        <b class="sm">項目名称</b>
                        <select id="jippiFieldName<?= $jippi_i ?>" data-index="<?= $jippi_i ?>" class="cngOffice uis_name cngJippi" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][uninsure_id]">
                            <option value="">選択してください</option>
                            <?php foreach ($uisList as $type => $uisList2) : ?>
                                <?php foreach ($uisList2 as $uisId => $uisData) : ?>
                                    <?php $select = $jippiList['name'] === $uisData['name'] ? ' selected' : null; ?>
                                    <option class="cngJippiType<?= $jippi_i ?>" value="<?= $uisId ?>" 
                                            data-office_id="<?= $uisData['link_office'] ?>"
                                            data-type="<?= $uisData['type'] ?>"
                                            data-zei_type="<?= $uisData['zei_type'] ?>"
                                            data-subsidy="<?= $uisData['subsidy'] ?>"
                                            data-rate="<?= $uisData['rate'] ?>"
                                            data-price="<?= $uisData['price'] ?>"
                                            data-name="<?= $uisData['name'] ?>"
                                            data-id="<?= $uisId ?>"
                                            <?= $select ?>
                                            ><?= $uisData['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="price">
                        <b class="sm">単価最大7桁</b>
                        <input id="jippiPrice<?= $jippi_i ?>" type="text" class="validate[maxSize[7],onlyNumberSp] uis_price" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][price]" value="<?= $jippiList['price'] ?>" style="width:85px" maxlength="7" placeholder="半角数字7桁">
                    </td>
                    <td class="tax">
                        <b class="sm">消費税<br>区分</b>
                        <select id="jippiZeiType<?= $jippi_i ?>" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][zei_type]" class="uis_zeiType">
                            <?php foreach ($unInsType['zei_type'] as $zeiType => $dummy) : ?>
                                <?php $select = $jippiList['zei_type'] === $zeiType ? ' selected' : null; ?>
                                <option value="<?= $zeiType ?>" <?= $select ?>><?= $zeiType ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="sales_tax">
                        <b class="sm">消費税率</b>
                        <input id="jippiUisRate<?= $jippi_i ?>" type="text" class="validate[maxSize[2],onlyNumberSp] uis_rate" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][rate]" value="<?= isset($jippiList['rate']) ? $jippiList['rate'] : '' ?>" style="width:60px;" maxlength="2" placeholder="半角数字2桁"><span>%</span>
                    </td>
                    <td class="d_cate">
                        <b class="sm">控除区分</b>
                        <select id="jippiSubsidy<?= $jippi_i ?>" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][subsidy]" class="uis_subsidy">
                            <?php foreach ($unInsType['subsidy'] as $subsidy => $dummy) : ?>
                                <?php $select = $jippiList['subsidy'] === $subsidy ? ' selected' : null; ?>
                                <option value="<?= $subsidy ?>" <?= $select ?>><?= $subsidy ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="row_delete" style="width:32px;height:32px;background:#FFFFFF;border-radius: 5px;border: 1px solid #A7A7A7;">
                            <img src="/common/image/icon_trash2.png" />
                        </button>
                    </td>
                    <td></td>
                    </tr>
                    <?php $jippi_i = $jippi_i + 1; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <p class="btn_append_jippi add_btn">+</p>
    </div>
    <!-- 実費表示エリア:end -->
    <!-- サービス内容詳細表示エリア:start -->
    <div class="add_sub">
        <p class="mid" style="width:150px;">サービス内容詳細</p>
        <div class="service_list content_service">
            <ol id="dtl_root">
                <?php if (empty($serviceData)) : ?>
                    <li>
                        <?php $svcId = $svcData['unique_id']; ?>
                        <input type="hidden" name="<?= $svcPrefix ?>[<?= $svcId ?>][unique_id]" value="<?= $svcId ?>">
                        <input type="hidden" name="<?= $svcPrefix ?>[<?= $svcId ?>][user_plan_id]" value="<?= $planId ?>">
                        <select class="validate[required]" id="start_time_h_<?= $detail_i ?>" name="<?= $svcPrefix ?>[0][start_time_h]" style="width:60px;">
                            <?php foreach ($selHour as $val) : ?>
                                <option value="<?= $val ?>"><?= $val ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small>：</small>
                        <select class="validate[required]" id="start_time_m_<?= $detail_i ?>" name="<?= $svcPrefix ?>[0][start_time_m]" style="width:60px;">
                            <?php foreach ($selMinutes as $val) : ?>
                                <option value="<?= $val ?>"><?= $val ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small>～</small>
                        <select class="validate[required]" id="end_time_h_<?= $detail_i ?>" name="<?= $svcPrefix ?>[0][end_time_h]" style="width:60px;">
                            <?php foreach ($selHour as $val) : ?>
                                <option value="<?= $val ?>"><?= $val ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small>：</small>
                        <select class="validate[required, custom[notEqualTime]]" id="end_time_m_<?= $detail_i ?>" data-index="<?= $detail_i ?>" name="<?= $svcPrefix ?>[0][end_time_m]" style="width:60px;">
                            <?php foreach ($selMinutes as $val) : ?>
                                <option value="<?= $val ?>"><?= $val ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select class="cngOffice validate[required]" name="<?= $svcPrefix ?>[0][service_detail_id]" style="width:200px;">
                            <option value="">選択してください</option>
                            <?php foreach ($svcDtlMst as $type => $svcDtlMst2) : ?>
                                <?php foreach ($svcDtlMst2 as $tgtId => $val) : ?>
                                    <option class="cngService" value="<?= $tgtId ?>" data-value="<?= $val['name'] ?>" data-service_name="<?= $type ?>"><?= $val['name'] ?></option>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="<?= $svcPrefix ?>[0][name]" style="width:90px;" value="<?= isset($svcData['name']) ? $svcData['name'] : '' ?>">
                        <p class="list_delete row_delete">Delete</p>
                    </li>
                    <?php $detail_i = $detail_i + 1; ?>
                <?php else : ?>
                    <?php foreach ($serviceData as $svcData) : ?>
                        <li>
                            <?php $svcId = $svcData['unique_id']; ?>
                            <input type="hidden" name="<?= $svcPrefix ?>[<?= $svcId ?>][unique_id]" value="<?= $svcId ?>">
                            <input type="hidden" name="<?= $svcPrefix ?>[<?= $svcId ?>][user_plan_id]" value="<?= $planId ?>">
                            <select class="validate[required]" id="start_time_h_<?= $detail_i ?>" name="<?= $svcPrefix ?>[<?= $svcId ?>][start_time_h]" style="width:60px;">
                                <?php foreach ($selHour as $val) : ?>
                                    <?php $selected = strpos($svcData['start_time'], $val . ":") !== false ? ' selected' : ""; ?>
                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small>：</small>
                            <select class="validate[required]" id="start_time_m_<?= $detail_i ?>" name="<?= $svcPrefix ?>[<?= $svcId ?>][start_time_m]" style="width:60px;">
                                <?php foreach ($selMinutes as $val) : ?>
                                    <?php $selected = strpos($svcData['start_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small>～</small>
                            <select class="validate[required]" id="end_time_h_<?= $detail_i ?>" name="<?= $svcPrefix ?>[<?= $svcId ?>][end_time_h]" style="width:60px;">
                                <?php foreach ($selHour as $val) : ?>
                                    <?php $selected = strpos($svcData['end_time'], $val . ":") !== false ? ' selected' : ""; ?>
                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small>：</small>
                            <select class="validate[required, custom[notEqualTime]]" id="end_time_m_<?= $detail_i ?>" data-index="<?= $detail_i ?>" name="<?= $svcPrefix ?>[<?= $svcId ?>][end_time_m]" style="width:60px;">
                                <?php foreach ($selMinutes as $val) : ?>
                                    <?php $selected = strpos($svcData['end_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="cngOffice validate[required]" name="<?= $svcPrefix ?>[<?= $svcId ?>][service_detail_id]" style="width:200px;">
                                <option value="">選択してください</option>
                                <?php foreach ($svcDtlMst as $type => $svcDtlMst2) : ?>
                                    <?php foreach ($svcDtlMst2 as $tgtId => $val) : ?>
                                        <?php $select = $svcData['service_detail_id'] === $tgtId ? ' selected' : null; ?>
                                        <option class="cngService" value="<?= $tgtId ?>" data-value="<?= $val['name'] ?>" data-service_name="<?= $type ?>" <?= $select ?>><?= $val['name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="<?= $svcPrefix ?>[<?= $svcId ?>][name]" style="width:90px;" value="<?= isset($svcData['name']) ? $svcData['name'] : '' ?>">
                            <p class="list_delete row_delete">Delete</p>
                        </li>
                        <?php $detail_i = $detail_i + 1; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ol>
            <p class="btn_append_service add_btn">+</p>
        </div>
    </div>
    <!-- サービス内容詳細表示エリア:end-->
    <!-- 下部ボタンエリア:start-->
    <div class="s_constrols">
        <p><span class="modal_close btn cancel">キャンセル</span></p>
        <p>
            <?php $disabled = empty($planId) ? 'disabled' : ''; ?>
            <button type="submit" name="btnDelUserPlan" value="<?= $planId ?>" class="btn delete" style="width:60px;height:37px;font-size: 15px;">削除</button>
            <button type="submit" name="btnEditUserPlan" value="<?= $planId ?>" class="btn save" style="width:60px;height:37px;font-size: 15px;">保存</button>
        </p>
    </div>
    <!-- 下部ボタンエリア:end-->
    <div class="update">
        最終更新:
        <span class="time"><?= isset($mainData['update_time']) ? $mainData['update_time'] : '' ?></span>
        <span class="person"><?= isset($mainData['update_name']) ? $mainData['update_name'] : '' ?></span>
    </div>
    <script>
        var addNewCnt = 1;
        var jippiNewCnt = 1;
        var svcNewCnt = 1;
        window.addEventListener("load", function () {
            setValidate();
        });
        $(function () {
            var index = <?= $detail_i ?>;
            var jippi_i = <?= $jippi_i ?>;

            // ダイアログクローズ
            $(".modal_close").on("click", function () {
                // windowを閉じる
                $(".dynamic_modal").remove();
            });
            // 加減算新規行追加
            $(".btn_append_add").click(function () {
                addNewCnt++;
                var ol_wrap = $(this).closest(".content_add").find("ol");
                var newRow = '';
                newRow += '<li>';
                newRow += '  <select name="<?= $addPrefix ?>[' + addNewCnt + '][add_id]" class="addList">';
                newRow += ' <option value="">選択してください</option>';
<?php foreach ($addMst as $type => $addMst2) : ?>
    <?php foreach ($addMst2 as $tgtId => $val) : ?>
                        newRow += ' <option value="<?= $tgtId ?>" class="cngService" data-value="<?= $val ?>" data-service_name="<?= $type ?>"><?= $val ?></option>';
    <?php endforeach; ?>
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <input type="date" name="<?= $addPrefix ?>[' + addNewCnt + '][start_day]" value="">';
                newRow += '  <small>～</small>';
                newRow += '  <input type="date" name="<?= $addPrefix ?>[' + addNewCnt + '][end_day]" value="">';
                newRow += '  <p class="list_delete row_delete">Delete</p>';
                newRow += '</li>';
                $(newRow).appendTo(ol_wrap);
                //changeService();
            });
            // 加減算行削除
            $(".content_add").on('click', '.row_delete', function (event) {
                event.preventDefault();
                $(this).closest('li').remove();
                return false;
            });
            // 実費新規行追加
            $(".btn_append_jippi").click(function () {
                jippiNewCnt++;
                var ol_wrap = $(this).closest(".content_jippi").find("tbody");
                var newRow = '';
                newRow += '  <tr>';
                newRow += '    <td class="type">';
                newRow += '      <b class="sm">種類</b>';
                newRow += '      <select id="selJippiType' + jippi_i + '" data-index="' + jippi_i + '" class="cngOffice uis_type selJippiType cngJippi" name="<?= $jpiPrefix ?>[' + jippiNewCnt + '][type]">';
                newRow += '        <option value="">選択してください</option>';
<?php foreach ($unInsType['type'] as $type => $dummy) : ?>
                    newRow += '          <option value="<?= $type ?>"><?= $type ?></option>';
<?php endforeach; ?>
                newRow += '      </select>';
                newRow += '    </td>';
                newRow += '    <td class="item">';
                newRow += '      <b class="sm">項目名称</b>';
                newRow += '      <select id="jippiFieldName' + jippi_i + '" data-index="' + jippi_i + '" class="cngOffice uis_name cngJippi" name="<?= $jpiPrefix ?>[' + jippiNewCnt + '][uninsure_id]">';
                newRow += '        <option value="">選択してください</option>';
<?php foreach ($uisList as $type => $uisList2) : ?>
    <?php foreach ($uisList2 as $uisId => $uisData) : ?>
                        newRow += '                <option class="cngJippiType' + jippi_i + '" value="<?= $uisId ?>" ';
                        newRow += '                        data-office_id="<?= $uisData['link_office'] ?>"';
                        newRow += '                        data-type="<?= $uisData['type'] ?>"';
                        newRow += '                        data-zei_type="<?= $uisData['zei_type'] ?>"';
                        newRow += '                        data-subsidy="<?= $uisData['subsidy'] ?>"';
                        newRow += '                        data-rate="<?= $uisData['rate'] ?>"';
                        newRow += '                        data-price="<?= $uisData['price'] ?>"';
                        newRow += '                        data-name="<?= $uisData['name'] ?>"';
                        newRow += '                        data-id="<?= $uisId ?>"';
                        newRow += '                        ><?= $uisData['name'] ?>';
                        newRow += '                </option>';
    <?php endforeach; ?>
<?php endforeach; ?>
                newRow += '      </select>';
                newRow += '    </td>';
                newRow += '    <td class="price" style="width:60px;">';
                newRow += '      <b class="sm">単価最大7桁</b>';
                newRow += '      <input id="jippiPrice' + jippi_i + '" type="text" class="validate[maxSize[7],onlyNumberSp] uis_price" name="<?= $jpiPrefix ?>[' + jippiNewCnt + '][price]" value="<?= isset($jippiList['price']) ? $jippiList['price'] : '' ?>" style="width:85px" placeholder="半角数字7桁">';
                newRow += '    </td>';
                newRow += '    <td class="tax">';
                newRow += '      <b class="sm">消費税<br>区分</b>';
                newRow += '      <select id="jippiZeiType' + jippi_i + '" name="<?= $jpiPrefix ?>[' + jippiNewCnt + '][zei_type]" class="uis_zei_type">';
<?php foreach ($unInsType['zei_type'] as $zeiType => $dummy) : ?>
                    newRow += '        <option value="<?= $zeiType ?>" ><?= $zeiType ?></option>';
<?php endforeach; ?>
                newRow += '      </select>';
                newRow += '    </td>';
                newRow += '    <td class="sales_tax">';
                newRow += '      <b class="sm">消費税率</b>';
                newRow += '      <input id="jippiUisRate' + jippi_i + '" type="text" class="validate[maxSize[7],onlyNumberSp] uis_rate" name="<?= $jpiPrefix ?>[' + jippiNewCnt + '][rate]" value="<?= isset($jippiList['rate']) ? $jippiList['rate'] : '' ?>" style="width:60px;" placeholder="半角数字2桁"><span>%</span>';
                newRow += '    </td>';
                newRow += '    <td class="d_cate">';
                newRow += '      <b class="sm">控除区分</b>';
                newRow += '      <select id="jippiSubsidy' + jippi_i + '" name="<?= $jpiPrefix ?>[' + jippiNewCnt + '][subsidy]" class="uis_subsidy">';
<?php foreach ($unInsType['subsidy'] as $subsidy => $dummy) : ?>
                    newRow += '         <option value="<?= $subsidy ?>"><?= $subsidy ?></option>';
<?php endforeach; ?>
                newRow += '      </select>';
                newRow += '    </td>';
                newRow += '    <td>';
                newRow += '      <button type="button" class="row_delete" style="width:32px;height:32px;background:#FFFFFF;border-radius: 5px;border: 1px solid #A7A7A7;">';
                newRow += '        <img src="/common/image/icon_trash2.png" />';
                newRow += '      </button>';
                newRow += '    </td>';
                newRow += '    <td></td>';
                newRow += '  </tr>';
                $(newRow).appendTo(ol_wrap);
                jippi_i = jippi_i + 1;
                //changeService();
            });
            // 実費行削除
            $(".content_jippi").on('click', '.row_delete', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                return false;
            });
            // サービス内容詳細新規行追加
            $(".btn_append_service").click(function () {
                svcNewCnt++;
                var ol_wrap = $(this).closest(".content_service").find("ol");
                var newRow = '';
                newRow += '<li>';
                newRow += '  <select class="validate[required]" id="start_time_h_' + index + '" name="<?= $svcPrefix ?>[' + svcNewCnt + '][start_time_h]" style="width:60px;">';
<?php foreach ($selHour as $val) : ?>
                    newRow += '      <option value="<?= $val ?>"><?= $val ?></option>';
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <small>：</small>';
                newRow += '  <select class="validate[required]" id="start_time_m_' + index + '" name="<?= $svcPrefix ?>[' + svcNewCnt + '][start_time_m]" style="width:60px;">';
<?php foreach ($selMinutes as $val) : ?>
                    newRow += '      <option value="<?= $val ?>"><?= $val ?></option>';
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <small>～</small>';
                newRow += '  <select class="validate[required]" id="end_time_h_' + index + '" name="<?= $svcPrefix ?>[' + svcNewCnt + '][end_time_h]" style="width:60px;">';
<?php foreach ($selHour as $val) : ?>
                    newRow += '      <option value="<?= $val ?>"><?= $val ?></option>';
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <small>：</small>';
                newRow += '  <select class="validate[required, custom[notEqualTime]]" id="end_time_m_' + index + '" data-index="' + index + '" name="<?= $svcPrefix ?>[' + svcNewCnt + '][end_time_m]" style="width:60px;">';
<?php foreach ($selMinutes as $val) : ?>
                    newRow += '      <option value="<?= $val ?>"><?= $val ?></option>';
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <select class="cngOffice validate[required]" name="<?= $svcPrefix ?>[' + svcNewCnt + '][service_detail_id]" style="width:200px;">';
                newRow += '  <option value="">選択してください</option>';
<?php foreach ($svcDtlMst as $type => $svcDtlMst2) : ?>
    <?php foreach ($svcDtlMst2 as $tgtId => $val) : ?>
                        newRow += '  <option class="cngService" value="<?= $tgtId ?>" data-value="<?= $val['name'] ?>" data-service_name="<?= $type ?>"><?= $val['name'] ?></option>';
    <?php endforeach; ?>
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <input type="text" name="<?= $svcPrefix ?>[' + svcNewCnt + '][name]" style="width:90px;" value="<?= isset($svcData['name']) ? $svcData['name'] : '' ?>">';
                newRow += '  <p class="list_delete row_delete">Delete</p>';
                newRow += '</li>';
                $(newRow).appendTo(ol_wrap);
                index = index + 1;
                //changeService();
            });
            // サービス内容詳細行削除
            $(".content_service").on('click', '.row_delete', function (event) {
                event.preventDefault();
                $(this).closest('li').remove();
                return false;
            });
            $('.duplicate').click(function () {
                // スケジュール親
                var tgUrl = event.target.getAttribute('data-url');
                var dlgName = event.target.getAttribute('data-dialog_name');
                $(".modal_setting").children().remove();
                let xhr = new XMLHttpRequest();
                xhr.open('GET', tgUrl, true);
                xhr.addEventListener('load', function () {
                    console.log(this.response);
                    $(".modal_setting").append(this.response);
                    $("." + dlgName).css("display", "block");
                });
                xhr.send();
            });

            // サービス内容変更イベント
            $('#selServiceName').on('change', function () {
                changeService();
            });

            // 事業所変更イベント
            $('#office').on('change', function () {
            });

            // 実費名称変更イベント
            $(document).on('change', ".uis_name", function () {

                var type = $(this).find("option:selected").data("type");
                var name = $(this).find("option:selected").data("name");
                var type = $(this).find("option:selected").data("type");
                var zei_type = $(this).find("option:selected").data("zei_type");
                var subsidy = $(this).find("option:selected").data("subsidy");
                var rate = $(this).find("option:selected").data("rate");
                var price = $(this).find("option:selected").data("price");
                var name = $(this).find("option:selected").data("name");
                var id = $(this).find("option:selected").data("id");
                var element = $(this).find("option:selected");
                $(element).closest("tr").each(function (tgtElement) {
                    var tgtId = $(this).find(".uis_name").val();
                    if (tgtId === id) {
                        $(this).find(".uis_zei_type").val(zei_type);
                        $(this).find(".uis_rate").val(rate);
                        $(this).find(".uis_subsidy").val(subsidy);
                        $(this).find(".uis_price").val(price);
                    }
                });
            });

            // ダイアログ内はenterキー押下でのform送信を無効化
            $("input").on("keydown", function(e) {
                if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                    return false;
                } else {
                    return true;
                }
            });

            // 実費種類　選択・変更イベント
            $(document).on('change', ".selJippiType", function () {
                changeJippiType($(this).data("index"));
            });

            // 実費 種類・項目名称 選択・変更イベント
            $(document).on('change', ".cngJippi", function () {
                var row = $(this).data("index");
                var jippiTypeVal = $("#selJippiType" + row).val();
                var jippiFieldNameVal = $("#jippiFieldName" + row).val();

                if (jippiTypeVal !== "" || jippiFieldNameVal !== "") {
                    // 種類or項目名称のいずれかが選択されていたら実費系項目は必須とする
                    $("#selJippiType" + row).addClass("validate[required]");
                    $('#jippiFieldName' + row).addClass("validate[required]");
                    $('#jippiPrice' + row).removeClass("validate[maxSize[7],onlyNumberSp]");
                    $('#jippiPrice' + row).addClass("validate[required,maxSize[7],onlyNumberSp]");
                    $('#jippiZeiType' + row).addClass("validate[required]");
                    $('#jippiUisRate' + row).removeClass("validate[maxSize[7],onlyNumberSp]");
                    $('#jippiUisRate' + row).addClass("validate[required,maxSize[7],onlyNumberSp]");
                    $('#jippiSubsidy' + row).addClass("validate[required]");
                } else {
                    // 種類or項目名称のいずれも選択されていなければ必須をはずす
                    $("#selJippiType" + row).removeClass("validate[required]");
                    $('#jippiFieldName' + row).removeClass("validate[required]");                    
                    $('#jippiPrice' + row).removeClass("validate[required,maxSize[7],onlyNumberSp]");
                    $('#jippiPrice' + row).addClass("validate[maxSize[7],onlyNumberSp]");
                    $('#jippiZeiType' + row).removeClass("validate[required]");                    
                    $('#jippiUisRate' + row).removeClass("validate[required,maxSize[7],onlyNumberSp]");
                    $('#jippiUisRate' + row).addClass("validate[maxSize[7],onlyNumberSp]");
                    $('#jippiSubsidy' + row).removeClass("validate[required]");
                }
            });
        });

        // サービス名変更
        function changeService() {
            $(document).find(".cngService").each(function () {
                $(this).hide();
                $(this).prop('disabled', true);
            });

            var selServiceName = $('#selServiceName').val();
            
            $(document).find(".cngService").each(function () {
                var serviceName = $(this).data("service_name");
                if (!selServiceName || selServiceName === serviceName) {
                    $(this).show();
                    $(this).prop('disabled', false);
                } else {
                    $(this).removeAttr("selected");
                }
            });

            // サービス内容が変更されたら基本サービスコードはクリア
            $('#dtlstServiceId').val("");
        }

        // 実費種類　選択・変更
        function changeJippiType(index) {
            $(document).find(".cngJippiType" + index).each(function () {
                $(this).hide();
                $(this).prop('disabled', true);
            });

            var selJippiType = $('#selJippiType' + index).val();

            $(document).find(".cngJippiType" + index).each(function () {
                var jippiType = $(this).data("type");
                if (!selJippiType || selJippiType === jippiType) {
                    $(this).show();
                    $(this).prop('disabled', false);
                } else {
                    $(this).removeAttr("selected");
                }
            });

            $('#jippiFieldName' + index).val("");
            $('#jippiPrice' + index).val("");
            $('#jippiZeiType' + index).val("税込");
            $('#jippiUisRate' + index).val("");
            $('#jippiSubsidy' + index).val("控除対象外");
        }
    </script>
</div>