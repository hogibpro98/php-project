
<?php
ini_set('memory_limit', '512M');
?>
<?php require_once(dirname(__FILE__) . "/php/user_edit.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <script src="/user/edit/js/user.js"></script>
        <title>利用者基本情報 詳細 - やさしい手</title>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <form action="/user/edit/?user=<?= $userId ?>&tab=<?= $tab ?>" method="post" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">
                        <h2 class="tit_sm">利用者基本情報 詳細</h2>
                        <div id="patient" class="sm"><?= $dispData['standard']['last_name'] . $dispData['standard']['first_name'] ?></div>
                        <div id="subpage"><div id="user-new" class="nursing user_info">

                                <div class="wrap">

                                    <ul class="user-tab">
                                        <li class="active"><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
                                        <li><a href="/report/print_list/?user=<?= $userId ?>">各種帳票</a></li>
                                        <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
                                    </ul>

                                    <?php if ($userId): ?>
                                        <div class="user-details">
                                            <div class="d_left">
                                                <dl class="sm">
                                                    <dt><span class="label_t">利用者ID/内部ID</span></dt>
                                                    <dd><?= $dispData['standard']['other_id'] . ' / ' . $dispData['standard']['unique_id'] ?></dd>
                                                </dl>
                                                <dl class="col1 pc">
                                                    <dt><span class="label_t">利用者ID</span></dt>
                                                    <dd><?= $dispData['standard']['other_id'] ?></dd>
                                                </dl>
                                                <dl class="col2 pc">
                                                    <dt><span class="label_t">内部ID</span></dt>
                                                    <dd><?= $dispData['standard']['unique_id'] ?></dd>
                                                </dl>
                                                <dl class="col1 name_u">
                                                    <dt><span class="label_t">氏名</span></dt>
                                                    <dd><span class="name"><?= $dispData['standard']['last_name'] ?></span><span class="name"><?= $dispData['standard']['first_name'] ?></span></dd>
                                                </dl>
                                                <dl class="col2 status_u">
                                                    <dt><span class="label_t">契約状態</span></dt>
                                                    <!--<dd><span class="<?= $dispData['standard']['st_cls'] ?>"><?= $dispData['standard']['status'] ?></span></dd>-->
                                                    <dd><?= $dispData['standard']['status'] ?></dd>
                                                </dl>
                                                <dl class="col1 name_u">
                                                    <dt><span class="label_t">氏名(カナ)</span></dt>
                                                    <dd><span class="name"><?= $dispData['standard']['last_kana'] ?></span><span class="name"><?= $dispData['standard']['first_kana'] ?></span></dd>
                                                </dl>
                                                <dl class="col2 gender_u">
                                                    <dt><span class="label_t">性別</span></dt>
                                                    <dd><?= $dispData['standard']['sex'] ?></dd>
                                                </dl>
                                                <dl class="col1 d_birth">
                                                    <dt><span class="label_t">生年月日</span></dt>
                                                    <dd><span class="d_yr"><?= $dispData['standard']['birthday'] ?></span></dd>
                                                    <dd></dd>
                                                </dl>
                                                <dl class="col1 map_b">
                                                    <dt><span class="label_t">住所</span></dt>
                                                    <dd><?= $dispData['standard']['prefecture'] . $dispData['standard']['area'] . $dispData['standard']['address1'] . $dispData['standard']['address2'] . $dispData['standard']['address3'] ?></dd>
                                                    <dd><span class="map"><a href="http://local.google.co.jp/maps?q=<?= $dispData['standard']['prefecture'] . $dispData['standard']['area'] . $dispData['standard']['address1'] . $dispData['standard']['address2'] . $dispData['standard']['address3'] ?>" target="_blank">地図</a></span></dd>
                                                </dl>
                                                <div class="con_num">                
                                                    <dl class="pc">
                                                        <dt><span class="label_t">電話番号①</span></dt>
                                                        <dd><?= $dispData['standard']['tel1'] ?></dd>
                                                    </dl>
                                                    <dl class="pc">
                                                        <dt><span class="label_t">電話番号②</span></dt>
                                                        <dd><?= $dispData['standard']['tel2'] ?></dd>                
                                                    </dl>
                                                    <dl class="sm">
                                                        <dt><span class="label_t">連絡先①/②</span></dt>
                                                        <dd><?= $dispData['standard']['tel1'] . ' / ' . $dispData['standard']['tel2'] ?></dd>
                                                    </dl>
                                                </div>
                                                <dl class="note">
                                                    <dt><span class="label_t">利用者メモ</span></dt>
                                                    <dd><textarea name="upAry[remarks]" class="userRemarks" value="<?= $dispData['standard']['remarks'] ?>"><?= $dispData['standard']['remarks'] ?></textarea></dd>
                                                    <input type="hidden" name="upAry[unique_id]" value="<?= $dispData['standard']['unique_id'] ?>">
                                                </dl>
                                            </div>
                                            <div class="d_right">
                                                <dl>
                                                    <dt><span class="label_t">サービス利用区分</span></dt>
                                                    <!--<dd><span class="<?= $dispData['standard']['sv_cls'] ?>"><?= $dispData['standard']['service_type'] ?></span></dd>-->
                                                    <dd><?= $dispData['standard']['service_type'] ?></dd>
                                                </dl>
                                                <table class="em_contact">
                                                    <thead>
                                                        <tr>                            
                                                            <th>緊急連絡先</th>
                                                            <td><span class="label_t">氏名</span></td>
                                                            <td><span class="label_t">電話番号</span></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th>1</th>
                                                            <td><span class=""><?= $dispData['emergency'][0]['name'] ?></span></td>
                                                            <td><span class=""><?= $dispData['emergency'][0]['tel1'] ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <th>2</th>
                                                            <td><span class=""><?= $dispData['emergency'][1]['name'] ?></span></td>
                                                            <td><span class=""><?= $dispData['emergency'][1]['tel1'] ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <th>3</th>
                                                            <td><span class=""><?= $dispData['emergency'][2]['name'] ?></span></td>
                                                            <td><span class=""><?= $dispData['emergency'][2]['tel1'] ?></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="sup_contact">
                                                    <tr>
                                                        <th colspan="2">居宅介護支援事業所情報</th>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="label_t">事業所名称</span></td>
                                                        <td><?= $dispData['standard']['office2_name'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="label_t">担当者</span><span class="label_t sm">/電話番号</span></td>
                                                        <td><span class=""><?= $dispData['standard']['office2_person'] ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="label_t">電話番号</span></td>
                                                        <td><?= $dispData['standard']['office2_tel'] ?></td>
                                                    </tr>
                                                </table>
                                                <table class="shuji_contact">
                                                    <tr>
                                                        <th colspan="2">主治医情報</th>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="label_t">医療機関名</span></td>
                                                        <td><?= $dispData['standard']['medical_hospital'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="label_t">主治医</span></td>
                                                        <td><div class=""><?= $dispData['standard']['medical_doctor'] ?></div></td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="label_t">電話番号①</span></td>
                                                        <td><?= $dispData['standard']['medical_tel'] ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="modal_setting2"></div>
                                    <div class="modal_setting"></div>

                                    <div class="accor_box">        
                                        <div class="accor_tab-s sm">入力タブ切替</div>
                                        <ul class="accor_tab">
                                            <li class="<?= $tab == 1 ? 'active' : null ?>">
                                                <button type="submit" name="tab" value="1">基本情報</button>
                                                <?php if (!empty($ngList['tab1'])): ?>
                                                    <span class="ng">NG</span>
                                                <?php endif; ?>
                                            </li>
                                            <li class="<?= $tab == 2 ? 'active' : null ?>">
                                                <button type="submit" name="tab" value="2">支払方法</button>
                                                <?php if (!empty($ngList['tab2'])): ?>
                                                    <span class="ng">NG</span>
                                                <?php endif; ?>
                                            </li>
                                            <li class="<?= $tab == 3 ? 'active' : null ?>">
                                                <button type="submit" name="tab" value="3">保険証</button>
                                                <?php if (!empty($ngList['tab3'])): ?>
                                                    <span class="ng">NG</span>
                                                <?php endif; ?>
                                            </li>
                                            <li class="<?= $tab == 4 ? 'active' : null ?>">
                                                <button type="submit" name="tab" value="4">医療情報</button>
                                                <?php if (!empty($ngList['tab4'])): ?>
                                                    <span class="ng">NG</span>
                                                <?php endif; ?>
                                            </li>
                                            <li class="<?= $tab == 5 ? 'active' : null ?>">
                                                <button type="submit" name="tab" value="5">緊急連絡先</button>
                                                <?php if (!empty($ngList['tab5'])): ?>
                                                    <span class="ng">NG</span>
                                                <?php endif; ?>
                                            </li>
                                            <li class="<?= $tab == 6 ? 'active' : null ?>">
                                                <button type="submit" name="tab" value="6">流入流出情報</button>
                                                <?php if (!empty($ngList['tab6'])): ?>
                                                    <span class="ng">NG</span>
                                                <?php endif; ?>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="content_box">

                                        <?php if ($tab == 1): ?>
                                            <div class="basic_info con_box">
                                                <div class="box-l">
                                                    <div class="tab">
                                                        <table>
                                                            <tr class="tr1">
                                                                <th><span class="label_t">利用者ID</span></th>
                                                                <td>
                                                                    <input type="text" name="upAry[other_id]" id="user_ID" value="<?= $dispData['standard']['other_id'] ?>" maxlength="7" pattern="^[0-9]+$">
                                                                    <input type="hidden" name="upAry[unique_id]" id="std_user_unique_id" value="<?= $dispData['standard']['unique_id'] ?>">
                                                                    <?php if ($dplIcon['other_id']): ?>
                                                                        <span class="no_dup"><a href="javascript:openDupList();" class="no_dup">*既に登録済みのIDです</a></span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <div class="tab-main">
                                                            <!-- タブ切り替え -->
                                                        </div>
                                                        <table>
                                                            <tr>
                                                                <th><span class="label_t">契約事業所</span></th>
                                                                <td>
                                                                    <div class="hist_box">
                                                                        <div class="tit tit_toggle2">履歴<span class="btn add office3" style="margin-left:220px;margin-right:10px;" >新規追加</span></div>
                                                                        <div class="hist_list" >
                                                                            <?php $i = 0; ?>
                                                                            <ul class="tab-btn office_history">
                                                                                <?php foreach ($dispData['office1'] as $key => $val): ?>
                                                                                    <?php
                                                                                    if ($key == 'def') {
                                                                                        continue;
                                                                                    } else {
                                                                                        $i++;
                                                                                    }
                                                                                    ?>
                                                                                    <li style="display:flex;" class="history_<?= $val['unique_id'] ?>">
                                                                                        
                                                                                        <span style="width:120px">
                                                                                            <?= $val['start_day'] ?><br/>〜<?= $val['end_day'] ?>
                                                                                        </span>
                                                                                        <div style="width:200px"><?= $val['office_name'] ?></div>
                                                                                        <button type="button" class="btn-edit office office_edit modal_open" 
                                                                                                name="" value="<?= $val['unique_id'] ?>"
                                                                                                data-url="/user/edit/dialog/office3.php?user_office_id=<?= $val['unique_id'] ?>&office_name=<?= $val['office_name'] ?>&office_id=<?= $val['office_id'] ?>&mode=edit
                                                                                                data-id="<?= $val['unique_id'] ?>" 
                                                                                                data-user_office_id="<?= $val['unique_id'] ?>"
                                                                                                data-office_id="<?= $val['office_id'] ?>"
                                                                                                data-office_no="<?= $val['office_no'] ?>"
                                                                                                data-start_day="<?= $val['start_day'] ?>"
                                                                                                data-end_day="<?= $val['end_day'] ?>"
                                                                                                data-office_name="<?= $val['office_name'] ?>"
                                                                                                data-dialog_name="dynamic_modal">編集</button>
                                                                                        <button type="submit" class="btn-del row_delete2" name="btnDelOffice" value="<?= $val['unique_id'] ?>" style="margin-left:10px;">削除</button>
                                                                                    </li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                        </table>

                                                        <table>
                                                            <tr class="tr3">
                                                                <th>
                                                                    <span class="label_t label_y">氏名</span>
                                                                </th>
                                                                <td>
                                                                    <input type="text" name="upAry[last_name]" id="lname" class="name" maxlength="30" value="<?= $dispData['standard']['last_name'] ?>">
                                                                    <input type="text" name="upAry[first_name]" id="fname" class="name" maxlength="30" value="<?= $dispData['standard']['first_name'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr class="tr4">
                                                                <th>
                                                                    <span class="label_t">氏名(カナ)</span><span class="req">*</span>
                                                                </th>
                                                                <td>
                                                                    <input type="text" name="upAry[last_kana]" id="lname" class="name standard_last_kana" maxlength="30" pattern="[\u30A1-\u30F6]*" value="<?= $dispData['standard']['last_kana'] ?>">
                                                                    <input type="text" name="upAry[first_kana]" id="fname" class="name standard_first_kana" maxlength="30" pattern="[\u30A1-\u30F6]*" value="<?= $dispData['standard']['first_kana'] ?>">
                                                                    <?php if ($dplIcon['kana'] && $dplIcon['birthday']): ?>
                                                                        <span class="no_dup"><a href="javascript:openDupList();" class="no_dup">*既に登録済みです</a></span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="tr5">
                                                                <th><span class="label_t">性別</span><span class="req">*</span></th>
                                                                <td>
                                                                    <?php $check = !empty($dispData['standard']['sex']) && $dispData['standard']['sex'] == '女性' ? ' checked' : null; ?>
                                                                    <span><input type="radio" name="upAry[sex]" id="mode1" value="女性"<?= $check ?>><label for="mode1">女性</label></span>
                                                                    <?php $check = !empty($dispData['standard']['sex']) && $dispData['standard']['sex'] == '男性' ? ' checked' : null; ?>
                                                                    <span><input type="radio" name="upAry[sex]" id="mode1" value="男性"<?= $check ?>><label for="mode1">男性</label></span>
                                                                </td>
                                                            </tr>
                                                            <tr class="d_birth tr6">
                                                                <th><span class="label_t">生年月日</span><span class="req">*</span></th>
                                                                <td class="birthday">
                                                                    <select name="upDummy[std_nengo]" id="era_list">
                                                                        <option value=""></option>
                                                                        <?php $select = $dispData['standard']['nengo'] === '明治' ? ' selected' : null; ?>
                                                                        <option value="明治"<?= $select ?>>明治</option>
                                                                        <?php $select = $dispData['standard']['nengo'] === '大正' ? ' selected' : null; ?>
                                                                        <option value="大正"<?= $select ?>>大正</option>
                                                                        <?php $select = $dispData['standard']['nengo'] === '昭和' ? ' selected' : null; ?>
                                                                        <option value="昭和"<?= $select ?>>昭和</option>
                                                                        <?php $select = $dispData['standard']['nengo'] === '平成' ? ' selected' : null; ?>
                                                                        <option value="平成"<?= $select ?>>平成</option>
                                                                        <?php $select = $dispData['standard']['nengo'] === '令和' ? ' selected' : null; ?>
                                                                        <option value="令和"<?= $select ?>>令和</option>
                                                                    </select>
                                                                    <span><input type="text" name="upDummy[std_wareki]" maxlength="2" pattern="^[0-9]+$" value="<?= $dispData['standard']['wareki'] ?>" id="era_yr" class="b_ymd standard_wareki"><label for="era_yr">年</label></span>
                                                                    <span><input type="text" name="upDummy[std_year]" maxlength="4" pattern="^[0-9]+$" value="<?= $dispData['standard']['year'] ?>" placeholder="西暦" id="birth_yr" style="width:60px;" class="standard_year"><label for="birth_yr">年</label></span><br class="sm" />
                                                                    <span><input type="text" name="upDummy[std_month]" maxlength="2" pattern="^[0-9]+$" value="<?= $dispData['standard']['month'] ?>" id="birth_m" class="b_ymd standard_month"><label for="birth_m">月</label></span>
                                                                    <span><input type="text" name="upDummy[std_day]" maxlength="2" pattern="^[0-9]+$" value="<?= $dispData['standard']['day'] ?>" id="birth_d" class="b_ymd standard_day"><label for="birth_d">日</label></span>    
                                                                    <span><input type="text" name="upDummy[std_age]" maxlength="3" pattern="^[0-9]+$" value="<?= $dispData['standard']['age'] ?>" id="birth_age" class="b_ymd standard_age" style="width:50px;" readonly><label for="birth_d">歳</label></span>    
                                                                    <!-- <span><?php //$dispData['standard']['age']?>歳</span> -->
                                                                    <?php if ($dplIcon['kana'] && $dplIcon['birthday']): ?>
                                                                        <span class="no_dup"><a href="javascript:openDupList();" class="no_dup">*既に登録済みです</a></span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="tr7">
                                                                <th><span class="label_t label_y">住所</span></th>
                                                                <td><div class="box-i1">
                                                                        <div>
                                                                            <span class="label_t label_y"><label for="prefecture">都道府県</label></span>
                                                                            <select name="upAry[prefecture]" id="prefecture" class="f-keyVal">
                                                                                <option value="">▼選択</option>
                                                                                <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                                                    <?php $select = $pref === $dispData['standard']['prefecture'] ? ' selected' : null; ?>
                                                                                    <option value="<?= $pref ?>"<?= $select ?>><?= $pref ?></option>
                                                                                <?php endforeach; ?>
                                                                                <!-- ※クラス名で市区町村と連携 -->
                                                                            </select>
                                                                        </div>
                                                                        <div>
                                                                            <span class="label_t label_y"><label for="municipal">市区町村</label></span>
                                                                            <select name="upAry[area]" id="municipal" class="f-keyVal">
                                                                                <option value="">▼選択</option>
                                                                                <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                                                    <?php foreach ($areaMst2 as $city => $areaMst3): ?>
                                                                                        <?php $select = $city === $dispData['standard']['area'] ? ' selected' : null; ?>
                                                                                        <option class="<?= $pref ?>" value="<?= $city ?>"<?= $select ?>><?= $city ?></option>
                                                                                    <?php endforeach; ?>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                        <div>
                                                                            <span class="label_t label_y"><label for="town">町域</label></span>
                                                                            <dd class="f-keyData" data-tg_url='/user/edit/ajax/post_ajax.php?type=town'>
                                                                                <input type="text" name="upAry[address1]" maxlength="100" id="address1" value="<?= $dispData['standard']['address1'] ?>" class="f-keyVal">
                                                                            </dd>
                                                                        </div>
                                                                    </div>
                                                                    <div class="box-i2">
                                                                        <div>
                                                                            <span class="label_t label_y"><label for="address">番地</label></span>
                                                                            <input type="text" name="upAry[address2]" id="address" maxlength="100" value="<?= $dispData['standard']['address2'] ?>">
                                                                        </div>
                                                                        <div>
                                                                            <span class="label_t"><label for="building_no">建物名</label></span>
                                                                            <input type="text" name="upAry[address3]" id="building_no" maxlength="100" value="<?= $dispData['standard']['address3'] ?>">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="tr8">
                                                                <th><span class="label_t label_y">郵便番号</span></th>
                                                                <td>
                                                                    <div>
                                                                        <input type="text" name="upAry[post]" id="post" pattern="\d{3}-?\d{4}" value="<?= $dispData['standard']['post'] ?>">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="bango_l tr9">
                                                                <th><span class="label_t">電話番号①</span><span class="req">*</span></th>
                                                                <td><div><input type="tel" name="upAry[tel1]" id="bango1" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['standard']['tel1'] ?>"></div>
                                                                    <div class="pc"><span class="label_t">FAX</span><input type="tel" name="upAry[fax]" id="fax" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['standard']['fax'] ?>"></div>
                                                                </td>
                                                            </tr>
                                                            <tr class="tr10">
                                                                <th><span class="label_t">電話番号②</span></th>
                                                                <td><div><input type="tel" name="upAry[tel2]" id="bango2" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['standard']['tel2'] ?>"></div></td>
                                                            </tr>
                                        <!--                    <tr class="fax sm">
                                                                <th><span class="label_t">FAX</span></th>
                                                                <td><input type="tel" name="upAry[fax]" id="fax" value="<?= $dispData['standard']['fax'] ?>"></td>
                                                            </tr>-->
                                                            <tr class="tr11">
                                                                <th><span class="label_t">メールアドレス</span></th>
                                                                <td><div><input type="email" name="upAry[mail]" id="email" maxlength="100" value="<?= $dispData['standard']['mail'] ?>"></div></td>
                                                            </tr>
                                                            <tr class="category tr12">
                                                                <th><span class="label_t">世帯区分<br class="sm" />/メモ</span></th>
                                                                <td>
                                                                    <div>
                                                                        <select name='upAry[household_type]' id="cat_list">
                                                                            <option value=""></option>
                                                                            <?php foreach ($codeList['利用者基本情報_基本情報']['世帯区分／メモ'] as $val): ?>
                                                                                <?php $select = $dispData['standard']['household_type'] == $val ? ' selected' : null; ?>
                                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                        <span>
                                                                            <input type="text" name="upAry[household_memo]" id="memo" placeholder="入力してください" maxlength="256" value="<?= $dispData['standard']['household_memo'] ?>">
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="box-r">
                                                    <table>
                                                        <tr class="tr13">
                                                            <th><span class="label_t">サービス利用区分</span><span class="req">*</span></th>
                                                            <td><div><ul>
                                                                        <?php $check = !empty($dispData['standard']['service_type']) && $dispData['standard']['service_type'] == '医療保険訪問看護' ? ' checked' : null; ?>
                                                                        <li><input type="radio" name="upAry[service_type]" value="医療保険訪問看護"<?= $check ?> id="service_cat1"><label for="service_cat1">医療保険訪問看護</label></li>
                                                                        <?php $check = !empty($dispData['standard']['service_type']) && $dispData['standard']['service_type'] == '看護小規模多機能' ? ' checked' : null; ?>
                                                                        <li><input type="radio" name="upAry[service_type]" value="看護小規模多機能"<?= $check ?> id="service_cat2"><label for="service_cat2">看護小規模多機能</label></li>
                                                                        <?php $check = !empty($dispData['standard']['service_type']) && $dispData['standard']['service_type'] == '指定訪問看護' ? ' checked' : null; ?>
                                                                        <li><input type="radio" name="upAry[service_type]" value="指定訪問看護"<?= $check ?> id="service_cat3"><label for="service_cat3">指定訪問看護</label></li>
                                                                        <?php $check = !empty($dispData['standard']['service_type']) && $dispData['standard']['service_type'] == '定期巡回' ? ' checked' : null; ?>
                                                                        <li><input type="radio" name="upAry[service_type]" value="定期巡回"<?= $check ?> id="service_cat4"><label for="service_cat4">定期巡回</label></li>
                                                                        <?php $check = !empty($dispData['standard']['service_type']) && $dispData['standard']['service_type'] == '医療保険訪問看護+看護小規模多機能' ? ' checked' : null; ?>
                                                                        <li><input type="radio" name="upAry[service_type]" value="医療保険訪問看護+看護小規模多機能"<?= $check ?> id="service_cat5"><label for="service_cat5">医療保険訪問看護+看護小規模多機能</label></li>
                                                                        <?php $check = !empty($dispData['standard']['service_type']) && $dispData['standard']['service_type'] == '医療保険訪問看護+指定訪問看護' ? ' checked' : null; ?>
                                                                        <li><input type="radio" name="upAry[service_type]" value="医療保険訪問看護+指定訪問看護"<?= $check ?> id="service_cat6"><label for="service_cat6">医療保険訪問看護+指定訪問看護</label></li>
                                                                        <?php $check = !empty($dispData['standard']['service_type']) && $dispData['standard']['service_type'] == '医療保険訪問看護+定期巡回' ? ' checked' : null; ?>
                                                                        <li><input type="radio" name="upAry[service_type]" value="医療保険訪問看護+定期巡回"<?= $check ?> id="service_cat7"><label for="service_cat7">医療保険訪問看護+定期巡回</label></li>
                                                                        <?php $check = !empty($dispData['standard']['service_type']) && $dispData['standard']['service_type'] == '医療訪看・指定訪看・看多機' ? ' checked' : null; ?>
                                                                        <li><input type="radio" name="upAry[service_type]" value="医療訪看・指定訪看・看多機"<?= $check ?> id="service_cat8"><label for="service_cat8">医療訪看・指定訪看・看多機</label></li>
                                                                        <?php $check = !empty($dispData['standard']['service_type']) && $dispData['standard']['service_type'] == '指定訪看・看多機' ? ' checked' : null; ?>
                                                                        <li><input type="radio" name="upAry[service_type]" value="指定訪看・看多機"<?= $check ?> id="service_cat9"><label for="service_cat9">指定訪看・看多機</label></li>
                                                                    </ul></div></td>
                                                        </tr>    
                                                        <tr class="tr14">
                                                            <th><span class="label_t">画像</span></th>
                                                            <td>
                                                                <div>
                                                                    <dl class="img_tit-box" id="img_box">
                                                                        <!--<dt>画像タイトル<span class="btn add ">行追加</span></dt>-->
                                                                        <dt>画像タイトル</dt>
                                                                        <?php $i = 0; ?>
                                                                        <?php foreach ($dispData['image'] as $key => $val): ?>
                                                                            <?php
                                                                            if ($key != 'def') {
                                                                                $i++;
                                                                            }
                                                                            ?>
                                                                            <?php $tgt = $key == 'def' ? '&nbsp;&nbsp;&nbsp;' : $i; ?>
                                                                            <dd class="imageｰdd">
                                                                                <span><?= $tgt ?></span>
                                                                                <select name="upImg[<?= $key ?>][tag]">
                                                                                    <option value="">▼タグ選択</option>
                                                                                    <?php foreach ($codeList['画像関連']['絞り込み'] as $dat): ?>
                                                                                        <?php $select = $val['tag'] === $dat ? ' selected' : null; ?>
                                                                                        <option value="<?= $dat ?>"<?= $select ?>><?= $dat ?></option>
                                                                                    <?php endforeach; ?>
                                                                                </select>

                                                                                <input type="month" name="upImg[<?= $key ?>][month]" value="<?= $val['month'] ?>" style="width:130px;">

                                                                                <?php if ($val['image']): ?>
                                                                                    <a href="<?= $val['image'] ?>" data-lightbox="image-1" class="btn display2">表示</a>
                                                                                <?php endif; ?>
                                                                                <?php if ($key != 'def'): ?>
                                                                                    <button type="submit" class="btn-del2" name="btnDelImg" value="<?= $val['unique_id'] ?>">削除</button>
                                                                                <?php else: ?>
                                                                                    <!--<button type="button" class="btn-del2" name="" value="">削除</button>-->
                                                                                <?php endif; ?>

                                                                                <label>
                                                                                    <span class="btn upload"><img src="/common/image/icon_upload.png" alt=""></span>
                                                                                    <input type="file" name="<?= $key ?>" id="<?= $key ?>" style="display:none;">
                                                                                </label>

                                                                                <input type="hidden" name="upImg[<?= $key ?>][unique_id]" value="<?= $val['unique_id'] ?>">
                                                                            </dd>
                                                                        <?php endforeach; ?>
                                                                    </dl>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="category tr15">
                                                            <th><span class="label_t">入浴/メモ</span></th>
                                                            <td>
                                                                <div>
                                                                    <select name='upAry[bath_type]' id="cat_list">
                                                                        <option value=""></option>
                                                                        <?php $select = $dispData['standard']['bath_type'] == '一般浴' ? ' selected' : null; ?>
                                                                        <option value="一般浴"<?= $select ?>>一般浴</option>
                                                                        <?php $select = $dispData['standard']['bath_type'] == 'チェアー浴' ? ' selected' : null; ?>
                                                                        <option value="チェアー浴"<?= $select ?>>チェアー浴</option>
                                                                        <?php $select = $dispData['standard']['bath_type'] == 'ストレッチャー浴' ? ' selected' : null; ?>
                                                                        <option value="ストレッチャー浴"<?= $select ?>>ストレッチャー浴</option>
                                                                    </select>
                                                                    <span>
                                                                        <input type="text" name="upAry[bath_memo]" maxlength="256" value="<?= $dispData['standard']['bath_memo'] ?>" id="memo" placeholder="入力してください">
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>    
                                                        <tr class="category tr16">
                                                            <th><span class="label_t">排泄/メモ</span></th>
                                                            <td>
                                                                <div>
                                                                    <select name='upAry[excretion_type]' id="cat_list">
                                                                        <option value=""></option>
                                                                        <?php $select = $dispData['standard']['excretion_type'] == 'バルーン' ? ' selected' : null; ?>
                                                                        <option value="バルーン"<?= $select ?>>バルーン</option>
                                                                        <?php $select = $dispData['standard']['excretion_type'] == 'おむつ' ? ' selected' : null; ?>
                                                                        <option value="おむつ"<?= $select ?>>おむつ</option>
                                                                        <?php $select = $dispData['standard']['excretion_type'] == '誘導' ? ' selected' : null; ?>
                                                                        <option value="誘導"<?= $select ?>>誘導</option>
                                                                    </select>
                                                                    <span>
                                                                        <input type="text" name="upAry[excretion_memo]" maxlength="256" value="<?= $dispData['standard']['excretion_memo'] ?>" id="memo" placeholder="入力してください">
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>    
                                                        <tr class="category tr17">
                                                            <th><span class="label_t">食事/メモ</span></th>
                                                            <td>
                                                                <div>
                                                                    <select name='upAry[meal_type]' id="cat_list">
                                                                        <option value=""></option>
                                                                        <?php $select = $dispData['standard']['meal_type'] == '介助' ? ' selected' : null; ?>
                                                                        <option value="介助"<?= $select ?>>介助</option>
                                                                        <?php $select = $dispData['standard']['meal_type'] == '経管栄養' ? ' selected' : null; ?>
                                                                        <option value="経管栄養"<?= $select ?>>経管栄養</option>
                                                                    </select>
                                                                    <span>
                                                                        <input type="text" name="upAry[meal_memo]" maxlength="256" value="<?= $dispData['standard']['meal_memo'] ?>" id="memo" placeholder="入力してください">
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($tab == 2): ?>
                                            <div class="payment_mode con_box">
                                                <dl class="mode_box">
                                                    <dt><span class="label_t label_y">支払方法</span><span class="req">*</span></dt>
                                                    <dd>
                                                        <?php $check = empty($dispData['pay']['unique_id']) || $dispData['pay']['method'] == '引き落とし' ? ' checked' : null; ?>
                                                        <span><input type="radio" name="upPay[method]" id="mode1" value="引き落とし"<?= $check ?>><label for="mode1">引き落とし</label></span>
                                                        <?php $check = !empty($dispData['pay']['method']) && $dispData['pay']['method'] == '振込' ? ' checked' : null; ?>
                                                        <span><input type="radio" name="upPay[method]" id="mode2" value="振込"<?= $check ?>><label for="mode2">振込</label></span>
                                                        <?php $check = !empty($dispData['pay']['method']) && $dispData['pay']['method'] == '現金' ? ' checked' : null; ?>
                                                        <span><input type="radio" name="upPay[method]" id="mode3" value="現金"<?= $check ?>><label for="mode3">現金</label></span>
                                                    </dd>
                                                </dl>
                                                <dl class="financial_cat">
                                                    <dt><span class="label_t label_y">金融機関区分</span><span class="req">*</span></dt>
                                                    <dd><select name="upPay[bank_type]" id="financial_cat">
                                                            <?php $select = $dispData['pay']['bank_type'] !== 'ゆうちょ銀行' ? ' selected' : null; ?>
                                                            <option value="銀行" <?= $select ?>>銀行</option>
                                                            <?php $select = $dispData['pay']['bank_type'] == 'ゆうちょ銀行' ? ' selected' : null; ?>
                                                            <option value="ゆうちょ銀行" <?= $select ?>>ゆうちょ銀行</option>
                                                        </select>
                                                    </dd>
                                                </dl>
                                                <!--            <div class="btn clear-bg">クリア</div>-->
                                                <!--<div class="isOpt1 hidden">-->
                                                <div class="">
                                                    <div class="opt_list1">
                                                        <dl>
                                                            <dt><span class="label_t label_y">金融機関コード</span><span class="req">*</span><span class="text_blue">4桁の数字</span></dt>
                                                            <dd class="f-keyData" data-tg_url='/user/edit/ajax/bank_ajax.php?type=bankCD'>
                                                                <span>
                                                                    <input type="text" name="upPay[bank_code]" id="bank_code1" maxlength="5" pattern="^[0-9]+$" value="<?= $dispData['pay']['bank_code'] ?>" class="f-keyVal">
                                                                </span>
                                                                <span class="btn search bank">
                                                                    <a href="https://zengin.ajtw.net/" target="_brank">金融機関コードを調べる</a>
                                                                </span>
                                                            </dd>
                                                        </dl>
                                                        <dl>
                                                            <dt><span class="label_t label_y">金融機関名</span><span class="req">*</span><span class="text_blue">最大全角10文字</span></dt>
                                                            <dd><span><input type="text" name="upPay[bank_name]" id="financial_name1" maxlength="10" pattern="^[^\x01-\x7E\uFF61-\uFF9F]+$" value="<?= $dispData['pay']['bank_name'] ?>"></span></dd>
                                                        </dl>
                                                    </div>        
                                                    <div class="opt_list2">
                                                        <dl>
                                                            <dt><span class="label_t label_y">支店コード</span><span class="req">*</span><span class="text_blue">5桁の数字</span></dt>
                                                            <dd class="f-keyData" data-tg_url='/user/edit/ajax/bank_ajax.php?type=branchCD'>
                                                                <span><input type="text" name="upPay[branch_code]" id="branch_code1" maxlength="5" pattern="^[0-9]+$" value="<?= $dispData['pay']['branch_code'] ?>" class="f-keyVal"></span>
                                                            </dd>
                                                        </dl>    
                                                        <dl>
                                                            <dt><span class="label_t label_y">支店名</span><span class="req">*</span><span class="text_blue">最大全角10文字</span></dt>
                                                            <dd><span><input type="text" name="upPay[branch_name]" value="<?= $dispData['pay']['branch_name'] ?>" id="branch_name1" maxlength="10" pattern="^[^\x01-\x7E\uFF61-\uFF9F]+$" placeholder="池尻大橋"></span></dd>
                                                        </dl>    
                                                        <dl>
                                                            <dt><span class="label_t label_y">口座番号</span><span class="req">*</span><span class="text_blue">7桁の数字</span></dt>
                                                            <dd><span><input type="text" name="upPay[deposit_code]" id="acc_num1" maxlength="7" pattern="^[0-9]+$" value="<?= $dispData['pay']['deposit_code'] ?>"></span></dd>
                                                        </dl>    
                                                        <dl>
                                                            <dt><span class="label_t label_y">預金者名</span><span class="req">*</span><span class="text_blue">半角カナのみ/<br class="sm"/>最大20文字</span></dt>
                                                            <dd><span><input type="text" name="upPay[deposit_name]" id="depositor_name1" maxlength="20" pattern="[\uFF66-\uFF9F\s+]*" value="<?= $dispData['pay']['deposit_name'] ?>"></span></dd>
                                                        </dl>    
                                                    </div>        
                                                    <div class="opt_list3">
                                                        <dl>
                                                            <dt><span class="label_t label_y">預金種別</span><span class="req">*</span></dt>
                                                            <dd>
                                                                <select name="upPay[deposit_type]" id="mode_deposit1">
                                                                    <option hidden disabled>&nbsp;</option>
                                                                    <?php foreach ($codeList['利用者基本情報_支払方法']['預金種別'] as $codeVal): ?>
                                                                        <?php $select = $dispData['pay']['deposit_type'] == $codeVal ? ' selected' : null; ?>
                                                                        <option value="<?= $codeVal ?>"<?= $select ?>><?= $codeVal ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </dd>
                                                        </dl>                        
                                                    </div>
                                                    <tr>
                                                    <input type="hidden" name="upPay[unique_id]" value="<?= $dispData['pay']['unique_id'] ?>">
                                                    <input type="hidden" name="upPay[user_id]" value="<?= $dispData['standard']['unique_id'] ?>">
                                                    </tr>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($tab == 3): ?>
                                            <div class="insurance_card con_box">
                                                <div class="insurance_box">
                                                    <div class="ncare_card tab"><!-- ★切り替えグループ親 -->
                                                        <div class="tit no_bg tit_toggle">介護保険証</div>
                                                        <div class="box-i child_toggle">
                                                            <span class="btn add ins1-edit">新規</span>
                                                            <div class="box-i1 list_scroll-x">
                                                                <table class="ins_table1 ">
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th><span >認定日</span></th>
                                                                            <th><span class="label_y">認定有効期間</span></th>
                                                                            <th><span class="label_y">要介護度</span></th>
                                                                            <th><span class="label_y">保険者番号</span></th>
                                                                            <th><span class="label_y">被保険者番号</span></th>
                                                                            <th><span class="label_y">区分支給限度額管理期間</span></th>
                                                                            <th></th>
                                                                        </tr>    
                                                                    </thead>
                                                                    <tbody class="tab-btn"><!-- ★切り替えグループ：クリック要素 -->
                                                                        <?php foreach ($dispData['insure1'] as $key => $val): ?>
                                                                            <?php if ($key !== 'def'): ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <?php if ($key == 'def'): ?>
                                                                                            <span class="new">new</span>
                                                                                        <?php endif; ?>
                                                                                        <?php if (isset($ngList['insure1'][$key])): ?> 
                                                                                            <span class="ng">NG</span>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td><?= $val['certif_day'] ?></td>
                                                                                    <td>
                                                                                        <span><?= $val['start_day1'] ?></span>
                                                                                        <small>～</small>
                                                                                        <span><?= $val['end_day1'] ?></span>
                                                                                    </td>
                                                                                    <td><?= $val['care_rank'] ?></td>
                                                                                    <td><?= $val['insure_no'] ?></td>
                                                                                    <td><?= $val['insured_no'] ?></td>
                                                                                    <td>
                                                                                        <span><?= $val['start_day2'] ?></span>
                                                                                        <small>～</small>
                                                                                        <span><?= $val['end_day2'] ?></span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button" class="btn-edit ins1-edit" name="btnEditIns1" value="<?= $val['unique_id'] ?>"
                                                                                                data-ins1_id           ="<?= $val['unique_id'] ?>"
                                                                                                data-ins1_start_nengo  ="<?= $val['ins1_start_nengo'] ?>"
                                                                                                data-ins1_start_year1  ="<?= $val['start_year1'] ?>"
                                                                                                data-ins1_start_month1 ="<?= $val['start_month1'] ?>"
                                                                                                data-ins1_start_dt1    ="<?= $val['start_dt1'] ?>"
                                                                                                data-ins1_end_nengo    ="<?= $val['ins1_end_nengo'] ?>"
                                                                                                data-ins1_end_year1    ="<?= $val['end_year1'] ?>"
                                                                                                data-ins1_end_month1   ="<?= $val['end_month1'] ?>"
                                                                                                data-ins1_end_dt1      ="<?= $val['end_dt1'] ?>"
                                                                                                data-ins1_start_nengo2 ="<?= $val['ins1_start_nengo2'] ?>"
                                                                                                data-ins1_start_year2  ="<?= $val['start_year2'] ?>"
                                                                                                data-ins1_start_month2 ="<?= $val['start_month2'] ?>"
                                                                                                data-ins1_start_dt2    ="<?= $val['start_dt2'] ?>"
                                                                                                data-ins1_end_nengo2   ="<?= $val['ins1_end_nengo2'] ?>"
                                                                                                data-ins1_end_year2    ="<?= $val['end_year2'] ?>"
                                                                                                data-ins1_end_month2   ="<?= $val['end_month2'] ?>"
                                                                                                data-ins1_end_dt2      ="<?= $val['end_dt2'] ?>"
                                                                                                data-ins1_insure_no    ="<?= $val['insure_no'] ?>"
                                                                                                data-ins1_insured_no   ="<?= $val['insured_no'] ?>"
                                                                                                data-ins1_care_rank    ="<?= $val['care_rank'] ?>"
                                                                                                data-ins1_certif_nengo ="<?= $val['ins1_certif_nengo'] ?>"
                                                                                                data-ins1_certif_year  ="<?= $val['certif_year'] ?>"
                                                                                                data-ins1_certif_month ="<?= $val['certif_month'] ?>"
                                                                                                data-ins1_certif_dt    ="<?= $val['certif_dt'] ?>"
                                                                                                >
                                                                                            編集
                                                                                        </button>
                                                                                        <button type="submit" class="btn-del" name="btnDelIns1" value="<?= $val['unique_id'] ?>">削除</button>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <!-- ★切り替え対象 -->
                                                            <div class="tab-main">

                                                                <!-- 内容 -->
                                                                <?php foreach ($dispData['insure1'] as $key => $val): ?>
                                                                    <?php if ($key !== 'def'): ?>
                                                                        <div class="tab-main-box">
                                                                            <div style="display:flex;">
                                                                                <div class="box-i2">
                                                                                    <dl>
                                                                                        <dt><span class="label_t ">認定日</span></dt>
                                                                                        <dd>
                                                                                            <div>
                                                                                                <select name="" id="era_list" class="bg-gray2" disabled="">
                                                                                                    <option value=""></option>
                                                                                                    <?php $select = !empty($val['ins1_certif_nengo']) && $val['ins1_certif_nengo'] === '昭和' ? ' selected' : null; ?>
                                                                                                    <option value="昭和"<?= $select ?>>昭和</option>
                                                                                                    <?php $select = !empty($val['ins1_certif_nengo']) && $val['ins1_certif_nengo'] === '平成' ? ' selected' : null; ?>
                                                                                                    <option value="平成"<?= $select ?>>平成</option>
                                                                                                    <?php $select = !empty($val['ins1_certif_nengo']) && $val['ins1_certif_nengo'] === '令和' ? ' selected' : null; ?>
                                                                                                    <option value="令和"<?= $select ?>>令和</option>
                                                                                                </select>
                                                                                                <span><input type="text" name="" value="<?= $val['certif_year'] ?>" id="birth_yr" class="b_ymd ins1_start_year1 bg-gray2" readonly=""><label for="birth_yr">年</label></span>
                                                                                                <span><input type="text" name="" value="<?= $val['certif_month'] ?>" id="birth_m" class="b_ymd bg-gray2" readonly=""><label for="birth_m">月</label></span>
                                                                                                <span><input type="text" name="" value="<?= $val['certif_dt'] ?>" id="birth_d" class="b_ymd bg-gray2" readonly=""><label for="birth_d">日</label></span>    
                                                                                            </div>
                                                                                        </dd>
                                                                                    </dl>
                                                                                    <dl>
                                                                                        <dt><span class="label_t label_y">認定有効期間</span></dt>
                                                                                        <dd>
                                                                                            <div>
                                                                                                <select name="" id="era_list" class="bg-gray2" disabled="">
                                                                                                    <option value=""></option>
                                                                                                    <?php $select = !empty($val['ins1_start_nengo']) && $val['ins1_start_nengo'] === '昭和' ? ' selected' : null; ?>
                                                                                                    <option value="昭和"<?= $select ?>>昭和</option>
                                                                                                    <?php $select = !empty($val['ins1_start_nengo']) && $val['ins1_start_nengo'] === '平成' ? ' selected' : null; ?>
                                                                                                    <option value="平成"<?= $select ?>>平成</option>
                                                                                                    <?php $select = !empty($val['ins1_start_nengo']) && $val['ins1_start_nengo'] === '令和' ? ' selected' : null; ?>
                                                                                                    <option value="令和"<?= $select ?>>令和</option>
                                                                                                </select>
                                                                                                <span><input type="text" name="" value="<?= $val['start_year1'] ?>" id="birth_yr" class="b_ymd ins1_start_year1 bg-gray2" readonly=""><label for="birth_yr">年</label></span>
                                                                                                <span><input type="text" name="" value="<?= $val['start_month1'] ?>" id="birth_m" class="b_ymd bg-gray2" readonly=""><label for="birth_m">月</label></span>
                                                                                                <span><input type="text" name="" value="<?= $val['start_dt1'] ?>" id="birth_d" class="b_ymd bg-gray2" readonly=""><label for="birth_d">日</label></span>    
                                                                                            </div>
                                                                                            <small>～</small>
                                                                                            <div>
                                                                                                <select name="" id="era_list" class="tgt_en1 bg-gray2" disabled="">
                                                                                                    <option value=""></option>
                                                                                                    <?php $select = !empty($val['ins1_end_nengo']) && $val['ins1_end_nengo'] === '昭和' ? ' selected' : null; ?>
                                                                                                    <option value="昭和"<?= $select ?>>昭和</option>
                                                                                                    <?php $select = !empty($val['ins1_end_nengo']) && $val['ins1_end_nengo'] === '平成' ? ' selected' : null; ?>
                                                                                                    <option value="平成"<?= $select ?>>平成</option>
                                                                                                    <?php $select = !empty($val['ins1_end_nengo']) && $val['ins1_end_nengo'] === '令和' ? ' selected' : null; ?>
                                                                                                    <option value="令和"<?= $select ?>>令和</option>
                                                                                                </select>
                                                                                                <span><input type="text" name="" value="<?= $val['end_year1'] ?>" id="birth_yr" class="b_ymd bg-gray2" readonly=""><label for="birth_yr">年</label></span>
                                                                                                <span><input type="text" name="" value="<?= $val['end_month1'] ?>" id="birth_m" class="b_ymd bg-gray2" readonly=""><label for="birth_m">月</label></span>
                                                                                                <span><input type="text" name="" value="<?= $val['end_dt1'] ?>" id="birth_d" class="b_ymd bg-gray2" readonly=""><label for="birth_d">日</label></span>    
                                                                                            </div>
                                                                                        </dd>
                                                                                    </dl>
                                                                                    <dl>
                                                                                        <dt><span class="label_t label_y">区分支給<br class="sm" />限度額<br class="pc" />管理<br class="sm" />期間</span></dt>
                                                                                        <dd>
                                                                                            <div>
                                                                                                <select name="" id="era_list" class="bg-gray2" disabled="">
                                                                                                    <option value=""></option>
                                                                                                    <?php $select = !empty($val['ins1_start_nengo2']) && $val['ins1_start_nengo2'] === '昭和' ? ' selected' : null; ?>
                                                                                                    <option value="昭和"<?= $select ?>>昭和</option>
                                                                                                    <?php $select = !empty($val['ins1_start_nengo2']) && $val['ins1_start_nengo2'] === '平成' ? ' selected' : null; ?>
                                                                                                    <option value="平成"<?= $select ?>>平成</option>
                                                                                                    <?php $select = !empty($val['ins1_start_nengo2']) && $val['ins1_start_nengo2'] === '令和' ? ' selected' : null; ?>
                                                                                                    <option value="令和"<?= $select ?>>令和</option>
                                                                                                </select>
                                                                                                <span><input type="text" name="" value="<?= $val['start_year2'] ?>" id="birth_yr" class="b_ymd bg-gray2" readonly=""><label for="birth_yr">年</label></span>
                                                                                                <span><input type="text" name="" value="<?= $val['start_month2'] ?>" id="birth_m" class="b_ymd bg-gray2" readonly=""><label for="birth_m">月</label></span>
                                                                                                <span><input type="text" name="" value="<?= $val['start_dt2'] ?>" id="birth_d" class="b_ymd bg-gray2" readonly=""><label for="birth_d">日</label></span>    
                                                                                            </div>
                                                                                            <small>～</small>
                                                                                            <div>
                                                                                                <select name="" id="era_list" class="tgt_en2 bg-gray2" disabled="">
                                                                                                    <option value=""></option>
                                                                                                    <?php $select = !empty($val['ins1_end_nengo2']) && $val['ins1_end_nengo2'] === '昭和' ? ' selected' : null; ?>
                                                                                                    <option value="昭和"<?= $select ?>>昭和</option>
                                                                                                    <?php $select = !empty($val['ins1_end_nengo2']) && $val['ins1_end_nengo2'] === '平成' ? ' selected' : null; ?>
                                                                                                    <option value="平成"<?= $select ?>>平成</option>
                                                                                                    <?php $select = !empty($val['ins1_end_nengo2']) && $val['ins1_end_nengo2'] === '令和' ? ' selected' : null; ?>
                                                                                                    <option value="令和"<?= $select ?>>令和</option>
                                                                                                </select>
                                                                                                <span><input type="text" name="" maxlength="2" pattern="^[0-9]+$" value="<?= $val['end_year2'] ?>" id="birth_yr" class="b_ymd bg-gray2" readonly=""><label for="birth_yr">年</label></span>
                                                                                                <span><input type="text" name="" maxlength="2" pattern="^[0-9]+$" value="<?= $val['end_month2'] ?>" id="birth_m" class="b_ymd bg-gray2" readonly=""><label for="birth_m">月</label></span>
                                                                                                <span><input type="text" name="" maxlength="2" pattern="^[0-9]+$" value="<?= $val['end_dt2'] ?>" id="birth_d" class="b_ymd bg-gray2" readonly=""><label for="birth_d">日</label></span>    
                                                                                            </div>
                                                                                        </dd>
                                                                                        <!--<dd><span class="valid_copy"><a href="javascript:periodCopy();">認定有効期間をコピー</a></span></dd>-->
                                                                                        <dd></dd>
                                                                                    </dl>
                                                                                </div>

                                                                                <div class="box-i3">
                                                                                    <dl>
                                                                                        <dt><span class="label_t label_y">要介護度</span></dt>
                                                                                        <dd><select name='' id="kaigo_do" class="bg-gray2" disabled="">
                                                                                                <?php $select = empty($val['care_rank']) ? ' selected' : null; ?>
                                                                                                <option value=""<?= $select ?>></option>
                                                                                                <?php $select = $val['care_rank'] == '非該当' ? ' selected' : null; ?>
                                                                                                <option value="非該当"<?= $select ?>>非該当</option>
                                                                                                <?php $select = $val['care_rank'] == '自立' ? ' selected' : null; ?>
                                                                                                <option value="自立"<?= $select ?>>自立</option>
                                                                                                <?php $select = $val['care_rank'] == '事業対象者' ? ' selected' : null; ?>
                                                                                                <option value="事業対象者"<?= $select ?>>事業対象者</option>
                                                                                                <?php $select = $val['care_rank'] == '要支援（経過的要介護）' ? ' selected' : null; ?>
                                                                                                <option value="要支援（経過的要介護）"<?= $select ?>>要支援（経過的要介護）</option>
                                                                                                <?php $select = $val['care_rank'] == '要支援1' ? ' selected' : null; ?>
                                                                                                <option value="要支援1"<?= $select ?>>要支援1</option>
                                                                                                <?php $select = $val['care_rank'] == '要支援2' ? ' selected' : null; ?>
                                                                                                <option value="要支援2"<?= $select ?>>要支援2</option>
                                                                                                <?php $select = $val['care_rank'] == '要介護1' ? ' selected' : null; ?>
                                                                                                <option value="要介護1"<?= $select ?>>要介護1</option>
                                                                                                <?php $select = $val['care_rank'] == '要介護2' ? ' selected' : null; ?>
                                                                                                <option value="要介護2"<?= $select ?>>要介護2</option>
                                                                                                <?php $select = $val['care_rank'] == '要介護3' ? ' selected' : null; ?>
                                                                                                <option value="要介護3"<?= $select ?>>要介護3</option>
                                                                                                <?php $select = $val['care_rank'] == '要介護4' ? ' selected' : null; ?>
                                                                                                <option value="要介護4"<?= $select ?>>要介護4</option>
                                                                                                <?php $select = $val['care_rank'] == '要介護5' ? ' selected' : null; ?>
                                                                                                <option value="要介護5"<?= $select ?>>要介護5</option>
                                                                                            </select></dd>
                                                                                    </dl>
                                                                                </div>
                                                                                <div class="box-i4">
                                                                                    <dl>
                                                                                        <dt><span class="label_t label_y">保険者番号</span><span class="text_blue">最大8桁</span></dt>
                                                                                        <dd><input type="text" name="" id="insurer_num" maxlength="8" value="<?= $val['insure_no'] ?>" class="bg-gray2" readonly=""></dd>
                                                                                    </dl>
                                                                                    <dl>
                                                                                        <dt><span class="label_t label_y">被保険者番号</span><span class="text_blue">最大10桁</span></dt>
                                                                                        <dd><input type="text" name="" id="insured_num" maxlength="10" value="<?= $val['insured_no'] ?>" class="bg-gray2" readonly=""></dd>
                                                                                    </dl>
                                                                                </div>
                                                                                <input type="hidden" name="" value="<?= $val['unique_id'] ?>">
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="inhome-sup_list tab">
                                                        <div class="tit no_bg tit_toggle">居宅支援事業所履歴</div>
                                                        <div class="box-i child_toggle">
                                                            <div class="sup_list">
                                                                <span class="btn add ofc2-edit">新規</span>
                                                                <div class="hist_box">
                                                                    <div class="tit tit_toggle2">履歴</div>
                                                                    <div class="child_toggle2 list_scroll-x">
                                                                        <table class="ins_table2">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th></th>
                                                                                    <th><span class="label_y">開始</span>/終了</th>
                                                                                    <th><span class="label_y">居宅支援事業所名</span></th>
                                                                                    <th><span class="label_y">担当CM名</span></th>
                                                                                    <th></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody class="tab-btn">
                                                                                <?php foreach ($dispData['office2'] as $key => $val): ?>
                                                                                    <?php if ($key !== 'def'): ?>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <?php if ($key == 'def'): ?>
                                                                                                    <span class="new">new</span>
                                                                                                <?php elseif (isset($ngList['office2'][$key])): ?> 
                                                                                                    <span class="ng">NG</span>
                                                                                                <?php endif; ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <span><?= $val['start_day'] ?></span>
                                                                                                <?php if ($key != 'def'): ?>
                                                                                                    <small>～</small>
                                                                                                    <span><?= $val['end_day'] ?></span>
                                                                                                <?php endif; ?>
                                                                                            </td>
                                                                                            <td><?= $val['office_name'] ?></td>
                                                                                            <td><?= $val['person_name'] ?></td>
                                                                                            <td>
                                                                                    <spam>
                                                                                        <button type="button" class="btn-edit ofc2-edit" name="btnEditOfc2" value="<?= $val['unique_id'] ?>"
                                                                                                data-ofc2_id="<?= $val['unique_id'] ?>"
                                                                                                data-ofc2_start_day="<?= $val['start_day'] ?>"
                                                                                                data-ofc2_end_day1="<?= $val['end_day'] ?>"
                                                                                                data-ofc2_office_no="<?= $val['office_code'] ?>"
                                                                                                data-ofc2_office_name="<?= $val['office_name'] ?>"
                                                                                                data-ofc2_address="<?= $val['address'] ?>"
                                                                                                data-ofc2_tel="<?= $val['tel'] ?>"
                                                                                                data-ofc2_fax="<?= $val['fax'] ?>"
                                                                                                data-ofc2_found_day="<?= $val['found_day'] ?>"
                                                                                                data-ofc2_person_name="<?= $val['person_name'] ?>"
                                                                                                data-ofc2_person_kana="<?= $val['person_kana'] ?>"
                                                                                                data-ofc2_plan_type="<?= $val['plan_type'] ?>"
                                                                                                data-ofc2_cancel_type="<?= $val['cancel_type'] ?>"
                                                                                                data-ofc2_cancel_memo="<?= $val['cancel_memo'] ?>">
                                                                                            編集
                                                                                        </button>
                                                                                    </spam>
                                                                                    <spam><button type="submit" class="btn-del" name="btnDelOfc2" value="<?= $val['unique_id'] ?>">削除</button></spam>
                                                                                    </td>
                                                                                    </tr>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="sup_info">
                                                                <div class="tit no_bg">居宅介護支援事業所情報</div>
                                                                <!--<span class="btn clear-bg">クリア</span>-->


                                                                <div class="box-i tab-main">

                                                                    <!-- 内容 -->
                                                                    <?php foreach ($dispData['office2'] as $key => $val): ?>
                                                                        <?php if ($key !== 'def'): ?>
                                                                            <div class="tab-main-box">
                                                                                <table>
                                                                                    <tr>
                                                                                        <th><span class="label_t"><label><span class="label_y">開始</span>/終了</label></span><span class="text_blue">終了は未設定可</span></th>
                                                                                        <td>
                                                                                            <input type="text" name="" class="tgt_ofc1_std bg-gray2" style="width:123px;" value="<?= $val['start_day'] ?>" readonly="">
                                                                                            <small>～</small>
                                                                                            <input type="text" name="" class="bg-gray2" style="width:123px;" value="<?= $val['end_day'] ?>" readonly="">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th><span class="label_t label_y"><label for="office_no">事業所番号</label></span><span class="text_blue">半角10桁</span></th>
                                                                                        <td>
                                                                                            <span><input type="text" name="" id="office_no" maxlength="10" value="<?= $val['office_code'] ?>" class="bg-gray2" readonly=""></span>
                                                <!--                                            <span class="search_db"><a href="#">事業所DB</a></span>
                                                                                            <span class="valid_copy"><a href="#">自事業所反映</a></span>-->
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th><span class="label_t label_y"><label for="business_name">事業所名称</label></span><span class="text_blue">最大全角25文字</span></th>
                                                                                        <td>
                                                                                            <input type="text" name="" class="business_name bg-gray2" maxlength="25" value="<?= $val['office_name'] ?>" readonly="">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th><span class="label_t"><label for="address">所在地</label></span></th>
                                                                                        <td>
                                                                                            <input type="text" name="" id="address" value="<?= $val['address'] ?>" class="bg-gray2" readonly="">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th><span class="label_t label_y"><label for="bango">電話番号</label></span></th>
                                                                                        <td>
                                                                                            <input type="text" id="bango" name="" value="<?= $val['tel'] ?>" class="bg-gray2" readonly="">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th><span class="label_t label_y"><label for="fax">FAX</label></span></th>
                                                                                        <td>
                                                                                            <input type="text" name="" id="fax" value="<?= $val['fax'] ?>" class="bg-gray2" readonly="">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th><span class="label_t"><label for="notif_date">届出年月日</label></span></th>
                                                                                        <td>
                                                                                            <input type="text" name="" class="master_date date_no-Day bg-gray2" id="notif_date" value="<?= $val['found_day'] ?>" readonly="">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th><span class="label_t label_y"><label for="manager">担当者</label></span></th>
                                                                                        <td>
                                                                                            <div>
                                                                                                <input type="text" name="" id="manager" value="<?= $val['person_name'] ?>" class="bg-gray2" readonly="">
                                                                                            </div>
                                                                                            <div>
                                                                                                <span class="label_t"><label for="manager-k">担当者(カナ)</label></span>
                                                                                                <input type="text" name="" id="manager-k" value="<?= $val['person_kana'] ?>" class="bg-gray2" readonly="">
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th><span class="label_t label_y"><label for="plan_div">計画作成<br class="sm" />区分</label></span></th>
                                                                                        <td>
                                                                                            <select name="" id="plan_div" class="bg-gray2" disabled="">
                                                                                                <option value=""></option>
                                                                                                <?php $select = $val['plan_type'] == '居宅支援作成' ? ' selected' : null; ?>
                                                                                                <option value="居宅支援作成"<?= $select ?>>居宅支援作成</option>
                                                                                                <?php $select = $val['plan_type'] == '居宅介護支援事業者作成' ? ' selected' : null; ?>
                                                                                                <option value="居宅介護支援事業者作成"<?= $select ?>>居宅介護支援事業者作成</option>
                                                                                                <?php $select = $val['plan_type'] == '予防支援作成' ? ' selected' : null; ?>
                                                                                                <option value="予防支援作成"<?= $select ?>>予防支援作成</option>
                                                                                                <?php $select = $val['plan_type'] == '自己作成' ? ' selected' : null; ?>
                                                                                                <option value="自己作成"<?= $select ?>>自己作成</option>
                                                                                            </select></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th><span class="label_t"><label for="med_facility">中止理由</label></span></th>
                                                                                        <td>
                                                                                            <select name="" id="med_facility" class="bg-gray2" disabled="">
                                                                                                <option value=""></option>
                                                                                                <?php $select = $val['cancel_type'] == '非該当' ? ' selected' : null; ?>
                                                                                                <option value="非該当"<?= $select ?>>非該当</option>
                                                                                                <?php $select = $val['cancel_type'] == '医療機関入金' ? ' selected' : null; ?>
                                                                                                <option value="医療機関入金"<?= $select ?>>医療機関入金</option>
                                                                                                <?php $select = $val['cancel_type'] == '死亡' ? ' selected' : null; ?>
                                                                                                <option value="死亡"<?= $select ?>>死亡</option>
                                                                                                <?php $select = $val['cancel_type'] == 'その他' ? ' selected' : null; ?>
                                                                                                <option value="その他"<?= $select ?>>その他</option>
                                                                                                <?php $select = $val['cancel_type'] == '介護老人福祉施設入所' ? ' selected' : null; ?>
                                                                                                <option value="介護老人福祉施設入所"<?= $select ?>>介護老人福祉施設入所</option>
                                                                                                <?php $select = $val['cancel_type'] == '介護老人保健施設入所' ? ' selected' : null; ?>
                                                                                                <option value="介護老人保健施設入所"<?= $select ?>>介護老人保健施設入所</option>
                                                                                                <?php $select = $val['cancel_type'] == '介護療養型医療施設入所' ? ' selected' : null; ?>
                                                                                                <option value="介護療養型医療施設入所"<?= $select ?>>介護療養型医療施設入所</option>
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th><span class="label_t"><label for="cancel_riyu">中止理由<br class="sm" />備考</label></span></th>
                                                                                        <td>
                                                                                            <input type="text" name="" id="cancel_riyu" value="<?= $val['cancel_memo'] ?>" class="bg-gray2" readonly="">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <input type="hidden" name="" value="<?= $val['unique_id'] ?>">
                                                                                </table>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>        

                                                </div>
                                                <div class="benefit_info">
                                                    <div class="box-i">
                                                        <div class="tit no_bg tit_toggle">給付情報（負担割合、高所得者情報）</div>                    
                                                        <div class="child_toggle">
                                                            <span class="btn add ins_add3">行追加</span>
                                                            <div class="list_scroll-x">
                                                                <table class="ins_table3" id="ins3">
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th><span class="label_y">有効期間 開始</span></th>
                                                                            <th></th>
                                                                            <th><span class="label_y">終了</span></th>
                                                                            <th><span class="label_y">給付率</span></th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $i = 0; ?>
                                                                        <?php foreach ($dispData['insure2'] as $key => $val): ?>
                                                                            <?php if ($key !== 'def'): ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <?php if (isset($ngList['insure2'][$key])): ?> 
                                                                                            <span class="ng">NG</span>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div>
                                                                                            <select class="era validate[required]" name="upDummy[ins2][<?= $key ?>][start_nengo]">
                                                                                                <option value=""></option>
                                                                                                <?php $select = $val['start_nengo'] === '昭和' ? ' selected' : null; ?>
                                                                                                <option value="昭和"<?= $select ?>>昭和</option>
                                                                                                <?php $select = $val['start_nengo'] === '平成' ? ' selected' : null; ?>
                                                                                                <option value="平成"<?= $select ?>>平成</option>
                                                                                                <?php $select = $val['start_nengo'] === '令和' ? ' selected' : null; ?>
                                                                                                <option value="令和"<?= $select ?>>令和</option>
                                                                                            </select>
                                                                                            <span><input type="text" name="upDummy[ins2][<?= $key ?>][start_year]" maxlength="2" pattern="^[0-9]+$" value="<?= !empty($val['start_year']) ? $val['start_year'] : null ?>" id="birth_yr" class="b_ymd validate[required,maxSize[3]]"><label for="birth_yr">年</label></span>
                                                                                            <span><input type="text" name="upDummy[ins2][<?= $key ?>][start_month]" maxlength="2" pattern="^[0-9]+$" value="<?= !empty($val['start_month']) ? $val['start_month'] : null ?>" id="birth_m" class="b_ymd validate[required,maxSize[2]]"><label for="birth_m">月</label></span>
                                                                                            <span><input type="text" name="upDummy[ins2][<?= $key ?>][start_dt]" maxlength="2" pattern="^[0-9]+$" value="<?= !empty($val['start_dt']) ? $val['start_dt'] : null ?>" id="birth_d" class="b_ymd validate[required,maxSize[2]]"><label for="birth_d">日</label></span>    
                                                                                        </div>
                                                                                    </td>
                                                                                    <td><small>～</small></td>
                                                                                    <td>
                                                                                        <div>
                                                                                            <select class="era validate[required]" name="upDummy[ins2][<?= $key ?>][end_nengo]">
                                                                                                <option value=""></option>
                                                                                                <?php $select = $val['end_nengo'] === '昭和' ? ' selected' : null; ?>
                                                                                                <option value="昭和"<?= $select ?>>昭和</option>
                                                                                                <?php $select = $val['end_nengo'] === '平成' ? ' selected' : null; ?>
                                                                                                <option value="平成"<?= $select ?>>平成</option>
                                                                                                <?php $select = $val['end_nengo'] === '令和' ? ' selected' : null; ?>
                                                                                                <option value="令和"<?= $select ?>>令和</option>
                                                                                            </select>
                                                                                            <span><input type="text" name="upDummy[ins2][<?= $key ?>][end_year]" maxlength="2" pattern="^[0-9]+$" value="<?= !empty($val['end_year']) ? $val['end_year'] : null ?>" id="birth_yr" class="b_ymd validate[required,maxSize[3]]"><label for="birth_yr">年</label></span>
                                                                                            <span><input type="text" name="upDummy[ins2][<?= $key ?>][end_month]" maxlength="2" pattern="^[0-9]+$" value="<?= !empty($val['end_month']) ? $val['end_month'] : null ?>" id="birth_m" class="b_ymd validate[required,maxSize[2]]"><label for="birth_m">月</label></span>
                                                                                            <span><input type="text" name="upDummy[ins2][<?= $key ?>][end_dt]" maxlength="2" pattern="^[0-9]+$" value="<?= !empty($val['end_dt']) ? $val['end_dt'] : null ?>" id="birth_d" class="b_ymd validate[required,maxSize[2]]"><label for="birth_d">日</label></span>    
                                                                                        </div>
                                                                                    </td>
                                                                                    <td><span><input type="text" name="upIns2[<?= $key ?>][rate]" id="rate1" maxlength="3" pattern="^[0-9]+$" class="validate[required,maxSize[3]]" value="<?= $val['rate'] ?>" style="width:50px;"></span></td>
                                                                                    <td>
                                                                                        <button type="submit" class="btn-del" name="btnDelIns2" value="<?= $val['unique_id'] ?>" style="width:60px;">削除</button>
                                                                                    </td>
                                                                                </tr>
                                                                            <input type="hidden" name="upIns2[<?= $key ?>][unique_id]" value="<?= $val['unique_id'] ?>">
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="med_insurance tab"><!-- ★切り替えグループ親 -->
                                                    <div class="tit no_bg tit_toggle">医療保険証</div>
                                                    <div class="box-i child_toggle">
                                                        <div class="hist_box">
                                                            <div class="tit tit_toggle2">履歴</div>
                                                            <div class="child_toggle2">
                                                                <div class="list_scroll-x">
                                                                    <span class="btn add ins3-edit">新規</span>
                                                                    <table class="ins_table4">
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th>有効期間　<span class="">開始</span>/終了</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="tab-btn">
                                                                            <?php foreach ($dispData['insure3'] as $key => $val): ?>
                                                                                <?php if ($key !== 'def'): ?>
                                                                                    <tr class="">
                                                                                        <td>
                                                                                            <?php if ($key == 'def'): ?>
                                                                                                <span class="new">new</span>
                                                                                            <?php elseif (isset($ngList['insure3'][$key])): ?> 
                                                                                                <span class="ng">NG</span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><span><?= $val['start_day'] ?></span>
                                                                                            <?php if ($key != 'def'): ?>
                                                                                                <small>～</small>
                                                                                            <?php endif; ?>                                                
                                                                                            <span><?= $val['end_day'] ?></span></td>
                                                                                        <td>
                                                                                            <span>
                                                                                                <button type="button" class="btn-edit ins3-edit" name="" value="<?= $val['unique_id'] ?>"
                                                                                                        data-ins3_id="<?= $val['unique_id'] ?>"
                                                                                                        data-ins3_start_nengo="<?= $val['start_nengo'] ?>"
                                                                                                        data-ins3_start_year="<?= $val['start_year'] ?>"
                                                                                                        data-ins3_start_month="<?= $val['start_month'] ?>"
                                                                                                        data-ins3_start_dt="<?= $val['start_dt'] ?>"
                                                                                                        data-ins3_end_nengo="<?= $val['end_nengo'] ?>"
                                                                                                        data-ins3_end_year="<?= $val['end_year'] ?>"
                                                                                                        data-ins3_end_month="<?= $val['end_month'] ?>"
                                                                                                        data-ins3_end_dt="<?= $val['end_dt'] ?>"
                                                                                                        data-ins3_select1="<?= $val['select1'] ?>"
                                                                                                        data-ins3_select2="<?= $val['select2'] ?>"
                                                                                                        data-ins3_type1="<?= $val['type1'] ?>"
                                                                                                        data-ins3_type2="<?= $val['type2'] ?>"
                                                                                                        data-ins3_type3="<?= $val['type3'] ?>"
                                                                                                        data-ins3_number1="<?= $val['number1'] ?>"
                                                                                                        data-ins3_number2="<?= $val['number2'] ?>"
                                                                                                        data-ins3_number3="<?= $val['number3'] ?>"
                                                                                                        data-ins3_number4="<?= $val['number4'] ?>"
                                                                                                        data-ins3_number5="<?= $val['number5'] ?>"
                                                                                                        data-ins3_name="<?= $val['name'] ?>"
                                                                                                        data-ins3_type4="<?= $val['type4'] ?>">
                                                                                                    編集
                                                                                                </button>
                                                                                            </span>
                                                                                            <span><button type="submit" class="btn-del" name="btnDelIns3" value="<?= $val['unique_id'] ?>">削除</button></span>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="med_info tab-main">

                                                            <!-- 切り替え内容 -->
                                                            <?php foreach ($dispData['insure3'] as $key => $val): ?>
                                                                <?php if ($key !== 'def'): ?>
                                                                    <div class="tab-main-box">
                                                                        <dl class="med_l1">
                                                                            <dt><span class="label_t"><span class="">開始</span>/終了</span><span class="text_blue">終了は未設定可</span></dt>
                                                                            <dd>
                                                                                <div>
                                                                                    <select class="era bg-gray2" name="" disabled="">
                                                                                        <option value=""></option>
                                                                                        <?php $select = $val['start_nengo'] === '昭和' ? ' selected' : null; ?>
                                                                                        <option value="昭和"<?= $select ?>>昭和</option>
                                                                                        <?php $select = $val['start_nengo'] === '平成' ? ' selected' : null; ?>
                                                                                        <option value="平成"<?= $select ?>>平成</option>
                                                                                        <?php $select = $val['start_nengo'] === '令和' ? ' selected' : null; ?>
                                                                                        <option value="令和"<?= $select ?>>令和</option>
                                                                                    </select>
                                                                                    <span><input type="text" name="" value="<?= $val['start_year'] ?>" id="birth_yr" class="b_ymd bg-gray2" readonly=""><label for="birth_yr">年</label></span>
                                                                                    <span><input type="text" name="" value="<?= $val['start_month'] ?>" id="birth_m" class="b_ymd bg-gray2" readonly=""><label for="birth_m">月</label></span>
                                                                                    <span><input type="text" name="" value="<?= $val['start_dt'] ?>" id="birth_d" class="b_ymd bg-gray2" readonly=""><label for="birth_d">日</label></span>    
                                                                                </div>
                                                                                <small>～</small><br class="sm" />
                                                                                <div>
                                                                                    <select class="era bg-gray2" name="" disabled="">
                                                                                        <option value=""></option>
                                                                                        <?php $select = $val['end_nengo'] === '昭和' ? ' selected' : null; ?>
                                                                                        <option value="昭和"<?= $select ?>>昭和</option>
                                                                                        <?php $select = $val['end_nengo'] === '平成' ? ' selected' : null; ?>
                                                                                        <option value="平成"<?= $select ?>>平成</option>
                                                                                        <?php $select = $val['end_nengo'] === '令和' ? ' selected' : null; ?>
                                                                                        <option value="令和"<?= $select ?>>令和</option>
                                                                                    </select>
                                                                                    <span><input type="text" name="" value="<?= $val['end_year'] ?>" id="birth_yr" class="b_ymd bg-gray2" readonly=""><label for="birth_yr">年</label></span>
                                                                                    <span><input type="text" name="" value="<?= $val['end_month'] ?>" id="birth_m" class="b_ymd bg-gray2" readonly=""><label for="birth_m">月</label></span>
                                                                                    <span><input type="text" name="" value="<?= $val['end_dt'] ?>" id="birth_d" class="b_ymd bg-gray2" readonly=""><label for="birth_d">日</label></span>    
                                                                                </div>
                                                                            </dd>
                                                                        </dl>
                                                                        <dl class="med_l2">
                                                                            <dt><span class="label_t">特定措置による<br/>経過措置</span></dt>
                                                                            <dd>
                                                                                <?php $check = empty($val['select1']) ? ' checked' : null; ?>
                                                                                <span><input type="radio" name="" id="mode1" value=""<?= $check ?> class="bg-gray2" disabled=""><label for="mode1">無</label></span>
                                                                                <?php $check = !empty($val['select1']) ? ' checked' : null; ?>
                                                                                <span><input type="radio" name="" id="mode1" value=""<?= $check ?> class="bg-gray2" disabled=""><label for="mode1">有</label></span>
                                                                            </dd>
                                                                            <dd><span class="label_t">退職者医療<br/>制度区分</span>
                                                                                <?php $check = empty($val['select2']) ? ' checked' : null; ?>
                                                                                <span><input type="radio" name="" id="mode1" value=""<?= $check ?> class="bg-gray2" disabled=""><label for="mode1">無</label></span>
                                                                                <?php $check = !empty($val['select2']) ? ' checked' : null; ?>
                                                                                <span><input type="radio" name="" id="mode1" value=""<?= $check ?> class="bg-gray2" disabled=""><label for="mode1">有</label></span>
                                                                            </dd>
                                                                        </dl>
                                                                        <dl class="med_l3">
                                                                            <dt><span class="label_t "><label for="health_cat">保健区分</label></span></dt>
                                                                            <dd><select name="" id="health_cat" class="bg-gray2" disabled="">
                                                                                    <option value=""></option>
                                                                                    <?php $select = $val['type1'] == '国保' ? ' selected' : null; ?>
                                                                                    <option value="国保"<?= $select ?>>国保</option>
                                                                                    <?php $select = $val['type1'] == '社保' ? ' selected' : null; ?>
                                                                                    <option value="社保"<?= $select ?>>社保</option>
                                                                                    <?php $select = $val['type1'] == '後期高齢者' ? ' selected' : null; ?>
                                                                                    <option value="後期高齢者"<?= $select ?>>後期高齢者</option>
                                                                                    <?php $select = $val['type1'] == '公費のみ' ? ' selected' : null; ?>
                                                                                    <option value="公費のみ"<?= $select ?>>公費のみ</option>
                                                                                    <?php $select = $val['type1'] == '労災' ? ' selected' : null; ?>
                                                                                    <option value="労災"<?= $select ?>>労災</option>
                                                                                    <?php $select = $val['type1'] == '公害' ? ' selected' : null; ?>
                                                                                    <option value="公害"<?= $select ?>>公害</option>
                                                                                    <?php $select = $val['type1'] == 'その他' ? ' selected' : null; ?>
                                                                                    <option value="その他"<?= $select ?>>その他</option>
                                                                                </select>
                                                                            </dd>
                                                                            <dd><span class="label_t "><label for="health_cat">本人区分</label></span>
                                                                                <select name="" id="personal_cat" class="bg-gray2" disabled="">
                                                                                    <option value=""></option>
                                                                                    <?php $select = $val['type2'] == '本人' ? ' selected' : null; ?>
                                                                                    <option value="本人"<?= $select ?>>本人</option>
                                                                                    <?php $select = $val['type2'] == '被扶養者' ? ' selected' : null; ?>
                                                                                    <option value="被扶養者"<?= $select ?>>被扶養者</option>
                                                                                    <?php $select = $val['type2'] == '高齢者' ? ' selected' : null; ?>
                                                                                    <option value="高齢者"<?= $select ?>>高齢者</option>
                                                                                    <?php $select = $val['type2'] == '義務教育就学前' ? ' selected' : null; ?>
                                                                                    <option value="義務教育就学前"<?= $select ?>>義務教育就学前</option>
                                                                                </select>
                                                                            </dd>
                                                                            <dd><span class="label_t "><label for="income_cat">所得区分</label></span>
                                                                                <select name="" id="income_cat" class="bg-gray2" disabled="">
                                                                                    <option value=""></option>
                                                                                    <?php $select = $val['type3'] == '現役並みⅢ' ? ' selected' : null; ?>
                                                                                    <option value="現役並みⅢ"<?= $select ?>>現役並みⅢ</option>
                                                                                    <?php $select = $val['type3'] == '現役並みⅠ' ? ' selected' : null; ?>
                                                                                    <option value="現役並みⅠ"<?= $select ?>>現役並みⅠ</option>
                                                                                    <?php $select = $val['type3'] == '現役並みⅠ' ? ' selected' : null; ?>
                                                                                    <option value="現役並みⅠ"<?= $select ?>>現役並みⅠ</option>
                                                                                    <?php $select = $val['type3'] == '一般所得者' ? ' selected' : null; ?>
                                                                                    <option value="一般所得者"<?= $select ?>>一般所得者</option>
                                                                                    <?php $select = $val['type3'] == '低所得者Ⅱ' ? ' selected' : null; ?>
                                                                                    <option value="低所得者Ⅱ"<?= $select ?>>低所得者Ⅱ</option>
                                                                                    <?php $select = $val['type3'] == '低所得者Ⅰ' ? ' selected' : null; ?>
                                                                                    <option value="低所得者Ⅰ"<?= $select ?>>低所得者Ⅰ</option>
                                                                                    <?php $select = $val['type3'] == '不明' ? ' selected' : null; ?>
                                                                                    <option value="不明"<?= $select ?>>不明</option>
                                                                                </select>
                                                                            </dd>
                                                                        </dl>
                                                                        <dl class="med_l4">
                                                                            <dt><span class="label_t ">保険者番号</span><span class="text_blue">8桁</span></dt>
                                                                            <dd>
                                                                                <input type="text" name="" id="insurer_num" maxlength="8" value="<?= $val['number1'] ?>" class="bg-gray2" readonly="">
                                                                            </dd>
                                                                            <dd><span class="label_t">法別番号</span>
                                                                                <span><input type="text" name="" id="legal_num" value="<?= $val['number2'] ?>" class="bg-gray2" readonly=""></span>
                                                                            </dd>
                                                                        </dl>
                                                                        <dl class="med_l5">
                                                                            <dt><span class="label_t ">記号/番号/枝番</span></dt>
                                                                            <dd><span><input type="text" name="" id="ins_sym" value="<?= $val['number3'] ?>" class="bg-gray2" readonly=""></span>
                                                                                <span><input type="text" name="" value="<?= $val['number4'] ?>" id="ins_num" class="bg-gray2" readonly=""></span>
                                                                                <span><input type="text" name="" value="<?= $val['number5'] ?>" id="ins_branch" class="bg-gray2" readonly=""></span>
                                                                            </dd>
<!--                                                                            <dd><span class="label_t">被保険者番号</span>
                                                                                <span><input type="text" name="" id="_num" value="<?= $val['number2'] ?>" class="bg-gray2" style="width:130px;" readonly=""></span>
                                                                            </dd>-->
                                                                        </dl>
                                                                        <dl class="med_l5">
                                                                            <dt></dt>
                                                                            <dd>
                                                                                <span class="ins_note">※記号・番号・枝番の代わりに被保険者番号を入力する<br/>場合は保険区分を後期高齢者に設定してください。</span>
                                                                            </dd>
                                                                        </dl>
                                                                        <dl class="med_l6">
                                                                            <dt><span class="label_t "><label for="insurance_name">保険名称</label></span></dt>
                                                                            <dd>
                                                                                <select name="" id="insurance_name" class="bg-gray2" disabled="">
                                                                                    <option data-legal_no="" value=""<?= $select ?>></option>
                                                                                    <?php $select = $val['name'] === "国保(30%" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="" value="国保(30%)"<?= $select ?>>国保(30%)</option>
                                                                                    <?php $select = $val['name'] === "国保(2割)(20％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="" value="国保(2割)(20％)"<?= $select ?>>国保(2割)(20％)</option>
                                                                                    <?php $select = $val['name'] === "国保退職者(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="" value="国保退職者(30％)"<?= $select ?>>国保退職者(30％)</option>
                                                                                    <?php $select = $val['name'] === "(退)警察特定共済組合(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="74" value="(退)警察特定共済組合(30％)"<?= $select ?>>(退)警察特定共済組合(30％)</option>
                                                                                    <?php $select = $val['name'] === "(退)公立学校特定共済組合(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="75" value="(退)公立学校特定共済組合(30％)"<?= $select ?>>(退)公立学校特定共済組合(30％)</option>
                                                                                    <?php $select = $val['name'] === "(退)国家公務員特定共済組合(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="72" value="(退)国家公務員特定共済組合(30％)"<?= $select ?>>(退)国家公務員特定共済組合(30％)</option>
                                                                                    <?php $select = $val['name'] === "(退)地方公務員特定共済組合(30％)" ? ' selected' : null; ?>
                                                                                    <option data-legal_no="73" value="(退)地方公務員特定共済組合(30％)"<?= $select ?>>(退)地方公務員特定共済組合(30％)</option>
                                                                                    <?php $select = $val['name'] === "(退)特定健康保険組合(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="63" value="(退)特定健康保険組合(30％)"<?= $select ?>>(退)特定健康保険組合(30％)</option>
                                                                                    <?php $select = $val['name'] === "(退)日本私立学校振興・共済事業団(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="34" value="(退)日本私立学校振興・共済事業団(30％)"<?= $select ?>>(退)日本私立学校振興・共済事業団(30％)</option>
                                                                                    <?php $select = $val['name'] === "協会けんぽ(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="01" value="協会けんぽ(30％)"<?= $select ?>>協会けんぽ(30％)</option>
                                                                                    <?php $select = $val['name'] === "協会けんぽ(1割)(10％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="01" value="協会けんぽ(1割)(10％)"<?= $select ?>>協会けんぽ(1割)(10％)</option>
                                                                                    <?php $select = $val['name'] === "協会けんぽ(2割)(20％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="01" value="協会けんぽ(2割)(20％)"<?= $select ?>>協会けんぽ(2割)(20％)</option>
                                                                                    <?php $select = $val['name'] === "警察共済組合(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="33" value="警察共済組合(30％)"<?= $select ?>>警察共済組合(30％)</option>
                                                                                    <?php $select = $val['name'] === "公立学校共済組合(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="34" value="公立学校共済組合(30％)"<?= $select ?>>公立学校共済組合(30％)</option>
                                                                                    <?php $select = $val['name'] === "国家公務員共済組合(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="31" value="国家公務員共済組合(30％)"<?= $select ?>>国家公務員共済組合(30％)</option>
                                                                                    <?php $select = $val['name'] === "自衛官" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="07" value="自衛官"<?= $select ?>>自衛官</option>
                                                                                    <?php $select = $val['name'] === "船員(職務外)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="02" value="船員(職務外)"<?= $select ?>>船員(職務外)</option>
                                                                                    <?php $select = $val['name'] === "船員(職務上)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="02" value="船員(職務上)"<?= $select ?>>船員(職務上)</option>
                                                                                    <?php $select = $val['name'] === "組合管掌(30%）" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="06" value="組合管掌(30%）"<?= $select ?>>組合管掌(30%）</option>
                                                                                    <?php $select = $val['name'] === "地方公務員等共済組合(30％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="32" value="地方公務員等共済組合(30％)"<?= $select ?>>地方公務員等共済組合(30％)</option>
                                                                                    <?php $select = $val['name'] === "日雇い(一般)(20％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="03" value="日雇い(一般)(20％)"<?= $select ?>>日雇い(一般)(20％)</option>
                                                                                    <?php $select = $val['name'] === "日雇い(特別)(20％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="04" value="日雇い(特別)(20％)"<?= $select ?>>日雇い(特別)(20％)</option>
                                                                                    <?php $select = $val['name'] === "日本私立学校振興・共済事業団(30％）" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="34" value="日本私立学校振興・共済事業団(30％）"<?= $select ?>>日本私立学校振興・共済事業団(30％）</option>
                                                                                    <?php $select = $val['name'] === "後期高齢者1割(10％）" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="39" value="後期高齢者1割(10％）"<?= $select ?>>後期高齢者1割(10％）</option>
                                                                                    <?php $select = $val['name'] === "後期高齢者2割(20％）" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="39" value="後期高齢者2割(20％）"<?= $select ?>>後期高齢者2割(20％）</option>
                                                                                    <?php $select = $val['name'] === "後期高齢者3割(30％）" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="39" value="後期高齢者3割(30％）"<?= $select ?>>後期高齢者3割(30％）</option>
                                                                                    <?php $select = $val['name'] === "労災(0％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="" value="労災(0％)"<?= $select ?>>労災(0％)</option>
                                                                                    <?php $select = $val['name'] === "公害(0％)" ? ' selected' : null; ?>	
                                                                                    <option data-legal_no="" value="公害(0％)"<?= $select ?>>公害(0％)</option>
                                                                                </select>
                                                                            </dd>
                                                                            <dd><span class="label_t "><label for="prof_jiyu">職務上の事由</label></span>
                                                                                <select name="" id="prof_jiyu" class="bg-gray2" disabled="">
                                                                                    <option value=""></option>
                                                                                    <?php $select = $val['type4'] == 'なし' ? ' selected' : null; ?>
                                                                                    <option value="なし"<?= $select ?>>なし</option>
                                                                                    <?php $select = $val['type4'] == '職務上' ? ' selected' : null; ?>
                                                                                    <option value="職務上"<?= $select ?>>職務上</option>
                                                                                    <?php $select = $val['type4'] == '下船後3カ月以内' ? ' selected' : null; ?>
                                                                                    <option value="下船後3カ月以内"<?= $select ?>>下船後3カ月以内</option>
                                                                                    <?php $select = $val['type4'] == '通勤災害' ? ' selected' : null; ?>
                                                                                    <option value="通勤災害"<?= $select ?>>通勤災害</option>
                                                                                </select>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                    <input type="hidden" name="upIns3[unique_id]" value="<?= $val['unique_id'] ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="pub_exp">
                                                    <div class="tit no_bg tit_toggle">公費</div>
                                                    <div class="box-i child_toggle tab">
                                                        <div class="exp_list">
                                                            <span class="btn add ins4-edit">新規</span>
                                                            <div class="list_scroll-x">
                                                                <table class="ins_table5">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>有効期間　開始/終了</th>
                                                                            <th>法別</th>
                                                                            <th>負担者番号</th>
                                                                            <th>受給者番号</th>
                                                                            <th>負担割合</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="tab-btn">
                                                                        <?php foreach ($dispData['insure4'] as $seq => $val): ?>
                                                                            <?php if ($seq !== 'def'): ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span><?= $val['start_day'] ?></span>
                                                                                        <?php if ($seq != 'def'): ?>
                                                                                            <small>～</small>
                                                                                        <?php endif; ?>
                                                                                        <span><?= $val['end_day'] ?></span>
                                                                                    </td>
                                                                                    <td><?= $val['number1'] ?></td>
                                                                                    <td><?= $val['number2'] ?></td>
                                                                                    <td><?= $val['number3'] ?></td>
                                                                                    <td><?= $val['rate'] ?></td>
                                                                                    <td>
                                                                                        <span>
                                                                                            <button type="button" class="btn-edit ins4-edit" name="btnEditIns4" value="<?= $val['unique_id'] ?>"
                                                                                                    data-ins4_id="<?= $val['unique_id'] ?>"
                                                                                                    data-ins4_start_nengo="<?= $val['start_nengo'] ?>"
                                                                                                    data-ins4_start_year="<?= $val['start_year'] ?>"
                                                                                                    data-ins4_start_month="<?= $val['start_month'] ?>"
                                                                                                    data-ins4_start_dt="<?= $val['start_dt'] ?>"
                                                                                                    data-ins4_end_nengo="<?= $val['end_nengo'] ?>"
                                                                                                    data-ins4_end_year="<?= $val['end_year'] ?>"
                                                                                                    data-ins4_end_month="<?= $val['end_month'] ?>"
                                                                                                    data-ins4_end_dt="<?= $val['end_dt'] ?>"
                                                                                                    data-ins4_name="<?= $val['name'] ?>"
                                                                                                    data-ins4_number1="<?= $val['number1'] ?>"
                                                                                                    data-ins4_number2="<?= $val['number2'] ?>"
                                                                                                    data-ins4_number3="<?= $val['number3'] ?>"
                                                                                                    data-ins4_upper_limit="<?= $val['upper_limit'] ?>"
                                                                                                    data-ins4_rate="<?= $val['rate'] ?>">
                                                                                                編集
                                                                                            </button>
                                                                                            <span><button type="submit" class="btn-del" name="btnDelIns4" value="<?= $val['unique_id'] ?>">削除</button></span>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="exp_info tab-main">

                                                            <!-- 切り替え内容 -->
                                                            <?php foreach ($dispData['insure4'] as $seq => $val): ?>
                                                                <?php if ($seq !== 'def'): ?>
                                                                    <div class="tab-main-box">
                                                                        <dl class="exp_l1">
                                                                            <dt><span class="label_t"><label for="effect_d">開始/終了</label></span></dt>
                                                                            <dd>
                                                                                <div>
                                                                                    <select name="" class="era ins4_sn bg-gray2" disabled="">
                                                                                        <option value=""></option>
                                                                                        <?php $select = $val['start_nengo'] === '昭和' ? ' selected' : null; ?>
                                                                                        <option value="昭和"<?= $select ?>>昭和</option>
                                                                                        <?php $select = $val['start_nengo'] === '平成' ? ' selected' : null; ?>
                                                                                        <option value="平成"<?= $select ?>>平成</option>
                                                                                        <?php $select = $val['start_nengo'] === '令和' ? ' selected' : null; ?>
                                                                                        <option value="令和"<?= $select ?>>令和</option>
                                                                                    </select>
                                                                                    <span><input type="text" name="" value="<?= $val['start_year'] ?>" id="birth_yr" class="b_ymd bg-gray2 ins4_sy" readonly=""><label for="birth_yr">年</label></span>
                                                                                    <span><input type="text" name="" value="<?= $val['start_month'] ?>" id="birth_m" class="b_ymd bg-gray2 ins4_sm" readonly=""><label for="birth_m">月</label></span>
                                                                                    <span><input type="text" name="" value="<?= $val['start_dt'] ?>" id="birth_d" class="b_ymd bg-gray2 ins4_sd" readonly=""><label for="birth_d">日</label></span>
                                                                                </div>
                                                                                <small>～</small><br class="sm" />
                                                                                <div>
                                                                                    <select name="" class="era ins4_en bg-gray2" disabled="">
                                                                                        <option value=""></option>
                                                                                        <?php $select = $val['end_nengo'] === '昭和' ? ' selected' : null; ?>
                                                                                        <option value="昭和"<?= $select ?>>昭和</option>
                                                                                        <?php $select = $val['end_nengo'] === '平成' ? ' selected' : null; ?>
                                                                                        <option value="平成"<?= $select ?>>平成</option>
                                                                                        <?php $select = $val['end_nengo'] === '令和' ? ' selected' : null; ?>
                                                                                        <option value="令和"<?= $select ?>>令和</option>
                                                                                    </select>
                                                                                    <span><input type="text" name="" value="<?= $val['end_year'] ?>" id="birth_yr" class="b_ymd bg-gray2 ins4_ey" readonly=""><label for="birth_yr">年</label></span>
                                                                                    <span><input type="text" name="" value="<?= $val['end_month'] ?>" id="birth_m" class="b_ymd bg-gray2 ins4_em" readonly=""><label for="birth_m">月</label></span>
                                                                                    <span><input type="text" name="" value="<?= $val['end_day'] ?>" id="birth_d" class="b_ymd bg-gray2 ins4_ed" readonly=""><label for="birth_d">日</label></span>    
                                                                                </div>
                                                                            </dd>
                                                                            <!--<dd><span class="valid_copy"><a href="javascript:addMonth();">1ヶ月有効にする</a></span></dd>-->
                                                                        </dl>
                                                                        <dl class="exp_l2">
                                                                            <dt><span class="label_t"><label for="legal_num2">法別番号</label></span></dt>
                                                                            <dd><input type="text" name="" id="legal_num2" value="<?= $val['number1'] ?>" class="bg-gray2" readonly=""></dd>
                                                                            <dd><span class="label_t"><label for="bearer_num">負担者番号</label></span>
                                                                                <span>
                                                                                    <input type="text" name="" id="bearer_num" value="<?= $val['number2'] ?>" class="bg-gray2" readonly="">
                                                                                </span>
                                                                            </dd>
                                                                        </dl>
                                                                        <dl class="exp_l3">
                                                                            <dt><span class="label_t"><label for="jukyusha_num">受給者番号</label></span></dt>
                                                                            <dd>
                                                                                <input type="text" name="" id="jukyusha_num" value="<?= $val['number3'] ?>" class="bg-gray2" readonly="">
                                                                            </dd>
                                                                        </dl>
                                                                        <dl class="exp_l4">
                                                                            <dt><span class="label_t"><label for="max_am">上限額</label></span></dt>
                                                                            <dd><input type="text" name="" id="max_am" value="<?= $val['upper_limit'] ?>" class="bg-gray2" readonly=""><span class="unit_m">円</span></dd>
                                                                            <dd><span class="label_t"><label for="ratio">負担割合</label></span>
                                                                                <span>
                                                                                    <input type="text" name="" id="ratio" value="<?= $val['rate'] ?>" class="bg-gray2" readonly="">
                                                                                </span>
                                                                                <span class="unit_m">%</span>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                    <input type="hidden" name="" value="<?= $val['unique_id'] ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($tab == 4): ?>
                                            <div class="med_information con_box">
                                                <div class="med_box1 tab">
                                                    <div class="med_lbox">
                                                        <div class="tit no_bg tit_toggle"></div>
                                                        <div class="box-i child_toggle hsp-search">
                                                            <span class="chk_direct">
                                                                <?php $check = $search['sijisyo'] ? ' checked' : null; ?>
                                                                <input type="checkbox" name="search[sijisyo]" value="1" id="direction" onchange="changeSijisyo(<?= $search['sijisyo'] ?>)"<?= $check ?>>
                                                                <label for="direction">指示書</label>
                                                            </span>
                                                            <span class="btn add hsp-edit">新規</span>
                                                            <div class="list_scroll-x">
                                                                <table class="med_table1">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><span class="label_y">開始</span><span>終了</span></th>
                                                                            <th><span class="label_y">医療機関名</span></th>
                                                                            <th><span class="label_y">主治医</span></th>
                                                                            <th><span class="label_y">指示書</span></th>
                                                                            <th>病院/在宅</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="tab-btn">
                                                                        <?php foreach ($dispData['hospital'] as $key => $val): ?>
                                                                            <?php if ($key !== 'def'): ?>
                                                                                <tr class="disnon">
                                                                                    <td>
                                                                                        <span><?= $val['start_day'] ?></span>
                                                                                        <?php if ($key != 'def'): ?>
                                                                                            <small>～</small>
                                                                                        <?php endif; ?>
                                                                                        <span><?= $val['end_day'] ?></span>
                                                                                    </td>
                                                                                    <td><?= $val['name'] ?></td>
                                                                                    <td><?= $val['doctor'] ?></td>
                                                                                    <td><?= $val['select1'] ? '〇' : null; ?></td>
                                                                                    <td><?= $val['type1'] ?></td>
                                                                                    <td>
                                                                                        <button type="button" class="btn-edit hsp-edit" name="btnEditHsp" value="<?= $val['unique_id'] ?>"
                                                                                                data-hsp_id="<?= $val['unique_id'] ?>"
                                                                                                data-hsp_start_day="<?= $val['start_day'] ?>"
                                                                                                data-hsp_end_day="<?= $val['end_day'] ?>"
                                                                                                data-hsp_select1="<?= $val['select1'] ?>"
                                                                                                data-hsp_type1="<?= $val['type1'] ?>"
                                                                                                data-hsp_name="<?= $val['name'] ?>"
                                                                                                data-hsp_disp_name="<?= $val['disp_name'] ?>"
                                                                                                data-hsp_doctor="<?= $val['doctor'] ?>"
                                                                                                data-hsp_address="<?= $val['address'] ?>"
                                                                                                data-hsp_tel1="<?= $val['tel1'] ?>"
                                                                                                data-hsp_tel2="<?= $val['tel2'] ?>"
                                                                                                data-hsp_fax="<?= $val['fax'] ?>">
                                                                                            編集
                                                                                        </button>
                                                                                        <button type="submit" class="btn-del" name="btnDelHsp" value="<?= $val['unique_id'] ?>">削除</button>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="med_lbox">

                                                        <div class="tit no_bg tit_toggle">主治医情報</div>

                                                        <div class="tab-main">

                                                            <!-- 切り替え -->
                                                            <?php foreach ($dispData['hospital'] as $key => $val): ?>
                                                                <?php if ($key !== 'def'): ?>
                                                                    <div class="tab-main-box">
                                                                        <div class="box-i child_toggle">
                                                                            <div class="box-i1">
                                                                                <div>
                                                                                    <span class="label_t label_y"><label>開始</label></span>
                                                                                    <input type="text" name="" class="bg-gray2" value="<?= $val['start_day'] ?>" readonly=""><small>～</small>
                                                                                </div>
                                                                                <div>
                                                                                    <span class="label_t"><label>終了</label><span class="text_blue">未設定可</span></span>
                                                                                    <input type="text" name="" class="bg-gray2" value="<?= $val['end_day'] ?>" readonly="">
                                                                                </div><br class="sm" />
                                                                                <div>
                                                                                    <?php $checked = !empty($val['select1']) ? ' checked' : null; ?>
                                                                                    <input type="checkbox" name="" id="" <?= $checked ?> class="bg-gray2" disabled="">
                                                                                    <span class="label_t label_y"><label for="shijisho_hakko">指示書発行</label></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span class="label_t"><label>病院/在宅</label></span>
                                                                                    <select name="" id="attend_cat" class="bg-gray2" disabled="">
                                                                                        <option value=""></option>
                                                                                        <?php $select = $val['type1'] !== '在宅' ? ' selected' : null; ?>
                                                                                        <option value="病院"<?= $select ?>>病院</option>
                                                                                        <?php $select = $val['type1'] == '在宅' ? ' selected' : null; ?>
                                                                                        <option value="在宅"<?= $select ?>>在宅</option>
                                                                                    </select>
                                                                                </div>
                                                                                <dl>
                                                                                    <dt><span class="label_t label_y"><label for="med_institution-n">医療機関名称</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="text" name='' id="med_institution-n" value="<?= $val['name'] ?>" class="bg-gray2" readonly=""><span class="note1">帳票類の印刷時に出力されます</span>
                                                                                    </dd>
                                                                                </dl>
                                                                            </div>
                                                                            <div class="box-i2">
                                                                                <dl>
                                                                                    <dt><span class="label_t"><label for="receipt_name">レセプト出力用名称</label></span>
                                                                                        <span class="quest"><img src="/common/image/icon_question.png" alt=""></span>
                                                                                        <span class="text_blue">最大16文字まで</span>
                                                                                        <div class="quest_box">
                                                                                            「医療機関名(正式名称)」を入力したときに下記の判定がかかります。<br/><br/>
                                                                                            <b>【16文字以下の場合】</b>
                                                                                            「レセプト出力用名称」に同じものが反映されます。<br/><br/>
                                                                                            <b>【16文字以上の場合】</b>
                                                                                            「レセプト出力用名称」がリセットされ編集可能になります。
                                                                                        </div>
                                                                                    </dt>
                                                                                    <dd><input type="text" name="" id="receipt_name" maxlength="16" value="<?= $val['disp_name'] ?>" class="bg-gray2" readonly="">
                                                                                        <span class="note2">指示書の連携データに出力されます</span>
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl>
                                                                                    <dt><span class="label_t label_y"><label for="attentding_name">主治医</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="text" name="" id="attentding_name" value="<?= $val['doctor'] ?>" class="bg-gray2" readonly="">
                                                                                    </dd>
                                                                                </dl>
                                                                            </div>
                                                                            <div class="box-i3">
                                                                                <dl>
                                                                                    <dt><span class="label_t"><label for="location">所在地</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="text" name="" id="location" value="<?= $val['address'] ?>" style="width:500px;" class="bg-gray2" readonly="">
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl>
                                                                                    <dt><span class="label_t"><label for="bango1">電話番号①</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="tel" name="" id="bango1" value="<?= $val['tel1'] ?>" class="bg-gray2" readonly="">
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl>
                                                                                    <dt><span class="label_t"><label for="bango2">電話番号②</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="tel" name="" id="bango2" value="<?= $val['tel2'] ?>" class="bg-gray2" readonly="">
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl>
                                                                                    <dt><span class="label_t"><label for="fax">FAX</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="tel" name="" id="fax" value="<?= $val['fax'] ?>" class="bg-gray2" readonly="">
                                                                                    </dd>
                                                                                </dl>
                                                                            </div>
                                                                            <input type="hidden" name="" value="<?= $val['unique_id'] ?>">
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="med_box2">
                                                    <div class="tit no_bg tit_toggle">薬情</div>
                                                    <div class="box-i child_toggle">
                                                        <span class="chk_direct">
                                                            <?php $check = $search['drg_disp_flg'] ? ' checked' : null; ?>
                                                            <input type="checkbox" name="search[drg_disp_flg]" id="direction"  onchange="changeYakujo(<?= $search['drg_disp_flg'] ?>)"<?= $check ?>>
                                                            <label for="direction">終了分表示</label>
                                                        </span>
                                                        <span class="btn add med_add2">行追加</span>
                                                        <div class="list_scroll-x">
                                                            <table class="med_table2" id="drug">
                                                                <thead>
                                                                    <tr>
                                                                        <th>処方開始</th>
                                                                        <th></th>
                                                                        <th>処方終了</th>
                                                                        <th>医薬品名</th>
                                                                        <th>用法</th>
                                                                        <th>量</th>
                                                                        <th>効果</th>
                                                                        <th>副作用</th>
                                                                        <th>備考</th>
                                                                        <th></th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($dispData['drug'] as $key => $val): ?>
                                                                        <?php if ($key !== 'def'): ?>
                                                                            <tr>
                                                                                <td><input type="text" name="upDrg[<?= $key ?>][start_day]" class="master_date date_no-Day med_col" value="<?= $val['start_day'] ?>"></td>
                                                                                <td>～</td>
                                                                                <td><input type="text" name="upDrg[<?= $key ?>][end_day]" class="master_date date_no-Day med_col" value="<?= $val['end_day'] ?>"></td>
                                                                                <td><input type="text" name="upDrg[<?= $key ?>][drug_name]" class="iyakuhin_n med_col" maxlength="30" value="<?= $val['drug_name'] ?>"></td>
                                                                                <td>
                                                                                    <select name="upDrg[<?= $key ?>][drug_usage]" class=" med_col">
                                                                                        <option value=""></option>
                                                                                        <?php foreach ($codeList['利用者基本情報_医療情報']['用法'] as $codeVal): ?>
                                                                                            <?php $select = $val['drug_usage'] === $codeVal ? ' selected' : ''; ?>
                                                                                            <option value="<?= $codeVal ?>"<?= $select ?>><?= $codeVal ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td><input type="text" name="upDrg[<?= $key ?>][dose]" class="amount med_col" maxlength="10" value="<?= $val['dose'] ?>"></td>
                                                                                <td><input type="text" name="upDrg[<?= $key ?>][effect]" class="effect med_col" maxlength="30" value="<?= $val['effect'] ?>"></td>
                                                                                <td><input type="text" name="upDrg[<?= $key ?>][side_effect]" class="side_effect med_col" maxlength="30" value="<?= $val['side_effect'] ?>"></td>
                                                                                <td><input type="text" name="upDrg[<?= $key ?>][remarks]" class="remark med_col" value="<?= $val['remarks'] ?>"></td>
                                                                                <td>
                                                                                    <button type="submit" class="btn-del" name="btnDelDrg" value="<?= $val['unique_id'] ?>">削除</button>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="hidden" name="upDrg[<?= $key ?>][unique_id]" value="<?= $dispData['drug'][$key]['unique_id'] ?>">
                                                                                </td>
                                                                                <?php $class = $val['disable'] ? 'disabled' : null; ?>
                                                                                <td class="<?= $class ?>">
                                                                                </td>
                                                                            </tr>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="med_box3">
                                                    <div class="med_ibox">
                                                        <div class="tit no_bg tit_toggle">薬局情報</div>
                                                        <div class="box-i child_toggle">
                                                            <dl class="med_l1">
                                                                <dt><span class="label_t"><label for="iyakuhin_n">名称</label></span></dt>
                                                                <dd><input type="text" name="upMdc[drug_name]" id="iyakuhin_n" class="med_info-n" maxlength="30" value="<?= $dispData['medical']['drug_name'] ?>"></dd>
                                                            </dl>
                                                            <dl class="med_l2">
                                                                <dt><span class="label_t"><label for="iyakuhin_manager">担当者</label></span></dt>
                                                                <dd><input type="text" name="upMdc[drug_person]" id="iyakuhin_manager" class="med_info-m" maxlength="30" value="<?= $dispData['medical']['drug_person'] ?>"></dd>
                                                            </dl>
                                                            <dl class="med_l3">
                                                                <dt><span class="label_t"><label for="iyakuhin_num">電話番号</label></span></dt>
                                                                <dd><input type="tel" name="upMdc[drug_tel]" id="iyakuhin_num" class="med_info-num" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['medical']['drug_tel'] ?>"></dd>
                                                            </dl>
                                                            <dl class="med_l4">
                                                                <dt><span class="label_t"><label for="iyakuhin_fax">FAX</label></span></dt>
                                                                <dd><input type="tel" name="upMdc[drug_fax]" id="iyakuhin_fax" class="med_info-fax" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['medical']['drug_fax'] ?>"></dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                    <div class="med_ibox">
                                                        <div class="tit no_bg tit_toggle">訪問歯科情報</div>
                                                        <div class="box-i child_toggle">
                                                            <dl class="med_l1">
                                                                <dt><span class="label_t"><label for="dentistry_n">名称</label></span></dt>
                                                                <dd><input type="text" name="upMdc[dental_name]" id="dentistry_n" class="med_info-n" maxlength="30" value="<?= $dispData['medical']['dental_name'] ?>"></dd>
                                                            </dl>
                                                            <dl class="med_l2">
                                                                <dt><span class="label_t"><label for="dentistry_manager">担当者</label></span></dt>
                                                                <dd><input type="text" name="upMdc[dental_person]" id="dentistry_manager" class="med_info-m" maxlength="30" value="<?= $dispData['medical']['dental_person'] ?>"></dd>
                                                            </dl>
                                                            <dl class="med_l3">
                                                                <dt><span class="label_t"><label for="dentistry_num">電話番号</label></span></dt>
                                                                <dd><input type="tel" name="upMdc[dental_tel]" id="dentistry_num" class="med_info-num" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['medical']['dental_tel'] ?>"></dd>
                                                            </dl>
                                                            <dl class="med_l4">
                                                                <dt><span class="label_t"><label for="dentistry_fax">FAX</label></span></dt>
                                                                <dd><input type="tel" name="upMdc[dental_fax]" id="dentistry_fax" class="med_info-fax" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['medical']['dental_fax'] ?>"></dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                    <tr>
                                                    <input type="hidden" name="upMdc[unique_id]" value="<?= $dispData['medical']['unique_id'] ?>">
                                                    </tr>
                                                </div>
                                                <!--            <div class="med_box4">
                                                                <div class="tit no_bg tit_toggle">GAF尺度</div>
                                                                <div class="box-i child_toggle">
                                                                    <span><input type="checkbox" name="全て表示" id="show_all"><label for="show_all">全て表示</label></span>
                                                                    <table>
                                                                        <tr class="gaf_act">
                                                                            <th></th>
                                                                            <th>GAF日付</th>
                                                                            <th>GAF点数</th>
                                                                        </tr>
                                                                        <tr class="gaf_act">
                                                                            <td>1</td>
                                                                            <td><input type="text" name="date" class="master_date date_no-Day" value="2021/01/01"></td>
                                                                            <td><input type="text" name="GAF点数" id="gaf_score" value="67"></td>
                                                                        </tr>
                                                                        <tr class="gaf_act">
                                                                            <td>2</td>
                                                                            <td><input type="text" name="date" class="master_date date_no-Day" value="2021/01/01"></td>
                                                                            <td><input type="text" name="GAF点数" id="gaf_score" value="67"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
                                                                            <td><input type="text" name="date" class="master_date date_no-Day" value="2021/01/01"></td>
                                                                            <td><input type="text" name="GAF点数" id="gaf_score" value="67"></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>-->
                                                <div class="med_box5">
                                                    <div class="tit no_bg tit_toggle">サービス開始終了情報<br class="sm" />（サービス開始終了日 医療保険)</div>
                                                    <div class="box-i child_toggle tab">
                                                        <div class="hist_box">
                                                            <div class="tit tit_toggle2">履歴</div>
                                                            <div class="child_toggle2">
                                                                <div class="list_scroll-x">
                                                                    <table class="med_table3">
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th><span>開始</span><span>終了</span></th>
                                                                                <th>開始区分</th>
                                                                                <th><span class="btn add svc-edit">新規</span></th>
                                                                            </tr>                                    
                                                                        </thead>
                                                                        <tbody class="tab-btn">
                                                                            <?php foreach ($dispData['service'] as $key => $val): ?>
                                                                                <?php if ($key != 'def'): ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <?php if ($key == 'def'): ?>
                                                                                                <span class="new">new</span>
                                                                                            <?php endif; ?>
                                                                                            <?php if (isset($ngList['insure3'][$key])): ?>
                                                                                                <span class="ng">NG</span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <span><?= $val['start_day'] ?></span>
                                                                                            <small>～</small>
                                                                                            <span><?= $val['end_day'] ?></span>
                                                                                        </td>
                                                                                        <td><?= $val['start_type'] ?></td>
                                                                                        <td>
                                                                                            <button type="button" class="btn-edit svc-edit" name="btnEditSvc" value="<?= $val['unique_id'] ?>"
                                                                                                    data-svc_id="<?= $val['unique_id'] ?>"
                                                                                                    data-svc_start_day="<?= $val['start_day'] ?>"
                                                                                                    data-svc_end_day="<?= $val['end_day'] ?>"
                                                                                                    data-svc_start_type="<?= $val['start_type'] ?>"
                                                                                                    data-svc_cancel_reason="<?= $val['cancel_reason'] ?>"
                                                                                                    data-svc_death_day="<?= $val['death_day'] ?>"
                                                                                                    data-svc_death_time="<?= $val['death_time'] ?>"
                                                                                                    data-svc_death_place="<?= $val['death_place'] ?>"
                                                                                                    data-svc_death_reason="<?= $val['death_reason'] ?>">
                                                                                                編集
                                                                                            </button>
                                                                                            <button type="submit" class="btn-del" name="btnDelSvc" value="<?= $val['unique_id'] ?>">削除</button>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="service_info tab-main">

                                                            <!-- 切り替え -->
                                                            <?php foreach ($dispData['service'] as $key => $val): ?>
                                                                <?php if ($key !== 'def'): ?>
                                                                    <div class="tab-main-box">
                                                                        <dl>
                                                                            <dt><span class="label_t"><label>訪問看護期間</label></span></dt>
                                                                            <dd><input type="text" name="" class="bg-gray2" value="<?= $val['start_day'] ?>" readonly="">
                                                                                <small>～</small>
                                                                                <input type="text" name="" class="bg-gray2" value="<?= $val['end_day'] ?>" readonly="">
                                                                            </dd>
                                                                        </dl>
                                                                        <dl>
                                                                            <dt><span class="label_t"><label>開始区分</label></span></dt>
                                                                            <dd><select name="" id="kaishi_kubun" class="bg-gray2" disabled="">
                                                                                    <?php $select = $val['start_type'] == '訪問開始' ? ' selected' : null; ?>
                                                                                    <option value=""></option>
                                                                                    <option value="訪問開始"<?= $select ?>>訪問開始</option>
                                                                                </select>                                
                                                                            </dd>
                                                                        </dl>
                                                                        <dl>
                                                                            <dt><span class="label_t"><label>訪問終了の状況</label></span></dt>
                                                                            <dd><select name="" id="visit_stat" class="bg-gray2" disabled="">
                                                                                    <option value=""></option>
                                                                                    <?php $select = $val['支店'] == '軽快' ? ' selected' : null; ?>
                                                                                    <option value="軽快"<?= $select ?>>1:軽快</option>
                                                                                    <?php $select = $val['cancel_reason'] == '施設' ? ' selected' : null; ?>
                                                                                    <option value="施設"<?= $select ?>>2:施設</option>
                                                                                    <?php $select = $val['cancel_reason'] == '医療機関' ? ' selected' : null; ?>
                                                                                    <option value="医療機関"<?= $select ?>>3:医療機関</option>
                                                                                    <?php $select = $val['cancel_reason'] == '死亡' ? ' selected' : null; ?>
                                                                                    <option value="死亡"<?= $select ?>>4:死亡</option>
                                                                                    <?php $select = $val['cancel_reason'] == 'その他' ? ' selected' : null; ?>
                                                                                    <option value="その他"<?= $select ?>>5:その他</option>
                                                                                </select>                                
                                                                            </dd>
                                                                        </dl>
                                                                        <dl>
                                                                            <dt><span class="label_t"><label>死亡の状況</label></span></dt>
                                                                            <dd><div><span class="label_t">年月日</span><input type="text" name="" class="bg-gray2" value="<?= $val['death_day'] ?>" readonly=""></div>
                                                                                <div><span class="label_t">時間</span><input type="text" name="" class="time bg-gray2" value="<?= $val['death_time'] ?>" readonly=""></div>
                                                                                <div><span class="label_t">場所</span>
                                                                                    <select name="" class="place bg-gray2" disabled="">
                                                                                        <option value=""></option>
                                                                                        <?php $select = $val['death_place'] == '自宅' ? ' selected' : null; ?>
                                                                                        <option value="自宅"<?= $select ?>>1:軽快</option>
                                                                                        <?php $select = $val['death_place'] == '施設' ? ' selected' : null; ?>
                                                                                        <option value="施設"<?= $select ?>>2:施設</option>
                                                                                        <?php $select = $val['death_place'] == '病院' ? ' selected' : null; ?>
                                                                                        <option value="病院"<?= $select ?>>3:病院</option>
                                                                                        <?php $select = $val['death_place'] == '診療所' ? ' selected' : null; ?>
                                                                                        <option value="診療所"<?= $select ?>>4:診療所</option>
                                                                                        <?php $select = $val['death_place'] == 'その他' ? ' selected' : null; ?>
                                                                                        <option value="その他"<?= $select ?>>5:その他</option>
                                                                                    </select>                                    
                                                                                </div>
                                                                            </dd>
                                                                        </dl>
                                                                        <dl>
                                                                            <dt><span class="label_t"><label>中止理由</label></span></dt>
                                                                            <dd><input type="text" name="" id="cancel_riyu" value="<?= $val['death_reason'] ?>" class="bg-gray2" readonly=""></dd>
                                                                        </dl>
                                                                    </div>
                                                                    <input type="hidden" name="" value="<?= $val['unique_id'] ?>">
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($tab == 5): ?>
                                            <div class="em_contact con_box">
                                                <div class="em_box1">
                                                    <div class="tit no_bg tit_toggle sm">緊急連絡先</div>
                                                    <div class="box-i child_toggle">

                                                        <table class="em_tb1">
                                                            <tr>
                                                                <th></th>
                                                                <td>
                                                                    <select name="upDummy[emg_no]" class="em_con-list1 label_y">
                                                                        <option selected">緊急連絡先1</option>
                                                                        <option>緊急連絡先2</option>
                                                                        <option>緊急連絡先3</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>氏名</th>
                                                                <td>
                                                                    <input type="text" name="upEmg[0][name]" id="em_name" maxlength="30" value="<?= $dispData['emergency'][0]['name'] ?>" class="emg-name-1">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="label_y">氏名(カナ)</span><span class="pc">/</span><span>同居</span></th>
                                                                <td>
                                                                    <span>
                                                                        <input type="text" name="upEmg[0][kana]" id="em_name-k" maxlength="30" class="" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" value="<?= $dispData['emergency'][0]['kana'] ?>">
                                                                    </span>
                                                                    <select name="upEmg[0][together]" id="dokyo">
                                                                        <option value=""></option>
                                                                        <?php $select = $dispData['emergency'][0]['together'] == '同居' ? ' selected' : null; ?>
                                                                        <option value="同居"<?= $select ?>>同居</option>
                                                                        <?php $select = $dispData['emergency'][0]['together'] == '別居(近隣)' ? ' selected' : null; ?>
                                                                        <option value="別居(近隣)"<?= $select ?>>別居(近隣)</option>
                                                                        <?php $select = $dispData['emergency'][0]['together'] == '別居(遠方)' ? ' selected' : null; ?>
                                                                        <option value="別居(遠方)"<?= $select ?>>別居(遠方)</option>
                                                                        <?php $select = $dispData['emergency'][0]['together'] == 'その他' ? ' selected' : null; ?>
                                                                        <option value="その他"<?= $select ?>>その他</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span>続柄</span><span class="pc">/</span><span>メモ</span></th>
                                                                <td>
                                                                    <select name="upEmg[0][relation_type]" id="relation" class="emg-relation_type-1">
                                                                        <option value=""></option>
                                                                        <?php $select = $dispData['emergency'][0]['relation_type'] == '夫' ? ' selected' : null; ?>
                                                                        <option value="夫"<?= $select ?>>夫</option>
                                                                        <?php $select = $dispData['emergency'][0]['relation_type'] == '妻' ? ' selected' : null; ?>
                                                                        <option value="妻"<?= $select ?>>妻</option>
                                                                        <?php $select = $dispData['emergency'][0]['relation_type'] == '子' ? ' selected' : null; ?>
                                                                        <option value="子"<?= $select ?>>子</option>
                                                                        <?php $select = $dispData['emergency'][0]['relation_type'] == '兄弟' ? ' selected' : null; ?>
                                                                        <option value="兄弟"<?= $select ?>>兄弟</option>
                                                                        <?php $select = $dispData['emergency'][0]['relation_type'] == 'その他' ? ' selected' : null; ?>
                                                                        <option value="その他"<?= $select ?>>その他</option>
                                                                    </select>
                                                                    <span>
                                                                        <input type="text" name="upEmg[0][relation_memo]" id="relation_memo" maxlength="256" value="<?= $dispData['emergency'][0]['relation_memo'] ?>" class="emg-relation_memo-1">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>メールアドレス</th>
                                                                <td>
                                                                    <input type="email" name="upEmg[0][mail]" id="email3" maxlength="100" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?= $dispData['emergency'][0]['mail'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="label_y">電話番号①</span><span class="pc">/</span><span class="sm">電話番号</span>②</th>
                                                                <td>
                                                                    <span>
                                                                        <input type="tel" name="upEmg[0][tel1]" id="bango1" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['emergency'][0]['tel1'] ?>">
                                                                    </span>
                                                                    <span>
                                                                        <input type="tel" name="upEmg[0][tel2]" id="bango2" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['emergency'][0]['tel2'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>FAX</th>
                                                                <td>
                                                                    <input type="tel" name="upEmg[0][fax]" id="fax" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['emergency'][0]['fax'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="">〒/都道府県</span></th>
                                                                <td class="f-keyData" data-tg_url='/user/edit/ajax/address_ajax.php?type=0'>
                                                                    <span>
                                                                        <input type="text" name="upEmg[0][post]" id="prefecture_num" pattern="\d{3}-?\d{4}" value="<?= $dispData['emergency'][0]['post'] ?>" class="f-keyVal">
                                                                    </span>
                                                                    <select name="upEmg[0][prefecture]" id="prefecture">
                                                                        <option value="">▼選択</option>
                                                                        <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                                            <?php $select = $pref === $dispData['emergency'][0]['prefecture'] ? ' selected' : null; ?>
                                                                            <option value="<?= $pref ?>"<?= $select ?>><?= $pref ?></option>
                                                                        <?php endforeach; ?>
                                                                        <!-- ※クラス名で市区町村と連携 -->
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="">市区町村/町域</span></th>
                                                                <td>
                                                                    <select name="upEmg[0][area]" id="municipal">
                                                                        <option value="">▼選択</option>
                                                                        <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                                            <?php foreach ($areaMst2 as $city => $areaMst3): ?>
                                                                                <?php $select = $city === $dispData['emergency'][0]['area'] ? ' selected' : null; ?>
                                                                                <option class="<?= $pref ?>" value="<?= $city ?>"<?= $select ?>><?= $city ?></option>
                                                                            <?php endforeach; ?>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                    <span>
                                                                        <input type="text" name="upEmg[0][address1]" id="town" maxlength="100" value="<?= $dispData['emergency'][0]['address1'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span><span  class="">番地</span>・建物名</span><span class="pc"></span></th>
                                                                <td>
                                                                    <span>
                                                                        <input type="text" name="upEmg[0][address2]" id="building_no1" maxlength="100" value="<?= $dispData['emergency'][0]['address2'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>備考</th>
                                                                <td>
                                                                    <input type="text" name="upEmg[0][remarks]" class="remark emg-remarks-1" value="<?= $dispData['emergency'][0]['remarks'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            <input type="hidden" name="upEmg[0][unique_id]" value="<?= $dispData['emergency'][0]['unique_id'] ?>">
                                                            </tr>
                                                        </table>

                                                        <table class="em_tb2">
                                                            <tr>
                                                                <th></th>
                                                                <td>
                                                                    <select name="upDummy[emg_no]" class="em_con-list1">
                                                                        <option>緊急連絡先1</option>
                                                                        <option selected>緊急連絡先2</option>
                                                                        <option>緊急連絡先3</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>氏名</th>
                                                                <td>
                                                                    <input type="text" name="upEmg[1][name]" id="em_name" maxlength="30" value="<?= $dispData['emergency'][1]['name'] ?>" class="emg-name-2">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="">氏名(カナ)</span><span class="pc">/</span><span>同居</span></th>
                                                                <td>
                                                                    <span>
                                                                        <input type="text" name="upEmg[1][kana]" id="em_name-k" maxlength="30" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" value="<?= $dispData['emergency'][1]['kana'] ?>">
                                                                    </span>
                                                                    <select name="upEmg[1][together]" id="dokyo">
                                                                        <option value=""></option>
                                                                        <?php $select = $dispData['emergency'][1]['together'] == '同居' ? ' selected' : null; ?>
                                                                        <option value="同居"<?= $select ?>>同居</option>
                                                                        <?php $select = $dispData['emergency'][1]['together'] == '別居(近隣)' ? ' selected' : null; ?>
                                                                        <option value="別居(近隣)"<?= $select ?>>別居(近隣)</option>
                                                                        <?php $select = $dispData['emergency'][1]['together'] == '別居(遠方)' ? ' selected' : null; ?>
                                                                        <option value="別居(遠方)"<?= $select ?>>別居(遠方)</option>
                                                                        <?php $select = $dispData['emergency'][1]['together'] == 'その他' ? ' selected' : null; ?>
                                                                        <option value="その他"<?= $select ?>>その他</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span>続柄</span><span class="pc">/</span><span>メモ</span></th>
                                                                <td>
                                                                    <select name="upEmg[1][relation_type]" id="relation" class="emg-relation_type-2">
                                                                        <option value=""></option>
                                                                        <?php $select = $dispData['emergency'][1]['relation_type'] == '夫' ? ' selected' : null; ?>
                                                                        <option value="夫"<?= $select ?>>夫</option>
                                                                        <?php $select = $dispData['emergency'][1]['relation_type'] == '妻' ? ' selected' : null; ?>
                                                                        <option value="妻"<?= $select ?>>妻</option>
                                                                        <?php $select = $dispData['emergency'][1]['relation_type'] == '子' ? ' selected' : null; ?>
                                                                        <option value="子"<?= $select ?>>子</option>
                                                                        <?php $select = $dispData['emergency'][1]['relation_type'] == '兄弟' ? ' selected' : null; ?>
                                                                        <option value="兄弟"<?= $select ?>>兄弟</option>
                                                                        <?php $select = $dispData['emergency'][1]['relation_type'] == 'その他' ? ' selected' : null; ?>
                                                                        <option value="その他"<?= $select ?>>その他</option>
                                                                    </select>
                                                                    <span>
                                                                        <input type="text" name="upEmg[1][relation_memo]" id="relation_memo" maxlength="256" value="<?= $dispData['emergency'][1]['relation_memo'] ?>" class="emg-relation_memo-2">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>メールアドレス</th>
                                                                <td>
                                                                    <input type="email" name="upEmg[1][mail]" id="email3" maxlength="100" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?= $dispData['emergency'][1]['mail'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="">電話番号①</span><span class="pc">/</span><span class="sm">電話番号</span>②</th>
                                                                <td>
                                                                    <span>
                                                                        <input type="tel" name="upEmg[1][tel1]" id="bango1" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['emergency'][1]['tel1'] ?>">
                                                                    </span>
                                                                    <span>
                                                                        <input type="tel" name="upEmg[1][tel2]" id="bango2" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['emergency'][1]['tel2'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>FAX</th>
                                                                <td>
                                                                    <input type="tel" name="upEmg[1][fax]" id="fax" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['emergency'][1]['fax'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="">〒/都道府県</span></th>
                                                                <td class="f-keyData" data-tg_url='/user/edit/ajax/address_ajax.php?type=1'>
                                                                    <span>
                                                                        <input type="text" name="upEmg[1][post]" id="prefecture_num" pattern="\d{3}-?\d{4}" value="<?= $dispData['emergency'][1]['post'] ?>" class="f-keyVal"></span>
                                                                    <select name="upEmg[1][prefecture]" id="prefecture2">
                                                                        <option value="">▼選択</option>
                                                                        <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                                            <?php $select = $pref === $dispData['emergency'][1]['prefecture'] ? ' selected' : null; ?>
                                                                            <option value="<?= $pref ?>"<?= $select ?>><?= $pref ?></option>
                                                                        <?php endforeach; ?>
                                                                        <!-- ※クラス名で市区町村と連携 -->
                                                                    </select>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="">市区町村/町域</span></th>
                                                                <td>
                                                                    <select name="upEmg[1][area]" id="municipal2">
                                                                        <option value="">▼選択</option>
                                                                        <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                                            <?php foreach ($areaMst2 as $city => $areaMst3): ?>
                                                                                <?php $select = $city === $dispData['emergency'][1]['area'] ? ' selected' : null; ?>
                                                                                <option class="<?= $pref ?>" value="<?= $city ?>"<?= $select ?>><?= $city ?></option>
                                                                            <?php endforeach; ?>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                    <span>
                                                                        <input type="text" name="upEmg[1][address1]" id="town2" maxlength="100" value="<?= $dispData['emergency'][1]['address1'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span><span class="">番地</span>・建物名</span><span class="pc"></span></th>
                                                                <td>
                                                                    <span>
                                                                        <input type="text" name="upEmg[1][address2]" id="building_no1" maxlength="100" value="<?= $dispData['emergency'][1]['address2'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>備考</th>
                                                                <td>
                                                                    <input type="text" name="upEmg[1][remarks]" class="remark emg-remarks-2" value="<?= $dispData['emergency'][1]['remarks'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            <input type="hidden" name="upEmg[1][unique_id]" value="<?= $dispData['emergency'][1]['unique_id'] ?>">
                                                            </tr>
                                                        </table>

                                                        <table class="em_tb3">
                                                            <tr>
                                                                <th></th>
                                                                <td>
                                                                    <select name="upDummy[emg_no]" class="em_con-list1">
                                                                        <option>緊急連絡先1</option>
                                                                        <option>緊急連絡先2</option>
                                                                        <option selected>緊急連絡先3</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>氏名</th>
                                                                <td>
                                                                    <input type="text" name="upEmg[2][name]" id="em_name" maxlength="30" value="<?= $dispData['emergency'][2]['name'] ?>" class="emg-name-3">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span>氏名(カナ)</span><span class="pc">/</span><span>同居</span></th>
                                                                <td>
                                                                    <span>
                                                                        <input type="text" name="upEmg[2][kana]" id="em_name-k" maxlength="30" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" value="<?= $dispData['emergency'][2]['kana'] ?>">
                                                                    </span>
                                                                    <select name="upEmg[2][together]" id="dokyo">
                                                                        <option value=""></option>
                                                                        <?php $select = $dispData['emergency'][2]['together'] == '同居' ? ' selected' : null; ?>
                                                                        <option value="同居"<?= $select ?>>同居</option>
                                                                        <?php $select = $dispData['emergency'][2]['together'] == '別居(近隣)' ? ' selected' : null; ?>
                                                                        <option value="別居(近隣)"<?= $select ?>>別居(近隣)</option>
                                                                        <?php $select = $dispData['emergency'][2]['together'] == '別居(遠方)' ? ' selected' : null; ?>
                                                                        <option value="別居(遠方)"<?= $select ?>>別居(遠方)</option>
                                                                        <?php $select = $dispData['emergency'][2]['together'] == 'その他' ? ' selected' : null; ?>
                                                                        <option value="その他"<?= $select ?>>その他</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span>続柄</span><span class="pc">/</span><span>メモ</span></th>
                                                                <td>
                                                                    <select name="upEmg[2][relation_type]" id="relation" class="emg-relation_type-3">
                                                                        <option value=""></option>
                                                                        <?php $select = $dispData['emergency'][2]['relation_type'] == '夫' ? ' selected' : null; ?>
                                                                        <option value="夫"<?= $select ?>>夫</option>
                                                                        <?php $select = $dispData['emergency'][2]['relation_type'] == '妻' ? ' selected' : null; ?>
                                                                        <option value="妻"<?= $select ?>>妻</option>
                                                                        <?php $select = $dispData['emergency'][2]['relation_type'] == '子' ? ' selected' : null; ?>
                                                                        <option value="子"<?= $select ?>>子</option>
                                                                        <?php $select = $dispData['emergency'][2]['relation_type'] == '兄弟' ? ' selected' : null; ?>
                                                                        <option value="兄弟"<?= $select ?>>兄弟</option>
                                                                        <?php $select = $dispData['emergency'][2]['relation_type'] == 'その他' ? ' selected' : null; ?>
                                                                        <option value="その他"<?= $select ?>>その他</option>
                                                                    </select>
                                                                    <span>
                                                                        <input type="text" name="upEmg[2][relation_memo]" id="relation_memo" maxlength="256" value="<?= $dispData['emergency'][2]['relation_memo'] ?>" class="emg-relation_memo-3">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>メールアドレス</th>
                                                                <td>
                                                                    <input type="email" name="upEmg[2][mail]" id="email3" maxlength="100" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?= $dispData['emergency'][2]['mail'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span>電話番号①</span><span class="pc">/</span><span class="sm">電話番号</span>②</th>
                                                                <td>
                                                                    <span>
                                                                        <input type="tel" name="upEmg[2][tel1]" id="bango1" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['emergency'][2]['tel1'] ?>">
                                                                    </span>
                                                                    <span>
                                                                        <input type="tel" name="upEmg[2][tel2]" id="bango2" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['emergency'][2]['tel2'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>FAX</th>
                                                                <td><input type="tel" name="upEmg[2][fax]" id="fax" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['emergency'][2]['fax'] ?>"></td>                        
                                                            </tr>
                                                            <tr>
                                                                <th>〒/都道府県</th>
                                                                <td class="f-keyData" data-tg_url='/user/edit/ajax/address_ajax.php?type=2'>
                                                                    <span>
                                                                        <input type="text" name="upEmg[2][post]" id="prefecture_num" pattern="\d{3}-?\d{4}" value="<?= $dispData['emergency'][2]['post'] ?>" class="f-keyVal"></span>
                                                                    <select name="upEmg[2][prefecture]" id="prefecture3">
                                                                        <option value="">▼選択</option>
                                                                        <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                                            <?php $select = $pref === $dispData['emergency'][2]['prefecture'] ? ' selected' : null; ?>
                                                                            <option value="<?= $pref ?>"<?= $select ?>><?= $pref ?></option>
                                                                        <?php endforeach; ?>
                                                                        <!-- ※クラス名で市区町村と連携 -->
                                                                    </select>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>市区町村/町域</th>
                                                                <td>
                                                                    <select name="upEmg[2][area]" id="municipal3">
                                                                        <option value="">▼選択</option>
                                                                        <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                                            <?php foreach ($areaMst2 as $city => $areaMst3): ?>
                                                                                <?php $select = $city === $dispData['emergency'][2]['area'] ? ' selected' : null; ?>
                                                                                <option class="<?= $pref ?>" value="<?= $city ?>"<?= $select ?>><?= $city ?></option>
                                                                            <?php endforeach; ?>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                    <span>
                                                                        <input type="text" name="upEmg[2][address1]" id="town3" maxlength="100" value="<?= $dispData['emergency'][2]['address1'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span>番地・建物名</span><span class="pc"></span></th>
                                                                <td>
                                                                    <span>
                                                                        <input type="text" name="upEmg[2][address2]" id="town_area" maxlength="100" value="<?= $dispData['emergency'][2]['address2'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>備考</th>
                                                                <td>
                                                                    <input type="text" name="upEmg[2][remarks]" class="remark emg-remarks-3" value="<?= $dispData['emergency'][2]['remarks'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            <input type="hidden" name="upEmg[2][unique_id]" value="<?= $dispData['emergency'][2]['unique_id'] ?>">
                                                            </tr>
                                                        </table>

                                                    </div>
                                                </div>
                                                <div class="em_box2">
                                                    <div class="tit no_bg tit_toggle sm">その他キーパーソン</div>
                                                    <div class="box-i child_toggle">
                                                        <table class="em_tb1">
                                                            <tr>
                                                                <th></th>
                                                                <td><span class="">その他キーパーソン1</span></td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="">氏名</span><span class="pc">/</span><span class="">関係性</span></th>
                                                                <td><span><input type="text" name="upPsn[0][name]" id="key_name" maxlength="30" value="<?= $dispData['person'][0]['name'] ?>"></span>
                                                                    <span><input type="text" name="upPsn[0][relation]" id="key_relation" maxlength="10" value="<?= $dispData['person'][0]['relation'] ?>"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="">氏名(カナ)</span></th>
                                                                <td>
                                                                    <input type="text" name="upPsn[0][kana]" id="key_name-k" maxlength="30" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" value="<?= $dispData['person'][0]['kana'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><span class="">電話番号</span></th>
                                                                <td>
                                                                    <input type="tel" name="upPsn[0][tel]" id="key_num" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['person'][0]['tel'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>備考</th>
                                                                <td>
                                                                    <input type="text" name="upPsn[0][remarks]" class="remark" value="<?= $dispData['person'][0]['remarks'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            <input type="hidden" name="upPsn[0][unique_id]" value="<?= $dispData['person'][0]['unique_id'] ?>">
                                                            </tr>
                                                        </table>
                                                        <table class="em_tb2">
                                                            <tr>
                                                                <th></th>
                                                                <td>その他キーパーソン2</td>
                                                            </tr>
                                                            <tr>
                                                                <th><span>氏名</span><span class="pc">/</span>関係性</th>
                                                                <td>
                                                                    <span>
                                                                        <input type="text" name="upPsn[1][name]" id="key_name" maxlength="30" value="<?= $dispData['person'][1]['name'] ?>">
                                                                    </span>
                                                                    <span>
                                                                        <input type="text" name="upPsn[1][relation]" id="key_relation" maxlength="10" value="<?= $dispData['person'][1]['relation'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>氏名(カナ)</th>
                                                                <td>
                                                                    <input type="text" name="upPsn[1][kana]" id="key_name-k" maxlength="30" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" value="<?= $dispData['person'][1]['kana'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>電話番号</th>
                                                                <td>
                                                                    <input type="tel" name="upPsn[1][tel]" id="key_num" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['person'][1]['tel'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>備考</th>
                                                                <td>
                                                                    <input type="text" name="upPsn[1][remarks]" class="remark" value="<?= $dispData['person'][1]['remarks'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            <input type="hidden" name="upPsn[1][unique_id]" value="<?= $dispData['person'][1]['unique_id'] ?>">
                                                            </tr>
                                                        </table>
                                                        <table class="em_tb3">
                                                            <tr>
                                                                <th></th>
                                                                <td>その他キーパーソン3</td>
                                                            </tr>
                                                            <tr>
                                                                <th><span>氏名</span><span class="pc">/</span>関係性</th>
                                                                <td>
                                                                    <span>
                                                                        <input type="text" name="upPsn[2][name]" id="key_name" maxlength="30" value="<?= $dispData['person'][2]['name'] ?>">
                                                                    </span>
                                                                    <span>
                                                                        <input type="text" name="upPsn[2][relation]" id="key_relation" maxlength="10" clas="" value="<?= $dispData['person'][2]['relation'] ?>">
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>氏名(カナ)</th>
                                                                <td>
                                                                    <input type="text" name="upPsn[2][kana]" id="key_name-k" maxlength="30" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" value="<?= $dispData['person'][2]['kana'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>電話番号</th>
                                                                <td>
                                                                    <input type="tel" name="upPsn[2][tel]" id="key_num" maxlength="13" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" value="<?= $dispData['person'][2]['tel'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>備考</th>
                                                                <td>
                                                                    <input type="text" name="upPsn[2][remarks]" class="remark" value="<?= $dispData['person'][2]['remarks'] ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            <input type="hidden" name="upPsn[2][unique_id]" value="<?= $dispData['person'][2]['unique_id'] ?>">
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="em_list">
                                                    <div class="tit no_bg tit_toggle">家族構成</div>
                                                    <div class="box-i child_toggle">
                                                        <span class="btn add fml_add">行追加</span>
                                                        <div class="list_scroll-x content_flm">        
                                                            <table class="kazoku_kosei" id="family">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width:70px;"></th>
                                                                        <th style="width:200px;">緊急連絡先から反映</th>
                                                                        <th style="width:150px;">氏名</th>
                                                                        <th style="width:80px;">続柄</th>
                                                                        <th style="width:150px;">続柄メモ</th>
                                                                        <th style="width:150px;">職業</th>
                                                                        <th style="width:350px;">備考</th>
                                                                        <th style="width:80px;"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="kazoku_kosei_data">
                                                                    <?php $i = 0; ?>
                                                                    <?php foreach ($dispData['family'] as $key => $val): ?>
                                                                        <?php if ($key !== 'def'): ?>
                                                                            <?php $i++; ?>
                                                                            <tr>
                                                                                <td style="width:70px;">家族<span class="num"><?= $i ?></span></td>
                                                                                <td style="width:200px;">
                                                                                    <select name="upFml[<?= $i ?>][type]" class="em_con-list2" data-key="<?= $key ?>" onchange="copyFamily(this)">
                                                                                        <option value="">▼反映元を選択</option>
                                                                                        <?php $select = $val['type'] == '1' ? ' selected' : null; ?>
                                                                                        <option value="1"<?= $select ?>>緊急連絡先①を反映</option>
                                                                                        <?php $select = $val['type'] == '2' ? ' selected' : null; ?>
                                                                                        <option value="2"<?= $select ?>>緊急連絡先②を反映</option>
                                                                                        <?php $select = $val['type'] == '3' ? ' selected' : null; ?>
                                                                                        <option value="3"<?= $select ?>>緊急連絡先③を反映</option>
                                                                                    </select>
                                                                                    <input type="hidden" name="btnFmlCopy" class="copyFml" value="">
                                                                                </td>
                                                                                <td style="width:150px;">
                                                                                    <input type="text" name="upFml[<?= $i ?>][name]" class="name fml-name-<?= $key ?>" maxlength="30" value="<?= $val['name'] ?>">
                                                                                </td>
                                                                                <td style="width:80px;">
                                                                                    <input type="text" name="upFml[<?= $i ?>][relation_type]" class="relation fml-relation_type-<?= $key ?>" maxlength="10" value="<?= $val['relation_type'] ?>">
                                                                                </td>
                                                                                <td style="width:150px;">
                                                                                    <input type="text" name="upFml[<?= $i ?>][relation_memo]" class="relation_memo fml-relation_memo-<?= $key ?>" maxlength="256" value="<?= $val['relation_memo'] ?>">
                                                                                </td>
                                                                                <td style="width:150px;">
                                                                                    <input type="text" name="upFml[<?= $i ?>][business]" class="occupation fml-business-<?= $key ?>" maxlength="30" value="<?= $val['business'] ?>">
                                                                                </td>
                                                                                <td style="width:350px;">
                                                                                    <input type="text" name="upFml[<?= $i ?>][remarks]" class="remark fml-remarks-<?= $key ?>" value="<?= $val['remarks'] ?>">
                                                                                </td>
                                                                                <td style="width:80px;">
                                                                                    <button type="submit" class="btn-del" name="btnDelFml" style="width:70px;" value="<?= $val['unique_id'] ?>">削除</button>
                                                                                </td>
                                                                        <input type="hidden" name="upFml[<?= $i ?>][unique_id]"  value="<?= $val['unique_id'] ?>">
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <span class="note">※家族①～③に入力した情報は「訪問看護記録Ⅰ」作成時に「家族構成」として自動反映されます。</span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($tab == 6): ?>
                                            <div class="flow_info con_box tab">
                                                <div class="flow_list">
                                                    <span class="btn add int-edit">新規</span>
                                                    <div class="list_scroll-x">
                                                        <table class="flow_tbl">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:200px;">第1紹介機関</th>
                                                                    <th style="width:150px;">担当者1</th>
                                                                    <th style="width:123px;">サービス<br/>開始予定日</th>
                                                                    <th style="width:200px;">第2紹介機関</th>
                                                                    <th style="width:150px;">担当者1</th>
                                                                    <th style="width:300px;"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="tab-btn">
                                                                <?php foreach ($dispData['introduct'] as $key => $val): ?>
                                                                    <?php if ($key !== 'def'): ?>
                                                                        <tr>
                                                                            <td style="width:200px;"><?= $val['in1_name'] ?></td>
                                                                            <td style="width:150px;"><?= $val['in1_person1'] ?></td>
                                                                            <td style="width:123px;"><?= $val['in1_start'] ?></td>
                                                                            <td style="width:200px;"><?= $val['in2_name'] ?></td>
                                                                            <td style="width:150px;"><?= $val['in2_person1'] ?></td>
                                                                            <td style="width:300px;">
                                                                                <button type="button" class="btn-edit int-edit1" name="btnEditHsp"  style="width:70px;" value="<?= $val['unique_id'] ?>"
                                                                                        data-int_id="<?= $val['unique_id'] ?>"
                                                                                        data-int_in1_name="<?= $val['in1_name'] ?>"
                                                                                        data-int_in1_company="<?= $val['in1_company'] ?>"
                                                                                        data-int_in1_post="<?= $val['in1_post'] ?>"
                                                                                        data-int_in1_address="<?= $val['in1_address'] ?>"
                                                                                        data-int_in1_tel="<?= $val['in1_tel'] ?>"
                                                                                        data-int_in1_fax="<?= $val['in1_fax'] ?>"
                                                                                        data-int_in1_mail="<?= $val['in1_mail'] ?>"
                                                                                        data-int_in1_person1="<?= $val['in1_person1'] ?>"
                                                                                        data-int_in1_person2="<?= $val['in1_person2'] ?>"
                                                                                        data-int_in1_person3="<?= $val['in1_person3'] ?>"
                                                                                        data-int_in1_start="<?= $val['in1_start'] ?>"
                                                                                        data-int_in1_remarks="<?= $val['in1_remarks'] ?>"
                                                                                        data-int_in2_name="<?= $val['in2_name'] ?>"
                                                                                        data-int_in2_company="<?= $val['in2_company'] ?>"
                                                                                        data-int_in2_post="<?= $val['in2_post'] ?>"
                                                                                        data-int_in2_address="<?= $val['in2_address'] ?>"
                                                                                        data-int_in2_tel="<?= $val['in2_tel'] ?>"
                                                                                        data-int_in2_fax="<?= $val['in2_fax'] ?>"
                                                                                        data-int_in2_mail="<?= $val['in2_mail'] ?>"
                                                                                        data-int_in2_person1="<?= $val['in2_person1'] ?>"
                                                                                        data-int_in2_person2="<?= $val['in2_person2'] ?>"
                                                                                        data-int_in2_person3="<?= $val['in2_person3'] ?>"
                                                                                        data-int_in2_remarks="<?= $val['in2_remarks'] ?>"
                                                                                        data-int_out_day="<?= $val['out_day'] ?>"
                                                                                        data-int_out_name="<?= $val['out_name'] ?>"
                                                                                        data-int_out_person="<?= $val['out_person'] ?>"
                                                                                        data-int_out_type="<?= $val['out_type'] ?>"
                                                                                        data-int_out_memo="<?= $val['out_memo'] ?>">
                                                                                    第1編集
                                                                                </button>
                                                                                <button type="button" class="btn-edit int-edit2" name="btnEditHsp" style="width:70px;" value="<?= $val['unique_id'] ?>"
                                                                                        data-int_id="<?= $val['unique_id'] ?>"
                                                                                        data-int_in1_name="<?= $val['in1_name'] ?>"
                                                                                        data-int_in1_company="<?= $val['in1_company'] ?>"
                                                                                        data-int_in1_post="<?= $val['in1_post'] ?>"
                                                                                        data-int_in1_address="<?= $val['in1_address'] ?>"
                                                                                        data-int_in1_tel="<?= $val['in1_tel'] ?>"
                                                                                        data-int_in1_fax="<?= $val['in1_fax'] ?>"
                                                                                        data-int_in1_mail="<?= $val['in1_mail'] ?>"
                                                                                        data-int_in1_person1="<?= $val['in1_person1'] ?>"
                                                                                        data-int_in1_person2="<?= $val['in1_person2'] ?>"
                                                                                        data-int_in1_person3="<?= $val['in1_person3'] ?>"
                                                                                        data-int_in1_start="<?= $val['in1_start'] ?>"
                                                                                        data-int_in1_remarks="<?= $val['in1_remarks'] ?>"
                                                                                        data-int_in2_name="<?= $val['in2_name'] ?>"
                                                                                        data-int_in2_company="<?= $val['in2_company'] ?>"
                                                                                        data-int_in2_post="<?= $val['in2_post'] ?>"
                                                                                        data-int_in2_address="<?= $val['in2_address'] ?>"
                                                                                        data-int_in2_tel="<?= $val['in2_tel'] ?>"
                                                                                        data-int_in2_fax="<?= $val['in2_fax'] ?>"
                                                                                        data-int_in2_mail="<?= $val['in2_mail'] ?>"
                                                                                        data-int_in2_person1="<?= $val['in2_person1'] ?>"
                                                                                        data-int_in2_person2="<?= $val['in2_person2'] ?>"
                                                                                        data-int_in2_person3="<?= $val['in2_person3'] ?>"
                                                                                        data-int_in2_remarks="<?= $val['in2_remarks'] ?>"
                                                                                        data-int_out_day="<?= $val['out_day'] ?>"
                                                                                        data-int_out_name="<?= $val['out_name'] ?>"
                                                                                        data-int_out_person="<?= $val['out_person'] ?>"
                                                                                        data-int_out_type="<?= $val['out_type'] ?>"
                                                                                        data-int_out_memo="<?= $val['out_memo'] ?>">
                                                                                    第2編集
                                                                                </button>
                                                                                <button type="button" class="btn-edit int-edit3" name="btnEditHsp" style="width:70px;" value="<?= $val['unique_id'] ?>"
                                                                                        data-int_id="<?= $val['unique_id'] ?>"
                                                                                        data-int_in1_name="<?= $val['in1_name'] ?>"
                                                                                        data-int_in1_company="<?= $val['in1_company'] ?>"
                                                                                        data-int_in1_post="<?= $val['in1_post'] ?>"
                                                                                        data-int_in1_address="<?= $val['in1_address'] ?>"
                                                                                        data-int_in1_tel="<?= $val['in1_tel'] ?>"
                                                                                        data-int_in1_fax="<?= $val['in1_fax'] ?>"
                                                                                        data-int_in1_mail="<?= $val['in1_mail'] ?>"
                                                                                        data-int_in1_person1="<?= $val['in1_person1'] ?>"
                                                                                        data-int_in1_person2="<?= $val['in1_person2'] ?>"
                                                                                        data-int_in1_person3="<?= $val['in1_person3'] ?>"
                                                                                        data-int_in1_start="<?= $val['in1_start'] ?>"
                                                                                        data-int_in1_remarks="<?= $val['in1_remarks'] ?>"
                                                                                        data-int_in2_name="<?= $val['in2_name'] ?>"
                                                                                        data-int_in2_company="<?= $val['in2_company'] ?>"
                                                                                        data-int_in2_post="<?= $val['in2_post'] ?>"
                                                                                        data-int_in2_address="<?= $val['in2_address'] ?>"
                                                                                        data-int_in2_tel="<?= $val['in2_tel'] ?>"
                                                                                        data-int_in2_fax="<?= $val['in2_fax'] ?>"
                                                                                        data-int_in2_mail="<?= $val['in2_mail'] ?>"
                                                                                        data-int_in2_person1="<?= $val['in2_person1'] ?>"
                                                                                        data-int_in2_person2="<?= $val['in2_person2'] ?>"
                                                                                        data-int_in2_person3="<?= $val['in2_person3'] ?>"
                                                                                        data-int_in2_remarks="<?= $val['in2_remarks'] ?>"
                                                                                        data-int_out_day="<?= $val['out_day'] ?>"
                                                                                        data-int_out_name="<?= $val['out_name'] ?>"
                                                                                        data-int_out_person="<?= $val['out_person'] ?>"
                                                                                        data-int_out_type="<?= $val['out_type'] ?>"
                                                                                        data-int_out_memo="<?= $val['out_memo'] ?>">
                                                                                    流入編集
                                                                                </button>
                                                                                <button type="submit" class="btn-del" name="btnDelInt" style="width:70px;" value="<?= $val['unique_id'] ?>">削除</button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endif; ?> 
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-main" style="flex-basis:100%;">

                                                    <!-- 切り替え -->
                                                    <?php foreach ($dispData['introduct'] as $key => $val): ?>
                                                        <?php if ($key !== 'def'): ?>
                                                            <div class="tab-main-box">
                                                                <div style="display:flex;">
                                                                    <div class="box-l">            
                                                                        <div class="box1">
                                                                            <div class="tit no_bg tit_toggle">第1紹介機関</div>
                                                                            <div class="box-i child_toggle">
                                                                                <dl class="flow_l1">
                                                                                    <dt><span class="label_t"><label for="institution_n1">機関名<span class="pc">/法人名</span></label></span></dt>
                                                                                    <dd>
                                                                                        <span>
                                                                                            <input type="text" name="" id="institution_n1-1" class="institution_n bg-gray2" value="<?= $val['in1_name'] ?>" readonly="">
                                                                                        </span>
                                                                                        <span class="pc">
                                                                                            <input type="text" name="" id="institution_n1-2" class="institution_n bg-gray2" value="<?= $val['in1_company'] ?>" readonly="">
                                                                                        </span>
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl class="flow_l1 sm">
                                                                                    <dt><span class="label_t"><label for="institution_n1">法人名</label></span></dt>
                                                                                    <dd>
                                                                                        <span>
                                                                                            <input type="text" name="" id="institution_n1-2" class="institution_n bg-gray2" value="<?= $val['in1_company'] ?>" readonly="">
                                                                                        </span>
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl class="flow_l2">
                                                                                    <dt><span class="label_t"><label for="institution_add1">〒<span class="pc">/所在地</span></label></span></dt>
                                                                                    <dd><span><input type="text" name="" id="institution_add-num1" value="<?= $val['in1_post'] ?>" class="bg-gray2" readonly=""></span>
                                                                                        <span class="pc"><input type="text" name="" id="institution_add-txt1" value="<?= $val['in1_address'] ?>" class="bg-gray2" readonly=""></span>
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl class="flow_l2 sm">
                                                                                    <dt><span class="label_t"><label for="institution_add1">所在地</label></span></dt>
                                                                                    <dd>
                                                                                        <span>
                                                                                            <input type="text" name="" id="institution_add-txt1" value="<?= $val['in1_address'] ?>" class="bg-gray2" readonly="">
                                                                                        </span>
                                                                                    </dd>
                                                                                </dl>
                                                                                <div class="incharge">
                                                                                    <dl>
                                                                                        <dt><span class="label_t"><label for="incharge1-1">担当者1</label></span></dt>
                                                                                        <dd>
                                                                                            <input type="text" name="" id="incharge1-1" value="<?= $val['in1_person1'] ?>" class="bg-gray2" readonly="">
                                                                                        </dd>
                                                                                    </dl>
                                                                                    <dl>
                                                                                        <dt><span class="label_t"><label for="incharge1-2">担当者2</label></span></dt>
                                                                                        <dd>
                                                                                            <input type="text" name="" id="incharge1-2" value="<?= $val['in1_person2'] ?>" class="bg-gray2" readonly="">
                                                                                        </dd>
                                                                                    </dl>
                                                                                    <dl>
                                                                                        <dt><span class="label_t"><label for="incharge1-3">担当者3</label></span></dt>
                                                                                        <dd>
                                                                                            <input type="text" name="" id="incharge1-3" value="<?= $val['in1_person3'] ?>" class="bg-gray2" readonly="">
                                                                                        </dd>
                                                                                    </dl>
                                                                                </div>
                                                                                <dl class="flow_l3">
                                                                                    <dt><span class="label_t"><label for="bango1">電話番号</label></span><span class="sm label_t"><label for="fax1">FAX</label></span></dt>
                                                                                    <dd><input type="tel" name="" id="bango1" value="<?= $val['in1_tel'] ?>" class="bg-gray2" readonly=""></dd>
                                                                                    <dd>
                                                                                        <span class="label_t pc"><label for="fax1">FAX</label></span>
                                                                                        <span>
                                                                                            <input type="tel" name="" id="fax1" value="<?= $val['in1_fax'] ?>" class="bg-gray2" readonly="">
                                                                                        </span>
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl class="flow_l4">
                                                                                    <dt><span class="label_t"><label for="email1">メールアドレス</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="email" name="" id="email1" value="<?= $val['in1_mail'] ?>" class="bg-gray2" readonly="">
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl class="flow_l5">
                                                                                    <dt><span class="label_t"><label for="service_start1">サービス開始予定日</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="text" name="" class="bg-gray2" id="service_start1" value="<?= $val['in1_start'] ?>" readonly="">
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl class="flow_l6">
                                                                                    <dt><span class="label_t"><label for="remarks1">備考</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="text" name="" value="<?= $val['in1_remarks'] ?>" class="bg-gray2" readonly="">
                                                                                    </dd>
                                                                                </dl>
                                                                            </div>
                                                                        </div>    
                                                                        <div class="box2">
                                                                            <div class="tit no_bg tit_toggle">第2紹介機関</div>
                                                                            <div class="box-i child_toggle">
                                                                                <dl class="flow_l1">
                                                                                    <dt><span class="label_t"><label for="institution_n2">機関名<span class="pc">/法人名</span></label></span></dt>
                                                                                    <dd>
                                                                                        <span>
                                                                                            <input type="text" name="" id="institution_n2-1" class="institution_n bg-gray2" value="<?= $val['in2_name'] ?>" readonly="">
                                                                                        </span>
                                                                                        <span class="pc">
                                                                                            <input type="text" name="" id="institution_n2-2" class="institution_n bg-gray2" value="<?= $val['in2_company'] ?>" readonly="">
                                                                                        </span>
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl class="flow_l1 sm">
                                                                                    <dt><span class="label_t"><label for="institution_n1">法人名</label></span></dt>
                                                                                    <dd>
                                                                                        <span>
                                                                                            <input type="text" name="" id="institution_n1-2" class="institution_n bg-gray2" value="<?= $val['in2_company'] ?>" readonly="">
                                                                                        </span>
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl class="flow_l2">
                                                                                    <dt><span class="label_t"><label for="institution_add2">〒<span class="pc">/所在地</span></label></span></dt>
                                                                                    <dd>
                                                                                        <span>
                                                                                            <input type="text" name="" id="institution_add-num2" value="<?= $val['in2_post'] ?>" class="bg-gray2" readonly="">
                                                                                        </span>
                                                                                        <span class="pc">
                                                                                            <input type="text" name="" id="institution_add-txt2" value="<?= $val['in2_address'] ?>" class="bg-gray2" readonly="">
                                                                                        </span>
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl class="flow_l2 sm">
                                                                                    <dt><span class="label_t"><label for="institution_add1">所在地</label></span></dt>
                                                                                    <dd><span><input type="text" name="" id="institution_add-txt1" value="<?= $val['in2_address'] ?>" class="bg-gray2" readonly=""></span></dd>
                                                                                </dl>
                                                                                <div class="incharge">
                                                                                    <dl>
                                                                                        <dt><span class="label_t"><label for="incharge2-1">担当者1</label></span></dt>
                                                                                        <dd>
                                                                                            <input type="text" name="" id="incharge2-1" value="<?= $val['in2_person1'] ?>" class="bg-gray2" readonly="">
                                                                                        </dd>
                                                                                    </dl>
                                                                                    <dl>
                                                                                        <dt><span class="label_t"><label for="incharge2-2">担当者2</label></span></dt>
                                                                                        <dd>
                                                                                            <input type="text" name="" id="incharge2-2" value="<?= $val['in2_person2'] ?>" class="bg-gray2" readonly="">
                                                                                        </dd>
                                                                                    </dl>
                                                                                    <dl>
                                                                                        <dt><span class="label_t"><label for="incharge2-3">担当者3</label></span></dt>
                                                                                        <dd>
                                                                                            <input type="text" name="" id="incharge2-3" value="<?= $val['in2_person3'] ?>" class="bg-gray2" readonly="">
                                                                                        </dd>
                                                                                    </dl>
                                                                                </div>
                                                                                <dl class="flow_l3">
                                                                                    <dt><span class="label_t"><label for="bango2">電話番号</label></span><span class="sm label_t"><label for="fax1">FAX</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="tel" name="" id="bango2" value="<?= $val['in2_tel'] ?>" class="bg-gray2" readonly="">
                                                                                    </dd>
                                                                                    <dd><span class="label_t pc"><label for="fax2">FAX</label></span>
                                                                                        <span>
                                                                                            <input type="tel" name="" id="fax2" value="<?= $val['in2_fax'] ?>" class="bg-gray2" readonly="">
                                                                                        </span>
                                                                                </dl>
                                                                                <dl class="flow_l4">
                                                                                    <dt><span class="label_t"><label for="email2">メールアドレス</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="email" name="" id="email2" value="<?= $val['in2_mail'] ?>" class="bg-gray2" readonly="">
                                                                                    </dd>
                                                                                </dl>
                                                                                <dl class="flow_l6">
                                                                                    <dt><span class="label_t"><label for="remarks2">備考</label></span></dt>
                                                                                    <dd>
                                                                                        <input type="text" name="" value="<?= $val['in2_remarks'] ?>" class="bg-gray2" readonly="">
                                                                                    </dd>
                                                                                </dl>
                                            <!--                                    <span class="btn add">紹介機関をさらに追加</span>-->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="box-r">
                                                                        <div class="tit no_bg tit_toggle">流出先</div>
                                                                        <div class="box-i child_toggle">
                                                                            <dl>
                                                                                <dt><span class="label_t"><label for="destination">流出日</label></span></dt>
                                                                                <dd>
                                                                                    <input type="text" name="" class="bg-gray2" id="destination" value="<?= $val['out_day'] ?>" readonly="">
                                                                                </dd>
                                                                            </dl>
                                                                            <dl>
                                                                                <dt><span class="label_t"><label for="agency_name">流出機関名</label></span></dt>
                                                                                <dd>
                                                                                    <input type="text" name="" id="agency_name" value="<?= $val['out_name'] ?>" class="bg-gray2" readonly="">
                                                                                </dd>
                                                                            </dl>
                                                                            <dl>
                                                                                <dt><span class="label_t"><label for="manager_n">担当者</label></span></dt>
                                                                                <dd>
                                                                                    <input type="text" name="" id="manager_n" value="<?= $val['out_person'] ?>" class="bg-gray2" readonly="">
                                                                                </dd>
                                                                            </dl>
                                                                            <dl>
                                                                                <dt><span class="label_t"><label for="riyu_cat">流出理由</label></span></dt>
                                                                                <dd>
                                                                                    <select name="" class="">
                                                                                        <option value=""></option>
                                                                                        <?php $select = $val['out_type'] == '逝去' ? ' selected' : null; ?>
                                                                                        <option value="逝去"<?= $select ?>>逝去</option>
                                                                                        <?php $select = $val['out_type'] == '入院' ? ' selected' : null; ?>
                                                                                        <option value="入院"<?= $select ?>>入院</option>
                                                                                        <?php $select = $val['out_type'] == '施設入所' ? ' selected' : null; ?>
                                                                                        <option value="施設入所"<?= $select ?>>施設入所</option>
                                                                                        <?php $select = $val['out_type'] == 'その他' ? ' selected' : null; ?>
                                                                                        <option value="その他"<?= $select ?>>その他</option>
                                                                                    </select>
                                                                                </dd>
                                                                            </dl>
                                                                            <dl>
                                                                                <dt><span class="label_t"><label for="riyu_txt">流出理由</label></span></dt>
                                                                                <dd><textarea  name="" kangae int class="bg-gray2" readonly=""><?= $val['out_memo'] ?></textarea></dd>
                                                                            </dl>
                                                                            <tr>
                                                                            <input type="hidden" name="" value="<?= $val['unique_id'] ?>">
                                                                            </tr>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?> 
                                                    <?php endforeach; ?>

                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!--ダイアログ呼出し-->
                                    <?php // require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/office.php');?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/office2.php'); ?>
                                    <?php //require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/office3.php');?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/insurance.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/insurance3.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/insurance4.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/introduct_1.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/introduct_2.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/introduct_3.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/service.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/hospital.php'); ?>

                                    <!--登録者情報-->
                                    <div class="nurse_record record9">
                                        <div class="i_register">
                                            <span class="label_t">初回登録:</span>
                                            <span class="label_t hidzuke"><?= isset($dispData['standard']['create_day']) ? $dispData['standard']['create_day'] : null ?></span>
                                            <span class="label_t time"><?= isset($dispData['standard']['create_time']) ? $dispData['standard']['create_time'] : null ?></span>
                                            <span class="label_t"><?= isset($dispData['standard']['create_name']) ? $dispData['standard']['create_name'] : null ?></span>
                                        </div>
                                        <div class="l_update">
                                            <span class="label_t">最終更新:</span>
                                            <span class="label_t hidzuke"><?= isset($dispData['standard']['update_day']) ? $dispData['standard']['update_day'] : null ?></span>
                                            <span class="label_t time"><?= isset($dispData['standard']['update_time']) ? $dispData['standard']['update_time'] : null ?></span>
                                            <span class="label_t"><?= isset($dispData['standard']['update_name']) ? $dispData['standard']['update_name'] : null ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div></div>
                        <!--/// CONTENT_END ///-->
                        <div class="fixed_navi">
                            <div class="box">
                                <div class="btn back pc"><button type="submit" name="btnReturn" value="true">利用者一覧にもどる</button></div>
                                <div class="btn back sm"><a href="/user/list/"><img src="/common/image/icon_return.png" alt=""></a></div>
                                <div class="controls">
                                    <!--<div class="btn cancel">キャンセル</div>-->
                                        <!--<div class="btn save"><input type="submit" class="btn save" name="btnEntry" value="true">保存</div>-->
                                        <!--<input type="submit" class="btn save" name="btnEntry" value="保存">-->
                                    <button type="submit" id="btnEntry" class="btn save" name="btnEntry" value="保存">保存</button>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(function () {
                                
                                $(".hist_list").toggle(true);
                                

                                $(document).on("click",".office_edit", function () {
                                    var userId = getQuery('user');

                                    if (!userId) {
                                        alert("利用者が選択されていません。利用者を選択して下さい。");
                                        return false;
                                    }
                                    var url = $(this).data("url");
                                    var id = $(this).data("id");
                                    var userOfcd = $(this).data("user_office_id");
                                    var startDay = $(this).data("start_day");
                                    var endDay = $(this).data("end_day");
                                    var officeName = $(this).data("office_name");
                                    var tgUrl = $(this).data('url');
                                    var dlgName = $(this).data('dialog_name');
                                    
                                    var tgtUrl = "/user/edit/dialog/office3.php?id=" + id + "&user_id=" + userId + '&office_name=' + officeName + "&user_office_id=" + userOfcd + '&start_day=' + startDay + '&end_day=' + endDay + '&mode=edit';
                                    
                                    $(this).data("url",tgtUrl);

                                });

                                $(".office_history").on('click', '.row_delete', function (event) {

                                    var result = window.confirm('削除してもよろしいですか？');
                                    if (!result) {
                                        // いいえ押下時、Submit阻止
                                        return false;
                                    }
                                    event.preventDefault();
                                    $(this).closest('li').remove();
                                });
                            });
                            // URLから特定クエリを取得
                            function getQuery(name) {

                                var url = window.location.href;
                                name = name.replace(/[\[\]]/g, "\\$&");
                                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                                        results = regex.exec(url);
                                if (!results)
                                    return null;
                                if (!results[2])
                                    return '';
                                return decodeURIComponent(results[2].replace(/\+/g, " "));
                            }
                        </script>
                    </form>
                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/dialog/introduct.php'); ?>
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>