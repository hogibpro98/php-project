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
$tgtData = initTable('dat_root_config');
/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/* -- 検索用パラメータ --------------------------------------- */

// ルートコンフィグID
$rootCfgId = filter_input(INPUT_GET, 'id');

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

/* -- ルートコンフィグ取得 --------------------------------- */
$where = array();
$where['delete_flg'] = 0;
$where['unique_id'] = $rootCfgId;
$temp = select('dat_root_config', '*', $where);
foreach ($temp as $val) {
    // 格納
    $tgtData = $val;
}

/* -- 画面表示データ格納 ---------------------------- */
$dispData = $tgtData;
?>
<div class="modal sched_details add_root cancel_act" style="left:40%; width:550px; display:block;">
    <div class="close close_part modal_close">✕<span class="modal_close">閉じる</span></div>
    <input type="hidden" name="upRoot[unique_id]" value="<?= $dispData['unique_id'] ?>">
    <div class="sched_tit">ルート編集</div>
    <div class="s_detail">
        <div class="box1">
            <p class="mid">ルート名</p>
           <p><input type="text" name="upRoot[name]" value="<?= $dispData['name'] ?>"></p>
        </div>
        <div class="box1">
            <p class="mid">ルート<br>種類</p>
            <p>
                <span class="type"><input type="checkbox" name="upRoot[root_type][]" value="看多機" id="type1" <?= mb_strpos($dispData['root_type'], '看多機') !== false ? 'checked' : '' ?>><label for="type1">看多機</label></span>
                <span class="type"><input type="checkbox" name="upRoot[root_type][]" value="訪問看護" id="type2" <?= mb_strpos($dispData['root_type'], '訪問看護') !== false ? 'checked' : '' ?>><label for="type2">訪問看護</label></span>
            </p>
        </div>
    </div>
    <div class="s_constrols">
        <p><span class="btn cancel modal_close">キャンセル</span></p>
        <p>
            <button name="btnDelRoot" class="btn trash" value="<?= $dispData['unique_id'] ?>">削除</button>
            <button name="btnEntryRoot" class="btn save display_rr" value="true">保存</button>
        </p>
    </div>
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