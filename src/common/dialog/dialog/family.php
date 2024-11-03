<?php
// 家族構成
$familyList = array();
$where = array();
$where['delete_flg'] = 0;
$where['user_id'] = $userId;
$orderBy = 'unique_id DESC';
$limit = 3;
$temp = select('mst_user_family', '*', $where, $orderBy, $limit);
foreach ($temp as $val) {
    $familyList[] = $val;
}
?>
<div class="cont_fml cancel_act">
    <div class="tit">家族構成</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>氏名</th>
                <th>続柄</th>
                <th>続柄メモ</th>
                <th>職業</th>
                <th>備考</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($familyList as $val): ?>
            <tr>
                <td>
                    <button type="button"
                            data-fml_name="<?= $val['name'] ?>"
                            data-fml_type="<?= $val['relation_type'] ?>"
                            data-fml_memo="<?= $val['relation_memo'] ?>"
                            data-fml_business="<?= $val['business'] ?>"
                            data-fml_remarks="<?= $val['remarks'] ?>">
                        選択
                    </button>
                </td>
                <td><?= $val['name'] ?></td>
                <td><?= $val['relation_type'] ?></td>
                <td><?= $val['relation_memo'] ?></td>
                <td><?= $val['business'] ?></td>
                <td><?= $val['remarks'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(function(){
        
        // モーダルから選択
        $(".cont_fml").find("table button").on("click",function(){
            // 各種データ取得
            var name     = $(this).data("fml_name");
            var type     = $(this).data("fml_type");
            var memo     = $(this).data("fml_memo");
            var business = $(this).data("fml_business");
            var remarks  = $(this).data("fml_remarks");
            
            /* ※表示先のinputには .tgt-fml_name .tgt-fml_type・・・ のクラスを指定しておくこと。 */
            
            $(".tgt-fml_name").val(name);
            $(".tgt-fml_type").val(type);
            $(".tgt-fml_memo").val(memo);
            $(".tgt-fml_business").val(business);
            $(".tgt-fml_remarks").val(remarks);
            
            // windowを閉じる
            $(".cont_fml").hide();
        });
        
        // 反映ボタンクリック
        $(".ref_fml").on("click",function(){
            var cnt = 0;
            // IDの一致チェック
            $(".cont_fml button").each(function(){
                cnt = cnt + 1;
                var name     = $(this).data("fml_name");
                var type     = $(this).data("fml_type");
                var memo     = $(this).data("fml_memo");
                var business = $(this).data("fml_business");
                var remarks  = $(this).data("fml_remarks");

                $(".tgt-fml_name"+cnt).val(name);
                $(".tgt-fml_type"+cnt).val(type);
                $(".tgt-fml_memo"+cnt).val(memo);
                $(".tgt-fml_business"+cnt).val(business);
                $(".tgt-fml_remarks"+cnt).val(remarks);
            });
        });
        
    });
</script>