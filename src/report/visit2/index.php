<?php require_once(dirname(__FILE__) . "/php/visit2.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>訪問看護記録Ⅱ詳細</title>
        <?php foreach ($otherWindowURL as $otherURL): ?>
            <script>
                $(function () {
                    window.open('<?= $otherURL ?>', '_blank');
                });
            </script>
        <?php endforeach; ?>
        <script>
            // 直近の医師からの指示を取得する
            function getDoctorInstructionData() {
                var userId = $(".tgt-unique_id").val();
                if (userId === null) {
                    return false;
                }
                $.ajax({
                    async: false,
                    type: "pjson",
                    url: "./ajax/doctor_instruction.php",
                    dataType: "text",
                    data: {
                        "user_id": userId
                    }
                }).done(function (data) {
                    console.log("処理取得データ : " + data);
                    $(".doctor_instruction").val(data);
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log("ajax通信に失敗しました");
                    console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
                    console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー
                    console.log("errorThrown    : " + errorThrown.message); // 例外情報
                    console.log("URL            : " + url);
                });
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
                        <h2 class="tit_sm">訪問看護記録Ⅱ<span class="n_rec2">詳細</span></h2>
                        <div id="patient" class="sm"><?= $dispData['user_name'] ?></div>
                        <div id="subpage"><div id="nurse-record2" class="nursing">
                                <div class="wrap">
                                    <ul class="user-tab">
                                        <li><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
                                        <li class="active"><a href="/report/list/?user=<?= $userId ?>">各種帳票</a></li>
                                        <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
                                    </ul>
                                    <div class="nurse_record user-details">
                                        <div class="d_left">
                                            <div class="box1 profile">
                                                <div class="user_id">
                                                    <span class="label_t">利用者ID</span>
                                                    <p class="n_search user_search">Search</p>
                                                    <input type="text" name="upDummy[other_id]" class="tgt-usr_id n_num" value="<?= $dispData['other_id'] ?>" maxlength="7" pattern="^[0-9]+$" onchange="getDoctorInstructionData();">
                                                    <input type="hidden" name="upAry[user_id]" class="tgt-unique_id" value="<?= $userId ?>">
                                                </div>
                                                <div class="user_name">
                                                    <span class="label_t">利用者氏名</span>
                                                    <input type="text" name="upDummy[user_name]" value="<?= $dispData['user_name'] ?>" class="tgt-usr_name n_name bg-gray2" readonly>
                                                </div>
                                                <div class="category">
                                                    <span class="label_t">訪問看護区分</span>
                                                    <?php foreach ($gnrList['訪問看護区分'] as $val): ?>
                                                        <?php $check = $dispData['care_kb'] == $val ? ' checked' : NULL; ?>
                                                        <p><input type="radio" name="upAry[care_kb]" value="<?= $val ?>"<?= $check ?>><label><?= $val ?></label></p>
                                                    <?php endforeach; ?>
                                                    <?php $checked = !empty($dispData['importantly']) ? ' checked' : NULL; ?>
                                                    <span class="juuyou"><input type="checkbox" name="upAry[importantly]" value="重要" id="" <?= $checked ?> class=""><label>重要</label></span>
                                                </div>
                                            </div>
                                            <div class="wrap1">
                                                <div class="profile prof1">
                                                    <div class="visit">
                                                        <span class="label_t">サービス<br class="sm">提供日</span>
                                                        <input type="date" name="upAry[service_day]" class="" style="width:130px;" value="<?= $dispData['service_day'] ?>">
                                                        <span class="time">
                                                            <select name="upTime[start_time_h]" style="width:60px;">
                                                                <?php foreach ($selHour as $val) : ?>
                                                                    <?php $selected = strpos($dispData['start_time'], $val . ":") !== false ? ' selected' : ""; ?>
                                                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <small>：</small>
                                                            <select name="upTime[start_time_m]" style="width:60px;">
                                                                <?php foreach ($selMinutes as $val) : ?>
                                                                    <?php $selected = strpos($dispData['start_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                                                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <small>～</small>
                                                            <select name="upTime[end_time_h]" class="select_time" style="width:60px;">
                                                                <?php foreach ($selHour as $val) : ?>
                                                                    <?php $selected = strpos($dispData['end_time'], $val . ":") !== false ? ' selected' : ""; ?>
                                                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <small>：</small>
                                                            <select name="upTime[end_time_m]" class="select_time" style="width:60px;">
                                                                <?php foreach ($selMinutes as $val) : ?>
                                                                    <?php $selected = strpos($dispData['end_time'], ":" . $val) !== false ? ' selected' : ""; ?>
                                                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                    <div class="visit">
                                                        <span class="label_t">次回サービス<br class="sm">提供日</span>
                                                        <input type="date" name="upAry[next_day]" class="" style="width:130px;" value="<?= $dispData['next_day'] ?>">
                                                        <span class="time">
                                                            <select name="upTime[next_start_h]" style="width:60px;">
                                                                <?php foreach ($selHour as $val) : ?>
                                                                    <?php $selected = strpos($dispData['next_start'], $val . ":") !== false ? ' selected' : ""; ?>
                                                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <small>：</small>
                                                            <select name="upTime[next_start_m]" style="width:60px;">
                                                                <?php foreach ($selMinutes as $val) : ?>
                                                                    <?php $selected = strpos($dispData['next_start'], ":" . $val) !== false ? ' selected' : ""; ?>
                                                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <small>～</small>
                                                            <select name="upTime[next_end_h]" class="select_time" style="width:60px;">
                                                                <?php foreach ($selHour as $val) : ?>
                                                                    <?php $selected = strpos($dispData['next_end'], $val . ":") !== false ? ' selected' : ""; ?>
                                                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <small>：</small>
                                                            <select name="upTime[next_end_m]" class="select_time" style="width:60px;">
                                                                <?php foreach ($selMinutes as $val) : ?>
                                                                    <?php $selected = strpos($dispData['next_end'], ":" . $val) !== false ? ' selected' : ""; ?>
                                                                    <option value="<?= $val ?>" <?= $selected ?>><?= $val ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="profile pro2">
                                                    <div class="name">
                                                        <span class="label_t">訪問<br class="sm">スタッフ1</span>
                                                        <p class="n_search staff_search">Search</p>
                                                        <input type="hidden" class="n_num tgt-stf_id" name="upAry[staff1_id]" value="<?= $dispData['staff1_id'] ?>">
                                                        <input type="text" class="n_num tgt-stf_cd" name="dummy[staff1_cd]" value="<?= $dispData['staff1_cd'] ?>">
                                                        <input type="text" class="n_name tgt-stf_name bg-gray2" name="upDummy[staff1_name]" value="<?= $dispData['staff1_name'] ?>" readonly="">
                                                        <select name="upAry[visit1_job]">
                                                            <?php foreach ($gnrList['訪問スタッフ1_資格'] as $val): ?>
                                                                <?php $select = $dispData['visit1_job'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="name">
                                                        <span class="label_t">訪問<br class="sm">スタッフ2</span>
                                                        <p class="n_search staff2_search">Search</p>
                                                        <input type="hidden" class="n_num tgt-stf2_id" name="upAry[staff2_id]" value="<?= $dispData['staff2_id'] ?>">
                                                        <input type="text" class="n_num tgt-stf2_cd" name="upDummy[staff2_cd]" value="<?= $dispData['staff2_cd'] ?>">
                                                        <input type="text" class="n_name tgt-stf2_name bg-gray2" name="upDummy[staff2_name]" value="<?= $dispData['staff2_name'] ?>" readonly="">
                                                        <select name="upAry[visit2_job]">
                                                            <?php foreach ($gnrList['訪問スタッフ2_資格'] as $val): ?>
                                                                <?php $select = $dispData['visit2_job'] == $val ? ' selected' : NULL; ?>
                                                                <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d_right">
                                            <p>
                                                <button type="submit" class="btn print" name="btnPrint" value="<?= $dispData['unique_id'] ?>">印刷</button>
                                                <button type="submit" class="btn-del" name="btnDel" value="<?= $dispData['unique_id'] ?>">削除</button>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="nurse_record vitals">
                                        <div class="d_left">
                                            <div class="tit tit_toggle">バイタルサイン</div>
                                            <div class="child_toggle">
                                                <table>
                                                    <tr>
                                                        <th><span class="label_t">体温</span></th>
                                                        <td><p><input type="text" name="upAry[temperature]" class="num_data" value="<?= $dispData['temperature'] ?>"><span class="unit">℃</span></p></td>
                                                    </tr>
                                                    <tr>
                                                        <th><span class="label_t">脈拍</span></th>
                                                        <td><p><input type="text" name="upAry[pulse]" class="num_data" value="<?= $dispData['pulse'] ?>"><span class="unit">／分</span></p></td>
                                                    </tr>
                                                    <tr>
                                                        <th><span class="label_t">血圧</span></th>
                                                        <td>
                                                            <p class="d_inl"><span class="unit">上</span><input type="text" name="upAry[blood_pressure1]" class="num_data" value="<?= $dispData['blood_pressure1'] ?>"></p>
                                                            <p class="d_inl"><span class="unit">下</span><input type="text" name="upAry[blood_pressure2]" class="num_data" value="<?= $dispData['blood_pressure2'] ?>"><span class="unit">／mmHg</span></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><span class="label_t">呼吸</span></th>
                                                        <td>
                                                            <p class="d_inl">
                                                                <input type="text" name="upAry[pneusis]" class="num_data" value="<?= $dispData['pneusis'] ?>">
                                                                <span class="unit">／分</span>
                                                            </p>
                                                            <p class="d_inl">
                                                                <span class="unit">右</span>
                                                                <select name="upAry[pneusis_right]" class="">
                                                                    <option value=""></option>
                                                                    <?php foreach ($gnrList['右'] as $key => $val): ?>
                                                                        <?php $select = $dispData['pneusis_right'] == $val ? ' selected' : NULL; ?>
                                                                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </p>
                                                            <p class="d_inl">
                                                                <span class="unit">左</span>
                                                                <select name="upAry[pneusis_left]" class="">
                                                                    <option value=""></option>
                                                                    <?php foreach ($gnrList['左'] as $key => $val): ?>
                                                                        <?php $select = $dispData['pneusis_left'] == $val ? ' selected' : NULL; ?>
                                                                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><span class="label_t">SpO2</span></th>
                                                        <td><p><input type="text" name="upAry[spo2]" class="num_data" value="<?= $dispData['spo2'] ?>"><span class="unit">％</span></p></td>
                                                    </tr>
                                                    <tr>
                                                        <th><span class="label_t">栄養・代謝</span></th>
                                                        <td>
                                                            <p>
                                                                <select name="upAry[metabolism_kb]" class="">
                                                                    <?php foreach ($gnrList['栄養・代謝'] as $key => $val): ?>
                                                                        <?php $select = $dispData['metabolism_kb'] == $val ? ' selected' : NULL; ?>
                                                                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <input class="i2" type="text" name="upAry[metabolism_detail]" value="<?= $dispData['metabolism_detail'] ?>">
                                                            </p>
                                                            <p><input type="text" name="upAry[metabolism_memo]" value="<?= $dispData['metabolism_memo'] ?>"></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><span class="label_t">睡眠・休息</span></th>
                                                        <td>
                                                            <p>
                                                                <select name="upAry[rest_kb]" class="">
                                                                    <?php foreach ($gnrList['睡眠・休息'] as $key => $val): ?>
                                                                        <?php $select = $dispData['rest_kb'] == $val ? ' selected' : NULL; ?>
                                                                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <input class="i2" type="text" name="upAry[rest_memo]" value="<?= $dispData['rest_memo'] ?>">
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><span class="label_t">排尿</span></th>
                                                        <td>
                                                            <p>
                                                                <input type="text" name="upAry[urination_frequency]" class="num_data" value="<?= $dispData['urination_frequency'] ?>">
                                                                <span class="unit">／</span>
                                                                <select name="upAry[urination_term]" class="">
                                                                    <?php foreach ($gnrList['排尿'] as $key => $val): ?>
                                                                        <?php $select = $dispData['urination_term'] == $val ? ' selected' : NULL; ?>
                                                                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><span class="label_t">排便</span></th>
                                                        <td>
                                                            <p class="d_inl">
                                                                <input type="text" name="upAry[evacuation_frequency]" class="num_data" value="<?= $dispData['evacuation_frequency'] ?>">
                                                                <span class="unit">／</span>
                                                                <select name="upAry[evacuation_term]" class="">
                                                                    <?php foreach ($gnrList['排便'] as $key => $val): ?>
                                                                        <?php $select = $dispData['evacuation_term'] == $val ? ' selected' : NULL; ?>
                                                                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </p>
                                                            <!-- データ保持 -->
                                                            <p class="d_inl w_btn" style="height:38.2px;">
                                                                <!-- 初期値 -->
                                                                <input type="number" class="selected_num selected_num_input" style="width:54px;height:34px;" min="1" max="7" name="upAry[bristol]" value="<?= !empty($dispData['bristol']) ? $dispData['bristol'] : '' ?>">
                                                                <span class="scale_menu display_part1" style="height:34px;">Bristol便形状スケール</span>
                                                            </p>
                                                            <p><input type="text" name="upAry[evacuation_memo]" value="<?= $dispData['evacuation_memo'] ?>"></p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="d_right">
                                            <div class="orders">
                                                <span class="label_t text_blue">医師からの指示</span>
                                                <textarea name="upAry[doctor_instruction]" value="<?= $dispData['doctor_instruction'] ?>" class="doctor_instruction"><?= $dispData['doctor_instruction'] ?></textarea>
                                            </div>
                                            <div class="remarks">
                                                <span class="label_t text_blue">備考</span>
                                                <textarea name="upAry[remarks]" value="<?= $dispData['remarks'] ?>"><?= $dispData['remarks'] ?></textarea>
                                            </div>
                                            <div class="exam_date">
                                                <span class="label_t text_blue">主治医次回診察日</span>
                                                <input type="date" name="upAry[next_examination]" class="" style="width:130px;" value="<?= $dispData['next_examination'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="problem">
                                        <div class="h_wrap">
                                            <p>No</p>
                                            <p>問題点/記録</p>
                                        </div>
                                        <div class="slide_wrap">
                                            <?php if (!empty($dispPrb)): ?>
                                                <?php foreach ($dispPrb as $seq => $val): ?>
                                                    <dl class="activated">
                                                        <dt class="active">
                                                            <input type="text" name="upPrb[<?= $seq ?>][problem]" value="<?= $val['problem'] ?>" placeholder="入力してください">
                                                        </dt>
                                                        <dd>
                                                            <textarea name="upPrb[<?= $seq ?>][comment]" value="<?= $val['comment'] ?>" placeholder="入力してください"><?= $val['comment'] ?></textarea>
                                                            <input type="hidden" name="upPrb[<?= $seq ?>][unique_id]" value="<?= $val['unique_id'] ?>">
                                                            <input type="hidden" name="upPrb[<?= $seq ?>][visit2_id]" value="<?= $keyId ?>">
                                                            <input type="hidden" name="upPrb[<?= $seq ?>][problem_id]" value="<?= $val['problem_id'] ?>">
                                                        </dd>
                                                    </dl>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <dl class="activated">
                                                    <dt class="active">
                                                        <input type="text" name="upPrb[1][problem]" value="" placeholder="入力してください">
                                                    </dt>
                                                    <dd>
                                                        <textarea name="upPrb[1][comment]" value="" placeholder="入力してください"></textarea>
                                                    </dd>
                                                </dl>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="scale">
                                        <div class="gaf_sc display_part2">GAF尺度</div>
                                        <div class="point"><input type="text" name="upAry[gaf]" value="<?= $dispData['gaf'] ?>"><span class="unit">点</span></div>
                                    </div>
                                    <div class="nurse_record care">
                                        <div class="tit tit_toggle">処置・ケア</div>
                                        <div class="box_wrap child_toggle">
                                            <dl>
                                                <dt>身体介助</dt>
                                                <?php foreach ($gnrList['身体介助'] as $key => $val): ?>
                                                    <dd>
                                                        <?php $check = strpos($dispData['deal_care'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                        <label><input type="checkbox" name="upDummy[deal_care][]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label>
                                                    </dd>
                                                <?php endforeach; ?>
                                            </dl>
                                            <dl>
                                                <dt>処置</dt>
                                                <?php foreach ($gnrList['処置'] as $key => $val): ?>
                                                    <dd>
                                                        <?php $check = strpos($dispData['deal_care'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                        <label><input type="checkbox" name="upDummy[deal_care][]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label>
                                                    </dd>
                                                <?php endforeach; ?>
                                            </dl>
                                            <dl>
                                                <dt>管理・指導</dt>
                                                <?php foreach ($gnrList['管理・指導'] as $key => $val): ?>
                                                    <dd>
                                                        <?php $check = strpos($dispData['deal_care'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                        <label><input type="checkbox" name="upDummy[deal_care][]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label>
                                                    </dd>
                                                <?php endforeach; ?>
                                            </dl>
                                            <dl>
                                                <dt>リハビリ</dt>
                                                <?php foreach ($gnrList['リハビリ'] as $key => $val): ?>
                                                    <dd>
                                                        <?php $check = strpos($dispData['deal_care'], $val) !== FALSE ? ' checked' : NULL; ?>
                                                        <label><input type="checkbox" name="upDummy[deal_care][]" value="<?= $val ?>"<?= $check ?>><?= $val ?></label>
                                                    </dd>
                                                <?php endforeach; ?>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="others">
                                        <span class="label_t text_blue">その他(例：ストマの洗浄、服薬セット、など)</span>
                                        <textarea name="upAry[other]" value="<?= $dispData['other'] ?>" placeholder="入力してください"><?= $dispData['other'] ?></textarea>
                                    </div>
                                    <input type="hidden" name="upAry[status]" value="完成" >
                                    <div class="nurse_record record9">
                                        <div class="i_register">
                                            <span class="label_t">初回登録:</span>
                                            <span class="label_t hidzuke"><?= $dispData['create_day'] ?></span>
                                            <span class="label_t time"><?= $dispData['create_time'] ?></span>
                                            <span class="label_t"><?= $dispData['create_name'] ?></span>
                                        </div>
                                        <div class="l_update">
                                            <span class="label_t">最終更新:</span>
                                            <span class="label_t hidzuke"><?= $dispData['update_day'] ?></span>
                                            <span class="label_t time"><?= $dispData['update_time'] ?></span>
                                            <span class="label_t"><?= $dispData['update_name'] ?></span>
                                        </div>
                                    </div>

                                    <!-- Bristol便形状スケール -->
                                    <script>
                                        $(function () {
                                            $(".bristol_scale table td button").on("click", function () {
                                                var num = $(this).data("num");

                                                /* 数値書き換え */
                                                $(".selected_num").text(num);
                                                $(".selected_num_input").val(num);

                                                /* ウィンドウを閉じる */
                                                $(".bristol_scale").hide();
                                            });
                                        });
                                    </script>
                                    <div class="new_default bristol_scale common_part1 cancel_act">
                                        <div class="close close_part">✕<span>閉じる</span></div>
                                        <div class="cont_main">
                                            <h2>Bristol便形状スケール</h2>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th colspan="2">タイプ</th>
                                                        <th>図</th>
                                                        <th>説明</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <button type="button" data-num="1">選択</button>
                                                        </td>
                                                        <td>1</td>
                                                        <td>コロコロ便</td>
                                                        <td>
                                                            <img src="/common/image/sub/bristol_01.png" alt="コロコロ便">
                                                        </td>
                                                        <td>分離した硬い木の実のような便<br>（排便困難を伴う）</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button type="button" data-num="2">選択</button>
                                                        </td>
                                                        <td>2</td>
                                                        <td>硬い便</td>
                                                        <td>
                                                            <img src="/common/image/sub/bristol_02.png" alt="硬い便">
                                                        </td>
                                                        <td>硬便が集合したソーセージ状の便</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button type="button" data-num="3">選択</button>
                                                        </td>
                                                        <td>3</td>
                                                        <td>やや硬い便</td>
                                                        <td>
                                                            <img src="/common/image/sub/bristol_03.png" alt="やや硬い便">
                                                        </td>
                                                        <td>表面にひび割れがあるソーセージ状の便</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button type="button" data-num="4">選択</button>
                                                        </td>
                                                        <td>4</td>
                                                        <td>普通便</td>
                                                        <td>
                                                            <img src="/common/image/sub/bristol_04.png" alt="普通便">
                                                        </td>
                                                        <td>平滑で軟らかいソーセージ状あるいは蛇状の便</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button type="button" data-num="5">選択</button>
                                                        </td>
                                                        <td>5</td>
                                                        <td>やや軟らかい便</td>
                                                        <td>
                                                            <img src="/common/image/sub/bristol_05.png" alt="やや軟らかい便">
                                                        </td>
                                                        <td>軟らかく割面が鋭い小塊状の弁<br>（排便が容易）</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button type="button" data-num="6">選択</button>
                                                        </td>
                                                        <td>6</td>
                                                        <td>泥状便</td>
                                                        <td>
                                                            <img src="/common/image/sub/bristol_06.png" alt="泥状便">
                                                        </td>
                                                        <td>ふわふわした不定形の小片弁、泥状便</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button type="button" data-num="7">選択</button>
                                                        </td>
                                                        <td>7</td>
                                                        <td>水様便</td>
                                                        <td>
                                                            <img src="/common/image/sub/bristol_07.png" alt="水様便">
                                                        </td>
                                                        <td>固形物を含まない水様便</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- GAF尺度 -->
                                    <div class="new_default gaf_scale common_part2 cancel_act" style="height:600px;margin-top: 25px; margin-bottom: 40px;">
                                        <div class="close close_part">✕<span>閉じる</span></div>
                                        <div class="cont_main">
                                            <img src="/common/image/sub/gaf_scale.png" alt="GAF尺度">
                                        </div>
                                    </div>

                                    <!--ダイアログ呼出し-->
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff2.php'); ?>
                                </div>

                            </div></div>
                        <!--/// CONTENT_END ///-->
                        <div class="fixed_navi patient_navi record2-navi">
                            <div class="box">
                                <div class="btn back pc"><button type="submit" name="btnReturn" value="true">記録一覧にもどる</button></div>
                                <div class="controls">
                                    <button type="submit" class="btn save" name="btnEntry" value="保存">保存</button>
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