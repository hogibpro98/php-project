<?php require_once(dirname(__FILE__) . "/php/staff_edit.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <script src="/system/staff_edit/js/staff.js"></script>
        <!--CONTENT-->
        <title>従業員詳細</title>
        <!-- <style type="text/css">
        input:required {
          border: 1px solid red !important;
        }
        </style> -->
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <form action="" class="p-form-validate" method="post">
                    <article id="content">
                        <!--/// CONTENT_START ///-->
                        <h2>従業員詳細</h2>
                        <div id="subpage"><div id="staff-detail" class="nursing">
                                <?php if ($dispData['unique_id']) : ?>
                                    <input type="hidden" name="upAry[unique_id]" value="<?= $dispData['unique_id'] ?>">
                                <?php endif; ?>
                                <div class="wrap">
                                    <div class="user-details nurse_record">
                                        <div class="box-l">
                                            <ul class="name_info">
                                                <li class="line">
                                                    <div>
                                                        <label for="emp_ID">社員ID<span class="req">*</span></label>
                                                        <input type="text" name="upAry[staff_id]" id="emp_ID" value="<?= $dispData['staff_id'] ?>" required>
                                                    </div>
                                                </li>
                                                <li class="line">
                                                    <div>
                                                        <label for="emp_lname">氏(漢字)<span class="req">*</span></label>
                                                        <input type="text" name="upAry[last_name]" id="emp_lname" value="<?= $dispData['last_name'] ?>" required>
                                                    </div>
                                                    <div>
                                                        <label for="emp_fname">名(漢字)<span class="req">*</span></label>
                                                        <input type="text" name="upAry[first_name]" id="emp_fname" value="<?= $dispData['first_name'] ?>" required>
                                                    </div>
                                                </li>
                                                <li class="line">
                                                    <div>
                                                        <label for="emp_lname-kana">氏(カナ)<span class="req">*</span></label>
                                                        <input type="text" name="upAry[last_kana]" id="emp_lname-kana" value="<?= $dispData['last_kana'] ?>" required>
                                                    </div>
                                                    <div>
                                                        <label for="emp_fname-kana">名(カナ)<span class="req">*</span></label>
                                                        <input type="text" name="upAry[first_kana]" id="emp_fname-kana" value="<?= $dispData['first_kana'] ?>" required>
                                                    </div>
                                                </li>
                                                <li class="line"><div><label>アカウント</label><input type="text" name="upAry[account]" value="<?= $dispData['account'] ?>" autocomplete="false"></div>
                                                    <div><label>パスワード</label><input type="password" name="upAry[password]" value="<?= $dispData['password'] ?>" autocomplete="false"></div>
                                                </li>
                                                <li class="line"><div><label for="emp_pri-role">第1役割</label><input type="text" name="upAry[role1]" id="emp_pri-role" value="<?= $dispData['role1'] ?>"></div>
                                                    <div><label for="emp_sec-role">第2役割</label><input type="text" name="upAry[role2]" id="emp_sec-role" value="<?= $dispData['role2'] ?>"></div>
                                                </li>
                                                <li class="line"><span class="renkei_data">連携<br class="pc"/>システムデータ</span>
                                                    <div><label for="renkei_data-name">名称</label><input type="text" name="upAry[linkage_name]" id="renkei_data-name" value="<?= $dispData['linkage_name'] ?>"></div>
                                                    <div><label for="renkei_data-code">コード</label><input type="text" name="upAry[linkage_code]" id="renkei_data-code" value="<?= $dispData['linkage_code'] ?>"></div>
                                                </li>
                                            </ul>
                                            <div class="aff_box">
                                                <table id="affliate_add">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2" class="label_t" style="width:270px;background: #F1F8FF;">所属拠点<span class="req">*</span></th>
                                                            <th>所属事業所<span class="req">*</span></th>
                                                            <th style="width:110px;"><div class="btn add add2 addBase" style="width:90px;">拠点追加</div></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(empty($dispOfc)) : ?>
                                                            <tr>
                                                                <td class="del_tr">
                                                                    <span class="btn trash2 del">
                                                                        <button type="submit" name="btnDel" value="<?= $stfOfcId ?>" class="btn trash2 del"></button>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <select name="upOfc[0][place_id]" class="sel_place" style="width:330px;" required>
                                                                            <option></option>
                                                                            <?php foreach ($plcMst as $plcMstId => $plcVal): ?>
                                                                                <option data-place_id="<?= $plcMstId ?>" value="<?= $plcMstId ?>"><?= $plcVal['name'] ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td class="office_in">
                                                                    <div>
                                                                        <select name="upOfc[0][office1_id]" class="upOfc1"  style="width:330px;" required>
                                                                            <option></option>
                                                                            <?php foreach ($plcOfc as $plcId => $plcOfc2): ?>
                                                                                <?php foreach ($plcOfc2 as $ofcId => $ofcName): ?>
                                                                                    <option class="cngPlace" data-place_id="<?= $plcId ?>" data-place_name="<?= $plcList[$plcId]['name'] ?>" value="<?= $ofcId ?>"><?= $ofcName ?></option>
                                                                                <?php endforeach; ?>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <select name="upOfc[0][office2_id]" class="upOfc2" style="width:330px;">
                                                                            <option></option>
                                                                            <?php foreach ($plcOfc as $plcId => $plcOfc2): ?>
                                                                                <?php foreach ($plcOfc2 as $ofcId => $ofcName): ?>
                                                                                    <option class="cngPlace" data-place_id="<?= $plcId ?>" data-place_name="<?= $plcList[$plcId]['name'] ?>" value="<?= $ofcId ?>" ><?= $ofcName ?></option>
                                                                                <?php endforeach; ?>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="aff_date line">
                                                                        <input type="date" name="upOfc[0][start_day]" style="width:130px;" value="" required>
                                                                        <small>～</small>
                                                                        <input type="date" name="upOfc[0][end_day]" class="" style="width:130px;" value="">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php foreach ($dispOfc as $keyId => $stfOfc) : ?>
                                                            <?php $stfOfcId = $stfOfc['unique_id']; ?>
                                                                <input type="hidden" name="upOfc[<?= $stfOfcId ?>][unique_id]" value="<?= $stfOfcId ?>">
                                                            <tr>
                                                                <td class="del_tr">
                                                                    <span class="btn trash2">
                                                                        <button type="submit" name="btnDel" value="<?= $stfOfcId ?>" class="btn trash2"></button>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <select name="upOfc[<?= $stfOfcId ?>][place_id]" class="sel_place" style="width:330px;" required>
                                                                            <option></option>
                                                                            <?php foreach ($plcMst as $plcMstId => $plcVal): ?>
                                                                                <?php $selected = $stfOfc['place_id'] == $plcMstId ? " selected" : ""; ?>
                                                                                <option data-place_id="<?= $plcMstId ?>" value="<?= $plcMstId ?>" <?= $selected ?> ><?= $plcVal['name'] ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td class="office_in">
                                                                    <div>
                                                                        <select name="upOfc[<?= $stfOfcId ?>][office1_id]" style="width:330px;" required>
                                                                            <option></option>
                                                                            <?php foreach ($plcOfc as $plcId => $plcOfc2): ?>
                                                                                <?php foreach ($plcOfc2 as $ofcId => $ofcName): ?>
                                                                                    <?php $selected = $stfOfc['office1_id'] == $ofcId ? " selected" : ""; ?>
                                                                                    <option class="cngPlace" data-place_id="<?= $plcId ?>" data-place_name="<?= $plcList[$plcId]['name'] ?>" value="<?= $ofcId ?>" <?= $selected ?> ><?= $ofcName ?></option>
                                                                                <?php endforeach; ?>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <select name="upOfc[<?= $stfOfcId ?>][office2_id]" style="width:330px;">
                                                                            <option></option>
                                                                            <?php foreach ($plcOfc as $plcId => $plcOfc2): ?>
                                                                                <?php foreach ($plcOfc2 as $ofcId => $ofcName): ?>
                                                                                    <?php $selected = $stfOfc['office2_id'] == $ofcId ? " selected" : ""; ?>
                                                                                    <option class="cngPlace" data-place_id="<?= $plcId ?>" data-place_name="<?= $plcList[$plcId]['name'] ?>" value="<?= $ofcId ?>" <?= $selected ?> ><?= $ofcName ?></option>
                                                                                <?php endforeach; ?>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="aff_date line">
                                                                        <input type="date" name="upOfc[<?= $stfOfcId ?>][start_day]" class="" style="width:130px;" value="<?= $stfOfc['start_day'] ?>" required>
                                                                        <small>～</small>
                                                                        <input type="date" name="upOfc[<?= $stfOfcId ?>][end_day]" class="" style="width:130px;" value="<?= $stfOfc['end_day'] ?>">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="box-r">
                                            <div class="box-r-in">
                                                <dl>
                                                    <dt><label for="billing-shikaku">主たる資格</label></dt>
                                                    <dd><div><select id="billing-shikaku" name="upAry[license1]">
                                                                <option value="" disabled <?= $dispData['license1'] == '' ? 'selected' : '' ?> hidden>選択してください</option>
                                                                <?php foreach ($gnrList['従業員マスタ']['請求用資格'] as $val): ?>
                                                                    <option value="<?= $val ?>" <?= $dispData['license1'] == $val ? ' selected' : '' ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select></div>
                                                        <div class="pc"><span class="label_t"><label for="emp_job">看多機用職種</label></span>
                                                            <select id="emp_job" name="upAry[job]">
                                                                <option value="" disabled <?= $dispData['job'] == '' ? 'selected' : '' ?> hidden>計画作成担当者</option>
                                                                <?php foreach ($gnrList['従業員マスタ']['職種'] as $val): ?>
                                                                    <option value="<?= $val ?>" <?= $dispData['job'] == $val ? 'selected' : '' ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </dd>
                                                </dl>
                                                <dl>
                                                    <dt><label>保有資格</label></dt>
                                                    <dd><ul>
                                                            <?php $i = 0; ?>
                                                            <?php foreach ($gnrList['従業員マスタ']['保有資格'] as $i => $val): ?>
                                                                <?php $i++; ?>
                                                                <li>
                                                                    <input type="checkbox" name="upAry[license2][]" id="license2-<?= $i ?>" value="<?= $val ?>" <?= mb_strpos($dispData['license2'], $val) !== false ? 'checked' : '' ?>>
                                                                    <label for="license2-<?= $i ?>"><?= $val ?></label>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul></dd>
                                                </dl>
                                                <dl>
                                                    <dt class="pc"><label for="sys-kengen">システム権限<span class="req">*</span></label></dt>
                                                    <dd>
                                                        <div>
                                                            <span class="sm label_t"><label for="sys-kengen">システム権限<span class="req">*</span></label></span>
                                                            <select id="sys-kengen" name="upAry[type]">
                                                                <option value="" disabled <?= $dispData['type'] == '' ? 'selected' : '' ?> hidden>選択してください</option>
                                                                <?php foreach ($gnrList['従業員マスタ']['システム権限'] as $val): ?>
                                                                    <option value="<?= $val ?>" <?= $dispData['type'] == $val ? 'selected' : '' ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <span class="label_t"><label for="shain-kubun">社員区分</label></span>
                                                            <select id="shain-kubun" name="upAry[employee_type]">
                                                                <option value="" disabled <?= $dispData['employee_type'] == '' ? 'selected' : '' ?> hidden></option>
                                                                <?php foreach ($gnrList['従業員マスタ']['社員区分'] as $val): ?>
                                                                    <option value="<?= $val ?>" <?= $dispData['employee_type'] == $val ? 'selected' : '' ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </dd>
                                                </dl>
                                                <dl class="date_birth">
                                                    <dt><label>生年月日</label></dt>
                                                    <dd>
                                                        <div class="era">
                                                            <select id="era_list" name="年号">
                                                                <option value="明治" <?= $dispData['birthAry']['nengo'] == "明治" ? " selected" : "" ?>>明治</option>
                                                                <option value="大正" <?= $dispData['birthAry']['nengo'] == "大正" ? " selected" : "" ?>>大正</option>
                                                                <option value="昭和" <?= $dispData['birthAry']['nengo'] == "昭和" ? " selected" : "" ?>>昭和</option>
                                                                <option value="平成" <?= $dispData['birthAry']['nengo'] == "平成" ? " selected" : "" ?>>平成</option>
                                                                <option value="令和" <?= $dispData['birthAry']['nengo'] == "令和" ? " selected" : "" ?>>令和</option>
                                                            </select>
                                                            <span><input type="text" name=" name="和暦" value="<?= $dispData['birthAry']['wareki'] ?>" id="era_yr"><label for="era_yr">年</label></span>
                                                        </div>
                                                        <div><input type="text" name="upAry[birthday][Y]" value="<?= $dispData['birthAry']['Y'] ?>" id="birth_yr"><label for="birth_yr">年</label></div>
                                                        <div><input type="text" name="upAry[birthday][m]" value="<?= $dispData['birthAry']['m'] ?>" id="birth_m"><label for="birth_m">月</label></div>
                                                        <div><input type="text" name="upAry[birthday][d]" value="<?= $dispData['birthAry']['d'] ?>" id="birth_d"><label for="birth_d">日</label></div>
                                                    </dd>
                                                </dl>
                                                <dl>
                                                    <dt><label>性別</label></dt>
                                                    <dd><div><input type="radio" name="upAry[sex]" id="emp_gender-f" value="女性" <?= $dispData['sex'] == '女性' ? 'checked' : '' ?>><label for="emp_gender-f">女性</label></div>
                                                        <div><input type="radio" name="upAry[sex]" id="emp_gender-m" value="男性" <?= $dispData['sex'] == '男性' ? 'checked' : '' ?>><label for="emp_gender-m">男性</label></div>
                                                    </dd>
                                                </dl>
                                                <dl>
                                                    <dt><label for="emp_address">住所</label></dt>
                                                    <dd><input type="text" name="upAry[address]" id="emp_address" value="<?= $dispData['address'] ?>"></dd>
                                                </dl>
                                                <dl>
                                                    <dt class="pc"><label for="emp_bango">電話番号</label></dt>
                                                    <dd><div><label for="emp_bango" class="sm">電話番号</label><input type="tel" name="upAry[tel]" id="emp_bango" value="<?= $dispData['tel'] ?>"></div>
                                                        <div><label for="emp_em-contact">緊急連絡先</label><input type="text" name="upAry[emg_contact]" id="emp_em-contact" value="<?= $dispData['emg_contact'] ?>"></div>
                                                    </dd>
                                                </dl>
                                                <dl>
                                                    <dt><label for="emp_email">メールアドレス</label></dt>
                                                    <dd><input type="email" name="upAry[mail]" id="emp_email" value="<?= $dispData['mail'] ?>"></dd>
                                                </dl>
                                                <dl>
                                                    <dt><label>自動車免許</label></dt>
                                                    <dd><div><input type="checkbox" name="upAry[driving_license]" id="emp_dlicense-ari" value="1" <?= $dispData['driving_license'] == '1' ? 'checked' : '' ?>><label for="emp_dlicense-ari">あり</label></div>
                                                        <div><label for="emp_dlicense-retire">退職</label><input type="checkbox" name="upAry[retired]" id="emp_status" value="1" <?= $dispData['retired'] == '1' ? 'checked' : '' ?>></div>
                                                    </dd>
                                                </dl>
                                                <dl>
                                                    <dt><label for="emp_note">備考</label></dt>
                                                    <dd><div><input type="text" name="upAry[remarks]" id="emp_note" value="<?= $dispData['remarks'] ?>"></div>
                                                    </dd>
                                                </dl>
                                            </div>
                                        </div>
                                        <div class="em-contact_box">
                                            <div class="tit tit_toggle">本人以外の緊急連絡先</div>
                                            <div class="tb_box1 child_toggle">
                                                <dl>
                                                    <dt><label for="em-con-name">氏名(漢字)</label></dt>
                                                    <dd><div><input type="text" name="upAry[emg_name]" id="em-con-name" value="<?= $dispData['emg_name'] ?>"></div>
                                                        <div class="pc"><span class="label_t"><label for="em-con-gender">続柄</label></span><input type="text" name="upAry[relation_type]" id="em-con-gender" value="<?= $dispData['relation_type'] ?>"></div>
                                                    </dd>
                                                </dl>
                                                <dl>
                                                    <dt><label for="em-con-namek">氏名(カナ)</label></dt>
                                                    <dd><input type="text" name="upAry[emg_kana]" id="em-con-namek" value="<?= $dispData['emg_kana'] ?>"></dd>
                                                </dl>
                                                <dl>
                                                    <dt><label for="em-con-bango">電話番号</label></dt>
                                                    <dd><div><input type="tel" name="upAry[emg_tel]" id="em-con-bango" value="<?= $dispData['emg_tel'] ?>"></div>
                                                        <div class="pc"><span class="label_t"><label for="em-con-mbango">携帯電話番号</label></span><input type="tel" name="upAry[emg_phone]" id="em-con-mbango" value="<?= $dispData['emg_phone'] ?>"></div>
                                                    </dd>
                                                </dl>
                                            </div>
                                            <div class="tb_box2 child_toggle">
                                                <dl>
                                                    <dt><label for="em-con-address">住所</label></dd>
                                                    <dd><div><input type="text" name="upAry[emg_address]" id="em-con-address" value="<?= $dispData['emg_address'] ?>"></div></dd>
                                                </dl>
                                                <dl>
                                                    <dt><label for="em-con-email">メールアドレス</label></dd>
                                                    <dd><div><input type="email" name="upAry[emg_mail]" id="em-con-email" value="<?= $dispData['emg_mail'] ?>"></div></dd>
                                                </dl>
                                                <dl>
                                                    <dt><label for="em-con-note">備考</label></dd>
                                                    <dd><div><input type="text" name="upAry[emg_remarks]" id="em-con-note" value="<?= $dispData['emg_remarks'] ?>"></div></dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nurse_record record9">
                                        <div class="i_register">
                                            <span class="label_t">初回登録：</span>
                                            <span class="label_t hidzuke"><?= $dispData['create_day'] ?></span>
                                            <span class="label_t time"><?= $dispData['create_time'] ?></span>
                                            <span class="label_t"><?= $dispData['create_name'] ?></span>|
                                        </div>
                                        <div class="l_update">
                                            <span class="label_t">更新日時：</span>
                                            <span class="label_t hidzuke"><?= $dispData['update_day'] ?></span>
                                            <span class="label_t time"><?= $dispData['update_time'] ?></span>
                                            <span class="label_t"><?= $dispData['update_name'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/system/staff_edit/dialog/place.php'); ?>
                                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/system/staff_edit/dialog/office_simple.php'); ?>
                                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/system/staff_edit/dialog/office_copy.php'); ?>

                            </div></div>
                        <!--/// CONTENT_END ///-->
                        <div class="fixed_navi staff-navi staff-d-navi">
                            <div class="box">
                                <div class="btn back pc"><a href="/system/staff_list" style="padding-top:15px; ">従業員一覧にもどる</a></div>
                                <div class="controls">
                                    <div class="btn cancel"><a href="/system/staff_list">キャンセル</a></div>
                                    <button type="submit" name="btnEntry" value="true" class="btn save btnEntry">保存</button>
                                </div>
                            </div>
                        </div>
                    </article>
                </form >
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
        <script>

    $(document).find(".sel_place").each(function(){
       changePlace($(this)); 
    });
    
            //行追加
            $(".addBase").click(function () {
                
                var tbody = $("#affliate_add").find("tbody");
                var len = tbody.children().length;
                len++;
                var tr_new = "";
                tr_new += '    <tr>';
                tr_new += '        <td class="del_tr">';
                tr_new += '            <span class="btn trash2 del">';
                tr_new += '                <button type="button" name="btnDel" value="" class="btn trash2 del"></button>';
                tr_new += '            </span>';
                tr_new += '        </td>';
                tr_new += '        <td>';
                tr_new += '            <div>';
                tr_new += '                <select name="upOfc[' + len + '][place_id]" class="sel_place" style="width:330px;" required>';
                tr_new += '                     <option></option>';
<?php foreach ($plcMst as $plcMstId => $plcVal): ?>
                    tr_new += '                     <option data-place_id="<?= $plcMstId ?>" value="<?= $plcMstId ?>"><?= $plcVal['name'] ?></option>';
<?php endforeach; ?>
                tr_new += '                </select>';
                tr_new += '            </div>';
                tr_new += '        </td>';
                tr_new += '        <td class="office_in">';
                tr_new += '            <div>';
                tr_new += '                <select name="upOfc[' + len + '][office1_id]" style="width:330px;" required>';
                tr_new += '                    <option></option>';
<?php foreach ($plcOfc as $plcMstId => $plcOfc2): ?>
    <?php foreach ($plcOfc2 as $ofcId => $ofcName): ?>
                        tr_new += '                    <option class="cngPlace" data-place_id="<?= $plcMstId ?>" data-place_name="<?= isset($plcList[$plcMstId]["name"]) ? $plcList[$plcMstId]["name"] : '' ?>" value="<?= $ofcId ?>" ><?= $ofcName ?></option>';
    <?php endforeach; ?>
<?php endforeach; ?>
                tr_new += '                </select>';
                tr_new += '            </div>';
                tr_new += '            <div>';
                tr_new += '                <select name="upOfc[' + len + '][office2_id]" style="width:330px;">';
                tr_new += '                    <option></option>';
<?php foreach ($plcOfc as $plcMstId => $plcOfc2): ?>
    <?php foreach ($plcOfc2 as $ofcId => $ofcName): ?>
                        tr_new += '                    <option class="cngPlace" data-place_id="<?= $plcMstId ?>" data-place_name="<?= isset($plcList[$plcMstId]["name"]) ? $plcList[$plcMstId]["name"] : '' ?>" value="<?= $ofcId ?>"><?= $ofcName ?></option>';
    <?php endforeach; ?>
<?php endforeach; ?>
                tr_new += '                </select>';
                tr_new += '            </div>';
                tr_new += '        </td>';
                tr_new += '        <td>';
                tr_new += '            <div class="aff_date line">';
                tr_new += '                <input type="date" name="upOfc[' + len + '][start_day]" class="" style="width:130px;" value="" required>';
                tr_new += '                <small>～</small>';
                tr_new += '                <input type="date" name="upOfc[' + len + '][end_day]" class="" style="width:130px;" value="">';
                tr_new += '            </div>';
                tr_new += '        </td>';
                tr_new += '    </tr>';

                $(tr_new).appendTo(tbody);


                var script = document.createElement('script');
                script.src = "/system/staff_edit/js/staff.js";
                document.head.appendChild(script);

                $(".date_no-Day").datepicker({dateFormat: 'yy/mm/dd'});
                // ※appendで追加要素には親要素でクリックイベントを定義する必要あり

                // 所属事業所検索モーダル
                $(".place_search").on('click', function () {
                    var lenx = $(this).data("len");
                    $(".tgt-len").val(lenx);
                    $(".cont_place_search").show();
                });
                $(".cont_place_search .close").click(function () {
                    $(".cont_place_search").hide();
                });

                // 所属事業所検索モーダル
                $(".office_simple").on('click', function () {
                    var lenx = $(this).data("len");
                    $(".tgt-len").val(lenx);
                    $(".cont_office_simple").show();
                });
                $(".cont_office_simple .close").click(function () {
                    $(".cont_office_simple").hide();
                });

                // 所属事業所検索モーダル
                $(".office_copy").on('click', function () {
                    var lenx = $(this).data("len");
                    $(".tgt-len").val(lenx);
                    $(".cont_office_copy").show();
                });
                $(".cont_office_copy .close").click(function () {
                    $(".cont_office_copy").hide();
                });

                // ID直接入力
                $(".tgt-ofc_id").on("input", function () {
                    // 入力テキスト取得
                    var inputText = $(this).val();

                    // IDの一致チェック
                    $(".cont_office_simple button").each(function () {
                        var ofc_id = $(this).data("ofc_id");
                        var ofc_name = $(this).data("ofc_name");

                        if (id == inputText) {
                            $(".tgt-ofc_name").val(ofc_name);
                        }
                    });
                });
            });

            $("#affliate_add").on('click', '.addInput', function (event) {
                event.preventDefault();
                var inputDiv = $(this).closest("tr").find(".office_in");
                var len = inputDiv.children().length;
                var inp_new = "";
                inp_new += '<div><span class="n_search">Search</span><input type="text" name="所属事業所" placeholder="所属事務所を選択してください"><span class="btn trash2 del_inp"></span></div>';
                $(inp_new).appendTo(inputDiv);

            });

            //削除
            $("#affliate_add").on('click', '.del', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                return false;
            });

            $("#affliate_add").on('click', '.del_inp', function (event) {
                event.preventDefault();
                $(this).closest('div').remove();
                return false;
            });
            
            function getOfcName() {
                // モーダルから選択
                $(".cont_place_search").find("table button").on("click", function () {
                    // 各種データ取得
                    var plc_id = $(this).data("plc_id");
                    var plc_name = $(this).data("plc_name");
                    var len = $(".tgt-len").val();

                    /* ※表示先のinputには .tgt-plc_id .tgt-plc_name のクラスを指定しておくこと。 */

                    //        $(".tgt-plc_id").val(plc_id);
                    $(".tgt-plc_name" + len).val(plc_name);

                    // windowを閉じる
                    $(".cont_place_search").hide();
                });

                // ID直接入力
                $(".tgt-plc_id").on("input", function () {
                    // 入力テキスト取得
                    var inputText = $(this).val();

                    // IDの一致チェック
                    $(".cont_place_search button").each(function () {
                        var plc_id = $(this).data("plc_id");
                        var plc_name = $(this).data("plc_name");

                        if (plc_id == inputText) {
                            $(".tgt-plc_name").val(plc_name);
                        }
                    });
                });
            }

            function getPlcName() {

                // モーダルから選択
                $(".cont_office_simple").find("table button").on("click", function () {
                    // 各種データ取得
                    var ofc_id = $(this).data("ofc_id");
                    var ofc_name = $(this).data("ofc_name");
                    var len = $(".tgt-len").val();
                    /* ※表示先のinputには .tgt-ofc_id .tgt-ofc_name のクラスを指定しておくこと。 */

                    //        $(".tgt-ofc_id").val(ofc_id);
                    $(".tgt-ofc_name" + len).val(ofc_name);

                    // windowを閉じる
                    $(".cont_office_simple").hide();
                });

                // ID直接入力
                $(".tgt-ofc_id").on("input", function () {
                    // 入力テキスト取得
                    var inputText = $(this).val();

                    // IDの一致チェック
                    $(".cont_office_simple button").each(function () {
                        var ofc_id = $(this).data("ofc_id");
                        var ofc_name = $(this).data("ofc_name");

                        if (id == inputText) {
                            $(".tgt-ofc_name").val(ofc_name);
                        }
                    });
                });
            }

            function getPlcName2() {

                // モーダルから選択
                $(".cont_office_copy").find("table button").on("click", function () {
                    // 各種データ取得
                    var ofc_id = $(this).data("ofc_id");
                    var ofc_name = $(this).data("ofc_name");
                    var len = $(".tgt-len").val();
                    /* ※表示先のinputには .tgt-ofc_id .tgt-ofc_name のクラスを指定しておくこと。 */

                    //        $(".tgt-ofc_id").val(ofc_id);
                    $(".tgt-ofc_name2" + len).val(ofc_name);

                    // windowを閉じる
                    $(".cont_office_copy").hide();
                });

                // ID直接入力
                $(".tgt-ofc_id").on("input", function () {
                    // 入力テキスト取得
                    var inputText = $(this).val();

                    // IDの一致チェック
                    $(".cont_office_copy button").each(function () {
                        var ofc_id = $(this).data("ofc_id");
                        var ofc_name = $(this).data("ofc_name");

                        if (id == inputText) {
                            $(".tgt-ofc_name").val(ofc_name);
                        }
                    });
                });
            }

        </script>
    </body>
</html>