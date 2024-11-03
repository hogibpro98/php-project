<?php require_once(dirname(__FILE__) . "/php/cooperate.php"); ?>
<!DOCTYPE html>
<html lang="ja">

    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <title>連携データ作成</title>
    </head>

    <body>
        <div id="wrapper">
            <div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <form action="" class="p-form-validate" method="POST">
                        <!--/// CONTENT_START ///-->
                        <h2>連携データ作成</h2>
                        <div id="subpage">
                            <div id="data" class="nursing">
                                <form method="POST" class="p-form-validate" action="">
                                    <div class="cont_head nurse_record">
                                        <div class="box1 profile">
                                            <div class="user">
                                                <span class="label_t text_blue">利用者</span>
                                                <p class="n_search user_search">Search</p>
                                                <input type="text" name="search[other_id]" class="n_num tgt-usr_id" value="<?= $search['other_id'] ?>" placeholder="利用者を入力してください">
                                                <input type="hidden" name="search[user_id]" class="n_num tgt-unique_id" value="<?= $search['user_id'] ?>">
                                                <input type="text" name="search[user_name]" value="<?= $search['user_name'] ?>" class="n_name tgt-usr_name bg-gray2" readonly>
                                            </div>
                                            <div class="service">
                                                <span class="label_t text_blue">サービス利用区分</span>
                                                <?php $check = $search['type'] != '訪問看護' ? ' checked' : null; ?>
                                                <p><input type="radio" name="search[type]" value="看多機" id="kubun1" <?= $check ?>><label for="kubun1">看多機</label></p>
                                                <?php $check = $search['type'] == '訪問看護' ? ' checked' : null; ?>
                                                <p><input type="radio" name="search[type]" value="訪問看護" id="kubun2" <?= $check ?>><label for="kubun2">訪問看護</label></p>
                                            </div>
                                        </div>
                                        <div class="box1 rules">
                                            <ul>
                                                <li>
                                                    <p class="num"><span>1</span></p>
                                                    <p>対象年月を選択し「連携データ作成」<br>ボタンを押下する</p>
                                                </li>
                                                <li>
                                                    <span class="label_t text_blue">対象年月</span>
                                                    <div>
                                                        <select class="year" name="search[year]">
                                                            <?php foreach ($slctYear as $val) : ?>
                                                                <?php $select = $search['year'] == $val ? ' selected' : null; ?>
                                                                <option value="<?= $val ?>" <?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <label>年</label>
                                                        <select class="month" name="search[month]">
                                                            <?php foreach ($slctMonth as $val) : ?>
                                                                <?php $select = $search['month'] == $val ? ' selected' : null; ?>
                                                                <option value="<?= $val ?>" <?= $select ?>><?= $val ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <label>月</label>
                                                        <span class="btn display  display_data">
                                                            <button type="submit" class="btn display btnSearch" name="btnSearch" value="true">連携データ作成</button>
                                                        </span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <p class="num"><span>2</span></p>
                                                    <p>連携データの作成が終了した後、<br>「連携データ出力」ボタンを押下する。</p>
    <!--                                                <span class="btn display ">
                                                        <button type="submit" class="btn display btnError" name="btnError" value="true">エラーデータ出力</button>
                                                    </span>-->
                                                    <span class="btn display ">
                                                        <button type="submit" class="btn display btnMake" name="btnMake" value="true">連携データ出力</button>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="wrap">
                                        <div class="data_wrap">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>出力対象<input type="checkbox" name="" checked id="select-all1"></th>
                                                        <th>自己負担<input type="checkbox" name="" id="select-all2"></th>
                                                        <th>利用者</th>
                                                        <th>コード</th>
                                                        <th>f1</th>
                                                        <th>f2</th>
                                                        <th>f3</th>
                                                        <th>f4</th>
                                                        <th>f5</th>
                                                        <th>f6</th>
                                                        <th>f7</th>
                                                        <th>f8</th>
                                                        <th>f9</th>
                                                        <th>f10</th>
                                                        <th>f11</th>
                                                        <th>f12</th>
                                                        <th>f13</th>
                                                        <th>f14</th>
                                                        <th>f15</th>
                                                        <th>f16</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($dispData as $idx => $val) : ?>
                                                        <tr>
                                                            <td><input type="checkbox" name="upRowChk[<?= $idx?>]" class="row_check" value="true"  <?= !empty($val['output']) ? ' checked' : '' ?>></td>
                                                            <td>
                                                                <?php if($val['code'] === '25') : ?>
                                                                    <input type="checkbox" name="upCharge [<?= $idx?>]"  class="charge" value="<?= $val['user_id'] ?>" <?= !empty($val['charge']) ? ' checked' : '' ?> >
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?= $val['user_name'] ?></td>
                                                            <td><?= $val['code'] ?></td>
                                                            <td><?= isset($val['f1']) ? $val['f1'] : null ?></td>
                                                            <td><?= isset($val['f2']) ? $val['f2'] : null ?></td>
                                                            <td><?= isset($val['f3']) ? $val['f3'] : null ?></td>
                                                            <td><?= isset($val['f4']) ? $val['f4'] : null ?></td>
                                                            <td><?= isset($val['f5']) ? $val['f5'] : null ?></td>
                                                            <td><?= isset($val['f6']) ? $val['f6'] : null ?></td>
                                                            <td><?= isset($val['f7']) ? $val['f7'] : null ?></td>
                                                            <td><?= isset($val['f8']) ? $val['f8'] : null ?></td>
                                                            <td><?= isset($val['f9']) ? $val['f9'] : null ?></td>
                                                            <td><?= isset($val['f10']) ? $val['f10'] : null ?></td>
                                                            <td><?= isset($val['f11']) ? $val['f11'] : null ?></td>
                                                            <td><?= isset($val['f12']) ? $val['f12'] : null ?></td>
                                                            <td><?= isset($val['f13']) ? $val['f13'] : null ?></td>
                                                            <td><?= isset($val['f14']) ? $val['f14'] : null ?></td>
                                                            <td><?= isset($val['f15']) ? $val['f15'] : null ?></td>
                                                            <td><?= isset($val['f16']) ? $val['f16'] : null ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!--                                <div class="msg_box data_updated cancel_act">
                                                                        <div class="msg_box-tit">連携データ更新完了</div>
                                                                        <div class="msg_box-cont">連携データの更新が完了しました。</div>
                                                                    </div>-->

                                    <!--ダイアログ呼出し-->
                                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/dialog/user.php'); ?>

                                    <script>
                                        function fileDownLoad(filename, pathname) {
                                            console.log("call filedownload");
                                            console.log("filename:" + filename);
                                            console.log("pathname:" + pathname);

                                            var anchor = document.createElement('a');
                                            anchor.create
                                            anchor.download = filename;
                                            var textnode = document.createTextNode("");
                                            document.body.appendChild(anchor);
                                            anchor.appendChild(textnode);
                                            anchor.href = pathname;
                                        }

                                        function dlFile(url) {
                                            // aタグを生成し、変数createElementに格納
                                            const createElement = $('<a href="" class="createElement" download></a>');

                                            // 生成したaタグのhref属性に引数のurlの値を代入
                                            createElement.attr('href', url);
                                            // body内に生成したaタグを追加
                                            $('body').append(createElement);
                                            // aタグにクリックイベントのイベントハンドラを登録し、関数triggerClickを実行
                                            $('.createElement').on('click', triggerClick());
                                        }
                                        function triggerClick() {
                                            //関数fileDLを実行
                                            dlAll();
                                            // 生成したaタグをbody内から削除
                                            $('.createElement').remove();
                                        }
                                        function dlAll() {
                                            let a = document.querySelectorAll('.createElement'); //生成したaタグを全て変数aに格納
                                            a.forEach(function (e) {
                                                e.click();  //生成したaタグを全てクリック
                                            });
                                        }
                                        
                                        $(function () {
                                            
                                            $(".btnSearch").on("click", function () {
                                                var result = window.confirm('自己負担がチェックされている利用者の該当月分の実績データを全て自己負担に更新後に連携データの作成してもよろしいですか？');
                                                if(!result){
                                                    // いいえ押下時、Submit阻止
                                                    return false;
                                                }
                                            });
                                                                                        
                                            // 「全選択」する
                                            $('#select-all1').on('click', function() {
                                              $(".row_check").prop('checked', this.checked);
                                            });
                                            // 「全選択」する
                                            $('#select-all2').on('click', function() {
                                              $(".charge").prop('checked', this.checked);
                                            });
                                            
                                        });
                                    </script>
                                    <?php
                                        if ($btnMake && $csvFilePath) {
                                            $data = readCsv($csvFilePath, true);
                                            foreach ($data as $chkRow => $val) {
                                                foreach ($upRow as $idx => $dummy) {
                                                    if ($idx != $chkRow) {
                                                        continue;
                                                    }
                                                    $newData[$idx] = $val;
                                                }
                                            }
                                            if ($newData) {
                                                writeCsvLock($csvFilePath, $newData);
                                            }
                                        }
?>
                                    <?php if ($btnMake && $csvFilePath): ?>
                                        <script>
                                            var csvFileName = "<?= $csvFileName ?>";
                                            var csvFilePath = "<?= $csvFilePath ?>";
                                            var csvFileUrl = "<?= $csvFileUrl ?>";
                                            var errFileName = "<?= $errFileName ?>";
                                            var errFilePath = "<?= $errFilePath ?>";
                                            var errFileUrl = "<?= $errFileUrl ?>";
                                            
                                            // 実行CSVファイル
                                            if (csvFileName && csvFilePath) {
                                                dlFile(csvFileUrl);
                                            }
                                            
                                            // エラーファイル
                                            if (errFileName && errFileUrl) {
                                                dlFile(errFileUrl);
                                            }
                                        </script>
                                    <?php endif; ?>
                                </form>
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