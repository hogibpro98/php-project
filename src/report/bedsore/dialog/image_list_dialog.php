<?php
/* =================================================== 
 * 画像一覧モーダル
 * ===================================================
 */

/* =================================================== 
 * 初期処理
 * ===================================================
 */

$dispData = array();
$tgtData = array();
$where = array();

/* --共通ファイル呼び出し------------------------------------- */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

/* --変数定義------------------------------------------------- */

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/* -- 検索用パラメータ --------------------------------------- */

// 利用者ID
$userId = filter_input(INPUT_GET, 'user_id');

/* ===================================================
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */

/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- 利用者情報取得 --------------------------------- */
$userInfo = getUserInfo($userId);

/* -- イメージ一覧取得 --------------------------------- */
$where['delete_flg'] = 0;
$where['user_id'] = $userId;
$temp = select('mst_user_image', '*', $where);
foreach ($temp as $val) {
    $keyId = $val['unique_id'];
    // 登録者名
    $val['name'] = getStaffName($val['create_user']);

    // 格納
    $tgtData[$keyId] = $val;
}

/* -- 画面表示データ格納 ---------------------------- */
$dispData = $tgtData;
?>
<div class="dynamic_modal msg_box" style="display:flex;" style="width:600px;background-color: #FFFFFF;">
    <style>
        .img_list{
            z-index: 2;
            background-color: #FFFFFF;
        }

        .dialog_style{
            /*            position: fixed;*/
            left: 0;
            right: 0;
            top: 50%;
            margin: auto;
            /*width: 487px;*/
            padding: 10px;
            background: #FFF;
            border-radius: 5px;
            border: 1px solid #CACED5;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            transform: translateY(-50%);
            z-index: 9;
        }

        .img_display .sched_tit {
            font-size: 100%;
            margin-bottom: 5px;
            z-index: 2;
            background-color: #FFFFFF;
            max-height: 500px;
        }

        .sched_tit {
            font-size: 93.8%;
            font-weight: 500;
            color: #174A84;
            margin-bottom: 20px;
        }
        p.mid {
            width:100px;
            font-size:87.5%;
            font-weight:500;
            color:#174A84;
            line-height:100%;
        }
        #modal-container {
            display: none;
            position: fixed;
            background: rgba(0, 0, 0, .6);
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 99;
            & > div {
                display: flex;
                height: 100vh;
                justify-content: center;
                align-items: center;
                & > img {
                    max-width: calc(100vw - 30px);
                    max-height: calc(100vh - 30px);
                }
            }
        }
        img.popup {
            cursor: pointer;
        }
    </style>
    <div class="dialog_style img_display common_part1 cancel_act" style="max-height:550px;">
        <div class="close close_part dynamic_modal_close">✕<span class="dynamic_modal_close">閉じる</span></div>
        <div class="sched_tit">画像選択</div>
        <div class="img_list" style="max-height: 500px;">
            <?php if (!empty($dispData)) : ?>
                <?php foreach ($dispData as $keyid => $val) : ?>
                    <ul class="duplicate1">
                        <li>
                            <p class="mid">タグ名:<?= !empty($val['tag']) ? $val['tag'] : "" ?></p>
                            <p class="app_date">該当月：<?= !empty($val['month']) ? $val['month'] : "" ?></p>
                            <?php if (!empty($val['image'])) : ?>
                                <p class="btn edit">詳細</p>
                            <?php endif; ?>
                        </li>
                        <li>
                            <img src="<?= !empty($val['image']) ? $val['image'] : '' ?>" alt="<?= !empty($val['tag']) ? $val['tag'] : '' ?>" class="popup">
                        </li>
                        <li>
                            <p class="r_date">登録日：<?= !empty($val['entry_day']) ? $val['entry_day'] : "" ?></p>
                            <p class="a_person">登録者:<?= !empty($val['name']) ? $val['name'] : "" ?></p>
                            <p class="memo"><textarea><?= !empty($val['memo']) ? $val['memo'] : "" ?></textarea></p>
                                                        </li>
                                                    </ul>
                                                <div class="new_default img_details duplicated1 cancel_act">
                                                <div class="close close_part">✕<span>閉じる</span></div>
                                                    <div class="sched_tit pc">画像選択</div>
                                                    <div class="cont_details"><img src="<?= !empty($val['image']) ? $val['image'] : '' ?>" alt="<?= !empty($val['tag']) ? $val['tag'] : '' ?>"></div>
                                                    <div class="btn back prev_page_toggle">画像一覧にもどる</div>
                                                </div>

                <?php endforeach; ?>
            <?php else : ?>
                                <p >画像は登録されていません。</p>
            <?php endif; ?>
        </div>
    </div>
                            <div id="modal-container">
                                <div><img src=""></div>
                            </div>
    <script>
        $(function () {
            // ダイアログクローズ
            $(".dynamic_modal_close").on("click", function () {
                $(".dynamic_modal").remove();
            });
            const modal = $('#modal-container');
            const img = modal.find('img');

            $('img.popup').each(function (index) {
                $(this).click(function () {
                    img.attr('src', $(this).attr('src'));
                    modal.show();
                });
            });

            modal.click(function () {
                $(this).hide();
            });
        });
    </script>
</div>