<?php
// スタッフ
$stfList = array();
$where = array();
$where['delete_flg'] = 0;
$orderBy = 'unique_id ASC';
$temp = select('mst_staff', '*', $where, $orderBy);
foreach ($temp as $val) {
    $stfId = $val['unique_id'];
    $val['driving_license'] = $val['driving_license'] ? '〇' : null;
    $val['license2'] = !empty($val['license2']) ? '〇' : null;
    $stfList[$stfId] = $val;
}
?>
<div class="cont_staff5 cancel_act">
    <div class="tit">スタッフ選択</div>
    <div>
        <span class="label_t">氏名漢字／カナ</span>
        <input type="text" class="searchKanaSt5" value="">
        <button type="button" class="btnDlgSearchSt5">検索</button>
    </div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th style="display:none">従業員ID</th>
                <th>従業員氏名</th>
                <th style="display:none">従業員氏名カナ</th>
                <th>保有資格</th>
                <th>自動車運転<br>可否</th>
                <th>特定行為<br>可否</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stfList as $stfId => $val): ?>
                <tr>
                    <td><button type="button" data-stf_id="<?= $stfId ?>" data-stf_cd="<?= $val['staff_id'] ?>" data-stf_name="<?= $val['last_name'] . $val['first_name'] ?>">選択</button></td>
                    <td class="tgtSearchVal5" style="display:none"><?= $val['staff_id'] ?></td>
                    <td class="tgtSearchVal5"><?= $val['last_name'] . $val['first_name'] ?></td>
                    <td class="tgtSearchVal5" style="display:none"><?= $val['last_kana'] . $val['first_kana'] ?></td>
                    <td><?= $val['license1'] ?></td>
                    <td><?= $val['driving_license'] ?></td>
                    <td><?= $val['license2'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(function () {

        // モーダルから選択
        $(".cont_staff5").find("table button").on("click", function () {
            // 各種データ取得
            var stf_id = $(this).data("stf_id");
            var stf_cd = $(this).data("stf_cd");
            var stf_name = $(this).data("stf_name");

            /* ※表示先のinputには .tgt-stf5_id .tgt-stf5_name のクラスを指定しておくこと。 */

            $(".tgt-stf5_id").val(stf_id);
            $(".tgt-stf5_cd").val(stf_cd);
            $(".tgt-stf5_name").val(stf_name);

            // windowを閉じる
            $(".cont_staff5").hide();
        });

        // ID直接入力
        $(".tgt-stf5_cd").on("input", function () {
            // 入力テキスト取得
            var inputText = $(this).val();

            // IDの一致チェック
            $(".cont_staff button").each(function () {
                var stf_id = $(this).data("stf_id");
                var stf_cd = $(this).data("stf_cd");
                var stf_name = $(this).data("stf_name");

                if (stf_cd == inputText) {
                    $(".tgt-stf5_id").val(stf_id);
                    $(".tgt-stf5_name").val(stf_name);
                }
            });
        });
        // 氏名（漢字／カナ）検索
        $(".btnDlgSearchSt5").on("click", function () {
            var kana = $(".searchKanaSt5").val();
            if (kana) {
                // 一旦絞込を解除する
                $(".tgtSearchVal5").each(function () {
                    var tgtKana = $(this).first().text();
                    $(this).closest('tr').hide();
                });
                // 検索にHITしなかった行を非表示する
                $(".tgtSearchVal5").each(function () {
                    var tgtKana = $(this).first().text();
                    if (tgtKana && tgtKana.includes(kana)) {
                        $(this).closest('tr').show();
                    }
                });
            } else {
                // 検索文字列が無い場合は、全て表示する
                $(".tgtSearchVal5").each(function () {
                    var tgtKana = $(this).first().text();
                    $(this).closest('tr').show();
                });
            }
        });
    });
</script>