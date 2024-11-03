<?php

//=====================================================================
// [ajax]郵便番号検索
//=====================================================================
try {
    /* ===================================================
     * 初期処理
     * ===================================================
     */

    /*--共通ファイル呼び出し-------------------------------------*/
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

    /*--変数定義-------------------------------------------------*/
    $notice   = null;
    $sendData = array();

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    // 検索場所
    $type = h(filter_input(INPUT_GET, 'type'));

    // 更新内容
    $upAry = filter_input(INPUT_GET, 'upAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upAry = $upAry ? $upAry : array();


    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */
    /* -- データ取得 ----------------------------------------*/



    /* -- 検索結果反映 --------------------------------------*/

    // 郵便番号検索
    if ($type === 'town') {

        // 初期化
        $upAry['post'] = null;

        // 入力内容がある時に検索実行
        if (!empty($upAry['address1'])) {

            // マスタ取得
            $where = array();
            $where['delete_flg'] = 0;
            $where['prefecture_name'] = isset($upAry['prefecture'])
                    ? $upAry['prefecture']
                    : 'dummy';
            $where['city_name'] = isset($upAry['area'])
                    ? $upAry['area']
                    : 'dummy';
            $where['town_name'] = isset($upAry['address1'])
                    ? $upAry['address1']
                    : 'dummy';
            $temp = select('mst_area', 'post', $where);

            // 銀行名を補完
            if (isset($temp[0])) {
                $upAry['post'] = $temp[0]['post'];
            } else {
                $notice = '検索条件に合致しません';
            }
        }
    }



    /* -- データ送信 ----------------------------------------*/
    $sendData = array();
    foreach ($upAry as $key => $val) {
        $sendData['upAry[' . $key . ']'] = $val;
    }
    // -----------------------------------------------------------

    if ($sendData) {
        echo sprintf("setMultiValue(%s);", jsonEncode($sendData));
    }

    // メッセージ送信
    if ($notice) {
        echo sprintf("noticeModal(%s);", jsonEncode($notice));
    }
    exit;

    /* ===================================================
     * 例外処理
     * ===================================================
     */
} catch (Exception $e) {
    debug($e);
    exit;
    $_SESSION['err'] = !empty($err) ? $err : array();
    header("Location:" . ERROR_PAGE);
    exit;
}
