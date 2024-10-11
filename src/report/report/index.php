<?php require_once(dirname(__FILE__) . "/php/report.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>報告書</title>
        <?php foreach ($otherWindowURL as $otherURL): ?>
            <script>
                $(function () {
                    window.open('<?= $otherURL ?>', '_blank');
                });
            </script>
        <?php endforeach; ?>
        <script>
            $(function () {
                var careKb1 = '<span>保健師、助産師、看護師又は准看護師による訪問日を〇、理学療法士、作業療法士又は言語聴覚士による訪問日を◇で囲むこと。<br>特別訪問看護指示書に基づく訪問看護を実施した日を△で囲むこと。<br>1日に2回以上訪問した日を◎で、長時間訪問看護加算を算定した日を□で囲むこと。<br>なお、右表は訪問日が2月にわたる場合使用すること。</span>';
                var careKb2 = '<span>保健師、看護師又は准看護師による訪問日を○で、理学療法士、作業療法士又は作業療法士による訪問日を◇で囲むこと。<br/>精神科特別訪問看護指示書に基づく訪問看護を実施した日を△で囲むこと。<br/>１日に２回以上訪問した日を◎で、長時間訪問看護加算又は長時間精神科訪問看護加算を算定した日を□で囲むこと。<br/>また、精神科訪問看護報告書においては、30分未満の訪問看護を実施した日に✔印をつけること。<br>なお、右表は訪問日が2月にわたる場合使用すること。</span>';
                // 訪問看護区分切替
                $('input[name="upAry[care_kb]"]:radio').on('change', function () {
                    var radioval = $(this).val();
                    $(".note").children().remove();
                    if (radioval === "訪問看護") {
                        $(careKb1).appendTo(".note");
                    } else if (radioval === "精神科訪問看護") {
                        $(careKb2).appendTo(".note");
                    } else {
                        $(careKb1).appendTo(".note");
                    }
                });

                // 該当年変更イベント
                $(".cal_year").on('change', function () {
                    getCalendarData();
                });

                // 該当月変更イベント
                $(".cal_month").on('change', function () {
                    getCalendarData();
                });
            });

            // Calendar情報を取得する
            function getCalendarData() {
                var id = getUrlParam('id');
                var year = $(".cal_year").val();
                var month = $(".cal_month").val();
                var userId = $(".tgt-unique_id").val();
                var tbl1 = $(".calender_data1").find("table");
                var tbl2 = $(".calender_data2").find("table");

                console.log('id:' + id);
                console.log('userId:' + userId);
                console.log('year:' + year);
                console.log('month:' + month);

                $.ajax({
                    async: false,
                    type: "POST",
                    url: "./ajax/calendar_ajax.php",
                    dataType: "text",
                    data: {
                        "id": id,
                        "year": year,
                        "month": month,
                        "user_id": userId
                    }
                }).done(function (data) {
                    //console.log("処理取得データ : " + data);
                    var split = data.split(',');
                    tbl1.remove();
                    tbl2.remove();

                    $(".calender_data1").append(split[0]);
                    $(".calender_data2").append(split[1]);
                    $(".condition_progress").val(split[2]);
//                    $(newRow).appendTo(ol_wrap);
                    $.getScript("./js/report_marker.js");

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log("ajax通信に失敗しました");
                    console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                    console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                    console.log("errorThrown    : " + errorThrown.message); // 例外情報
                    console.log("URL            : " + url);
                });
            }
            // 複製ボタン押下時の処理
            function duplicate() {
                var url = new URL(window.location.href);
                url.searchParams.append('copy','true');
                location.href = url;
            }
            /**
             * Get the URL parameter value
             *
             * @param  name {string} パラメータのキー文字列
             * @return  url {url} 対象のURL文字列（任意）
             */
            function getUrlParam(name) {
                url = window.location.href;
                name = name.replace(/[\[\]]/g, "\\$&");
                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                        results = regex.exec(url);
                if (!results)
                    return null;
                if (!results[2])
                    return '';
                return decodeURIComponent(results[2].replace(/\+/g, " "));
            }
        </script>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <form action="" method="post" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">
                        <h2>報告書</h2>
                        <div id="patient" class="sm"></div>
                        <div id="subpage"><div id="report" class="nursing rep_details">


                                <div class="new_default i_instructions gaf_inst common_part2 cancel_act">
                                    <div class="close close_part">✕<span>閉じる</span></div>
                                    <div class="sched_tit">訪問看護記録Ⅱ選択</div>
                                    <div class="instruct">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th>訪問看護区分</th>
                                                    <th>訪問年月日</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><button>選択</button></td>
                                                    <td>1</td>
                                                    <td>訪問看護</td>
                                                    <td>2021/10/01</td>
                                                    <td><span class="btn duplicate"><a href="/report/instruct/index.php">複製</a></span></td>
                                                </tr>
                                                <tr>
                                                    <td><button>選択</button></td>
                                                    <td>2</td>
                                                    <td>精神科訪問看護</td>
                                                    <td>2021/09/01</td>
                                                    <td><span class="btn duplicate"><a href="/report/instruct/index.php">複製</a></span></td>
                                                </tr>
                                                <tr>
                                                    <td><button>選択</button></td>
                                                    <td>3</td>
                                                    <td>訪問看護</td>
                                                    <td>2021/10/01</td>
                                                    <td><span class="btn duplicate"><a href="/report/instruct/index.php">複製</a></span></td>
                                                </tr>
                                                <tr>
                                                    <td><button>選択</button></td>
                                                    <td>4</td>
                                                    <td>精神科訪問看護</td>
                                                    <td>2021/09/01</td>
                                                    <td><span class="btn duplicate"><a href="/report/instruct/index.php">複製</a></span></td>
                                                </tr>
                                                <tr>
                                                    <td><button>選択</button></td>
                                                    <td>5</td>
                                                    <td>訪問看護</td>
                                                    <td>2021/10/01</td>
                                                    <td><span class="btn duplicate"><a href="/report/instruct/index.php">複製</a></span></td>
                                                </tr>
                                                <tr>
                                                    <td><button>選択</button></td>
                                                    <td>6</td>
                                                    <td>精神科訪問看護</td>
                                                    <td>2021/09/01</td>
                                                    <td><span class="btn duplicate"><a href="/report/instruct/index.php">複製</a></span></td>
                                                </tr>
                                                <tr>
                                                    <td><button>選択</button></td>
                                                    <td>7</td>
                                                    <td>訪問看護</td>
                                                    <td>2021/10/01</td>
                                                    <td><span class="btn duplicate"><a href="/report/instruct/index.php">複製</a></span></td>
                                                </tr>
                                                <tr>
                                                    <td><button>選択</button></td>
                                                    <td>8</td>
                                                    <td>精神科訪問看護</td>
                                                    <td>2021/09/01</td>
                                                    <td><span class="btn duplicate"><a href="/report/instruct/index.php">複製</a></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="wrap">

                                    <ul class="user-tab">
                                        <li><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
                                        <li class="active"><a href="/report/list/?user=<?= $userId ?>">各種帳票</a></li>
                                        <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
                                    </ul>
                                    <?php if(!empty($dispData['unique_id'])) : ?>
                                        <input type="hidden" name="upAry[unique_id]" value="$dispData['unique_id']">
                                    <?php endif; ?>

                                    <div class="nurse_record user-details">
                                        <div class="line profile">
                                            <div class="line category">
                                                <span class="label_t">訪問看護区分</span>
                                                <span class="req">*</span>
                                                <?php foreach ($gnrList['訪問看護区分'] as $key => $val): ?>
                                                    <?php $check = $dispData['care_kb'] === $val ? ' checked' : NULL; ?>
                                                    <p><input type="radio" name="upAry[care_kb]" class="validate[required] f-keyVal" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></span></label></p>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="name">
                                                <span class="label_t">担当者</span>
                                                <p class="n_search staff_search">Search</p>
                                                <input type="hidden" class="n_num tgt-stf_id f-keyVal" name="upAry[staff_id]" value="<?= $dispData['staff_id'] ?>">
                                                <input type="text" class="n_num tgt-stf_cd f-keyVal" name="upDummy[staff_cd]" value="<?= $dispData['staff_cd'] ?>">
                                                <input type="text" class="n_name tgt-stf_name bg-gray2" name="upDummy[staff_name]" value="<?= $dispData['staff_name'] ?>" readonly="">
                                            </div>
                                            <div class="create_d">
                                                <span class="label_t">作成日</span>
                                                <input type="date" name="upAry[report_day]" class="" style="width:130px" value="<?= $dispData['report_day'] ?>">
                                            </div>
                                            <div class="i_period">
                                                <span class="label_t">有効期間</span>
                                                <input type="date" name="upAry[validate_start]" class="f-keyVal" style="width:130px" value="<?= $dispData['validate_start'] ?>">
                                                <small>～</small>
                                                <input type="date" name="upAry[validate_end]" class="f-keyVal" style="width:130px" value="<?= $dispData['validate_end'] ?>">
                                            </div>
                                            <div class="line3">
                                                <dl>
                                                    <dt>利用者ID</dt>
                                                    <dd>
                                                        <p class="n_search user_search">Search</p>
                                                        <input type="text" name="upDummy[other_id]" class="n_num tgt-usr_id f-keyVal" value="<?= $dispData['other_id'] ?>" maxlength="7" pattern="^[0-9]+$" onchange="getCalendarData();">
                                                        <input type="hidden" name="upAry[user_id]" class="tgt-unique_id f-keyVal" value="<?= $userId ?>">
                                                    </dd>
                                                </dl>
                                                <dl>
                                                    <dt>利用者氏名</dt>
                                                    <dd>
                                                        <input type="text" name="upDummy[user_name]" value="<?= $dispData['user_name'] ?>" class="n_name tgt-usr_name bg-gray2" readonly>
                                                    </dd>
                                                </dl>
                                                <div class="birthday">
                                                    <span class="label_t">生年月日</span>
                                                    <input type="text" name="upDummy[birthday]" value="<?= $dispData['birthday_disp'] ?>" class="n_birthday tgt-usr_birthday bg-gray2" readonly>
                                                    <input type="text" name="upDummy[age]" value="<?= $dispData['age'] ?>" class="n_age tgt-usr_age bg-gray2" readonly>
                                                </div>
                                                <div class="care">
                                                    <span class="label_t">要介護度</span>
                                                    <input type="text" name="upDummy[care_rank]" class="n_rank tgt-usr_rank bg-gray2" value="<?= $dispData['care_rank'] ?>" readonly>
                                                </div>	
                                                <div class="address">
                                                    <span class="label_t">住所</span>
                                                    <input type="text" name="upDummy[user_address]" class="n_adr tgt-usr_adr bg-gray2" value="<?= $dispData['user_address'] ?>" readonly>
                                                </div>
                                            </div>			
                                        </div>
                                        <div class="d_right">
                                            <div class="pc">
                                                <span class="label_t">宛先指定</span>
                                                <p>
                                                    <input type="checkbox" name="upTgtPsn[target_person][]" value="主治医" <?= mb_strpos($dispData['target_person'], "主治医") ? " checked" : '' ?> id="add1" >
                                                    <label for="add1">主治医</label>
                                                    <input type="checkbox" name="upTgtPsn[target_person][]" value="利用者" <?= mb_strpos($dispData['target_person'], "利用者") ? " checked" : '' ?> id="add2">
                                                    <label for="add2">利用者</label>
                                                    <input type="checkbox" name="upTgtPsn[target_person][]" value="ケアマネ" <?= mb_strpos($dispData['target_person'], "ケアマネ") ? " checked" : '' ?> id="add3" >
                                                    <label for="add3">ケアマネ</label>
                                                    <input type="checkbox" name="upTgtPsn[target_person][]" value="その他" <?= mb_strpos($dispData['target_person'], "その他") ? " checked" : '' ?> id="add4">
                                                    <label for="add4">その他</label>
                                                </p>
                                                <p><span class="label_t">印刷日：</span><?= $dispData['print_day'] ?></p>
                                            </div>
                                            <p>
                                                <button type="submit" class="btn print" name="btnPrint" value="<?= $dispData['unique_id'] ?>">印刷</button>
                                                <?php $disabled = empty($keyId) || $dispData['copy'] ? 'disabled' : NULL; ?>
                                                <button type="button" onclick="duplicate();" class="btn-edit" name="btnCopy" value="<?= $dispData['unique_id'] ?>" <?= $disabled ?>>複製</button>
                                                <button type="submit" class="btn-del" name="btnDel" value="<?= $dispData['unique_id'] ?>">削除</button>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="nurse_record report_stat">
                                        <div class="app_month">
                                            <div class="month_c">
                                                <div class="m_head">
                                                    <div class="c_tit">該当月</div>
                                                    <select class="year sm" name="search[year]">
                                                        <?php foreach ($slctYear as $val): ?>
                                                            <?php $select = $val == $tgtYear ? ' selected' : NULL; ?>
                                                            <option<?= $select ?>><?= $val ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <span class="cal_txt sm">年</span>
                                                    <select class="month sm" name="search[month]">
                                                        <?php foreach ($slctMonth as $val): ?>
                                                            <?php $select = $val == $tgtMonthNum ? ' selected' : NULL; ?>
                                                            <option<?= $select ?>><?= $val ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <span class="cal_txt sm">月</span>
                                                </div>
                                                <div class="calendar">
                                                    <p class="pc">
                                                        <select class="year cal_year">
                                                            <?php foreach ($slctYear as $val): ?>
                                                                <?php $select = $val == $tgtYear ? ' selected' : NULL; ?>
                                                                <option<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <span class="cal_txt">年</span>
                                                        <select class="month cal_month" name="search[month]">
                                                            <?php foreach ($slctMonth as $val): ?>
                                                                <?php $select = $val == $tgtMonthNum ? ' selected' : NULL; ?>
                                                                <option<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <span class="cal_txt">月</span>
                                                        <!--<span class="update"><img src="/common/image/icon_calendar-rb.png" alt="Calendar"><img src="/common/image/icon_circle.png" alt="Circle">更新</span>-->
                                                    </p>

                                                    <!-- カレンダー処理 -->
                                                    <script>
                                                        $(function () {
                                                            /* モーダル開閉 */
                                                            $("#report .calendar button.calendar_open").on("click", function () {
                                                                /* モーダル開閉 */
                                                                if (!$(this).hasClass("is-open")) {
                                                                    $(this).next("ul").show();
                                                                    $(this).addClass("is-open");
                                                                } else {
                                                                    $(this).next("ul").hide();
                                                                    $(this).removeClass("is-open");
                                                                }
                                                            });
                                                            /* マーク押下 */
                                                            $("#report .calendar .sched_sign button").on("click", function () {

                                                                /* オブジェクト定義 */
                                                                var td = $(this).parents("td");
                                                                var tgtVal = $(this).val();
                                                                var tgtInput = $(td).find("input[name$='[event_kb]']");
                                                                /* マーク描画 */
                                                                $(td).removeClass();
                                                                var tdClass = "sign-" + tgtVal;
                                                                $(td).addClass(tdClass);
                                                                /* inputにデータを保持 */
                                                                $(tgtInput).val(tgtVal);
                                                                /* モーダルを閉じる */
                                                                $(td).find(".calendar_open").removeClass("is-open");
                                                                $(this).parents(".sched_sign").hide();
                                                            });
                                                            $("#report .calendar button.calendar_open").on("dblclick", function () {
                                                                $(this).parent().removeClass();
                                                                var tgtInput = $(this).parents("td").find("input[name$='[event_kb]']");
                                                                if (tgtInput) {
                                                                    tgtInput.val('');
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                    <!-- カレンダー処理 -->
                                                    <div class="calender_data1">
                                                        <table>
                                                            <!-- カレンダーモーダル -->
                                                            <?php foreach ($cldList1 as $cldList11): ?>
                                                                <tr>
                                                                    <?php foreach ($cldList11 as $day): ?>
                                                                        <?php $tgtDay = $day ? formatDateTime($day, 'j') : NULL; ?>
                                                                        <?php $class = isset($dispEvt[$day]) ? $dispEvt[$day]['event_kb'] : NULL; ?>
                                                                        <td class="sign-<?= $class ?>">
                                                                            <button type="button" class="calendar_open"><?= $tgtDay ?></button>
                                                                            <!-- モーダルエリア -->
                                                                            <ul class="sched_sign">
                                                                                <li>
                                                                                    <button type="button" name="" value="circle1">
                                                                                        <img src="/common/image/sign_circle1.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" name="" value="square">
                                                                                        <img src="/common/image/sign_square.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" name="" value="circle2">
                                                                                        <img src="/common/image/sign_circle2.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" name="" value="diamond">
                                                                                        <img src="/common/image/sign_diamond.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" name="" value="check">
                                                                                        <img src="/common/image/sign_check.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" name="" value="triangle">
                                                                                        <img src="/common/image/sign_triangle.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                            </ul>
                                                                            <!-- 値の保持（name改変可） -->
                                                                            <input type="hidden" name="upAry2[<?= $day ?>][unique_id]" value="<?= $dispEvt[$day]['unique_id'] ?? '' ?>">
                                                                            <input type="hidden" name="upAry2[<?= $day ?>][event_kb]" value="<?= $class ?>">
                                                                        </td>
                                                                    <?php endforeach; ?>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="calendar calendar2">
                                                    <div class="calender_data2">
                                                        <table>
                                                            <!-- カレンダーモーダル -->
                                                            <?php foreach ($cldList2 as $cldList21): ?>
                                                                <tr>
                                                                    <?php foreach ($cldList21 as $day): ?>
                                                                        <?php $tgtDay = $day ? formatDateTime($day, 'j') : NULL; ?>
                                                                        <?php $class = isset($dispEvt[$day]) ? $dispEvt[$day]['event_kb'] : NULL; ?>
                                                                        <td class="sign-<?= $class ?>">
                                                                            <button type="button" class="calendar_open"><?= $tgtDay ?></button>
                                                                            <!-- モーダルエリア -->
                                                                            <ul class="sched_sign">
                                                                                <li>
                                                                                    <button type="button" name="" value="circle1">
                                                                                        <img src="/common/image/sign_circle1.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" name="" value="square">
                                                                                        <img src="/common/image/sign_square.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" name="" value="circle2">
                                                                                        <img src="/common/image/sign_circle2.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" name="" value="diamond">
                                                                                        <img src="/common/image/sign_diamond.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" name="" value="check">
                                                                                        <img src="/common/image/sign_check.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" name="" value="triangle">
                                                                                        <img src="/common/image/sign_triangle.png" alt="">
                                                                                    </button>
                                                                                </li>
                                                                            </ul>
                                                                            <!-- 値の保持（name改変可） -->
                                                                            <input type="hidden" name="upAry2[<?= $day ?>][unique_id]" value="<?= $dispEvt[$day]['unique_id'] ?? '' ?>">
                                                                            <input type="hidden" name="upAry2[<?= $day ?>][event_kb]" value="<?= $class ?>">
                                                                        </td>
                                                                    <?php endforeach; ?>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="note">
                                                    <span>保健師、助産師、看護師又は准看護師による訪問日を〇、理学療法士、作業療法士又は言語聴覚士による訪問日を◇で囲むこと。<br>特別訪問看護指示書に基づく訪問看護を実施した日を△で囲むこと。<br>1日に2回以上訪問した日を◎で、長時間訪問看護加算を算定した日を□で囲むこと。<br>なお、右表は訪問日が2月にわたる場合使用すること。</span>
                                                </div>
                                            </div>
                                            <div class="stat_box">
                                                <table>
                                                    <tr>
                                                        <th class="tit_toggle">病状の経過</th>
                                                        <td class="child_toggle">
                                                            <textarea name="upAry[condition_progress]" class="condition_progress" value="<?= $dispData['condition_progress'] ?>" maxlength="400"><?= $dispData['condition_progress'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="tit_toggle">看護の内容</th>
                                                        <td class="child_toggle">
                                                            <textarea name="upAry[nursing_contents]" class="" value="<?= $dispData['nursing_contents'] ?>" maxlength="400"><?= $dispData['nursing_contents'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="tit_toggle">家庭での介護の状況</th>
                                                        <td class="child_toggle">
                                                            <textarea name="upAry[care_situation]" class="" value="<?= $dispData['care_situation'] ?>" maxlength="400"><?= $dispData['care_situation'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="materials_use">
                                            <div class="c_tit no_bg tit_toggle">衛生材料の使用量および使用状況</div>
                                            <div class="stat_box child_toggle">
                                                <table>
                                                    <tr>
                                                        <th>衛生材料(種類・サイズ)等</th>
                                                        <td>
                                                            <textarea name="upAry[material]" class="" value="<?= $dispData['material'] ?>" maxlength="80"><?= $dispData['material'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>使用及び交換頻度</th>
                                                        <td>
                                                            <textarea name="upAry[material_term]" class="" value="<?= $dispData['material_term'] ?>" maxlength="80"><?= $dispData['material_term'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>使用量</th>
                                                        <td>
                                                            <textarea name="upAry[material_use]" class="" value="<?= $dispData['material_use'] ?>" maxlength="80"><?= $dispData['material_use'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="materials_type">
                                            <div class="c_tit no_bg tit_toggle">衛生材料等の種類・量の変更</div>
                                            <div class="stat_box child_toggle">
                                                <table>
                                                    <tr>
                                                        <th>衛生材料等(種類・サイズ・必要量等）の変更の必要性</th>
                                                        <td>
                                                            <?php foreach ($gnrList['衛生材料等(種類・サイズ・必要量等)の変更の必要性'] as $key => $val): ?>
                                                                <?php $check = $dispData['material_change'] === $val ? ' checked' : NULL; ?>
                                                                <input type="radio" name="upAry[material_change]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label>
                                                            <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>変更内容</th>
                                                        <td>
                                                            <textarea name="upAry[material_detail]" class="" value="<?= $dispData['material_detail'] ?>"><?= $dispData['material_detail'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="provision">
                                            <div class="c_tit no_bg tit_toggle">情報提供</div>
                                            <div class="stat_box child_toggle">
                                                <div class="recipient">
                                                    <div class="mid">訪問看護情報提供に係る情報提供先</div>
                                                    <div class="come">
                                                        <textarea name="upAry[information]" class="" value="<?= $dispData['information'] ?>"><?= $dispData['information'] ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="provi_date">
                                                    <div class="mid">情報提供日</div>
                                                    <div class="come">
                                                        <input type="date" name="upAry[information_day]" class="" style="width:130px;" value="<?= $dispData['information_day'] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="record_info info1">
                                            <div class="special_note">
                                                <p class="c_tit no_bg tit_toggle">特記すべき事項</p>
                                                <p class="txt_a child_toggle">
                                                    <textarea name="upAry[special_report]" class="" value="<?= $dispData['special_report'] ?>" maxlength="80"><?= $dispData['special_report'] ?></textarea>
                                                </p>
                                            </div>
                                            <div class="personnel ">
                                                <div class="creator">
                                                    <p>
                                                        <span class="label_t">作成者①</span>
                                                    <p class="n_search staff2_search">Search</p>
                                                    <input type="text" class="n_name tgt-stf2_name" name="upAry[create_staff1]" value="<?= $dispData['create_staff1'] ?>" readonly="">
                                                    <p>
                                                        <span class="label_t">職種</span>
                                                        <select class="choice" name="upAry[create_job1]">
                                                            <option selected hidden disabled>選択してください</option>
                                                            <?php foreach ($gnrList['作成者①_職種'] as $key => $val): ?>
                                                                <?php $select = $dispData['create_job1'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </p>
                                                </div>
                                                <div class="creator">
                                                    <p>
                                                        <span class="label_t">作成者②</span>
                                                    <p class="n_search staff3_search">Search</p>
                                                    <input type="text" class="n_name tgt-stf3_name" name="upAry[create_staff2]" value="<?= $dispData['create_staff2'] ?>" readonly="">
                                                    <p>
                                                        <span class="label_t">職種</span>
                                                        <select class="choice" name="upAry[create_job2]">
                                                            <option selected hidden disabled>選択してください</option>
                                                            <?php foreach ($gnrList['作成者②_職種'] as $key => $val): ?>
                                                                <?php $select = $dispData['create_job2'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </p>
                                                </div>
                                                <div class="creator">
                                                    <p>
                                                        <span class="label_t">管理者</span>
                                                    <p class="n_search staff4_search">Search</p>
                                                    <input type="text" class="n_name tgt-stf4_name" name="upAry[manager]" value="<?= $dispData['manager'] ?>" readonly="">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="record_info info2 disnon">
                                            <div class="special_note">
                                                <p class="c_tit no_bg tit_toggle">特記すべき事項</p>
                                                <p class="txt_a child_toggle">
                                                    <textarea name="upDummy[special_report]" class="" value="<?= $dispData['special_report'] ?>"><?= $dispData['special_report'] ?></textarea>
                                                </p>
                                            </div>
                                            <div class="personnel">
                                                <div class="gaf">
                                                    <div class="tit no_bg tit_toggle">GAF尺度</div>
                                                    <div class="box_wrap child_toggle">
                                                        <div class="copy_btn display_part2">訪看Ⅱから選択</div>
                                                        <div class="point_summary">
                                                            <div class="box1">
                                                                <div class="mid">点数</div>
                                                                <div class="inp">
                                                                    <input type="text" name="upAry[gaf_score]" class="" value="<?= $dispData['gaf_score'] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="box2">
                                                                <div class="mid">日付</div>
                                                                <div class="inp">
                                                                    <input type="date" name="upAry[gaf_date]" class="" value="<?= $dispData['gaf_date'] ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="creator">
                                                    <p>
                                                        <span class="label_t">作成者①</span>
                                                    <p class="n_search staff2_search">Search</p>
                                                    <input type="text" class="n_name tgt-stf2_name" name="upDummy[create_staff1]" value="<?= $dispData['create_staff1'] ?>" readonly="">
                                                    </p>
                                                    <p>
                                                        <span class="label_t">職種</span>
                                                        <select class="choice">
                                                            <?php foreach ($gnrList['作成者①_職種'] as $key => $val): ?>
                                                                <?php $select = $dispData['create_job1'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </p>
                                                </div>
                                                <div class="creator">
                                                    <p>
                                                        <span class="label_t">作成者②</span>1
                                                    <p class="n_search staff3_search">Search</p>
                                                    <input type="text" class="n_name tgt-stf3_name" name="upDummy[create_staff2]" value="<?= $dispData['create_staff2'] ?>" readonly="">
                                                    </p>
                                                    <p>
                                                        <span class="label_t">職種</span>
                                                        <select class="choice">
                                                            <?php foreach ($gnrList['作成者②_職種'] as $key => $val): ?>
                                                                <?php $select = $dispData['create_job1'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </p>
                                                </div>
                                                <div class="admin">
                                                    <p>
                                                        <span class="label_t">管理者</span>
                                                    <p class="n_search staff4_search">Search</p>
                                                    <input type="text" class="n_name tgt-stf4_name" name="upDummy[manager]" value="<?= $dispData['manager'] ?>" readonly="">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="record4 doctor_info">
                                            <div class="tit tit_toggle">主治医情報</div>
                                            <div class="box_wrap child_toggle">
                                                <dd class="f-keyData" data-tg_url='/report/report/ajax/doctor_ajax.php?type=doctor'>
                                                    <div class="copy_btn ref_doctor modal_open"
                                                         data-url="/common/dialog/InstructionCopy.php?user=<?= $dispData['user_id'] ?>" 
                                                         data-dialog_name="dynamic_modal">
                                                        指示書から反映</div>
                                                </dd>
                                                <div class="box-l">
                                                    <div class="institution">
                                                        <span class="label_t">医療機関名称</span>
                                                        <input type="text" name="upAry[medical_institution]" class="tgt-doc_hosp set_hospital" value="<?= $dispData['medical_institution'] ?>">
                                                    </div>
                                                    <div class="physician">
                                                        <span class="label_t">主治医</span>
                                                        <input type="text" name="upAry[doctor]" class="tgt-doc_doc set_doctor" value="<?= $dispData['doctor'] ?>">
                                                    </div>
                                                </div>
                                                <div class="box-r">
                                                    <div class="location">
                                                        <span class="label_t">所在地</span>
                                                        <input type="text" name="upAry[address]" class="tgt-doc_adr set_address1" value="<?= $dispData['address'] ?>">
                                                    </div>
                                                    <div class="number">
                                                        <p><span class="label_t">電話番号①</span>
                                                            <input type="tel" name="upAry[tel1]" class="tgt-doc_tel1 set_tel1" value="<?= $dispData['tel1'] ?>">
                                                        <p><span class="label_t">電話番号②</span>
                                                            <input type="tel" name="upAry[tel2]" class="tgt-doc_tel2 set_tel2" value="<?= $dispData['tel2'] ?>">
                                                        <p><span class="label_t">ＦＡＸ 　　</span>
                                                            <input type="tel" name="upAry[fax]" class="tgt-doc_fax set_fax" value="<?= $dispData['fax'] ?>">
                                                    </div>				
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nurse_record record8">
                                        <span class="label_t">作成状態</span>
                                        <?php $check = $dispData['status'] === '完成' ? ' checked' : NULL; ?>
                                        <input type="radio" name="upAry[status]" value="完成"<?= $check ?>>
                                        <label for="完成">完成</label>
                                        <?php $check = $dispData['status'] !== '完成' ? ' checked' : NULL; ?>
                                        <input type="radio" name="upAry[status]" value="作成中"<?= $check ?>>
                                        <label for="作成中">作成中</label>
                                    </div>
                                    <div class="nurse_record record9">
                                        <div class="i_register">
                                            <span class="label_t">初回登録:</span>
                                            <span class=""><?= $dispData['create_date'] ?></span>
                                            <span class=""><?= $dispData['create_name'] ?></span>
                                        </div>
                                        <div class="l_update">
                                            <span class="label_t">最終更新:</span>
                                            <span class=""><?= $dispData['update_date'] ?></span>
                                            <span class=""><?= $dispData['update_name'] ?></span>
                                        </div>
                                    </div>

                                    <!-- ダイアログ流し込みエリア -->
                                    <div class="modal_setting"></div>

                                    <!--ダイアログ呼出し-->
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff2.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff3.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff4.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/doctor.php'); ?>
                                </div>

                            </div></div>
                        <!--/// CONTENT_END ///-->
                        <div class="fixed_navi">
                            <div class="box">
                                <!--<div class="btn back pc"><button type="submit" name="btnReturn" value="true">利用者一覧にもどる</button></div>-->
                                <div class="btn back pc"><button type="submit" name="btnReturn" value="true">報告書一覧に戻る</button></div>
                                <div class="btn back sm"><a href="/report/list/index.php"><img src="/common/image/icon_return.png" alt="Return"></a></div>
                                <div class="controls">
                                    <button type="submit" class="btn save" name="btnEntry" value="保存">保存
                                </div>
                            </div>
                        </div>
                    </form>
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>