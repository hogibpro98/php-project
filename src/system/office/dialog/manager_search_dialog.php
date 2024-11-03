<?php
/* ===================================================
 * スタッフ検索モーダル
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

// 反映先ID取得
$tgtSetId = filter_input(INPUT_GET, 'tgt_set_id');

// 反映先ID取得
$tgtSetOtherId = filter_input(INPUT_GET, 'tgt_set_other_id');

// 反映先名取得
$tgtSetName = filter_input(INPUT_GET, 'tgt_set_name');

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

/* -- マスタ関連 --------------------------------------------*/

// スタッフ
$stfList = getStaffList($placeId);
foreach ($stfList as $stfId => $val) {

    $license = $val['license2'];
    if (mb_strpos($license, '普通自動車免許')) {
        $val['drive']  = '〇';
    }
    if (
        mb_strpos($license, '正看護師') !== false
        || mb_strpos($license, '准看護師') !== false
        || mb_strpos($license, '保健師') !== false
        || mb_strpos($license, '助産師') !== false
        || mb_strpos($license, '喀痰吸引等研修(第1号研修)') !== false
        || mb_strpos($license, '喀痰吸引等研修(第2号研修)') !== false
        || mb_strpos($license, '喀痰吸引等研修(第3号研修)') !== false
    ) {
        $val['action'] = '〇';
    }
    if (mb_strpos($license, '精神科訪問看護研修修了')) {
        $val['mental']  = '〇';
    }
    $stfList[$stfId] = $val;
}

/* -- データ取得 --------------------------------------------*/

/* -- その他計画関連 ------------------------------*/

/* -- 画面表示データ格納 ----------------------------*/

$dispData = $stfList;

?>
<div class="manager_modal new_default sched_default displayed_part cancel_act" style="height:600px;width:950px!important;overflow: scroll!important;overflow-y: auto;overscroll-behavior-y: contain;top:60%;">
    <div class="tit">管理者選択</div>
    <div class="modal_close close close_part">✕<span class="modal_close">閉じる</span></div>
    <div>
        <span class="label_t">氏名漢字／カナ</span>
        <input type="text" class="searchKana" value="">
        <button type="button" class="btnDlgSearch">検索</button>
    </div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>従業員コード</th>
                <th>従業員氏名</th>
                <th>従業員氏名</th>
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