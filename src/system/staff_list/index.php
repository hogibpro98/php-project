<?php require_once(dirname(__FILE__) . "/php/staff_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>従業員一覧</title>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <h2>従業員一覧</h2>
                    <div id="subpage"><div id="staff-list" class="nursing">

                            <div class="search_drop sm">検索</div>
                            <form action="" class="p-form-validate" method="get">
                                <div class="user-details nurse_record">
                                    <div class="affliation">
                                        <select name="sAry[place]">
                                            <option disabled <?= $dispSearch['place'] == '' ? 'selected' : '' ?> hidden>所属拠点</option>
                                            <option value="" ></option>
                                            <?php foreach ($dispSearch['placelist'] as $val): ?>
                                                <option value="<?= $val['unique_id'] ?>" <?= $dispSearch['place'] == $val['unique_id'] ? 'selected' : '' ?>><?= $val['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="shain_info">
                                        <span><label for="emp_ID">社員ID</label><input type="text" name="sAry[staff_id]" id="emp_ID" value="<?= $dispSearch['staff_id'] ?>" style="width:130px"></span>
                                        <span><label for="emp_name">氏名</label><input type="text" name="sAry[name]" id="emp_name" value="<?= $dispSearch['name'] ?>"></span>
                                        <span><input type="checkbox" name="sAry[retired]" id="dis_retiree" value="1" <?= ($dispSearch['retired'] != '') ? 'checked' : '' ?>><label for="dis_retiree">退職者も表示</label></span>
                                    </div>
                                    <div class="s_control">
                                        <button type="submit" name="btnSearch" value="true" class="btn search" style="margin-left:20px;">絞り込み</button>
                                        <button type="submit" name="btnClear" value="true" class="btn clear">クリア</button>
                                    </div>
                                    <div class="outpg_control">
                                            <!-- <span class="btn excel">Excel出力</span> -->
                                        <span class="btn add add2"><a href="/system/staff_edit/">新規従業員登録</a></span>
                                        <!-- TODO:リスト内の編集ボタンが利用できるようになったら下記ボタンは削除する -->
                                        <!--<span class="btn add add2"><a href="/system/staff_edit?id=stff0001">DMY:staff0001編集</a></span>-->
                                    </div>
                                </div>
                            </form>
                            <form action="" class="p-form-validate" method="post">
                            <div class="wrap">
                                <div class="dis_num">該当件数<b><?= is_null($dispData) ? 0 : count($dispData) ?></b></div>
                                <div class="dis_box"> 
                                    <table class="dis_result">
                                        <tr>
                                            <th class="th_emp-id">社員ID</th>
                                            <th class="th_name">氏名(漢字)</th>
                                            <th class="th_name-kana">氏名(カナ)</th>
                                            <th class="th_pri-role">第１役割</th>
                                            <th class="th_affliate-base">所属拠点</th>
                                            <th class="th_affliate-office">所属事業所</th>
                                            <th class="th_billing-shikaku">請求用資格</th>
                                            <th class="th_sys-kengen">システム権限</th>
                                            <th class="th_shain-kubun">社員区分</th>
                                            <th class="th_em-contact">緊急連絡先</th>
                                            <th class="th_retire">退職</th>
                                            <th class="th_edit"></th>
                                            <th class="th_delete"></th>
                                        </tr>
                                        <?php foreach ($dispData as $tgtId => $val): ?>
                                            <tr>
                                                <td><?= $val['staff_id'] ?></td>
                                                <td><?= $val['last_name'] . ' ' . $val['first_name'] ?></td>
                                                <td><?= $val['last_kana'] . ' ' . $val['first_kana'] ?></td>
                                                <td><?= $val['role1'] ?></td>
                                                <td><?= trimStrWidth($val['place_name']) ?></td>
                                                <td><?= trimStrWidth($val['office_name']) ?></td>
                                                <td><?= $val['license1'] ?></td>
                                                <td><?= $val['type'] ?></td>
                                                <td><?= $val['employee_type'] ?></td>
                                                <td><?= $val['emg_tel'] ?></td>
                                                <td style="min-width:60px;"><?= $val['retired'] == '1' ? '<div class="retire_btn">退職</div>' : '' ?></td>
                                                <td><span class="btn edit" style="padding: 0px;font-size:14px;text-align: center;"><a href="/system/staff_edit?id=<?= $tgtId ?>">編集</a></span></td>
                                                <td><button class="btn delete" name="btnDel" value="<?= $tgtId ?>" style="margin-left:10px; font-size:14px; height:36px;">削除</button></td> 
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div> 
                            </div>
                            </form>
                            <!-- ページャー -->
                            <?php dispPager($tgtData, $page, $line, $server['requestUri']) ?>

                        </div></div>
                    <!--/// CONTENT_END ///-->
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>