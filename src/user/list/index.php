<?php require_once(dirname(__FILE__) . "/php/user_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!--COMMON-->
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
        <!--CONTENT-->
        <script src="/user/list/js/user.js"></script>
        <title>利用者一覧 - やさしい手</title>
        <style>
        /* Default button styles */
        .btn.save {
            background: #188F9D;
            color: #fff;
            border-radius: 4px;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        /* Disabled button styles */
        .btn.save.disabled {
            background-color: grey;
            cursor: not-allowed;
        }

        /* Message styles */
        #message {
            display: none; /* Hidden by default */
            color: red;
            margin-top: 5px;
        }
    </style>
    </head>

    <body>
        <div id="wrapper"><div id="base">
                <!--HEADER-->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
                <!--CONTENT-->
                <article id="content">
                    <!--/// CONTENT_START ///-->
                    <h2 class="tit_sm">利用者基本情報</h2>
                    <div id="subpage"><div id="user-list" class="nursing">
                            <form action="" class="p-form-validate" method="get">

                                <div class="search_box">
                                    <div class="box">
                                        <div class="name_box pc">
                                            <div>
                                                <input type="text" name="search[kana]" value="<?= $search['kana'] ?>" id="user_name-k" placeholder="全角カナを入力してください。">
                                                <select name="search[sort]" class="fil_all">
                                                    <?php $select = $search['sort'] == null || $search['sort'] === "other_id ASC" ? ' selected' : null; ?>
                                                    <option value="other_id ASC" <?= $select ?>>利用者IDでソート</option>
                                                    <?php $select = $search['sort'] === "last_kana ASC, first_kana ASC" ? ' selected' : null; ?>
                                                    <option value="last_kana ASC, first_kana ASC" <?= $select ?>>カナでソート</option>
                                                </select>
                                                <button type="submit" name="btnSearch" value="true" class="btn search">絞り込み</button>
                                            </div>
                                        </div>
                                        <div class="filter_box">
                                            <div class="fil_con">
                                                <select id="fil_con" name="search[status]">
                                                    <?php $select = !$search['status'] ? ' selected' : null; ?>
                                                    <option value=""<?= $select ?>>契約状態全て</option>
                                                    <?php $select = $search['status'] === '契約中' ? ' selected' : null; ?>
                                                    <option value="契約中"<?= $select ?>>契約中</option>
                                                    <?php $select = $search['status'] === '停止中' ? ' selected' : null; ?>
                                                    <option value="停止中"<?= $select ?>>契約中以外</option>
                                                </select>
                                            </div>
                                            <div class="fil_cat pc">
                                                <select id="fil_cat" name="search[service]">
                                                    <option value="">サービス利用区分全て</option>
                                                    <?php foreach ($codeList['利用者基本情報_基本情報']['サービス利用区分'] as $val): ?>
                                                        <?php $select = $search['service'] === $val ? ' selected' : null; ?>
                                                        <option value="<?= $val ?>"<?= $select ?>><?= $val ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="fil_all">
                                                <select id="fil_all" name="search[ng]">
                                                    <?php $select = !$search['ng'] ? ' selected' : null; ?>
                                                    <option value="" <?= $select ?>>NG状態全て</option>
                                                    <?php $select = $search['ng'] ? ' selected' : null; ?>
                                                    <option value="ng" <?= $select ?>>NGのみ</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn add add3"><a href="/user/edit/">新規作成</a></div>
                                </div>
                            </form>

                            <form  action="" class="p-form-validate" method="post">
                                    <div class="btn deploy display_dets  disabled"  style="top:200px;left:1020px;z-index:1;">展開</div>
                                    <div class="sched_details dep_jouken cancel_act" style="top:260px;left:1000px;">
                                        <div class="close close_part">✕<span>閉じる</span></div>
                                        <div class="sched_tit">展開条件</div>
                                        <div class="s_detail">
                                            <div class="box1">
                                                <p class="mid"><span class="label_t">展開方法</span></p>
                                                <p>
                                                    <?php $check = $search['type'] != 2 ? ' checked' : null; ?>
                                                    <span><input type="radio" name="search[type]" value="1" id="method1"<?= $check ?>><label for="method1">差分のみ展開</label></span>
                                                    <?php $check = $search['type'] == 2 ? ' checked' : null; ?>
                                                    <span><input type="radio" name="search[type]" value="2" id="method2"<?= $check ?>><label for="method2">既存削除後に上書き</label></span>
                                                </p>
                                            </div>
                                            <div class="box1">
                                                <p class="mid"><span class="label_t">展開範囲</span></p>
                                                <p>
                                                    <span>
                                                        <input type="date" name="search[start_day]" class="month_from" value="<?= $search['start_day'] ?>">
                                                        <small>～</small>
                                                        <input type="date" name="search[end_day]" class="month_to" value="<?= $search['end_day'] ?>">
                                                    </span>
                                                    <span>
                                                        <input type="button" name="展開範囲" value="当月" class="btn_prev_mon">
                                                        <input type="button" name="展開範囲" value="翌月" class="btn_next_mon">
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="s_constrols">
                                            <p>
                                                <span class="btn cancel">キャンセル</span>
                                                <!--<span class="btn save">上記の条件で一括展開する</span>-->
                                                <button type="submit" class="btn save" name="btnMakePlanAll" value="true">展開</button>
                                                <span id="message">利用者を選択してください</span>
                                            </p>
                                        </div>
                                        <script>
                                       $(function(){
                                           
                                           
                                       });     
                                        </script>
                                    </div>
                                <div class="wrap">
                                    <div class="dis_num">表示件数<b><?= count($dispData) ?></b></div>
                                    <div class="dis_box">
                                        <table class="dis_result">
                                            <thead>
                                                <tr>
                                                    <th class="th_check"><span>展開</span><input type="checkbox" id="select-all"></th>
                                                    <th class="th_ng"></th>
                                                    <th class="th_date">前回展開日</th>
                                                    <!--<th class="th_id th_sort">利用者ID</th>-->
                                                    <th class="th_id">利用者ID</th>
                                                    <th class="th_kananame">氏名(カナ)</th>
                                                    <th class="th_fullname">氏名</th>
                                                    <th class="th_age">年齢</th>
                                                    <th class="th_gender">性別</th>
                                                    <th class="th_degree">要介護度</th>
                                                    <th class="th_add">住所</th>
                                                    <th class="th_num">電話番号</th>
                                                    <th class="th_cat">サービス利用区分</th>
                                                    <th class="th_status"><span>契約</span><span>状態</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($dispData as $key => $val): ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="check_user" name="userAry[]" value="<?= $val['unique_id'] ?>">
                                                        </td>
                                                        <?php if ($val['ng']): ?>
                                                            <td><div><span class="ng no" onclick="showNgPop('<?= $val['ng'] ?>')">NG</span></div></td>
                                                        <?php else: ?>
                                                            <td><div></div></td>
                                                        <?php endif; ?>
                                                        <td>
                                                            <div>
                                                                <?= $val['last_day'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <?= $val['other_id'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <?= $val['last_kana'] . $dispData[$key]['first_kana'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <?= $val['last_name'] . $dispData[$key]['first_name'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <?= $val['age'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <?= $val['sex'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <?= $val['care_rank'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <?= $val['address'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <?= $val['tel'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <span class="<?= $val['sv_cls'] ?>"><?= $val['service_type'] ?></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <span class="<?= $val['st_cls'] ?>"><?= $val['status'] ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="disabled_list">
                                                            <span><a href="/user/edit/?user=<?= $val['unique_id'] ?>">基本<br/>情報</a></span>
                                                            <span><a href="/report/list/?user=<?= $val['unique_id'] ?>">各種<br/>帳票</a></span>
                                                            <span><a href="/image/list/?user=<?= $val['unique_id'] ?>">画像<br/>関連</a></span>
                                                            <span><a href="/schedule/week/?user=<?= $val['unique_id'] ?>">週間<br/>ｽｹｼﾞｭｰﾙ</a></span>
                                                            <span><a href="/record/user/?user=<?= $val['unique_id'] ?>">予定<br/>実績</a></span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                        <div class="no_data-in">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?= dispPager($tgtData, $page, $line, $server['requestUri']) ?>
                        </div>
                    </div>
                    <!--/// CONTENT_END ///-->
                </article>
                <!--CONTENT-->
            </div></div>
        <p id="page"><a href="#wrapper">PAGE TOP</a></p>
        <script src="https://unpkg.com/wanakana"></script>
        <script>
            $(document).ready(function () {
                const $selectAll = $('#select-all');
                const $checkUsers = $('.check_user');
                const $submitButton = $('.btn.save');
                const $message = $('#message');

                // Function to update submit button state and message
                function updateButtonState() {
                    const anyChecked = $checkUsers.is(':checked');
                    if (anyChecked) {
                        $submitButton.removeClass('disabled').prop('disabled', false);
                        $message.hide();
                    } else {
                        $submitButton.addClass('disabled').prop('disabled', true);
                        $message.show();
                    }
                }

                // Select or deselect all checkboxes based on "select all" checkbox
                $selectAll.on('click', function () {
                    $checkUsers.prop('checked', this.checked);
                    updateButtonState();
                });

                // Update submit button state when individual checkboxes are clicked
                $checkUsers.on('click', function () {
                    if (!$checkUsers.is(':checked')) {
                        $selectAll.prop('checked', false);
                    } else if ($checkUsers.filter(':checked').length === $checkUsers.length) {
                        $selectAll.prop('checked', true);
                    }
                    updateButtonState();
                });

                // Initial button state check
                updateButtonState();

                // save position scroll into localStorage when click
                $('#user-list table.dis_result td.disabled_list span a').on('click', function () {
                    localStorage.setItem('scrollPosition', window.scrollY);
                });

                // set scroll position when reload
                const scrollPosition = localStorage.getItem('scrollPosition');
                if (scrollPosition) {
                    window.scrollTo(0, parseInt(scrollPosition, 10));
                    localStorage.removeItem('scrollPosition');
                }

                // No98
                function replaceStringByArrayOfIndex(str, src, dest) {
                    let len = src.length;
                    for (let i = 0; i < len; i++) {
                        str = replaceAll(str, src[i], dest[i])
                    }
                    return str;
                }

                function replaceAll(target, from, to) {
                    console.log(target+" "+from+" "+to);
                    if (target.indexOf(from) < 0) {
                        return target;
                    } else {
                        return target.split(from).join(to)
                    }
                }

                const HKANA = ["ｶﾞ", "ｷﾞ", "ｸﾞ", "ｹﾞ", "ｺﾞ", "ｻﾞ", "ｼﾞ", "ｽﾞ", "ｾﾞ", "ｿﾞ",
                    "ﾀﾞ", "ﾁﾞ", "ﾂﾞ", "ﾃﾞ", "ﾄﾞ", "ﾊﾞ", "ﾋﾞ", "ﾌﾞ", "ﾍﾞ", "ﾎﾞ", "ｳﾞ", //濁音
                    "ﾊﾟ", "ﾋﾟ", "ﾌﾟ", "ﾍﾟ", "ﾎﾟ", //半濁音
                    "ｧ", "ｨ", "ｩ", "ｪ", "ｫ", "ｬ", "ｭ", "ｮ", "ｯ", "ｰ", // 小文字
                    "ｱ", "ｲ", "ｳ", "ｴ", "ｵ", "ｶ", "ｷ", "ｸ", "ｹ", "ｺ", // 50音
                    "ｻ", "ｼ", "ｽ", "ｾ", "ｿ", "ﾀ", "ﾁ", "ﾂ", "ﾃ", "ﾄ",
                    "ﾅ", "ﾆ", "ﾇ", "ﾈ", "ﾉ", "ﾊ", "ﾋ", "ﾌ", "ﾍ", "ﾎ",
                    "ﾏ", "ﾐ", "ﾑ", "ﾒ", "ﾓ", "ﾔ", "ﾕ", "ﾖ",
                    "ﾗ", "ﾘ", "ﾙ", "ﾚ", "ﾛ", "ﾜ", "ｦ", "ﾝ"];

                const WKANA = ["ガ", "ギ", "グ", "ゲ", "ゴ", "ザ", "ジ", "ズ", "ゼ", "ゾ",
                    "ダ", "ヂ", "ヅ", "デ", "ド", "バ", "ビ", "ブ", "ベ", "ボ", "ヴ", //濁音
                    "パ", "ピ", "プ", "ペ", "ポ", //半濁音
                    "ァ", "ィ", "ゥ", "ェ", "ォ", "ャ", "ュ", "ョ", "ッ", "ー", // 小文字
                    "ア", "イ", "ウ", "エ", "オ", "カ", "キ", "ク", "ケ", "コ", // 50音 start
                    "サ", "シ", "ス", "セ", "ソ", "タ", "チ", "ツ", "テ", "ト",
                    "ナ", "ニ", "ヌ", "ネ", "ノ", "ハ", "ヒ", "フ", "ヘ", "ホ",
                    "マ", "ミ", "ム", "メ", "モ", "ヤ", "ユ", "ヨ",
                    "ラ", "リ", "ル", "レ", "ロ", "ワ", "ヲ", "ン"];

                const halfWidthKatakanaRegex = /[\uFF66-\uFF9F]/;

                // dom input
                const $input = $('#user_name-k');

                // convert to full-width katakana
                function convertToFullWidthKatakana() {
                    const currentValue = $input.val();
                    if (halfWidthKatakanaRegex.test(currentValue)) {
                        const convertedValue = replaceStringByArrayOfIndex(currentValue, HKANA, WKANA);
                        $input.val(convertedValue);
                    } else {
                        $input.val(wanakana.toKatakana(currentValue, { useObsoleteKana: true, upcaseKatakana: true }));
                    }

                }

                // add event to input
                $input.on('input', convertToFullWidthKatakana);
            });
        </script>
    </body>
</html>