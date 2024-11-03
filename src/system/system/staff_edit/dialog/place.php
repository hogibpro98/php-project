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
?>
<div class="cont_place_search cancel_act">
    <div class="tit">拠点選択</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>拠点名</th>
            </tr>
        </thead>
        <tbody>
            <input type="hidden" class="tgt-len" name="" value="">
            <?php foreach ($plcList as $plcId => $val): ?>
            <tr>
                <td><button type="button" data-plc_id="<?= $plcId ?>" data-plc_name="<?= $val ?>" onclick="getOfcName()">選択</button></td>
                <td><?= $val ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
//    $(function(){
//        
//        // モーダルから選択
//        $(".cont_place_search").find("table button").on("click",function(){
//            // 各種データ取得
//            var plc_id = $(this).data("plc_id");
//            var plc_name = $(this).data("plc_name");
//            
//            /* ※表示先のinputには .tgt-plc_id .tgt-plc_name のクラスを指定しておくこと。 */
//            
//            $(".tgt-plc_id").val(plc_id);
//            $(".tgt-plc_name").val(plc_name);
//            
//            // windowを閉じる
//            $(".cont_place_search").hide();
//        });
//        
//        // ID直接入力
//        $(".tgt-plc_id").on("input",function(){
//            // 入力テキスト取得
//            var inputText = $(this).val();
//            
//            // IDの一致チェック
//            $(".cont_place_search button").each(function(){
//                var plc_id = $(this).data("plc_id");
//                var plc_name = $(this).data("plc_name");
//                
//                if(plc_id == inputText){
//                    $(".tgt-plc_name").val(plc_name);
//                }
//            });
//        });
//        
//    });
</script>