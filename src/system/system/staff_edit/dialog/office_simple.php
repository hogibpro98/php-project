<?php

// 所属事業所
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
<div class="cont_office_simple cancel_act">
    <div class="tit">事業所選択</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>事業所名</th>
            </tr>
        </thead>
        <tbody>
            <input type="hidden" class="tgt-len" name="" value="">
            <?php foreach ($ofcList as $ofcId => $val): ?>
            <tr>
                <td><button type="button" data-ofc_id="<?= $ofcId ?>" data-ofc_name="<?= $val['name'] ?>" onclick="getPlcName()">選択</button></td>
                <td><?= $val['name'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
//    function getPlcNm(){
//        
//        // モーダルから選択
//        $(".cont_office_simple").find("table button").on("click",function(){
//        // 各種データ取得
//            var ofc_id = $(this).data("ofc_id");
//        alert('aa');
//            var ofc_name = $(this).data("ofc_name");
//        alert('bb');
//            var len = $(".len").val();
// alert(len);
//            /* ※表示先のinputには .tgt-ofc_id .tgt-ofc_name のクラスを指定しておくこと。 */
//            
//            $(".tgt-ofc_id").val(ofc_id);
//            $(".tgt-ofc_name" + len).val(ofc_name);
//            
//            // windowを閉じる
//            $(".cont_office_simple").hide();
//        });
//        
//        // ID直接入力
//        $(".tgt-ofc_id").on("input",function(){
//            // 入力テキスト取得
//            var inputText = $(this).val();
//            
//            // IDの一致チェック
//            $(".cont_office_simple button").each(function(){
//                var ofc_id = $(this).data("ofc_id");
//                var ofc_name = $(this).data("ofc_name");
//                
//                if(id == inputText){
//                    $(".tgt-ofc_name").val(ofc_name);
//                }
//            });
//        });
//        
//    }
//    $(function(){
//        
//        // モーダルから選択
//        $(".cont_office_simple").find("table button").on("click",function(){
//            // 各種データ取得
//            var ofc_id = $(this).data("ofc_id");
//            var ofc_name = $(this).data("ofc_name");
//            
//            /* ※表示先のinputには .tgt-ofc_id .tgt-ofc_name のクラスを指定しておくこと。 */
//            
//            $(".tgt-ofc_id").val(ofc_id);
//            $(".tgt-ofc_name").val(ofc_name);
//            
//            // windowを閉じる
//            $(".cont_office_simple").hide();
//        });
//        
//        // ID直接入力
//        $(".tgt-ofc_id").on("input",function(){
//            // 入力テキスト取得
//            var inputText = $(this).val();
//            
//            // IDの一致チェック
//            $(".cont_office_simple button").each(function(){
//                var ofc_id = $(this).data("ofc_id");
//                var ofc_name = $(this).data("ofc_name");
//                
//                if(id == inputText){
//                    $(".tgt-ofc_name").val(ofc_name);
//                }
//            });
//        });
//        
//    });
</script>