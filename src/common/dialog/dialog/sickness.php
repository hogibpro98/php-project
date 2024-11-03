<?php
// 病名（指示書）
$sickList = array();
$where = array();
$where['delete_flg'] = 0;
$where['user_id'] = $userId;
$orderBy = 'unique_id DESC';
$limit = 1;
$temp = select('doc_instruct', '*', $where, $orderBy, $limit);
foreach ($temp as $val) {
    $instructId = $val['unique_id'];
    // 傷病名１を主たる傷病名にセット
    $val['main_sickness'] = $val['sickness1'];
    $sickList[$instructId] = $val;
}
?>
<div class="cont_sick cancel_act">
    <div class="tit">傷病名選択</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>主たる傷病名</th>
                <th>傷病名１</th>
                <th>傷病名２</th>
                <th>傷病名３</th>
                <th>傷病名４</th>
                <th>傷病名５</th>
                <th>傷病名６</th>
                <th>傷病名７</th>
                <th>傷病名８</th>
                <th>傷病名９</th>
                <th>傷病名１０</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sickList as $val): ?>
            <tr>
                <td>
                    <button type="button"
                            data-sick_main="<?= $val['main_sickness'] ?>"
                            data-sick1="<?= $val['sickness1'] ?>"
                            data-sick2="<?= $val['sickness2'] ?>"
                            data-sick3="<?= $val['sickness3'] ?>"
                            data-sick4="<?= $val['sickness4'] ?>"
                            data-sick5="<?= $val['sickness5'] ?>"
                            data-sick6="<?= $val['sickness6'] ?>"
                            data-sick7="<?= $val['sickness7'] ?>"
                            data-sick8="<?= $val['sickness8'] ?>"
                            data-sick9="<?= $val['sickness9'] ?>"
                            data-sick10="<?= $val['sickness10'] ?>">
                        選択
                    </button>
                </td>
                <td><?= $val['main_sickness'] ?></td>
                <td><?= $val['sickness1'] ?></td>
                <td><?= $val['sickness2'] ?></td>
                <td><?= $val['sickness3'] ?></td>
                <td><?= $val['sickness4'] ?></td>
                <td><?= $val['sickness5'] ?></td>
                <td><?= $val['sickness6'] ?></td>
                <td><?= $val['sickness7'] ?></td>
                <td><?= $val['sickness8'] ?></td>
                <td><?= $val['sickness9'] ?></td>
                <td><?= $val['sickness10'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(function(){
        
        // モーダルから選択
        $(".cont_sick").find("table button").on("click",function(){
            // 各種データ取得
            var sick_main    = $(this).data("sick_main");
            var sick1        = $(this).data("sick1");
            var sick2        = $(this).data("sick2");
            var sick3        = $(this).data("sick3");
            var sick4        = $(this).data("sick4");
            var sick5        = $(this).data("sick5");
            var sick6        = $(this).data("sick6");
            var sick7        = $(this).data("sick7");
            var sick8        = $(this).data("sick8");
            var sick9        = $(this).data("sick9");
            var sick10       = $(this).data("sick10");
            
            /* ※表示先のinputには .tgt-sick_main .tgt-sick1・・・ のクラスを指定しておくこと。 */
            
            $(".tgt-sick_main").val(sick_main);
            $(".tgt-sick1").val(sick1);
            $(".tgt-sick2").val(sick2);
            $(".tgt-sick3").val(sick3);
            $(".tgt-sick4").val(sick4);
            $(".tgt-sick5").val(sick5);
            $(".tgt-sick6").val(sick6);
            $(".tgt-sick7").val(sick7);
            $(".tgt-sick8").val(sick8);
            $(".tgt-sick9").val(sick9);
            $(".tgt-sick10").val(sick10);
            
            // windowを閉じる
            $(".cont_sick").hide();
        });
        
        // 反映ボタンクリック
        $(".ref_sick").on("click",function(){
//            alert ('aa');
            // IDの一致チェック
            $(".cont_sick button").each(function(){
                var sick_main    = $(this).data("sick_main");
                var sick1        = $(this).data("sick1");
                var sick2        = $(this).data("sick2");
                var sick3        = $(this).data("sick3");
                var sick4        = $(this).data("sick4");
                var sick5        = $(this).data("sick5");
                var sick6        = $(this).data("sick6");
                var sick7        = $(this).data("sick7");
                var sick8        = $(this).data("sick8");
                var sick9        = $(this).data("sick9");
                var sick10       = $(this).data("sick10");
                
                $(".tgt-sick_main").val(sick_main);
                $(".tgt-sick1").val(sick1);
                $(".tgt-sick2").val(sick2);
                $(".tgt-sick3").val(sick3);
                $(".tgt-sick4").val(sick4);
                $(".tgt-sick5").val(sick5);
                $(".tgt-sick6").val(sick6);
                $(".tgt-sick7").val(sick7);
                $(".tgt-sick8").val(sick8);
                $(".tgt-sick9").val(sick9);
                $(".tgt-sick10").val(sick10);
            });
        });
        
    });
</script>