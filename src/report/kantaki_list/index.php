<?php require_once(dirname(__FILE__) . "/php/list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>看多機記録一覧</title>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <h2>看多機記録一覧</h2>
                    <div id="subpage">
                        <div id="log" class="nursing">
                            <span class="btn edit" style="min-width:130px;text-align: center;"><a href="/report/bedsore_list/index.php">褥瘡計画一覧</a></span>
                            <span class="btn edit" style="min-width:130px;text-align: center;"><a href="/report/instruct_list/index.php">指示書一覧</a></span>
                            <span class="btn edit" style="min-width:130px;text-align: center;"><a href="/report/plan_list/index.php">計画書一覧</a></span>
                            <span class="btn edit" style="min-width:130px;text-align: center;"><a href="/report/report_list2/index.php">報告書一覧</a></span>
                            <span class="btn edit" style="min-width:130px;text-align: center;"><a href="/report/progress_list/index.php">経過記録一覧</a></span>
                            <span class="btn edit" style="min-width:130px;text-align: center;"><a href="/report/kantaki_list/index.php">看多機記録一覧</a></span>
                            <span class="btn edit" style="min-width:160px;text-align: center;"><a href="/report/visit1_list/index.php">訪問看護記録Ⅰ一覧</a></span>
                            <span class="btn edit" style="min-width:160px;text-align: center;"><a href="/report/visit2_list/index.php">訪問看護記録Ⅱ一覧</a></span>
                            <form action="" class="p-form-validate" method="">
                                <div class="log-details" style="margin-top:20px;">
                                    <div class="box1">
                                        <div class="date_period">
                                            <span class="label_t text_blue">対象期間</span>
                                            <input type="date" name="search[start_day]" class="" value="<?= $search['start_day'] ?>">
                                            <small>～</small>
                                            <input type="date" name="search[end_day]" class="" value="<?= $search['end_day'] ?>">
                                        </div>
                                    </div>
                                    <div class="box2" style="flex-basis:17.0%;">
                                        <div class="name_box">
                                            <span class="label_t text_blue" stype="width:100px;">氏名(カナ)</span>
                                            <input type="text" name="search[kana]" class="" value="<?= $search['kana'] ?>" placeholder="氏名(カナ)">
                                        </div>
                                    </div>
                                    <div class="box2" style="flex-basis:17.0%;">
                                        <div class="date_period">
                                            <span class="label_t text_blue">作成状態</span>
                                            <span><input type="checkbox" name="search[status1]" value="完成" id="state1" <?= $search['status1'] === "完成" ? 'checked' : '' ?>><label for="state1">完成</label></span>
                                            <span><input type="checkbox" name="search[status2]" value="作成中" id="state2" <?= $search['status2'] === "作成中" ? 'checked' : '' ?>><label for="state2">作成中</label></span>
                                        </div>
                                    </div>
                                    <div class="box2" style="flex-basis:17.0%;">
                                        <div class="date_period">
                                            <span class="label_t text_blue">重要</span>
                                            <span><input type="checkbox" name="search[important]" value="重要" id="important" <?= $search['important'] === "重要" ? 'checked' : '' ?>><label for="importantly">重要</label></span>
                                        </div>
                                    </div>

                                    <div class="s_control">
                                        <input type="submit" name="btnSearch" value="絞り込み" class="btn search">
                                    </div>
                                    <div class="s_control">
                                        <span class="btn add"  style="padding:2px;"><a href="/report/kantaki/index.php">新規作成</a></span>
                                    </div>
                                </div>
                            </form>
                            <div class="wrap">
                                <div class="dis_num">該当件数<b><?= count($tgtData) ?></b></div>
                                <table class="dis_result">
                                    <tr>
                                        <th class="">No</th>
                                        <th class="">帳票名</th>
                                        <th class="">作成日時</th>
                                        <th class="">利用者</th>
                                        <th class="">担当者</th>
                                        <th class="">ステータス</th>
                                        <th class="">重要</th>
                                        <th class=""></th>
                                    </tr>
                                    <?php $i = 0; ?>
                                    <?php foreach ($dispData as $keyId => $val) : ?>
                                        <?php $i++; ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $val['report_name'] ?></td>
                                            <td><?= $val['create_date'] ?></td>
                                            <td><?= $val['user_name'] ?></td>
                                            <td><?= $val['person_name'] ?></td>
                                            <td><?= $val['status'] ?></td>
                                            <td><?= $val['important'] ?></td>
                                            <td>
                                                <p class="btn_box">
                                                    <span class="btn edit" style="padding:2px;">
                                                        <a href="<?= $val['edit_url'] ?>">編集</a>
                                                    </span>
                                                    <span class="btn duplicate" style="padding:2px;">
                                                        <a href="<?= $val['copy_url'] ?>">複製</a>
                                                    </span>
                                                </p>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <?php dispPager($tgtData, $page, $line, $server['requestUri']) ?>
                        </div></div>
                    <!--/// CONTENT_END ///-->
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>