<?php
/* ===================================================
 * スタッフ編集モーダル
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
$upAry = array();

$selHour = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
$selMinutes = ['00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55'];

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/* -- 検索用パラメータ --------------------------------------- */

// 予定ID（スタッフ）
$stfPlanId = filter_input(INPUT_GET, 'id');

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

/* -- データ取得 -------------------------------------------- */

/* -- スタッフ予定 --------------------------------- */
$where = array();
$where['delete_flg'] = 0;
$where['unique_id'] = $stfPlanId;
$temp = select('dat_staff_schedule', '*', $where);
foreach ($temp as $val) {

    // 曜日、開始・終了時刻、更新者名
    $val['week_name'] = !empty($val['week']) ? $weekAry[$val['week']] : null;
    $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
    $val['end_time'] = formatDateTime($val['end_time'], 'H:i');
    $val['update_name'] = getStaffName($val['update_user']);

    // 格納
    $tgtData = $val;
}

/* -- 画面表示データ格納 ---------------------------- */
$dispData = $tgtData;
?>
<div class="modal sched_details add_memo_details rt_memo cancel_act" style="left:40%; width:550px; display:block;">
    <div class="close close_part modal_close">✕<span class="modal_close">閉じる</span></div>
    <?php $mainPrefix = "upStf"; ?>
    <input type="hidden" name="<?= $mainPrefix ?>[unique_id]" value="<?= $dispData['unique_id'] ?>">
    <input type="hidden" name="<?= $mainPrefix ?>[staff_id]" value="<?= $dispData['staff_id'] ?>">
    <div class="sched_tit">従業員スケジュール編集</div>
    <div class="s_detail">
        <div class="box1">
            <p class="mid">ルート</p>
            <input type="hidden" name="<?= $mainPrefix ?>[root_id]" value="<?= $dispData['root_id'] ?>">
            <input type="text" name="<?= $mainPrefix ?>[root_name]" value="<?= $dispData['root_name'] ?>" style="width: 300px; text-align: left; background-color: lightgray;" readonly>
        </div>
        <div class="box1">
            <p class="mid">曜日/時刻</p>
            <p>
                <select name="<?= $mainPrefix ?>[week]" class="s_month">
                    <option <?= $dispData['week'] == '1' ? 'selected' : '' ?> value="1">月</option>
                    <option <?= $dispData['week'] == '2' ? 'selected' : '' ?> value="2">火</option>
                    <option <?= $dispData['week'] == '3' ? 'selected' : '' ?> value="3">水</option>
                    <option <?= $dispData['week'] == '4' ? 'selected' : '' ?> value="4">木</option>
                    <option <?= $dispData['week'] == '5' ? 'selected' : '' ?> value="5">金</option>
                    <option <?= $dispData['week'] == '6' ? 'selected' : '' ?> value="6">土</option>
                    <option <?= $dispData['week'] == '0' ? 'selected' : '' ?> value="0">日</option>
                </select>
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
                </select>
            </p>
        </div>
        <div class="box1">
            <p class="mid"></p>
            <p class="month_list">
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第1週" id="week1" style="margin-left:0px;" <?= strpos($dispData['week_num'], '第1週') !== false ? 'checked' : '' ?>></label>第1週</label></span>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第2週" id="week2" style="margin-left:10px;" <?= strpos($dispData['week_num'], '第2週') !== false ? 'checked' : '' ?>></label>第2週</label></span>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第3週" id="week3" style="margin-left:10px;" <?= strpos($dispData['week_num'], '第3週') !== false ? 'checked' : '' ?>></label>第3週</label></span>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第4週" id="week4" style="margin-left:10px;" <?= strpos($dispData['week_num'], '第4週') !== false ? 'checked' : '' ?>></label>第4週</label></span>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第5週" id="week5" style="margin-left:10px;" <?= strpos($dispData['week_num'], '第5週') !== false ? 'checked' : '' ?>></label>第5週</label></span>
                <span><input type="checkbox" name="<?= $mainPrefix ?>[week_num][]" value="第6週" id="week6" style="margin-left:10px;" <?= strpos($dispData['week_num'], '第6週') !== false ? 'checked' : '' ?>></label>第6週</label></span>
            </p>
        </div>
        <div class="box1">
            <p class="mid">パーツ</p>
            <input type="text" name="<?= $mainPrefix ?>[work]" value="<?= $dispData['work'] ?>" style="width: 300px; text-align: left; background-color: lightgray;" readonly>
        </div>
        <div class="box1">
            <p class="mid">メモ</p>
            <p><textarea name="<?= $mainPrefix ?>[memo]" style="height: 54px; width: 300px;" value="<?= $dispData['memo'] ?>"><?= $dispData['memo'] ?></textarea></p>
        </div>
    </div>
    <div class="s_add_sub">

    </div>
    <div class="s_constrols">
        <p><span class="btn cancel modal_close">キャンセル</span></p>
        <p>
            <button name="btnDelStf" value="<?= $dispData['unique_id'] ?>" class="btn trash">削除</button>
            <button type="button" class="btn duplicate"
             	data-url="/schedule/route_edit/dialog/staff_dupli_dialog.php?id=<?= $stfPlanId ?>" 
             	data-dialog_name="modal"
             >複製</button>
            <button type="submit" name="btnEntryStf" value="<?= $dispData['unique_id'] ?>" class="btn save">保存</button>
        </p>
    </div>
    <div class="update">
        最終更新:
        <span class="time"><?= $dispData['update_date'] ?></span>
        <span class="person"><?= $dispData['update_name'] ?></span>
    </div>
    <script>
        $(function () {
            // ダイアログクローズ
            $(".modal_close").on("click", function () {
                // windowを閉じる
                $(".modal").remove();
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
        });
    </script>
</div>
