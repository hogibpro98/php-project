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
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : NULL;
}

// ユーザID
$userId = filter_input(INPUT_GET, 'user');
if (!$userId) {
    $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : NULL;
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
$instList = array();
$instList[0] = array();
$dispData = array();
//$where['status'] = '完了';
$where['direction_start <='] = TODAY;
if (!empty($where['direction_end'])) {
    $where['direction_end >='] = TODAY;
}
$where['delete_flg'] = 0;
if ($userId) {
    $where['user_id'] = $userId;
}
$orderBy = "report_day ASC";
$temp = select('doc_instruct', '*', $where, $orderBy);
foreach ($temp as $val) {
    $keyId = $val['unique_id'];
    $val['staff_name']      = getStaffName($val['staff_id']);
    $val['report_day']      = $val['report_day']      == "0000-00-00" ? NULL : $val['report_day'];
    $val['direction_start'] = $val['direction_start'] == "0000-00-00" ? NULL : $val['direction_start'];
    $val['direction_end']   = $val['direction_end']   == "0000-00-00" ? NULL : $val['direction_end'];
    $val['plan_day']        = $val['plan_day']        == "0000-00-00" ? NULL : $val['plan_day'];
    $val['judgement_day']   = $val['judgement_day']   == "0000-00-00" ? NULL : $val['judgement_day'];
    $val['tel1']            = !empty($val['tel1'])        ? $val['tel1']     : NULL;
    $val['tel2']            = !empty($val['tel2'])        ? $val['tel2']     : NULL;
    $val['fax']             = !empty($val['fax'])         ? $val['fax']      : NULL;
    $val['address1']        = !empty($val['address1'])    ? $val['address1'] : NULL;
    $val['update_day']     = !empty($val['update_date'])  ? formatDateTime($val['update_date'],"Y/m/d") : NULL;
    $instList[$keyId] = $val;
}

/* -- データ取得 -------------------------------------------- */

/* -- その他計画関連 ------------------------------ */

/* -- 画面表示データ格納 ---------------------------- */
?>
<div class="dynamic_modal new_default displayed_part cancel_act" style="height:600px;width:950px!important;overflow: scroll!important;overflow-y: auto;overscroll-behavior-y: contain;top:60%;">
    <div class="tit">指示書選択</div>
    <div class="modal_close close close_part">✕<span class="modal_close">閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th class="w10"></th>
                <th class="w20">作成日</th>
                <th class="w20">訪問看護区分</th>
                <th class="w20">医療機関名称</th>
                <th class="w20">主治医</th>
                <th class="w20">TEL1</th>
                <th class="w20">担当者</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($instList as $keyId => $val) : ?>
                <tr>
                    <td>
                        <button class="modal_selected" type="button" 
                                data-unique_id="<?= $val['unique_id'] ?>"
                                data-create_date="<?= $val['create_date'] ?>"
                                data-create_user="<?= $val['create_user'] ?>"
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
                    <td><?= $val['create_date'] ?></td>
                    <td><?= $val['care_kb'] ?></td>
                    <td><?= $val['hospital'] ?></td>
                    <td><?= $val['doctor'] ?></td>
                    <td><?= $val['tel1'] ?></td>
                    <td><?= $val['staff_name'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        $(function () {
            // モーダルから選択
            $(".dynamic_modal").find("table button").on("click", function () {

                // 各種データ取得
                var unique_id = $(this).data("unique_id");
                var create_date = $(this).data("create_date");
                var create_user = $(this).data("create_user");
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
                var hospital = $(this).data("hospital");
                var hospital_rece = $(this).data("hospital_rece");
                var address1 = $(this).data("address1");
                var doctor = $(this).data("doctor");
                var tel1 = $(this).data("tel1");
                var tel2 = $(this).data("tel2");
                var fax = $(this).data("fax");
                var status = $(this).data("status");

                $(".set_unique_id").val(unique_id);
                $(".set_create_date").val(create_date);
                $(".set_create_user").val(create_user);
                $(".set_update_day").val(update_day);
                $(".set_update_user").val(update_user);
                $(".set_user_id").val(user_id);
                $(".set_staff_id").val(staff_id);
                $(".set_staff_name").val(staff_name);
                $(".set_direction_start").val(direction_start);
                $(".set_direction_end").val(direction_end);
                $(".set_direction_months").val(direction_months);
                $(".set_plan_day").val(plan_day);
                $(".set_report_day").val(report_day);
                $(".set_care_kb").val(care_kb);
                $(".set_direction_kb").val(direction_kb);
                $(".set_judgement_day").val(judgement_day);
                $(".set_rece_detail").val(rece_detail);
                $(".set_postscript").val(postscript);
                $(".set_attached8").val(attached8);
                $(".set_seriously_child").val(seriously_child);
                $(".set_attached8_detail").val(attached8_detail);
                $(".set_other_station1").val(other_station1);
                $(".set_other_station1_address").val(other_station1_address);
                $(".set_other_station2").val(other_station2);
                $(".set_other_station2_address").val(other_station2_address);
                $(".set_hospital").val(hospital);
                $(".set_hospital_rece").val(hospital_rece);
                $(".set_address1").val(address1);
                $(".set_doctor").val(doctor);
                $(".set_tel1").val(tel1);
                $(".set_tel2").val(tel2);
                $(".set_fax").val(fax);
                $(".set_status").val(status);

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