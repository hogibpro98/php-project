<?php
/* ===================================================
 * 実績確定（利用者）モーダル
 * ===================================================
 */

/* ===================================================
 * 初期処理
 * ===================================================
 */

/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

/*--変数定義-------------------------------------------------*/

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// 予定サービスID
$planId = filter_input(INPUT_GET, 'id');

/* ===================================================
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */

/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

?>
<div class="dynamic_modal msg_box">
    <style>
        .msg_box-exec {
            background: #FF9900;
            border: 1px solid #FF9900;
            color: #fff;
            right: 10px;
            padding: auto;
        }
    </style>
    <div class="msg_box-tit">ワンクリック実績化</div>
    <div class="msg_box-cont">実績化を行いますか？<br>(予定データがそのまま実績データに書き込まれます。）</div>
    <div class="msg_box-btn" style="position:fixed;">
        <div style="text-align: center;">
            <button type="button" class="dynamic_modal_close msg_box-cancel" style="padding:5px;height:38px;margin:15px 0px 15px 10px;">キャンセル</button>
            <button type="submit" name="btnFixUser" class="btn msg_box-exec" value="<?= $planId ?>" style="padding:5px;width:80px;height:38px;font-size:15px">実行</button>
        </div>
    </div>
    <script>
        $(function() {
            // ダイアログクローズ
            $(".dynamic_modal_close").on("click", function() {
                $(".dynamic_modal").remove();
            });
        });
    </script>
</div>