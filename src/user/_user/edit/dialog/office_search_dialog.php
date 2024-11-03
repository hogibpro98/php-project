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
$dispData['standard'] = array();
$dispData['standard']['prefecture'] = '東京都';
$tgtData = array();

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/* ===================================================
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */
/* -- マスタ関連 -------------------------------------------- */
$where = array();
$where['delete_flg'] = 0;
$target = '*';
$orderBy = "prefecture_id ASC";
$temp = select('mst_area', $target, $where, $orderBy);
foreach ($temp as $val) {
    $pref = $val['prefecture_name'];
    $city = $val['city_name'];
    $areaMst[$pref][$city] = true;
}

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
<div class="modal_office_search cancel_act cont_user-dup" style="height:450px;width:850px; z-index: 100;">
    <div class="tit">居宅支援事業所選択</div>
    <div class="close close_part modal_office_close">✕<span class="modal_office_close">閉じる</span></div>
    <div>
        <span class="label_t">都道府県</span>
        <select name="upAry[prefecture]" id="prefecture" class="f-keyVal search_prefecture">
            <?php foreach ($areaMst as $pref => $areaMst2): ?>
                <?php $select = $pref === $dispData['standard']['prefecture'] ? ' selected' : null; ?>
                <option value="<?= $pref ?>"<?= $select ?>><?= $pref ?></option>
            <?php endforeach; ?>
        </select>
        <span class="label_t">フリーワード検索</span>
        <input type="text" class="searchKana search_freeword" value="">
        <button type="button" class="btnSearch">検索</button>
    </div>
    <div class="tab-main-office_box">
        <!-- 流し込みエリア -->
    </div>
    <script>
        $(function () {
            $(".modal_office_close").on("click", function () {
                $(this).parent().parent().remove();
            });
            
            $(".btnSearch").on("click", function () {

                var prefecture = $(".search_prefecture").val();
                var freeword = $(".search_freeword").val();
                var target = $(".tab-main-office_box");

                $.ajax({
                    async: false,
                    type: "POST",
                    url: "./ajax/search_office.php",
                    dataType: "text",
                    data: {
                        "prefecture": prefecture,
                        "freeword": freeword
                    }
                }).done(function (data) {
                    if (data) {
                         // 検索結果の差込
                        $(target).children().remove();
                        $(data).appendTo(target);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log("ajax通信に失敗しました");
                    console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                    console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                    console.log("errorThrown    : " + errorThrown.message); // 例外情報
                    console.log("URL            : " + url);
                });
            });
        });
    </script>
</div>