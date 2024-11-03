<?php
$usrList = array();
$placeId = !isset($placeId) ? $_SESSION['place'] : $placeId;
$temp = getUserList($placeId);
foreach ($temp as $key => $val) {

    // 利用者ID、外部コード
    $userId = $val['unique_id'];
    $othId = $val['other_id'];
    if (!$othId) {
        continue;
    }

    // カナ
    $val['kana'] = $val['last_kana'] . ' ' . $val['first_kana'];

    // 年号
    $nengo = !empty($val['birthday']) ? chgAdToJpNengo($val['birthday']) : null;
    // 和暦
    $wareki = !empty($val['birthday']) ? chgAdToJpYear($val['birthday']) . '年' : null;
    // 生年月日
    //$val['birthday_disp'] = $nengo.$wareki.$val['birthday'];
    $val['birthday_disp'] = !empty($val['birthday']) ? chgAdToJpDate($val['birthday']) : null;

    // 年齢
    $val['age'] = !empty($val['birthday']) ? getAge($val['birthday']) . '歳' : null;
    // 住所
    $val['address'] = $val['prefecture'] . $val['area'] . $val['address1'] . $val['address2'] . $val['address3'];

    // 要介護度
    $val['care_rank'] = getCareRank($userId);

    // 格納
    $usrList[$othId] = $val;
}
?>
<div class="cont_user cancel_act">
    <div class="tit">利用者選択</div>
    <div>
        <span class="label_t">氏名漢字／カナ</span>
        <input type="text" class="searchKana" value="">
        <button type="button" class="btnDlgSearch">検索</button>
    </div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>利用者ID</th>
                <th>氏名(カナ)</th>
                <th>氏名</th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($usrList as $othId => $val): ?>
                <tr>
                    <td>
                        <button type="button"
                                data-unique_id="<?= $val['unique_id'] ?>"
                                data-usr_id="<?= $othId ?>"
                                data-usr_name="<?= $val['name'] ?>"
                                data-usr_kana="<?= $val['kana'] ?>"
                                data-usr_birthday="<?= $val['birthday_disp'] ?>"
                                data-usr_age="<?= $val['age'] ?>"
                                data-usr_adr="<?= $val['address'] ?>"
                                data-usr_rank="<?= $val['care_rank'] ?>">
                            選択
                        </button>
                    </td>
                    <td class="tgtSearchVal"><?= $othId ?></td>
                    <td class="tgtSearchVal"><?= $val['kana'] ?></td>
                    <td class="tgtSearchVal"><?= $val['name'] ?></td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(function () {

        // モーダルから選択
        $(".cont_user").find("table button").on("click", function () {
            // 各種データ取得
            var unique_id = $(this).data("unique_id");
            var usr_id = $(this).data("usr_id");
            var usr_name = $(this).data("usr_name");
            var usr_kana = $(this).data("usr_kana");
            var usr_nengo = $(this).data("usr_nengo");
            var usr_wareki = $(this).data("usr_wareki");
            var usr_birthday = $(this).data("usr_birthday");
            var usr_age = $(this).data("usr_age");
            var usr_address = $(this).data("usr_adr");
            var usr_rank = $(this).data("usr_rank");

            /* ※表示先のinputには .tgt-usr_id .tgt-usr_name .tgt-usr_kana のクラスを指定しておくこと。 */

            $(".tgt-unique_id").val(unique_id);
            $(".tgt-usr_id").val(usr_id);
            $(".tgt-usr_name").val(usr_name);
            $(".tgt-usr_kana").val(usr_kana);
            $(".tgt-usr_nengo").val(usr_nengo);
            $(".tgt-usr_wareki").val(usr_wareki);
            $(".tgt-usr_birthday").val(usr_birthday);
            $(".tgt-usr_age").val(usr_age);
            $(".tgt-usr_adr").val(usr_address);
            $(".tgt-usr_rank").val(usr_rank);

            // windowを閉じる
            $(".cont_user").hide();
        });

        // ID直接入力
        $(".tgt-usr_id").on("input", function () {
            // 入力テキスト取得
            var inputText = $(this).val();

            // IDの一致チェック
            $(".cont_user button").each(function () {
                var unique_id = $(this).data("unique_id");
                var usr_id = $(this).data("usr_id");
                var usr_name = $(this).data("usr_name");
                var usr_kana = $(this).data("usr_kana");
                var usr_nengo = $(this).data("usr_nengo");
                var usr_wareki = $(this).data("usr_wareki");
                var usr_birthday = $(this).data("usr_birthday");
                var usr_age = $(this).data("usr_age");
                var usr_address = $(this).data("usr_adr");
                var usr_rank = $(this).data("usr_rank");

                if (usr_id == inputText) {
                    $(".tgt-unique_id").val(unique_id);
                    $(".tgt-usr_name").val(usr_name);
                    $(".tgt-usr_kana").val(usr_kana);
                    $(".tgt-usr_nengo").val(usr_nengo);
                    $(".tgt-usr_wareki").val(usr_wareki);
                    $(".tgt-usr_birthday").val(usr_birthday);
                    $(".tgt-usr_age").val(usr_age);
                    $(".tgt-usr_adr").val(usr_address);
                    $(".tgt-usr_rank").val(usr_rank);
                }
            });
        });
        // 氏名（漢字／カナ）検索
        $(".btnDlgSearch").on("click", function () {
            var kana = $(".searchKana").val();
            if (kana) {
                // 一旦絞込を解除する
                $(".tgtSearchVal").each(function () {
                    var tgtKana = $(this).first().text();
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
                    var tgtKana = $(this).first().text();
                    $(this).closest('tr').show();
                });
            }
        });
    });
</script>