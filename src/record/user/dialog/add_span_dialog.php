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
$tgtData  = array();

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/* -- 検索用パラメータ --------------------------------------- */

// 利用者ID
$userId = filter_input(INPUT_GET, 'id');

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

// 加算マスタ(期間指定のみ)
$where = array();
$where['delete_flg'] = 0;
$where['span_flg']   = 1;
$temp = select('mst_add', '*', $where);
foreach ($temp as $val) {
    $type = $val['type'];
    $tgtId = $val['unique_id'];
    $addSpnMst[$type][$tgtId] = $val['name'];
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
$where['user_id']    = $userId;
$temp = select('dat_user_record_add', '*', $where);
foreach ($temp as $val) {

    // レコードID
    $tgtId = $val['unique_id'];

    // 開始・終了時刻
    $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
    $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

    // 格納
    $tgtData[$tgtId] = $val;
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
    <div class="sched_tit">期間指定加算 編集</div>
    
    <!-- 加減算表示エリア:start -->
    <div class="add_sub content_add">
        <p class="mid">加減算</p>
        <ol id="add_root">
            <?php $num = 99; ?>
            <?php if (empty($dispData)) : ?>
                <li>
                    <select id="add0" name="upAddSpn[0][add_id]" class="addList" onchange="calcDay(0)">
                        <option value="">選択してください</option>
                        <?php foreach ($addSpnMst as $type => $addSpnMst2) : ?>
                            <?php foreach ($addSpnMst2 as $tgtId => $val) : ?>
                                <option value="<?= $tgtId ?>" class="cngService" data-value="<?= $val ?>" data-service_name="<?= $type ?>"><?= $type . '　：　' . $val ?></option>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" id="stDay0" name="upAddSpn[0][start_day]" value="" onchange="calcDay(0)">
                    <small>～</small>
                    <input type="date" id="edDay0" name="upAddSpn[0][end_day]" value="">
                    <p class="list_delete row_delete">Delete</p>
                </li>
            <?php else : ?>
            <?php foreach ($dispData as $addId => $addVal) : ?>
                <li>
                <?php $num++; ?>
                <input type="hidden" name="upAddSpn[<?= $addId ?>][unique_id]" value="<?= $addId ?>">
                    <select id="add<?= $num ?>" name="upAddSpn[<?= $addId ?>][add_id]" class="addList" onchange="calcDay(<?= $num ?>)">
                        <option value="">選択してください</option>
                        <?php foreach ($addSpnMst as $type => $addSpnMst2) : ?>
                            <?php foreach ($addSpnMst2 as $tgtId => $val) : ?>
                                <?php $select = $addVal['add_id'] === $tgtId ? ' selected' : ''; ?>
                                <option value="<?= $tgtId ?>" class="cngService" data-value="<?= $val ?>" data-service_name="<?= $type ?>" <?= $select ?>><?= $type . '　：　' . $val ?></option>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" id="stDay<?= $num ?>" name="upAddSpn[<?= $addId ?>][start_day]" value="<?= isset($addVal['start_day']) ? $addVal['start_day'] : '' ?>" onchange="calcDay(<?= $num ?>)">
                    <small>～</small>
                    <input type="date" id="edDay<?= $num ?>" name="upAddSpn[<?= $addId ?>][end_day]" value="<?= isset($addVal['end_day']) ? $addVal['end_day'] : '' ?>">
                    <p class="list_delete row_delete">Delete</p>
                </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ol>
        <p class="btn_append_add add_btn add_sub_btn">+</p>
    </div>
    <!-- 加減算表示エリア:end -->

    <!-- 下部ボタンエリア:start-->
    <div class="s_constrols">
        <p>
            <span class="modal_close btn cancel">キャンセル</span>
        </p>
        <p>
            <button type="submit" name="btnEntrySpn" value="<?= $userId ?>" class="btn save" style="width:60px;height:37px;font-size: 15px;">保存</button>
        </p>
    </div>
    <!-- 下部ボタンエリア:end-->
    
    <script>
        var addNewCnt = 1;
        window.addEventListener("load", function () {
            setValidate();
        });
        $(function () {
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
                newRow += '  <select id="add' + addNewCnt + '" name="upAddSpn[' + addNewCnt + '][add_id]" class="addList"> onchange="calcDay(' + addNewCnt + ')"';
                newRow += ' <option value="">選択してください</option>';
<?php foreach ($addSpnMst as $type => $addSpnMst2) : ?>
    <?php foreach ($addSpnMst2 as $tgtId => $val) : ?>
                newRow += ' <option value="<?= $tgtId ?>" class="cngService" data-value="<?= $val ?>" data-service_name="<?= $type ?>"><?= $type . '　：　' . $val ?></option>';
    <?php endforeach; ?>
<?php endforeach; ?>
                newRow += '  </select>';
                newRow += '  <input id="stDay' + addNewCnt + '" type="date" name="upAddSpn[' + addNewCnt + '][start_day]" value="" onchange="calcDay(' + addNewCnt + ')">';
                newRow += '  <small>～</small>';
                newRow += '  <input id="edDay' + addNewCnt + '" type="date" name="upAddSpn[' + addNewCnt + '][end_day]" value="" onchange="calcDay(' + addNewCnt + ')">';
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

            // ダイアログ内はenterキー押下でのform送信を無効化
            $("input").on("keydown", function(e) {
                if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                    return false;
                } else {
                    return true;
                }
            });
        });
        function calcDay(num){
            var addId = document.getElementById('add' + num).value;
            var stDay = $('#stDay' + num).val();
            
            // 初期加算の場合
            if (addId == 'add00000110' || addId == 'add00000148' || addId == 'add00000186' || addId == 'add00000186'){
                if (stDay != "" && stDay != "0000-00-00"){
                    
                    // 開始日 y,m,d
                    var dt = new Date(stDay);
                    var y = dt.getFullYear();
                    var m = ('00' + (dt.getMonth()+1)).slice(-2);
                    var d = ('00' + dt.getDate()).slice(-2);
                    
                    // 30日後の計算
                    var dtObj = calcDate(30,y,m,d);
                    var Y = dtObj.getFullYear();
                    var M = ('00' + (dtObj.getMonth()+1)).slice(-2);
                    var D = ('00' + dtObj.getDate()).slice(-2);
                    var edDay = Y + '-' + M  + '-' + D
                    
                    // 終了日の書き換え
                    $('#edDay' + num).val(edDay);
                }
            }
        }
        function calcDate(n, y,m,d){
            var n = parseInt(n);
            var nmsec = n * 1000 * 60 * 60 * 24; // 1日のミリ秒
            var msec = (new Date(""+y+"/"+m+"/"+d)).getTime();
            return new Date(msec + nmsec );
        }
    </script>
</div>