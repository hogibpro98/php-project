<?php require_once(dirname(__FILE__) . "/php/kantaki.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>看多機記録</title>
        <?php foreach ($otherWindowURL as $otherURL): ?>
            <script>
                $(function () {
                    window.open('<?= $otherURL ?>', '_blank');
                });
            </script>
        <?php endforeach; ?>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <form action="" method="post" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">
                        <h2 class="tit_sm">看多機記録</h2>
                        <div id="patient" class="sm"><?= $dispData['user_name'] ?></div>
                        <div id="subpage"><div id="kantaki" class="nursing">
                                <!-- ダイアログ流し込みエリア -->
                                <div class="modal_setting"></div>
                                <div class="wrap">
                                    <ul class="user-tab">
                                        <li><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
                                        <li class="active"><a href="/report/list/?user=<?= $userId ?>">各種帳票</a></li>
                                        <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
                                    </ul>
                                    <div class="user-details nurse_record">
                                        <div class="sm name">
                                            <span class="label_t">利用者ID</span>
                                            <p class="n_search user_search">Search</p>
                                            <input type="text" name="upDummy[other_id]" class="tgt-usr_id n_num" value="<?= $dispData['other_id'] ?>" maxlength="7" pattern="^[0-9]+$">
                                            <input type="hidden" id="user_id" name="upAry[user_id]" class="n_num tgt-unique_id" value="<?= $userId ?>">
                                            <div class="box_username">
                                            <span class="label_t pc">利用者氏名</span>
                                            <input type="text" name="upDummy[user_name]" value="<?= $dispData['user_name'] ?>" class="tgt-usr_name n_name bg-gray2" readonly>
                                            </div>
                                        </div>
                                        <div class="tit tit_toggle sm">利用者/サービス提供日/担当スタッフ</div>
                                        <div class="box child_toggle">
                                            <div class="box-l">
                                                <div class="name">
                                                    <span class="label_t">利用者ID</span>
                                                    <p class="n_search user_search">Search</p>
                                                    <input type="text" name="upDummy[other_id]" class="tgt-usr_id n_num" value="<?= $dispData['other_id'] ?>" maxlength="7" pattern="^[0-9]+$">
                                                    <input type="hidden" name="upAry[user_id]" class="n_num tgt-unique_id" value="<?= $userId ?>">
                                                    
                                                    <span class="label_t label_username">利用者氏名</span>
                                                    <input type="text" name="upDummy[user_name]" value="<?= $dispData['user_name'] ?>" class="tgt-usr_name n_name bg-gray2" readonly>
                                                    
                                                </div>
                                                <div class="service_date">
                                                    <span class="label_t">サービス<br class="sm">提供日</span>
                                                    <input type="date" id="service_day" name="upAry[service_day]" class="" value="<?= $dispData['service_day'] ?>">
                                                    <span class="time">
                                                        <select name="upTime[start_time_h]" style="width:60px;">
                                                            <?php foreach ($selHour as $val) : ?>
                                                                <?php $selected = strpos($dispData['start_time'], $val . ":") !== false ? ' selected' : ""; ?>
                                                                <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <small>：</small>
                                                        <select name="upTime[start_time_m]" style="width:60px;">
                                                            <?php foreach ($selMinutes as $val) : ?>
                                                                <?php $selected = strpos($dispData['start_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                                                                <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <small>～</small>
                                                        <select name="upTime[end_time_h]" class="select_time" style="width:60px;">
                                                            <?php foreach ($selHour as $val) : ?>
                                                                <?php $selected = strpos($dispData['end_time'], $val . ":") !== false ? ' selected' : ""; ?>
                                                                <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <small>：</small>
                                                        <select name="upTime[end_time_m]" class="select_time" style="width:60px;">
                                                            <?php foreach ($selMinutes as $val) : ?>
                                                                <?php $selected = strpos($dispData['end_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                                                                <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </span>
                                                    <div class="mark_important">
                                                    <?php $checked = !empty($dispData['important']) ? ' checked' : NULL; ?>
                                                    <input type="checkbox" name="upAry[important]" value="重要" id="add1_1" <?= $checked ?> class="">
                                                    <span class="label_t"><label for="add1_1">重要</label></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="staff_inch">
                                                <span class="label_t">担当<br class="sm">スタッフ</span>
                                                <div class="staff_wrap">
                                                    <?php $i = 0 ?>
                                                    <?php foreach ($dispStf as $tgtId => $rec): ?>
                                                        <?php $i++ ?>
                                                        <div class="sline service_inch1" style="display:flex;">
                                                            <p class="n_search multi_modal_open" 
                                                               data-url="/report/kantaki/dialog/staff_search_dialog.php?tgt_set_id=targetSetId<?= $i ?>&tgt_set_other_id=targetSetOtherId<?= $i ?>&tgt_set_name=targetSetName<?= $i ?>" 
                                                               data-dialog_name="staff_modal">Search</p>
                                                            <input type="hidden" name="upStf1[unique_id][<?= $i ?>]" value="<?= $rec['unique_id'] ?>">
                                                            <input type="text" class="targetSetOtherId<?= $i ?>" name="" value="<?= $rec['other_id'] ?>" style="margin-left:0px;width:90px;">
                                                            <input type="hidden" class="targetSetId<?= $i ?>" name="upStf1[staff_id][<?= $i ?>]" value="<?= $rec['staff_id'] ?>">
                                                            <input type="text" class="n_name targetSetName<?= $i ?> bg-gray2" name="upStf1[name][<?= $i ?>]" value="<?= $rec['name'] ?>" style="margin-left:0px;width:130px;" readonly="">
                                                            <div class="care_worker" style="margin-left:2px;">
                                                                <select name="upStf1[license][<?= $i ?>]">
                                                                    <option selected hidden disabled>選択してください</option>
                                                                    <?php foreach ($gnrList['担当スタッフ_職種'] as $key => $val): ?>
                                                                        <?php $select = $rec['license'] == $val ? ' selected' : NULL; ?>
                                                                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <?php if ($i > 1): ?>
                                                                <span class="btn trash2 delStaff"></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <span class="btn add addStaff" style="height:35.6px;margin-top:-2px;">追加</span>
                                            </div>
                                            <div class="btn_w pc">
                                                <button type="submit" class="btn print" name="btnPrint" value="<?= $dispData['unique_id'] ?>"  style="height:35.6px;" <?= !empty($dispData['user_id']) ? ' desabled' : '' ?>>印刷</button>
                                                <button type="submit" class="btn-del" name="btnDel" value="<?= $dispData['unique_id'] ?>"  style="height:35.6px;">削除</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nurse_record record2">
                                        <div class="box_wrap">
                                            <div class="box-l">
                                                <div class="box1">
                                                    <div class="tit tit_toggle">サービスの種類</div>
                                                    <ul class="child_toggle">
                                                        <?php foreach ($gnrList['サービスの種類'] as $key => $val): ?>
                                                            <li>
                                                                <?php $check = strpos($dispData['service_kind'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                                <input type="checkbox" name="upDummy[service_kind][]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                                <div class="box2">
                                                    <div class="tit tit_toggle sm">身体図</div>
                                                    
                                                    <div class="child_toggle photo_body">
                                                    <input type="hidden" name="unique_id" value="<?= $dispData['unique_id'] ?>">
                                                    <button type='submit' name="btnImageEdit" value="1">編集</button>
                                                        <?php $life_image = empty($dispData['life_image']) ? '/common/image/sub/body_img.png' : $dispData['life_image']; ?>
                                                        <div class="hbody"><img src="<?= $life_image ?>" alt="人体"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-r">
                                                <div class="tit tit_toggle">実施内容</div>
                                                <div class="child_toggle">
                                                    <div class="physical_box con_box">
                                                        <div class="tit-i tit_toggle">身体介助</div>
                                                        <div class="box-i child_toggle">
                                                            <?php $i = 0 ?>
                                                            <?php $j = ceil(count($gnrList['身体介助']) / 3) ?>
                                                            <?php foreach ($gnrList['身体介助'] as $key => $val): ?>
                                                                <?php $i++ ?>
                                                                <?php if (($i % $j) == 1): ?>
                                                                    <ul>
                                                                    <?php endif; ?>
                                                                    <li>
                                                                        <?php $check = strpos($dispData['body_assist'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                                        <input type="checkbox" name="upDummy[body_assist][]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                                    </li>
                                                                    <?php if (($i % $j == 0) || $i == count($gnrList['身体介助'])): ?>
                                                                    </ul>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <div class="livelihood_box con_box">
                                                        <div class="tit-i tit_toggle">生活援助</div>
                                                        <div class="box-i child_toggle">
                                                            <?php $i = 0 ?>
                                                            <?php $j = ceil(count($gnrList['生活援助']) / 2) ?>
                                                            <?php foreach ($gnrList['生活援助'] as $key => $val): ?>
                                                                <?php $i++ ?>
                                                                <?php if (($i % $j) == 1): ?>
                                                                    <ul>
                                                                    <?php endif; ?>
                                                                    <li>
                                                                        <?php $check = strpos($dispData['life_support'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                                        <input type="checkbox" name="upDummy[life_support][]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                                    </li>
                                                                    <?php if (($i % $j == 0) || $i == count($gnrList['生活援助'])): ?>
                                                                    </ul>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            <ul class="receipt_box">
                                                                <li><span class="label_t">レシート</span>

                                                                    <?php if (empty($dispData['receipt_img'])) : ?>
                                                                        <div class="btn upload up_view">
                                                                            <img src="/common/image/icon_upload.svg" alt="View Receipt">
                                                                        </div>
                                                                    <?php else : ?>
                                                                        <div class="btn view up_view">
                                                                            <img src="/common/image/icon_photo.svg" alt="View Receipt">
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="view_box">
                                                                        <div class="view_box-i">
                                                                            <div class="close close_part">✕<span>閉じる</span></div>
                                                                            <div class="view_btn">
                                                                                <label for="receipt_uploader"><span class="btn upload"> レシート添付</span></label>
                                                                                <!-- ファイルアップロード -->
                                                                                <input type="file" name="receipt_img" id="receipt_uploader" accept=".jpg,.png,.pdf,.xlsx,.xls,.xlsm,.csv,.doc,.docx,/docm" onchange="previewImage(this);" style="display:none;">
                                                                                <div class="btn trash">
                                                                                    <button type="submit" name="btnDelReceiptImg" value="<?= $dispData['unique_id'] ?>">
                                                                                        <img src="/common/image/icon_trash.svg" alt="削除">
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="view-cont" id="preview">
                                                                                <a href="<?= $dispData['receipt_img'] ?>" id="big_image" data-lightbox="group">
                                                                                    <img id="img_view" name="upAry[receipt_img]" src="<?= $dispData['receipt_img'] ?>" value="<?= $dispData['receipt_img'] ?>" style="width:50vw;">
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--<div class="btn trash trash_act"><img src="/common/image/icon_trash.svg" alt="削除"></div>-->
                                                                    <div class="msg_box">
                                                                        <div class="msg_box-tit">画像削除</div>
                                                                        <div class="msg_box-cont">削除してよろしいですか？</div>
                                                                        <div class="msg_box-btn">
                                                                            <span class="msg_box-cancel">キャンセル</span>
                                                                            <span class="msg_box-dlt"><button type="submit" name="" value="">削除する</button></span>
                                                                            <span class="msg_box-close">閉じる</span>
                                                                        </div>							
                                                                    </div> 
                                                                </li>
                                                                <li>
                                                                    <span class="label_t">預り金</span>
                                                                    <input type="text" name="upAry[deposit]" class="" value="<?= $dispData['deposit'] ?>">
                                                                    <span class="label_yen">円</span>
                                                                </li>
                                                                <li>
                                                                    <span class="label_t">支払金</span>
                                                                    <input type="text" name="upAry[payment]" class="" value="<?= $dispData['payment'] ?>">
                                                                    <span class="label_yen">円</span>
                                                                </li>
                                                                <li>
                                                                    <span class="label_t">お釣り</span>
                                                                    <input type="text" name="upAry[repayment]" class="" value="<?= $dispData['repayment'] ?>">
                                                                    <span class="label_yen">円</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>	
                                                    <div class="medical_box con_box">
                                                        <div class="tit-i tit_toggle">医療処置</div>
                                                        <div class="box-i child_toggle">
                                                            <?php $i = 0 ?>
                                                            <?php $j = ceil(count($gnrList['医療処置']) / 3) ?>
                                                            <?php foreach ($gnrList['医療処置'] as $key => $val): ?>
                                                                <?php $i++ ?>
                                                                <?php if (($i % $j) == 1): ?>
                                                                    <ul>
                                                                    <?php endif; ?>
                                                                    <li>
                                                                        <?php $check = strpos($dispData['medical_procedures'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                                        <input type="checkbox" name="upDummy[medical_procedures][]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                                    </li>
                                                                    <?php if (($i % $j == 0) || $i == count($gnrList['医療処置'])): ?>
                                                                    </ul>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <div class="rehab_box con_box">
                                                        <div class="tit-i tit_toggle">リハビリ</div>
                                                        <div class="box-i child_toggle">
                                                            <?php $i = 0 ?>
                                                            <?php $j = ceil(count($gnrList['リハビリ']) / 4) ?>
                                                            <?php foreach ($gnrList['リハビリ'] as $key => $val): ?>
                                                                <?php $i++ ?>
                                                                <?php if (($i % $j) == 1): ?>
                                                                    <ul>
                                                                    <?php endif; ?>
                                                                    <li>
                                                                        <?php $check = strpos($dispData['rehabilitation'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                                        <input type="checkbox" name="upDummy[rehabilitation][]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                                    </li>
                                                                    <?php if (($i % $j == 0) || $i == count($gnrList['リハビリ'])): ?>
                                                                    </ul>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <div class="tbox naiyo_box con_box">
                                                        <div class="tit-i tit_toggle">処置内容</div>
                                                        <div class="box-i child_toggle">
                                                            <span>最大全角300文字まで</span>
                                                            <textarea name="upAry[measures_contents]" value="<?= $dispData['measures_contents'] ?>" class="" maxlength="300"><?= $dispData['measures_contents'] ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="tbox other_box con_box">
                                                        <div class="tit-i tit_toggle">その他</div>
                                                        <div class="box-i child_toggle">
                                                            <span>最大全角100文字まで</span>
                                                            <textarea name="upAry[other]" value="<?= $dispData['other'] ?>" class="" maxlength="100"><?= $dispData['measures_contents'] ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="nurse_record record5">
                                        <div class="tit tit_toggle sm">バイタル・食事・排泄等</div>
                                        <div class="box-i child_toggle">
                                            <div class="vital_box">
                                                <div class="tit-i tit_toggle">バイタル</div>
                                                <div class="table_box child_toggle">
                                                    <table>
                                                        <tr>
                                                            <th>時刻</th>
                                                            <th>体温</th>
                                                            <th>脈拍</th>
                                                            <th>血圧</th>
                                                            <th>SpO2</th>
                                                            <th></th>
                                                        </tr>
                                                        <?php foreach ($dispVtl as $key => $val): ?>
                                                            <tr>
                                                                <td>
                                                                    <span class="label_t"><label>時刻</label></span>
                                                                    <input type="time" name="upVtl1[counting_time][]" class="vital_time" value="<?= $val['counting_time'] ?>">
                                                                    <input type="hidden" name="upVtl1[unique_id][]" value="<?= $val['unique_id'] ?>">
                                                                </td>
                                                                <td>
                                                                    <span class="label_t"><label>体温</label></span>
                                                                    <input type="text" name="upVtl1[temperature][]" class="vital_temp" value="<?= $val['temperature'] ?>">
                                                                    <span class="unit_m">℃</span>
                                                                </td>
                                                                <td>
                                                                    <span class="label_t"><label>脈拍</label></span>
                                                                    <input type="text" name="upVtl1[pulse][]" class="vital_pulse" value="<?= $val['pulse'] ?>">
                                                                    <span class="unit_m">／分</span>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <span class="label_t"><label>血圧</label></span>
                                                                        <input type="text" name="upVtl1[blood_pressure1][]" class="vital_bp1" value="<?= $val['blood_pressure1'] ?>">
                                                                        <span>／</span>
                                                                        <input type="text" name="upVtl1[blood_pressure2][]" class="vital_bp2" value="<?= $val['blood_pressure2'] ?>">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span class="label_t"><label>SpO2</label></span>
                                                                    <input type="text" name="upVtl1[spo2][]" class="vital_sp" value="<?= $val['spo2'] ?>">
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr>
                                                            <td>
                                                                <span class="label_t"><label>時刻</label></span>
                                                                <input type="time" name="upVtl1[counting_time][]" class="vital_time" value="">
                                                            </td>
                                                            <td>
                                                                <span class="label_t"><label>体温</label></span>
                                                                <input type="text" name="upVtl1[temperature][]" class="vital_temp" value="">
                                                                <span class="unit_m">℃</span>
                                                            </td>
                                                            <td>
                                                                <span class="label_t"><label>脈拍</label></span>
                                                                <input type="text" name="upVtl1[pulse][]" class="vital_pulse" value="">
                                                                <span class="unit_m">／分</span>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <span class="label_t"><label>血圧</label></span>
                                                                    <input type="text" name="upVtl1[blood_pressure1][]" class="vital_bp1" value="">
                                                                    <span>／</span>
                                                                    <input type="text" name="upVtl1[blood_pressure2][]" class="vital_bp2" value="">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="label_t"><label>SpO2</label></span>
                                                                <input type="text" name="upVtl1[spo2][]" class="vital_sp" value="">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                    <span class="btn add addVital">追加</span>
                                                </div>
                                            </div>
                                            <div class="intake_box">
                                                <div class="tit-i tit_toggle">水分摂取</div>	
                                                <div class="table_box child_toggle">
                                                    <table>
                                                        <tr>
                                                            <th>時刻</th>
                                                            <th></th>
                                                            <th colspan="2">経口・その他</th>
                                                        </tr>
                                                        <?php foreach ($dispWtr as $key => $val): ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="time" name="upWtr1[counting_time][]" class="intake_time" value="<?= $val['counting_time'] ?>">
                                                                    <input type="hidden" name="upWtr1[unique_id][]" value="<?= $val['unique_id'] ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="upWtr1[amount][]" class="intake_ml" value="<?= $val['amount'] ?>">
                                                                    <span class="unit_m">ml</span>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="upWtr1[method][]" class="intake_other" value="<?= $val['method'] ?>">
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr>
                                                            <td>
                                                                <input type="time" name="upWtr1[counting_time][]" class="intake_time" value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="upWtr1[amount][]" class="intake_ml" value="">
                                                                <span class="unit_m">ml</span>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="upWtr1[method][]" class="intake_other" value="">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <th>合計</th>
                                                            <td>
                                                                <input type="text" name="upDummy[wtr_sum]" class="total_intake" value="<?= $wtrSum ?>">
                                                                <span class="unit_m">ml/日</span>
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </table>	
                                                    <span class="btn add addIntake">追加</span>
                                                </div>	
                                            </div>
                                            <div class="bmi_box">
                                                <div class="tit-i tit_toggle">身長/体重</div>
                                                <div class="table_box child_toggle">
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <td>
                                                                    <div>
                                                                        <span class="label_t">身長</span>
                                                                        <input type="text" name="upAry[body_height]" class="bmi_hwght now_hght" value="<?= $dispData['body_height'] ?>">
                                                                        <span class="unit_m">cm</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="label_t">体重</span>
                                                                        <input type="text" name="upAry[body_weight]" id="now_wght" class="bmi_hwght now_wght f-keyVal" value="<?= $dispData['body_weight'] ?>">
                                                                        <span class="unit_m">kg</span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span class="label_t">BMI</span>
                                                                    <input type="text" name="upAry[bmi]" class="bmi_total" value="<?= $dispData['bmi'] ?>" readonly>
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th colspan="2">過去体重履歴<span>今回との差分</span></th>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><span class="label_t">前回体重</span>
                                                                    <div>
                                                                        <input type="text" name="upDummy[last_wght]" id="last_wght" class="last_wght bg-gray2" value="<?= $lastWght1 ?>" readonly>
                                                                        <span class="unit_m">kg</span>
                                                                    </div>
                                                                    <div class="">
                                                                        <input type="text" name="upDummy[last_diff_wght]" class="diff-wght bg-gray2" value="<?= $diffWght1 ?>" readonly>
                                                                        <span class="unit_m">kg</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><span class="label_t">前月の<br/>最終体重</span>
                                                                    <div>
                                                                        <input type="text" name="upDummy[last_wght2]" id="last_wght2" class="last_wght2 bg-gray2" value="<?= $lastWght2 ?>" readonly>
                                                                        <span class="unit_m">kg</span>
                                                                    </div>
                                                                    <div>
                                                                        <input type="text" name="upDummy[last_diff_wght2]" class="diff-wght2 bg-gray2" value="<?= $diffWght2 ?>" readonly>
                                                                        <span class="unit_m">kg</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><span class="label_t">前々月の<br/>最終体重</span>
                                                                    <div>
                                                                        <input type="text" name="upDummy[last_wght3]" id="last_wght3" class="last_wght3 bg-gray2" value="<?= $lastWght3 ?>" readonly>
                                                                        <span class="unit_m">kg</span>
                                                                    </div>
                                                                    <div>
                                                                        <input type="text" name="upDummy[last_diff_wght3]" class="diff-wght3 bg-gray2" value="<?= $diffWght3 ?>" readonly>
                                                                        <span class="unit_m">kg</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="excretion_box">
                                                <div class="tit-i tit_toggle">排泄</div>
                                                <div class="table_box child_toggle">
                                                    <table>
                                                        <tr>
                                                            <th>時刻</th>
                                                            <th>排尿</th>
                                                            <th>排便</th>
                                                            <th></th>
                                                        </tr>
                                                        <?php foreach ($dispExc as $key => $val): ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="time" name="upExc1[counting_time][<?= $key ?>]" class="" value="<?= $val['counting_time'] ?>">
                                                                    <input type="hidden" name="upExc1[unique_id][<?= $key ?>]" value="<?= $val['unique_id'] ?>">
                                                                </td>
                                                                <td>
                                                                    <?php $checked = $val['urination'] === "有" ? ' checked' : NULL; ?>
                                                                    <input type="checkbox" name="upExc1[urination][<?= $key ?>]" id="" value="有" <?= $checked ?>>
                                                                    <input type="text" name="upExc1[urination_quantity][<?= $key ?>]" class="exc1" value="<?= $val['urination_quantity'] ?>">
                                                                    <span class="unit_m">ml</span>
                                                                </td>
                                                                <td>
                                                                    <?php $checked = $val['evacuation'] === "有" ? ' checked' : NULL; ?>
                                                                    <input type="checkbox" name="upExc1[evacuation][<?= $key ?>]" id="" value="有" <?= $checked ?>>
                                                                    <input type="text" name="upExc1[evacuation_memo][<?= $key ?>]" class="" value="<?= $val['evacuation_memo'] ?>">
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        <?php endforeach; ?>
<!--                                                        <tr>
                                                    <td>
                                                        <input type="time" name="upExc1[counting_time][]" class="" value="">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="upExc1[urination][]" id="" value="">
                                                        <input type="text" name="upExc1[urination_quantity][]" class="exc1" value="">
                                                        <span class="unit_m">ml</span>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="upExc1[evacuation][]" id="" value="">
                                                        <input type="text" name="upExc1[evacuation_memo][]" class="" value="">
                                                    </td>
                                                    <td></td>
                                                </tr>-->
                                                        <tr>
                                                            <th><span>排尿量合計</span></th>
                                                            <td>
                                                                <input type="text" name="upDummy[exc_sum]" class="excre_total bg-gray" value="<?= $dispData['exc_sum'] ?>" readonly>
                                                                <span class="unit_m exc_total">ml/日</span>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                    <span class="btn add addExc">追加</span>
                                                </div>
                                            </div>
                                            <div class="goods_use_box">
                                                <div class="tit-i tit_toggle">物品使用</div>
                                                <div class="table_box child_toggle">
                                                    <table>
                                                        <tr>
                                                            <th>項目</th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        <?php foreach ($dispGds as $key => $val): ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" name="upGds1[goods_name][]" class="" value="<?= $val['goods_name'] ?>">
                                                                    <input type="hidden" name="upGds1[unique_id][]" class="" value="<?= $val['unique_id'] ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="upGds1[quantity][]" class="" value="<?= $val['quantity'] ?>">
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr>
                                                            <td>
                                                                <input type="text" name="upGds1[goods_name][]" class="" value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="upGds1[quantity][]" class="" value="">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                    <span class="btn add addUse">追加</span>
                                                </div>
                                            </div>
                                            <div class="meal_box">
                                                <div class="tit-i tit_toggle">食事</div>
                                                <div class="table_box child_toggle">
                                                    <table>
                                                        <tr>
                                                            <th class="mf_tit">朝食</th>
                                                            <td class="meal_main">
                                                                <label for="breakfast_main">主</label>
                                                                <input type="text" name="upAry[breakfast_main]" class="" value="<?= $dispData['breakfast_main'] ?>">
                                                                <span class="unit_m">/10</span>
                                                            </td>
                                                            <td class="meal_add">
                                                                <label for="breakfast_add">副</label>
                                                                <input type="text" name="upAry[breakfast_side]" class="" value="<?= $dispData['breakfast_side'] ?>">
                                                                <span class="unit_m">/10</span>
                                                            </td>
                                                            <?php if (!empty($dispData['breakfast_img'])) : ?>
                                                                <td class="meal_btn">
                                                                    <div class="btn view">
                                                                        <img src="/common/image/icon_photo.svg" alt="View Meal">
                                                                    </div>
                                                                    <div class="photo_box-meal">
                                                                        <div class="meal_img">
                                                                            <img src="<?= $dispData['breakfast_img'] ?>" alt="Sample Meal">
                                                                        </div>
                                                                        <div class="m_control">
                                                                            <!-- アップロード -->
                                                                            <label for="meal_breakfast">
                                                                                <span class="btn upload">
                                                                                    <img src="/common/image/icon_upload.svg" alt="Upload Meal">
                                                                                </span>
                                                                            </label>
                                                                            <input type="file" name="breakfast_img" value="<?= $dispData['breakfast_img'] ?>" id="meal_breakfast">

                                                                            <!-- 削除 -->
                                                                            <button type="submit" name="btnDelBfImg" value="<?= $dispData['unique_id'] ?>">
                                                                                <span class="btn trash">
                                                                                    <img src="/common/image/icon_trash.svg" alt="Delete Meal">
                                                                                </span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            <?php else : ?>
                                                                <td>
                                                                    <label for="meal_breakfast_def">
                                                                        <div class="btn upload">
                                                                            <img src="/common/image/icon_upload.svg" alt="Upload Meal">
                                                                        </div>
                                                                    </label>
                                                                    <input type="file" name="breakfast_img" value="" id="meal_breakfast_def">
                                                                </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                        <tr>
                                                            <th class="mf_tit">昼食</th>
                                                            <td class="meal_main">
                                                                <label for="lunch_main">主</label>
                                                                <input type="text" name="upAry[lunch_main]" class="" value="<?= $dispData['lunch_main'] ?>">
                                                                <span class="unit_m">/10</span>
                                                            </td>
                                                            <td class="meal_add">
                                                                <label for="lunch_add">副</label>
                                                                <input type="text" name="upAry[lunch_side]" class="" value="<?= $dispData['lunch_side'] ?>">
                                                                <span class="unit_m">/10</span>
                                                            </td>
                                                            <!-- ★画像未登録のパターン -->
                                                            <?php if (!empty($dispData['lunch_img'])) : ?>
                                                                <td class="meal_btn">
                                                                    <div class="btn view">
                                                                        <img src="/common/image/icon_photo.svg" alt="View Meal">
                                                                    </div>
                                                                    <div class="photo_box-meal">
                                                                        <div class="meal_img">
                                                                            <img src="<?= $dispData['lunch_img'] ?>" alt="Meal">
                                                                        </div>
                                                                        <div class="m_control">
                                                                            <!-- アップロード -->
                                                                            <label for="meal_lunch">
                                                                                <span class="btn upload">
                                                                                    <img src="/common/image/icon_upload.svg" alt="Upload Meal">
                                                                                </span>
                                                                            </label>
                                                                            <input type="file" name="lunch_img" value="<?= $dispData['lunch_img'] ?>" id="meal_lunch">

                                                                            <!-- 削除 -->
                                                                            <button type="submit" name="btnDellcImg" value="<?= $dispData['unique_id'] ?>">
                                                                                <span class="btn trash">
                                                                                    <img src="/common/image/icon_trash.svg" alt="Delete Meal">
                                                                                </span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            <?php else : ?>
                                                                <td>
                                                                    <label for="meal_lunch_def">
                                                                        <div class="btn upload">
                                                                            <img src="/common/image/icon_upload.svg" alt="Upload Meal">
                                                                        </div>
                                                                    </label>
                                                                    <input type="file" name="lunch_img" value="" id="meal_lunch_def">
                                                                </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                        <tr>
                                                            <th class="mf_tit">おやつ</th>
                                                            <td class="meal_snack">
                                                                <label for="meal_snack"></label>
                                                                <input type="text" name="upAry[bite]" class="" value="<?= $dispData['bite'] ?>">
                                                                <span class="unit_m">/10</span>
                                                            </td>
                                                            <td></td>
                                                            <?php if (!empty($dispData['bite_img'])) : ?>
                                                                <td class="meal_btn">
                                                                    <div class="btn view">
                                                                        <img src="/common/image/icon_photo.svg" alt="View Meal">
                                                                    </div>
                                                                    <div class="photo_box-meal">
                                                                        <div class="meal_img">
                                                                            <img src="<?= $dispData['bite_img'] ?>" alt="Sample Meal">
                                                                        </div>
                                                                        <div class="m_control">
                                                                            <!-- アップロード -->
                                                                            <label for="meal_bite">
                                                                                <span class="btn upload">
                                                                                    <img src="/common/image/icon_upload.svg" alt="Upload Meal">
                                                                                </span>
                                                                            </label>
                                                                            <input type="file" name="bite_img" value="<?= $dispData['bite_img'] ?>" id="meal_bite">

                                                                            <!-- 削除 -->
                                                                            <button type="submit" name="btnDelBtImg" value="<?= $dispData['unique_id'] ?>">
                                                                                <span class="btn trash">
                                                                                    <img src="/common/image/icon_trash.svg" alt="Delete Meal">
                                                                                </span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            <?php else : ?>
                                                                <td>
                                                                    <label for="meal_bite_def">
                                                                        <div class="btn upload">
                                                                            <img src="/common/image/icon_upload.svg" alt="Upload Meal">
                                                                        </div>
                                                                    </label>
                                                                    <input type="file" name="bite_img" value="" id="meal_bite_def">
                                                                </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                        <tr>
                                                            <th class="mf_tit">夕食</th>
                                                            <td class="meal_main">
                                                                <label for="dinner_main">主</label>
                                                                <input type="text" name="upAry[dinner_main]" class="" value="<?= $dispData['dinner_main'] ?>">
                                                                <span class="unit_m">/10</span>
                                                            </td>
                                                            <td class="dinner_add">
                                                                <label for="dinner_add">副</label>
                                                                <input type="text" name="upAry[dinner_side]" class="" value="<?= $dispData['dinner_side'] ?>">
                                                                <span class="unit_m">/10</span>
                                                            </td>
                                                            <?php if (!empty($dispData['dinner_img'])) : ?>
                                                                <td class="meal_btn">
                                                                    <div class="btn view">
                                                                        <img src="/common/image/icon_photo.svg" alt="View Meal">
                                                                    </div>
                                                                    <div class="photo_box-meal">
                                                                        <div class="meal_img">
                                                                            <img src="<?= $dispData['dinner_img'] ?>" alt="Sample Meal">
                                                                        </div>
                                                                        <div class="m_control">
                                                                            <!-- アップロード -->
                                                                            <label for="meal_dinner">
                                                                                <span class="btn upload">
                                                                                    <img src="/common/image/icon_upload.svg" alt="Upload Meal">
                                                                                </span>
                                                                            </label>
                                                                            <input type="file" name="dinner_img" value="<?= $dispData['dinner_img'] ?>" id="meal_dinner">

                                                                            <!-- 削除 -->
                                                                            <button type="submit" name="btnDelDnImg" value="<?= $dispData['unique_id'] ?>">
                                                                                <span class="btn trash">
                                                                                    <img src="/common/image/icon_trash.svg" alt="Delete Meal">
                                                                                </span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            <?php else : ?>
                                                                <td>
                                                                    <label for="meal_dinner_def">
                                                                        <div class="btn upload">
                                                                            <img src="/common/image/icon_upload.svg" alt="Upload Meal">
                                                                        </div>
                                                                    </label>
                                                                    <input type="file" name="dinner_img" value="" id="meal_dinner_def">
                                                                </td>
                                                            <?php endif; ?>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="fukuyaku_box">
                                                <div class="tit-i tit_toggle">服薬</div>
                                                <div class="table_box child_toggle">
                                                    <table>
                                                        <tr>
                                                            <th colspan="4"></th>
                                                        </tr>
                                                        <tr>
                                                            <td class="mf_tit"><span class="label_t"><label>朝食</label></span></td>
                                                            <td>
                                                                <span>
                                                                    <?php $checked = $dispData['drug_bb'] === "食前" ? ' checked' : NULL; ?>
                                                                    <label><input type="checkbox" name="upAry[drug_bb]" id="" value="食前" <?= $checked ?>>
                                                                    食前</label>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span>
                                                                    <?php $checked = $dispData['drug_ab'] === "食後" ? ' checked' : NULL; ?>
                                                                    <label><input type="checkbox" name="upAry[drug_ab]" id="" value="食後" <?= $checked ?>>
                                                                    食後</label>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="upAry[drug_name_bb]" class="" value="<?= $dispData['drug_name_bb'] ?>" placeholder="入力">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="mf_tit"><span class="label_t"><label>昼食</label></span></td>
                                                            <td>
                                                                <span>
                                                                    <?php $checked = $dispData['drug_bl'] === "食前" ? ' checked' : NULL; ?>
                                                                    <label><input type="checkbox" name="upAry[drug_bl]" id="" value="食前" <?= $checked ?>>
                                                                    食前</label>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span>
                                                                    <?php $checked = $dispData['drug_al'] === "食後" ? ' checked' : NULL; ?>
                                                                    <label><input type="checkbox" name="upAry[drug_al]" id="" value="食後" <?= $checked ?>>
                                                                    食後</label>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="upAry[drug_name_bl]" class="" value="<?= $dispData['drug_name_bl'] ?>" placeholder="入力">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="mf_tit"><span class="label_t"><label>夕食</label></span></td>
                                                            <td>
                                                                <span>
                                                                    <?php $checked = $dispData['drug_bd'] === "食前" ? ' checked' : NULL; ?>
                                                                    <label><input type="checkbox" name="upAry[drug_bd]" id="" value="食前" <?= $checked ?>>
                                                                    食前</label>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span>
                                                                    <?php $checked = $dispData['drug_ad'] === "食後" ? ' checked' : NULL; ?>
                                                                    <label><input type="checkbox" name="upAry[drug_ad]" id="" value="食後" <?= $checked ?>>
                                                                    食後</label>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="upAry[drug_name_bd]" class="" value="<?= $dispData['drug_name_bd'] ?>" placeholder="入力">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4">
                                                                <span class="label_t"><label for="shushinzen_med">就寝前・その他</label></span>
                                                                <input type="text" name="upAry[drug_name_ps]" class="" value="<?= $dispData['drug_name_ps'] ?>" placeholder="入力">
                                                            </td>
                                                        </tr>
                                                        <?php foreach ($dispDrg as $key => $val): ?>
                                                            <tr class="new_input">
                                                                <td colspan="2">
                                                                    <input type="text" name="upDrg1[timing][]" class="" value="<?= $val['timing'] ?>" placeholder="項目名">
                                                                    <input type="hidden" name="upDrg1[unique_id][]" class="" value="<?= $val['unique_id'] ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="upDrg1[medicine][]" class="" value="<?= $val['medicine'] ?>" placeholder="入力">
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr class="new_input">
                                                            <td colspan="2">
                                                                <input type="text" name="upDrg1[timing][]" class="" value="" placeholder="項目名">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="upDrg1[medicine][]" class="" value="" placeholder="入力">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                    <span class="btn add addFukuyaku">追加</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nurse_record patient_comment">
                                        <div class="comm_box">
                                            <div class="tit-i tit_toggle"><ご利用中の様子>(介護)</div>
                                            <div class="box-i child_toggle">
                                                <div class="txt_note">最大全角500文字まで</div>
                                                <textarea name="upAry[state_care]" value="<?= $dispData['state_care'] ?>" class="" maxlength="500"><?= $dispData['state_care'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="comm_box">
                                            <div class="tit-i tit_toggle"><ご利用中の様子>(看護)</div>
                                            <div class="box-i child_toggle">
                                                <div class="txt_note">最大全角500文字まで</div>
                                                <textarea name="upAry[state_nurse]" value="<?= $dispData['state_nurse'] ?>" class="" maxlength="500"><?= $dispData['state_nurse'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="comm_box">
                                            <div class="tit-i tit_toggle"><ご家族への連絡></div>
                                            <div class="box-i child_toggle">
                                                <div class="txt_note">最大全角500文字まで</div>
                                                <textarea name="upAry[family_contact]" value="<?= $dispData['family_contact'] ?>" class="" maxlength="500"><?= $dispData['family_contact'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="comm_box">
                                            <div class="tit-i tit_toggle"><職員への申し送り事項></div>
                                            <div class="box-i child_toggle">
                                                <div class="txt_note">最大全角500文字まで<span>※帳票には印字されませんが、経過記録には自動で転記されます</span></div>
                                                <textarea name="upAry[staff_message]" value="<?= $dispData['staff_message'] ?>" class="" maxlength="500"><?= $dispData['staff_message'] ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nursing_need-b">
                                        <div class="table_box">
                                            <dl>
                                                <dt class="tit_toggle">看護必要度（B項目）<span class="txt_note">※帳票には印字されません</span></dt>
                                                <dd class="child_toggle">
                                                    <table>
                                                        <tr class="need-b1">
                                                            <th>1.寝返り</th>
                                                            <td colspan="2">
                                                                <?php foreach ($gnrList['1.寝返り'] as $key => $val): ?>
                                                                    <?php $check = $dispData['nurse_needs1'] === $val ? ' checked' : NULL; ?>
                                                                    <p><label><input type="radio" name="upAry[nurse_needs1]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                                <?php endforeach; ?>
                                                            </td>
                                                            <?php $res1 = (empty($dispData['nurse_needs1']) || $dispData['nurse_needs1'] === 'できる') ? 0 : ($dispData['nurse_needs1'] === '何かにつかまればできる' ? 1 : 2); ?>
                                                            <td class="score"><div><?= $res1 ?></div></td>
                                                        </tr>
                                                        <tr class="need-b2">
                                                            <th>2.移乗</th>
                                                            <td>
                                                                <?php foreach ($gnrList['2.移乗'] as $key => $val): ?>
                                                                    <?php $check = $dispData['nurse_needs2'] === $val ? ' checked' : NULL; ?>
                                                                    <p><label><input type="radio" name="upAry[nurse_needs2]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                                <?php endforeach; ?>
                                                            </td>
                                                            <td class="assistance_list">
                                                                <span>介助の実施</span>
                                                                <?php $needs2Val = (empty($dispData['nurse_needs2']) || $dispData['nurse_needs2'] === '自立') ? '無' : '有'; ?>
                                                                <input type="text" class="needs2_flg bg-gray2" value="<?= $needs2Val ?>" style="width:45px;" readonly>
                                                            </td>
                                                            <?php $res2 = (empty($dispData['nurse_needs2']) || $dispData['nurse_needs2'] === '自立') ? 0 : ($dispData['nurse_needs2'] === '一部介助' ? 1 : 2); ?>
                                                            <td class="score"><div><?= $res2 ?></div></td>
                                                        </tr>
                                                        <tr class="need-b3">
                                                            <th>3.口腔清潔</th>
                                                            <td>
                                                                <?php foreach ($gnrList['3.口腔清潔'] as $key => $val): ?>
                                                                    <?php $check = $dispData['nurse_needs3'] === $val ? ' checked' : NULL; ?>
                                                                    <p><label><input type="radio" name="upAry[nurse_needs3]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                                <?php endforeach; ?>
                                                            </td>
                                                            <td class="assistance_list">
                                                                <span>介助の実施</span>
                                                                <?php $needs3Val = (empty($dispData['nurse_needs3']) || $dispData['nurse_needs3'] === '自立') ? '無' : '有'; ?>
                                                                <input type="text" class="needs3_flg bg-gray2" value="<?= $needs3Val ?>" style="width:45px;" readonly>
                                                            </td>
                                                            <?php $res3 = (empty($dispData['nurse_needs3']) || $dispData['nurse_needs3'] === '自立') ? 0 : ($dispData['nurse_needs3'] === '要介助' ? 1 : 0); ?>
                                                            <td class="score"><div><?= $res3 ?></div></td>
                                                        </tr>
                                                        <tr class="need-b4">
                                                            <th>4.食事摂取</th>
                                                            <td>
                                                                <?php foreach ($gnrList['4.食事摂取'] as $key => $val): ?>
                                                                    <?php $check = $dispData['nurse_needs4'] === $val ? ' checked' : NULL; ?>
                                                                    <p><label><input type="radio" name="upAry[nurse_needs4]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                                <?php endforeach; ?>
                                                            </td>
                                                            <td class="assistance_list">
                                                                <span>介助の実施</span>
                                                                <?php $needs4Val = (empty($dispData['nurse_needs4']) || $dispData['nurse_needs4'] === '自立') ? '無' : '有'; ?>
                                                                <input type="text" class="needs4_flg bg-gray2" value="<?= $needs4Val ?>" style="width:45px;" readonly>
                                                            </td>
                                                            <?php $res4 = (empty($dispData['nurse_needs4']) || $dispData['nurse_needs4'] === '自立') ? 0 : ($dispData['nurse_needs4'] === '一部介助' ? 1 : 2); ?>
                                                            <td class="score"><div><?= $res4 ?></div></td>
                                                        </tr>
                                                    </table>
                                                </dd>
                                                <dd class="child_toggle">
                                                    <table>
                                                        <tr class="need-b5">
                                                            <th>5.衣服の着脱</th>
                                                            <td>
                                                                <?php foreach ($gnrList['5.衣服の着脱'] as $key => $val): ?>
                                                                    <?php $check = $dispData['nurse_needs5'] === $val ? ' checked' : NULL; ?>
                                                                    <p><label><input type="radio" name="upAry[nurse_needs5]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                                <?php endforeach; ?>
                                                            </td>
                                                            <td class="assistance_list">
                                                                <span>介助の実施</span>
                                                                <?php $needs5Val = (empty($dispData['nurse_needs5']) || $dispData['nurse_needs5'] === '自立') ? '無' : '有'; ?>
                                                                <input type="text" class="needs5_flg bg-gray2" value="<?= $needs5Val ?>" style="width:45px;" readonly>
                                                            </td>
                                                            <?php $res5 = (empty($dispData['nurse_needs5']) || $dispData['nurse_needs5'] === '自立') ? 0 : ($dispData['nurse_needs5'] === '一部介助' ? 1 : 2); ?>
                                                            <td class="score"><div><?= $res5 ?></div></td>
                                                        </tr>
                                                        <tr class="need-b6">
                                                            <th>6.診療・療養上の<span>指示が通じる</span></th>
                                                            <td colspan="2">
                                                                <?php foreach ($gnrList['6.診察・療養上の指示が通じる'] as $key => $val): ?>
                                                                    <?php $check = $dispData['nurse_needs6'] === $val ? ' checked' : NULL; ?>
                                                                    <p><label><input type="radio" name="upAry[nurse_needs6]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                                <?php endforeach; ?>
                                                            </td>
                                                            <?php $res6 = $dispData['nurse_needs6'] === 'いいえ' ? 1 : 0; ?>
                                                            <td class="score"><div><?= $res6 ?></div></td>
                                                        </tr>
                                                        <tr class="need-b7">
                                                            <th>7.危険行動</th>
                                                            <td colspan="2">
                                                                <?php foreach ($gnrList['7.危険行動'] as $key => $val): ?>
                                                                    <?php $check = $dispData['nurse_needs7'] === $val ? ' checked' : NULL; ?>
                                                                    <p><label><input type="radio" name="upAry[nurse_needs7]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                                <?php endforeach; ?>
                                                            </td>
                                                            <?php $res7 = $dispData['nurse_needs7'] === 'ある' ? 2 : 0; ?>
                                                            <td class="score"><div><?= $res7 ?></div></td>
                                                        </tr>
                                                        <tr>
                                                            <?php $total = $res1 + $res2 + $res3 + $res4 + $res5 + $res6 + $res7; ?>
                                                            <td class="total_score" colspan="4"><span>合計</span><div><b><?= $total ?></b><small>/12点</small></div></td>							
                                                        </tr>
                                                    </table>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="nurse_record record8">
                                        <span class="label_t">作成状態</span>
                                        <?php $check = $dispData['status'] === '完成' ? ' checked' : NULL; ?>
                                        <label for="完成"><input type="radio" name="upAry[status]" value="完成"<?= $check ?>>
                                        完成</label>
                                        <?php $check = $dispData['status'] !== '完成' ? ' checked' : NULL; ?>
                                        <label for="作成中"><input type="radio" name="upAry[status]" value="作成中"<?= $check ?>>
                                        作成中</label>
                                    </div>
                                    <div class="nurse_record record9">
                                        <div class="i_register">
                                            <span class="label_t">初回登録:</span>
                                            <span class="label_t hidzuke"><?= $dispData['create_day'] ?></span>
                                            <span class="label_t time"><?= $dispData['create_time'] ?></span>
                                            <span class="label_t"><?= $dispData['create_name'] ?></span>
                                        </div>
                                        <div class="l_update">
                                            <span class="label_t">最終更新:</span>
                                            <span class="label_t hidzuke"><?= $dispData['update_day'] ?></span>
                                            <span class="label_t time"><?= $dispData['update_time'] ?></span>
                                            <span class="label_t"><?= $dispData['update_name'] ?></span>
                                        </div>
                                    </div>
                                    <!--ダイアログ呼出し-->
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user.php'); ?>
                                    <?php // require_once($_SERVER['DOCUMENT_ROOT'].'/common/dialog/staff.php'); ?>
                                </div>
                            </div></div>
                        <!--/// CONTENT_END ///-->
                        <div class="fixed_navi patient_navi">
                            <div class="box">
                                <div class="btn back pc"><button type="submit" name="btnReturn" value="true">記録一覧にもどる</button></div>
                                <div class="controls">
                                    <button type="submit" class="btn save" name="btnEntry" value="保存">保存</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
        <script>

            // スタッフ検索クリックイベント
            $(document).on("click", ".multi_modal_open", function () {
                var tgUrl = $(this).data('url');
                var dlgName = $(this).data('dialog_name');
                $(".modal_setting").children().remove();

                let xhr = new XMLHttpRequest();
                xhr.open('GET', tgUrl, true);
                xhr.addEventListener('load', function () {
                    console.log(this.response);
                    $(".modal_setting").append(this.response);
                    $("." + dlgName).css("display", "block");
                });
                xhr.send();
            });

            var stfCnt = 100;
            function addStaffLine() {
                stfCnt++;
                var staffDiv = $(".staff_wrap");
                var staff_new = "";
                staff_new += '<div class="sline service_inch1">';
                staff_new += '  <p class="n_search multi_modal_open"';
                staff_new += '     data-url="/report/kantaki/dialog/staff_search_dialog.php?tgt_set_id=targetSetId' + stfCnt + '&tgt_set_other_id=targetSetOtherId' + stfCnt + '&tgt_set_name=targetSetName' + stfCnt + '"';
                staff_new += '     data-dialog_name="staff_modal">Search</p>';
                staff_new += '  <input type="text" class="targetSetOtherId' + stfCnt + '" name="" value="" style="margin-left:0px;width:90px;">';
                staff_new += '  <input type="hidden" class="targetSetId' + stfCnt + '" name="upStf1[staff_id][]" value="">';
                staff_new += '  <input type="text" class="n_name targetSetName' + stfCnt + ' bg-gray2" name="upStf1[name][]" value="" style="margin-left:0px;width:130px;" readonly="">';
                staff_new += '  <div class="care_worker" style="margin-left:2px;">';
                staff_new += '    <select name="upStf1[license][]">';
                staff_new += '      <option selected hidden disabled>選択してください</option>';
<?php foreach ($gnrList['担当スタッフ_職種'] as $key => $val): ?>
                staff_new += '      <option value="<?= $val ?>"><?= $val ?></option>';
<?php endforeach; ?>
                staff_new += '    </select>';
                staff_new += '  </div>';
                staff_new += '  <span class="btn trash2 delStaff"></span>';
                staff_new += '</div>';
                $(staff_new).appendTo(staffDiv);
            }

            $(document).ready(function () {
                if ($(".staff_wrap .sline.service_inch1").length === 0) {
                    addStaffLine();
                }
            });
            $(".staff_inch").on('click', '.addStaff', function (event) {
                event.preventDefault();
                addStaffLine();
            });

            $(".addVital").click(function () {
                var tbl = $(".vital_box table");
                var tr_new = "";
                tr_new += '<tr>';
                tr_new += '    <td>';
                tr_new += '        <span class="label_t"><label>時刻</label></span>';
                tr_new += '        <input type="time" name="upVtl1[counting_time][]" class="vital_time" value="">';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <span class="label_t"><label>体温</label></span>';
                tr_new += '        <input type="text" name="upVtl1[temperature][]" class="vital_temp" value="">';
                tr_new += '        <span class="unit_m">℃</span>';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <span class="label_t"><label>脈拍</label></span>';
                tr_new += '        <input type="text" name="upVtl1[pulse][]" class="vital_pulse" value="">';
                tr_new += '        <span class="unit_m">／分</span>';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <div>';
                tr_new += '            <span class="label_t"><label>血圧</label></span>';
                tr_new += '            <input type="text" name="upVtl1[blood_pressure1][]" class="vital_bp1" value="">';
                tr_new += '            <span>／</span>';
                tr_new += '            <input type="text" name="upVtl1[blood_pressure2][]" class="vital_bp2" value="">';
                tr_new += '        </div>';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <span class="label_t"><label>SpO2</label></span>';
                tr_new += '        <input type="text" name="upVtl1[spo2][]" class="vital_sp" value="">';
                tr_new += '    </td>';
                tr_new += '</tr>';
                $(tr_new).appendTo(tbl);
            });

            $(".addIntake").click(function () {
                var tbl = $(".intake_box table tr:nth-last-child(2)");
                var tr_new = "";
                tr_new += '<tr>';
                tr_new += '    <td>';
                tr_new += '        <input type="time" name="upWtr1[counting_time][]" class="intake_time" value="">';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <input type="text" name="upWtr1[amount][]" class="intake_ml" value="">';
                tr_new += '        <span class="unit_m">ml</span>';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <input type="text" name="upWtr1[method][]" class="intake_other" value="">';
                tr_new += '    </td>';
                tr_new += '    <td></td>';
                tr_new += '</tr>';
                $(tbl).after(tr_new);
            });

            var addIdx = 0;
            $(".addExc").click(function () {
                addIdx++;
                var tbl = $(".excretion_box table tr:nth-last-child(2)");
                var tr_new = "";
                tr_new += '<tr>';
                tr_new += '    <td>';
                tr_new += '        <input type="time" name="upExc1[counting_time][' + addIdx + ']" class="" value="">';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <input type="checkbox" name="upExc1[urination][' + addIdx + ']" id="" value="有">';
                tr_new += '        <input type="text" name="upExc1[urination_quantity][' + addIdx + ']" class="exc1" value="">';
                tr_new += '        <span class="unit_m">ml</span>';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <input type="checkbox" name="upExc1[evacuation][' + addIdx + ']" id="" value="有">';
                tr_new += '        <input type="text" name="upExc1[evacuation_memo][' + addIdx + ']" class="" value="">';
                tr_new += '    </td>';
                tr_new += '    <td></td>';
                tr_new += '</tr>';
                $(tbl).after(tr_new);
            });

            $(".addUse").click(function () {
                var tbl = $(".goods_use_box table");
                var tr_new = "";
                tr_new += '<tr>';
                tr_new += '    <td>';
                tr_new += '        <input type="text" name="upGds1[goods_name][]" class="excre_total" value="">';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <input type="text" name="upGds1[quantity][]" class="excre_total" value="">';
                tr_new += '    </td>';
                tr_new += '    <td></td>';
                tr_new += '</tr>';
                $(tr_new).appendTo(tbl);
            });

            $(".addFukuyaku").click(function () {
                var tbl = $(".fukuyaku_box table");
                var tr_new = "";
                tr_new += '<tr class="new_input">';
                tr_new += '    <td colspan="2">';
                tr_new += '        <input type="text" name="upDrg1[timing][]" class="" value="" placeholder="項目名">';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <input type="text" name="upDrg1[medicine][]" class="" value="" placeholder="入力">';
                tr_new += '    </td>';
                tr_new += '    <td></td>';
                tr_new += '</tr>';
                $(tr_new).appendTo(tbl);
            });


            //削除
            $(".staff_inch").on('click', '.delStaff', function (event) {
                event.preventDefault();
                $(this).closest('div').remove();
                return false;
            });

            // 体重計算
            function weightCalculation() {
                if(!$('#now_wght').val()) {return;}

                // 今回、前回、前月、前々月
                var now_wght = $('#now_wght').val();
                var last_wght = $('#last_wght').val();
                var last_wght2 = $('#last_wght2').val();
                var last_wght3 = $('#last_wght3').val();
                // 今回との差分
                var diff_wght = now_wght - last_wght;
                var diff_wght2 = now_wght - last_wght2;
                var diff_wght3 = now_wght - last_wght3;

                if (diff_wght > 0) {
                    diff_wght = '+' + diff_wght;
                }
                if (diff_wght2 > 0) {
                    diff_wght2 = '+' + diff_wght2;
                }
                if (diff_wght3 > 0) {
                    diff_wght3 = '+' + diff_wght3;
                }

                $(".diff-wght").val(diff_wght);
                $(".diff-wght2").val(diff_wght2);
                $(".diff-wght3").val(diff_wght3);
            }

            // 体重再計算イベント設定
            $(".now_wght").change(weightCalculation);
            $(".now_wght").ready(weightCalculation);

            /* 看護必要度（B項目）計算 */
            function needCalc() {

                /* 初期化 */
                var b11 = 0;
                var b21 = 0;
                var b22 = 0;
                var b31 = 0;
                var b32 = 0;
                var b41 = 0;
                var b42 = 0;
                var b51 = 0;
                var b52 = 0;
                var b61 = 0;
                var b71 = 0;
                var res1 = 0;
                var res2 = 0;
                var res3 = 0;
                var res4 = 0;
                var res5 = 0;
                var res6 = 0;
                var res7 = 0;
                var total = 0;
                //
                /* 値取得 */
                // 寝返り
                var tgt11 = $(".need-b1").find("input[type='radio']:checked").val();
                var b12 = '有';
                if (tgt11 === '何かにつかまればできる') {
                    b11 = 1;
                } else if (tgt11 === 'できない') {
                    b11 = 2;
                } else {
                    b11 = 0;
                    b12 = '無';
                }

                // 移乗
                var tgt21 = $(".need-b2").find("input[type='radio']:checked").val();
                var b22 = '有';
                if (tgt21 === '一部介助') {
                    b21 = 1;
                } else if (tgt21 === '全介助') {
                    b21 = 2;
                } else {
                    b21 = 0;
                    b22 = '無';
                }

                // 口腔清潔
                var tgt31 = $(".need-b3").find("input[type='radio']:checked").val();
                var b32 = '有';

                if (tgt31 === '要介助') {
                    b31 = 1;
                } else {
                    b31 = 0;
                    b32 = '無';
                }

                // 食事摂取
                var tgt41 = $(".need-b4").find("input[type='radio']:checked").val();
                var b42 = '有';
                if (tgt41 === '一部介助') {
                    b41 = 1;
                } else if (tgt41 === '全介助') {
                    b41 = 2;
                } else {
                    b41 = 0;
                    b42 = '無';
                }

                // 衣服の着脱
                var tgt51 = $(".need-b5").find("input[type='radio']:checked").val();
                var b52 = '有';
                if (tgt51 === '一部介助') {
                    b51 = 1;
                } else if (tgt51 === '全介助') {
                    b51 = 2;
                } else {
                    b51 = 0;
                    b52 = '無';
                }

                // 診療・療養上の指示が通じる
                var tgt61 = $(".need-b6").find("input[type='radio']:checked").val();
                if (tgt61 === 'いいえ') {
                    b61 = 1;
                } else {
                    b61 = 0;
                }

                // 危険行動
                var tgt71 = $(".need-b7").find("input[type='radio']:checked").val();
                if (tgt71 === 'ある') {
                    b71 = 2;
                } else {
                    b71 = 0;
                }

                /* 点数計算 */
                // 寝返り
                res1 = b11;

                // 移乗
                res2 = b21;

                // 口腔清潔
                res3 = b31;

                // 食事摂取
                res4 = b41;

                // 衣服の着脱
                res5 = b51;

                // 診療・療養上の指示が通じる
                res6 = b61;

                // 危険行動
                res7 = b71;

                // 合計
                total = res1 + res2 + res3 + res4 + res5 + res6 + res7;

                /* 点数書き換え */
                $(".need-b1").find(".score div").text(res1);
                $(".need-b2").find(".score div").text(res2);
                $(".need-b3").find(".score div").text(res3);
                $(".need-b4").find(".score div").text(res4);
                $(".need-b5").find(".score div").text(res5);
                $(".need-b6").find(".score div").text(res6);
                $(".need-b7").find(".score div").text(res7);
                $(".nursing_need-b").find(".total_score b").text(total);
                $(".needs2_flg").val(b22);
                $(".needs3_flg").val(b32);
                $(".needs4_flg").val(b42);
                $(".needs5_flg").val(b52);
            }

            $(function () {
                /* BMI計算 */
                $(".bmi_box").find(".bmi_hwght").on("change", function () {
                    /* 初期化 */
                    var height = 0;
                    var weight = 0;
                    var bmi = '';

                    /* データ値取得 */
                    var target1 = $(this).parents(".bmi_box").find(".now_hght").val();
                    var target2 = $(this).parents(".bmi_box").find(".now_wght").val();

                    /* 点書き換え */
                    if (target1) {
                        height = target1 / 100;
                    }
                    if (target2) {
                        weight = target2;
                    }

                    /* BMI計算 */
                    if (height && weight) {
                        bmi = Math.round((weight / (height * height)) * 10) / 10;
                        $(this).parents(".bmi_box").find(".bmi_total").val(bmi);
                    }
                });

                /* 水分摂取計算 */
                $(document).on("change", ".intake_ml", function () {

                    /* 初期化 */
                    var total = 0;

                    $(this).parents(".intake_box").find(".intake_ml").each(function () {

                        /* 初期化 */
                        var intakeVal = 0;

                        /* データ値取得 */
                        intakeVal = Number($(this).val());

                        if (intakeVal > 0) {
                            total = total + intakeVal;
                        }
                    });

                    /* 点書き換え */
                    $(".total_intake").val(total);
                });

                /* 排尿量計算 */
                $(document).on("change", ".exc1", function () {

                    /* 初期化 */
                    var total = 0;

                    $(this).parents(".excretion_box").find(".exc1").each(function () {

                        /* 初期化 */
                        var excVal = 0;

                        /* データ値取得 */
                        excVal = Number($(this).val());

                        if (excVal > 0) {
                            total = total + excVal;
                        }
                    });

                    /* 点書き換え */
                    // $(".exc_total").text(total);
                    $(".excre_total").val(total);
                });

                /* 看護必要度（B項目）計算 */
                $(".nursing_need-b").find("input[type='radio']").on("change", function () {
                    needCalc();
                });
                $(".nursing_need-b").find("select").on("change", function () {
                    needCalc();
                });
            });

            function linkImage() {
                var param = "";
                var user = $(".tgt-unique_id").val();
                if (user) {
                    param = "?user=" + user;
                } else {
                    param = $(location).attr('search');
                }
                window.open('/image/list/index.php' + param, '_blank', 'width=1500, height=800, top=200, left=200');
            }

            function previewImage(obj)
            {
                var fileReader = new FileReader();
                fileReader.onload = (function () {
                    document.getElementById('img_view').src = fileReader.result;
                    document.getElementById('big_image').href = fileReader.result;
                });
                fileReader.readAsDataURL(obj.files[0]);
            }

            /**
             * ユーザーを変更した際の画面遷移処理
             */
            (() => {
                // MutationObserverのコールバック関数を定義
                const observerCallback = function(mutations) {
                    mutations.forEach(function(mutation) {
                        const userId     = document.getElementById('user_id');
                        const serviceDay = document.getElementById('service_day');
                        if (mutation.type === 'attributes' && mutation.attributeName === 'value' && userId.value.length) {
                            getLastWeights(userId.value, serviceDay.value);
                        }
                    });
                };

                const observer = new MutationObserver(observerCallback);
                const userId   = document.getElementById('user_id');

                // 対象の要素と監視する設定を指定して監視を開始
                observer.observe(userId, {
                    attributes: true,          // 属性の変更を監視
                    attributeFilter: ['value'] // 'value'属性のみ監視
                });
            })();

            $('#service_day').on('change', function(){
                const userId     = document.getElementById('user_id');
                const serviceDay = document.getElementById('service_day');
                getLastWeights(userId.value, serviceDay.value);
            });

            $(document).ready(function() {
                const userId     = document.getElementById('user_id');
                const serviceDay = document.getElementById('service_day');
                getLastWeights(userId.value, serviceDay.value);
            });
            /**
             * 前回体重を取得します。
             */
            function getLastWeights(userId, serviceDay) {
                $.ajax({
                    async: false,
                    type: "GET",
                    url: "./ajax/ajax.php",
                    dataType: "text",
                    data: {
                        upAry : {
                            "user_id": userId,
                            "service_day": serviceDay
                        },
                    }
                }).done(function (data) {
                    const jsonData = JSON.parse(data);
                    console.log(jsonData);
                    setMultiValue(jsonData);
                    weightCalculation();
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log("ajax通信に失敗しました");
                    console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                    console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                    console.log("errorThrown    : " + errorThrown.message); // 例外情報
                });
            }
            
        </script>
    </body>
</html>