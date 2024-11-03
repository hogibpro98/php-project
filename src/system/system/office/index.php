<?php require_once(dirname(__FILE__) . "/php/office.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>

        <!-- 住所検索API -->
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

        <!--CONTENT-->
        <title>事業所管理</title>
        <style>
            .n_search2 {
                font-size: 87.5%;
                color: #000;
                border-radius: 4px;
                border: 1px solid #E8E9EC;
                width: 40px;
                cursor: pointer;
                text-indent: -9999px;
                background: #FFF url(/common/image/icon_search.png) no-repeat center;
            }
            .del_cars{
                margin-top:10px;
                margin-left:10px;
                height:36px;
                width:36px;
            }
        </style>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <h2>事業所管理</h2>
                    <form action="" class="" method="post">
                        <div id="subpage">
                            <div id="office" class="nursing">
                                <!-- ダイアログ流し込みエリア -->
                                <div class="modal_setting"></div>
                                <div class="wrap">
                                    <div class="base_name">
                                        <label for="base-name">拠点名称</label>
                                        <select id="base-name" name="place_id" class="nav-search" >
                                            <option value=""></option>
                                            <?php foreach ($plcMst as $plcMstId => $val): ?>
                                                <option value="<?= $plcMstId ?>" <?= $plcId === $plcMstId ? 'selected' : '' ?>><?= $val['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="multi_function nursing_box">
                                        <div class="tit">看護小規模多機能</div>
                                        <input type="hidden" name="upAry[0][place_id]" value="<?= $dispData[0]['place_id'] ?>">
                                        <input type="hidden" name="upAry[0][unique_id]" value="<?= $dispData[0]['unique_id'] ?>">
                                        <input type="hidden" name="upAry[0][type]" value="看多機">
                                        <input type="hidden" name="upAry[0][office_group]" value="<?= $dispData[0]['office_group'] ?>">
                                        <input type="hidden" name="upAry[0][record_no]" value="<?= $dispData[0]['record_no'] ?>">
                                        <div class="box-w">
                                            <div class="hist_no">
                                                <span class="label_t"><label>履歴No</label></span>
                                                <select id="hist_no" name="office1" class="nav9-search sendHisNo1">
                                                    <?php foreach ($rcdList[0] as $rcdNo => $ofcId): ?>
                                                        <option value="<?= $ofcId ?>" <?= $rcdNo == $dispData[0]['record_no'] ? 'selected' : '' ?>><?= $rcdNo ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" name="btnRcd" value="<?= $dispData[0]['unique_id'] ?>" class="btn add add3">履歴追加</button>
                                            </div>
                                        </div>
                                        <div class="box1">
                                            <table class="tbl_add">
                                                <tr class="tr1">
                                                    <th><label class="label_t">有効期間</label></th>
                                                    <td>
                                                        <div>
                                                            <input type="date" name="upAry[0][start_day]" class="date_start" value="<?= $dispData[0]['start_day'] ?>" style="width:140px;">
                                                            <small>～</small>
                                                            <input type="date" name="upAry[0][end_day]" class="date_end" value="<?= $dispData[0]['end_day'] ?>" style="width:140px;">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t">事業所名</label></th>
                                                    <td><input type="text" name="upAry[0][name]" id="name" value="<?= $dispData[0]['name'] ?>"></td>
                                                </tr>
                                            </table>
                                            <table class="tbl2">
                                                <tr>
                                                    <th><label class="label_t" for="hyoki_name">帳票表記名称</label></th>
                                                    <td><input type="text" name="upAry[0][disply_name]" id="hyoki_name" value="<?= $dispData[0]['disply_name'] ?>"></td>
                                                </tr>
                                                <tr>
                                                    <th><label  class="label_t" for="post-code">郵便番号</label></th>
                                                    <td><input type="text" name="upAry[0][post]" value="<?= $dispData[0]['post'] ?>" class="zip_code0" onKeyUp="AjaxZip3.zip2addr(this, '', 'upAry[0][prefecture]', 'upAry[0][area]', 'upAry[0][address1]');"></td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t">住所</label></th>
                                                    <td>
                                                        <div class="box-i">
                                                            <div>
                                                                <label class="label_t" for="base-prefecture">都道府県</label>
                                                                <select name="upAry[0][prefecture]" class="f-keyVal prefecture_name0">
                                                                    <option value=""></option>
                                                                    <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                                        <?php $select = $pref === $dispData[0]['prefecture'] ? ' selected' : null; ?>
                                                                        <option value="<?= $pref ?>"<?= $select ?>><?= $pref ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="label_t" for="base-municipal">市区町村</label>
                                                                <select name="upAry[0][area]" class="area_name0">
                                                                    <option value=""></option>
                                                                    <?php foreach ($areaMst as $prefectureName => $areaMst2): ?>
                                                                        <?php foreach ($areaMst2 as $areaName => $dummy): ?>
                                                                            <?php $select = $dispData[0]['area'] === $areaName ? ' selected' : null; ?>
                                                                            <option data-pref_name="<?= $prefectureName ?>" value="<?= $areaName ?>"<?= $select ?>><?= $areaName ?></option>
                                                                        <?php endforeach; ?>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="label_t" for="base-town">町域</label>
                                                                <input type="text" name="upAry[0][address1]" value="<?= $dispData[0]['address1'] ?>">
                                                            </div>
                                                            <div>
                                                                <label class="label_t" for="base-houseno">番地以降</label>
                                                                <input type="text" name="upAry[0][address2]" value="<?= $dispData[0]['address2'] ?>">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="label_t" for="bango">電話番号</label>
                                                    </th>
                                                    <td style="display:flex;">
                                                        <div>
                                                            <input type="tel" name="upAry[0][tel]" id="bango" value="<?= $dispData[0]['tel'] ?>">
                                                        </div>
                                                        <div>
                                                            <span class="label_t"><label for="fax">FAX</label></span><input type="tel" name="upAry[0][fax]" id="fax" value="<?= $dispData[0]['fax'] ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" for="email">メールアドレス</label></th>
                                                    <td><input type="email" name="upAry[0][mail]" id="email" value="<?= $dispData[0]['mail'] ?>"></td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" for="admin_name">管理者名</label></th>
                                                    <td style="display:flex;">
                                                        <p class="n_search2 modal_open" data-url="/system/office/dialog/manager_search_dialog.php?tgt_set_id=targetSetId&tgt_set_other_id=targetSetOtherId&tgt_set_name=targetSetName" data-dialog_name="manager_modal">Search</p>
                                                        <input type="hidden" class="targetSetId" name="upAry[0][manager_id]" value="<?= $dispData[0]['manager_id'] ?>">
                                                        <input type="text" class="targetSetOtherId" name="" style="width:135px" value="<?= !empty($dispData[0]['staff_id']) ? $dispData[0]['staff_id'] : '' ?>">
                                                        <input type="text" class="targetSetName bg-gray2" name="" style="width:135px" value="<?= !empty($dispData[0]['manager_name']) ? $dispData[0]['manager_name'] : '' ?>" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" for="accom_cap">宿泊定員</label></th>
                                                    <td><div><input type="text" name="upAry[0][capacity1]" id="accom_cap" value="<?= $dispData[0]['capacity1'] ?>" pattern="^[0-9]+$"><span class="label_t">人</span></div>
                                                        <div><span class="label_t"><label for="commu_cap">通い定員</label></span><input type="text" name="upAry[0][capacity2]" id="commu_cap" value="<?= $dispData[0]['capacity2'] ?>" pattern="^[0-9]+$"><span class="label_t">人</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="label_t">自動車名称</label>
                                                        <span class="btn add add3 add_cars addcars0" style="margin-top:10px;height:38px; width:95px">名称追加</span>
                                                    </th>
                                                    <td class="cars0">
                                                        <?php foreach ($dispCar[0] as $carId => $carVal): ?>
                                                            <div>
                                                                <input  type="hidden" name="upCar[0][<?= $carId ?>][unique_id]" value="<?= $carVal['unique_id'] ?>">
                                                                <input  type="text" name="upCar[0][<?= $carId ?>][name]" value="<?= $carVal['name'] ?>" style="width:200px;" placeholder="入力してください">
                                                                <button type="submit" name="btnDelCar" value="<?= $carId ?>" class="btn trash2 del_cars"></button>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="box2">
                                            <table>
                                                <tr>
                                                    <th><label class="label_t" for="office_no">指定事業所番号</label></th>
                                                    <td><input type="text" name="upAry[0][office_no]" id="office_no" value="<?= $dispData[0]['office_no'] ?>"></td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" for="chiiki_tanka">地域単価</label></th>
                                                    <td><input type="text" name="upAry[0][price]" id="chiiki_tanka" value="<?= $dispData[0]['price'] ?>" pattern="^[0-9]+$"></td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" for="diff_sys-code">別システムコード</label></th>
                                                    <td><input type="text" name="upAry[0][other_code]" id="diff_sys-code" value="<?= $dispData[0]['other_code'] ?>"></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="box3">
                                            <dl>
                                                <dt>看多機加算情報</dt>
                                                <dd><ul>
                                                        <li><div class="label_t">サービス提供体制強化加算Ⅰ・Ⅱ・Ⅲ</div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['サービス提供体制強化加算Ⅰ・Ⅱ・Ⅲ'] as $id => $temp): ?>
                                                                    <span><input type="radio" name="upAry[0][select1]" value="<?= $temp ?>" <?= $temp == $dispData[0]['select1'] ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                        <li><div class="label_t">看護体制強化加算Ⅰ・Ⅱ</div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['看護体制強化加算Ⅰ・Ⅱ'] as $id => $temp): ?>
                                                                    <span><input type="radio" name="upAry[0][select2]" value="<?= $temp ?>" <?= ($temp == $dispData[0]['select2']) ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                        <li><div class="label_t">総合マネジメント体制強化加算</div>
                                                            <div class="opt_s"><span><input type="checkbox" name="upAry[0][select3]" value="1" <?= ($dispData[0]['select3'] == '1') ? 'checked' : '' ?>></span></div>
                                                        </li>
                                                        <li><div class="label_t">訪問体制強化加算</div>
                                                            <div class="opt_s"><span><input type="checkbox" name="upAry[0][select4]" value="1" <?= ($dispData[0]['select4'] == '1') ? 'checked' : '' ?>></span></div>
                                                        </li>
                                                        <li><div class="label_t">介護職員処遇改善加算Ⅰ・Ⅱ・Ⅲ</div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['介護職員処遇改善加算Ⅰ・Ⅱ・Ⅲ'] as $id => $temp): ?>
                                                                    <span><input type="radio" name="upAry[0][select5]" value="<?= $temp ?>" <?= $temp == $dispData[0]['select5'] ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                        <li><div class="label_t">介護職員特定処遇改善加算Ⅰ・Ⅱ</div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['介護職員特定処遇改善加算Ⅰ・Ⅱ'] as $id => $temp): ?>
                                                                    <span><input type="radio" name="upAry[0][select6]" value="<?= $temp ?>" <?= $temp == $dispData[0]['select6'] ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                    </ul></dd>
                                            </dl>
                                            <dl>
                                                <dt>同時可能件数</dt>
                                                <dd>
                                                    <ul>
                                                        <li><div class="label_t"><label for="gen_bath">一般浴</label></div>
                                                            <div><input type="text" name="upAry[0][capacity3]" id="gen_bath" value="<?= $dispData[0]['capacity3'] ?>" pattern="^[0-9]+$"></div>
                                                        </li>
                                                        <li><div class="label_t"><label for="stretcher_bath">ストレッチャー浴</label></div>
                                                            <div><input type="text" name="upAry[0][capacity4]" id="gen_bath" value="<?= $dispData[0]['capacity4'] ?>" pattern="^[0-9]+$"></div>
                                                        </li>
                                                        <li><div class="label_t"><label for="chair_bath">チェアー浴</label></div>
                                                            <div><input type="text" name="upAry[0][capacity5]" id="gen_bath" value="<?= $dispData[0]['capacity5'] ?>" pattern="^[0-9]+$"></div>
                                                        </li>
                                                    </ul>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="home_nursing nursing_box">
                                        <div class="tit">訪問看護</div>
                                        <input type="hidden" name="upAry[1][place_id]" value="<?= $dispData[1]['place_id'] ?>">
                                        <input type="hidden" name="upAry[1][unique_id]" value="<?= $dispData[1]['unique_id'] ?>">
                                        <input type="hidden" name="upAry[1][office_group]" value="<?= $dispData[1]['office_group'] ?>">
                                        <input type="hidden" name="upAry[1][type]" value="訪問看護">

                                        <div class="box-w">
                                            <div class="hist_no">
                                                <span class="label_t"><label>履歴No</label></span>
                                                <select id="hist_no2" name="office2" class="nav9-search sendHisNo2">
                                                    <?php foreach ($rcdList[1] as $rcdNo => $ofcId): ?>
                                                        <option value="<?= $ofcId ?>" <?= $rcdNo == $dispData[1]['record_no'] ? 'selected' : '' ?>><?= $rcdNo ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" name="btnRcd" value="<?= $dispData[1]['unique_id'] ?>" class="btn add add3">履歴追加</button>
                                            </div>
                                        </div>
                                        <div class="box1">
                                            <table class="tbl_add">
                                                <tr class="tr1">
                                                    <th><label class="label_t">有効期間</label></th>
                                                    <td>
                                                        <div><input type="date" name="upAry[1][start_day]" id="start_day" class="date_start" style="width:130px;" value="<?= $dispData[1]['start_day'] ?>">
                                                            <small>～</small>
                                                            <input type="date" name="upAry[1][end_day]" class="date_end" style="width:130px;" value="<?= $dispData[1]['end_day'] ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t">事業所名</label></th>
                                                    <td><input type="text" name="upAry[1][name]" id="name" value="<?= $dispData[1]['name'] ?>"></td>
                                                </tr>
                                            </table>
                                            <table class="tbl2">
                                                <tr>
                                                    <th><label class="label_t" for="hyoki_name">帳票表記名称</label></th>
                                                    <td><input type="text" name="upAry[1][disply_name]" id="hyoki_name" value="<?= $dispData[1]['disply_name'] ?>"></td>
                                                </tr>
                                                <tr>
                                                    <th><label for="post-code" class="label_t">郵便番号</label></th>
                                                    <td><input type="text" name="upAry[1][post]" value="<?= $dispData[1]['post'] ?>" class="zip_code1" onKeyUp="AjaxZip3.zip2addr(this, '', 'upAry[1][prefecture]', 'upAry[1][area]', 'upAry[1][address1]');"></td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t">住所</label></th>
                                                    <td>
                                                        <div class="box-i">
                                                            <div>
                                                                <label class="label_t" for="base-prefecture">都道府県</label>
                                                                <select name="upAry[1][prefecture]" class="f-keyVal prefecture_name1">
                                                                    <option value=""></option>
                                                                    <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                                        <?php $select = $pref === $dispData[1]['prefecture'] ? ' selected' : null; ?>
                                                                        <option value="<?= $pref ?>"<?= $select ?>><?= $pref ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="label_t" for="base-municipal">市区町村</label>
                                                                <select name="upAry[1][area]" class="area_name1">
                                                                    <option value=""></option>
                                                                    <?php foreach ($areaMst as $prefectureName => $areaMst2): ?>
                                                                        <?php foreach ($areaMst2 as $areaName => $dummy): ?>
                                                                            <?php $select = $dispData[1]['area'] === $areaName ? ' selected' : null; ?>
                                                                            <option data-pref_name="<?= $prefectureName ?>" value="<?= $areaName ?>"<?= $select ?>><?= $areaName ?></option>
                                                                        <?php endforeach; ?>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="label_t" for="base-town">町域</label><input type="text" name="upAry[1][address1]" value="<?= $dispData[1]['address1'] ?>"></div>
                                                            <div><label class="label_t" for="base-houseno">番地以降</label><input type="text" name="upAry[1][address2]" value="<?= $dispData[1]['address2'] ?>"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" for="bango">電話番号</label></th>
                                                    <td style="display:flex;">
                                                        <div>
                                                            <input type="tel" name="upAry[1][tel]" id="bango" value="<?= $dispData[1]['tel'] ?>">
                                                        </div>
                                                        <div  style="margin-left:10px;">
                                                            <span class="label_t"><label for="fax">FAX</label></span><input type="tel" name="upAry[1][fax]" id="fax" value="<?= $dispData[1]['fax'] ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" for="email">メールアドレス</label></th>
                                                    <td><input type="email" name="upAry[1][mail]" id="email" value="<?= $dispData[1]['mail'] ?>"></td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" for="admin_name">管理者名</label></th>
                                                    <td style="display:flex;">
                                                        <p class="n_search2 modal_open" data-url="/system/office/dialog/manager_search_dialog.php?tgt_set_id=targetSetId2&tgt_set_other_id=targetSetOtherId2&tgt_set_name=targetSetName2" data-dialog_name="manager_modal">Search</p>
                                                        <input type="hidden" class="targetSetId2" name="upAry[1][manager_id]" value="<?= $dispData[1]['manager_id'] ?>">
                                                        <input type="text" class="targetSetOtherId2" name="" style="width:135px" value="<?= $dispData[1]['staff_id'] ?>">
                                                        <input type="text" class="targetSetName2 bg-gray2" name="" style="width:135px" value="<?= !empty($dispData[1]['manager_name']) ? $dispData[1]['manager_name'] : '' ?>" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="label_t">自動車名称</label>
                                                        <span class="btn add add3 add_cars addcars1" style="margin-top:10px;height:38px; width:95px">名称追加</span>
                                                    </th>
                                                    <td class="cars1">
                                                        <?php foreach ($dispCar[1] as $carId => $carVal): ?>
                                                            <div>
                                                                <input  type="hidden" name="upCar[1][<?= $carId ?>][unique_id]" value="<?= $carVal['unique_id'] ?>">
                                                                <input  type="text" name="upCar[1][<?= $carId ?>][name]" value="<?= $carVal['name'] ?>" style="width:200px;" placeholder="入力してください">
                                                                <button type="submit" name="btnDelCar" value="<?= $carId ?>" class="btn trash2 del_cars"></button>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="label_t" style="line-height:120%;">連携定期巡回事業所名</label>
                                                        <span class="btn add add3 addPatrol" style="margin-top:10px;height:38px; width:95px">追加</span>
                                                    </th>
                                                    <td class="add_patrol">
                                                        <?php foreach ($dispPtl[1] as $ptlId => $patVal): ?>
                                                            <input type="hidden" name="upPtl[1][<?= $ptlId ?>][unique_id]" value="<?= $patVal['unique_id'] ?>">
                                                            <div style="display:flex;">
                                                                <span>
                                                                    <input type="text" name="upPtl[1][<?= $ptlId ?>][name]" placeholder="入力してください" value="<?= $patVal['name'] ?>" style="width:230px;">
                                                                    <?php foreach ($gnrList['事業所管理']['連携定期巡回事業所名'] as $id2 => $ofcType): ?>
                                                                        <input type="radio" name="upPtl[1][<?= $ptlId ?>][type]" value="<?= $ofcType ?>" <?= $ofcType == $patVal['type'] ? ' checked' : '' ?> style="width:20px;"><?= $ofcType ?>
                                                                    <?php endforeach; ?>
                                                                    <button type="submit" name="btnDelPtl" value="<?= $patVal['unique_id'] ?>" class="btn trash2 del del_patrol"></button>
                                                                </span>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="box2">
                                            <table>
                                                <tr>
                                                    <th><label class="label_t" for="office_no">指定事業所番号</label></th>
                                                    <td><input type="text" name="upAry[1][office_no]" id="office_no" value="<?= $dispData[1]['office_no'] ?>"></td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" style="line-height:120%;"  for="station_code">ステーション<br/>コード</label></th>
                                                    <td><input type="text" name="upAry[1][station_code]" id="station_code" value="<?= $dispData[1]['station_code'] ?>"></td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" for="chiiki_tanka">地域単価</label></th>
                                                    <td><input type="text" name="upAry[1][price]" id="chiiki_tanka" value="<?= $dispData[1]['price'] ?>" pattern="^[0-9]+$"></td>
                                                </tr>
                                                <tr>
                                                    <th><label class="label_t" for="diff_sys-code">別システムコード</label></th>
                                                    <td><input type="text" name="upAry[1][other_code]" id="diff_sys-code" value="<?= $dispData[1]['other_code'] ?>"></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="box3">
                                            <dl>
                                                <dt>訪問看護加算（介護）</dt>
                                                <dd><ul>
                                                        <li><div class="label_t">特別地域訪問看護加算１・２</div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['特別地域訪問看護加算１・２'] as $id => $temp): ?>
                                                                    <span><input type="checkbox" name="upAry[1][select7][]" value="<?= $temp ?>" <?= mb_strpos($dispData[1]['select7'], $temp) !== false ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                        <li><div class="label_t">訪問看護小規模事業所加算１・２</div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['訪問看護小規模事業所加算１・２'] as $id => $temp): ?>
                                                                    <span><input type="checkbox" name="upAry[1][select8][]" value="<?= $temp ?>" <?= mb_strpos($dispData[1]['select8'], $temp) !== false ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                        <li><div class="label_t">訪問看護中山間地域提供加算１・２</div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['訪問看護中山間地域提供加算１・２'] as $id => $temp): ?>
                                                                    <span><input type="checkbox" name="upAry[1][select9][]" value="<?= $temp ?>" <?= mb_strpos($dispData[1]['select9'], $temp) !== false ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                        <li><div class="label_t">訪問看護体制強化加算Ⅰ・Ⅱ</div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['訪問看護体制強化加算Ⅰ・Ⅱ'] as $id => $temp): ?>
                                                                    <span><input type="radio" name="upAry[1][select10]" value="<?= $temp ?>" <?= mb_strpos($dispData[1]['select10'], $temp) !== false ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                        <li><div class="label_t">訪問看護サービス提供体制加算Ⅰ１・Ⅱ１</div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['訪問看護サービス提供体制加算Ⅰ１・Ⅱ１'] as $id => $temp): ?>
                                                                    <span><input type="radio" name="upAry[1][select11]" value="<?= $temp ?>" <?= mb_strpos($dispData[1]['select11'], $temp) !== false ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                        <li><div class="label_t">訪問看護サービス提供体制加算Ⅰ２・Ⅱ２</div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['訪問看護サービス提供体制加算Ⅰ２・Ⅱ２'] as $id => $temp): ?>
                                                                    <span><input type="radio" name="upAry[1][select12]" value="<?= $temp ?>" <?= mb_strpos($dispData[1]['select12'], $temp) !== false ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                        <li><div class="label_t">緊急時訪問看護加算</div>
                                                            <div class="opt_s"><span><input type="checkbox" name="upAry[1][select13]" value="1" <?= ($dispData[1]['select13'] == '1') ? 'checked' : '' ?>></span></div>
                                                        </li>
                                                    </ul></dd>
                                            </dl>
                                            <dl>
                                                <dt>訪問看護加算（医療）</dt>
                                                <dd>
                                                    <ul>
                                                        <li><div class="label_t"><label>24時間対応体制加算</label></div>
                                                            <div><input type="checkbox" name="upAry[1][select14]" value="1" <?= ($dispData[1]['select14'] == '1') ? 'checked' : '' ?>></span></div>
                                                        </li>
                                                        <li><div class="label_t"><label>機能強化型訪問看護管理療養費1・２・３</label></div>
                                                            <div class="opt_s">
                                                                <?php foreach ($gnrList['事業所管理']['機能強化型訪問看護管理療養費１・２・３'] as $id => $temp): ?>
                                                                    <span><input type="radio" name="upAry[1][select15]" value="<?= $temp ?>" <?= mb_strpos($dispData[1]['select15'], $temp) !== false ? 'checked' : '' ?>><label><?= $temp ?></label></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="nurse_record record9">
                                        <div class="i_register">
                                            <span class="label_t text_blue">初回登録：</span>
                                            <span class="label_t hidzuke"><?= $dispData[0]['create_day'] ?></span>
                                            <span class="label_t time"><?= $dispData[0]['create_time'] ?></span>
                                            <span class="label_t"><?= $dispData[0]['create_name'] ?></span>|
                                        </div>
                                        <div class="l_update">
                                            <span class="label_t text_blue">更新日時：</span>
                                            <span class="label_t hidzuke"><?= $dispData[0]['update_day'] ?></span>
                                            <span class="label_t time"><?= $dispData[0]['update_time'] ?></span>
                                            <span class="label_t"><?= $dispData[0]['update_name'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/// CONTENT_END ///-->
                        <div class="fixed_navi staff-navi">
                            <div class="box">
                                <div class="controls">
                                    <div class="btn cancel"><a href="/place/news/index.php">キャンセル</a></div>
                                    <button type="submit" name="btnEntry" value="true" class="btn save search">保存</button> 
                                </div>
                            </div>
                        </div>
                    </form>
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
        <script>
            //行追加
            function addCarInput(t) {
                var element = $(".cars" + t);
                var len = $(element).find("div").length;
                var addTxt = "";
                addTxt += '<div style="display: flex;">';
                addTxt += '    <input type="text" name="upCar[' + t + '][' + len + '][name]" value="" style="width:200px;"  placeholder="入力してください">';
                addTxt += '    <button type="button" value="" class="btn trash2 del del_cars car' + t + '_del">';
                addTxt += '</div>';
                $(addTxt).appendTo(element);
            }

            $(function () {
                $(".addcars0").on('click', function () {
                    addCarInput("0");
                });
                //削除
                $(document).on('click', ".car0_del", function () {
                    $(this).parent().remove();
                    return false;
                });
                $(".addcars1").on('click', function () {
                    addCarInput("1");
                });
                //削除
                $(document).on('click', ".car1_del", function () {
                    $(this).parent().remove();
                    return false;
                });
                // 連携定期巡回事業所追加
                $(".addPatrol").on('click', function () {
                    addPatrolInput();
                });
                //削除
                $(document).on('click', ".patrol_del", function () {
                    $(this).parent().remove();
                    return false;
                });
               
               　// 看護小規模多機能履歴送信
                $(document).on("change", ".sendHisNo1", function(){
                    var id = "<?= $plcId ?>";
                    var dataVal = $(this).val();
                    window.location.href = location.href + "&office1=" + dataVal;
                });
                
               　// 訪問看護履歴送信
                $(document).on("change", ".sendHisNo2", function(){
                    var id = "<?= $plcId ?>";
                    var dataVal = $(this).val();
                    window.location.href = location.href + "&office2=" + dataVal;
                });

            });

            function addPatrolInput() {
                var element = $(".add_patrol");
                var len = $(element).find("div").length;
                var addTxt = "";
                addTxt += '<div >';
                addTxt += '    <span>';
                addTxt += '       <input type="text" name="upPtl[1][' + len + '][name]" placeholder="入力してください" value="" style="width:230px;">';
                <?php foreach ($gnrList['事業所管理']['連携定期巡回事業所名'] as $id2 => $ofcType) : ?>
                    addTxt += '            <input type="radio" name="upPtl[1][' + len + '][type]" value="<?= $ofcType ?>" style="width:20px;"><?= $ofcType ?>';
                <?php endforeach; ?>
                addTxt += '        <button type="button" name="btnDelPtl" value="" class="btn trash2 del  del_patrol patrol_del"></button>';
                addTxt += '    </span>';
                addTxt += '</div>';
                $(addTxt).appendTo(element);
            }

            //----------------------------------------------------
            // 共通都道府県市区町村変更関数
            //----------------------------------------------------
            $(function () {
                // 都道府県変更イベント
                $(".prefecture_name0").on("change", function () {
                    changePref(0);
                });
                // 郵便番号変更イベント
                $(".zip_code0").on("change", function () {
                    changePref(0);
                });
                // 都道府県変更イベント
                $(".prefecture_name1").on("change", function () {
                    changePref(1);
                });
                // 郵便番号変更イベント
                $(".zip_code1").on("change", function () {
                    changePref(1);
                });

            });

            // 都道府県情報で市区町村セレクトボックスを切り替える
            function changePref(no) {
                var options = $(".area_name" + no).find("option");
                // セレクトボックスを非表示にする
                options.each(function () {
                    $(this).hide();
                });

                var selPrefName = $(".prefecture_name" + no).val();
                options.each(function () {
                    var prefName = $(this).data("pref_name");
                    // 名称が一致したものだけを表示対象にする
                    if (!selPrefName || selPrefName === prefName) {
                        $(this).show();
                    } else {
                        // 非表示対象の物は選択状態を解除
                        $(this).removeAttr("selected");
                    }
                });
            }
            
        </script>
    </body>
</html>