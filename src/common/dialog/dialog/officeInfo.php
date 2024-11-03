<?php
// 居宅情報
$officeList = array();
$where = array();
$where['delete_flg'] = 0;
$where['user_id'] = $userId;
$orderBy = 'unique_id DESC';
$limit = 1;
$temp = select('mst_user_office2', '*', $where, $orderBy, $limit);
foreach ($temp as $val) {
    $officeList[] = $val;
}
?>
<div class="cont_ofc cancel_act">
    <div class="tit">居宅情報選択</div>
    <div class="close close_part">✕<span>閉じる</span></div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>事業所名称</th>
                <th>担当者</th>
                <th>所在地</th>
                <th>電話番号</th>
                <th>ＦＡＸ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($officeList as $val): ?>
            <tr>
                <td>
                    <button type="button"
                            data-ofc_name="<?= $val['office_name'] ?>"
                            data-ofc_staff="<?= $val['person_name'] ?>"
                            data-ofc_adr="<?= $val['address'] ?>"
                            data-ofc_tel="<?= $val['tel'] ?>"
                            data-ofc_fax="<?= $val['fax'] ?>">
                        選択
                    </button>
                </td>
                <td><?= $val['office_name'] ?></td>
                <td><?= $val['person_name'] ?></td>
                <td><?= $val['address'] ?></td>
                <td><?= $val['tel'] ?></td>
                <td><?= $val['fax'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(function(){
        
        // モーダルから選択
        $(".cont_ofc").find("table button").on("click",function(){
            // 各種データ取得
            var name    = $(this).data("ofc_name");
            var staff   = $(this).data("ofc_staff");
            var adr     = $(this).data("ofc_adr");
            var tel     = $(this).data("ofc_tel");
            var fax     = $(this).data("ofc_fax");
            
            /* ※表示先のinputには .tgt-doc_hosp .tgt-doc_doc・・・ のクラスを指定しておくこと。 */
            
            $(".tgt-ofc_name").val(name);
            $(".tgt-ofc_staff").val(staff);
            $(".tgt-ofc_adr").val(adr);
            $(".tgt-ofc_tel").val(tel);
            $(".tgt-ofc_fax").val(fax);
            
            // windowを閉じる
            $(".cont_ofc").hide();
        });
        
        // 反映ボタンクリック
        $(".ref_ofc").on("click",function(){
            // IDの一致チェック
            $(".cont_ofc button").each(function(){
                var name    = $(this).data("ofc_name");
                var staff   = $(this).data("ofc_staff");
                var adr     = $(this).data("ofc_adr");
                var tel     = $(this).data("ofc_tel");
                var fax     = $(this).data("ofc_fax");

                $(".tgt-ofc_name").val(name);
                $(".tgt-ofc_staff").val(staff);
                $(".tgt-ofc_adr").val(adr);
                $(".tgt-ofc_tel").val(tel);
                $(".tgt-ofc_fax").val(fax);
            });
        });
        
    });
</script>