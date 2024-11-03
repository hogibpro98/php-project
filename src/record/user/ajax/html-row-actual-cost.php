<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/record/user/ajax/get-data-actual-cost.php');
if (!empty(filter_input(INPUT_GET, 'jippi_i'))) {
    $jippi_i = filter_input(INPUT_GET, 'jippi_i');
}

if (!empty(filter_input(INPUT_GET, 'jpiPrefix'))) {
    $jpiPrefix = filter_input(INPUT_GET, 'jpiPrefix');
}

if (!empty(filter_input(INPUT_GET, 'jippiId'))) {
    $jippiId= filter_input(INPUT_GET, 'jippiId');
}

if (isset($_SESSION['data_actual_cost'])) {
    $unInsType = $_SESSION['data_actual_cost']['unInsType'];
    $uisList = $_SESSION['data_actual_cost']['uisList'];
}
?>
<tr>
    <td class="type">
        <b class="sm">種類</b>
        <select id="selJippiType<?= $jippi_i ?>" data-index="<?= $jippi_i ?>" class="cngOffice uis_type selJippiType cngJippi" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][type]">
            <option value="">選択してください</option>
            <?php foreach ($unInsType['type'] as $type => $dummy) : ?>
                <option value="<?= $type ?>"><?= $type ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    <td class="item">
        <b class="sm">項目名称</b>
        <select id="jippiFieldName<?= $jippi_i ?>" data-index="<?= $jippi_i ?>" class="cngOffice uis_name cngJippi" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][uninsure_id]">
            <option value="">選択してください</option>
            <?php foreach ($uisList as $type => $uisList2) : ?>
                <?php foreach ($uisList2 as $uisId => $uisData) : ?>
                    <option class="cngJippiType<?= $jippi_i ?>" value="<?= $uisId ?>"
                            data-office_id="<?= $uisData['link_office'] ?>"
                            data-type="<?= $uisData['type'] ?>"
                            data-zei_type="<?= $uisData['zei_type'] ?>"
                            data-subsidy="<?= $uisData['subsidy'] ?>"
                            data-rate="<?= $uisData['rate'] ?>"
                            data-price="<?= $uisData['price'] ?>"
                            data-name="<?= $uisData['name'] ?>"
                            data-id="<?= $uisId ?>"
                    ><?= $uisData['name'] ?>
                    </option>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </select>
    </td>
    <td class="price">
        <b class="sm">単価最大7桁</b>
        <input id="jippiPrice<?= $jippi_i ?>" type="text" class="validate[maxSize[7],custom[onlyNumberSp]] uis_price" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][price]" value="<?= $jippiList['price'] ?>" style="width:85px" maxlength="7" placeholder="半角数字7桁">
    </td>
    <td class="tax">
        <b class="sm">消費税<br>区分</b>
        <select id="jippiZeiType<?= $jippi_i ?>" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][zei_type]" class="uis_zei_type">
            <?php foreach ($unInsType['zei_type'] as $zeiType => $dummy) : ?>
                <option value="<?= $zeiType ?>"><?= $zeiType ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    <td class="sales_tax">
        <b class="sm">消費税率</b>
        <input id="jippiUisRate<?= $jippi_i ?>" type="text" class="validate[maxSize[2],custom[onlyNumberSp]] uis_rate" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][rate]" value="<?= isset($jippiList['rate']) ? $jippiList['rate'] : '' ?>" style="width:60px;" maxlength="2" placeholder="半角数字2桁"><span>%</span>
    </td>
    <td class="d_cate">
        <b class="sm">控除区分</b>
        <select id="jippiSubsidy<?= $jippi_i ?>" name="<?= $jpiPrefix ?>[<?= $jippiId ?>][subsidy]" class="uis_subsidy">
            <?php foreach ($unInsType['subsidy'] as $subsidy => $dummy) : ?>
                <option value="<?= $subsidy ?>"><?= $subsidy ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    <td>
        <button type="button" class="row_delete" style="width:32px;height:32px;background:#FFFFFF;border-radius: 5px;border: 1px solid #A7A7A7;">
            <img src="/common/image/icon_trash2.png" />
        </button>
    </td>
    <td></td>
</tr>
