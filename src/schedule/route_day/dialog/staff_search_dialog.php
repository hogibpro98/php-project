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

// 反映先CD取得
$tgtSetCd = filter_input(INPUT_GET, 'tgt_set_cd');

// 反映先名取得
$tgtSetName = filter_input(INPUT_GET, 'tgt_set_name');

// 反映先備考取得
$tgtSetRemarks = filter_input(INPUT_GET, 'tgt_set_remarks');

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
    if (!empty($val['driving_license'])) {
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
<div class="cont_staff_modal cancel_act">
    <div class="tit">スタッフ選択</div>
    <div class="modal_close close close_part">✕<span class="modal_close">閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>従業員氏名</th>
                <th>主たる資格</th>
                <th>自動車運転<br>可否</th>
                <th>特定行為<br>可否</th>
                <th>精神科<br>訪問看護</th>
                <th>備考</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dispData as $stfId => $val) : ?>
                <tr>
                    <td>
                        <button class="modal_selected" type="button" 
                                data-stf_id="<?= $stfId ?>" 
                                data-stf_cd="<?= $val['staff_id'] ?>" 
                                data-stf_name="<?= $val['last_name'] . $val['first_name'] ?>" 
                                data-tgt_set_id="<?= $tgtSetId ?>" 
                                data-tgt_set_id="<?= $tgtSetCd ?>" 
                                data-tgt_set_name="<?= $tgtSetName ?>" 
                                data-tgt_set_remarks="<?= $tgtSetRemarks ?>" 
                                data-remarks="<?= $val['remarks'] ?>"
                                >選択</button></td>
                    <td><?= $val['name'] ?></td>
                    <td><?= $val['license1'] ?></td>
                    <td><?php isset($val['drive']) ? $val['drive'] : null; ?></td>
                    <td><?php isset($val['action']) ? $val['action'] : null; ?></td>
                    <td><?php isset($val['mental']) ? $val['mental'] : null; ?></td>
                    <td><?= $val['remarks'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        $(function() {
            // モーダルから選択
            $(".cont_staff_modal").find("table button").on("click", function() {
                // 各種データ取得
                var stf_id = $(this).data("stf_id");
                var stf_cd = $(this).data("stf_cd");
                var stf_name = $(this).data("stf_name");
                var remarks = $(this).data("remarks");

                var tgtSetId = $(this).data("tgt_set_id");
                var tgtSetCd = $(this).data("tgt_set_cd");
                var tgtSetName = $(this).data("tgt_set_name");
                var tgtSetRemarks = $(this).data("tgt_set_remarks");

                $("." + tgtSetId).val(stf_id);
                $("." + tgtSetCd).val(stf_cd);
                $("." + tgtSetName).val(stf_name);
                $("." + tgtSetRemarks).val(remarks);

                // windowを閉じる
                $(".cont_staff_modal").remove();
            });
            // ダイアログクローズ
            $(".modal_close").on("click", function() {
                // windowを閉じる
                $(".cont_staff_modal").remove();
            });
        });
    </script>
</div>