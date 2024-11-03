<?php require_once(dirname(__FILE__) . "/php/plan.php"); 
//echo '<pre>';print_r($dispData);die();
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>計画書</title>
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
                        <h2>計画書</h2>
                        <div id="subpage"><div id="plan" class="nursing rep_details">

                                <!--                                <div class="new_default dname_db duplicated1 cancel_act">
                                                                    <div class="sched_tit">病名選択</div>
                                                                    <div class="close close_part">✕<span>閉じる</span></div>
                                                                    <div class="selected_name">選択した病名を反映</div>
                                                                    <div class="d_database">
                                                                        <table>
                                                                            <thead>
                                                                                <tr>
                                                                                    <th></th>
                                                                                    <th>病名</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td><input type="checkbox" name="病名" id="name1" checked></td>
                                                                                    <td>末期の悪性腫瘍</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><input type="checkbox" name="病名" id="name1" checked></td>
                                                                                    <td>多発性硬化症</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><input type="checkbox" name="病名" id="name1" checked></td>
                                                                                    <td>重症筋無力症</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><input type="checkbox" name="病名" id="name1" checked></td>
                                                                                    <td>スモン</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><input type="checkbox" name="病名" id="name1" checked></td>
                                                                                    <td>筋萎縮性側索硬化症</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><input type="checkbox" name="病名" id="name1" checked></td>
                                                                                    <td>脊髄小脳変性症</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><input type="checkbox" name="病名" id="name1" checked></td>
                                                                                    <td>ハンチントン病</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><input type="checkbox" name="病名" id="name1" checked></td>
                                                                                    <td>進行性筋ジストロフィー症</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><input type="checkbox" name="病名" id="name1" checked></td>
                                                                                    <td>パーキンソン病関連疾患(進行性核上性麻痺、大脳皮質基底核変性症、パーキンソン病（ホーエン・ヤールの重症度分類がステージ三以上であって生活機能障害度がⅡ度又はⅢ度のものに限る。））</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>-->

                                <div class="wrap">
                                    <ul class="user-tab">
                                        <li><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
                                        <li class="active"><a href="/report/list/?user=<?= $userId ?>">各種帳票</a></li>
                                        <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
                                    </ul>
                                    <div class="nurse_record user-details">
                                        <div class="line profile">
                                            <div class="category">
                                                <span class="label_t">訪問看護区分</span>
                                                <span class="req">*</span> 
                                                <?php foreach ($gnrList['訪問看護区分'] as $key => $val): ?>
                                                    <?php $check = $dispData['care_kb'] === $val ? ' checked' : NULL; ?>
                                                    <input type="radio" name="upAry[care_kb]" class=" f-keyVal" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="name">
                                                <span class="label_t">担当者</span>
                                                <p class="n_search staff_search">Search</p>
                                                <input type="hidden" class="n_num tgt-stf_id f-keyVal" name="upAry[staff_id]" value="<?= $dispData['staff_id'] ?>">
                                                <input type="text" class="n_num tgt-stf_cd f-keyVal" name="upDummy[staff_cd]" value="<?= $dispData['staff_cd'] ?>">
                                                <input type="text" class="n_name tgt-stf_name bg-gray2" name="upDummy[staff_name]" value="<?= $dispData['staff_name'] ?>" readonly="">
                                            </div>
                                            <div class="create_d">
                                                <span class="label_t">作成日</span>
                                                <input type="date" pattern="\d{4}-\d{2}-\d{2}" name="upAry[report_day]" class="" style="width:130px;" value="<?= $dispData['report_day'] ?>">
                                            </div>
                                            <div class="i_period">
                                                <span class="label_t">有効期間</span>
                                                <input type="date" name="upAry[validate_start]"  class="" style="width:130px;" value="<?= $dispData['validate_start'] ?>">
                                                <small>～</small>
                                                <input type="date" name="upAry[validate_end]"  class="" style="width:130px;" value="<?= $dispData['validate_end'] ?>">
                                            </div>
                                            <div class="line3">
                                                <dl>
                                                    <dt>利用者ID</dt>
                                                    <dd>
                                                        <p class="n_search user_search">Search</p>
                                                        <input type="text" name="upDummy[other_id]" class="n_num tgt-usr_id f-keyVal" value="<?= $dispData['other_id'] ?>" maxlength="7" pattern="^[0-9]+$">
                                                        <input type="hidden" name="upAry[user_id]" class="tgt-unique_id f-keyVal" value="<?= $userId ?>">
                                                    </dd>
                                                </dl>
                                                <dl>
                                                    <dt>利用者氏名</dt>
                                                    <dd>
                                                        <input type="text" name="upDummy[user_name]" value="<?= $dispData['user_name'] ?>" class="n_name tgt-usr_name bg-gray2" readonly>
                                                    </dd>
                                                </dl>
                                                <div class="birthday">
                                                    <span class="label_t">生年月日</span>
                                                    <p style="display:flex; ">
                                                        <input type="text" name="upDummy[birthday]" value="<?= $dispData['birthday_disp'] ?>" class="n_birthday tgt-usr_birthday bg-gray2" readonly>
                                                        <span style="margin-left: 4px;"></span>
                                                        <input type="text" name="upDummy[age]" value="<?= $dispData['age'] ?>" class="n_age tgt-usr_age bg-gray2" readonly>
                                                    </p>
                                                </div>
                                                <div class="care">
                                                    <span class="label_t">要介護度</span>
                                                    <input type="text" name="upDummy[care_rank]" class="n_rank tgt-usr_rank bg-gray2" value="<?= $dispData['care_rank'] ?>" readonly>
                                                </div>	
                                                <div class="address">
                                                    <span class="label_t">住所</span>
                                                    <input type="text" name="upDummy[address]" class="n_adr tgt-usr_adr bg-gray2" value="<?= $dispData['user_address'] ?>" readonly>
                                                </div>
                                            </div>			
                                        </div>
                                        <div class="d_right">
                                            <div class="pc">
                                                <span class="label_t">宛先指定</span>
                                                <p>
                                                    <?php $checked = mb_strpos($dispData['target_person'], '主治医') !== FALSE ? ' checked' : NULL; ?>
                                                    <input type="checkbox" name="upDummy[target_person][]" value="主治医" id="add1" <?= $checked ?> class="">
                                                    <span class="label_t"><label for="add1">主治医</label></span>
                                                    <?php $checked = mb_strpos($dispData['target_person'], '利用者') !== FALSE ? ' checked' : NULL; ?>
                                                    <input type="checkbox" name="upDummy[target_person][]" value="利用者" id="add2" <?= $checked ?> class="">
                                                    <span class="label_t"><label for="add2">利用者</label></span>
                                                    <?php $checked = mb_strpos($dispData['target_person'], 'ケアマネ') !== FALSE ? ' checked' : NULL; ?>
                                                    <input type="checkbox" name="upDummy[target_person][]" value="ケアマネ" id="add3" <?= $checked ?> class="">
                                                    <span class="label_t"><label for="add3">ケアマネ</label></span>
                                                    <?php $checked = mb_strpos($dispData['target_person'], 'その他') !== FALSE ? ' checked' : NULL; ?>
                                                    <input type="checkbox" name="upDummy[target_person][]" value="その他" id="add4" <?= $checked ?> class="">
                                                    <span class="label_t"><label for="add4">その他</label></span>
                                                </p>
                                                <span class="label_t"><?= $dispData['print_day'] ?></span>
                                            </div>
                                            <p>
                                                <?php $disabled2 = $dispData['unique_id'] ? '' : ' disabled' ?>
                                                <?php $opacity = $dispData['unique_id'] ? '' : ' opacity:0.5;' ?>
                                                <button type="submit" class="btn print chkSave" name="btnPrint" value="<?= $dispData['unique_id'] ?>"<?= $disabled2 ?> style="height:45.6px;<?= $opacity ?>">印刷</button>
                                                <?php $disabled = empty($keyId) ? 'disabled' : NULL; ?>
                                                <button type="submit" class="btn-edit chkSave btnCopy" name="btnCopy" value="<?= $dispData['unique_id'] ?>" <?= $disabled ?> style="margin-left:7px;width:65.6px;height:45.6px;<?= $opacity ?>">複製</button>
                                                <button type="submit" class="btn-del chkSave" name="btnDel" value="<?= $dispData['unique_id'] ?>"<?= $disabled2 ?> style="margin-left:7px;width:65.6px;height:45.6px;<?= $opacity ?>">削除</button>
                                                <span class="">
                                                    <button type="submit" class="btn add2 chkSave" name="btnAdd" value="<?= $dispData['unique_id'] ?>"<?= $disabled2 ?> style="margin-top:10px;<?= $opacity ?>">+この計画書から報告書を作成</button>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="nurse_record goals">
                                        <div class="rehab">
                                            <span class="label_t no_bg tit_toggle">看護リハビリテーションの<br class="pc">目標</span>
                                            <textarea name="upAry[goal]" class="child_toggle" value="<?= $dispData['goal'] ?>"><?= $dispData['goal'] ?></textarea>
                                        </div>
                                        <div class="tit no_bg tit_toggle sm">問題点・解決策・評価</div>
                                        <div class="rehab_box child_toggle">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>日付</th>
                                                        <th>問題点</th>
                                                        <th>解決策</th>
                                                        <th>評価</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($dispPrb as $key => $val): ?>
                                                        <tr>
                                                            <td><button type="submit" class="btn-del3" name="btnDelPrb" value="<?= $val['unique_id'] ?>">削除</button></td>
                                                            <td>
                                                                <input type="date" name="upPrb[plan_day][]" class="" value="<?= $val['plan_day'] ?>">
                                                                <input type="hidden" name="upPrb[unique_id][]" value="<?= $val['unique_id'] ?>">
                                                            </td>
                                                            <td>
                                                                <b>問題点</b>
                                                                <textarea name="upPrb[problem][]" class="" value="<?= $val['problem'] ?>" maxlength="100"><?= $val['problem'] ?></textarea>
                                                            </td>
                                                            <td>
                                                                <b>解決策</b>
                                                                <textarea name="upPrb[solution][]" class="" value="<?= $val['solution'] ?>" maxlength="300"><?= $val['solution'] ?></textarea>
                                                            </td>
                                                            <td>
                                                                <b>評価</b>
                                                                <textarea name="upPrb[evaluation][]" class="" value="<?= $val['evaluation'] ?>" maxlength="200"><?= $val['evaluation'] ?></textarea>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="btn add pc addRows">追加</div>
                                                            <div class="btn add sm addRows">新規作成</div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>			
                                        </div>
                                        <div class="treatment">
                                            <div class="quest">
                                                <span class="label_t">衛生材料等が必要な処置の有無</span>
                                                <?php foreach ($gnrList['衛生材料等が必要な処置の有無'] as $key => $val): ?>
                                                    <?php $check = $dispData['material_needs'] === $val ? ' checked' : NULL; ?>
                                                    <input type="radio" name="upAry[material_needs]" value="<?= $val ?>"<?= $check ?>><label><span><?= $val ?></span></label>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="treatment_guide">
                                                <div class="treat_cont">
                                                    <div class="mid tit_toggle">処置の内容</div>
                                                    <div class="come child_toggle">
                                                        <textarea name="upAry[dealing]" class="" value="<?= $dispData['dealing'] ?>" maxlength="150"><?= $dispData['dealing'] ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="materials">
                                                    <div class="mid tit_toggle">衛生材料(種類・サイズ)等</div>
                                                    <div class="come child_toggle">
                                                        <textarea name="upAry[medical_material]" class="" value="<?= $dispData['medical_material'] ?>" maxlength="150"><?= $dispData['medical_material'] ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="amount">
                                                    <div class="mid tit_toggle">必要量</div>
                                                    <div class="come child_toggle">
                                                        <textarea name="upAry[requirement]" class="" value="<?= $dispData['requirement'] ?>" maxlength="150"><?= $dispData['requirement'] ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="occupation">
                                                <div class="visit">
                                                    <p class="tit no_bg tit_toggle">訪問予定の職種<small>※当該月に理学療法士等による訪問が予定されている場合に記載</small></p>
                                                    <div class="box_wrap child_toggle">
                                                        <small class="sm">※当該月に理学療法士等による訪問が予定されている場合に記載</small>
                                                        <select name="upAry[visit_job]" class="">
                                                            <option selected hidden>選択してください</option>
                                                            <?php foreach ($gnrList['訪問予定の職種'] as $key => $val): ?>
                                                                <?php $select = $dispData['visit_job'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="remarks">
                                                    <p>備考</p>
                                                    <textarea name="upAry[remarks]" class="" value="<?= $dispData['remarks'] ?>" maxlength="150"><?= $dispData['remarks'] ?></textarea>
                                                </div>
                                            </div>
                                            <div class="personnel">
                                                <div class="creator">
                                                    <span class="label_t">作成者①</span>
                                                    <p class="n_search staff2_search">Search</p>
                                                    <input type="hidden" class="n_num tgt-stf2_id" name="upAry[create_staff1]" value="<?= $dispData['create_staff1'] ?>">
                                                    <input type="text" class="n_num tgt-stf2_cd" name="upDummy[staff1_cd]" value="<?= $dispData['staff1_cd'] ?>">
                                                    <input type="text" class="n_name tgt-stf2_name bg-gray2" name="upDummy[staff1_name]" value="<?= $dispData['staff1_name'] ?>" readonly="">
                                                    <p>
                                                        <span class="label_t">職種</span>
                                                        <select name="upAry[create_job1]" class="">
                                                            <option selected hidden>選択してください</option>
                                                            <?php foreach ($gnrList['作成者①_職種'] as $key => $val): ?>
                                                                <?php $select = $dispData['create_job1'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </p>
                                                </div>
                                                <div class="creator">
                                                    <span class="label_t">作成者②</span>
                                                    <p class="n_search staff3_search">Search</p>
                                                    <input type="hidden" class="n_num tgt-stf3_id" name="upAry[create_staff2]" value="<?= $dispData['create_staff2'] ?>">
                                                    <input type="text" class="n_num tgt-stf3_cd" name="upDummy[staff2_cd]" value="<?= $dispData['staff2_cd'] ?>">
                                                    <input type="text" class="n_name tgt-stf3_name bg-gray2" name="upDummy[staff2_name]" value="<?= $dispData['staff2_name'] ?>" readonly="">
                                                    <p>
                                                        <span class="label_t">職種</span>
                                                        <select name="upAry[create_job2]" class="">
                                                            <option selected hidden>選択してください</option>
                                                            <?php foreach ($gnrList['作成者②_職種'] as $key => $val): ?>
                                                                <?php $select = $dispData['create_job2'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </p>
                                                </div>
                                                <div class="admin">
                                                    <span class="label_t">管理者　</span>
                                                    <p class="n_search staff4_search">Search</p>
                                                    <input type="hidden" class="n_num tgt-stf4_id" name="upAry[manager]" value="<?= $dispData['manager'] ?>">
                                                    <input type="text" class="n_num tgt-stf4_cd" name="upDummy[manager_cd]" value="<?= $dispData['manager_cd'] ?>">
                                                    <input type="text" class="n_name tgt-stf4_name bg-gray2" name="upDummy[manager_name]" value="<?= $dispData['manager_name'] ?>" readonly="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="record4 doctor_info">
                                            <div class="tit tit_toggle">主治医情報</div>
                                            <div class="box_wrap child_toggle">

                                                <dd class="f-keyData">
                                                    <!--data-tg_url='/report/plan/ajax/doctor_ajax.php?type=doctor'>-->
                                                    <div class="copy_btn ref_doctor modal_open"
                                                         data-url="/common/dialog/InstructionCopy.php?user=<?= $dispData['user_id'] ?>&index=1" 
                                                         data-dialog_name="dynamic_modal">
                                                        指示書から反映</div>
                                                </dd>

                                                <div class="box-l">
                                                    <div class="institution">
                                                        <span class="label_t">医療機関名称</span>
                                                        <input type="text" name="upAry[hospital]" class="tgt-doc_hosp set1_hospital" value="<?= $dispData['hospital'] ?>">
                                                    </div>
                                                    <div class="physician">
                                                        <span class="label_t">主治医</span>
                                                        <input type="text" name="upAry[doctor]" class="tgt-doc_doc set1_doctor" value="<?= $dispData['doctor'] ?>">
                                                    </div>
                                                </div>
                                                <div class="box-r">
                                                    <div class="location">
                                                        <span class="label_t">所在地</span>
                                                        <input type="text" name="upAry[address]" class="tgt-doc_adr set1_address1" value="<?= $dispData['address'] ?>">
                                                    </div>
                                                    <div class="number">
                                                        <p><span class="label_t">電話番号①</span>
                                                            <input type="tel" name="upAry[tel1]" class="tgt-doc_tel1 set1_tel1" value="<?= $dispData['tel1'] ?>">
                                                        <p><span class="label_t" style="width:95px;">電話番号②</span>
                                                            <input type="tel" name="upAry[tel2]" class="tgt-doc_tel2 set1_tel2" value="<?= $dispData['tel2'] ?>">
                                                        <p><span class="label_t" style="width:95px;">FAX</span>
                                                            <input type="tel" name="upAry[fax]" class="tgt-doc_fax set1_fax" value="<?= $dispData['fax'] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

                                    <!-- ダイアログ流し込みエリア -->
                                    <div class="modal_setting"></div>

                                    <!--ダイアログ呼出し-->
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff2.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff3.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/report/plan/dialog/staff4.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/doctor.php'); ?>

                                </div>

                            </div></div>
                        <!--/// CONTENT_END ///-->
                        <div class="fixed_navi">
                            <div class="box">
                                <!--<div class="btn back pc"><button type="submit" name="btnReturn" value="true">利用者一覧にもどる</button></div>-->
                                <div class="btn back pc"><button type="submit" name="btnReturn" value="true">計画書一覧にもどる</button></div>
                                <div class="btn back sm"><a href="/report/list/index.php"><img src="/common/image/icon_return.png" alt="Return"></a></div>
                                <div class="controls">
                                    <button type="submit" class="btn save" name="btnEntry" value="保存">保存
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
            $(".addRows").click(function () {
                var tr_last = $(".rehab_box table tbody").find("tr:nth-last-child(1)");
                var tr_new = "";
                tr_new += '<tr>';
                tr_new += '    <td>';
                tr_new += '        <span class="btn trash">削除</span>';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <input type="date" name="upPrb[plan_day][]" class="" value="<?= THISMONTHFIRST ?>">';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <b>問題点</b>';
                tr_new += '        <textarea name="upPrb[problem][]" style="height:120px;" placeholder=""></textarea>';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <b>解決策</b>';
                tr_new += '        <textarea name="upPrb[solution][]" style="height:120px;" placeholder=""></textarea>';
                tr_new += '    </td>';
                tr_new += '    <td>';
                tr_new += '        <b>評価</b>';
                tr_new += '        <textarea name="upPrb[evaluation][]" style="height:120px;" placeholder=""></textarea>';
                tr_new += '    </td>';
                tr_new += '</tr>';
                $(tr_last).before(tr_new)
//                $(".date_no-Day").datepicker({dateFormat: 'yy/mm/dd'});
            });

            //末尾行削除
            $(".rehab_box").on('click', '.trash', function (event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                return false;
            });

        </script>
    </body>
</html>