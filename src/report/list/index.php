<?php require_once(dirname(__FILE__) . "/php/list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>帳票・記録一覧</title>
    </head>

    <body>
        <div id="wrapper">
            <div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <h2>帳票・記録一覧</h2>
                    <div id="subpage">
                        <div id="" class="nursing">
                            <div class="wrap">
                                <ul class="user-tab">
                                    <li><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
                                    <li class="active"><a href="/report/list/?user=<?= $userId ?>">各種帳票</a></li>
                                    <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
                                </ul>
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
                                        <div class="log-details" style="margin-top:20px">
                                            <div class="box1">
                                                <div class="date_period">
                                                    <span class="label_t text_blue" style="min-width:90px;padding:5px;line-height:35px;text-align: center;">対象期間</span>
                                                    <input type="date" name="search[start_day]" class="" value="<?= $search['start_day'] ?>" style="margin-left:3px;">
                                                    <small>～</small>
                                                    <input type="date" name="search[end_day]" class="" value="<?= $search['end_day'] ?>">
                                                </div>
                                            </div>
                                            <div class="box2" style="margin-left:30px;">
                                                <span class="label_t text_blue" style="padding:10px;">帳票</span>
                                                <select name="search[report]">
                                                    <option value=""></option>
                                                    <?php foreach ($reportNames as $reportName => $dummy) : ?>
                                                        <?php $selected = $search['report'] == $reportName ? ' selected' : ''; ?>
                                                        <option value="<?= $reportName ?>"<?= $selected ?>><?= $reportName ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span style="margin-left:30px;">
                                                    <input type="checkbox" name="search[status]" id="status" value="1" <?= (!empty($search['status'])) ? 'checked' : '' ?>>
                                                    <label for="status">完成も表示</label>
                                                </span>
                                            </div>
                                            <div class="s_control" style="margin-left:30px;">
                                                <input type="submit" name="btnSearch" value="絞り込み" class="btn search">
                                            </div>
                                        </div>
                                    </form>
                                    <div class="wrap">
                                        <div class="dis_num">該当件数<b><?= count($tgtData) ?></b></div>
                                        <table class="dis_result">
                                            <tr style="height:40px;">
                                                <th class="">No</th>
                                                <th class="">帳票名</th>
                                                <th class="">作成日時</th>
                                                <th class="">利用者</th>
                                                <th class="">担当者</th>
                                                <th class="">ステータス</th>
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
                                                    <td>
                                                        <p class="btn_box">
                                                            <span class="btn edit">
                                                                <a href="<?= $val['edit_url'] ?>">編集</a>
                                                            </span>
                                                            <span class="btn edit duplicate" style="height:42px;margin-left:10px;">
                                                                <a href="<?= $val['copy_url'] ?>" style="height:42px;">複製</a>
                                                            </span>
                                                        </p>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </div>
                                    <?php dispPager($tgtData, $page, $line, $server['requestUri']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/// CONTENT_END ///-->
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>