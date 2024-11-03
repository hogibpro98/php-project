<?php
// 拠点
$plcList = array();
$where = array();
$where['delete_flg'] = 0;
$orderBy = 'unique_id ASC';
$temp = select('mst_place', 'unique_id,name', $where, $orderBy);
foreach ($temp as $val) {
    $plcList[$val['unique_id']] = $val['name'];
}

// 契約事業所
$ofcList = array();
$where = array();
$where['delete_flg'] = 0;
$orderBy = 'unique_id ASC';
$temp = select('mst_office', 'unique_id,name,place_id', $where, $orderBy);
foreach ($temp as $val) {
    $val['place_name'] = isset($plcList[$val['place_id']])
            ? $plcList[$val['place_id']]
            : null;
    $ofcList[$val['unique_id']] = $val;
}
?>
<div class="cont_office cancel_act">
    <div class="tit">事業所選択</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <input type="hidden" id="tgt-id" value="tgt-id">
    <input type="hidden" id="tgt-name1" value="tgt-name1">
    <input type="hidden" id="tgt-name2" value="tgt-name2">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>拠点名</th>
                <th>事業所名</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ofcList as $ofcId => $val): ?>
            <tr>
                <td><button type="button" data-id="<?= $ofcId ?>" data-name1="<?= $val['place_name'] ?>" data-name2="<?= $val['name'] ?>">選択</button></td>
                <td><?= $val['place_name'] ?></td>
                <td><?= $val['name'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(function(){
        
        // モーダルから選択
        $(".cont_office").find("table button").on("click",function(){
            // 各種データ取得
            var id = $(this).data("id");
            var name1 = $(this).data("name1");
            var name2 = $(this).data("name2");
            
            /* ※表示先のinputには .tgt-id .tgt-name1 .tgt-name2 のクラスを指定しておくこと。 */
            var setTgtId = $("#tgt-id").val();
            var setTgtName1 = $("#tgt-name1").val();
            var setTgtName2 = $("#tgt-name2").val();
            
            $("."+setTgtId).val(id);
            $("."+setTgtName1).val(name1);
            $("."+setTgtName2).val(name2);

            // $(".tgt-id").val(id);
            // $(".tgt-name1").val(name1);
            // $(".tgt-name2").val(name2);
           
            // windowを閉じる
            $(".cont_office").hide();
        });
        
        // ID直接入力
        $(".tgt-id").on("input",function(){
            // 入力テキスト取得
            var inputText = $(this).val();
            
            // IDの一致チェック
            $(".cont_office button").each(function(){
                var id = $(this).data("id");
                var name1 = $(this).data("name1");
                var name2 = $(this).data("name2");
                
                if(id == inputText){
                    $(".tgt-name2").val(name2);
                }
            });
        });
        
    });
</script>