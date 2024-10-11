<?php require_once(dirname(__FILE__) . "/php/all_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>帳票一括確認</title>
        <script>
            $(function () {
                $(".btn.summary").click(function () {
                    var csvFilePath = $(this).data('csv_filpath');

                    if (csvFilePath !== "") {
                        const downloadLink = $('<a></a>');
                        downloadLink.attr('href', csvFilePath);
                        downloadLink.attr('download', 'report.csv');
                        downloadLink.css('display', 'none');
                        $('body').append(downloadLink);
                        downloadLink[0].click();
                        downloadLink.remove();
                    } else {
                        alert("csv出力できるデータがありません。")
                    }
                });
            });
        </script>
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
                <article id="content">][
                    <form action="" method="post" class="p-form-validate report_print_submit" enctype="multipart/form-data" accept-charset="UTF-8">
                    <!--/// CONTENT_START ///-->
                    <h2>帳票一括確認</h2>
                    <div id="subpage"><div id="register-list" class="nursing">


                            <div class="user-details nurse_record">
                                <div class="box1 profile">
                                    <input type="text" name="search[kana]" placeholder="利用者氏名(カナ)" class="user_name" value="<?= $search['kana'] ?>">
                                    <input type="date" name="search[start_day]" class="" value="<?= $search['start_day'] ?>" style="width:130px">
                                    <small>～</small>
                                    <input type="date" name="search[end_day]" class="" value="<?= $search['end_day'] ?>" style="width:130px">
                                    <!--<span><input type="text" name="前月" placeholder="前月" class="month"><input type="text" name="当月" placeholder="当月" class="month"></span>-->
                                </div>
                                <div class="box1 profile">
                        <!--		<span class="label_t text_blue">担当者</span>
                                        <input type="text" name="n_num" class="n_num" value="0000079">
                                        <input type="text" name="n_name" class="n_name" value="佐藤 清人">-->
                                        <!--<p class="n_search user_search">Search</p>-->
                                    <span class="label_t">担当者</span>
                                    <p class="n_search staff_search">Search</p>
                                    <input type="hidden" class="n_num tgt-stf_id" name="upAry[staff_id]" value="<?= $search['staff_id'] ?>">
                                    <input type="text" class="n_num tgt-stf_cd" name="upDummy[staff_cd]" value="<?= $search['staff_cd'] ?>">
                                    <input type="text" class="n_name tgt-stf_name bg-gray2" name="upDummy[staff_name]" value="" readonly="">
                                    <input type="text" name="居宅支援事業所" placeholder="居宅支援事業所/医療機関" class="support">
                                    <select>
                                        <option>居宅支援事業所空欄を除外</option>
                                        <option>居宅支援事業所空欄のみを表示</option>
                                        <option selected>居宅支援事業所空欄を含む</option>
                                    </select>
                                </div>
                                <div class="box1 rules">
                                    <div class="form">
                                        <span class="label_t text_blue">帳票種類</span>
                                        <ul>
                                                <li><input type="checkbox" name="search[doc_type][計画書]" value="計画書" id="f1" <?= !empty($search['doc_type']['計画書']) ? ' checked' : NULL ?>><label for="f1">計画書</label></li>
                                                <li><input type="checkbox" name="search[doc_type][報告書]" value="報告書" id="f2" <?= !empty($search['doc_type']['報告書']) ? ' checked' : NULL ?>><label for="f2">報告書</label></li>
                                                <li><input type="checkbox" name="search[doc_type][褥瘡計画書]" value="褥瘡計画書" id="f3" <?= !empty($search['doc_type']['褥瘡計画書']) ? ' checked' : NULL ?>><label for="f3">褥瘡計画書</label></li>
                                                <li><input type="checkbox" name="search[doc_type][指示書]" value="指示書" id="f4" <?= !empty($search['doc_type']['指示書']) ? ' checked' : NULL ?>><label for="f4">指示書</label></li>
                                        </ul>
                                    </div>
                                    <div class="kubun">
                                        <span class="label_t text_blue">指示書<br class="sm">区分</span>
                                        <select>
                                            <option selected>全て</option>
                                            <option>通常</option>
                                            <option>特指示</option>
                                            <option>点滴</option>
                                        </select>
                                    </div>
                                    <div class="state">
                                        <span class="label_t text_blue">作成状態</span>
                                        <ul>
                                            <li><input type="checkbox" name="search[status][]" value="完成" id="s1" checked><label for="s1">完成</label></li>
                                                <li><input type="checkbox" name="search[status][]" value="作成中" id="s2" checked><label for="s2">作成中</label></li>
                                        </ul>
                                        <button type="submit" name="btnSearch" value="true" class="btn search">絞り込み</button>
                                    </div>
                                </div>
                                <div class="d_right">
                                    <div class="shitei">
                                        <span class="label_t text_blue">宛先指定</span>
                                        <ul>
                                                <li><input type="checkbox" name="search[person][]" value="主治医" id="d1"><label for="d1" >主治医</label></li>
                                                <li><input type="checkbox" name="search[person][]" value="利用者" id="d2"><label for="d2">利用者</label></li>
                                                <li><input type="checkbox" name="search[person][]" value="ケアマネ" id="d3" ><label for="d3">ケアマネ</label></li>
                                                <li><input type="checkbox" name="search[person][]" value="その他" id="d4"><label for="d4">その他</label></li>
                                        </ul>
                                    </div>
                                        <div class="btn_box">
                                            <div><button type="submit" name="btnPrt" value="true" class="btn print">一括印刷</button></div>
                                            <div><button type="button" data-csv_filpath="<?= $csv_file_path ?? "" ?>" class="btn summary">CSV出力</button></div>
                                </div>
                                </div>
                            </div>

                            <div class="wrap">
                                <div class="dis_num">該当件数<b><?= count($dispData) ?></b></div>
                                <div class="registered_list">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>印刷<br>除外</th>
                                                <th>帳票種類</th>
                                                <th>作成状態</th>
                                                <th>該当月</th>
                                                <th>利用者名</th>
                                                <th>担当者</th>
                                                <th>契約事業所</th>
                                                <th>居宅支援事業所</th>
                                                <th>医療機関</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dispData as $val): ?>
                                                <?php $tgtId = $val['unique_id']; ?>
                                                <input type="hidden" name="allPrt[<?= $val['type'] ?>][]" value="<?= $tgtId ?>">
                                                <tr class="report_data" id="<?= $tgtId ?>">
                                                    <td><input type="checkbox" class="chekedPrt" name="ngPrt[<?= $val['type'] ?>][]" value="<?= $tgtId ?>" checked></td>
                                                    <td><a href="/report/plan/index.php?id=<?= $tgtId ?>" target="_blank"><?= $val['type'] ?></a></td>
                                                    <td><?= $val['status'] ?></td>
                                                    <td><?= $val['date'] ?></td>
                                                    <td><?= $val['user_name'] ?></td>
                                                    <td><?= $val['staff_name'] ?></td>
                                                    <td><?= $val['office1'] ?></td>
                                                    <td><?= $val['office2'] ?></td>
                                                    <td><?= $val['hospital'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <?= dispPager($tgtData, $page, $line, $server['requestUri']) ?>
                            <!--ダイアログ呼出し-->

                            <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/staff.php'); ?>
                        </div></div>
                    <!--/// CONTENT_END ///-->
                    </form>
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
    </body>
</html>