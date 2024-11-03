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

// 居宅支援事業所一覧
$where = array();
$where['delete_flg']  = 0;
$target = "office_code, office_name, address, tel, fax";
$orderBy = 'unique_id ASC';
$temp = select('mst_user_office2', '*', $where, $orderBy);
foreach ($temp as $val) {
    $ofcCode = $val['office_code'];
    $val['found_day'] = $val['found_day'] == '0000-00-00' ? null : $val['found_day'];
    $ofc2Data[$ofcCode] = $val;
}

/* -- 画面表示データ格納 ---------------------------- */
?>
<div class="manager_modal new_default sched_default displayed_part cancel_act" style="height:600px;width:950px!important;overflow: scroll!important;overflow-y: auto;overscroll-behavior-y: contain;top:60%;">
    <div class="tit">居宅支援事業所選択</div>
    <div class="modal_close close close_part">✕<span class="modal_close">閉じる</span></div>
    <div>
        <span class="label_t">事業所コード／事業所名</span>
        <input type="text" class="searchKana" value="">
        <button type="button" class="btnDlgSearch">検索</button>
    </div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>事業所コード</th>
                <th>事業所名</th>
                <th>住所</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dispData as $stfId => $val) : ?>
                <tr>
                    <td>
                        <button class="modal_selected" type="button" 
                                data-stf_id="<?= $stfId ?>" 
                                data-other_id="<?= $val['staff_id'] ?>" 
                                data-stf_name="<?= $val['last_name'] . $val['first_name'] ?>" 
                                data-tgt_set_id="<?= $tgtSetId ?>" 
                                data-tgt_set_other_id="<?= $tgtSetOtherId ?>" 
                                data-tgt_set_name="<?= $tgtSetName ?>" 
                                >選択</button></td>
                    <td><?= $val['staff_id'] ?></td>
                    <td class="tgtSearchVal"><?= $val['name'] ?></td>
                    <td class="tgtSearchVal"><?= $val['last_kana'] . $val['first_kana'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        $(function() {
            // モーダルから選択
            $(".manager_modal").find("table button").on("click", function() {
                // 各種データ取得
                var stf_id = $(this).data("stf_id");
                var other_id = $(this).data("other_id");
                var stf_name = $(this).data("stf_name");

                var tgtSetId = $(this).data("tgt_set_id");
                var tgtSetOtherId  = $(this).data("tgt_set_other_id");
                var tgtSetName = $(this).data("tgt_set_name");

                $("." + tgtSetId).val(stf_id);
                $("." + tgtSetOtherId).val(other_id);
                $("." + tgtSetName).val(stf_name);

                // windowを閉じる
                $(".manager_modal").remove();
            });
            // ダイアログクローズ
            $(".modal_close").on("click", function() {
                // windowを閉じる
                $(".manager_modal").remove();
            });
            
            
        // 氏名（漢字／カナ）検索
        $(".btnDlgSearch").on("click", function () {
            var kana = $(".searchKana").val();
            if (kana) {
                // 一旦絞込を解除する
                $(".tgtSearchVal").each(function () {
                    $(this).closest('tr').hide();
                });
                // 検索にHITしなかった行を非表示する
                $(".tgtSearchVal").each(function () {
                    var tgtKana = $(this).first().text();
                    if (tgtKana && tgtKana.includes(kana)) {
                        $(this).closest('tr').show();
                    }
                });
            } else {
                // 検索文字列が無い場合は、全て表示する
                $(".tgtSearchVal").each(function () {
                    $(this).closest('tr').show();
                });
            }
        });
    });
    </script>
</div>