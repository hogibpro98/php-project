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
$dispData = array();
$tgtData = array();

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

// 利用者(外部ID)取得
$userId = filter_input(INPUT_GET, 'user_id');

$userOfcId = filter_input(INPUT_GET, 'user_office_id');

$startDay = filter_input(INPUT_GET, 'start_day');
$endDay = filter_input(INPUT_GET, 'end_day');
$officeId = filter_input(INPUT_GET, 'office_id');
$officeName = filter_input(INPUT_GET, 'office_name');
$uniqueId = filter_input(INPUT_GET, 'id');
$mode = filter_input(INPUT_GET, 'mode');

$userId = !empty($userId) ? $userId : null;
$userOfcId = !empty($userOfcId) ? $userOfcId : null;
$startDay = !empty($startDay) ? formatDateTime($startDay, "Y-m-d") : null;
$endDay = !empty($endDay) ? formatDateTime($endDay, "Y-m-d") : null;
$officeName = !empty($officeName) ? $officeName : null;
$uniqueId = !empty($uniqueId) ? $uniqueId : null;

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

/* -- データ取得 -------------------------------------------- */

// 拠点
$plcList = array();
$where = array();
$where['delete_flg'] = 0;
$orderBy = 'unique_id ASC';
$temp = select('mst_place', 'unique_id,name', $where, $orderBy);
foreach ($temp as $val) {
    $plcList[$val['unique_id']] = $val['name'];
}

// 契約事業所
$ofcList = array();
$ofcInfo = array();
$where = array();
$where['delete_flg'] = 0;
$orderBy = 'unique_id ASC';
$temp = select('mst_office', '*', $where, $orderBy);
foreach ($temp as $val) {
    $val['place_name'] = isset($plcList[$val['place_id']]) ? $plcList[$val['place_id']] : null;
    $ofcList[$val['unique_id']] = $val;
}

// 事業所番号取得
$officeNo = !empty($ofcList[$officeId]['office_no']) ? $ofcList[$officeId]['office_no'] : null;

// 契約事業所情報取得
//$dispData = initTable('mst_user_office1');
//$ofcList = array();
//$where = array();
//$where['delete_flg'] = 0;
//$where['unique_id'] = $userOfcId;
//$orderBy = 'unique_id ASC';
//$temp = select('mst_user_office1', '*', $where, $orderBy);
//foreach ($temp as $val) {
//    $dispData = $val;
//}

/* -- 画面表示データ格納 ---------------------------- */
?>
<div class="dynamic_modal modal_office3 cancel_act cont_user-dup" style="height:600px;z-index: 3;">
    <div class="tit">契約事業所登録</div>
    <div class="close close_part modal_close">✕<span class="modal_close">閉じる</span></div>
    <div class="tab-main-box">
        <table>
            <tr class="tr2" style="border:0;">
                <td>
                    <div>
                        <span class="n_search office_search3" data-tgt_id="<?= $userId ?>">Search</span>
                        <input type="hidden" name="upOfc3[<?= $userId ?>][unique_id]" id="user_office_id3" value="<?= $userOfcId ?>" class="user_office_id3" maxlength="8" pattern="^[a-zA-Z0-9]+$">
                        <input type="hidden" name="upOfc3[<?= $userId ?>][office_id]" id="office_id3" value="<?= $officeId ?>" class="office_id3" maxlength="8" pattern="^[a-zA-Z0-9]+$">
                        <input type="text" name="upOfc3[<?= $userId ?>][office_no]" id="office_no3" value="<?= $officeNo ?>" class="office_no3" maxlength="8" pattern="^[a-zA-Z0-9]+$">
                        <input type="text" name="upOfc3[<?= $userId ?>][office_name]" id="office_name3" value="<?= $officeName ?>" class="tgt-name2_<?= $userId ?> bg-gray3 office_name3" readonly>
                    </div>
                </td>
            </tr>
            <tr style="border:0;">
                <td>
                    <div>
                        <span class="label_t">開始/終了</span>
                        <input type="date" name="upOfc3[<?= $userId ?>][start_day]" class="date_start3" value="<?= $startDay ?>" >
                        <small>～</small>
                        <input type="date" name="upOfc3[<?= $userId ?>][end_day]" class="date_end3 " value="<?= $endDay ?>">
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="cont_office3 cancel_act" style="display:none;">
        <div class="tit">事業所選択</div>
        <div class="close close_part close_sub">✕<span class="close_sub">閉じる</span></div>
        <input type="hidden" id="tgt-id" value="tgt-id">
        <input type="hidden" id="tgt-no" value="tgt-no">
        <input type="hidden" id="tgt-name1" value="tgt-name1">
        <input type="hidden" id="tgt-name2" value="tgt-name2">
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>拠点名</th>
                    <th>事業所名</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ofcList as $ofcId => $val): ?>
                    <tr>
                        <td><button type="button" class="select_office3" data-id="<?= $val['unique_id'] ?>" data-office_id="<?= $val['office_id'] ?>"  data-number="<?= $val['office_no'] ?>" data-name1="<?= $val['place_name'] ?>" data-name2="<?= $val['name'] ?>">選択</button></td>
                        <td><?= $val['place_name'] ?></td>
                        <td><?= $val['name'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="modal_office2_btn">
        <button type="button" name="" class="btn_add_office_his" value="">この内容で追加する</button>
    </div>
    <script>
        var dateStart3 = "";
        var dateEnd3 = "";
        var officeId3 = "";
        var userOfficeId3 = "";
        var officeNo3 = "";
        var officeName3 = "";
        var placeName3 = "";
        var editMode = "<?= $mode ?>";

        $(function () {
            // ダイアログクローズ
            $(".modal_close").on("click", function () {
                // windowを閉じる
                $(".dynamic_modal").remove();
            });

            // 選択ボタン押下
            $('.select_office3').on('click', function () {

                userOfficeId3 = $(this).data('id');
                officeId3 = $(this).data('office_id');
                officeNo3 = $(this).data('number');
                placeName3 = $(this).data('name1');
                officeName3 = $(this).data('name2');

                $('.office_id3').val(officeId3);
                $('.office_no3').val(officeNo3);
                $('.office_name3').val(officeName3);

                $('.cont_office3').hide();
            });

            $(".date_start3").on("change", function () {
                dateStart3 = $(this).val();
            });

            $(".date_end3").on("change", function () {
                dateEnd3 = $(this).val();
            });

            // 追加ボタン押下

            $('.btn_add_office_his').off('click');
            $('.btn_add_office_his').on('click', function () {

                var uniqueId = $(document).find('.user_office_id3').val();
                var officeId = $(document).find('.office_id3').val();
                var officeNo = $(document).find('.office_no3').val();
                var dateStart3 = $(document).find('.date_start3').val();
                var dateEnd3 = $(document).find('.date_end3').val();
                var officeName3 = $(document).find('.office_name3').val();

                if (!officeName3) {
                    alert("事業所を選択して下さい。");
                    return false;
                }
                if (!dateStart3) {
                    alert("開始日を入力して下さい。");
                    return false;
                }
                if (dateEnd3 && dateStart3 > dateEnd3) {
                    alert("終了日は開始日より後に設定してください。");
                    return false;
                }

                if (editMode) {
                    // 既存のレコードを削除する             
                    var history = $('.office_history').find('.history_' + uniqueId);
                    if (history) {
                        history.remove();
                    }

                }
                // 登録処理実行
                addHistory(uniqueId, officeNo, dateStart3, dateEnd3, officeName3, officeId);
                
                $(".hist_list").toggle(true);

                // windowを閉じる
                $(".dynamic_modal").remove();
            });

            $(".office_search3").on('click', function () {
                $('.cont_office3').show();
            });

            $(".close_sub").on('click', function () {
                $('.cont_office3').hide();
            });

//            $('.is-current').on('click', function () {
//                $(this).hide();
//            });
        });

//         $('form').submit(addHistory);

        var keyId = -1;
        keyId = $(".office_history").find("li").length;
        function addHistory(uniqueId, officeNo, startDay, endDay, officeName, officeId) {
           
            var addCont = '';
            addCont += '<li style="display:flex;">';
            addCont += '    <span style="width:120px">';
            addCont += '        <input type="hidden" name="upOfc1[' + keyId + '][unique_id]" value="' + uniqueId + '" >';
            addCont += '        <input type="hidden" name="upOfc1[' + keyId + '][start_day]" value="' + startDay + '" >';
            addCont += '        <input type="hidden" name="upOfc1[' + keyId + '][end_day]" value="' + endDay + '" >';
            addCont += '        <input type="hidden" name="upOfc1[' + keyId + '][office_id]" value="' + officeId + '" >';
            addCont += '        <input type="hidden" name="upOfc1[' + keyId + '][office_name]" value="' + officeName + '" >';
            addCont += '        ' + startDay + '<br/>〜' + endDay;
            addCont += '    </span>';
            addCont += '    <div style="width:200px">' + officeName + '</div>';
//            addCont += '    <button type="button" class="btn-edit office office_edit modal_open"';
//            addCont += '            name="" value=""';
//            addCont += '            data-url="/user/edit/dialog/office3.php?user_office_id='+uniqueId+'&office_name=' + officeName +'&office_id=' + officeId +'&mode=edit"';
//            addCont += '            data-id="' + uniqueId + '"';
//            addCont += '            data-user_office_id="' + uniqueId + '"';
//            addCont += '            data-office_id="' + officeId + '"';
//            addCont += '            data-office_no="' + officeNo + '"';
//            addCont += '            data-start_day="' + startDay + '"';
//            addCont += '            data-end_day="' + endDay + '"';
//            addCont += '            data-office_name="' + officeName + '"';
//            addCont += '            data-dialog_name="dynamic_modal">編集</button>';
            addCont += '    <button type="button" class="btn-del row_delete" name="btnDelOffice" value="' + keyId + '" style="margin-left:10px;">削除</button>';
            addCont += '</li>';
            keyId++;
            $('.office_history').prepend(addCont);
            $.getScript("/common/js/common.js");
        }
    </script>    
</div>