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

$selHour = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
$selMinutes = ['00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55'];

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
$schId = filter_input(INPUT_GET, 'id');

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
    $uisList[$type][$tgtId] = $val;
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
if (empty($tgtData['main']['weel_num'])) {
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
?>

<div class="modal new_default sched_default displayed_part cancel_act" style="height:600px;width:950px!important;">
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
    <div class="sched_tit">利用者スケジュール登録</div>
    <!-- 基本情報表示エリア:start -->
    <div class="s_detail">
        <?php $mainData = $dispData['main']; ?>
        <?php $addData = $dispData['add']; ?>
        <?php $jippiData = $dispData['jippi']; ?>
        <?php $serviceData = $dispData['service']; ?>
        <?php $careJobList = $codeList['従業員マスタ']['請求用資格']; ?>
        <?php $visitorNumList = $codeList['従業員予定実績']['同一建物に訪問した利用者']; ?>
        <?php $areaAddList = $codeList['従業員予定実績']['特別地域加算有無']; ?>
        <?php $insStationList = $codeList['従業員予定実績']['緊急訪問看護を行った指示先ステーション名区分']; ?>
        <?php $mainPrefix = "upAry"; ?>
        <?php $addPrefix = "upAdd"; ?>
        <?php $jpiPrefix = "upJippi"; ?>
        <?php $svcPrefix = "upSvc"; ?>
        <div class="box1">
            <?php if (!empty($mainData['unique_id'])) : ?>
                <input type="hidden" name="<?= $mainPrefix ?>[unique_id]" value="<?= $mainData['unique_id'] ?>">
            <?php endif; ?>
            <p class="mid">曜日/時刻</p>
            <p>
                <select class="s_month" name="<?= $mainPrefix ?>[week]">
                    <option <?= $mainData['week'] == '1' ? 'selected' : '' ?> value="1">月</option>
                    <option <?= $mainData['week'] == '2' ? 'selected' : '' ?> value="2">火</option>
                    <option <?= $mainData['week'] == '3' ? 'selected' : '' ?> value="3">水</option>
                    <option <?= $mainData['week'] == '4' ? 'selected' : '' ?> value="4">木</option>
                    <option <?= $mainData['week'] == '5' ? 'selected' : '' ?> value="5">金</option>
                    <option <?= $mainData['week'] == '6' ? 'selected' : '' ?> value="6">土</option>
                    <option <?= $mainData['week'] == '0' ? 'selected' : '' ?> value="0">日</option>
                </select>
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
            <p class="month_list" name="<?= $mainPrefix ?>[week_num]" required>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第1週" id="week1" <?= strpos($mainData['week_num'], '第1週') !== false ? 'checked' : '' ?>></label>第1週</label></span>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第2週" id="week2" <?= strpos($mainData['week_num'], '第2週') !== false ? 'checked' : '' ?>></label>第2週</label></span>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第3週" id="week3" <?= strpos($mainData['week_num'], '第3週') !== false ? 'checked' : '' ?>></label>第3週</label></span>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第4週" id="week4" <?= strpos($mainData['week_num'], '第4週') !== false ? 'checked' : '' ?>></label>第4週</label></span>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第5週" id="week5" <?= strpos($mainData['week_num'], '第5週') !== false ? 'checked' : '' ?>></label>第5週</label></span>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第6週" id="week6" <?= strpos($mainData['week_num'], '第6週') !== false ? 'checked' : '' ?>></label>第6週</label></span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">利用者</p>
            <p>
                <span class="user_res"><?= $userInfo['user_name'] ?></span>
                <input type="hidden" name="<?= $mainPrefix ?>[user_id]" value="<?= $userInfo['unique_id'] ?>">
                <span class="label_t">(利用者ID: <?= $userInfo['other_id'] ?>)</span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">実施事業所</p>
            <select id="office" class="staff" name="<?= $mainPrefix ?>[office_id]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($ofcList as $ofcId => $val) : ?>
                    <?php $select = $mainData['office_id'] === $ofcId ? ' selected' : null; ?>
                    <option value="<?= $ofcId ?>" <?= $select ?>><?= $val['name'] . "(ID:" . $val['office_no'] . ")" ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="box1">
            <p class="mid">サービス内容</p>
            <select id="selServiceName" class="staff" name="<?= $mainPrefix ?>[service_name]" style="width:320px">
                <option value="">選択してください</option>
                <?php foreach ($svcMst as $type => $dummy) : ?>
                    <?php $select = $type === $mainData['service_name'] ? ' selected' : null; ?>
                    <option value="<?= $type ?>" <?= $select ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
            <p class="" style="margin-left:50px; margin-right:10px;">
                <span><label>自費</label><input type="checkbox" name="<?= $mainPrefix ?>[jihi_flg]" id="expense" value="1" <?= $mainData['jihi_flg'] == 1 ? 'checked' : '' ?>></span>
            </p>
            <span class="">
                <input type="text" name="<?= $mainPrefix ?>[jihi_price]" maxlength="11" value="<?= $mainData['jihi_price'] ?>" style="width:80px;" placeholder="半角数字"><label>円</label>
            </span>
        </div>
        <div class="box1">
            <p class="mid">基本サービス<br class="pc">コード</p>
            <input type="hidden" id="hidServiceId" name="<?= $mainPrefix ?>[service_id]" value="<?= $mainData['service_id'] ?>">
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
        <!-- 展開前は表示しない
        <div class="box1">
            <p class="mid">訪問職種</p>
            <select class="staff" name="<?= $mainPrefix ?>[care_job]" style="width:320px">
                <option value="">選択してください</option>
        <?php foreach ($careJobList as $type => $job) : ?>
            <?php $select = $job === $mainData['care_job'] ? ' selected' : null; ?>
                                                        <option value="<?= $job ?>" <?= $select ?>><?= $job ?></option>
        <?php endforeach; ?>
            </select>
        </div>
        <div class="box1">
            <p class="mid">同一建物に訪問した利用者</p>
            <select class="staff" name="<?= $mainPrefix ?>[visitor_num]" style="width:320px">
                <option value="">選択してください</option>
        <?php foreach ($visitorNumList as $type => $job) : ?>
            <?php $select = $job === $mainData['visitor_num'] ? ' selected' : null; ?>
                                                        <option value="<?= $job ?>" <?= $select ?>><?= $job ?></option>
        <?php endforeach; ?>
            </select>
        </div>
        <div class="box1">
            <p class="mid">特別地域加算有無</p>
            <select class="staff" name="<?= $mainPrefix ?>[area_add]" style="width:320px">
                <option value="">選択してください</option>
        <?php foreach ($areaAddList as $type => $job) : ?>
            <?php $select = $job === $mainData['area_add'] ? ' selected' : null; ?>
                                                        <option value="<?= $job ?>" <?= $select ?>><?= $job ?></option>
        <?php endforeach; ?>
            </select>
        </div>
        <div class="box1">
            <p class="mid">緊急自訪問看護を行った指示先<br/>ステーション名区分</p>
            <select class="staff" name="<?= $mainPrefix ?>[ins_station]" style="width:320px">
                <option value="">選択してください</option>
        <?php foreach ($insStationList as $type => $job) : ?>
            <?php $select = $job === $mainData['ins_station'] ? ' selected' : null; ?>
                                                        <option value="<?= $job ?>" <?= $select ?>><?= $job ?></option>
        <?php endforeach; ?>
            </select>
        </div>
        -->
    </div>
    <!-- 基本情報表示エリア:end -->
    <!-- 加減算表示エリア:start -->
    <div class="add_sub content_add">
        <p class="mid">加減算</p>
        <ol id="add_root">
            <?php if (empty($dispData['add'])) : ?>
                <li>
                    <input type="hidden" name="<?= $addPrefix ?>[user_id][]" value="<?= $userInfo['unique_id'] ?>">
                    <input type="hidden" name="<?= $addPrefix ?>[schedule_id][]" value="<?= $mainData['unique_id'] ?>">
                    <select name="<?= $addPrefix ?>[add_id][]" class="addList">
                        <option value="">選択してください</option>
                        <?php foreach ($addMst as $type => $addMst2) : ?>
                            <?php foreach ($addMst2 as $tgtId => $val) : ?>
                                <option value="<?= $tgtId ?>" class="cngService" data-value="<?= $val ?>" data-service_name="<?= $type ?>"><?= $val ?></option>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" name="<?= $addPrefix ?>[start_day][]" value="<?= isset($addData['start_day']) ? $addData['start_day'] : '' ?>">
                    <small>～</small>
                    <input type="date" name="<?= $addPrefix ?>[end_day][]" value="<?= isset($addData['end_day']) ? $addData['end_day'] : '' ?>">
                    <p class="list_delete row_delete">Delete</p>
                </li>
            <?php else : ?>
                <?php foreach ($dispData['add'] as $addData) : ?>
                    <li>
                        <input type="hidden" name="<?= $addPrefix ?>[unique_id][]" value="<?= $addData['unique_id'] ?>">
                        <input type="hidden" name="<?= $addPrefix ?>[user_id][]" value="<?= $userInfo['unique_id'] ?>">
                        <input type="hidden" name="<?= $addPrefix ?>[schedule_id][]" value="<?= $mainData['unique_id'] ?>">
                        <select name="<?= $addPrefix ?>[add_id][]" class="addList">
                            <option value="">選択してください</option>
                            <?php foreach ($addMst as $type => $addMst2) : ?>
                                <?php foreach ($addMst2 as $tgtId => $val) : ?>
                                    <?php $select = $addData['add_id'] === $tgtId ? ' selected' : ''; ?>
                                    <option value="<?= $tgtId ?>" class="cngService" data-value="<?= $val ?>" data-service_name="<?= $type ?>" <?= $select ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </select>
                        <input type="date" name="<?= $addPrefix ?>[start_day][]" value="<?= isset($addData['start_day']) ? $addData['start_day'] : '' ?>">
                        <small>～</small>
                        <input type="date" name="<?= $addPrefix ?>[end_day][]" value="<?= isset($addData['end_day']) ? $addData['end_day'] : '' ?>">
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
                <input type="hidden" name="<?= $jpiPrefix ?>[user_id][]" value="<?= $userInfo['unique_id'] ?>">
                <input type="hidden" name="<?= $jpiPrefix ?>[schedule_id][]" value="<?= $mainData['unique_id'] ?>">
                <td class="type">
                    <b class="sm">種類</b>
                    <select name="<?= $jpiPrefix ?>[type][]" class="uis_type">
                        <option value="">選択してください</option>
                        <?php foreach ($unInsType['type'] as $type => $dummy) : ?>
                            <option value="<?= $type ?>"><?= $type ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="item">
                    <b class="sm">項目名称</b>
                    <select class="cngOffice uis_name" name="<?= $jpiPrefix ?>[uninsure_id][]">
                        <option value="">選択してください</option>
                        <?php foreach ($uisList as $type => $uisList2) : ?>
                            <?php foreach ($uisList2 as $uisId => $uisData) : ?>
                                <option value="<?= $uisId ?>" 
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
                    <input type="text" class="validate[maxSize[7],onlyNumberSp] uis_price" name="<?= $jpiPrefix ?>[price][]" value="<?= isset($jippiList['price']) ? $jippiList['price'] : '' ?>" style="width:85px">
                </td>
                <td class="tax">
                    <b class="sm">消費税<br>区分</b>
                    <select name="<?= $jpiPrefix ?>[zei_type][]" class="uis_zei_type">
                        <?php foreach ($unInsType['zei_type'] as $zeiType => $dummy) : ?>
                            <option value="<?= $zeiType ?>"><?= $zeiType ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="sales_tax">
                    <b class="sm">消費税率</b>
                    <input type="text" class="validate[maxSize[2],onlyNumberSp] uis_rate" name="<?= $jpiPrefix ?>[rate][]" value="<?= isset($jippiList['rate']) ? $jippiList['rate'] : '' ?>" style="width:60px;"><span>%</span>
                </td>
                <td class="d_cate">
                    <b class="sm">控除区分</b>
                    <select name="<?= $jpiPrefix ?>[subsidy][]" class="uis_subsidy">
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
            <?php else : ?>
                <?php foreach ($dispData['jippi'] as $jippiList) : ?>
                    <tr>
                    <input type="hidden" name="<?= $jpiPrefix ?>[unique_id][]" value="<?= $jippiList['unique_id'] ?>">
                    <input type="hidden" name="<?= $jpiPrefix ?>[user_id][]" value="<?= $userInfo['unique_id'] ?>">
                    <input type="hidden" name="<?= $jpiPrefix ?>[schedule_id][]" value="<?= $mainData['unique_id'] ?>">
                    <td class="type">
                        <b class="sm">種類</b>
                        <select name="<?= $jpiPrefix ?>[type][]" class="uis_type">
                            <option value="">選択してください</option>
                            <?php foreach ($unInsType['type'] as $type => $dummy) : ?>
                                <?php $select = $jippiList['type'] === $type ? ' selected' : null; ?>
                                <option value="<?= $type ?>" <?= $select ?>><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="item">
                        <b class="sm">項目名称</b>
                        <select class="cngOffice uis_name" name="<?= $jpiPrefix ?>[uninsure_id][]">
                            <option value="">選択してください</option>
                            <?php foreach ($uisList as $type => $uisList2) : ?>
                                <?php foreach ($uisList2 as $uisId => $uisData) : ?>
                                    <?php $select = $jippiList['name'] === $uisData['name'] ? ' selected' : null; ?>
                                    <option value="<?= $uisId ?>" 
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
                        <input type="text" class="validate[maxSize[7],onlyNumberSp] uis_price" name="<?= $jpiPrefix ?>[price][]" value="<?= $jippiList['price'] ?>" style="width:85px" maxlength="7" placeholder="半角数字7桁">
                    </td>
                    <td class="tax">
                        <b class="sm">消費税<br>区分</b>
                        <select name="<?= $jpiPrefix ?>[zei_type][]" class="uis_zeiType">
                            <?php foreach ($unInsType['zei_type'] as $zeiType => $dummy) : ?>
                                <?php $select = $jippiList['zei_type'] === $zeiType ? ' selected' : null; ?>
                                <option value="<?= $zeiType ?>" <?= $select ?>><?= $zeiType ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="sales_tax">
                        <b class="sm">消費税率</b>
                        <input type="text" class="validate[maxSize[2],onlyNumberSp] uis_rate" name="<?= $jpiPrefix ?>[rate][]" value="<?= isset($jippiList['rate']) ? $jippiList['rate'] : '' ?>" style="width:60px;" maxlength="2" placeholder="半角数字2桁"><span>%</span>
                    </td>
                    <td class="d_cate">
                        <b class="sm">控除区分</b>
                        <select name="<?= $jpiPrefix ?>[subsidy][]" class="uis_subsidy">
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
                        <input type="hidden" name="<?= $svcPrefix ?>[user_id][]" value="<?= $userInfo['unique_id'] ?>">
                        <input type="hidden" name="<?= $svcPrefix ?>[schedule_id][]" value="<?= $mainData['unique_id'] ?>">
                        <select name="<?= $svcPrefix ?>[start_time_h][]" style="width:60px;">
                            <?php foreach ($selHour as $val) : ?>
                                <option value="<?= $val ?>"><?= $val ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small>：</small>
                        <select name="<?= $svcPrefix ?>[start_time_m][]" style="width:60px;">
                            <?php foreach ($selMinutes as $val) : ?>
                                <option value="<?= $val ?>"><?= $val ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small>～</small>
                        <select name="<?= $svcPrefix ?>[end_time_h][]" style="width:60px;">
                            <?php foreach ($selHour as $val) : ?>
                                <option value="<?= $val ?>"><?= $val ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small>：</small>
                        <select name="<?= $svcPrefix ?>[end_time_m][]" style="width:60px;">
                            <?php foreach ($selMinutes as $val) : ?>
                                <option value="<?= $val ?>"><?= $val ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select class="cngOffice" name="<?= $svcPrefix ?>[service_detail_id][]" style="width:200px;">
                            <option value="">選択してください</option>
                            <?php foreach ($svcDtlMst as $type => $svcDtlMst2) : ?>
                                <?php foreach ($svcDtlMst2 as $tgtId => $val) : ?>
                                    <option class="cngService" value="<?= $tgtId ?>" data-value="<?= $val['name'] ?>" data-service_name="<?= $type ?>"><?= $val['name'] ?></option>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </select>
                        <p class="list_delete row_delete">Delete</p>
                    </li>
                <?php else : ?>
                    <?php foreach ($serviceData as $svcData) : ?>
                        <li>
                            <input type="hidden" name="<?= $svcPrefix ?>[unique_id][]" value="<?= $svcData['unique_id'] ?>">
                            <input type="hidden" name="<?= $svcPrefix ?>[user_id][]" value="<?= $userInfo['unique_id'] ?>">
                            <input type="hidden" name="<?= $svcPrefix ?>[schedule_id][]" value="<?= $mainData['unique_id'] ?>">
                            <select name="<?= $svcPrefix ?>[start_time_h][]" style="width:60px;">
                                <?php foreach ($selHour as $val) : ?>
                                    <?php $selected = strpos($svcData['start_time'], $val . ":") !== false ? ' selected' : ""; ?>
                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small>：</small>
                            <select name="<?= $svcPrefix ?>[start_time_m][]" style="width:60px;">
                                <?php foreach ($selMinutes as $val) : ?>
                                    <?php $selected = strpos($svcData['start_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small>～</small>
                            <select name="<?= $svcPrefix ?>[end_time_h][]" style="width:60px;">
                                <?php foreach ($selHour as $val) : ?>
                                    <?php $selected = strpos($svcData['end_time'], $val . ":") !== false ? ' selected' : ""; ?>
                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small>：</small>
                            <select name="<?= $svcPrefix ?>[end_time_m][]" style="width:60px;">
                                <?php foreach ($selMinutes as $val) : ?>
                                    <?php $selected = strpos($svcData['end_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="cngOffice" name="<?= $svcPrefix ?>[service_detail_id][]" style="width:200px;">
                                <option value="">選択してください</option>
                                <?php foreach ($svcDtlMst as $type => $svcDtlMst2) : ?>
                                    <?php foreach ($svcDtlMst2 as $tgtId => $val) : ?>
                                        <?php $select = $svcData['service_detail_id'] === $tgtId ? ' selected' : null; ?>
                                        <option class="cngService" value="<?= $tgtId ?>" data-value="<?= $val['name'] ?>" data-service_name="<?= $type ?>" <?= $select ?>><?= $val['name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                            <p class="list_delete row_delete">Delete</p>
                        </li>
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
            <?php $disabled = empty($schId) ? 'disabled' : ''; ?>
            <button type="submit" name="btnDelUser" value="<?= $schId ?>" class="btn delete" style="width:60px;height:37px;font-size: 15px;">削除</button>
            <button type="button" name="btnDupli" value="true" class="modal_open btn duplicate" style="width:60px;height:37px;font-size: 15px;" data-url="/schedule/week/dialog/dupli_dialog.php?user=<?= $userId ?>&place=<?= $placeId ?>&id=<?= $schId ?>" data-dialog_name="modal" <?= $disabled ?>>複製</button>
            <button type="submit" name="btnEntryUser" value="true" class="btn save" style="width:60px;height:37px;font-size: 15px;">保存</button>
        </p>
    </div>
    <!-- 下部ボタンエリア:end-->
    <div class="update">
        最終更新:
        <span class="time"><?= isset($mainData['update_date']) ? $mainData['update_date'] : '' ?></span>
        <span class="person"><?= isset($mainData['update_name']) ? $mainData['update_name'] : '' ?></span>
    </div>
    <script>
        $(function () {
            // ダイアログクローズ
            $(".modal_close").on("click", function () {
                // windowを閉じる
                $(".modal").remove();
            });
            // 加減算新規行追加
            $(".btn_append_add").click(function () {
                var ol_wrap = $(this).closest(".content_add").find("ol");
                var newRow = '';
                newRow += '<li>';
                newRow += '  <input type="hidden" name="<?= $addPrefix ?>[user_id][]" value="<?= $userInfo['unique_id'] ?>">';
                newRow += '  <input type="hidden" name="<?= $addPrefix ?>[schedule_id][]" value="<?= $mainData['unique_id'] ?>">';
                newRow += '  <select name="<?= $addPrefix ?>[add_id][]" class="addList">';
                newRow += ' <option value="">選択してください</option>';
<?php foreach ($addMst as $type => $addMst2) : ?>
    <?php foreach ($addMst2 as $tgtId => $val) : ?>
                        newRow += ' <option value="<?= $tgtId ?>" class="cngService" data-value="<?= $val ?>" data-service_name="<?= $type ?>"><?= $val ?></option>';
    <?php endforeach; ?>
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <input type="date" name="<?= $addPrefix ?>[start_day][]" value="">';
                newRow += '  <small>～</small>';
                newRow += '  <input type="date" name="<?= $addPrefix ?>[end_day][]" value="">';
                newRow += '  <p class="list_delete row_delete">Delete</p>';
                newRow += '</li>';
                $(newRow).appendTo(ol_wrap);
                changeService();
            });
            // 加減算行削除
            $(".content_add").on('click', '.row_delete', function (event) {
                event.preventDefault();
                $(this).closest('li').remove();
                return false;
            });
            // 実費新規行追加
            $(".btn_append_jippi").click(function () {
                var ol_wrap = $(this).closest(".content_jippi").find("tbody");
                var newRow = '';
                newRow += '  <tr>';
                newRow += '    <input type="hidden" name="<?= $jpiPrefix ?>[user_id][]" value="<?= $userInfo['unique_id'] ?>">';
                newRow += '    <input type="hidden" name="<?= $jpiPrefix ?>[schedule_id][]" value="<?= $mainData['unique_id'] ?>">';
                newRow += '    <td class="type">';
                newRow += '      <b class="sm">種類</b>';
                newRow += '    <select name="<?= $jpiPrefix ?>[type][]" class="uis_type">';
                newRow += '        <option value="">選択してください</option>';
<?php foreach ($unInsType['type'] as $type => $dummy) : ?>
                    newRow += '          <option value="<?= $type ?>"><?= $type ?></option>';
<?php endforeach; ?>
                newRow += '      </select>';
                newRow += '    </td>';
                newRow += '    <td class="item">';
                newRow += '      <b class="sm">項目名称</b>';
                newRow += '    <select class="cngOffice uis_name" name="<?= $jpiPrefix ?>[uninsure_id][]">';
                newRow += '        <option value="">選択してください</option>';
<?php foreach ($uisList as $type => $uisList2) : ?>
    <?php foreach ($uisList2 as $uisId => $uisData) : ?>
                        newRow += '                <option value="<?= $uisId ?>" ';
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
                newRow += '    <input type="text" class="validate[maxSize[7],onlyNumberSp] uis_price" name="<?= $jpiPrefix ?>[price][]" value="<?= isset($jippiList['price']) ? $jippiList['price'] : '' ?>" style="width:85px">';
                newRow += '    </td>';
                newRow += '    <td class="tax">';
                newRow += '      <b class="sm">消費税<br>区分</b>';
                newRow += '      <select name="<?= $jpiPrefix ?>[zei_type][]" class="uis_zei_type">';
<?php foreach ($unInsType['zei_type'] as $zeiType => $dummy) : ?>
                    newRow += '        <option value="<?= $zeiType ?>" ><?= $zeiType ?></option>';
<?php endforeach; ?>
                newRow += '      </select>';
                newRow += '    </td>';
                newRow += '    <td class="sales_tax">';
                newRow += '      <b class="sm">消費税率</b>';
                newRow += '      <input type="text" class="validate[maxSize[7],onlyNumberSp] uis_rate" name="<?= $jpiPrefix ?>[rate][]" value="<?= isset($jippiList['rate']) ? $jippiList['rate'] : '' ?>" style="width:60px;" placeholder="半角数字2桁"><span>%</span>';
                newRow += '    </td>';
                newRow += '    <td class="d_cate">';
                newRow += '      <b class="sm">控除区分</b>';
                newRow += '      <select name="<?= $jpiPrefix ?>[subsidy][]" class="uis_subsidy">';
<?php foreach ($unInsType['subsidy'] as $subsidy => $dummy) : ?>
                    newRow += '         <option value="<?= $subsidy ?>" <?= $select ?>><?= $subsidy ?></option>';
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
                changeService();
            });
            // 実費行削除
            $(".content_jippi").on('click', '.row_delete', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                return false;
            });
            // サービス内容詳細新規行追加
            $(".btn_append_service").click(function () {
                var ol_wrap = $(this).closest(".content_service").find("ol");
                var newRow = '';
                newRow += '<li>';
                newRow += '  <input type="hidden" name="<?= $svcPrefix ?>[user_id][]" value="<?= $userInfo['unique_id'] ?>">';
                newRow += '  <input type="hidden" name="<?= $svcPrefix ?>[schedule_id][]" value="<?= $mainData['unique_id'] ?>">';
                newRow += '  <select name="<?= $svcPrefix ?>[start_time_h][]" style="width:60px;">';
<?php foreach ($selHour as $val) : ?>
                    newRow += '      <option value="<?= $val ?>"><?= $val ?></option>';
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <small>：</small>';
                newRow += '  <select name="<?= $svcPrefix ?>[start_time_m][]" style="width:60px;">';
<?php foreach ($selMinutes as $val) : ?>
                    newRow += '      <option value="<?= $val ?>"><?= $val ?></option>';
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <small>～</small>';
                newRow += '  <select name="<?= $svcPrefix ?>[end_time_h][]" style="width:60px;">';
<?php foreach ($selHour as $val) : ?>
                    newRow += '      <option value="<?= $val ?>"><?= $val ?></option>';
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <small>：</small>';
                newRow += '  <select name="<?= $svcPrefix ?>[end_time_m][]" style="width:60px;">';
<?php foreach ($selMinutes as $val) : ?>
                    newRow += '      <option value="<?= $val ?>"><?= $val ?></option>';
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <select class="cngOffice" name="<?= $svcPrefix ?>[service_detail_id][]" style="width:200px;">';
                newRow += '     <option value="">選択してください</option>';
<?php foreach ($svcDtlMst as $type => $svcDtlMst2) : ?>
    <?php foreach ($svcDtlMst2 as $tgtId => $val) : ?>
                        newRow += '     <option class="cngService" value="<?= $tgtId ?>" data-value="<?= $val['name'] ?>" data-service_name="<?= $type ?>"><?= $val['name'] ?></option>';
    <?php endforeach; ?>
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <p class="list_delete row_delete">Delete</p>';
                newRow += '</li>';
                $(newRow).appendTo(ol_wrap);
                changeService();
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
            }
        });
    </script>
</div>