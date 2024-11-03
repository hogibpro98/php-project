<?php require_once(dirname(__FILE__) . "/php/instruct.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>指示書</title>
        <style>
            .select7{
                content: "";
                position: absolute;
                right: 0;
                top: 0;
                width: 55px;
                height: 29px;
                transform: rotate(-6deg);
                background: url(/common/image/icon_stamp.png) no-repeat;
                background-size: contain;
                opacity: 0.6;
            }
        </style>
        <script>
            $(function () {
                // 指示期間の終了日を補完する
                $(".direction_months").on('change', function () {
                    // コンボの値を取得
                    var cmbVal = $(this).val();
                    if (cmbVal === null) {
                        return false;
                    }
                    // 開始日の取得
                    var startDate = $(".direction_start").val();
                    if (startDate === null)
                    {
                        return false;
                    }
                    var offsetMonth = 0;
                    var offsetDay = 0;
                    var lastDay = 0;
                    var arr = startDate.split('-');
                    var date = new Date();
                    // コンボボックス値を元にオフセット値を設定する
                    if (cmbVal === "1週間") {
                        offsetDay = 6;
                    } else if (cmbVal === "2週間") {
                        offsetDay = 13;
                    }
                    if (cmbVal === "1か月") {
                        offsetMonth = 1;
                        lastDay = 1;
                    } else if (cmbVal === "2か月") {
                        offsetMonth = 2;
                        lastDay = 1;
                    } else if (cmbVal === "3か月") {
                        offsetMonth = 3;
                        lastDay = 1;
                    } else if (cmbVal === "4か月") {
                        offsetMonth = 4;
                        lastDay = 1;
                    } else if (cmbVal === "5か月") {
                        offsetMonth = 5;
                        lastDay = 1;
                    } else if (cmbVal === "6か月") {
                        offsetMonth = 6;
                        lastDay = 1;
                    }
                    date = new Date(parseInt(arr[0]), parseInt(arr[1]) - 1 + offsetMonth, parseInt(arr[2]) + offsetDay);
                    if (lastDay === 1) {
                        date.setDate(0);
                    }
                    var y = date.getFullYear();
                    var m = ('00' + (date.getMonth() + 1)).slice(-2);
                    var d = ('00' + date.getDate()).slice(-2);
                    var endDate = (y + '-' + m + '-' + d);
                    $(".direction_end").val(endDate);
                });
                // 指示期間変更イベント
                $(".direction_start").on('change', function () {
                    var sirDate = $(".direction_start").val();
                    $(".judgement_day").val(sirDate);
                });
                // 別表８変更
                $('input[id=appendix8').change(function () {
                    let ckDtlList = document.querySelectorAll('.appendix8Dtl');
                    if ($(this).prop('checked')) {
                        for (let i in ckDtlList) {
                            if (ckDtlList.hasOwnProperty(i)) {
                                ckDtlList[i].disabled = false;
                            }
                        }
                        $(".dtl8Disp").toggle(true);
                    } else {
                        //全て解除
                        for (let i in ckDtlList) {
                            if (ckDtlList.hasOwnProperty(i)) {
                                ckDtlList[i].checked = false;
                                ckDtlList[i].disabled = true;
                            }
                        }
                        $(".dtl8Disp").toggle(false);
                    }
                });
            });
            function setDirectionEndDate() {
                date = new Date(parseInt(arr[0]), parseInt(arr[1]) - 1 + offsetMonth, parseInt(arr[2]) + offsetDay);
                var y = date.getFullYear();
                var m = ('00' + (date.getMonth() + 1)).slice(-2);
                var d = ('00' + date.getDate()).slice(-2);
                var endDate = (y + '-' + m + '-' + d);
                $(".direction_end").val(endDate);
            }
        </script>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <form action="" method="post" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">
                        <h2>指示書</h2>
                        <div id="patient" class="sm"></div>
                        <div id="subpage"><div id="directions" class="nursing">
                                <div class="wrap">
                                    <ul class="user-tab">
                                        <li><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
                                        <li class="active"><a href="/report/list/?user=<?= $userId ?>">各種帳票</a></li>
                                        <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
                                    </ul>
                                    <?php if (!empty($dispData['unique_id'])) : ?>
                                        <input type="hidden" name="upAry[unique_id]" value="$dispData['unique_id']">
                                    <?php endif; ?>
                                    <div class="patient_info">
                                        <div class="box-l">
                                            <ul class="pdf_controls">
                                                <li class="btn upload">
                                                    <label>
                                                        <div class="upload-input-file-btn">PDF取り込み</div>
                                                        <input type="file" name="file" id="ifile" accept=".jpg,.png,.pdf" style="display:none;">
                                                    </label>
                                                </li>
                                                <?php if (!empty($dispData['pdf_file'])) : ?>
                                                    <li class="btn download ">
                                                        <a href="<?= !empty($dispData['pdf_file']) ? $dispData['pdf_file'] : '' ?>"  target="_blank">ダウンロード</a></li>
                                                    </li>
                                                <?php endif; ?>
                                                <li class="btn delete"  style="width:110px">
                                                    <label>
                                                        <button type="submit" name="btnDelPdf" value="<?= $dispData['unique_id'] ?>">PDF削除</button>
                                                    </label>
                                                </li>
                                            </ul>
                                            <div id="preview" class="pdf_view">
                                                <!-- PDFビューア -->
                                                <?php $display_pdf = $dispData['pdf_file'] === 'pdf' ? 'style="display:none;"' : NULL; ?>
                                                <embed id="pdf_view" type="application/pdf" class="pdf_view" src="<?= $dispData['pdf_file'] ?>" width="100%" height="800" title="PDFのembed" <?= $display_pdf ?>>
                                                <?php $display_image = $dispData['pdf_file'] !== 'pdf' ? 'style="display:none;"' : NULL; ?>
                                                <img id="img_view" name="upAry[pdf_file]" src="<?= $dispData['pdf_file'] ?>" value="<?= $dispData['pdf_file'] ?>" <?= $display_image ?>>
                                            </div>                                            
                                            <div class="filename" style="width:450px;"><?= !empty($dispData['pdf_file']) ? $dispData['pdf_file'] : '' ?></div>
                                        </div>
                                        <div class="box-r">
                                            <div class="user-details">
                                                <div class="d_right">
                                                    <p>
                                                        <?php $disabled = empty($keyId) ? 'disabled' : NULL; ?>
<!--                                                        <button type="submit" class="btn print" name="btnPrint" value="<?= $dispData['unique_id'] ?>">印刷</button>-->
                                                        <button type="submit" class="btn-edit" name="btnCopy" value="<?= $dispData['unique_id'] ?>" <?= $disabled ?>>複製</button>
                                                        <button type="submit" class="btn-del" name="btnDel" value="<?= $dispData['unique_id'] ?>">削除</button>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="nurse_record pdf_info">
                                                <div class="line profile">
                                                    <div class="name">
                                                        <span class="label_t">担当者</span>
                                                        <p class="n_search staff_search">Search</p>
                                                        <input type="hidden" class="n_num tgt-stf_id" name="upAry[staff_id]" value="<?= $dispData['staff_id'] ?>">
                                                        <input type="text" class="n_num tgt-stf_cd" name="upDummy[staff_cd]" value="<?= $dispData['staff_cd'] ?>">
                                                        <input type="text" class="n_name tgt-stf_name bg-gray2" name="upDummy[staff_name]" value="<?= $dispData['staff_name'] ?>" readonly="">
                                                    </div>
                                                    <div class="user-id">
                                                        <span class="label_t">利用者ID</span>
                                                        <p class="n_search user_search">Search</p>
                                                        <input type="text" name="upDummy[other_id]" class="tgt-usr_id" value="<?= $dispData['other_id'] ?>" maxlength="7" pattern="^[0-9]+$">
                                                        <input type="hidden" name="upAry[user_id]" class="tgt-unique_id" value="<?= $dispData['user_id'] ?>">
                                                    </div>
                                                    <div class="user-name">
                                                        <span class="label_t">利用者氏名</span>
                                                        <input type="text" name="upDummy[user_name]" value="<?= $dispData['user_name'] ?>" class="tgt-usr_name bg-gray2" readonly>
                                                    </div>
                                                    <br>
                                                    <div class="birthday">
                                                        <span class="label_t">生年月日</span>
                                                        <input type="text" name="upDummy[birthday]" value="<?= $dispData['birthday_disp'] ?>" class="n_birthday tgt-usr_birthday bg-gray2" readonly>
                                                        <input type="text" name="upDummy[age]" value="<?= $dispData['age'] ?>" class="n_age tgt-usr_age bg-gray2" readonly>
                                                    </div>
                                                    <div class="care">
                                                        <span class="label_t">要介護度</span>
                                                        <input type="text" name="upDummy[care_rank]" class="n_rank tgt-usr_rank bg-gray2" value="<?= $dispData['care_rank'] ?>" readonly>
                                                    </div>
                                                    <div class="address">
                                                        <span class="label_t">住所</span>
                                                        <input type="text" name="upDummy[address]" class="n_adr tgt-usr_adr bg-gray2" value="<?= $dispData['address'] ?>" readonly>
                                                    </div>
                                                    <div class="i_period">
                                                        <span class="label_t">指示期間<small class="req">*</small></span>
                                                        <input type="date" name="upAry[direction_start]" class="direction_start" value="<?= $dispData['direction_start'] ?>">
                                                        <small>～</small>
                                                        <input type="date" name="upAry[direction_end]" class="direction_end" value="<?= $dispData['direction_end'] ?>">
                                                        <select name="upAry[direction_months]" class="direction_months">
                                                            <option value=""></option>
                                                            <?php foreach ($gnrList['指示期間'] as $key => $val): ?>
                                                                <?php $select = $dispData['direction_months'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="pl_date">
                                                        <span class="label_t">計画書年月日</span>
                                                        <input type="date" name="upAry[plan_day]" class="" value="<?= $dispData['plan_day'] ?>">
                                                    </div>
                                                    <div class="rep_date">
                                                        <span class="label_t">報告書年月日<small class="req">*</small></span>
                                                        <input type="date" name="upAry[report_day]" class="" value="<?= $dispData['report_day'] ?>">
                                                    </div>
                                                    <div class="line3">
                                                        <div class="n_cate">
                                                            <span class="label_t">看護区分<small class="req">*</small></span>
                                                            <select name="upAry[care_kb]" class="">
                                                                <option selected hidden disabled>選択してください</option>
                                                                <?php foreach ($gnrList['看護区分'] as $key => $val): ?>
                                                                    <?php $select = $dispData['care_kb'] == $val ? ' selected' : NULL; ?>
                                                                    <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="class">
                                                            <span class="label_t">指示区分<small class="req">*</small></span>
                                                            <select name="upAry[direction_kb]" class="">
                                                                <option selected hidden disabled>選択してください</option>
                                                                <!--                                <option>通常指示</option>
                                                                                                <option>特別指示</option>
                                                                                                <option>点滴指示</option>-->
                                                                <?php foreach ($gnrList['指示区分'] as $key => $val): ?>
                                                                    <?php $select = $dispData['direction_kb'] == $val ? ' selected' : NULL; ?>
                                                                    <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nurse_record user_stat">
                                                <div class="tit tit_toggle">利用者状態情報</div>
                                                <div class="box_wrap child_toggle">
                                                    <div class="j_date">
                                                        <span class="label_t">判定年月日<small class="req">*</small></span>
                                                        <input type="date" name="upAry[judgement_day]" class="judgement_day" value="<?= $dispData['judgement_day'] ?>">
                                                    </div>
                                                    <div class="mid">病状・治療状態・心身の状態</div>
                                                    <div class="column receipt">
                                                        <p class="mid1"><b>レセ出力欄</b><small>全角125文字まで</small></p>
                                                        <textarea maxlength="125" name="upAry[rece_detail]" value="<?= $dispData['rece_detail'] ?>"><?= $dispData['rece_detail'] ?></textarea>
                                                    </div>
                                                    <div class="column postscript">
                                                        <p class="mid1"><b>追記欄</b><small>連携データ<br class="pc">非連動</small></p>
                                                        <textarea maxlength="125" name="upAry[postscript]" value="<?= $dispData['postscript'] ?>"><?= $dispData['postscript'] ?></textarea>
                                                    </div>
                                                    <div class="m_injury">
                                                        <span class="label_t">主たる傷病名</span>
                                                        <ol class="inj_list ">
                                                            <li>
                                                                <!--<p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease.php?tgt_name=disease1&tgt_flg=sel71" data-dialog_name="dynamic_modal">Search</p>-->
                                                                <p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease_search_dialog.php?tgt_name=disease1&tgt_flg=sel71" data-set_name="modal_setting2" data-dialog_name="modal_disease_search">Search</p>
                                                                <input type="text" name="upAry[sickness1]" class="disease1 disease" value="<?= $dispData['sickness1'] ?>" style="width:260px;">
                                                                <?php $select7Flg = isset($sickList[$dispData['sickness1']]) ? $sickList[$dispData['sickness1']]['target_flg1'] : '0'; ?>
                                                                <span class="sel71 <?= $select7Flg === '1' ? " select7" : "" ?>"></span>
                                                            </li>
                                                            <li>
                                                                <p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease_search_dialog.php?tgt_name=disease2&tgt_flg=sel72" data-set_name="modal_setting2" data-dialog_name="modal_disease_search">Search</p>
                                                                <input type="text" name="upAry[sickness2]" class="disease2 disease" value="<?= $dispData['sickness2'] ?>" style="width:260px;">
                                                                <?php $select7Flg = isset($sickList[$dispData['sickness2']]) ? $sickList[$dispData['sickness2']]['target_flg1'] : '0'; ?>
                                                                <span class="sel72 <?= $select7Flg === '1' ? " select7" : "" ?>"></span>
                                                            </li>
                                                            <li>
                                                                <p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease_search_dialog.php?tgt_name=disease3&tgt_flg=sel73" data-set_name="modal_setting2" data-dialog_name="modal_disease_search">Search</p>
                                                                <input type="text" name="upAry[sickness3]" class="disease3 disease" value="<?= $dispData['sickness3'] ?>" style="width:260px;">
                                                                <?php $select7Flg = isset($sickList[$dispData['sickness3']]) ? $sickList[$dispData['sickness3']]['target_flg1'] : '0'; ?>
                                                                <span class="sel73 <?= $select7Flg === '1' ? " select7" : "" ?>"></span>
                                                            </li>
                                                            <li>
                                                                <p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease_search_dialog.php?tgt_name=disease4&tgt_flg=sel74" data-set_name="modal_setting2" data-dialog_name="modal_disease_search">Search</p>
                                                                <input type="text" name="upAry[sickness4]" class="disease4 disease" value="<?= $dispData['sickness4'] ?>" style="width:260px;">
                                                                <?php $select7Flg = isset($sickList[$dispData['sickness4']]) ? $sickList[$dispData['sickness4']]['target_flg1'] : '0'; ?>
                                                                <span class="sel74 <?= $select7Flg === '1' ? " select7" : "" ?>"></span>
                                                            </li>
                                                            <li>
                                                                <p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease_search_dialog.php?tgt_name=disease5&tgt_flg=sel75" data-set_name="modal_setting2" data-dialog_name="modal_disease_search">Search</p>
                                                                <input type="text" name="upAry[sickness5]" class="disease5 disease" value="<?= $dispData['sickness5'] ?>" style="width:260px;">
                                                                <?php $select7Flg = isset($sickList[$dispData['sickness5']]) ? $sickList[$dispData['sickness5']]['target_flg1'] : '0'; ?>
                                                                <span class="sel75 <?= $select7Flg === '1' ? " select7" : "" ?>"></span>
                                                            </li>
                                                        </ol>
                                                        <ol class="inj_list list2">
                                                            <li>
                                                                <p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease_search_dialog.php?tgt_name=disease6&tgt_flg=sel76" data-set_name="modal_setting2" data-dialog_name="modal_disease_search">Search</p>
                                                                <input type="text" name="upAry[sickness6]" class="disease6 disease" value="<?= $dispData['sickness6'] ?>" style="width:260px;">
                                                                <?php $select7Flg = isset($sickList[$dispData['sickness6']]) ? $sickList[$dispData['sickness6']]['target_flg1'] : '0'; ?>
                                                                <span class="sel76 <?= $select7Flg === '1' ? " select7" : "" ?>"></span>
                                                            </li>                                                            <li>
                                                                <p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease_search_dialog.php?tgt_name=disease7&tgt_flg=sel77" data-set_name="modal_setting2" data-dialog_name="modal_disease_search">Search</p>
                                                                <input type="text" name="upAry[sickness7]" class="disease7 disease" value="<?= $dispData['sickness7'] ?>" style="width:260px;">
                                                                <?php $select7Flg = isset($sickList[$dispData['sickness7']]) ? $sickList[$dispData['sickness7']]['target_flg1'] : '0'; ?>
                                                                <span class="sel77 <?= $select7Flg === '1' ? " select7" : "" ?>"></span>
                                                            </li>
                                                            <li>
                                                                <p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease_search_dialog.php?tgt_name=disease8&tgt_flg=sel78" data-set_name="modal_setting2" data-dialog_name="modal_disease_search">Search</p>
                                                                <input type="text" name="upAry[sickness8]" class="disease8 disease" value="<?= $dispData['sickness8'] ?>" style="width:260px;">
                                                                <?php $select7Flg = isset($sickList[$dispData['sickness8']]) ? $sickList[$dispData['sickness8']]['target_flg1'] : '0'; ?>
                                                                <span class="sel78 <?= $select7Flg === '1' ? " select7" : "" ?>"></span>
                                                            </li>
                                                            <li>
                                                                <p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease_search_dialog.php?tgt_name=disease9&tgt_flg=sel79" data-set_name="modal_setting2" data-dialog_name="modal_disease_search">Search</p>
                                                                <input type="text" name="upAry[sickness9]" class="disease9 disease" value="<?= $dispData['sickness9'] ?>" style="width:260px;">
                                                                <?php $select7Flg = isset($sickList[$dispData['sickness9']]) ? $sickList[$dispData['sickness9']]['target_flg1'] : '0'; ?>
                                                                <span class="sel79 <?= $select7Flg === '1' ? " select7" : "" ?>"></span>
                                                            </li>
                                                            <li>
                                                                <p class="modal_open n_search disease_search" data-url="/report/instruct/dialog/disease_search_dialog.php?tgt_name=disease10&tgt_flg=sel710" data-set_name="modal_setting2" data-dialog_name="modal_disease_search">Search</p>
                                                                <input type="text" name="upAry[sickness10]" class="disease10 disease" value="<?= $dispData['sickness10'] ?>" style="width:260px;">
                                                                <?php $select7Flg = isset($sickList[$dispData['sickness10']]) ? $sickList[$dispData['sickness10']]['target_flg1'] : '0'; ?>
                                                                <span class="sel710 <?= $select7Flg === '1' ? " select7" : "" ?>"></span>
                                                            </li>
                                                        </ol>
                                                    </div>
                                                    <div class="special">
                                                        <span class="label_t">重症心身障害児</span>
                                                        <div>
                                                            <?php $check = empty($dispData['seriously_child']) ? ' checked' : NULL; ?>
                                                            <input type="radio" name="upAry[seriously_child]" id="mode1" value=""<?= $check ?>><label for="mode1">非該当</span></label>
                                                            <?php foreach ($gnrList['別表8'] as $key => $val): ?>
                                                                <?php if ($val == '超重症児' || $val == '準超重症児'): ?>
                                                                    <?php $check = $dispData['seriously_child'] === $val ? ' checked' : NULL; ?>
                                                                    <input type="radio" name="upAry[seriously_child]" id="mode1" value="<?= $val ?>"<?= $check ?>><label for="mode1"><?= $val ?></span></label>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <!-- 別表8 start -->
                                                    <div class="special">
                                                        <p>
                                                            <?php $checked = !empty($dispData['attached8']) ? ' checked' : NULL; ?>
                                                            <input type="checkbox" name="upAry[attached8]" value="該当する" id="appendix8" <?= $checked ?>>
                                                            <label for="tbl8">別表8　特掲診療料の施設基準等に該当する</label>
                                                        </p>
                                                    </div>
                                                    <div class="special dtl8Disp" style="margin-left:20px;">
                                                        <ul>
                                                            <?php foreach ($gnrList['別表8'] as $key => $val): ?>
                                                                <?php if ($val != '超重症児' && $val != '準超重症児'): ?>
                                                                    <?php $check = mb_strpos($dispData['attached8_detail'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                                    <li><input type="checkbox" name="upDummy[attached8_detail][]" class="appendix8Dtl" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label></li>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    <!-- 別表8 end -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ダイアログ流し込みエリア -->
                                    <div class="modal_setting"></div>
                                    <div class="modal_setting2"></div>
                                    <div class="nurse_record n_stations">
                                        <div class="station">
                                            <div class="tit tit_toggle">他の訪問看護ステーションの指示1</div>
                                            <div class="box_wrap child_toggle">
                                                <div class="name">
                                                    <span class="label_t">名称</span>
                                                    <input type="text" name="upAry[other_station1]" class="" value="<?= $dispData['other_station1'] ?>">
                                                </div>
                                                <div class="location">
                                                    <span class="label_t">所在地</span>
                                                    <input type="text" name="upAry[other_station1_address]" class="" value="<?= $dispData['other_station1_address'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="station">
                                            <div class="tit tit_toggle">他の訪問看護ステーションの指示2</div>
                                            <div class="box_wrap child_toggle">
                                                <div class="name">
                                                    <span class="label_t">名称</span>
                                                    <input type="text" name="upAry[other_station2]" class="" value="<?= $dispData['other_station2'] ?>">
                                                </div>
                                                <div class="location">
                                                    <span class="label_t">所在地</span>
                                                    <input type="text" name="upAry[other_station2_address]" class="" value="<?= $dispData['other_station2_address'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nurse_record record4 phys_inst">
                                        <div class="tit tit_toggle">指示書を交付した主治医</div>
                                        <div class="box_wrap child_toggle">
                                            <div class="copy_btn set_user_edit">基本情報に反映する</div>
                                            <div class="box-l">
                                                <div class="institution">
                                                    <span class="label_t">医療機関名称<small class="inst">文字数制限なし</small></span>
                                                    <input type="text" name="upAry[hospital]" class="hospital" value="<?= $dispData['hospital'] ?>">
                                                </div>
                                                <div class="receipt">
                                                    <span class="label_t">レセプト出力用名称<small class="req">*</small><small class="inst">最大16文字まで</small></span>
                                                    <input type="text" name="upAry[hospital_rece]" class="hospital_rece" value="<?= $dispData['hospital_rece'] ?>">
                                                    <span class="label_t">主治医<small class="req">*</small></span>
                                                    <input type="text" name="upAry[doctor]" class="w20 doctor" value="<?= $dispData['doctor'] ?>">
                                                </div>
                                            </div>
                                            <div class="box-r">
                                                <div class="location">
                                                    <span class="label_t">所在地</span>
                                                    <input type="text" name="upAry[address1]" class="address1" value="<?= $dispData['address1'] ?>">
                                                </div>
                                                <div class="number">
                                                    <p>
                                                        <span class="label_t">電話番号①</span>
                                                        <input type="tel" name="upAry[tel1]" class="tel1" value="<?= $dispData['tel1'] ?>">
                                                    </p>
                                                    <p>
                                                        <span class="label_t">電話番号②</span>
                                                        <input type="tel" name="upAry[tel2]" class="tel2" value="<?= $dispData['tel2'] ?>">
                                                    </p>
                                                    <p>
                                                        <span class="label_t">FAX</span>
                                                        <input type="tel" name="upAry[fax]" class="fax" value="<?= $dispData['fax'] ?>">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--作成状態-->
                                    <div class="nurse_record record8">
                                        <span class="label_t">作成状態</span>
                                        <?php $check = $dispData['status'] === '完成' ? ' checked' : NULL; ?>
                                        <input type="radio" name="upAry[status]" value="完成"<?= $check ?>>
                                        <label for="完成">完成</label>
                                        <?php $check = $dispData['status'] !== '完成' ? ' checked' : NULL; ?>
                                        <input type="radio" name="upAry[status]" value="作成中"<?= $check ?>>
                                        <label for="作成中">作成中</label>
                                    </div>
                                    <div class="nurse_record record9">
                                        <div class="i_register">
                                            <span class="label_t">初回登録:</span>
                                            <span class=""><?= $dispData['create_date'] ?></span>
                                            <span class="label_t"><?= $dispData['create_name'] ?></span>
                                        </div>
                                        <div class="l_update">
                                            <span class="label_t">最終更新:</span>
                                            <span class=""><?= $dispData['update_date'] ?></span>
                                            <span class="label_t"><?= $dispData['update_name'] ?></span>
                                        </div>
                                    </div>
                                    <!--ダイアログ呼出し-->
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff.php'); ?>
                                </div>

                                <div class="msg_box msg_duplicated cancel_act">
                                    <div class="msg_box-tit">複製</div>
                                    <div class="msg_box-cont">複製が完了しました</div>
                                    <div class="msg_box-btn">
                                        <span class="msg_box-cancel cancel">キャンセル</span>
                                        <span class="msg_box-dlt">OK</span>
                                    </div>
                                </div>

                            </div></div>
                        <!--/// CONTENT_END ///-->
                        <div class="fixed_navi">
                            <div class="box">
                                <div class="btn back pc"><button type="submit" name="btnReturn" value="true">指示書一覧にもどる</button></div>
                                <div class="controls">
                                    <button type="submit" class="btn save" name="btnEntry" value="true">保存
                                </div>
                            </div>
                        </div>
                    </form>
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
       	<script>
            const ifile = document.querySelector('#ifile');
            const imageTypes = ["image/jpeg", "image/png"];
            const pdf_view = document.querySelector('#pdf_view');
            const img_view = document.querySelector('#img_view');
            ifile.addEventListener('change', preView);
            function preView() {
                const files = ifile.files;
                if (files.length) {
                    file = files[0];
                    if (imageTypes.includes(file.type)) {
                        console.log('img set');
                        pdf_view.style.display = 'none';
                        img_view.src = URL.createObjectURL(file);
                        img_view.style.display = 'block';
                    } else {
                        console.log('pdf set');
                        img_view.style.display = 'none';
                        pdf_view.src = URL.createObjectURL(file);
                        pdf_view.style.display = 'block';
                    }
                }
            }

            function fileClear() {
                var obj = document.getElementById("ifile");
                obj.value = "";
                pdf_view.src = "";
                img_view.src = "";
                pdf_view.value = "";
                img_view.value = "";
            }

            // 検索ボタンクリック
            $(".disease_search").on("click", function () {

            });

            // 利用者基本情報に反映する
            $(function () {
                $(".set_user_edit").on("click", function () {

                    var userId = $(".tgt-unique_id").val();
                    var otherId = $(".tgt-usr_id").val();
                    var userName = $(".tgt-usr_name").val();

                    if (!userId || !otherId || !userName) {
                        alert("利用者が選択されていません。利用者を選択して下さい。");
                        return false;
                    }
                    var result = window.confirm('利用者基本情報に反映してもよろしいですか？');
                    if (!result) {
                        // いいえ押下時、Submit阻止
                        return false;
                    }

                    var hospital = $(".hospital").val();
                    var hospitalRece = $(".hospital_rece").val();
                    var doctor = $(".doctor").val();
                    var address1 = $(".address1").val();
                    var tel1 = $(".tel1").val();
                    var tel2 = $(".tel2").val();
                    var fax = $(".fax").val();

                    $.ajax({
                        async: false,
                        type: "POST",
                        url: "./ajax/updateUserEdit.php",
                        dataType: "text",
                        data: {
                            "user_id": userId,
                            "hospital": hospital,
                            "hospital_rece": hospitalRece,
                            "doctor": doctor,
                            "address1": address1,
                            "tel1": tel1,
                            "tel2": tel2,
                            "fax": fax
                        }
                    }).done(function (data) {
                        console.log("結果" + data);
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log("ajax通信に失敗しました");
                        console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                        console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                        console.log("errorThrown    : " + errorThrown.message); // 例外情報
                        console.log("URL            : " + url);
                    });
                });
            });

        </script>
    </body>
</html>