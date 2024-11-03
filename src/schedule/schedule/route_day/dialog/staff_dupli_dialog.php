<?php
/* ===================================================
 * スタッフ複製モーダル
 * ===================================================
 */

/* ===================================================
 * 初期処理
 * ===================================================
 */

/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

/*--変数定義-------------------------------------------------*/
// 初期化
$err      = array();
$_SESSION['notice']['error'] = array();
$dispData = array();
$tgtData  = array();
$upAry    = array();

$selHour = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
$selMinutes = ['00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55'];

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// 拠点ID
$placeId = filter_input(INPUT_GET, 'place');
if (!$placeId) {
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
}

// 予定ID（スタッフ）
$stfPlanId = filter_input(INPUT_GET, 'id');

/*-- 更新用パラメータ ---------------------------------------*/

/* ===================================================
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ----------------------------------------*/

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */

/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- データ取得 --------------------------------------------*/

/* -- スタッフ予定 ---------------------------------*/
$where = array();
$where['delete_flg'] = 0;
$where['unique_id']  = $stfPlanId;
$temp = select('dat_staff_plan', '*', $where);
foreach ($temp as $val) {

    // 曜日、開始・終了時刻、更新者名
    $val['start_time']  = formatDateTime($val['start_time'], 'H:i');
    $val['end_time']    = formatDateTime($val['end_time'], 'H:i');
    $val['update_name'] = getStaffName($val['update_user']);

    // 格納
    $tgtData = $val;
}

/* -- 画面表示データ格納 ----------------------------*/
$dispData = $tgtData;
?>

<div class="modal sched_details add_memo_details rt_memo cancel_act" style="left:40%; width:550px;display:block;">
    <div class="close close_part modal_close">✕<span class="modal_close">閉じる</span></div>
    <?php $mainPrefix = "upStf"; ?>
    <div class="sched_tit">従業員スケジュール複製</div>
    <div class="fukusei_stat">複製中</div>
    <div class="s_detail">
        <div class="box1">
            <p class="mid">ルート</p>
            <input type="hidden" name="<?= $mainPrefix ?>[root_id]" value="<?= $dispData['root_id'] ?>">
            <p name="<?= $mainPrefix ?>[root_name]"><?= $dispData['root_name'] ?></p>
        </div>
        <div class="box1">
            <p class="mid">日付/時刻</p>
            <p><input type="date" name="<?= $mainPrefix ?>[target_day]" value="<?= $dispData['target_day'] ?>"></p>
            <p class="m_time">
                <select name="<?= $mainPrefix ?>[start_time_h]">
                    <?php foreach ($selHour as $val) : ?>
                        <?php $selected = strpos($dispData['start_time'], $val . ":") !== false ? ' selected' : ""; ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
                <small>：</small>
                <select name="<?= $mainPrefix ?>[start_time_m]">
                    <?php foreach ($selMinutes as $val) : ?>
                        <?php $selected = strpos($dispData['start_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
                <small>～</small>
                <select name="<?= $mainPrefix ?>[end_time_h]">
                    <?php foreach ($selHour as $val) : ?>
                        <?php $selected = strpos($dispData['end_time'], $val . ":") !== false ? ' selected' : ""; ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
                <small>：</small>
                <select name="<?= $mainPrefix ?>[end_time_m]">
                    <?php foreach ($selMinutes as $val) : ?>
                        <?php $selected = strpos($dispData['end_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>            </p>
        </div>
        <div class="box1">
            <p class="mid">パーツ</p>
            <input type="text" name="<?= $mainPrefix ?>[work]" value="<?= $dispData['work'] ?>" style="width: 300px; text-align: left; background-color: lightgray;" readonly>
        </div>
        <div class="box1">
            <p class="mid">メモ</p>
            <p><textarea name="<?= $mainPrefix ?>[memo]" style="height: 54px; width: 300px;"><?= $dispData['memo'] ?></textarea></p>
        </div>
    </div>
    <div class="s_add_sub">

    </div>
    <div class="s_constrols">
        <p><span class="btn cancel modal_close">キャンセル</span></p>
        <p>
            <button name="btnEntryStf" class="btn save" value="dupli">保存</button>
        </p>
    </div>
    <div class="update">
        最終更新:
        <span class="time"><?= $dispData['update_date'] ?></span>
        <span class="person"><?= $dispData['update_name'] ?></span>
    </div>
<script>
    $(function() {
        // ダイアログクローズ
        $(".modal_close").on("click", function() {
            // windowを閉じる
            $(".modal").remove();
        });
    });
    </script>
</div>