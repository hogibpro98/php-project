<?php require_once(dirname(__FILE__) . "/php/csv_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>CSVデータ出力</title>
    </head>
    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <form action="" class="p-form-validate" method="post">
                    <article id="content">
                        <!--/// CONTENT_START ///-->
                        <h2>CSVデータ出力</h2>
                        <div id="subpage">
                            <div id="csv" class="nursing">
                                <div class="wrap">
                                    <div class="target_data">
                                        <div class="tit">対象データ</div>
                                        <dl>
                                            <dt>
                                                <input type="radio" name="upSearch[type]" value="利用者情報" id="" class="select" <?= $dispSearch['type'] === "利用者情報" ? " checked" : "" ?>><label for="target1">利用者情報</label>
                                            </dt>
                                            <dd>
                                                <p><input type="checkbox" name="upSearch[target][standard]" value="基本情報" <?= mb_strpos($dispSearch['target']['standard'], "基本情報") !== false ? " checked" : '' ?> id="info1"><label for="info1">基本情報</label></p>
                                                <p><input type="checkbox" name="upSearch[target][insure1]" value="介護保険証情報" <?= mb_strpos($dispSearch['target']['insure1'], "介護保険証情報") !== false ? " checked" : '' ?> id="info2"><label for="info2">介護保険証情報</label></p>
                                                <p><input type="checkbox" name="upSearch[target][insure3]" value="医療保険証情報" <?= mb_strpos($dispSearch['target']['insure3'], "医療保険証情報") !== false ? " checked" : '' ?> id="info3"><label for="info3">医療保険証情報</label></p>
                                                <p><input type="checkbox" name="upSearch[target][introduct]" value="流入流出情報" <?= mb_strpos($dispSearch['target']['introduct'], "流入流出情報") !== false ? " checked" : '' ?> id="info4"><label for="info4">流入流出情報</label></p>
                                                <p><input type="checkbox" name="upSearch[target][family]" value="連絡先情報" <?= mb_strpos($dispSearch['target']['family'], "連絡先情報") !== false ? " checked" : '' ?> id="info5"><label for="info5">連絡先情報</label></p>
                                            </dd>
                                        </dl>
                                        <dl>
                                            <dt>
                                                <input type="radio" name="upSearch[type]" value="利用者スケジュール" id="" <?= $dispSearch['type'] === "利用者スケジュール" ? " checked" : "" ?>><label for="target2">利用者スケジュール</label>
                                            </dt>
                                            <dd>
                                                <p><input type="checkbox" name="upSearch[target][user_plan]" value="利用者スケジュール（本体のみ)" <?= mb_strpos($dispSearch['target']['user_plan'], "利用者スケジュール（本体のみ)") ? " checked" : '' ?> id="sched1"><label for="sched1">利用者スケジュール（本体のみ)</label></p>
                                                <p><input type="checkbox" name="upSearch[target][service]" value="利用者スケジュール（内訳のみ）" <?= mb_strpos($dispSearch['target']['service'], "利用者スケジュール（内訳のみ）") ? " checked" : '' ?> id="sched2"><label for="sched2">利用者スケジュール（内訳のみ）</label></p>
                                                <p><input type="checkbox" name="upSearch[target][add]" value="利用者スケジュール（加算減算）" <?= mb_strpos($dispSearch['target']['add'], "利用者スケジュール（加算減算）") ? " checked" : '' ?> id="sched3"><label for="sched3">利用者スケジュール（加算減算）</label></p>
                                                <p><input type="checkbox" name="upSearch[target][jippi]" value="実費" <?= mb_strpos($dispSearch['target']['jippi'], "実費") ? " checked" : '' ?> id="sched4"><label for="sched4">実費</label></p>
                                            </dd>
                                        </dl>
                                        <dl>
                                            <dt>
                                                <input type="radio" name="upSearch[type]" value="従業員スケジュール" id="target3" <?= $dispSearch['type'] === "従業員スケジュール" ? " checked" : "" ?>><label for="target3">従業員スケジュール</label>
                                            </dt>
                                            <dd>
                                                <p><input type="checkbox" name="upSearch[target][staff_plan]" value="従業員スケジュール" <?= mb_strpos($dispSearch['target']['staff_plan'], "従業員スケジュール") ? " checked" : '' ?> id="employee1"><label for="employee1">従業員スケジュール</label></p>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="sub_info2" style="display:flex;">
                                        <div class="d_left" style="width:50%">
                                            <div class="tit">サービス利用区分</div>
                                            <ul>
                                                <li><input type="checkbox" name="upSearch[service][]" value="医療保険訪問看護" id="cate1" <?= mb_strpos($dispSearch['service_type'], "医療保険訪問看護") !== false ? " checked" : "" ?>><label for="cate1">医療保険訪問看護</label></li>
                                                <li><input type="checkbox" name="upSearch[service][]" value="看護小規模多機能" id="cate2" <?= mb_strpos($dispSearch['service_type'], "看護小規模多機能") !== false ? " checked" : "" ?>><label for="cate2">看護小規模多機能</label></li>
                                                <li><input type="checkbox" name="upSearch[service][]" value="指定訪問看護" id="cate3" <?= mb_strpos($dispSearch['service_type'], "指定訪問看護") !== false ? " checked" : "" ?>><label for="cate3">指定訪問看護</label></li>
                                                <li><input type="checkbox" name="upSearch[service][]" value="定期巡回" id="cate4" <?= mb_strpos($dispSearch['service_type'], "定期巡回") !== false ? " checked" : "" ?>><label for="cate4">定期巡回</label></li>
                                            </ul>
                                            <ul>
                                                <li><input type="checkbox" name="upSearch[service][]" value="医療保険訪問看護+看護小規模多機能" id="cate5" <?= mb_strpos($dispSearch['service_type'], "医療保険訪問看護+看護小規模多機能") !== false ? " checked" : "" ?>><label for="cate5">医療保険訪問看護+看護小規模多機能</label></li>
                                                <li><input type="checkbox" name="upSearch[service][]" value="医療保険訪問看護+指定訪問看護" id="cate6" <?= mb_strpos($dispSearch['service_type'], "医療保険訪問看護+指定訪問看護") !== false ? " checked" : "" ?>><label for="cate6">医療保険訪問看護+指定訪問看護</label></li>
                                                <li><input type="checkbox" name="upSearch[service][]" value="医療保険訪問看護+定期巡回" id="cate7" <?= mb_strpos($dispSearch['service_type'], "医療保険訪問看護+定期巡回") !== false ? " checked" : "" ?>><label for="cate7">医療保険訪問看護+定期巡回</label></li>
                                                <li><input type="checkbox" name="upSearch[service][]" value="医療訪看・指定訪看・看多機" id="cate8" <?= mb_strpos($dispSearch['service_type'], "医療訪看・指定訪看・看多機") !== false ? " checked" : "" ?>><label for="cate8">医療訪看・指定訪看・看多機</label></li>
                                                <li><input type="checkbox" name="upSearch[service][]" value="指定訪看・看多機" id="cate9" <?= mb_strpos($dispSearch['service_type'], "指定訪看・看多機") !== false ? " checked" : "" ?>><label for="cate9">指定訪看・看多機</label></li>
                                            </ul>
                                        </div>
                                        <div class="d_right">
                                            <div class="extract_cond">
                                                <div class="tit">抽出条件</div>
                                                <div class="condition">
                                                    <select name="upSearch[place_id]" class="sel_place">
                                                        <option value='' ></option>
                                                        <?php foreach ($plcMst as $key => $val) : ?>
                                                            <option value='<?= $key ?>' data-place_id='<?= $key ?>'><?= $val ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <select name="upSearch[office_id]">
                                                        <option value='' ></option>
                                                        <?php foreach ($plcOfc as $key => $val) : ?>
                                                            <option class="selPlace" value='<?= $val['name'] ?>' data-place_id='<?= $val['place_id'] ?>'><?= $val['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <select name="upSearch[user_id]">
                                                        <option value='' ></option>
                                                        <?php foreach ($userList as $key => $val) : ?>
                                                            <option class="" value='<?= $val['unique_id'] ?>' data-place_id='<?= $val['place_id'] ?>'><?= $val['other_id'] ?> <?= $val['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="contract_stat">
                                                <div class="tit">現在契約状態</div>
                                                <p><input type="checkbox" name="upSearch[status][]" id="c_stat01" checked><label for="c_stat01">契約中</label></p>
                                                <p><input type="checkbox" name="upSearch[status][]" id="c_stat02"><label for="c_stat02">停止中</label></p>
                                            </div>
                                            <div class="contract_period">
                                                <div class="tit">契約対象期間</div>
                                                <p style="display:flex;">
                                                    <input type="date" name="upSearch[start_day]" class="validate[required]" value="<?= $dispSearch['start_day'] ?>" placeholder="契約対象日fromを入力してください">
                                                    <small>～</small>
                                                    <input type="date" name="upSearch[end_day]" class="validate[required]" value="<?= $dispSearch['end_day'] ?>" placeholder="契約対象日toを入力してください">
                                                </p>
                                                <p class="note">※対象期間を指定した場合は対象期間に契約中だったデータが出力されます</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn_box">
                                        <button type="button" class="btn clear btnClear">クリア</button>
                                        <button type="submit" name="btnCsvEntry" value="true" class="btn excel btnCsvEntry">CSV出力</button>
                                    </div>
                                </div>
                            </div></div>
                        <!--/// CONTENT_END ///-->
                    </article>
                </form>
                <script>
                    $(function () {
                        $(".btnClear").on("click", function () {
                            location.reload();
                        });

                        $(".sel_place").on("change", function () {

                            var plcId = $(this).val();
                            $(document).find(".selPlace").each(function () {
                                $(this).hide();
                            });

                            $(document).find(".selPlace").each(function () {

                                var pId = $(this).data("place_id");
                                if (!pId || pId === plcId) {
                                    $(this).show();
                                } else {
                                    $(this).removeAttr("selected");
                                }
                            });
                        });
                    });
                </script>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>