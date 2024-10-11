<?php require_once(dirname(__FILE__) . "/php/bedsore.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>褥瘡計画</title>
        <?php foreach ($otherWindowURL as $otherURL): ?>
            <script>
                $(function () {
                    window.open('<?= $otherURL ?>', '_blank');
                });
            </script>
        <?php endforeach; ?>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <form action="" method="post" class="p-form-validate" enctype="multipart/form-data" accept-charset="UTF-8">
                        <h2>褥瘡計画</h2>
                        <div id="patient" class="sm"></div>
                        <div id="subpage"><div id="bedsore" class="nursing">
                                <div class="wrap">
                                    <ul class="user-tab">
                                        <li><a href="/user/edit/?user=<?= $userId ?>">基本情報</a></li>
                                        <li class="active"><a href="/report/list/?user=<?= $userId ?>">各種帳票</a></li>
                                        <li><a href="/image/list/?user=<?= $userId ?>">画像関連</a></li>
                                    </ul>
                                    <div class="nurse_record user-details">
                                        <div class="d_left">
                                            <div class="line profile">
                                                <div class="name">
                                                    <span class="label_t">担当者</span>
                                                    <p class="n_search staff_search">Search</p>
                                                    <input type="hidden" class="n_num tgt-stf_id" name="upAry[staff_id]" value="<?= $dispData['staff_id'] ?>">
                                                    <input type="text" class="n_num tgt-stf_cd" name="upDummy[staff_cd]" value="<?= $dispData['staff1_cd'] ?>">
                                                    <input type="text" class="n_name tgt-stf_name bg-gray2" name="upDummy[staff_name]" value="<?= $dispData['staff1_name'] ?>" readonly="">
                                                </div>
                                                <dl>
                                                    <dt>利用者ID</dt>
                                                    <dd>
                                                        <p class="n_search user_search">Search</p>
                                                        <input type="text" name="upDummy[other_id]" class="n_num tgt-usr_id" value="<?= $dispData['other_id'] ?>" maxlength="7" pattern="^[0-9]+$">
                                                        <input type="hidden" name="upAry[user_id]" class="tgt-unique_id" value="<?= $userId ?>">
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
                                                    <input type="text" name="upDummy[address]" class="n_adr tgt-usr_adr bg-gray2" value="<?= $dispData['address'] ?>" readonly>
                                                </div>
                                                <div class="line3">
                                                    <div class="pl_date">
                                                        <span class="label_t">計画作成日</span>
                                                        <input type="date" name="upAry[plan_day]" class="" value="<?= $dispData['plan_day'] ?>">
                                                    </div>
                                                    <div class="rep_date">
                                                        <span class="label_t">褥瘡発生日<small class="red">*</small></span>
                                                        <input type="date" name="upAry[bedsore_day]" class="" value="<?= $dispData['bedsore_day'] ?>" required>
                                                    </div>
                                                </div>
                                                <div class="nurse">
                                                    <span class="label_t">記入看護師名</span>
                                                    <p class="n_search staff2_search">Search</p>
                                                    <input type="hidden" class="n_num tgt-stf2_id" name="upAry[report_staff]" value="<?= $dispData['report_staff'] ?>">
                                                    <input type="text" class="n_num tgt-stf2_cd" name="upDummy[staff2_cd]" value="<?= $dispData['staff2_cd'] ?>">
                                                    <input type="text" class="n_name tgt-stf2_name bg-gray2" name="upDummy[staff2_name]" value="<?= $dispData['staff2_name'] ?>" readonly="">
                                                    <!--<p class="img_search display_part1"><a hlef="/image/list/index.php">画像</a></p>-->
                                                    <p class="img_search display_part1 image_search" onclick="linkImage();">画像</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d_right">
                                            <p>
                                                <button type="submit" class="btn print" name="btnPrint" value="<?= $dispData['unique_id'] ?>">印刷</button>
                                                <?php $disabled = empty($keyId) ? 'disabled' : NULL; ?>
                                                <button type="submit" class="btn btn-edit" name="btnCopy" value="<?= $dispData['unique_id'] ?>" <?= $disabled ?>>複製</button>
                                                <button type="submit" class="btn btn-del" name="btnDel" value="<?= $dispData['unique_id'] ?>">削除</button>
                                            </p>
                                        </div>
                                    </div>
                                    <script>
                                        $(function () {

                                            /*---- ページ読込後の処理 --------*/
                                            $(document).ready(function () {
                                                initBedstoreDispCtl();
                                            });

                                            /*---- 褥瘡の有無(現在)を無に変更した場合は部位をクリアする --------*/
                                            $('input[name="upAry[bedsore_now]"]:radio').change(function () {
                                                var bedsore = $(this).val();
                                                var checkboxs = $('input[name="upDummy[bedsore_position_now][]"]:checkbox');
                                                var other = $('input[name="upAry[bedsore_other_now]"]');
                                                // 表示を切り替える
                                                bedstoreDispCtl(bedsore, checkboxs, other);
                                                checkBedSore(bedsore);
                                            });

                                            /*---- 褥瘡の有無(過去)を無に変更した場合は部位をクリアする --------*/
                                            $('input[name="upAry[bedsore_past]"]:radio').change(function () {
                                                var bedsore = $(this).val();
                                                var checkboxs = $('input[name="upDummy[bedsore_position_past][]"]:checkbox');
                                                var other = $('input[name="upAry[bedsore_other_past]"]');
                                                // 表示を切り替える
                                                bedstoreDispCtl(bedsore, checkboxs, other);
                                            });
                                        });

                                        /*---- 褥瘡の有無初期表示用 --------*/
                                        function initBedstoreDispCtl() {
                                            // 現在の制御
                                            var bedsoreNow = $('input:radio[name="upAry[bedsore_now]"]:checked').val();
                                            var checkboxsNow = $('input[name="upDummy[bedsore_position_now][]"]:checkbox');
                                            var otherNow = $('input[name="upAry[bedsore_other_now]"]');
                                            // 表示を切り替える
                                            bedstoreDispCtl(bedsoreNow, checkboxsNow, otherNow);
                                            checkBedSore(bedsoreNow);

                                            // 現在の制御
                                            var bedsorePast = $('input:radio[name="upAry[bedsore_past]"]:checked').val();
                                            var checkboxsPast = $('input[name="upDummy[bedsore_position_past][]"]:checkbox');
                                            var otherPast = $('input[name="upAry[bedsore_other_past]"]');
                                            // 表示を切り替える
                                            bedstoreDispCtl(bedsorePast, checkboxsPast, otherPast);
                                        }

                                        /*---- 褥瘡の有無(現在)を無に変更した場合は部位をクリアする --------*/
                                        function bedstoreDispCtl(bedsore, checkboxs, other) {
                                            if (bedsore === '無') {
                                                // 部位チェックボックスをOFFにする
                                                $(checkboxs).each(function (index, element) {
                                                    $(element).removeAttr('checked');
                                                    $(element).prop('disabled', true);
                                                });
                                                // その他理由を空欄にする
                                                $(other).val("");
                                                $(other).prop('disabled', true);
                                            } else if (bedsore === '有') {
                                                // 部位チェックボックスをOFFにする
                                                $(checkboxs).each(function (index, element) {
                                                    $(element).prop('disabled', false);
                                                });
                                                // その他理由を活性にする
                                                $(other).prop('disabled', false);
                                            }
                                        }

                                        function open_image_modal() {
                                            // モーダルダイアログ呼び出し
                                            var userId = $(".tgt-unique_id").val();
                                            var dlgName = "dynamic_modal";
                                            var tgUrl = "/report/bedsore/dialog/image_list_dialog.php?user_id=" + userId;
                                            let modalNode = document.getElementsByClassName('modal_setting');
                                            let node = modalNode.lastElementChild;
                                            if (node !== undefined) {
                                                node.lastElementChild.remove();
                                            }
                                            let xhr = new XMLHttpRequest();
                                            xhr.open('GET', tgUrl, true);
                                            xhr.addEventListener('load', function () {
                                                $(".modal_setting").append(this.response);
                                                $("." + dlgName).css("display", "block");
                                            });
                                            xhr.send();
                                        }

                                        function checkBedSore(bedsoreNow) {
                                            let independenceDegree = $('input[name="upAry[independence_degree]"]:radio');
                                            let physiqueConversion = $('input[name="upAry[physique_conversion]"]:radio');
                                            let seatrankPosture = $('input[name="upAry[seatrank_posture]"]:radio');
                                            let boneProminence = $('input[name="upAry[bone_prominence]"]:radio');
                                            let arthrogryposis = $('input[name="upAry[arthrogryposis]"]:radio');
                                            let nourishmentDrop = $('input[name="upAry[nourishment_drop]"]:radio');
                                            let skinDampness = $('input[name="upAry[skin_dampness]"]:radio');
                                            let swelling = $('input[name="upAry[swelling]"]:radio');
                                            let skinFragility = $('input[name="upAry[skin_fragility]"]:radio');
                                            let depth = $('input[name="upAry[depth]"]:radio');
                                            let effusion = $('input[name="upAry[effusion]"]:radio');
                                            let size = $('input[name="upAry[size]"]:radio');
                                            let infection = $('input[name="upAry[infection]"]:radio');
                                            let granulation = $('input[name="upAry[granulation]"]:radio');
                                            let sphacelus = $('input[name="upAry[sphacelus]"]:radio');
                                            let pocket = $('input[name="upAry[pocket]"]:radio');
                                            var className = 'validate[required]';

                                            // 褥瘡の有無（現在）の値によってvalidateクラスの付け替えを行なう
                                            if (bedsoreNow === '有') {
                                                independenceDegree.addClass(className);
                                                physiqueConversion.addClass(className);
                                                seatrankPosture.addClass(className);
                                                boneProminence.addClass(className);
                                                arthrogryposis.addClass(className);
                                                nourishmentDrop.addClass(className);
                                                skinDampness.addClass(className);
                                                swelling.addClass(className);
                                                skinFragility.addClass(className);
                                                depth.addClass(className);
                                                effusion.addClass(className);
                                                size.addClass(className);
                                                infection.addClass(className);
                                                granulation.addClass(className);
                                                sphacelus.addClass(className);
                                                pocket.addClass(className);
                                            } else {
                                                independenceDegree.removeClass(className);
                                                physiqueConversion.removeClass(className);
                                                seatrankPosture.removeClass(className);
                                                boneProminence.removeClass(className);
                                                arthrogryposis.removeClass(className);
                                                nourishmentDrop.removeClass(className);
                                                skinDampness.removeClass(className);
                                                swelling.removeClass(className);
                                                skinFragility.removeClass(className);
                                                depth.removeClass(className);
                                                effusion.removeClass(className);
                                                size.removeClass(className);
                                                infection.removeClass(className);
                                                granulation.removeClass(className);
                                                sphacelus.removeClass(className);
                                                pocket.removeClass(className);
                                            }
                                        }

                                        function linkImage() {
                                            var param = "";
                                            var user = $(".tgt-unique_id").val();
                                            if (user) {
                                                param = "?user=" + user;
                                            } else {
                                                param = $(location).attr('search');
                                            }
                                            window.open('/image/list/index.php'+ param, '_blank', 'width=1500, height=800, top=200, left=200');
//                                            window.location.href = "/image/list/index.php" + param;
                                        }

                                    </script>
                                    <div class="u_existence">
                                        <div class="tit">褥瘡の有無</div>
                                        <div class="current">
                                            <div class="ans">
                                                <span class="label_t">現在</span>
                                                <?php
                                                $i = 0;
                                                $grpNo = 1;
                                                ?>
                                                <?php foreach ($gnrList['褥瘡の有無_現在'] as $key => $val): ?>
                                                    <?php $check = $dispData['bedsore_now'] === $val ? ' checked' : NULL; ?>
                                                    <?php $i = $i + 1; ?>
                                                    <p><input type="radio" name="upAry[bedsore_now]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?>><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="e_type p_type">
                                                <?php
                                                $i = 0;
                                                $grpNo = 2;
                                                ?>
                                                <?php foreach ($gnrList['褥瘡の部位_現在'] as $key => $val): ?>
                                                    <?php $check = strpos($dispData['bedsore_position_now'], $val) !== false ? ' checked' : NULL; ?>
                                                    <?php $i = $i + 1; ?>
                                                    <p><input type="checkbox" name="upDummy[bedsore_position_now][]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?>><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                <?php endforeach; ?>
                                                <input type="text" name="upAry[bedsore_other_now]" value="<?= $dispData['bedsore_other_now'] ?>" class="type_hoka" placeholder="部位を入力">
                                            </div>
                                        </div>
                                        <div class="past">
                                            <div class="ans">
                                                <span class="label_t">過去</span>
                                                <?php
                                                $i = 0;
                                                $grpNo = 3;
                                                ?>
                                                <?php foreach ($gnrList['褥瘡の有無_過去'] as $key => $val): ?>
                                                    <?php if ($val == '無' || $val == '有'): ?>
                                                        <?php $check = $dispData['bedsore_past'] === $val ? ' checked' : NULL; ?>
                                                        <?php $i = $i + 1; ?>
                                                        <p><input type="radio" name="upAry[bedsore_past]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?>><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="e_type p_type">
                                                <?php
                                                $i = 0;
                                                $grpNo = 4;
                                                ?>
                                                <?php foreach ($gnrList['褥瘡の部位_過去'] as $key => $val): ?>
                                                    <?php $check = strpos($dispData['bedsore_position_past'], $val) !== false ? ' checked' : NULL; ?>
                                                    <?php $i = $i + 1; ?>
                                                    <p><input type="checkbox" name="upDummy[bedsore_position_past][]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?>><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                <?php endforeach; ?>
                                                <input type="text" name="upAry[bedsore_other_past]" value="<?= $dispData['bedsore_other_past'] ?>" class="type_hoka" placeholder="部位を入力">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="assess_risk">
                                        <div class="tit tit_toggle inactive">危険因子の評価</div>
                                        <div class="box_wrap child_toggle">
                                            <table class="row1">
                                                <tr>
                                                    <th>障害自立度</th>
                                                    <td>
                                                        <?php
                                                        $i = 0;
                                                        $grpNo = 5;
                                                        $offsetX = 240;
                                                        $offsetY = 25;
                                                        ?>
                                                        <?php foreach ($gnrList['障害自立度'] as $key => $val): ?>
                                                            <?php $check = $dispData['independence_degree'] === $val ? ' checked' : NULL; ?>
                                                            <?php $i = $i + 1; ?>
                                                            <p><input type="radio" name="upAry[independence_degree]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-prompt-position="topLeft:20,5 "><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                        <?php endforeach; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="mid">基本的<br class="pc">動作能力</div>
                                            <div class="row2">
                                                <table class="r2_left">
                                                    <tr>
                                                        <th><span class="pc">(ベッド上：自力体位変換)</span><span class="sm">[ベッド上：<br>自力体位変換]</span></th>
                                                        <td>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 6;
                                                            $offsetX = 240;
                                                            $offsetY = 75;
                                                            ?>
                                                            <?php foreach ($gnrList['ベッド上：自力体位変換'] as $key => $val): ?>
                                                                <?php $check = $dispData['physique_conversion'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[physique_conversion]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-prompt-position="topLeft:20,15 "><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><span class="pc">(イス上：座位姿勢の保持、除圧)</span><span class="sm">[イス上：<br>座位姿勢の保持、除圧]</span></th>
                                                        <td>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 7;
                                                            $offsetX = 240;
                                                            $offsetY = 125;
                                                            ?>
                                                            <?php foreach ($gnrList['イス上：座位姿勢の保持、除圧'] as $key => $val): ?>
                                                                <?php $check = $dispData['seatrank_posture'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[seatrank_posture]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?> "><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:left;">病的骨突出</th>
                                                        <td>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 8;
                                                            $offsetX = 240;
                                                            $offsetY = 175;
                                                            ?>
                                                            <?php foreach ($gnrList['病的骨突出'] as $key => $val): ?>
                                                                <?php $check = $dispData['bone_prominence'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[bone_prominence]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?> "><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:left;">関節拘縮</th>
                                                        <td>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 9;
                                                            $offsetX = 240;
                                                            $offsetY = 225;
                                                            ?>
                                                            <?php foreach ($gnrList['関節拘縮'] as $key => $val): ?>
                                                                <?php $check = $dispData['arthrogryposis'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[arthrogryposis]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?> "><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table class="r2_right">
                                                    <tr>
                                                        <th>栄養状態低下</th>
                                                        <td>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 10;
                                                            $offsetX = 790;
                                                            $offsetY = 75;
                                                            ?>
                                                            <?php foreach ($gnrList['栄養状態低下'] as $key => $val): ?>
                                                                <?php $check = $dispData['nourishment_drop'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[nourishment_drop]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?> "><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>皮膚湿潤<br class="sm">(多汗、尿失禁、便失禁)</th>
                                                        <td>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 11;
                                                            $offsetX = 790;
                                                            $offsetY = 125;
                                                            ?>
                                                            <?php foreach ($gnrList['皮膚湿潤(多汗・尿失禁・便失禁)'] as $key => $val): ?>
                                                                <?php $check = $dispData['skin_dampness'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[skin_dampness]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?> "><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>皮膚の脆弱性（浮腫）</th>
                                                        <td>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 12;
                                                            $offsetX = 790;
                                                            $offsetY = 175;
                                                            ?>
                                                            <?php foreach ($gnrList['皮膚の脆弱性(浮腫)'] as $key => $val): ?>
                                                                <?php $check = $dispData['swelling'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[swelling]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?> "><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>皮膚の脆弱性<br class="sm">(スキンーテアの保有、既往）</th>
                                                        <td>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 13;
                                                            $offsetX = 790;
                                                            $offsetY = 225;
                                                            ?>
                                                            <?php foreach ($gnrList['皮膚の脆弱性(スキンーテアの保有、既往)'] as $key => $val): ?>
                                                                <?php $check = $dispData['skin_fragility'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[skin_fragility]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?>"><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <table class="row3">
                                                <tr>
                                                    <th>対処</th>
                                                    <td>「有」もしくは「できない」が1つ以上の場合、看護計画を立案し実施する</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- 点数計算 -->
                                    <script>
                                        $(function () {
                                            pointCalc();

                                            $(".assess_risk").find("input[type='radio']").on("change", function () {
                                                pointCalc();
                                            });
                                        });
                                        function pointCalc() {
                                            /* 初期化 */
                                            var total = 0;
                                            var risk1 = 0;
                                            var risk2 = 0;
                                            var risk3 = 0;
                                            var risk4 = 0;
                                            var risk5 = 0;
                                            var risk6 = 0;
                                            var risk7 = 0;

                                            var target1 = $(document).find(".risk1 input[type='radio']:checked").data("num1");
                                            var target2 = $(document).find(".risk2 input[type='radio']:checked").data("num2");
                                            var target3 = $(document).find(".risk3 input[type='radio']:checked").data("num3");
                                            var target4 = $(document).find(".risk4 input[type='radio']:checked").data("num4");
                                            var target5 = $(document).find(".risk5 input[type='radio']:checked").data("num5");
                                            var target6 = $(document).find(".risk6 input[type='radio']:checked").data("num6");
                                            var target7 = $(document).find(".risk7 input[type='radio']:checked").data("num7");

                                            /* 点書き換え */
                                            if (target1) {
                                                risk1 = target1;
                                            }
                                            if (target2) {
                                                risk2 = target2;
                                            }
                                            if (target3) {
                                                risk3 = target3;
                                            }
                                            if (target4) {
                                                risk4 = target4;
                                            }
                                            if (target5) {
                                                risk5 = target5;
                                            }
                                            if (target6) {
                                                risk6 = target6;
                                            }
                                            if (target7) {
                                                risk7 = target7;
                                            }

                                            $(".risk1").find(".score").text(risk1);
                                            $(".risk2").find(".score").text(risk2);
                                            $(".risk3").find(".score").text(risk3);
                                            $(".risk4").find(".score").text(risk4);
                                            $(".risk5").find(".score").text(risk5);
                                            $(".risk6").find(".score").text(risk6);
                                            $(".risk7").find(".score").text(risk7);

                                            /* 合計点書き換え */
                                            total = risk2 + risk3 + risk4 + risk5 + risk6 + risk7;
                                            $(".assess_stat").find(".points b").text(total);
                                        }
                                    </script>
                                    <!-- 点数計算 -->

                                    <div class="assess_stat">
                                        <div class="tit_box">
                                            <div class="cont_tit">褥瘡に関する危険因子のある患者<br class="sm">及びすでに褥瘡を有する患者</div>
                                            <div class="points"><small>合計点</small><b>0</b></div>
                                        </div>
                                        <div class="assess_risk">
                                            <div class="tit tit_toggle inactive">褥瘡状態の評価<span>(DESIGN-R)</span></div>
                                            <div class="box_wrap child_toggle">
                                                <table>
                                                    <tr class="risk1">
                                                        <th>深さ</th>
                                                        <td>
                                                            <?php $score = 0; ?>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 14;
                                                            $offsetX = 45;
                                                            $offsetY = 45;
                                                            ?>
                                                            <?php foreach ($gnrList['深さ'] as $key => $val): ?>
                                                                <?php $check = $dispData['depth'] === $val ? ' checked' : NULL; ?>
                                                                <!-- data-num属性に各数値を格納 -->
                                                                <?php $ary = explode('_', $val); ?>
                                                                <?php $score = ($ary[0] != 'U') ? $ary[0] : 0; ?>
                                                                <!--<p><input type="radio" name="upAry[depth]" id="mode1" class="risk1" value="<?= $val ?>" <?= $check ?> data-num="<?= $score; ?>" data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?>"><label for="mode1"><?= $val ?></label></p>-->
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[depth]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-num1="<?= $score; ?>" data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?>"><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td class="score active">0</td>
                                                    </tr>
                                                    <tr class="risk2">
                                                        <th>滲出液</th>
                                                        <td>
                                                            <?php $score = 0; ?>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 15;
                                                            $offsetX = 45;
                                                            $offsetY = 25;
                                                            ?>
                                                            <?php foreach ($gnrList['滲出液'] as $key => $val): ?>
                                                                <?php $ary = explode('_', $val); ?>
                                                                <?php $score = $ary[0] != 'U' ? $ary[0] : 0; ?>
                                                                <?php $check = $dispData['effusion'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[effusion]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-num2="<?= $score; ?>" data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?>"><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td class="score">0</td>
                                                    </tr>
                                                    <tr class="risk3">
                                                        <th>大きさ(㎠)<small>長径x長径に直行する最大径<br>(持続する発赤の範囲も含む)</small></th>
                                                        <td>
                                                            <?php $score = 0; ?>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 16;
                                                            $offsetX = 45;
                                                            $offsetY = 55;
                                                            ?>
                                                            <?php foreach ($gnrList['大きさ'] as $key => $val): ?>
                                                                <?php $ary = explode('_', $val); ?>
                                                                <?php $score = $ary[0] != 'U' ? $ary[0] : 0; ?>
                                                                <?php $check = $dispData['size'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[size]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-num3="<?= $score; ?>" data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?>"><label for="<?= "mode" . $grpNo . $i ?>"><span><?= $val ?></span></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td class="score">0</td>
                                                    </tr>
                                                    <tr class="risk4">
                                                        <th>炎症・感染</th>
                                                        <td>
                                                            <?php $score = 0; ?>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 17;
                                                            $offsetX = 45;
                                                            $offsetY = 55;
                                                            ?>
                                                            <?php foreach ($gnrList['炎症・感染'] as $key => $val): ?>
                                                                <?php $check = $dispData['infection'] === $val ? ' checked' : NULL; ?>
                                                                <?php $ary = explode('_', $val); ?>
                                                                <?php $score = $ary[0] != 'U' ? $ary[0] : 0; ?>
                                                                <?php $score = $ary[0] == '3C' ? 3 : $ary[0]; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[infection]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-num4="<?= $score; ?>" data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?>"><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td class="score">0</td>
                                                    </tr>
                                                    <tr class="risk5">
                                                        <th>肉芽形成<br>良性肉芽が占める割合</th>
                                                        <td>
                                                            <?php $score = 0; ?>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 18;
                                                            $offsetX = 45;
                                                            $offsetY = 45;
                                                            ?>
                                                            <?php foreach ($gnrList['肉芽形成'] as $key => $val): ?>
                                                                <?php $ary = explode('_', $val); ?>
                                                                <?php $score = $ary[0] != 'U' ? $ary[0] : 0; ?>
                                                                <?php $check = $dispData['granulation'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[granulation]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-num5="<?= $score; ?>" data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?>"><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td class="score">0</td>
                                                    </tr>
                                                    <tr class="risk6">
                                                        <th>壊死組織</th>
                                                        <td>
                                                            <?php $score = 0; ?>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 19;
                                                            $offsetX = 45;
                                                            $offsetY = 25;
                                                            ?>
                                                            <?php foreach ($gnrList['壊死組織'] as $key => $val): ?>
                                                                <?php $ary = explode('_', $val); ?>
                                                                <?php $score = $ary[0] != 'U' ? $ary[0] : 0; ?>
                                                                <?php $check = $dispData['sphacelus'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[sphacelus]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-num6="<?= $score; ?>" data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?>"><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td class="score">0</td>
                                                    </tr>
                                                    <tr class="risk7">
                                                        <th>ポケット(㎠)<small>潰瘍面も含めたポケット全周<br class="pc">(ポ<br class="sm">ケットの長径×長径に直<br class="pc">交する最大径<br class="sm">)－潰瘍面積</small></th>
                                                        <td>
                                                            <?php $score = 0; ?>
                                                            <?php
                                                            $i = 0;
                                                            $grpNo = 20;
                                                            $offsetX = 45;
                                                            $offsetY = 45;
                                                            ?>
                                                            <?php foreach ($gnrList['ポケット'] as $key => $val): ?>
                                                                <?php $ary = explode('_', $val); ?>
                                                                <?php $score = $ary[0] != 'U' ? $ary[0] : 0; ?>
                                                                <?php $check = $dispData['pocket'] === $val ? ' checked' : NULL; ?>
                                                                <?php $i = $i + 1; ?>
                                                                <p><input type="radio" name="upAry[pocket]" id="<?= "mode" . $grpNo . $i ?>" value="<?= $val ?>" <?= $check ?> data-num7="<?= $score; ?>" data-prompt-position="topLeft:<?= $offsetX ?>,<?= $offsetY ?>"><label for="<?= "mode" . $grpNo . $i ?>"><?= $val ?></label></p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td class="score">0</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nurse_record n_plan">
                                        <div class="tit no_bg tit_toggle">看護計画</div>
                                        <div class="plan_table child_toggle">
                                            <div class="mid"><b>圧迫、ズレカの排除</b><small>(体位変換、体圧分散寝具、頭部拳上方法、車椅子姿勢保持等)</small></div>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>留意する項目</th>
                                                        <th>計画の内容</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th>ベッド上</th>
                                                        <td>
                                                            <textarea name="upAry[pressure_bed]" value="<?= $dispData['pressure_bed'] ?>" class="" maxlength="80"><?= $dispData['pressure_bed'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>イス上</th>
                                                        <td>
                                                            <textarea name="upAry[pressure_chair]" value="<?= $dispData['pressure_chair'] ?>" class="" maxlength="80"><?= $dispData['pressure_chair'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>スキンケア</th>
                                                        <td>
                                                            <textarea name="upAry[skincare]" value="<?= $dispData['skincare'] ?>" class="" maxlength="80"><?= $dispData['skincare'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>栄養状態改善</th>
                                                        <td>
                                                            <textarea name="upAry[nourishment_improvement]" value="<?= $dispData['nourishment_improvement'] ?>" class="" maxlength="800"><?= $dispData['nourishment_improvement'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>リハビリテーション</th>
                                                        <td>
                                                            <textarea name="upAry[rehabilitation]" value="<?= $dispData['rehabilitation'] ?>" class="" maxlength="80"><?= $dispData['rehabilitation'] ?></textarea>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="note">
                                                [記載上の注意]<br>
                                                1　障害自立度(日常生活自立度)の判定に当たっては「「障害老人日常生活自立度(寝たきり度)判定基準」の活用について」(平成3年11月18日厚生省大臣官房老人保健福祉部長通知 老健第102-2号)を参照のこと。<br>
                                                2　障害自立度(日常生活自立度)がJ1～A2である患者については、当該評価票の作成を要しないものであること。<br>
                                                3　必要な内容を訪問看護記録に記載している場合、当該評価票の作成を要しないものであること。
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ダイアログ流し込みエリア -->
                                    <div class="modal_setting"></div>
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
                                            <span class="label_t"><?= $dispData['create_name'] ?></span>
                                        </div>
                                        <div class="l_update">
                                            <span class="label_t">最終更新:</span>
                                            <span class=""><?= $dispData['update_date'] ?></span>
                                            <span class="label_t"><?= $dispData['update_name'] ?></span>
                                        </div>
                                    </div>
                                    <!--ダイアログ呼出し-->
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff.php'); ?>
                                    <?php //require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff2.php'); ?>
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/report/bedsore/dialog/nurse_search.php'); ?>
                                </div>

                            </div></div>
                        <!--/// CONTENT_END ///-->
                        <div class="fixed_navi">
                            <div class="box">
                                <div class="btn back pc"><button type="submit" name="btnReturn" value="true">褥瘡計画一覧にもどる</button></div>
                                <div class="controls">
                                    <button type="submit" class="btn save" name="btnEntry" value="保存">保存</button>
                                </div>
                            </div>
                        </div>
                    </form>>
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>