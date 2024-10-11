<?php require_once(dirname(__FILE__) . "/php/edit.php"); ?>
<!DOCTYPE html>
<html lang="ja">

    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <link rel="stylesheet" href="./css/jquery.skeduler.css" type="text/css">
        <link rel="stylesheet" href="./css/edit.css" type="text/css">
        <script src="./js/jquery.skeduler.js"></script>
        <script src="./js/main.js"></script>
        <script src="./js/edit.js"></script>
        <!--CONTENT-->
        <title>ルート管理</title>
    </head>

    <body>
        <!-- <div id="wrapper"> -->
        <div id="base">
            <!--HEADER-->
            <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
            <!--CONTENT-->
            <article id="content">
                <!--/// CONTENT_START ///-->
                <form action="" class="p-form-validate" method="post">
                    <h2>ルート管理</h2>
                    <div id="subpage">
                        <div id="root" class="nursing calendar_data">
                            <!-- ヘッダーパーツ(start) -->
                            <div class="cont_head nurse_record">
                                <div class="schedule">
                                    <span class="label_t" style="background: #F35523;">未割当<br>スケジュール</span>
                                    <div class="sched_box">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>月</th>
                                                    <th>火</th>
                                                    <th>水</th>
                                                    <th>木</th>
                                                    <th>金</th>
                                                    <th>土</th>
                                                    <th>日</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="<?= $unRoot[1]['cls'] ?>"><?= $unRoot[1]['num'] ?></td>
                                                    <td class="<?= $unRoot[2]['cls'] ?>"><?= $unRoot[2]['num'] ?></td>
                                                    <td class="<?= $unRoot[3]['cls'] ?>"><?= $unRoot[3]['num'] ?></td>
                                                    <td class="<?= $unRoot[4]['cls'] ?>"><?= $unRoot[4]['num'] ?></td>
                                                    <td class="<?= $unRoot[5]['cls'] ?>"><?= $unRoot[5]['num'] ?></td>
                                                    <td class="<?= $unRoot[6]['cls'] ?>"><?= $unRoot[6]['num'] ?></td>
                                                    <td class="<?= $unRoot[0]['cls'] ?>"><?= $unRoot[0]['num'] ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <span class="subject">件</span>
                                </div>
                                <div class="btn_box">
                                    <!-- ルート追加ボタン -->
                                    <button type="button" id="btn_add_root" class="btn add display_dets">ルート追加</button>
                                    <!-- <div>
                                            <span class="btn add display_dets">ルート追加</span>
                                    </div> -->
                                </div>
                            </div>
                            <!-- ヘッダーパーツ(end) -->
                            <!-- ルート追加ダイアログ(start) -->
                            <div class="sched_details add_root cancel_act left:50;">
                                <div class="close close_part">✕<span>閉じる</span></div>
                                <div class="sched_tit">ルート追加</div>
                                <input type="hidden" name="upRoot[week]" value="<?= $selectWeek ?>">
                                <div class="s_detail">
                                    <div class="box1">
                                        <p class="mid">ルート名</p>
                                        <p><input type="text" name="upRoot[name]" value=""></p>
                                    </div>
                                    <div class="box1">
                                        <p class="mid">ルート<br>種類</p>
                                        <p>
                                            <span class="type"><input type="checkbox" name="upRoot[root_type][]" value="看多機" id="type1" checked><label for="type1">看多機</label></span>
                                            <span class="type"><input type="checkbox" name="upRoot[root_type][]" value="訪問看護" id="type2" checked><label for="type2">訪問看護</label></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="s_constrols">
                                    <p><span class="btn cancel">キャンセル</span></p>
                                    <p>
                                            <!-- <span class="btn trash">削除</span> -->
                                        <button type="submit" name="btnEntryRoot" class="btn save display_rr" value="true">保存</button>
                                    </p>
                                </div>
                            </div>
                            <!-- ルート追加ダイアログ(end) -->

                            <div class="c_wrap">
                                <!-- スケジュール追加追従メニュー(start) -->
                                <div class="sched_parts">
                                    <ul>
                                        <li>
                                            <p draggable="false"><a href="javascript:;" draggable="false">利用者</a></p>
                                            <ul>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">アセスメント</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">記録入力</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">往診対応</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">契約</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">計画・報告作成</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">新規受入準備</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">服薬セット</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">フロア対応</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">送迎</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <p draggable="false"><a href="javascript:;" draggable="false">会議</a></p>
                                            <ul>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">サ担会議</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">退院前カンファ</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">レビュー</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">面接・面談</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">各種会議</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">申送り</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <p draggable="false"><a href="javascript:;" draggable="false">その他</a></p>
                                            <ul>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">勤務時間外</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">休憩・仮眠</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">移動時間</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">教育研修</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">営業活動</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">内覧対応</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">事務処理</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">調理・配膳</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">外部受託</a></li>
                                                <li><a class="add_menu" data-interval="60" data-background="#FFFAE5" data-border-color="#BCA850" href="javascript:;">オンコール当番</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <!-- スケジュール追加追従メニュー(end) -->

                                <!-- 曜日選択コンボ(start) -->
                                <div class="month_select">
                                    <select id="selectWeek" class="s_month" name="week">
                                        <option <?= $selectWeek == '1' ? 'selected' : '' ?> value="1">月</option>
                                        <option <?= $selectWeek == '2' ? 'selected' : '' ?> value="2">火</option>
                                        <option <?= $selectWeek == '3' ? 'selected' : '' ?> value="3">水</option>
                                        <option <?= $selectWeek == '4' ? 'selected' : '' ?> value="4">木</option>
                                        <option <?= $selectWeek == '5' ? 'selected' : '' ?> value="5">金</option>
                                        <option <?= $selectWeek == '6' ? 'selected' : '' ?> value="6">土</option>
                                        <option <?= $selectWeek == '0' ? 'selected' : '' ?> value="0">日</option>
                                    </select>
                                </div>
                                <!-- 曜日選択コンボ(end) -->
                                <!-- ダイアログ流し込みエリア -->
                                <div class="modal_setting"></div>
                                <div class="table_grp grp1">
                                    <!-- タイムスケジューラエリア -->
                                    <div id="skeduler-container" class="skeduler-container" style="overflow:visible !important;">
                                        <div class="skeduler-headers" style="user-select:none;position:sticky;">
                                            <?php foreach ($rootMst as $index => $rootInfo) : ?>
                                                <?php $name = $rootInfo['name']; ?>
                                                <div style="padding-right:9px;"
                                                    <?php if ($name !== '未割当') : ?> 
                                                         class="modal_open" 
                                                         data-url="/schedule/route_edit/dialog/root_edit_dialog.php?id=<?= $index ?>" 
                                                         data-dialog_name="modal" 
                                                     <?php endif; ?>
                                                     readonly
                                                     ><?= $name ?></div>
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

                                            <?php if (!empty($rootMst)) : ?>
                                                <div class="skeduler-main-body">
                                                    <?php foreach ($rootMst as $index => $rootInfo) : ?>
                                                        <?php $rootName = $rootInfo['name']; ?>
                                                        <?php $rootId = $rootInfo['unique_id']; ?>
                                                        <div>
                                                            <div class="skeduler-task-placeholder"></div>
                                                            <?php for ($i = 0; $i < COUNT($timeScaleList) * 12; $i++) : ?>
                                                                <?php
                                                                $totalMin = $i * 5;
                                                                $hour = (int) ($totalMin / 60);
                                                                $min = (int) ($totalMin % 60);
                                                                $startTime = sprintf("%02d:%02d", $hour, $min);
                                                                ?>
                                                                <div class="skeduler-cell" data-time="<?= strval($i * 5) ?>" data-root-name="<?= $rootName ?>" data-root-id="<?= $rootId ?>" data-place-id="<?= $placeId ?>" data-start-time="<?= $startTime ?>" style="padding-right:10px;" draggable="false">
                                                                    <?php if (isset($dispData[$rootName]) === true) : ?>
                                                                        <?php foreach ($dispData[$rootName] as $val_start_time => $scdData) : ?>
                                                                            <?php foreach ($scdData as $scdCd => $scdList) : ?>
                                                                                <?php if (isset($scdList['type']) && $scdList['type'] === 'staff') : ?>
                                                                                    <?php $mainList = $scdList['main']; ?>
                                                                                    <?php if ($startTime === $mainList['start_time']) : ?>
                                                                                        <?php
                                                                                        $endTime = $mainList['end_time'];
                                                                                        $startSplit = explode(":", $startTime);
                                                                                        $endSplit = explode(":", $endTime);
                                                                                        $startCnvMin = (int) $startSplit[0] * 60 + (int) $startSplit[1];
                                                                                        $endCnvMin = (int) $endSplit[0] * 60 + (int) $endSplit[1];
                                                                                        $top = $startCnvMin / 5 * 30;
                                                                                        $height = ($endCnvMin - $startCnvMin) / 5 * 30;
                                                                                        ?>
                                                                                        <div id="item" class="data data-grn" 
                                                                                             draggable="true" 
                                                                                             style="height:<?= $height ?>px; max-width: 186px; opacity: 0.6;background:#FFFAE5; border-color:#BCA850;" 
                                                                                             data-schedule-type="staff" 
                                                                                             data-schedule-id="<?= $mainList['unique_id'] ?>" 
                                                                                             data-root-name="<?= $rootName ?>" 
                                                                                             data-root-id="<?= $rootId ?>" 
                                                                                             data-place-id="<?= $placeId ?>" 
                                                                                             data-start-time="<?= $startTime ?>" 
                                                                                             data-end-time="<?= $endTime ?>" 
                                                                                             title="<?= $startTime ?> - <?= $endTime ?>" 
                                                                                             data-url="/schedule/route_edit/dialog/staff_edit_dialog.php?id=<?= $mainList['unique_id'] ?>" 
                                                                                             data-dialog_name="modal">
                                                                                            <div>
                                                                                                <span id="start_time" class="d_time"><?= $startTime ?></span>
                                                                                                <span class="d_time">~</span>
                                                                                                <span id="end_time" class="d_time"><?= $endTime ?></span>
                                                                                            </div>
                                                                                            <div>
                                                                                                <span id="work" class="dty_dets"><?= $mainList['work'] ?></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                                <?php if ($scdList['type'] === 'user') : ?>
                                                                                    <?php $svcList = isset($scdList['service']) ? $scdList['service'] : array(); ?>
                                                                                    <?php foreach ($svcList as $tgtId => $svcData) : ?>
                                                                                        <?php if ($startTime === $svcData['start_time']) : ?>
                                                                                            <?php
                                                                                            $endTime = $svcData['end_time'];
                                                                                            $startSplit = explode(":", $startTime);
                                                                                            $endSplit = explode(":", $endTime);
                                                                                            $startCnvMin = (int) $startSplit[0] * 60 + (int) $startSplit[1];
                                                                                            $endCnvMin = (int) $endSplit[0] * 60 + (int) $endSplit[1];
                                                                                            $top = $startCnvMin / 5 * 30;
                                                                                            $height = ($endCnvMin - $startCnvMin) / 5 * 30;
                                                                                            ?>
                                                                                            <div id="item" class="data data-grn" 
                                                                                                 draggable="true" 
                                                                                                 style="height: <?= $height ?>px; max-width: 152px; opacity: 0.6;" 
                                                                                                 title="<?= $startTime ?> - <?= $endTime ?>"
                                                                                                 data-schedule-type="week" 
                                                                                                 data-schedule-id="<?= $svcData['unique_id'] ?>" 
                                                                                                 data-root-name="<?= $rootName ?>" 
                                                                                                 data-root-id="<?= $rootId ?>" 
                                                                                                 data-place-id="<?= $placeId ?>" 
                                                                                                 data-start-time="<?= $startTime ?>" 
                                                                                                 data-end-time="<?= $endTime ?>"  
                                                                                                 data-url="/schedule/route_edit/dialog/edit_dialog.php?id=<?= $scdCd ?>&user=<?= $svcData['user_id'] ?>" 
                                                                                                 data-dialog_name="modal">
                                                                                                <div>
                                                                                                    <span id="start_time" class="d_time"><?= $startTime ?></span>
                                                                                                    <span class="d_time">~</span>
                                                                                                    <span id="end_time" class="d_time"><?= $endTime ?></span>
                                                                                                    <br />
                                                                                                    <span class=""><?= $svcData['type'] ?></span>
                                                                                                </div>
                                                                                                <div>
                                                                                                        <!--<span id="root_name" class=""><?= $rootName ?></span>-->
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php endif; ?>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endfor; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else : ?>
                                                <div><p>表示できるルート情報がありません。<br/>ルート情報を作成し再表示してください。</p></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- タイムスケジューラエリア -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--/// CONTENT_END ///-->
            </article>
            <!--CONTENT-->
        </div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>

</html>