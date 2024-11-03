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

// ユーザID
$index = filter_input(INPUT_GET, 'index');
$index = !empty($index) ? $index : '';

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
$instList = array();
$dispData = array();
//$where['status'] = '完了';
$where['direction_start <='] = TODAY;
$where['delete_flg'] = 0;
$where['user_id']    = $userId;
$orderBy             = "report_day ASC";
$temp = select('doc_instruct', '*', $where, $orderBy);
foreach ($temp as $val) {
    $keyId = $val['unique_id'];
    if(empty($keyId)) {
        continue;
    }

    if (!empty($val['direction_end'])
        && $val['direction_end'] === "0000-00-00"
        && $val['direction_end'] <= TODAY) {
        continue;
    }

    $val['staff_name'] = !empty($val['staff_id']) ? getStaffName($val['staff_id']) : '';
    $val['report_day'] = $val['report_day'] == "0000-00-00" ? null : $val['report_day'];
    $val['direction_start'] = $val['direction_start'] == "0000-00-00" ? null : $val['direction_start'];
    $val['direction_end'] = $val['direction_end'] == "0000-00-00" ? null : $val['direction_end'];
    $val['plan_day'] = $val['plan_day'] == "0000-00-00" ? null : $val['plan_day'];
    $val['judgement_day'] = $val['judgement_day'] == "0000-00-00" ? null : $val['judgement_day'];
    $val['tel1'] = !empty($val['tel1']) ? $val['tel1'] : '';
    $val['tel2'] = !empty($val['tel2']) ? $val['tel2'] : '';
    $val['fax'] = !empty($val['fax']) ? $val['fax'] : null;
    $val['address1'] = !empty($val['address1']) ? $val['address1'] : null;
    $val['create_date'] = !empty($val['create_date']) ? formatDateTime($val['create_date'], "Y/m/d") : null;
    $val['update_day'] = !empty($val['update_date']) ? formatDateTime($val['update_date'], "Y/m/d") : null;

    $instList[$keyId] = $val;
}

/* -- データ取得 -------------------------------------------- */

/* -- その他計画関連 ------------------------------ */

/* -- 画面表示データ格納 ---------------------------- */
?>
<div class="dynamic_modal new_default displayed_part cancel_act" style="height:400px;width:950px!important;overflow: scroll!important;overflow-y: auto;overscroll-behavior-y: contain;top:60%;">
    <div class="tit">指示書選択</div>
    <div class="modal_close close close_part">✕<span class="modal_close">閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th style="width:60px;"></th>
                <th style="width:120px;">作成日</th>
                <th style="width:140px;">訪問看護区分</th>
                <th style="width:180px;">医療機関名称</th>
                <th style="width:180px;">主治医</th>
                <th style="width:90px;">TEL1</th>
                <th style="width:150px;">担当者</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($instList as $keyId => $val) : ?>
                <tr>
                    <td>
                        <button class="modal_selected" type="button" 
                                data-unique_id="<?= $val['unique_id'] ?>"
                                data-update_day="<?= $val['update_day'] ?>"
                                data-update_user="<?= $val['update_user'] ?>"
                                data-user_id="<?= $val['user_id'] ?>"
                                data-staff_id="<?= $val['staff_id'] ?>"
                                data-staff_name="<?= $val['staff_name'] ?>"
                                data-direction_start="<?= $val['direction_start'] ?>"
                                data-direction_end="<?= $val['direction_end'] ?>"
                                data-direction_months="<?= $val['direction_months'] ?>"
                                data-plan_day="<?= $val['plan_day'] ?>"
                                data-report_day="<?= $val['report_day'] ?>"
                                data-care_kb="<?= $val['care_kb'] ?>"
                                data-direction_kb="<?= $val['direction_kb'] ?>"
                                data-judgement_day="<?= $val['judgement_day'] ?>"
                                data-rece_detail="<?= $val['rece_detail'] ?>"
                                data-postscript="<?= $val['postscript'] ?>"
                                data-attached8="<?= $val['attached8'] ?>"
                                data-seriously_child="<?= $val['seriously_child'] ?>"
                                data-attached8_detail="<?= $val['attached8_detail'] ?>"
                                data-other_station1="<?= $val['other_station1'] ?>"
                                data-other_station1_address="<?= $val['other_station1_address'] ?>"
                                data-other_station2="<?= $val['other_station2'] ?>"
                                data-other_station2_address="<?= $val['other_station2_address'] ?>"
                                data-sickness1="<?= $val['sickness1'] ?>"
                                data-sickness2="<?= $val['sickness2'] ?>"
                                data-sickness3="<?= $val['sickness3'] ?>"
                                data-sickness4="<?= $val['sickness4'] ?>"
                                data-sickness5="<?= $val['sickness5'] ?>"
                                data-sickness6="<?= $val['sickness6'] ?>"
                                data-sickness7="<?= $val['sickness7'] ?>"
                                data-sickness8="<?= $val['sickness8'] ?>"
                                data-sickness9="<?= $val['sickness9'] ?>"
                                data-sickness10="<?= $val['sickness10'] ?>"
                                data-hospital="<?= $val['hospital'] ?>"
                                data-hospital_rece="<?= $val['hospital_rece'] ?>"
                                data-address1="<?= $val['address1'] ?>"
                                data-doctor="<?= $val['doctor'] ?>"
                                data-tel1="<?= $val['tel1'] ?>"
                                data-tel2="<?= $val['tel2'] ?>"
                                data-fax="<?= $val['fax'] ?>"
                                data-status="<?= $val['status'] ?>"
                                >選択</button>
                    </td>
                    <td><?= isset($val['update_day']) ? $val['update_day'] : '' ?></td>
                    <td><?= isset($val['care_kb']) ? $val['care_kb'] : '' ?></td>
                    <td><?= isset($val['hospital']) ? $val['hospital'] : '' ?></td>
                    <td><?= isset($val['doctor']) ? $val['doctor'] : '' ?></td>
                    <td><?= isset($val['tel1']) ? $val['tel1'] : '' ?></td>
                    <td><?= isset($val['staff_name']) ? $val['staff_name'] : '' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        $(function () {
            // モーダルから選択
            $(".dynamic_modal").find("table button").on("click", function () {
                
                var index = '<?= !empty($index) ? $index : '' ?>';

                // 各種データ取得
                var unique_id = $(this).data("unique_id");
                var update_day = $(this).data("update_day");
                var update_user = $(this).data("update_user");
                var user_id = $(this).data("user_id");
                var staff_id = $(this).data("staff_id");
                var staff_name = $(this).data("staff_name");
                var direction_start = $(this).data("direction_start");
                var direction_end = $(this).data("direction_end");
                var direction_months = $(this).data("direction_months");
                var plan_day = $(this).data("plan_day");
                var report_day = $(this).data("report_day");
                var care_kb = $(this).data("care_kb");
                var direction_kb = $(this).data("direction_kb");
                var judgement_day = $(this).data("judgement_day");
                var rece_detail = $(this).data("rece_detail");
                var postscript = $(this).data("postscript");
                var attached8 = $(this).data("attached8");
                var seriously_child = $(this).data("seriously_child");
                var attached8_detail = $(this).data("attached8_detail");
                var other_station1 = $(this).data("other_station1");
                var other_station1_address = $(this).data("other_station1_address");
                var other_station2 = $(this).data("other_station2");
                var other_station2_address = $(this).data("other_station2_address");
                var sickness1 = $(this).data("sickness1");
                var sickness2 = $(this).data("sickness2");
                var sickness3 = $(this).data("sickness3");
                var sickness4 = $(this).data("sickness4");
                var sickness5 = $(this).data("sickness5");
                var sickness6 = $(this).data("sickness6");
                var sickness7 = $(this).data("sickness7");
                var sickness8 = $(this).data("sickness8");
                var sickness9 = $(this).data("sickness9");
                var sickness10 = $(this).data("sickness10");
                var hospital = $(this).data("hospital");
                var hospital_rece = $(this).data("hospital_rece");
                var address1 = $(this).data("address1");
                var doctor = $(this).data("doctor");
                var tel1 = $(this).data("tel1");
                var tel2 = $(this).data("tel2");
                var fax = $(this).data("fax");
                var status = $(this).data("status");

                $(".set" + index + "_unique_id").val(unique_id);
                $(".set" + index + "_update_day").val(update_day);
                $(".set" + index + "_update_user").val(update_user);
                $(".set" + index + "_user_id").val(user_id);
                $(".set" + index + "_staff_id").val(staff_id);
                $(".set" + index + "_staff_name").val(staff_name);
                $(".set" + index + "_direction_start").val(direction_start);
                $(".set" + index + "_direction_end").val(direction_end);
                $(".set" + index + "_direction_months").val(direction_months);
                $(".set" + index + "_plan_day").val(plan_day);
                $(".set" + index + "_report_day").val(report_day);
                $(".set" + index + "_care_kb").val(care_kb);
                $(".set" + index + "_direction_kb").val(direction_kb);
                $(".set" + index + "_judgement_day").val(judgement_day);
                $(".set" + index + "_rece_detail").val(rece_detail);
                $(".set" + index + "_postscript").val(postscript);
                $(".set" + index + "_attached8").val(attached8);
                $(".set" + index + "_seriously_child").val(seriously_child);
                $(".set" + index + "_attached8_detail").val(attached8_detail);
                $(".set" + index + "_other_station1").val(other_station1);
                $(".set" + index + "_other_station1_address").val(other_station1_address);
                $(".set" + index + "_other_station2").val(other_station2);
                $(".set" + index + "_other_station2_address").val(other_station2_address);
                $(".set" + index + "_sickness1").val(sickness1);
                $(".set" + index + "_sickness2").val(sickness2);
                $(".set" + index + "_sickness3").val(sickness3);
                $(".set" + index + "_sickness4").val(sickness4);
                $(".set" + index + "_sickness5").val(sickness5);
                $(".set" + index + "_sickness6").val(sickness6);
                $(".set" + index + "_sickness7").val(sickness7);
                $(".set" + index + "_sickness8").val(sickness8);
                $(".set" + index + "_sickness9").val(sickness9);
                $(".set" + index + "_sickness10").val(sickness10);                
                $(".set" + index + "_hospital").val(hospital);
                $(".set" + index + "_hospital_rece").val(hospital_rece);
                $(".set" + index + "_address1").val(address1);
                $(".set" + index + "_doctor").val(doctor);
                $(".set" + index + "_tel1").val(tel1);
                $(".set" + index + "_tel2").val(tel2);
                $(".set" + index + "_fax").val(fax);
                $(".set" + index + "_status").val(status);
                
                // windowを閉じる
                $(".dynamic_modal").remove();
            });
            // ダイアログクローズ
            $(".modal_close").on("click", function () {
                // windowを閉じる
                $(".dynamic_modal").remove();
            });
        });
    </script>
</div>