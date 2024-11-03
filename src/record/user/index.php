<?php require_once(dirname(__FILE__) . "/php/list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
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

                if(confirm('保護フラグを変更します。よろしいですか？'))
                {
                    // 処理タイプ(plan/service/staff)
                    var type = "plan";
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
//                    var status = $("#" + id).prop("checked");
//                    if (status === true) {
//                        $("#" + id).prop("checked", false);
//                    } else if (status === false) {
//                        $("#" + id).prop("checked", true);
//                    } else {
//                        $("#" + id).prop("checked", true);
//                    }
                    location.reload();
                }else{
                    return false;   
                }
//                window.location.href = "/record/user/index.php";
                return true;
            }
        </script>

        <!--CONTENT-->
        <title>利用者予定実績</title>
    </head>

    <body>
        <div id="wrapper">
            <div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                <form action="" class="p-form-validate" method="post">
                    <!--/// CONTENT_START ///-->
                    <h2>利用者予定実績</h2>
                    <div id="subpage">
                        <div id="user-schedule" class="nursing schedule_info">

                            <div class="keika pc"><a href="/report/progress/index.php">経過記録</a></div>

                            <ul class="switch_toggle">
                                <li class="active"><a href="/record/user/index.php">利用者</a></li>
                                <li><a href="/record/staff/index.php">従業員</a></li>
                            </ul>

                            
                            <div class="search_drop sm">検索</div>
                            <!--/// 検索エリア_START ///-->
                            
                                <div class="cont_head nurse_record">
                                    <div class="box1 profile">
                                        <select id="record_user_range_select" name="search[user_range]" value="<?= $search['user_range'] ?>" class="user_selects">
                                            <?php $rangeSelected1 = $search['user_range'] === "すべての利用者" ? 'selected' : '' ?>
                                            <?php $rangeSelected2 = $search['user_range'] === "利用者で絞り込む" ? 'selected' : '' ?>
                                            <option <?= $rangeSelected1 ?>>すべての利用者</option>
                                            <option <?= $rangeSelected2 ?>>利用者で絞り込む</option>
                                        </select>
                                        <div class="user">
                                            <span class="label_t text_blue">利用者</span>
                                            <!--<input type="text" name="n_num" class="n_num" value="0000009">
                                                <input type="text" name="n_name" class="n_name" value="10かえりえ 炭治郎">-->
                                            <p class="n_search user_search">Search</p>
                                            <input type="text" name="search[other_id]" class="n_num tgt-usr_id" value="<?= $search['other_id'] ?>">
                                            <input type="hidden" name="search[user_id]" class="n_num tgt-unique_id" value="<?= $search['user_id'] ?>">
                                            <input type="text" name="search[user_name]" value="<?= $search['user_name'] ?>" class="n_name tgt-usr_name bg-gray2" readonly>
                                        </div>
                                        <div class="period">
                                            <span class="label_t text_blue">表示<br>対象期間</span>
                                            <input type="date" name="search[date_from]" class="" value="<?= $search['date_from'] ?>">～
                                            <input type="date" name="search[date_to]" class="" value="<?= $search['date_to'] ?>">
                                        </div>
                                    </div>
                                    <div class="box2">
                                        <div class="condition1">
                                            <span class="label_t text_blue">条件1</span>
                                            <ul>
                                                <li><input type="checkbox" name="search[search1]" value="実績未のみ" id="cond1_1" <?= strpos($search['search1'], "実績未のみ") !== false ? 'checked' : '' ?>><label for="cond1_1">実績未のみ</label> </li>
                                                <li><input type="checkbox" name="search[search3]" value="予定キャンセルを表示" id="cond3_1" <?= strpos($search['search3'], "予定キャンセルを表示") !== false ? 'checked' : '' ?>><label for="cond3_1">予定キャンセルを表示</label></li>
                                            </ul>
                                        </div>
                                        <div class="condition2">
                                            <span class="label_t text_blue" style="height:30px;text-align: center;">条件2</span>
                                            <ul>
                                                <li><input type="checkbox" name="search[search2][]" value="訪問看護　医療保険" id="cond2_1" <?= mb_strpos($search2Str, "訪問看護　医療保険") !== false ? 'checked' : '' ?>><label for="cond2_1">訪問看護 医療保険</label></li>
                                                <li><input type="checkbox" name="search[search2][]" value="訪問看護　介護保険" id="cond2_2" <?= mb_strpos($search2Str, "訪問看護　介護保険") !== false ? 'checked' : '' ?>><label for="cond2_2">訪問看護 介護保険</label></li>
                                                <li><input type="checkbox" name="search[search2][]" value="訪問看護　定期巡回" id="cond2_3" <?= mb_strpos($search2Str, "定期巡回") !== false ? 'checked' : '' ?>><label for="cond2_3">定期巡回</label></li>
                                                <li><input type="checkbox" name="search[search2][]" value="自費" id="cond2_4" <?= mb_strpos($search2Str, "自費") !== false ? 'checked' : '' ?>><label for="cond2_4">自費</label></li>
                                                <li><input type="checkbox" name="search[search2][]" value="看多機　訪問介護" id="cond2_5" <?= mb_strpos($search2Str, "看多機　訪問介護") !== false ? 'checked' : '' ?>><label for="cond2_5">看多機 訪問介護</label></li>
                                                <li><input type="checkbox" name="search[search2][]" value="看多機　訪問看護" id="cond2_6" <?= mb_strpos($search2Str, "看多機　訪問看護") !== false ? 'checked' : '' ?>><label for="cond2_6">看多機 訪問看護</label></li>
                                                <li><input type="checkbox" name="search[search2][]" value="看多機　通い" id="cond2_7" <?= mb_strpos($search2Str, "看多機　通い") !== false ? 'checked' : '' ?>><label for="cond2_7">看多機 通い</label></li>
                                                <li><input type="checkbox" name="search[search2][]" value="看多機　宿泊" id="cond2_8" <?= mb_strpos($search2Str, "看多機　宿泊") !== false ? 'checked' : '' ?>><label for="cond2_8">看多機 宿泊</label></li>
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
                                                        <th>当月</th>
                                                        <td><?= $unRoot[1] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>現表示</th>
                                                        <td><?= $unRoot[2] ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!--/// 検索エリア_END ///-->
                                <!-- 予定追加：start -->
                                <div class="btn_box">
                                    <span class="modal_open btn add add-op add2 display_part" data-url="/record/user/dialog/usr_pln_new_edit_dialog.php?id=" data-dialog_name="dynamic_modal" style="width:130px;">予定追加</span>
                                </div>
                                <div class="sp_user_name">
                                    <p><?php echo ($search['user_name'] != "") ? $search['user_name'] : "全ての利用者"?></p>
                                </div>

                                <!-- ダイアログ流し込みエリア -->
                                <div class="modal_setting table_grp grp3"></div>

                                <?php foreach ($planList as $userId => $planList2) : ?>
                                    <!-- 予定実績ヘッダー start -->
                                    <div class="tit_box">
                                        <div class="yotei_box">
                                            <div class="mid">予定　　　　<?= $userList[$userId]['name'] ?>　<button type="button" class="modal_open btn edit" data-url="/record/user/dialog/add_span_dialog.php?id=<?= $userId ?>" data-dialog_name="dynamic_modal" style="width:auto">期間指定加算</button></div>
                                            <table>
                                                <tr>
                                                    <th class="h1">サービス<br>内容</th>
                                                    <th class="h2">時刻</th>
                                                    <th class="h3">基本サービスコード<br>/加算コード</th>
                                                    <th class="h4">対応者</th>
                                                    <th class="h5">最終更新日<br>/更新者</th>
                                                    <th class="h6">保護<br>記録</th>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="jisseki_box">
                                            <div class="mid">実績</div>
                                            <table>
                                                <tr>
                                                    <th class="h2">時刻</th>
                                                    <th class="h3">基本サービスコード<br>/加算コード</th>
                                                    <th class="h4">対応者</th>
                                                    <th class="h5">最終更新日<br>/更新者</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- 予定実績ヘッダー end -->
                                    <?php foreach ($planList2 as $useDay => $planList3) : ?>
                                        <?php foreach ($planList3 as $planId => $planList4) : ?>
                                            <?php $tgtPlan = $planList4['main']; ?>
                                            <!-- 予定実績 start -->
                                            <div class="sched1 sch1">
                                                <div class="mid_date"><?= $tgtPlan['use_from_day'] ?></div>
                                                <div class="box_wrap wrap1 child_toggle">
                                                    <!-- 予定(親) start -->
                                                    <div class="m_row m1_row ms_row">
                                                        <div class="layout-3col">
                                                            <div class="layout-3col-box">
                                                                <div class="row y_row">
                                                                    <div class="service_cont">
                                                                        <?php $class = isset($typeList[$tgtPlan['service_name']]) ? $typeList[$tgtPlan['service_name']]['class'] : ""; ?>
                                                                        <?php $typeName = isset($typeList[$tgtPlan['service_name']]) ? $typeList[$tgtPlan['service_name']]['name'] : ""; ?>
                                                                        <span class="<?= $class ?>"><?= $typeName ?> </span>
                                                                    </div>
                                                                    <div class="time"><?= $tgtPlan['start_time'] ?>~<?= $tgtPlan['end_time'] ?></div>
                                                                    <div class="sched_name"><?= $tgtPlan['base_service_name'] ?>
                                                                        <?php if (isset($tgtPlan['add_name'])) : ?>
                                                                            <small class="sc"><?= $tgtPlan['add_name'] ?></small>
                                                                        <?php else : ?>
                                                                            <small class="sc">&nbsp;</small>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="respondent"><?= $tgtPlan['staff_name'] ?></div>
                                                                    <div class="modified"><span><?= $tgtPlan['update_date'] ?></span><span><?= $tgtPlan['update_name'] ?></span></div>
                                                                    <div class="btn_box">
                                                                        <p>
                                                                            <!-- 看多機記録用hidden情報 -->
                                                                            <input type="hidden" name="upKtk[<?= $planId ?>][unique_id]" value="<?= $tgtPlan['kantaki'] ?>">
                                                                            <input type="hidden" name="upKtk[<?= $planId ?>][service_day]" value="<?= $tgtPlan['use_day'] ?>">
                                                                            <input type="hidden" name="upKtk[<?= $planId ?>][start_time]" value="<?= $tgtPlan['start_time'] ?>">
                                                                            <input type="hidden" name="upKtk[<?= $planId ?>][end_time]" value="<?= $tgtPlan['end_time'] ?>">
                                                                            <input type="hidden" name="upKtk[<?= $planId ?>][user_id]" value="<?= $tgtPlan['user_id'] ?>">


                                                                            <?php $planId = $tgtPlan['unique_id']; ?>
                                                                            <!-- ボタンアクションエリア -->
                                                                            <?php if (empty($tgtPlan['protection_flg'])) : ?>
                                                                                <?php if ($tgtPlan['status'] !== 'キャンセル') : ?>
                                                                                    <?php if (mb_strpos($tgtPlan['service_name'], "訪問看護") === false) : ?>
                                                                                        <button type="submit" name="btnKantaki" class="btn kiroku" value="<?= $planId; ?>">看多機記録</button>
                                                                                    <?php endif; ?>
                                                                                    <?php if (mb_strpos($tgtPlan['service_name'], "訪問看護") !== false) : ?>
                                                                                        <button type="submit" name="btnHokan2" class="btn kiroku" value="<?= $planId; ?>">訪看記録Ⅱ</button>
                                                                                    <?php endif; ?>
                                                                                    <?php if (mb_strpos($tgtPlan['service_name'], "送迎") !== false) : ?>
                                                                                        <button type="submit" name="btnKantaki" class="btn kiroku" value="<?= $planId; ?>">看多機記録</button>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                                <?php if ($tgtPlan['status'] !== '実施' && $tgtPlan['status'] !== 'キャンセル') : ?>
                                                                                    <button type="button" class="modal_open btn edit" data-url="/record/user/dialog/usr_pln_edit_dialog.php?id=<?= $planId ?>" data-dialog_name="dynamic_modal">編集</button>
                                                                                    <button type="button" class="modal_open btn duplicate" data-url="/record/user/dialog/usr_dupli_dialog.php?id=<?= $planId ?>" data-dialog_name="dynamic_modal">複製</button>
                                                                                    <button type="submit" name="btnDelUserPlan" class="btn delete" value="<?= $planId ?>">削除</button>
                                                                                    <button type="button" class="modal_open btn confirm record_entry" data-url="/record/user/dialog/fix_usr_dialog.php?id=<?= $planId ?>"<?= $tgtPlan['disable'] ?> data-dialog_name="dynamic_modal">実績確定</button>
                                                                                    <button type="button" class="modal_open btn change record_entry" data-url="/record/user/dialog/usr_rec_chg_dialog.php?id=<?= $planId ?>"<?= $tgtPlan['disable'] ?> data-dialog_name="dynamic_modal">実績変更</button>
                                                                                <?php endif; ?>
                                                                                <?php if ($tgtPlan['status'] !== 'キャンセル') : ?>
                                                                                    <button type="submit" name="btnCxlUser" class="btn cancel_appt" value="<?= $planId ?>">予定キャンセル</button>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="layout-3col-box">
                                                                <div class="record_cont">
                                                                    <span class="btn_stat"><input type="checkbox" id="<?= $planId ?>" class="protect" value="保護" onclick="return protectionChange('<?= $planId ?>');" <?= !empty($tgtPlan['protection_flg']) ? 'checked="true"' : '' ?>"><label for="<?= $planId ?>"><i></i></label></span>
                                                                    <?php if (!empty($tgtPlan['disable'])) : ?>
                                                                        <br />
                                                                        <br />
                                                                        <?php if (mb_strpos($tgtPlan['service_name'], "訪問看護") !== false) : ?>
                                                                            <span class="kiroku bg-gray2">訪看記録Ⅱ</span>
                                                                        <?php else: ?>
                                                                            <span class="kiroku bg-gray2">看多機記録</span>
                                                                        <?php endif; ?>
                                                                    <?php else : ?>
                                                                        <br />
                                                                        <br />
                                                                        <?php if (mb_strpos($tgtPlan['service_name'], "訪問看護") !== false) : ?>
                                                                            <span class="kiroku">訪看記録Ⅱ</span>
                                                                        <?php else: ?>
                                                                            <span class="kiroku">看多機記録</span>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <!-- 実績(親) start -->
                                                            <div class="layout-3col-box">
                                                                <div class="row j_row">
                                                                    <?php if (isset($rcdList[$userId][$useDay])) : ?>
                                                                        <?php foreach ($rcdList[$userId][$useDay] as $rcdId => $val) : ?>
                                                                            <?php $tgtRcd = $val['main'] ?>
                                                                            <?php if ($tgtRcd['user_plan_id'] == $planId) : ?>
                                                                                <div class="time"><?= $tgtRcd['start_time'] ?>~<?= $tgtRcd['end_time'] ?></div>
                                                                                <div class="sched_name"><?= $tgtRcd['base_service_name'] ?>
                                                                                    <?php if (!empty($tgtRcd['add_name'])) : ?>
                                                                                        <small class="sc"><?= $tgtRcd['add_name'] ?></small>
                                                                                    <?php else : ?>
                                                                                        <small class="sc">&nbsp;</small>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div class="respondent"><?= $tgtRcd['staff_name'] ?></div>
                                                                                <div class="modified"><span><?= $tgtRcd['update_date'] ?></span><span><?= $tgtRcd['update_name'] ?></span></div>
                                                                                <div class="btn_box d_box2">
                                                                                    <p>
                                                                                        <button type="button" class="modal_open btn edit" data-url="/record/user/dialog/usr_rec_dialog.php?id=<?= $tgtRcd['unique_id'] ?>" data-dialog_name="dynamic_modal">編集</button>
                                                                                        <button type="submit" name="btnDelUserRcd" class="btn delete" value="<?= $tgtRcd['unique_id'] ?>">削除</button>
                                                                                        <!-- <span class="btn cancel_appt">予定キャンセル</span> -->

                                                                                    </p>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    <?php else : ?>
                                                                        <div class="temp_txt">実績確定したデータはまだありません</div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <!-- 実績(親) end -->
                                                            <div class="row e_row"></div>
                                                        </div>
                                                        <!-- 予定(親) end -->

                                                        <!-- 詳細展開 start -->
                                                        <div class="breakdown" onclick="obj = document.getElementById('<?= $planId . '_open' ?>').style;
                                                                obj.display = (obj.display == 'none') ? 'block' : 'none';">
                                                            <a class="breakdown" style="cursor:pointer;"><img src="/common/image/arrow_up2.svg" alt="詳細表示"><span style="color:#174A84;font-size:81.3%;">詳細</span></a>
                                                        </div>
                                                        <div id="<?= $planId . '_open' ?>" style="display:none;clear:both;">
                                                            <!-- 詳細展開 end -->

                                                            <!-- 詳細 start -->

                                                            <div class="m_row">
                                                                <div class="layout-3col">
                                                                    <div class="layout-3col-box">
                                                                        <?php if (isset($planList4['service'])) : ?>
                                                                            <?php foreach ($planList4['service'] as $planSvcId => $planSvcInfo) : ?>
                                                                                <div class="row y_row">
                                                                                    <div class="time" style="text-indent:0px;"><?= $planSvcInfo['start_time'] ?>~<?= $planSvcInfo['end_time'] ?></div>
                                                                                    <div class="sched_name">
                                                                                        <?php if (isset($planSvcInfo['WB'])) : ?>
                                                                                            <span class="wb">WB</span>
                                                                                        <?php endif; ?>
                                                                                        <?= $planSvcInfo['service_name'] ?></div>
                                                                                    <div class="sched_name sche_type"><?= $planSvcInfo['type'] ?></div>
                                                                                    <div class="respondent"><?= $planSvcInfo['staff_name'] ?></div>
                                                                                    <div class="modified">
                                                                                        <span><?= $planSvcInfo['update_date'] ?></span>
                                                                                        <span><?= $planSvcInfo['update_name'] ?></span>
                                                                                    <?php if (isset($planSvcInfo['status']) && $planSvcInfo['status'] === "キャンセル") : ?>
                                                                                        <span class="btn1 cancel_appt">予定キャンセル</span>
                                                                                    <?php else : ?>
                                                                                        
                                                                                    <?php endif; ?>
                                                                                    </div>
                                                                                    <!--<div class="modified"><span></span><span></span></div>-->
                                                                                    

                                                                                    <div class="btn_box">
                                                                                        <p>
                                                                                            <input type="hidden" name="upKtk[<?= $planId ?>][unique_id]" value="<?= $planSvcInfo['kantaki'] ?>">
                                                                                            <input type="hidden" name="upKtk[<?= $planId ?>][service_day]" value="<?= $planSvcInfo['use_day'] ?>">
                                                                                            <input type="hidden" name="upKtk[<?= $planId ?>][start_time]" value="<?= $planSvcInfo['start_time'] ?>">
                                                                                            <input type="hidden" name="upKtk[<?= $planId ?>][end_time]" value="<?= $planSvcInfo['end_time'] ?>">
                                                                                            <input type="hidden" name="upKtk[<?= $planId ?>][user_id]" value="<?= $planSvcInfo['user_id'] ?>">

                                                                                            <!-- ボタンアクションエリア -->
                                                                                            <?php if (empty($planSvcInfo['protection_flg'])) : ?>
                                                                                                <?php if ($planSvcInfo['status'] !== 'キャンセル') : ?>
                                                                                                    <?php if (mb_strpos($planSvcInfo['service_name'], "訪問看護") === false) : ?>
                                                                                                        <button type="submit" name="btnKantaki" class="btn kiroku" value="<?= $planId; ?>">看多機記録</button>
                                                                                                    <?php endif; ?>
                                                                                                    <?php if (mb_strpos($planSvcInfo['service_name'], "訪問看護") !== false) : ?>
                                                                                                        <button type="submit" name="btnHokan2" class="btn kiroku" value="<?= $planId; ?>">訪看記録Ⅱ</button>
                                                                                                    <?php endif; ?>
                                                                                                    <?php if (mb_strpos($planSvcInfo['service_name'], "送迎") !== false) : ?>
                                                                                                        <button type="submit" name="btnKantaki" class="btn kiroku" value="<?= $planId; ?>">看多機記録</button>
                                                                                                    <?php endif; ?>
                                                                                                <?php endif; ?>
                                                                                                <?php if ($planSvcInfo['status'] !== '実施' && $planSvcInfo['status'] !== 'キャンセル') : ?>
                                                                                                    <button type="button" class="modal_open btn edit" data-url="/record/user/dialog/usr_pln_edit_dialog.php?id=<?= $planId ?>" data-dialog_name="dynamic_modal">編集</button>
                                                                                                    <button type="button" class="modal_open btn duplicate" data-url="/record/user/dialog/usr_dupli_dialog.php?id=<?= $planId ?>" data-dialog_name="dynamic_modal">複製</button>
                                                                                                    <button type="submit" name="btnDelUserPlanSvc" class="btn delete" value="<?= $planSvcId ?>">削除</button>
                                                                                                    <button type="button" class="modal_open btn confirm record_entry" data-url="/record/user/dialog/fix_usrsvc_dialog.php?id=<?= $planSvcId ?>"<?= $tgtPlan['disable'] ?> data-dialog_name="dynamic_modal">実績確定</button>
                                                                                                    <button type="button" class="modal_open btn change record_entry" data-url="/record/user/dialog/usr_rec_chg_dialog.php?id=<?= $planId ?>"<?= $tgtPlan['disable'] ?> data-dialog_name="dynamic_modal">実績変更</button>
                                                                                                <?php endif; ?>
                                                                                                <?php if ($planSvcInfo['status'] !== 'キャンセル') : ?>
                                                                                                    <button type="submit" name="btnCxlUserSvc" class="btn cancel_appt" value="<?= $planSvcId ?>">予定キャンセル</button>
                                                                                                <?php endif; ?>
                                                                                            <?php endif; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        <?php else : ?>
                                                                            <div class="temp_txt">予定詳細データはありません</div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <!--<div class="center_row"></div>-->
                                                                    <div class="layout-3col-box"></div>
                                                                    <div class="layout-3col-box">
                                                                        <?php if (isset($rcdList[$userId][$useDay])) : ?>
                                                                            <?php $rcdList2 = $rcdList[$userId][$useDay]; ?>
                                                                            <?php foreach ($rcdList2 as $rcdId => $rcdList3) : ?>
                                                                                <?php if ($rcdList3['main']['user_plan_id'] == $planId): ?>
                                                                                    <?php if (isset($rcdList3['service'])) : ?>
                                                                                        <?php foreach ($rcdList3['service'] as $rcdSvcId => $rcdSvcInfo) : ?>
                                                                                            <div class="row j_row">
                                                                                                <div class="time"><?= $rcdSvcInfo['start_time'] ?>~<?= $rcdSvcInfo['end_time'] ?></div>
                                                                                                <div class="sched_name"><?= $rcdSvcInfo['service_name'] ?></div>
                                                                                                <div class="sched_name sche_type"><?= $rcdSvcInfo['type'] ?></div>
                                                                                                <div class="respondent"><?= $rcdSvcInfo['staff_name'] ?></div>
                                                                                                <div class="modified">
                                                                                                    <span><?= $rcdSvcInfo['update_date'] ?></span>
                                                                                                    <span><?= $rcdSvcInfo['update_name'] ?></span>
                                                                                                </div>
                                                                                                <?php if (isset($rcdSvcInfo['status']) && $rcdSvcInfo['status'] === "キャンセル") : ?>
                                                                                                    <span class="btn1 cancel_appt">予定キャンセル</span>
                                                                                                <?php else : ?>
                                                                                                    
                                                                                                <?php endif; ?>
                                                                                                <div class="btn_box">
                                                                                                    <p>
                                                                                                        <button type="button" class="modal_open btn edit" data-url="/record/user/dialog/usr_rec_dialog.php?id=<?= $rcdId ?>" data-dialog_name="dynamic_modal">編集</button>
                                                                                                        <button type="submit" name="btnDelUserRcdSvc" class="btn delete" value="<?= $rcdSvcId ?>">削除</button>
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php endforeach; ?>
                                                                                    <?php else : ?>
                                                                                        <div class="temp_txt">実績詳細データはありません</div>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <!--</div>-->
                                                                    <div class="row e_row"></div>
                                                                </div>
                                                            </div>
                                                            <!-- 詳細 end -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- 予定実績 end -->
                                        <?php endforeach; ?><!-- ($planList3 as $planId => $planList4) -->
                                    <?php endforeach; ?><!-- ($planList2 as $useDay => $planList3) -->
                                <?php endforeach; ?><!-- ($planList as $userId => $planList2) -->

                                <!--ダイアログ呼出し-->
                                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user.php'); ?>
                                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/office.php'); ?>
                                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff.php'); ?>
                                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user2.php'); ?>
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