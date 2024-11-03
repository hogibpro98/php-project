<?php
// 主治医情報
$doctorList = array();
$where = array();
$where['delete_flg'] = 0;
$where['user_id'] = $userId;
$orderBy = 'unique_id DESC';
$limit = 1;
$temp = select('doc_instruct', '*', $where, $orderBy, $limit);
foreach ($temp as $val) {
    $docList[] = $val;
}
?>
<div class="cont_doc cancel_act">
    <div class="tit">主治医情報選択</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>医療機関名称</th>
                <th>主治医</th>
                <th>所在地</th>
                <th>電話番号１</th>
                <th>電話番号２</th>
                <th>ＦＡＸ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($docList as $val): ?>
            <tr>
                <td>
                    <button type="button"
                            data-doc_hosp="<?= $val['hospital'] ?>"
                            data-doc_doc="<?= $val['doctor'] ?>"
                            data-doc_adr="<?= $val['address1'] ?>"
                            data-doc_tel1="<?= $val['tel1'] ?>"
                            data-doc_tel2="<?= $val['tel2'] ?>"
                            data-doc_fax="<?= $val['fax'] ?>">
                        選択
                    </button>
                </td>
                <td><?= $val['hospital'] ?></td>
                <td><?= $val['doctor'] ?></td>
                <td><?= $val['address1'] ?></td>
                <td><?= $val['tel1'] ?></td>
                <td><?= $val['tel2'] ?></td>
                <td><?= $val['fax'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(function(){
        
        // モーダルから選択
        $(".cont_doc").find("table button").on("click",function(){
            // 各種データ取得
            var hosp    = $(this).data("doc_hosp");
            var doc     = $(this).data("doc_doc");
            var adr     = $(this).data("doc_adr");
            var tel1    = $(this).data("doc_tel1");
            var tel2    = $(this).data("doc_tel2");
            var fax     = $(this).data("doc_fax");
            
            /* ※表示先のinputには .tgt-doc_hosp .tgt-doc_doc・・・ のクラスを指定しておくこと。 */
            
            $(".tgt-doc_hosp").val(hosp);
            $(".tgt-doc_doc").val(doc);
            $(".tgt-doc_adr").val(adr);
            $(".tgt-doc_tel1").val(tel1);
            $(".tgt-doc_tel2").val(tel2);
            $(".tgt-doc_fax").val(fax);
            
            // windowを閉じる
            $(".cont_doc").hide();
        });
        
        // 反映ボタンクリック
        $(".ref_doctor").on("click",function(){
//            alert ('aa');
            // IDの一致チェック
            $(".cont_doc button").each(function(){
                var hosp    = $(this).data("doc_hosp");
                var doc     = $(this).data("doc_doc");
                var adr     = $(this).data("doc_adr");
                var tel1    = $(this).data("doc_tel1");
                var tel2    = $(this).data("doc_tel2");
                var fax     = $(this).data("doc_fax");
                
                $(".tgt-doc_hosp").val(hosp);
                $(".tgt-doc_doc").val(doc);
                $(".tgt-doc_adr").val(adr);
                $(".tgt-doc_tel1").val(tel1);
                $(".tgt-doc_tel2").val(tel2);
                $(".tgt-doc_fax").val(fax);
            });
        });
        
    });
</script>