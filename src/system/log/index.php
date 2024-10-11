<?php require_once(dirname(__FILE__) . "/php/log_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>ログ管理</title>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <h2>ログ管理</h2>
                    <div id="subpage"><div id="log" class="nursing">

                            <form action="" class="p-form-validate" method="">
                                <div class="log-details">
                                    <div class="box1">
                                        <div class="date_period">
                                            <span class="label_t text_blue">対象期間</span>
                                            <input type="date" name="search[start_day]" class="" value="<?= $search['start_day'] ?>">
                                            <small>～</small>
                                            <input type="date" name="search[end_day]" class="" value="<?= $search['end_day'] ?>">
                                        </div>
                                    </div>
                                    <div class="box2">
                                        <span class="label_t text_blue">画面</span>
                                        <select name="search[screen]">
                                            <option value=""></option>
                                            <?php foreach ($screenNames as $scName => $dummy) : ?>
                                            <?php $selected = $search['screen'] == $scName ? ' selected' : '';  ?>
                                            <option value="<?= $scName ?>"<?= $selected ?>><?= $scName ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="s_control">
                                        <input type="submit" name="btnSearch" value="絞り込み" class="btn search">
                                    </div>
                                </div>
                            </form>

                            <div class="wrap">
                                <div class="dis_num">該当件数<b><?= count($tgtData) ?></b></div>
                                <table class="dis_result">
                                    <tr>
                                        <th class="">No</th>
                                        <th class="th_rec_date">記録日時</th>
                                        <!--<th class="th_gamen">利用者</th>-->
                                        <th class="th_gamen">画面</th>
                                        <th class="th_detail">登録内容</th>
                                    </tr>
                                    <?php $i = 0; ?>
                                    <?php foreach ($dispData as $keyId => $val) : ?>
                                        <?php $i++; ?>
                                        <tr>
                                            <td style="min-width:50px !important; padding:0px;"><?= $i ?></td>
                                            <td style="min-width:150px !important;word-wrap: break-word !important;"><?= $val['create_date'] ?></td>
                                            <td style="min-width:200px !important;word-wrap: break-word !important;"><?= $val['screen'] ?></td>
                                            <td style="min-width:700px !important;display: inline-block; overflow-wrap: break-word !important;"><?= $val['entry_data'] ?></td>
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