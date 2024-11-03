<?php

//=====================================================================
// [ajax]住所検索(緊急連絡先)
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
    $type = (int)$type;

    // 更新内容
    $upEmg = filter_input(INPUT_GET, 'upEmg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upEmg = $upEmg ? $upEmg : array();


    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */
    /* -- データ取得 ----------------------------------------*/



    /* -- 検索結果反映 --------------------------------------*/



    // 入力内容がある時に検索実行
    if (!empty($upEmg[$type]['post'])) {

        // 初期化
        $upEmg[$type]['prefecture'] = null;
        $upEmg[$type]['area']       = null;
        $upEmg[$type]['address1']   = null;

        // マスタ取得
        $where = array();
        $where['delete_flg'] = 0;
        $zip = $upEmg[$type]['post'];
        $where['post'] = substr($zip, 0, 3) . '-' . substr($zip, -4);
        $temp = select('mst_area', 'prefecture_name,city_name,town_name', $where);

        // 補完
        if (isset($temp[0])) {
            $upEmg[$type]['prefecture'] = $temp[0]['prefecture_name'];
            $upEmg[$type]['area']       = $temp[0]['city_name'];
            $upEmg[$type]['address1']   = $temp[0]['town_name'];
        } else {
            $notice = '検索条件に合致しません';
        }
    }




    /* -- データ送信 ----------------------------------------*/
    $sendData = array();
    foreach ($upEmg as $seq => $upEmg2) {
        foreach ($upEmg2 as $key => $val) {
            $sendData['upEmg[' . $seq . '][' . $key . ']'] = $val;
        }
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
