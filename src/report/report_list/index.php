<?php require_once(dirname(__FILE__)."/php/report_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!--COMMON-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/common/parts/common.php'); ?>
<style>
    #loading {
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000; /* Ensure it's above other content */
}
</style>
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
<form name="searchForm" id="searchForm" action="" method="get" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">
<input type="hidden" id="branchType_search" name="branchType_search" value="">
<h2 class="tit_sm">記録一覧</h2>
<div id="subpage"><div id="record" class="nursing">


<div class="cont_head">
    <!-- Add this within your HTML where it makes sense, like inside the body tag or a container div -->
    <div id="loading" style="display:none;">
        <img src="/common/image/loading.gif" alt="Loading...">
    </div>
    <div class="box1">
        <div class="name_box">
            <input type="text" name="search[kana]" class="" value="<?= $search['kana'] ?>" placeholder="氏名(カナ)">
        </div>
        <div class="state">
            <select name="search[status_user]"  id="status_user">
                <?php foreach ($gnrList['絞り込み_サービス状態'] as $key => $val): ?>
                    <?php $select = @$search['status_user'] == @$val ? ' selected' : NULL; ?>
                    <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                <?php endforeach; ?>
            </select>
            <?php $select_imp = @$search['importance'] == 1 ? ' checked' : NULL; ?>
            <p><input type="checkbox" name="search[importance]" id="importance" value="1" <?= $select_imp?>><label for="importance">重要</label></p>
            <p>
                <?php   $select_imp1 = @$search['status1'] == "完成" ? ' checked' : NULL;
                        $select_imp2 = @$search['status2'] == "作成中" ? ' checked' : NULL; 
                ?>
                <span class="label_t text_blue">作成状態</span>
                <span><input type="checkbox" name="search[status1]" value="完成" id="state1" <?= $select_imp1?>><label for="state1">完成</label></span>
                <span><input type="checkbox" name="search[status2]" value="作成中" id="state2" <?= $select_imp2?>><label for="state2">作成中</label></span>
            </p>
        </div>
    </div>
    <div class="box2">
        <div class="i_period">
            <p>
                <input type="date" name="search[start_day]" id="start_day" class="" value="<?= $search['start_day']?>">
                <small>～</small>
                <input type="date" name="search[end_day]" id="end_day" class="" value="<?= $search['end_day']?>">
            </p>
            <p>
                <span class="disp_month prev_month">前月</span>
                <span class="disp_month this_month">当月</span>
            </p>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const prevMonthButton = document.querySelector('.prev_month');
                const thisMonthButton = document.querySelector('.this_month');
                const startDayInput = document.getElementById('start_day');
                const endDayInput = document.getElementById('end_day');

                function formatDate(date) {
                    let month = '' + (date.getMonth() + 1);
                    let day = '' + date.getDate();
                    const year = date.getFullYear();

                    if (month.length < 2) month = '0' + month;
                    if (day.length < 2) day = '0' + day;

                    return [year, month, day].join('-');
                }

                prevMonthButton.addEventListener('click', function() {
                    const today = new Date();
                    const prevMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    const prevMonthStart = new Date(prevMonth.getFullYear(), prevMonth.getMonth(), 1);
                    const prevMonthEnd = new Date(prevMonth.getFullYear(), prevMonth.getMonth() + 1, 0);

                    startDayInput.value = formatDate(prevMonthStart);
                    endDayInput.value = formatDate(prevMonthEnd);
                });

                thisMonthButton.addEventListener('click', function() {
                    const today = new Date();
                    const thisMonthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                    const thisMonthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);

                    startDayInput.value = formatDate(thisMonthStart);
                    endDayInput.value = formatDate(thisMonthEnd);
                });
            });
        </script>
        <div class="progress">
            <p>
                <select class="p_rec" name="search[care_kb]">
                    <?php foreach ($gnrList['絞り込み_経過記録'] as $key => $val): ?>
                        <?php $select = $search['care_kb'] == $val ? ' selected' : NULL; ?>
                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <select class="cate1" name="search[care_kb_type1]">
                    <option value="">全て</option>
                    <?php foreach ($gnrList['絞り込み_内容区分1'] as $key => $val): ?>
                        <?php $select = $search['care_kb_type1'] == $val ? ' selected' : NULL; ?>
                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
                <select class="cate2" name="search[care_kb_type2]">
                    <option value="">全て</option>
                    <?php foreach ($gnrList['絞り込み_内容区分2'] as $key => $val): ?>
                        <?php $select = $search['care_kb_type2'] == $val ? ' selected' : NULL; ?>
                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
        </div>
        <div class="btn_box">
            <span class=""><button type="submit" name="btnSearch" id="btnSearchBranch" value="true" class="btn search s1">絞り込み</button></span>
            <!-- <span class="btn search s1">絞り込み</span> -->
            <span class=""><button type="submit" name="btnSearch_all" value="true" id="btnSearchAllBranches" class="btn search s2">絞り込む(全拠点)</button></span>
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
                                <!-- <textarea value="<?= @$val['condition'] ?>" readonly></textarea> -->
                                <?= @$val['condition'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="mid">処置内容</div>
                            <div class="come">
                                <!-- <textarea value="<?= $val['measures_contents'] ?>" readonly><?= $val['measures_contents'] ?></textarea> -->
                                <?= $val['measures_contents'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="mid"><ご利用中の様子>(介護)</div>
                            <div class="come">
                                <!-- <textarea value="<?= $val['state_care'] ?>" readonly><?= $val['state_care'] ?></textarea> -->
                                <?= $val['state_care'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="mid"><ご家族への連絡></div>
                            <div class="come">
                                <!-- <textarea value="<?= $val['family_contact'] ?>" readonly><?= $val['family_contact'] ?></textarea> -->
                                <?= $val['family_contact'] ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="mid">その他</div>
                            <div class="come">
                                <!-- <textarea value="<?= $val['other'] ?>" readonly><?= $val['other'] ?></textarea> -->
                                <?= $val['other'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="mid"><ご利用中の様子>(看護)</div>
                            <div class="come">
                                <!-- <textarea value="<?= $val['state_nurse'] ?>" readonly><?= $val['state_nurse'] ?></textarea> -->
                                <?= $val['state_nurse'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="mid"><特記事項></div>
                            <div class="come">
                                <!-- <textarea value="<?= $val['staff_message'] ?>" readonly><?= $val['staff_message'] ?></textarea> -->
                                <?= $val['staff_message'] ?>
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
                                <!-- <textarea value="<?= $val['problem'] ?>" readonly><?= $val['problem'] ?></textarea> -->
                                <?= $val['problem'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="mid">指示事項</div>
                            <div class="come">
                                <!-- <textarea value="<?= $val['direction'] ?>" readonly><?= $val['direction'] ?></textarea> -->
                                <?= $val['direction'] ?>
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
                    <?php if ($val['status'] == '完成'): ?>
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
                                <!-- <textarea class="w100" value="<?= $val['main_sickness'] ?>" readonly><?= $val['main_sickness'] ?></textarea> -->
                                <?= $val['main_sickness'] ?>
                            </div>
                        </td>
                        <td class="w15">
                            <div class="mid">現病名</div>
                            <div class="come">
                                <!-- <textarea class="w100" value="<?= $val['medical_record'] ?>" readonly><?= $val['medical_record'] ?></textarea> -->
                                <?= $val['medical_record'] ?>
                            </div>
                        </td>
                        <td class="w15" rowspan="2">
                            <div class="mid">依頼目的</div>
                            <div class="come">
                                <!-- <textarea class="w100" value="<?= $val['purpose'] ?>" readonly><?= $val['purpose'] ?></textarea> -->
                                <?= $val['purpose'] ?>
                            </div>
                        </td>
                        <td class="w15" rowspan="2">
                            <div class="mid">療養状況</div>
                            <div class="come">
                                <!-- <textarea class="w100" value="<?= $val['treatment'] ?>" readonly><?= $val['treatment'] ?></textarea> -->
                                <?= $val['treatment'] ?>
                            </div>
                        </td>
                        <td class="w15" rowspan="2">
                            <div class="mid">介護状況</div>
                            <div class="come">
                                <!-- <textarea class="w100" value="<?= $val['care'] ?>" readonly><?= $val['care'] ?></textarea> -->
                                <?= $val['care'] ?>
                            </div>
                        </td>
                        <td class="w15" rowspan="2">
                            <div class="mid">生活歴</div>
                            <div class="come">
                                <!-- <textarea class="w100" value="<?= $val['life'] ?>" readonly><?= $val['life'] ?></textarea> -->
                                <?= $val['life'] ?>
                            </div>
                        </td>
                        <td class="w15" rowspan="2">
                            <div class="mid">主な介護者</div>
                            <div class="come">
                                <!-- <textarea class="w100" value="<?= $val['main_caregiver'] ?>" readonly><?= $val['main_caregiver'] ?></textarea> -->
                                <?= $val['main_caregiver'] ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="w15">
                            <div class="mid">既往歴</div>
                            <div class="come">
                                <!-- <textarea class="w100" value="<?= $val['past_history'] ?>" readonly><?= $val['past_history'] ?></textarea> -->
                                <?= $val['past_history'] ?>
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
                                <!-- <textarea value="<?= $val['condition'] ?>" readonly><?= $val['condition'] ?></textarea> -->
                                <?= $val['condition'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="come">
                                <?php if (isset($vst2PrbList[$tgtId]['problem'])): ?>
                                    <!-- <textarea value="<?= $vst2PrbList[$tgtId]['problem'] ?>" readonly><?= $vst2PrbList[$tgtId]['problem'] ?></textarea> -->
                                    <?= $vst2PrbList[$tgtId]['problem'] ?>
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
    <?php if($cnt >= 1) { ?>
    <?php dispPager($dispData, $page, $line, $server['requestUri']) ?>
    <?php } ?>
</div>


</div></div>
<!--/// CONTENT_END ///-->
</form>
</article>
<!--CONTENT-->
</div></div>
<p id="page"><a href="#wrapper">PAGE TOP</a></p>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function showLoading() {
        $('#loading').show(); // Show the loading image
    }

    $('#btnSearchBranch').click(function(e) {
        e.preventDefault(); // Prevent the default form submission
        showLoading(); // Show loading image
        $('#branchType_search').val('branch'); // Set hidden input value
        $('#searchForm').submit(); // Submit the form
    });

    $('#btnSearchAllBranches').click(function(e) {
        e.preventDefault(); // Prevent the default form submission
        showLoading(); // Show loading image
        $('#branchType_search').val('all'); // Set hidden input value
        $('#searchForm').submit(); // Submit the form
    });
});
</script>
</body>
</html>