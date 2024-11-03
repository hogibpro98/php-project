<?php require_once(dirname(__FILE__) . "/php/list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>従業員予定実績</title>
    </head>

    <body>
        <div id="wrapper">
            <div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <style>
                    .kiroku {
                        color: #FFF;
                        width: 110px;
                        cursor: pointer;
                        font-size: 81.3%;
                        font-weight: 500;
                        line-height: 140%;
                        user-select: none;
                        position: relative;
                        background: #EEC574;
                        border-radius: 50px;
                        display: inline-block;
                        padding: 4px 0 4px 35px;
                    }
                </style>
                <script>
                    $(function () {
                        $(".record_entry").on("click", function (e) {
                            //disabled属性の状態を取得する
                            var result = $(this).attr('readonly');
                            if (result == 'readonly') {
                                alert("作成中の看多機記録があるため実行できません。");
                                // デフォルトのイベントをキャンセル
                                e.stopImmediatePropagation();
                                e.preventDefault();
                            }
                        });
                    });

                    function protectionChange(id) {

                        var res = window.confirm('保護フラグを変更します。よろしいですか？');
                        if (!res) {
                            return false;
                        } else {
                            // 処理タイプ(plan/service/staff)
                            var type = "service";

                            // プロテクションフラグ更新処理
                            $.ajax({
                                async: false,
                                type: "POST",
                                url: "./ajax/change_protection.php",
                                dataType: "text",
                                data: {
                                    "id": id,
                                    "type": type
                                }
                            }).done(function (data) {
                                console.log("処理スケジュールID : " + data);
                            }).fail(function (jqXHR, textStatus, errorThrown) {
                                console.log("ajax通信に失敗しました");
                                console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                                console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                                console.log("errorThrown    : " + errorThrown.message); // 例外情報
                            });
                        }
                        location.reload();
//                        window.location.href = "/record/staff/index.php";
                    }
                </script>

                <!--CONTENT-->
                <article id="content">
                    <form action="" class="p-form-validate" method="POST">
                        <!--/// CONTENT_START ///-->
                        <h2>従業員予定実績</h2>
                        <div id="subpage">
                            <div id="staff-schedule" class="nursing schedule_info">

                                <div class="keika pc"><a href="/report/progress/index.php">経過記録</a></div>

                                <ul class="switch_toggle">
                                    <li><a href="/record/user/index.php">利用者</a></li>
                                    <li class="active"><a href="/record/staff/index.php">従業員</a></li>
                                </ul>
                                <div class="search_drop sm">検索</div>
                                <!--/// 検索エリア_START ///-->
                                <div class="cont_head nurse_record">
                                    <div class="box1 profile">
                                        <div class="user">
                                            <span class="label_t text_blue">従業員</span>
                                            <p class="n_search staff_search">Search</p>
                                            <!-- <input type="text" name="search[other_id]" class="n_num tgt-stf_id" value="<?= $search['other_id'] ?>" maxlength="7" pattern="^[0-9]+$"> -->
                                            <input type="text" name="search[other_id]" class="n_num tgt-other_id" value="<?= $search['other_id'] ?>">
                                            <input type="hidden" name="search[staff_id]" class="n_num tgt-stf_id" value="<?= $search['staff_id'] ?>">
                                            <input type="text" name="search[staff_name]" class="n_name tgt-stf_name bg-gray2" value="<?= $search['staff_name'] ?>" readonly>
                                        </div>
                                        <div class="period">
                                            <span class="label_t text_blue">表示対象期間</span>
                                            <input type="date" name="search[date_from]" class="" style="width:120px;" value="<?= $search['date_from'] ?>">～
                                            <input type="date" name="search[date_to]" class="" style="width:120px;" value="<?= $search['date_to'] ?>">
                                        </div>
                                        <div class="add_rules">
                                            <ul>
                                                <li><input type="checkbox" name="search[search1]" value="実績未のみ" id="cond1_1" <?= $search['search1'] == "実績未のみ" ? 'checked' : '' ?>><label for="cond1_1">実績未のみ</label> </li>
                                                <li><input type="checkbox" name="search[search3]" value="予定キャンセルを表示" id="cond3_1" <?= $search['search3'] == "予定キャンセルを表示" ? 'checked' : '' ?>><label for="cond3_1">予定キャンセルを表示</label></li>
                                            </ul>
                                        </div>
                                        <button type="submit" name="btnSearch" value="true" class="btn search">絞り込み</button>
                                    </div>
                                    <div class="schedule">
                                        <span class="label_t text_blue">未実績数</span>
                                        <div class="sched_box">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <th>本日分</th>
                                                        <td><?= isset($unRec) ? $unRec : '0'; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="sp_user_name">
                                    <p><?= $loginUser['name'] ?></p>
                                </div>
                                <!--/// 検索エリア_END ///-->
                                <!-- 予定実績エリア start -->
                                <!-- ダイアログ流し込みエリア -->
                                <div class="modal_setting"></div>
                                <!-- 予定実績ヘッダー start -->
                                <div class="tit_box">
                                    <div class="yotei_box" style="margin-left:18px">
                                        <div class="mid">予定</div>
                                        <table>
                                            <tr>
                                                <th class="h2">時刻</th>
                                                <th class="h3">スケジュール名称</th>
                                                <th class="h4">利用者</th>
                                                <th class="h5">最終更新日<br>/更新者</th>
                                                <th class="h6">保護</th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="jisseki_box">
                                        <div class="mid">実績</div>
                                        <table>
                                            <tr>
                                                <th class="h2">時刻</th>
                                                <th class="h3">スケジュール名称</th>
                                                <th class="h4">対応者</th>
                                                <th class="h5">最終更新日<br>/更新者</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- 予定実績ヘッダー end -->
                                <!-- 予定実績データ部 start -->
                                <div class="sched1">
                                    <?php foreach ($dispPlan as $tgtDate => $dispPlan2) : ?>
                                        <?php $wareki = formatDateTime($tgtDate, 'Y年m月d日') . '（' . $weekAry[formatDateTime($tgtDate, 'w')] . '）'; ?>
                                        <div class="mid_date tit_toggle2"><?= $wareki ?></div>
                                        <div class="box_wrap child_toggle2">
                                            <div class="m_row">
                                                <div class="layout-3col">
                                                    <!-- 予定データstart -->
                                                    <div class="layout-3col-box">
                                                        <?php foreach ($dispPlan2 as $tgtTime => $dispPlan3) : ?>
                                                            <?php foreach ($dispPlan3 as $planId => $dispPlan4) : ?>
                                                                <?php
                                                                if (!isset($dispPlan4['main'])) {
                                                                    continue;
                                                                }
                                                                ?>
                                                                <?php $mainPlan = $dispPlan4['main']; ?>
                                                                <?php if ($dispPlan4['type'] === 'staff') : ?>
                                                                    <!-- 予定スタッフデータ流し込み -->
                                                                    <div class="row y_row">
                                                                        <div class="time"><?= $mainPlan['start_time'] ?>~<?= $mainPlan['end_time'] ?></div>
                                                                        <div class="sched_name"><?= $mainPlan['work'] ?></div>
                                                                        <div class="respondent"></div>
                                                                        <div class="modified">
                                                                            <span><?= $mainPlan['update_date'] ?></span>
                                                                            <span><?= $mainPlan['update_name'] ?> </span>
                                                                            <?php //if (empty($mainPlan['protection_flg'])) :?>
                                                                                <?php if (isset($mainPlan['status']) && $mainPlan['status'] === "キャンセル") : ?>
                                                                                    <span class="btn1 cancel_appt">予定キャンセル</span>
                                                                                <?php else : ?>
                                                                                    <span class="btn1">&nbsp;</span>
                                                                                <?php endif; ?>
                                                                            <?php //endif;?>
                                                                        </div>
                                                                        <div class="btn_box">
                                                                            <p>
                                                                                <?php if (empty($mainPlan['protection_flg'])) : ?>
                                                                                    <?php if ($mainPlan['status'] !== '実施' && $mainPlan['status'] !== 'キャンセル') : ?>
                                                                                        <button type="button" class="modal_open btn edit" data-url="/record/staff/dialog/stf_pln_edit_dialog.php?id=<?= $planId ?>" data-dialog_name="dynamic_modal">編集</button>
                                                                                        <button type="button" class="modal_open btn duplicate" data-url="/record/staff/dialog/stf_dupli_dialog.php?id=<?= $planId ?>" data-dialog_name="dynamic_modal">複製</button>
                                                                                        <button type="submit" name="btnDelStfPlan" class="btn delete" value="<?= $mainPlan['unique_id'] ?>">削除</button>
                                                                                        <button type="button" class="modal_open btn confirm" data-url="/record/staff/dialog/fix_stf_dialog.php?id=<?= $planId ?>"data-dialog_name="dynamic_modal">実績確定</button>
                                                                                        <button type="button" class="modal_open btn change" data-url="/record/staff/dialog/stf_rec_chg_dialog.php?id=<?= $planId ?>" data-dialog_name="dynamic_modal">実績変更</button>
                                                                                    <?php endif; ?>
                                                                                    <?php if ($mainPlan['status'] !== 'キャンセル') : ?>
                                                                                        <button type="submit" name="btnCxlStf" class="btn cancel_appt" value="<?= $planId ?>">予定キャンセル</button>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if ($dispPlan4['type'] === 'user') : ?>
                                                                    <!-- 予定サービスデータ流し込み -->
                                                                    <?php foreach ($mainPlan as $tgtId => $usrSvc) : ?>
                                                                        <div class="row y_row">
                                                                            <div class="time"><?= $usrSvc['start_time'] . '~' . $usrSvc['end_time'] ?></div>
                                                                            <div class="sched_name">
                                                                                <?php if (isset($usrSvc['WB'])) : ?>
                                                                                    <span class="wb">WB</span>
                                                                                <?php endif; ?>
                                                                                <?= $usrSvc['service_name'] ?>
                                                                            </div>
                                                                            <div class="respondent"><?= $usrSvc['user_name'] ?></div>
                                                                            <div class="modified">
                                                                                <span><?= $usrSvc['update_date'] ?></span>
                                                                                <span><?= $usrSvc['update_name'] ?></span>
                                                                                <?php if (!empty($usrSvc['status']) && $usrSvc['status'] === "キャンセル") : ?>
                                                                                    <span class="btn1 cancel_appt">予定キャンセル</span>
                                                                                <?php else : ?>
                                                                                    <span class="btn1">&nbsp;</span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div class="btn_box">
                                                                                <p>
                                                                                    <!-- 看多機記録用hidden情報 -->
                                                                                    <input type="hidden" name="upKtk[<?= $planId ?>][unique_id]" value="<?= $usrSvc['kantaki'] ?>">
                                                                                    <input type="hidden" name="upKtk[<?= $planId ?>][service_day]" value="<?= $usrSvc['use_day'] ?>">
                                                                                    <input type="hidden" name="upKtk[<?= $planId ?>][start_time]" value="<?= $usrSvc['start_time'] ?>">
                                                                                    <input type="hidden" name="upKtk[<?= $planId ?>][end_time]" value="<?= $usrSvc['end_time'] ?>">
                                                                                    <input type="hidden" name="upKtk[<?= $planId ?>][user_id]" value="<?= $usrSvc['user_id'] ?>">

                                                                                    <!-- ボタンアクションエリア -->
                                                                                    <?php if (empty($usrSvc['protection_flg'])) : ?>
                                                                                        <?php if ($usrSvc['status'] !== 'キャンセル') : ?>
                                                                                            <?php if (mb_strpos($usrSvc['service_name'], "訪問看護") === false) : ?>
                                                                                                <button type="submit" name="btnKantaki" class="btn kiroku" value="<?= $planId; ?>">看多機記録</button>
                                                                                            <?php endif; ?>
                                                                                            <?php if (mb_strpos($usrSvc['service_name'], "訪問看護") !== false) : ?>
                                                                                                <button type="submit" name="btnHokan2" class="btn kiroku" value="<?= $planId; ?>">訪看記録Ⅱ</button>
                                                                                            <?php endif; ?>
                                                                                            <?php if (mb_strpos($usrSvc['service_name'], "送迎") !== false) : ?>
                                                                                                <button type="submit" name="btnKantaki" class="btn kiroku" value="<?= $planId; ?>">看多機記録</button>
                                                                                            <?php endif; ?>                                                                                    
                                                                                        <?php endif; ?>
                                                                                        <?php if ($usrSvc['status'] !== '実施' && $usrSvc['status'] !== 'キャンセル') : ?>
                                                                                            <button type="button" class="modal_open btn edit" data-url="/record/staff/dialog/usr_pln_edit_dialog.php?id=<?= $planId ?>" data-dialog_name="dynamic_modal">編集</button>
                                                                                            <button type="button" class="modal_open btn duplicate" data-url="/record/staff/dialog/usr_dupli_dialog.php?id=<?= $planId ?>" data-dialog_name="dynamic_modal">複製</button>
                                                                                            <button type="submit" name="btnDelUserPlan" class="btn delete" value="<?= $tgtId ?>">削除</button>
                                                                                            <button type="button" class="modal_open btn confirm record_entry" data-url="/record/staff/dialog/fix_usr_dialog.php?id=<?= $tgtId ?>" <?= $usrSvc['disable'] ?> data-dialog_name="dynamic_modal">実績確定</button>
                                                                                            <button type="button" class="modal_open btn change record_entry" data-url="/record/staff/dialog/usr_rec_chg_dialog.php?id=<?= $planId ?>" <?= $usrSvc['disable'] ?> data-dialog_name="dynamic_modal">実績変更</button>
                                                                                        <?php endif; ?>
                                                                                        <?php if ($usrSvc['status'] !== 'キャンセル') : ?>
                                                                                            <button type="submit" name="btnCxlUser" class="btn cancel_appt" value="<?= $tgtId ?>">予定キャンセル</button>
                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <!-- 予定データ end -->
                                                    <!-- センターエリア start -->
                                                    <div class="layout-3col-box" style="width:150px;">
                                                        <?php foreach ($dispPlan2 as $tgtTime => $dispPlan3) : ?>
                                                            <?php foreach ($dispPlan3 as $planId => $dispPlan4) : ?>
                                                                <?php
                                                                if (!isset($dispPlan4['main'])) {
                                                                    continue;
                                                                }
                                                                ?>
                                                                <?php $mainPlan = $dispPlan4['main']; ?>
                                                                <?php if ($dispPlan4['type'] === 'user') : ?>
                                                                    <?php foreach ($mainPlan as $tgtId => $usrPlan) : ?>
                                                                        <div class="row y_row" style="padding-bottom:0.2em;">
                                                                            <div class="modified">
                                                                                <input type="hidden" name="upKtk[<?= $planId ?>][service_day]" value="<?= $usrPlan['use_day'] ?>">
                                                                                <input type="hidden" name="upKtk[<?= $planId ?>][start_time]" value="<?= $usrPlan['start_time'] ?>">
                                                                                <input type="hidden" name="upKtk[<?= $planId ?>][end_time]" value="<?= $usrPlan['end_time'] ?>">
                                                                                <?php if (!empty($usrPlan['disable'])) : ?>
                                                                                    <br />
                                                                                    <?php if (mb_strpos($usrPlan['service_name'], "訪問看護") !== false) : ?>
                                                                                        <span class="kiroku bg-gray2">訪看記録Ⅱ</span>
                                                                                    <?php else: ?>
                                                                                        <span class="kiroku bg-gray2">看多機記録</span>
                                                                                    <?php endif; ?>
                                                                                <?php else : ?>
                                                                                    <br />
                                                                                    <?php if (mb_strpos($usrPlan['service_name'], "訪問看護") !== false) : ?>
                                                                                        <span class="kiroku">訪看記録Ⅱ</span>
                                                                                    <?php else: ?>
                                                                                        <span class="kiroku">看多機記録</span>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                                <span class="btn_stat" style="z-index:0;"><input type="checkbox" id="<?= $tgtId ?>" onChange="protectionChange('<?= $tgtId ?>');" <?= !empty($usrPlan['protection_flg']) ? 'checked="true"' : '' ?>><label for="<?= $tgtId ?>"><i></i></label></span>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <!-- センターエリア end -->
                                                    <!-- 実績データ start -->
                                                    <div class="layout-3col-box">
                                                        <?php $dispRcd2 = isset($dispRcd[$tgtDate]) ? $dispRcd[$tgtDate] : array(); ?>
                                                        <?php foreach ($dispRcd2 as $tgtTime => $dispRcd3) : ?>
                                                            <?php foreach ($dispRcd3 as $tgtId => $dispRcd4) : ?>
                                                                <?php $mainRcd = $dispRcd4['main']; ?>
                                                                <!-- 実績スタッフデータ流し込み -->
                                                                <?php if ($dispRcd4['type'] === 'staff') : ?>
                                                                    <div class="row j_row">
                                                                        <div class="time"><?= $mainRcd['start_time'] . '~' . $mainRcd['end_time'] ?></div>
                                                                        <div class="sched_name"><?= $mainRcd['work'] ?></div>
                                                                        <div class="respondent"></div>
                                                                        <div class="modified">
                                                                            <span><?= $mainRcd['update_date'] ?></span>
                                                                            <span><?= $mainRcd['update_name'] ?></span>
                                                                            <?php if (isset($mainRcd['status']) && $mainRcd['status'] === "キャンセル") : ?>
                                                                                <span class="btn1 cancel_appt">予定キャンセル</span>
                                                                            <?php else : ?>
                                                                                <span class="btn1">&nbsp;</span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <div class="btn_box">
                                                                            <p>
                                                                                <button type="button" class="modal_open btn edit" data-url="/record/staff/dialog/stf_rec_dialog.php?id=<?= $mainRcd['unique_id'] ?>" data-dialog_name="dynamic_modal">編集</button>
                                                                                <button type="submit" name="btnDelStfRcd" class="btn delete" value="<?= $tgtId ?>">削除</button>
                                                                                <!-- <span class="btn cancel_appt">予定キャンセル</span> -->
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <!-- 実績サービスデータ流し込み -->
                                                                <?php if ($dispRcd4['type'] === 'user') : ?>
                                                                    <?php foreach ($mainRcd as $tgtId => $usrRcd) : ?>
                                                                        <div class="row j_row">
                                                                            <div class="time"><?= $usrRcd['start_time'] . '~' . $usrRcd['end_time'] ?></div>
                                                                            <div class="sched_name"><?= $usrRcd['service_name'] ?></div>
                                                                            <div class="respondent"><?= $usrRcd['user_name'] ?></div>
                                                                            <div class="modified">
                                                                                <span><?= $usrRcd['update_date'] ?></span>
                                                                                <span><?= $usrRcd['update_name'] ?></span>
                                                                                <?php if (isset($usrRcd['status']) && $usrRcd['status'] === "キャンセル") : ?>
                                                                                    <span class="btn1 cancel_appt">予定キャンセル</span>
                                                                                <?php else : ?>
                                                                                    <span class="btn1">&nbsp;</span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div class="btn_box">
                                                                                <p>
                                                                                    <button type="button" class="modal_open btn edit" data-url="/record/staff/dialog/usr_rec_dialog.php?id=<?= $usrRcd['user_record_id'] ?>" data-dialog_name="dynamic_modal">編集</button>
                                                                                    <button type="submit" name="btnDelUserRcd" class="btn delete" value="<?= $tgtId ?>">削除</button>
                                                                                    <!-- <span class="btn cancel_appt">予定キャンセル</span> -->
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <!-- 実績データ end -->
                                                    <div class="row e_row"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <!-- 予定実績データ部 end -->
                                <!--ダイアログ呼出し-->
                                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff.php'); ?>
                                <!-- 予定実績エリア end -->
                            </div>
                        </div>
                        <!--/// CONTENT_END ///-->
                    </form>
                </article>
                <!--CONTENT-->
            </div>
        </div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>

</html>