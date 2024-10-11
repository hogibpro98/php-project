<?php require_once(dirname(__FILE__)."/php/report_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!--COMMON-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/common/parts/common.php'); ?>
<!--CONTENT-->
<title>記録一覧</title>
</head>

<body>
<div id="wrapper"><div id="base">
<!--HEADER-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/common/parts/header.php'); ?>
<!--CONTENT-->
<article id="content">
<!--/// CONTENT_START ///-->
<form action="" method="post" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">
<h2 class="tit_sm">記録一覧</h2>
<div id="subpage"><div id="record" class="nursing">


<div class="cont_head">
    <div class="box1">
        <div class="name_box">
            <input type="text" name="search[kana]" class="" value="<?= $search['kana'] ?>" placeholder="氏名(カナ)">
        </div>
        <div class="state">
            <select>
                <?php foreach ($gnrList['絞り込み_サービス状態'] as $key => $val): ?>
                    <?php $select = $dispData['status'] == $val ? ' selected' : NULL; ?>
                    <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                <?php endforeach; ?>
            </select>
            <p><input type="checkbox" name="search[importance]" id="importance" checked><label for="importance">重要</label></p>
            <p>
                <span class="label_t text_blue">作成状態</span>
                <span><input type="checkbox" name="search[status]" id="state1" checked><label for="state1">完成</label></span>
                <span><input type="checkbox" name="search[status]" id="state2" checked><label for="state2">作成中</label></span>
            </p>
        </div>
    </div>
    <div class="box2">
        <div class="i_period">
            <p>
                <input type="text" name="search[start_day]" class="" value="<?= $search['start_day'] ?>">
                <small>～</small>
                <input type="text" name="search[end_day]" class="" value="<?= $search['end_day'] ?>">
            </p>
            <p><span class="disp_month prev_month">前月</span><span class="disp_month this_month">当月</span></p>
        </div>
        <div class="progress">
            <p>
                <select class="p_rec">
                    <?php foreach ($gnrList['絞り込み_経過記録'] as $key => $val): ?>
                        <?php $select = $search['care_kb'] == $val ? ' selected' : NULL; ?>
                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <select class="cate1">
                    <?php foreach ($gnrList['絞り込み_内容区分1'] as $key => $val): ?>
                        <?php $select = $search['care_kb'] == $val ? ' selected' : NULL; ?>
                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="cate2">
                    <?php foreach ($gnrList['絞り込み_内容区分2'] as $key => $val): ?>
                        <?php $select = $search['care_kb'] == $val ? ' selected' : NULL; ?>
                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
        </div>
        <div class="btn_box">
            <span class="btn search s1">絞り込み</span>
            <span class="btn search s2">絞り込む(全拠点)</span>
            <span class="btn add"><a href="/report/progress/index.php">経過記録作成</a></span>
        </div>
    </div>
</div>

<div class="wrap">
    <?php $cnt = count($dispData) ?>
    <div class="dis_num">該当件数<b><?= $cnt ?></b></div>
    <?php if ($cnt): ?>
    <?php foreach ($dispData0 as $tgtDate => $ary): ?>
    <?php $tgtId = $ary['id'] ?>

    <?php if ($ary['type'] == '看多機記録'): ?>
    <?php $val = $ktkList[$tgtId]['main']; ?>
    <div class="box1">
        <div class="wrap-l">
            <table>
                <tr>
                    <th><span class="label_t text_blue">記録<br class="sm">区分</span><span class="label_t text_blue">作成状態</span></th>
                    <th><span class="label_t text_blue">利用者名</span><span class="label_t text_blue">記入者</span></th>
                    <th><span class="label_t text_blue">サービス提供<br>日時</span><span class="label_t text_blue">記録日時</span></th>
                </tr>
                <tr>
                    <td><p class="l_btn n_rec"><a href="/report/kantaki/index.php?id=<?= $tgtId ?>"><span>看多機<br>記録</span></a></p><span class="addon"><?= $val['service_kind'] ?></span></td>
                    <td><?= $val['user_name'] ?></td>
                    <td>
                        <b><?= $val['service_day'] ?></b>
                        <small><?= $val['start_time'] ?>-<?= $val['end_time'] ?></small>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php if (!empty($val['important'])): ?>
                        <span class="l_stat important">重要</span>
                        <?php endif; ?>
                        <?php if ($val['status'] == '完成'): ?>
                        <span class="l_stat complete">完成</span>
                        <?php else: ?>
                        <span class="l_stat ongoing">作成中</span>
                        <?php endif; ?>
                    </td>
                    </td>
                    <?php $name = isset($ktkStaffList[$tgtId][0]['name']) ? $ktkStaffList[$tgtId][0]['name'] : ''; ?>
                    <td><?= $name ?></td>
                    <td>
                        <b><?= $val['create_date'] ?></b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="wrap-r">
            <div class="tit tit_toggle">記録詳細</div>
            <div class="child_toggle">
                <table>
                    <tr>
                        <th colspan="4"><span class="label_t text_blue">記録詳細</span></th>
                    </tr>
                    <tr>
                        <td rowspan="2">
                            <div class="mid">身体状況</div>
                            <div class="come">
                                <!--<textarea name="upDummy[condition]" value="<?= $val['condition'] ?>" readonly><?= $val['condition'] ?></textarea>-->
                                <textarea name="upDummy[condition]" value="<?= $val['condition'] ?>" readonly></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="mid">処置内容</div>
                            <div class="come">
                                <textarea name="upDummy[measures_contents]" value="<?= $val['measures_contents'] ?>" readonly><?= $val['measures_contents'] ?></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="mid"><ご利用中の様子>(介護)</div>
                            <div class="come">
                                <textarea name="upDummy[state_care]" value="<?= $val['state_care'] ?>" readonly><?= $val['state_care'] ?></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="mid"><ご家族への連絡></div>
                            <div class="come">
                                <textarea name="upDummy[family_contact]" value="<?= $val['family_contact'] ?>" readonly><?= $val['family_contact'] ?></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="mid">その他</div>
                            <div class="come">
                                <textarea name="upDummy[other]" value="<?= $val['other'] ?>" readonly><?= $val['other'] ?></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="mid"><ご利用中の様子>(看護)</div>
                            <div class="come">
                                <textarea name="upDummy[state_nurse]" value="<?= $val['state_nurse'] ?>" readonly><?= $val['state_nurse'] ?></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="mid"><特記事項></div>
                            <div class="come">
                                <textarea name="upDummy[staff_message]" value="<?= $val['staff_message'] ?>" readonly><?= $val['staff_message'] ?></textarea>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php elseif ($ary['type'] == '経過記録'): ?>
    <?php $val = $pgsList[$tgtId]['main']; ?>
    <div class="box2">
        <div class="wrap-l">
            <table>
                <tr>
                    <td><p class="l_btn p_rec"><a href="/report/progress/index.php?id=<?= $tgtId ?>"><span>経過記録</span></a></p></td>
                    <td><?= $val['user_name'] ?></td>
                    <td>
                        <b><?= $val['target_date'] ?></b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php if (!empty($val['importantly'])): ?>
                        <span class="l_stat important">重要</span>
                        <?php endif; ?>
                        <?php if ($val['status'] == '完成'): ?>
                        <span class="l_stat complete">完成</span>
                        <?php else: ?>
                        <span class="l_stat ongoing">作成中</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="mid">内容区分1</div>
                        <div class="come"><?= $val['type1'] ?></div>
                    </td>
                    <td>
                        <div class="mid">内容区分2</div>
                        <div class="come"><?= $val['type2'] ?></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="wrap-r">
            <div class="tit tit_toggle">記録詳細</div>
            <div class="child_toggle">
                <table>
                    <tr>
                        <td colspan="2">
                            <div class="mid">件名</div>
                            <div class="come"><?= $val['title'] ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="mid">状況課題</div>
                            <div class="come">
                                <textarea name="upDummy[problem]" value="<?= $val['problem'] ?>" readonly><?= $val['problem'] ?></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="mid">指示事項</div>
                            <div class="come">
                                <textarea name="upDummy[direction]" value="<?= $val['direction'] ?>" readonly><?= $val['direction'] ?></textarea>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php elseif ($ary['type'] == '訪問看護記録1'): ?>
    <?php $val = $vst1List[$tgtId]['main']; ?>
    <div class="box5">
        <div class="wrap-l">
            <table>
                <tr>
                    <td><p class="l_btn n_rec2"><a href="/report/visit1/index.php?id=<?= $tgtId ?>"><span>看護<br>記録Ⅰ</span></a></p></td>
                    <td><?= $val['user_name'] ?></td>
                    <td>
                        <b><?= $val['first_day'] ?></b>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><?= $val['care_kb'] ?></td>
                </tr>
                <tr>
                    <td>
                        <?php if (!empty($val['status'])): ?>
                        <span class="l_stat complete">完成</span>
                        <?php else: ?>
                        <span class="l_stat ongoing">作成中</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $val['staff_name'] ?></td>
                    <td><?= $val['care_rank'] ?></td>
                </tr>
            </table>
        </div>
        <div class="wrap-r">
            <div class="tit tit_toggle">記録詳細</div>
            <div class="child_toggle">
                <table>
                    <tr>
                        <td class="w10" rowspan="2">
                            <div class="mid">主たる傷病名</div>
                            <div class="come">
                                <textarea class="w100" name="upDummy[main_sickness]" value="<?= $val['main_sickness'] ?>" readonly><?= $val['main_sickness'] ?></textarea>
                            </div>
                        </td>
                        <td class="w15">
                            <div class="mid">現病名</div>
                            <div class="come">
                                <textarea class="w100" name="upDummy[medical_record]" value="<?= $val['medical_record'] ?>" readonly><?= $val['medical_record'] ?></textarea>
                            </div>
                        </td>
                        <td class="w15" rowspan="2">
                            <div class="mid">依頼目的</div>
                            <div class="come">
                                <textarea class="w100" name="upDummy[purpose]" value="<?= $val['purpose'] ?>" readonly><?= $val['purpose'] ?></textarea>
                            </div>
                        </td>
                        <td class="w15" rowspan="2">
                            <div class="mid">療養状況</div>
                            <div class="come">
                                <textarea class="w100" name="upDummy[treatment]" value="<?= $val['treatment'] ?>" readonly><?= $val['treatment'] ?></textarea>
                            </div>
                        </td>
                        <td class="w15" rowspan="2">
                            <div class="mid">介護状況</div>
                            <div class="come">
                                <textarea class="w100" name="upDummy[care]" value="<?= $val['care'] ?>" readonly><?= $val['care'] ?></textarea>
                            </div>
                        </td>
                        <td class="w15" rowspan="2">
                            <div class="mid">生活歴</div>
                            <div class="come">
                                <textarea class="w100" name="upDummy[life]" value="<?= $val['life'] ?>" readonly><?= $val['life'] ?></textarea>
                            </div>
                        </td>
                        <td class="w15" rowspan="2">
                            <div class="mid">主な介護者</div>
                            <div class="come">
                                <textarea class="w100" name="upDummy[main_caregiver]" value="<?= $val['main_caregiver'] ?>" readonly><?= $val['main_caregiver'] ?></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="w15">
                            <div class="mid">既往歴</div>
                            <div class="come">
                                <textarea class="w100" name="upDummy[past_history]" value="<?= $val['past_history'] ?>" readonly><?= $val['past_history'] ?></textarea>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php elseif ($ary['type'] == '訪問看護記録2'): ?>
    <?php $val = $vst2List[$tgtId]['main']; ?>
    <div class="box3">
        <div class="wrap-l">
            <table>
                <tr>
                    <td><p class="l_btn n_rec2"><a href="/report/visit2/index.php?id=<?= $tgtId ?>"><span>看護<br>記録Ⅱ</span></a></p></td>
                    <td><?= $val['user_name'] ?></td>
                    <td>
                        <b><?= $val['service_day'] ?></b>
                        <small><?= $val['start_time'] ?>-<?= $val['end_time'] ?></small>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php if (!empty($val['importantly'])): ?>
                        <span class="l_stat important">重要</span>
                        <?php endif; ?>
                        <?php if ($val['status'] == '完成'): ?>
                        <span class="l_stat complete">完成</span>
                        <?php else: ?>
                        <span class="l_stat ongoing">作成中</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $val['staff_name'] ?></td>
                    <td>
                        <b><?= $val['create_date'] ?></b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="wrap-r">
            <div class="tit tit_toggle">記録詳細</div>
            <div class="child_toggle">
                <table>
                    <tr>
                        <td>
                            <div class="mid">身体状況</div>
                            <div class="come">
                                <textarea name="upDummy[condition]" value="<?= $val['condition'] ?>" readonly><?= $val['condition'] ?></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="come">
                                <?php if (isset($vst2PrbList[$tgtId]['problem'])): ?>
                                    <textarea name="upDummy[problem]" value="<?= $vst2PrbList[$tgtId]['problem'] ?>" readonly><?= $vst2PrbList[$tgtId]['problem'] ?></textarea>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
<!--    <div id="pager">
        <div class="active">1</div>
        <div>2</div>
    </div>-->
    <!-- ページャー -->
    <?php // dispPager($tgtData, $page, $line, $server['requestUri']) ?>
    <?php dispPager($dispData, $page, $line, $server['requestUri']) ?>
</div>


</div></div>
<!--/// CONTENT_END ///-->
</form>
</article>
<!--CONTENT-->
</div></div>
<p id="page"><a href="#wrapper">PAGE TOP</a></p>
</body>
</html>