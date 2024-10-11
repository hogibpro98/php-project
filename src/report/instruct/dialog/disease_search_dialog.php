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

$tgtName = h(filter_input(INPUT_GET, 'tgt_name'));
$tgtFlg = h(filter_input(INPUT_GET, 'tgt_flg'));

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

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

/* -- データ取得 -------------------------------------------- */

/* -- 画面表示データ格納 ---------------------------- */
?>
<div class="modal_disease_search cancel_act cont_user-dup" style="height:450px;width:850px; z-index: 100; margin: 0 auto;">
    <div class="tit">主たる傷病名選択</div>
    <div class="close close_part modal_disease_close">✕<span class="modal_disease_close">閉じる</span></div>
    <div>
        <span class="label_t">フリーワード検索</span>
        <input type="text" class="searchKana search_freeword" value="">
        <button type="button" class="btnSearch">検索</button>
    </div>
    <div class="tab-main-disease_box">
        <!-- 流し込みエリア -->
    </div>
    <script>
        $(function () {
            $(".modal_disease_close").on("click", function () {
//                $(this).parent().parent().remove();
                $(".modal_setting").children().remove();
            });
            
            $(".btnSearch").on("click", function () {

                var tgtName = "<?= $tgtName ?>";
                var tgtFlg = "<?= $tgtFlg ?>";
                var freeword = $(".search_freeword").val();
                var target = $(".tab-main-disease_box");

                $.ajax({
                    async: false,
                    type: "POST",
                    url: "./ajax/search_disease.php",
                    dataType: "text",
                    data: {
                        "freeword": freeword,
                        "tgt_name": tgtName,
                        "tgt_flg": tgtFlg
                    }
                }).done(function (data) {
                    if (data) {
                         // 検索結果の差込
                        $(target).children().remove();
                        $(data).appendTo(target);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
//                    console.log("ajax通信に失敗しました");
//                    console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
//                    console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
//                    console.log("errorThrown    : " + errorThrown.message); // 例外情報
//                    console.log("URL            : " + url);
                });
            });
        });
    </script>
</div>