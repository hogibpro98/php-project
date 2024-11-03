<?php
// 利用者
$placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : 'dummy';
$usrList = getUserList($placeId);
?>
<div class="cont_user2 cancel_act">
    <div class="tit">利用者選択</div>
    <div>
        <span class="label_t">氏名漢字／カナ</span>
        <input type="text" class="searchKana2" value="">
        <button type="button" class="btnDlgSearch2">検索</button>
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
            <?php foreach ($usrList as $tgtId => $val): ?>
            <tr>
                <td><button type="button" data-usr_id="<?= $tgtId ?>" data-usr_code="<?= $val['other_id'] ?>" data-usr_name="<?= $val['name'] ?>" data-usr_kana="<?= $val['kana'] ?>">選択</button></td>
                <td class="tgtSearchVal"><?= $val['other_id'] ?></td>
                <td class="tgtSearchVal"><?= $val['last_kana'] . $val['first_kana'] ?></td>
                <td class="tgtSearchVal"><?= $val['name'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(function(){
        
        // モーダルから選択
        $(".cont_user2").find("table button").on("click",function(){
            // 各種データ取得
            var usr_id = $(this).data("usr_id");
            var usr_code = $(this).data("usr_code");
            var usr_name = $(this).data("usr_name");
            var usr_kana = $(this).data("usr_kana");
            
            /* ※表示先のinputには .tgt-usr2_id .tgt-usr2_name .tgt-usr2_kana のクラスを指定しておくこと。 */
            
            $(".tgt-usr2_id").val(usr_id);
            $(".tgt-usr2_code").val(usr_code);
            $(".tgt-usr2_name").val(usr_name);
            $(".tgt-usr2_kana").val(usr_kana);
            
            // windowを閉じる
            $(".cont_user2").hide();
        });
        
        // ID直接入力
        $(".tgt-usr2_code").on("input",function(){
            // 入力テキスト取得
            var inputText = $(this).val();
            
            // IDの一致チェック
            $(".cont_user2 button").each(function(){
                var usr_id = $(this).data("usr_id");
                var usr_code = $(this).data("usr_code");
                var usr_name = $(this).data("usr_name");
                var usr_kana = $(this).data("usr_kana");
                
                if(usr_code == inputText){
                    $(".tgt-usr2_name").val(usr_name);
                    $(".tgt-usr2_kana").val(usr_kana);
                }
            });
        });
        // 氏名（漢字／カナ）検索
        $(".btnDlgSearch2").on("click", function () {
            var kana = $(".searchKana2").val();
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