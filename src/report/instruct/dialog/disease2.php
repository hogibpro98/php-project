<?php
/* =================================================== 
 * 主たる傷病名動的モーダル
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
$diseaseList = array();
$_SESSION['notice']['error'] = array();

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/* -- 検索用パラメータ --------------------------------------- */

// キーワード
$keyword = filter_input(INPUT_GET, 'keyword');
$setName = filter_input(INPUT_GET, 'tgt_name');
$setFlg  = filter_input(INPUT_GET, 'tgt_flg');


$where = array();
$where['delete_flg'] = 0;
if($keyword){
    $where['name LIKE'] = $keyword;
}
$orderBy = "target_flg1 DESC, name ASC";
$limit = 3000;
$temp = select('mst_disease', '*', $where, $orderBy, $limit);
foreach ($temp as $key =>$val){
    // 傷病名コード
    $code  = $val['code'];
    if (!$code){
        continue;
    }

    // カナ情報が入れば不要
    $val['kana'] = isset($val['kana']) ? $val['kana'] : "";
    
    // 格納
    $diseaseList[$code] = $val;
}
?>

<div class="dynamic_modal cont_user cancel_act" style="width:900px;">
    <div class="tit">主たる傷病名選択</div>
    <div>
        <span class="label_t">コード／傷病名</span>
        <input type="text" class="searchWord" value="">
        <button type="button" class="btnDlgSearch">絞込検索</button>
    </div>
    <div class="close close_part">✕<span class="close" style="width:75px;">閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>コード</th>
                <th>傷病名</th>
                <!--<th>傷病名カナ</th>-->
                <th>別表７</th>
                <th>別表８</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($diseaseList as $code => $val): ?>
            <tr>
                <td>
                    <button type="button" 
                            data-unique_id="<?= $val['unique_id'] ?>"
                            data-code="<?= $val['code'] ?>"
                            data-name="<?= $val['name'] ?>"
                            data-kana="<?= $val['kana'] ?>"
                            data-flg1="<?= $val['target_flg1'] ?>"
                            data-flg2="<?= $val['target_flg2'] ?>"
                            data-set_cls_name="<?= $setName ?>"
                            data-set_cls_flg="<?= $setFlg ?>"
                            >
                        選択
                    </button>
                </td>
                <td class="tgtSearchVal"><?= $val['code'] ?></td>
                <td class="tgtSearchVal"><?= $val['name'] ?></td>
                <!--<td class="tgtSearchVal"><?= $val['kana'] ?></td>-->
                <td class=""><?= $val['target_flg1'] === "1" ? "〇" : "―" ?></td>
                <td class=""><?= $val['target_flg2'] === "1" ? "〇" : "―"  ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(function(){
        
        // モーダルから選択
        $(".cont_user").find("table button").on("click",function(){
            // 各種データ取得
            var uniqueId    = $(this).data("unique_id");
            var code         = $(this).data("code");
            var name         = $(this).data("name");
            var kana         = $(this).data("kana");
            var flg1         = $(this).data("flg1");
            var flg2         = $(this).data("flg2");
            var setName      = $(this).data("set_cls_name");
            var setFlg       = $(this).data("set_cls_flg");
            
            var tgtCol = document.getElementsByClassName(setName);
            $(tgtCol).val(name);
            var tgtImg = document.getElementsByClassName(setFlg);
            $(tgtImg).removeClass("select7");
            if(flg1 === 1){
                $(tgtImg).addClass("select7");
            }
            // windowを閉じる
            $(".cont_user").hide();
        });
        
        $(".close").on("click",function(){
            // windowを閉じる
            $(".dynamic_modal").remove(); 
        });
       
          // キーワード検索
        $(".btnDlgSearch").on("click", function () {
            var word = $(".searchWord").val();
            if (word) {
                // 一旦絞込を解除する
                $(".tgtSearchVal").each(function () {
                    $(this).closest('tr').hide();
                });
                // 検索にHITしなかった行を非表示する
                $(".tgtSearchVal").each(function () {
                    var tgtSearch = $(this).first().text();
                    if (tgtSearch && tgtSearch.includes(word)) {
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