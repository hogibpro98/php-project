<?php require_once(dirname(__FILE__) . "/php/edit.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <link rel="stylesheet" href="./css/jquery.skeduler.css" type="text/css">
        <link rel="stylesheet" href="./css/day.css" type="text/css">
        <script src="./js/jquery.skeduler.js"></script>
        <script src="./js/main.js"></script>
        <script src="./js/day.js"></script>
        <!--CONTENT-->
        <title>ルート表</title>
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
                    <h2>ルート表</h2>
                    <div id="subpage">
                        <div id="root-table" class="nursing calendar_data">
                            <div class="cont_head head2 nurse_record">
                                <div class="current_date">
                                    <input type="date" id="selectDay" name="day" class="" style="width:155px;font-size: 100%;border-color: #A9C5F1;border-color: #A9C5F1;" value="<?= $tgtDay ?>">
                                </div>
                                <div class="search_drop sm">検索</div>
                                <div class="search_wrap">
                                    <div class="s_wrap">
                                        <div class="schedule">
                                            <span class="label_t" style="background: #F35523;">未割当<br>ルート</span>
                                            <div class="sched_box">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td><?= $unRoot ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class="subject">件</span>
                                        </div>
                                        <div class="schedule sched_blue sc1">
                                            <span class="label_t">未割当</span>
                                            <div class="sched_box">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td><?= $unRoot ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class="label_t">ルート</span>
                                        </div>
                                        <div class="schedule sched_blue">
                                            <span class="label_t">通い<br>利用人数</span>
                                            <div class="sched_box">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td><?= $hitRoot[1] ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class="subject">人</span>
                                        </div>
                                        <div class="schedule sched_blue">
                                            <span class="label_t">宿泊<br>利用人数</span>
                                            <div class="sched_box">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td><?= $hitRoot[2] ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class="subject">人</span>
                                        </div>
                                        <div class="checklist">
                                            <p><input type="checkbox" name="search[kantaki]" id="check1" <?= $search['kantaki'] === '看多機' ? ' checked' : '' ?>><label for="check1" value="看多機">看多機</label></p>
                                            <p><input type="checkbox" name="search[kaigo]" id="check2" <?= $search['kango'] === '訪問看護' ? ' checked' : '' ?>><label for="check2" value="訪問看護">訪問看護</label></p>
                                            <p><input type="checkbox" name="search[miwariate]" id="check3"  <?= $search['kango'] === '未割当' ? ' checked' : '' ?>><label for="check3" value="未割当">未割当</label></p>
                                        </div>
                                        <button name="btnSearch" class="btn search" value="true">絞り込み</button>
                                        </div>
                                    </div>
                                        <!-- 担当者を保存する -->
                                        <button name="btnRootEntry" type="submit" class="btn btn_charge" value="true">担当者を保存する</button>
                                    
                                
                            </div>
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

                                <!-- ダイアログ流し込みエリア -->
                                <div class="modal_setting"></div>
                                
                                <?php if(!empty($rootMst)) : ?>
                                
                                <!-- タイムスケジューラエリア -->
                                <div id="skeduler-container" class="skeduler-container">
                                    <div class="skeduler-headers" style="user-select:none; <?php /* position:sticky; */ ?>">
                                        <?php $i = 1; ?>
                                        <?php foreach ($rootMst as $tgtId => $val) : ?>
                                            <div style="padding-right:9px;" readonly>
                                                <div style="display:flex;">
                                                    <!--<p class="btn_stat"><input type="checkbox" id="b1"><label for="b1"><i></i></label></p>-->
                                                    <p class="display_dets schedule-header" value="<?= $val['name'] ?>">
                                                        <image><?= $val['name'] ?>
                                                    </p>
                                                    <input type="hidden" name="upRoot[<?= $tgtId ?>][unique_id]" value="<?= $tgtId ?>">
                                                    <input type="hidden" name="upRoot[<?= $tgtId ?>][root_name]" value="<?= $val['name'] ?>">
                                                    <input type="hidden" name="upRoot[<?= $tgtId ?>][root_id]" value="<?= $val['root_id'] ?>">
                                                    <input type="hidden" name="upRoot[<?= $tgtId ?>][target_day]" value="<?= $tgtDay ?>">
                                                    <input type="hidden" name="upRoot[<?= $tgtId ?>][place_id]" value="<?= $val['place_id'] ?>">
                                                </div>
                                                <?php if ($val['name'] !== '未割当') : ?>
                                                    <div style="display:flex">
                                                        <?php $targetSetId = "tgt_set_id" . $i; ?>
                                                        <?php $targetSetName = "tgt_set_name" . $i; ?>
                                                        <?php $targetSetRemarks = "tgt_set_remarks" . $i; ?>
                                                        <p class="n_search2 modal_open" 
                                                           data-url="/schedule/route_day/dialog/staff_search_dialog.php?tgt_set_id=<?= $targetSetId ?>&tgt_set_name=<?= $targetSetName ?>&tgt_set_remarks=<?= $targetSetRemarks ?>" 
                                                           data-dialog_name="cont_staff_modal">Search</p>
                                                        <input type="hidden" class="<?= $targetSetId ?>" name="upRoot[<?= $tgtId ?>][staff_id]" value="<?= $val['staff_id'] ?>">
                                                        <input id="hdnStaffName"  type="hidden" class="<?= $targetSetName ?>" name="upRoot[<?= $tgtId ?>][staff_name]" value="<?= $val['staff_name'] ?>">
                                                        <input type="text" class="<?= $targetSetName ?>" name="upRoot[<?= $tgtId ?>][staff_name]" style="width:135px" value="<?= $val['staff_name'] ?>" readonly>
                                                    </div>
                                                <p class="memo_txt"><input type="text" class="<?= $targetSetRemarks ?>" name="upRoot[<?= $tgtId ?>][remarks]" style="width:175px;" value="<?= $val['remarks'] ?>" placeholder="備考欄のメモ" autocomplete="off"></p>
                                                <?php endif; ?>
                                            </div>
                                            <?php $i = $i + 1; ?>
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
                                            <?php foreach ($rootMst as $index => $rootData) : ?>
                                                <?php $rootName = $rootData['name']; ?>
                                                <?php $rootId = $rootData['unique_id']; ?>
                                                <?php $staffId = $rootData['staff_id']; ?>
                                                <div>
                                                    <div class="skeduler-task-placeholder"></div>
                                                    <?php for ($i = 0; $i < COUNT($timeScaleList) * 12; $i++) : ?>
                                                        <?php
                                                        $totalMin = $i * 5;
                                                        $hour = (int) ($totalMin / 60);
                                                        $min = (int) ($totalMin % 60);
                                                        $startTime = sprintf("%02d:%02d", $hour, $min);
                                                        ?>
                                                        <div class="skeduler-cell" data-time="<?= strval($i * 5) ?>"
                                                             data-root-name="<?= $rootName ?>"
                                                             data-root-id="<?= $rootId ?>"
                                                             data-place-id="<?= $placeId ?>"
                                                             data-staff-id="<?= $staffId ?>"
                                                             data-start-time="<?= $startTime ?>"
                                                             style="padding-right:10px;"
                                                             draggable="false">
                                                            <?php if (isset($dispData[$rootName]) === true) : ?>
                                                                <?php foreach ($dispData[$rootName] as $val_start_time => $scdData) : ?>
                                                                    <?php foreach ($scdData as $scdCd => $scdList) : ?>
                                                                        <?php if (isset($scdList['type']) && $scdList['type'] === 'staff') : ?>
                                                                            <?php $mainList = $scdList['main']; ?>
                                                                            <?php if ($startTime === $mainList['start_time']) : ?>
                                                                                <?php
                                                                                $endTime = $mainList['disp_end'];
                                                                                $startSplit = explode(":", $startTime);
                                                                                $endSplit = explode(":", $endTime);
                                                                                $startCnvMin = (int) $startSplit[0] * 60 + (int) $startSplit[1];
                                                                                $endCnvMin = (int) $endSplit[0] * 60 + (int) $endSplit[1];
                                                                                $top = $startCnvMin / 5 * 32;
                                                                                $height = abs($endCnvMin - $startCnvMin) / 5 * 31 - 4;
                                                                                ?>
                                                                                <div id="item" class="data data-grn"
                                                                                     draggable="true"
                                                                                     style="height:<?= $height ?>px; max-width: 186px; opacity: 0.6;background:#FFFAE5; border-color:#BCA850;" 
                                                                                     data-schedule-type="staff" 
                                                                                     data-schedule-id="<?= $mainList['unique_id'] ?>" 
                                                                                     data-root-name="<?= $rootName ?>" 
                                                                                     data-root-id="<?= $rootId ?>" 
                                                                                     data-place-id="<?= $placeId ?>" 
                                                                                     data-staff-id="<?= $staffId ?>" 
                                                                                     data-start-time="<?= $startTime ?>" 
                                                                                     data-end-time="<?= $mainList['end_time'] ?>" 
                                                                                     title="<?= $startTime ?> - <?= $endTime ?>" 
                                                                                     data-url="/schedule/route_day/dialog/staff_edit_dialog.php?id=<?= $mainList['unique_id'] ?>" 
                                                                                     data-dialog_name="modal">
                                                                                    <div>
                                                                                        <span id="start_time" class="d_time"><?= $startTime ?></span>
                                                                                        <span class="d_time">~</span>
                                                                                        <span id="end_time" class="d_time"><?= $endTime ?></span>
                                                                                        <span class="in_charge"><?= $mainList['update_name'] ?></span>
                                                                                        <input type="hidden" name=upAry[first_name] value="<?= $mainList['first_name'] ?>">
                                                                                        <input type="hidden" name=upAry[lase_name] value="<?= $mainList['lase_name'] ?>">
                                                                                    </div>
                                                                                    <div>
                                                                                        <span id="work" class="dty_dets"><?= $mainList['work'] ?></span>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                        <?php if ($scdList['type'] === 'user') : ?>
                                                                            <?php $mainList = isset($scdList['main']) ? $scdList['main'] : array(); ?>
                                                                            <?php $svcList = isset($scdList['service']) ? $scdList['service'] : array(); ?>
                                                                            <?php foreach ($svcList as $tgtId => $svcData) : ?>
                                                                                <?php if ($startTime === $svcData['start_time']) : ?>
                                                                                    <?php
                                                                                    $endTime = $svcData['disp_end'];
                                                                                    $startSplit = explode(":", $startTime);
                                                                                    $endSplit = explode(":", $endTime);
                                                                                    $startCnvMin = (int) $startSplit[0] * 60 + (int) $startSplit[1];
                                                                                    $endCnvMin = (int) $endSplit[0] * 60 + (int) $endSplit[1];
                                                                                    $top = $startCnvMin / 5 * 32;
                                                                                    $height = abs($endCnvMin - $startCnvMin) / 5 * 31 - 4;
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
                                                                                         data-end-time="<?= $svcData['end_time'] ?>" 
                                                                                         data-url="/schedule/route_day/dialog/edit_dialog.php?id=<?= $scdCd ?>&user=<?= $mainList['user_id'] ?>" 
                                                                                         data-dialog_name="dynamic_modal">
                                                                                        <div>
                                                                                            <span id="start_time" class="d_time"><?= $startTime ?></span>
                                                                                            <span class="d_time">~</span>
                                                                                            <span id="end_time" class="d_time"><?= $endTime ?></span>
                                                                                            <br />
                                                                                            <span class=""><?= $svcData['service_name'] ?></span>
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
                                    </div>
                                </div>
                                <!-- タイムスケジューラエリア -->
                               <?php else : ?>
                                
                                <div><p>該当日に表示できるルート情報がありません。<br/>展開処理を実行しルート情報を作成してください。</p></div>
                                
                                <?php endif; ?>
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