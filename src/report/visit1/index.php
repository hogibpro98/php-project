<?php require_once(dirname(__FILE__) . "/php/visit1.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>訪問看護記録Ⅰ</title>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <form action="" method="post" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">

                        <h2>訪問看護記録Ⅰ</h2>
                        <div id="patient" class="sm"><?= $dispData['user_name'] ?></div>
                        <div id="subpage"><div class="nursing" id="nurse-record1">
                                <div class="wrap">
                                    <ul class="user-tab">
                                        <li><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
                                        <li class="active"><a href="/report/list/?user=<?= $userId ?>">各種帳票</a></li>
                                        <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
                                    </ul>
                                    <div class="user-details">
                                        <div class="d_left">
                                            <dl>
                                                <dt class="label_t">利用者ID</dt>
                                                <dd>
                                                    <p class="n_search user_search">Search</p>
                                                    <input type="text" name="upDummy[other_id]" class="tgt-usr_id f-keyVal" value="<?= $dispData['other_id'] ?>" maxlength="7" pattern="^[0-9]+$">
                                                    <input type="hidden" name="upAry[common][user_id]" class="tgt-unique_id f-keyVal" value="<?= $userId ?>">
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt class="label_t">利用者氏名</dt>
                                                <dd>
                                                    <input type="text" name="upDummy[user_name]" value="<?= $dispData['user_name'] ?>" class="tgt-usr_name bg-gray2" readonly>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt class="label_t">要介護度</dt>
                                                <dd>
                                                    <select name="upAry[common][care_rank]" style="width:150px;">
                                                        <option value=""></option>
                                                        <?php foreach ($gnrList['要介護度'] as $val): ?>
                                                            <?php $select = $dispData['care_rank'] == $val ? ' selected' : NULL; ?>
                                                            <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </dd>
                                            </dl>
                                        </div>
                                        <div class="d_right">
                                            <p>
                                                <button type="submit" class="btn-edit" name="btnExcel" style="width: 105px;background: #D9A338;" value="<?= $dispData['unique_id'] ?>">サマリー出力</button>
                                                <?php $disabled = empty($keyId) ? 'disabled' : NULL; ?>
                                                <button type="submit" class="btn-edit" name="btnCopy" value="<?= $dispData['unique_id'] ?>" <?= $disabled ?>>複製</button>
                                                <button type="submit" class="btn-del" name="btnDel" value="<?= $dispData['unique_id'] ?>">削除</button>
                                            </p>
                                        </div>        
                                        <div class="nurse_record record1">
                                            <div class="line category">
                                                <span class="label_t">訪問看護<br class="sm">区分</span>
                                                <p>
                                                    <?php $check = $dispData['care_kb'] !== '精神科訪問看護' ? ' checked' : NULL; ?>
                                                    <input type="radio" name="upAry[common][care_kb]" class="f-keyVal" value="訪問看護"<?= $check ?>>
                                                    <label for="訪問看護">訪問看護</label>
                                                </p>
                                                <p>
                                                    <?php $check = $dispData['care_kb'] === '精神科訪問看護' ? ' checked' : NULL; ?>
                                                    <input type="radio" name="upAry[common][care_kb]" class="f-keyVal" value="精神科訪問看護"<?= $check ?>>
                                                    <label for="精神科訪問看護">精神科訪問看護</label>
                                                </p>
                                            </div>
                                            <div class="line profile">
                                                <div class="create_d">
                                                    <span class="label_t">作成日</span>
                                                    <input type="date" class="" style="width:150px;" name="upAry[common][report_day]" value="<?= $dispData['report_day'] === '0000-00-00' ? NULL : $dispData['report_day'] ?>">
                                                </div>
                                                <div class="name">
                                                    <span class="label_t">看護師等<br class="sm">氏名</span>
                                                    <p class="n_search staff_search" style="position:static">Search</p>
                                                    <input type="hidden" class="n_num tgt-stf_id f-keyVal" name="upAry[common][staff_id]" value="<?= $dispData['staff_id'] ?>">
                                                    <input type="text" class="n_num tgt-stf_cd f-keyVal" name="upDummy[staff_cd]" value="<?= $dispData['staff_cd'] ?>">
                                                    <input type="text" class="n_name tgt-stf_name bg-gray2" name="upDummy[staff_name]" value="<?= $dispData['staff_name'] ?>" readonly="">
                                                </div>
                                                <div class="occupation">
                                                    <span class="label_t">訪問職種</span>
                                                    <p class="occu_list">
                                                        <select name="upAry[common][visit_job]">
                                                            <option selected hidden disabled>選択してください</option>
                                                            <?php foreach ($gnrList['訪問職種'] as $val): ?>
                                                                <?php $select = $dispData['visit_job'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="line visit">
                                                <span class="label_t">初回<br class="sm">訪問日</span>
                                                <input type="date" class="f-keyVal" name="upAry[common][first_day]" value="<?= $dispData['first_day'] === '0000-00-00' ? NULL : $dispData['first_day'] ?>" style="width:150px;">
                                                <!--<span class="time"><input type="text" name="to" class="from" value="10:00"> ～ <input type="text" name="to" class="to" placeholder="時刻"></span>-->
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
                                            </div>
                                        </div>
                                    </div>

                                    <div class="record_info info1">
                                        <div class="nurse_record record2">
                                            <div class="tit no_bg tit_toggle">病歴等</div>
                                            <div class="box_wrap child_toggle">
                                                <div class="box-l">
                                                    <div class="tit_box">
                                                        <div>
                                                            <span class="label_t">主たる傷病名</span>
                                                            <!--<dd class="f2-keyData" data-tg_url='/report/visit1/ajax/sick_ajax.php?type=sick' style="margin-left:100px;">-->
                                                            <div class="copy_btn ref_sick modal_open"
                                                                data-url="/common/dialog/InstructionCopy.php?user=<?= $dispData['user_id'] ?>&index=1" 
                                                                data-dialog_name="dynamic_modal">
                                                               指示書から反映</div>
                                                            <!--</dd>-->
                                                        </div>
                                                    </div>
                                                    <div class="injury">
                                                        <input type="text" name="upAry[訪問看護][sickness1]" class="tgt-sick1 set1_sickness1" value="<?= $dispData['sickness1'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[訪問看護][sickness2]" class="tgt-sick2 set1_sickness2" value="<?= $dispData['sickness2'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[訪問看護][sickness3]" class="tgt-sick3 set1_sickness3" value="<?= $dispData['sickness3'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[訪問看護][sickness4]" class="tgt-sick4 set1_sickness4" value="<?= $dispData['sickness4'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[訪問看護][sickness5]" class="tgt-sick5 set1_sickness5" value="<?= $dispData['sickness5'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[訪問看護][sickness6]" class="tgt-sick6 set1_sickness6" value="<?= $dispData['sickness6'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[訪問看護][sickness7]" class="tgt-sick7 set1_sickness7" value="<?= $dispData['sickness7'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[訪問看護][sickness8]" class="tgt-sick8 set1_sickness8" value="<?= $dispData['sickness8'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[訪問看護][sickness9]" class="tgt-sick9 set1_sickness9" value="<?= $dispData['sickness9'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[訪問看護][sickness10]" class="tgt-sick10 set1_sickness10" value="<?= $dispData['sickness10'] ?>" placeholder="入力してください">
                                                    </div>
                                                    <!--
                                                    <div class="injury">
                                                        <div class="shou">
                                                            <input type="text" name="upAry[main_sickness]" class="tgt-sick_main" value=" //$dispData['main_sickness'] ">
                                                        </div>
                                                        <input type="text" name="entry" placeholder="入力してください">
                                                    </div>
                                                    -->
                                                    <div class="ill_history">
                                                        <span class="label_t">現病歴</span>
                                                        <input type="text" name="upAry[訪問看護][medical_record]" value="<?= $dispData['medical_record'] ?>">
                                                    </div>
                                                    <div class="med_history">
                                                        <span class="label_t">既往歴</span>
                                                        <input type="text" name="upAry[訪問看護][past_history]" value="<?= $dispData['past_history'] ?>">
                                                    </div>
                                                </div>
                                                <div class="box-r">
                                                    <div class="condition">
                                                        <span class="label_t">療養状況</span>
                                                        <textarea name="upAry[訪問看護][treatment]" value="<?= $dispData['treatment'] ?>"><?= $dispData['treatment'] ?></textarea>
                                                    </div>
                                                    <div class="situation">
                                                        <span class="label_t">介護状況</span>
                                                        <textarea name="upAry[訪問看護][care]" value="<?= $dispData['care'] ?>"><?= $dispData['care'] ?></textarea>
                                                    </div>
                                                    <div class="life_history">
                                                        <span class="label_t">生活歴</span>
                                                        <textarea name="upAry[訪問看護][life]" value="<?= $dispData['life'] ?>"><?= $dispData['life'] ?></textarea>
                                                    </div>
                                                    <div class="caregiver">
                                                        <span class="label_t">主な介護者</span>
                                                        <textarea name="upAry[訪問看護][main_caregiver]" value="<?= $dispData['main_caregiver'] ?>"><?= $dispData['main_caregiver'] ?></textarea>
                                                    </div>
                                                    <div class="l_environment">
                                                        <span class="label_t">住環境</span>
                                                        <textarea name="upAry[訪問看護][living]" value="<?= $dispData['living'] ?>"><?= $dispData['living'] ?></textarea>
                                                    </div>
                                                    <div class="purpose">
                                                        <span class="label_t">依頼目的</span>
                                                        <textarea name="upAry[訪問看護][purpose]" value="<?= $dispData['purpose'] ?>"><?= $dispData['purpose'] ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nurse_record record3">
                                            <div class="box-l">
                                                <div class="tit tit_toggle">ADL</div>
                                                <div class="box_wrap child_toggle">
                                                    <div class="meal">
                                                        <span class="label_t">食事</span>
                                                        <?php foreach ($gnrList['食事'] as $val): ?>
                                                            <?php $check = $dispData['adl1'] == $val ? ' checked' : NULL; ?>
                                                            <p><label><input type="radio" name="upAry[訪問看護][adl1]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="transfer">
                                                        <span class="label_t">椅子とベッド間の移乗</span>
                                                        <?php foreach ($gnrList['椅子とベッドの移乗'] as $val): ?>
                                                            <?php $check = $dispData['adl2'] == $val ? ' checked' : NULL; ?>
                                                            <p><label><input type="radio" name="upAry[訪問看護][adl2]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="grooming">
                                                        <span class="label_t">整容</span>
                                                        <?php foreach ($gnrList['整容'] as $val): ?>
                                                            <?php $check = $dispData['adl3'] == $val ? ' checked' : NULL; ?>
                                                            <p><label><input type="radio" name="upAry[訪問看護][adl3]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="toilet">
                                                        <span class="label_t">トイレ動作</span>
                                                        <?php foreach ($gnrList['トイレ動作'] as $val): ?>
                                                            <?php $check = $dispData['adl4'] == $val ? ' checked' : NULL; ?>
                                                            <p><label><input type="radio" name="upAry[訪問看護][adl4]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="bath">
                                                        <span class="label_t">入浴</span>
                                                        <?php foreach ($gnrList['入浴'] as $val): ?>
                                                            <?php $check = $dispData['adl5'] == $val ? ' checked' : NULL; ?>
                                                            <p><label><input type="radio" name="upAry[訪問看護][adl5]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="walk">
                                                        <span class="label_t">平地歩行</span>
                                                        <?php foreach ($gnrList['平地歩行'] as $val): ?>
                                                            <?php $check = $dispData['adl6'] == $val ? ' checked' : NULL; ?>
                                                            <p><label><input type="radio" name="upAry[訪問看護][adl6]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="climb">
                                                        <span class="label_t">階段昇降</span>
                                                        <?php foreach ($gnrList['階段昇降'] as $val): ?>
                                                            <?php $check = $dispData['adl7'] == $val ? ' checked' : NULL; ?>
                                                            <p><label><input type="radio" name="upAry[訪問看護][adl7]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="change">
                                                        <span class="label_t">更衣</span>
                                                        <?php foreach ($gnrList['更衣'] as $val): ?>
                                                            <?php $check = $dispData['adl8'] == $val ? ' checked' : NULL; ?>
                                                            <p><label><input type="radio" name="upAry[訪問看護][adl8]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="b_control">
                                                        <span class="label_t">排便コントロール</span>
                                                        <?php foreach ($gnrList['排便コントロール'] as $val): ?>
                                                            <?php $check = $dispData['adl9'] == $val ? ' checked' : NULL; ?>
                                                            <p><label><input type="radio" name="upAry[訪問看護][adl9]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="v_control">
                                                        <span class="label_t">排尿コントロール</span>
                                                        <?php foreach ($gnrList['排尿コントロール'] as $val): ?>
                                                            <?php $check = $dispData['adl10'] == $val ? ' checked' : NULL; ?>
                                                            <p><label><input type="radio" name="upAry[訪問看護][adl10]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-r">
                                                <div class="ind disability">
                                                    <div class="tit tit_toggle">障害自立度</div>
                                                    <div class="box_wrap child_toggle">
                                                        <div class="opinion">
                                                            <?php foreach ($gnrList['障害自立度_見解'] as $val): ?>
                                                                <?php $check = $dispData['handicap_opinion'] == $val ? ' checked' : NULL; ?>
                                                                <label><input type="radio" name="upAry[訪問看護][handicap_opinion]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="none">
                                                            <?php foreach ($gnrList['障害自立度_ランク'] as $val): ?>
                                                                <?php $check = $dispData['handicap_rank'] == $val ? ' checked' : NULL; ?>
                                                                <p><label><input type="radio" name="upAry[訪問看護][handicap_rank]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="comment">
                                                            <span class="label_t">コメント</span>
                                                            <textarea name="upAry[訪問看護][handicap_comment]" value="<?= $dispData['handicap_comment'] ?>"><?= $dispData['handicap_comment'] ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ind dementia">
                                                    <div class="tit tit_toggle">認知症自立度</div>
                                                    <div class="box_wrap child_toggle">
                                                        <div class="opinion">
                                                            <?php foreach ($gnrList['認知症自立度_見解'] as $val): ?>
                                                                <?php $check = $dispData['dementia_opinion'] == $val ? ' checked' : NULL; ?>
                                                                <label><input type="radio" name="upAry[訪問看護][dementia_opinion]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="none">
                                                            <?php foreach ($gnrList['認知症自立度_ランク'] as $val): ?>
                                                                <?php $check = $dispData['dementia_rank'] == $val ? ' checked' : NULL; ?>
                                                                <p><label><input type="radio" name="upAry[訪問看護][dementia_rank]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="comment">
                                                            <span class="label_t">コメント</span>
                                                            <textarea name="upAry[訪問看護][dementia_comment]" value="<?= $dispData['dementia_comment'] ?>"><?= $dispData['dementia_comment'] ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="record_info info2 disnon">
                                        <div class="nurse_record record2">
                                            <div class="tit no_bg tit_toggle">病歴等</div>
                                            <div class="box_wrap child_toggle">
                                                <div class="box-l">
                                                    <div class="tit_box">
                                                        <span class="label_t">主たる傷病名</span>
                                                        <!--<div class="copy_btn ref_sick">指示書から反映</div>-->
                                                        <button type="button" class="modal_open copy_btn ref_sick" data-url="/common/dialog/InstructionCopy.php?id=<?= $userId ?>&index=2" data-dialog_name="dynamic_modal">指示書から反映</button>
                                                    </div>
                                                    <div class="injury">
                                                        <input type="text" name="upAry[精神科訪問看護][sickness1]" class="tgt-sick1 set2_sickness1" value="<?= $dispData['sickness1'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[精神科訪問看護][sickness2]" class="tgt-sick2 set2_sickness2" value="<?= $dispData['sickness2'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[精神科訪問看護][sickness3]" class="tgt-sick3 set2_sickness3" value="<?= $dispData['sickness3'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[精神科訪問看護][sickness4]" class="tgt-sick4 set2_sickness4" value="<?= $dispData['sickness4'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[精神科訪問看護][sickness5]" class="tgt-sick5 set2_sickness5" value="<?= $dispData['sickness5'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[精神科訪問看護][sickness6]" class="tgt-sick6 set2_sickness6" value="<?= $dispData['sickness6'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[精神科訪問看護][sickness7]" class="tgt-sick7 set2_sickness7" value="<?= $dispData['sickness7'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[精神科訪問看護][sickness8]" class="tgt-sick8 set2_sickness8" value="<?= $dispData['sickness8'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[精神科訪問看護][sickness9]" class="tgt-sick9 set2_sickness9" value="<?= $dispData['sickness9'] ?>" placeholder="入力してください">
                                                        <input type="text" name="upAry[精神科訪問看護][sickness10]" class="tgt-sick10 set2_sickness10" value="<?= $dispData['sickness10'] ?>" placeholder="入力してください">
                                                    </div>
                                                    <div class="ill_history">
                                                        <span class="label_t">現病歴</span>
                                                        <input type="text" name="upAry[精神科訪問看護][medical_record]" value="<?= $dispData['medical_record'] ?>">
                                                    </div>
                                                    <div class="med_history">
                                                        <span class="label_t">既往歴</span>
                                                        <input type="text" name="upAry[精神科訪問看護][past_history]" value="<?= $dispData['past_history'] ?>">
                                                    </div>
                                                </div>
                                                <div class="box-r">
                                                    <div class="condition">
                                                        <span class="label_t">療養状況</span>
                                                        <textarea name="upAry[精神科訪問看護][treatment]" value="<?= $dispData['treatment'] ?>"><?= $dispData['treatment'] ?></textarea>
                                                    </div>
                                                    <div class="situation">
                                                        <span class="label_t">介護状況</span>
                                                        <textarea name="upAry[精神科訪問看護][care]" value="<?= $dispData['care'] ?>"><?= $dispData['care'] ?></textarea>
                                                    </div>
                                                    <div class="life_history">
                                                        <span class="label_t">生活歴</span>
                                                        <textarea name="upAry[精神科訪問看護][life]" value="<?= $dispData['life'] ?>"><?= $dispData['life'] ?></textarea>
                                                    </div>
                                                    <div class="caregiver">
                                                        <span class="label_t">主な介護者</span>
                                                        <textarea name="upAry[精神科訪問看護][main_caregiver]" value="<?= $dispData['main_caregiver'] ?>"><?= $dispData['main_caregiver'] ?></textarea>
                                                    </div>
                                                    <div class="l_environment">
                                                        <span class="label_t">住環境</span>
                                                        <textarea name="upAry[精神科訪問看護][living]" value="<?= $dispData['living'] ?>"><?= $dispData['living'] ?></textarea>
                                                    </div>
                                                    <div class="purpose">
                                                        <span class="label_t">依頼目的</span>
                                                        <textarea name="upAry[精神科訪問看護][purpose]" value="<?= $dispData['purpose'] ?>"><?= $dispData['purpose'] ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nurse_record record3">
                                            <div class="tit tit_toggle">日常生活等の状況(精神科訪問看護）</div>
                                            <div class="box_wrap child_toggle">
                                                <div class="box-l">
                                                    <div class="temp_box">
                                                        <div class="diet">
                                                            <span class="label_t">食生活</span>
                                                            <?php foreach ($gnrList2['食生活'] as $val): ?>
                                                                <?php $check = $dispData['adl1'] == $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[精神科訪問看護][adl1]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="clean">
                                                            <span class="label_t">清潔</span>
                                                            <?php foreach ($gnrList2['清潔'] as $val): ?>
                                                                <?php $check = $dispData['adl2'] == $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[精神科訪問看護][adl2]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="excretion">
                                                            <span class="label_t">排泄</span>
                                                            <?php foreach ($gnrList2['排泄'] as $val): ?>
                                                                <?php $check = $dispData['adl3'] == $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[精神科訪問看護][adl3]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="sleep">
                                                            <span class="label_t">睡眠</span>
                                                            <?php foreach ($gnrList2['睡眠'] as $val): ?>
                                                                <?php $check = $dispData['adl4'] == $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[精神科訪問看護][adl4]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="rhythm">
                                                            <span class="label_t">生活のリズム</span>
                                                            <?php foreach ($gnrList2['生活のリズム'] as $val): ?>
                                                                <?php $check = $dispData['adl5'] == $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[精神科訪問看護][adl5]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <div class="temp_box">
                                                        <div class="room">
                                                            <span class="label_t">部屋の整頓</span>
                                                            <?php foreach ($gnrList2['部屋の整頓'] as $val): ?>
                                                                <?php $check = $dispData['adl6'] == $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[精神科訪問看護][adl6]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="med_stat">
                                                            <span class="label_t">服薬状況</span>
                                                            <?php foreach ($gnrList2['服薬状況'] as $val): ?>
                                                                <?php $check = $dispData['adl7'] == $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[精神科訪問看護][adl7]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="money">
                                                            <span class="label_t">金銭管理</span>
                                                            <?php foreach ($gnrList2['金銭管理'] as $val): ?>
                                                                <?php $check = $dispData['adl8'] == $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[精神科訪問看護][adl8]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="work_stat">
                                                            <span class="label_t">作業等の状況</span>
                                                            <?php foreach ($gnrList2['作業等の状況'] as $val): ?>
                                                                <?php $check = $dispData['adl9'] == $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[精神科訪問看護][adl9]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="relations">
                                                            <span class="label_t">対人関係</span>
                                                            <?php foreach ($gnrList2['対人関係'] as $val): ?>
                                                                <?php $check = $dispData['adl10'] == $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[精神科訪問看護][adl10]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box-r">
                                                    <div class="s_remark">
                                                        <span class="label_t">特記事項</span>
                                                        <input type="text" name="upAry[精神科訪問看護][notices]" value="<?= $dispData['notices'] ?>">
                                                    </div>
                                                    <div class="remark">
                                                        <span class="label_t">備考</span>
                                                        <textarea name="upAry[精神科訪問看護][remarks]" value="<?= $dispData['remarks'] ?>"><?= $dispData['remarks'] ?></textarea>
                                                    </div>            
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ダイアログ流し込みエリア -->
                                    <div class="modal_setting"></div>

                                    <div class="nurse_record record4">
                                        <div class="box-l">
                                            <div class="tit tit_toggle">主治医情報</div>
                                            <div class="box_wrap child_toggle">
                                                <dd class="f-keyData" data-tg_url='/report/visit1/ajax/doctor_ajax.php?type=doctor'>
                                                    <div class="copy_btn ref_doctor" >指示書から反映</div>
<!--                                                         data-url="/common/dialog/InstructionCopy.php?user=<?= $userId ?>"
                                                         data-dialog_name="dynamic_modal"
                                                         >指示書から反映</div>-->
                                                </dd>
                                                <div class="institution">
                                                    <span class="label_t col10">医療機関名称 <small class="sm sm_txt">文字数制限なし</small></span>
                                                    <input type="text" name="upDummy[hospital1]" class="tgt-doc_hosp set_hospital" value="<?= $dispData['hospital'] ?>">
                                                    <span class="sm txt_orn">帳票類の印刷時に出力されます</span>
                                                </div>
                                                <div class="receipt_out sm">
                                                    <span class="label_t col10">医療機関名称 <small class="qm"><img src="/common/image/icon_question.png" alt=""></small><small class="sm_txt">最大16文字まで</small></span>
                                                    <input type="text" name="upDummy[hospital2]" class="tgt-doc_hosp set_hospital_rece" value="<?= $dispData['hospital'] ?>">
                                                    <span class="txt_blu">帳票類の印刷時に出力されます</span>
                                                </div>
                                                <div class="physician">
                                                    <span class="label_t col1">主治医</span>
                                                    <input type="text" name="upAry[common][doctor]" class="tgt-doc_doc set_doctor" value="<?= $dispData['doctor'] ?>">
                                                </div>
                                                <div class="location">
                                                    <span class="label_t col2">所在地</span>
                                                    <input type="text" name="upAry[common][address1]" class="tgt-doc_adr set_address1" value="<?= $dispData['address1'] ?>">
                                                </div>
                                                <div class="number">
                                                    <p><span class="label_t col3">電話番号①</span>
                                                        <input type="tel" name="upAry[common][tel1]" class="tgt-doc_tel1 set_tel1" value="<?= $dispData['tel1'] ?>"></p>
                                                    <p><span class="label_t col4">電話番号②</span>
                                                        <input type="tel" name="upAry[common][tel2]" class="tgt-doc_tel2 set_tel2" value="<?= $dispData['tel2'] ?>"></p>
                                                    <p><span class="label_t col5">FAX</span>
                                                        <input type="tel" name="upAry[common][fax1]" class="tgt-doc_fax set_fax" value="<?= $dispData['fax1'] ?>"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-r">
                                            <div class="tit tit_toggle">居宅情報</div>
                                            <div class="box_wrap child_toggle">
                                                <dd class="f-keyData" data-tg_url='/report/visit1/ajax/office_ajax.php?type=office'>
                                                    <div class="copy_btn ref_ofc">基本情報から反映</div>
                                                </dd>
                                                <div class="business">
                                                    <span class="label_t">事業所名称</span>
                                                    <input type="text" name="upAry[common][office]" class="tgt-ofc_name" value="<?= $dispData['office'] ?>">
                                                </div>
                                                <div class="manager">
                                                    <span class="label_t">担当者</span>
                                                    <input type="text" name="upAry[common][person]" class="tgt-ofc_staff" value="<?= $dispData['person'] ?>">
                                                </div>
                                                <div class="location">
                                                    <span class="label_t">所在地</span>
                                                    <input type="text" name="upAry[common][address2]" class="tgt-ofc_adr" value="<?= $dispData['address2'] ?>">
                                                </div>
                                                <div class="number">
                                                    <p><span class="label_t">電話番号</span>
                                                        <input type="tel" name="upAry[common][tel3]" class="tgt-ofc_tel" value="<?= $dispData['tel3'] ?>"></p>
                                                    <p><span class="label_t">FAX</span>
                                                        <input type="tel" name="upAry[common][fax2]" class="tgt-ofc_fax" value="<?= $dispData['fax2'] ?>"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nurse_record record5">
                                        <div class="tit tit_toggle">家族構成</div>
                                        <div class="box_wrap child_toggle">
                                            <dd class="f-keyData" data-tg_url='/report/visit1/ajax/family_ajax.php?type=family'>
                                                <div class="copy_btn ref_fml get_family_info">基本情報から反映</div>
                                            </dd>
                                            <div class="family_list">
                                                <table>
                                                    <tr>
                                                        <th></th>
                                                        <th>氏名</th>
                                                        <th>年齢</th>
                                                        <th>続柄</th>
                                                        <th>続柄メモ</th>
                                                        <th>職業</th>
                                                        <th>備考</th>
                                                        <th></th>
                                                    </tr>
                                                    <?php $i = 0; ?>
                                                    <?php foreach ($dispFml as $key => $val): ?>
                                                        <?php $i++; ?>
                                                        <tr>
                                                            <td>家族<?= $i ?></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][name]" class="tgt-fml_name<?= $i ?>" value="<?= $val['name'] ?>"></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][age]" class="tgt-fml_age<?= $i ?>" value="<?= $val['age'] ?>"></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][relation]" class="tgt-fml_type<?= $i ?>" value="<?= $val['relation'] ?>"></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][relation_memo]" class="tgt-fml_type<?= $i ?>" value="<?= $val['relation_memo'] ?>"></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][job]" class="tgt-fml_business<?= $i ?>" value="<?= $val['job'] ?>"></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][remarks]" class="tgt-fml_remarks<?= $i ?>" value="<?= $val['remarks'] ?>"></td>
                                                            <td>
                                                                <button type="submit" class="btn-del" name="btnDelFml" value="<?= $val['unique_id'] ?>">削除</button>
                                                                <input type="hidden" name="upFml[<?= $i ?>][unique_id]" value="<?= $val['unique_id'] ?>">
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <?php for ($j = 0; $j < (3 - count($dispFml)); $j++): ?>
                                                        <?php $i++; ?>
                                                        <tr>
                                                            <td>家族<?= $i ?></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][name]" class="tgt-fml_name<?= $i ?>" value=""></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][age]" class="tgt-fml_age<?= $i ?>" value=""></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][relation]" class="tgt-fml_type<?= $i ?>" value=""></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][relation_memo]" class="tgt-fml_memo<?= $i ?>" value=""></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][job]" class="tgt-fml_business<?= $i ?>" value=""></td>
                                                            <td><input type="text" name="upFml[<?= $i ?>][remarks]" class="tgt-fml_remarks<?= $i ?>" value=""></td>
                                                            <td>
                                                                <span class="btn trash" onclick="delRows(this)">削除</span>
                                                            </td>
                                                        </tr>
                                                    <?php endfor; ?>
                                                </table>
                                            </div>
                                            <div class="note">※レコード作成時に「利用者基本情報」の「家族構成」家族①～③が自動挿入されます。</div>
                                        </div>
                                    </div>
                                    <div class="nurse_record record6">
                                        <div class="tit tit_toggle">関係施設</div>
                                        <div class="box_wrap child_toggle">
                                            <div class="btn add" onclick="addRows()">行追加</div>
                                            <div class="facilities">
                                                <table id="facility">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>連絡先(施設名など)</th>
                                                            <th>担当者</th>
                                                            <th>備考</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="facility_body">
                                                        <?php foreach ($dispFcl as $key => $val): ?>
                                                            <tr>
                                                                <td></td>
                                                                <td><input type="text" name="upFcl1[contact][]" value="<?= $val['contact'] ?>"></td>
                                                                <td><input type="text" name="upFcl1[person][]" value="<?= $val['person'] ?>"></td>
                                                                <td><input type="text" name="upFcl1[remarks][]" value="<?= $val['remarks'] ?>"></td>
                                                                <td>
                                                                    <input type="hidden" name="upFcl1[unique_id][]" value="<?= $val['unique_id'] ?>">
                                                                    <button type="submit" class="btn-del" name="btnDelFcl" value="<?= $val['unique_id'] ?>">削除</button>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr>
                                                            <td></td>
                                                            <td><input type="text" name="upFcl1[contact][]" value=""></td>
                                                            <td><input type="text" name="upFcl1[person][]" value=""></td>
                                                            <td><input type="text" name="upFcl1[remarks][]" value=""></td>
                                                            <td><span class="btn trash" onclick="delRows(this)">削除</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nurse_record record7">
                                        <div class="tit tit_toggle">健康・福祉サービスの利用状況</div>
                                        <div class="status child_toggle">
                                            <?php $i = 0; ?>
                                            <?php $lastKey = getLastKey($gnrList['健康・福祉サービスの利用状況']); ?>
                                            <?php foreach ($gnrList['健康・福祉サービスの利用状況'] as $tgtId => $val): ?>
                                                <?php $i++; ?>
                                                <?php if ($i == 1): ?>
                                                    <ul>
                                                    <?php endif; ?>
                                                    <li>
                                                        <label><?php $check = strpos($dispData['use_service'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                        <input type="checkbox" name="upDummy[use_service][]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label>
                                                    </li>
                                                    <?php if ($i > 9 || $tgtId == $lastKey): ?>
                                                    </ul>
                                                    <?php $i = 0; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <!--作成状態-->
                                    <div class="nurse_record record8">
                                        <span class="label_t">作成状態</span>
                                        <?php $check = $dispData['status'] === '完成' ? ' checked' : NULL; ?>
                                        <input type="radio" name="upAry[common][status]" value="完成"<?= $check ?>>
                                        <label for="完成">完成</label>
                                        <?php $check = $dispData['status'] !== '完成' ? ' checked' : NULL; ?>
                                        <input type="radio" name="upAry[common][status]" value="作成中"<?= $check ?>>
                                        <label for="作成中">作成中</label>
                                    </div>

                                    <!--登録者情報-->
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

                                    <!-- ダイアログ流し込みエリア -->
                                    <div class="modal_setting"></div>

                                    <!--ダイアログ呼出し-->
                                    <?php // require_once($_SERVER['DOCUMENT_ROOT'].'/common/dialog/office.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/sickness.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/doctor.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/officeInfo.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/family.php'); ?>

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
                                <!--        <div class="btn back pc">
                                            <button type="submit" name="btnReturn" value="true">利用者一覧にもどる</button>
                                        </div>-->
                                <div class="btn back pc"><button type="submit" name="btnReturn" value="true">記録一覧にもどる</button></div>
                                <div class="btn back sm"><a href="/report/report_list/index.php"><img src="/common/image/icon_return.png" alt="Return"></a></div>
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
            //行追加
            var tbl = document.getElementById("facility");
            var tbody = tbl.children[1];
            function addRows() {
                var tbl = document.getElementById("facility");
                var tbody = tbl.getElementsByTagName("tbody")[0];
                
                // Create a new row
                var newRow = tbody.insertRow();

                // Create and insert cells
                var cell1 = newRow.insertCell(0);
                var cell2 = newRow.insertCell(1);
                var cell3 = newRow.insertCell(2);
                var cell4 = newRow.insertCell(3);
                var cell5 = newRow.insertCell(4);

                // Set the cell contents
                cell1.innerHTML = '';
                cell2.innerHTML = '<input name="upFcl1[contact][]" type="text">';
                cell3.innerHTML = '<input name="upFcl1[person][]" type="text">';
                cell4.innerHTML = '<input name="upFcl1[remarks][]" type="text">';
                cell5.innerHTML = '<span class="btn trash" onclick="delRows(this)">削除</span>';
            }

            //行削除
            function delRows(element) {
                var row = element.parentNode.parentNode;
                row.parentNode.removeChild(row);
            }
            
            // 利用者基本情報から反映する
            $(function(){

                $(document).on("click", ".get_family_info", function () {
                   
                    var userId   = $(".tgt-unique_id").val();
                    var otherId  = $(".tgt-usr_id").val();
                    var userName = $(".tgt-usr_name").val();

                    if(!userId || !otherId || !userName){
                        alert("利用者が選択されていません。利用者を選択して下さい。");
                        return false;
                    }
                    var result = window.confirm('基本情報から家族構成に反映してもよろしいですか？');
                    if (!result) {
                        // いいえ押下時、Submit阻止
                        return false;
                    }else{
//                        alert("基本情報が取得できませんでした。");
//                        return false;
                    }

                    $.ajax({
                        async: false,
                        type: "POST",
                        url: "/common/dialog/userInfoCopy.php",
                        dataType: "jsonp",
                        data: {
                            "user_id"      : userId
                        }
                   }).done(function (data) {
                         console.log("結果"+data);
                         
                         
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log("ajax通信に失敗しました");
                        console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                        console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                        console.log("errorThrown    : " + errorThrown.message); // 例外情報
                    });
                });
            });
        </script>
    </body>
</html>