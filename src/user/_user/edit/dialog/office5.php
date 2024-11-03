<?php
/* ===================================================
 * スタッフ検索モーダル
 * ===================================================
 */

/* ===================================================
 * 初期処理
 * ===================================================
 */

/* --共通ファイル呼び出し------------------------------------- */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

/* --変数定義------------------------------------------------- */
// 初期化
$err = array();
$_SESSION['notice']['error'] = array();
$dispData = array();
$tgtData = array();

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/* -- 検索用パラメータ --------------------------------------- */

// 拠点ID
$placeId = filter_input(INPUT_GET, 'place');
if (!$placeId) {
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
}

// 利用者(外部ID)取得
$userOfcId = filter_input(INPUT_GET, 'id');

/* -- 更新用パラメータ --------------------------------------- */

/* ===================================================
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ---------------------------------------- */

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */

/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- マスタ関連 -------------------------------------------- */

/* -- データ取得 -------------------------------------------- */

// 契約事業所
$ofcData = array();
$where = array();
$where['delete_flg'] = 0;
$where['unique_id'] = $userOfcId;
$orderBy = 'unique_id ASC';
$temp = select('mst_user_office', '*', $where, $orderBy);
foreach ($temp as $val) {
    $ofcData = $val[0];
}

/* -- 画面表示データ格納 ---------------------------- */
?>
<div class="dynamic_modal modal_office5 cancel_act cont_user-dup" style="height:600px;z-index: 3;">
    <div class="tit">契約事業所編集</div>
    <div class="close close_part modal_close">✕<span class="modal_close">閉じる</span></div>
    <div class="tab-main-box">
        <table>
            <tr class="tr2" style="border:0;">
                <td>
                    <div>
                        <input type="text" name="upOfc3[<?= $userId ?>][office_name]" value="<?= $ofcData['office_name'] ?>" class="tgt-name2_<?= $userId ?> bg-gray3 office_name3" readonly>
                    </div>
                </td>
            </tr>
            <tr style="border:0;">
                <td>
                    <div>
                        <span class="label_t">開始/終了</span>
                        <input type="date" name="" class="date_start5" value="<?= $ofcData['start_day'] ?>" >
                        <small>～</small>
                        <input type="date" name="" class="date_end5" value="<?= $ofcData['end_day'] ?>">
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal_office2_btn">
        <button type="button" name="" class="btn_chg_office_his" value="">この内容で編集する</button>
    </div>
    <script>
        var dateStart = "";
        var dateEnd5 = "";
        var officeId5 = "";
        var officeNo5 = "";
        var officeName5 = "";
        var placeName5 = "";
        var index = <?= empty($index) ? $index : null ?>;
        if(!index){
            index = "";
        }

        $(function () {
            // ダイアログクローズ
            $(".modal_close").on("click", function () {
                // windowを閉じる
                $(".dynamic_modal").remove();
            });

            $('.btn_chg_office_his').on('click', function () {

                if (!dateStart5) {
                    alert("開始日を入力して下さい。");
                    return false;
                }

                // 更新処理実行
                chg_office_his(dateStart5, dateEnd5, officeName5);

                // windowを閉じる
                $(".dynamic_modal").remove();
            });
        });

        function chg_office_his(startDay, endDay, officeName, index) {
            $(".hisOfc"+ index +"_start_day").val(startDay);
            $(".hisOfc"+ index +"_end_day").val(endDay);
//            $(".hisOfc"+ index +"_office_name").val(officeName);
        }


    </script>    
</div>