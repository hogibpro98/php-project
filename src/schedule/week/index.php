<?php require_once(dirname(__FILE__) . "/php/week.php"); ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <!--COMMON-->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
    <link rel="stylesheet" href="./css/jquery.skeduler.css" type="text/css">
    <link rel="stylesheet" href="./css/week.css" type="text/css">
    <script src="./js/jquery.skeduler.js"></script>
    <script src="./js/main.js"></script>
    <script src="./js/week.js"></script>
    <!--CONTENT-->
    <title>週間スケジュール</title>
</head>
<body>
    <!-- <div id="wrapper"> -->
    <div id="base">
        <!--HEADER-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
        <!--CONTENT-->
        <article id="content">
            <!--/// CONTENT_START ///-->
            <form action="" class="p-form-validate" name="schedule" method="post">
                <h2>週間スケジュール</h2>
                <div id="subpage">
                    <div class="c_wrap">
                        <div id="schedule" class="nursing calendar_data">
                            <div class="search_drop sm">検索</div>
                            <div class="user-details nurse_record">
                                <div class="user">
                                    <span class="label_t text_blue">利用者</span>
                                    <p class="n_search user_search">Search</p>
                                    <input id="tgt-other_id" type="text" name="search[other_id]" class="n_num tgt-usr_id" value="<?= $search['other_id']; ?>" maxlength="7" pattern="^[0-9]+$" autocomplete="off">
                                    <input id="tgt-unique_id" type="hidden" name="search[user_id]" class="n_num tgt-unique_id" value="<?= $search['user_id'] ?>">
                                    <input type="text" name="search[user_name]" value="<?= $search['user_name']; ?>" class="n_name tgt-usr_name bg-gray2" style="width:200px;" readonly>
                                    <!-- <button type="submit" name="btnSearch" value="true" id="btnReloaded" class="btn search reloaded">再表示</button> -->
                                </div>
                                <!--ダイアログ呼出し-->
                                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/schedule/week/dialog/user.php'); ?>
                                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user2.php'); ?>
                                <div class="create_event">

                                    <div><span id="newSchedule" class="modal_open btn add add2 display_part" data-url="/schedule/week/dialog/edit_dialog.php?user=<?= $search['user_id'] ?>&place=<?= $placeId ?>" data-dialog_name="modal">予定追加</span></div>
                                    <!-- ダイアログ流し込みエリア -->
                                    <div class="modal_setting table_grp grp3"></div>

                                    <!-- 別利用者へスケジュールを複製するstart -->
                                    <div>
                                        <!-- <span class="btn duplicate d_modal">この週間スケジュールを別の利用者に複製</span> -->
                                        <button type="button" id="duplicate"  class="btn duplicate d_modal2">この週間スケジュールを別の利用者に複製</button>
                                        <div class="new_default dupli_modal cancel_act">
                                            <div class="sched_tit">週間スケジュール複製</div>
                                            <div class="s_detail">
                                                <div class="box1">
                                                    <p class="mid">利用者</p>
                                                    <p>
                                                        <span class="n_search user2_search">Search</span>
                                                        <input id="tgt-unique_id" type="hidden" name="upCopyUser[user_id]" class="n_num tgt-usr2_id" value="">
                                                        <input type="text" name="upCopyUser[other_id]" class="n_num tgt-usr2_code" value="">
                                                        <input type="text" name="upCopyUser[name]" class="n_name tgt-usr2_name bg-gray2" value="" readonly>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="s_constrols">
                                                <p><span class="btn cancel">キャンセル</span></p>
                                                <!-- <p><span class="btn duplicate">複製する</span></p> -->
                                                <button type="submit" name="btnCopyUser" class="btn duplicate tgt-usr2_id" value="">複製する</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 別利用者へスケジュールを複製するend -->
                                </div>
                                <div class="d_right">
                                    <div class="category">
                                        <span class="label_t">展開条件</span>
                                        <span class="label_t">展開方法</span>
                                        <?php $check = $search['type'] != 2 ? ' checked' : null; ?>
                                        <input type="radio" name="search[type]" value="1" id="差分のみ展開"<?= $check ?>>
                                        <label for="差分のみ展開">差分のみ展開</label>
                                        <?php $check = $search['type'] == 2 ? ' checked' : null; ?>
                                        <input type="radio" name="search[type]" value="2" id="既存削除後に上書き"<?= $check ?>>
                                        <label for="既存削除後に上書き">既存削除後に上書き</label>
                                    </div>
                                    <div class="i_period">
                                        <span class="label_t">展開範囲</span>
                                        <p><input type="date" name="search[start_day]" class="month_from" value="<?= $search['start_day'] ?>">
                                            <small>～</small>
                                            <input type="date" name="search[end_day]" class="month_to" value="<?= $search['end_day'] ?>">
                                        </p>
                                        <p>
                                            <input type="button" name="当月" class="month btn_prev_mon" value="当月">
                                            <input type="button" name="翌月" class="month btn_next_mon" value="翌月">
                                        </p>
                                        <!--<span class="btn deploy">展開</span>-->
                                        <button type="submit" class="btn deploy" name="btnMakePlan" value="true">展開</button>
                                    </div>
                                </div>
                            </div>
                            <!--ルート未割当表示-->
                            <div class="route_tit">ルート未割当 <b><?= $rootNotSetCount ?></b> ルート</div>
                            <!-- タイムスケジューラエリア -->
                            <div class="c_wrap">
                                <div id="skeduler-container" class="skeduler-container" style="overflow:visible !important;">
                                    <div class="skeduler-headers" style="user-select:none;position:sticky;">
                                        <?php foreach ($weekList as $weekName) : ?>
                                            <div style="padding-right:9px;" readonly><?= $weekName ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="skeduler-main">
                                        <div class="skeduler-main-timeline" style="user-select: none;">
                                            <?php foreach ($timeScaleList as $time) : ?>
                                                <div draggable="false" id="<?= $time ?>"><?= $time ?></div>
                                                <div draggable="false"></div>
                                                <div draggable="false"></div>
                                                <div draggable="false"></div>
                                                <div draggable="false"></div>
                                                <div draggable="false"></div>
                                                <div draggable="false"></div>
                                                <div draggable="false"></div>
                                                <div draggable="false"></div>
                                                <div draggable="false"></div>
                                                <div draggable="false"></div>
                                                <div draggable="false"></div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="skeduler-main-body">
                                            <?php foreach ($weekList as $weekName) : ?>
                                                <div>
                                                    <div class="skeduler-task-placeholder"></div>
                                                    <?php for ($i = 0; $i < COUNT($timeScaleList) * 12; $i++) : ?>
                                                        <?php
                                                        $totalMin = $i * 5;
                                                        $hour = (int) ($totalMin / 60);
                                                        $min = (int) ($totalMin % 60);
                                                        $startTime = sprintf("%02d:%02d", $hour, $min);
                                                        ?>
                                                        <div class="skeduler-cell" data-time="<?= strval($i * 5) ?>" data-root-name="<?= $weekName ?>" data-start-time="<?= $startTime ?>" style="padding-right:10px;" draggable="false">
                                                            <?php if (isset($dispData[$weekName]) === true) : ?>
                                                                <?php foreach ($dispData[$weekName] as $val_start_time => $scdData) : ?>
                                                                    <?php foreach ($scdData as $scdCd => $scdList) : ?>
                                                                        <?php $mainList = $scdList['main'] ?>
                                                                        <?php if ($startTime === $mainList['disp_start']) : ?>
                                                                            <?php
                                                                            $endTime = $mainList['disp_end'];
                                                                            $startSplit = explode(":", $startTime);
                                                                            $endSplit = explode(":", $endTime);
                                                                            $startCnvMin = (int) $startSplit[0] * 60 + (int) $startSplit[1];
                                                                            $endCnvMin = (int) $endSplit[0] * 60 + (int) $endSplit[1];
                                                                            $top = $startCnvMin / 5 * 32;
                                                                            $height = ($endCnvMin - $startCnvMin) / 5 * 31 - 4;
                                                                            ?>
                                                                            <div id="item" class="data data-grn" draggable="true" style="height: <?= $height ?>px; max-width: 152px; opacity: 0.6;" data-schedule-type="week" data-schedule-id="<?= $mainList['unique_id'] ?>" data-root-name="<?= $weekName ?>" data-start-time="<?= $startTime ?>" data-end-time="<?= $endTime ?>" title="<?= $startTime ?> - <?= $endTime ?>" data-hmtg-flg="<?= $mainList['hmtg_flg'] ?>" data-url="/schedule/week/dialog/edit_dialog.php?id=<?= $mainList['unique_id'] . "&user=" . $mainList['user_id'] ?>" data-dialog_name="modal">
                                                                                <div>
                                                                                    <input type="hidden" id="hmtg_flg" value="<?= $val['hmtg_flg'] ?>">
                                                                                    <span id="start_time" class="d_time"><?= $mainList['disp_start'] ?></span>
                                                                                    <span class="d_time">~</span>
                                                                                    <span id="end_time" class="d_time"><?= $mainList['disp_end'] ?></span>
                                                                                    <br />
                                                                                    <span class=""><?= $mainList['service_type'] ?></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span id="root_name" class=""><?= $mainList['service_name'] ?></span>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <?php $svcList = isset($scdList['service']) ? $scdList['service'] : array(); ?>
                                                                        <?php foreach ($svcList as $svcCd => $val) : ?>
                                                                            <?php if ($startTime === $val['disp_start']) : ?>
                                                                                <?php
                                                                                $endTime = $val['disp_end'];
                                                                                $startSplit = explode(":", $startTime);
                                                                                $endSplit = explode(":", $endTime);
                                                                                $startCnvMin = (int) $startSplit[0] * 60 + (int) $startSplit[1];
                                                                                $endCnvMin = (int) $endSplit[0] * 60 + (int) $endSplit[1];
                                                                                $top = $startCnvMin / 5 * 32;
                                                                                $height = ($endCnvMin - $startCnvMin) / 5 * 31 - 4;
                                                                                ?>
                                                                                <div id="item" class="data data-grn2" draggable="true" style="height: <?= $height ?>px; max-width: 152px; opacity: 0.6;" data-schedule-type="service" data-schedule-id="<?= $mainList['unique_id'] ?>" data-service-id="<?= $val['unique_id'] ?>" data-root-name="<?= $weekName ?>" data-start-time="<?= $startTime ?>" data-end-time="<?= $endTime ?>" data-hmtg-flg="<?= $val['hmtg_flg'] ?>" title="<?= $startTime ?> - <?= $endTime ?>" data-url="/schedule/week/dialog/edit_dialog.php?id=<?= $mainList['unique_id'] . "&user=" . $mainList['user_id'] ?>" data-dialog_name="modal">
                                                                                    <div>
                                                                                        <input type="hidden" id="hmtg_flg" value="<?= $val['hmtg_flg'] ?>">
                                                                                        <span id="start_time" class="d_time"><?= $val['disp_start'] ?></span>
                                                                                        <span class="d_time">~</span>
                                                                                        <span id="end_time" class="d_time"><?= $val['disp_end'] ?></span>
                                                                                    </div>
                                                                                    <div>
                                                                                        <span><?= $val['service_detail_name'] ?></span>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    <?php endforeach; ?>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endfor; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/// CONTENT_END ///-->
                    </div>
                </div>
            </form>
        </article>
        <!--CONTENT-->
        <!-- </div> -->
    </div>
    <p id="page"><a href="#wrapper">PAGE TOP</a></p>
</body>

</html>