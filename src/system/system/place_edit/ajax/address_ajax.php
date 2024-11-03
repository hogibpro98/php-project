<?php

//=====================================================================
// [ajax]住所検索(標準)
//=====================================================================
try {
    /* ===================================================
     * 初期処理
     * ===================================================
     */

    /*--共通ファイル呼び出し-------------------------------------*/
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

    /*--変数定義-------------------------------------------------*/
    $_SESSION['notice']['error']   = null;
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

    // 初期化
    $upAry['prefecture'] = null;
    $upAry['area']       = null;
    $upAry['address1']   = null;

    // 都道府県、市区町村、町域取得
    if ($type === 'post') {

        if (!empty($upAry['post'])) {

            // マスタ取得
            $where = array();
            $where['delete_flg'] = 0;
            $zip = substr($upAry['post'], 0, 3) . '-' . substr($upAry['post'], -4);
            $where['post'] = $zip;
            $temp = select('mst_area', 'prefecture_name,city_name,town_name', $where);

            // 補完
            if (isset($temp[0])) {
                $upAry['post']       = $zip;
                $upAry['prefecture'] = $temp[0]['prefecture_name'];
                $upAry['area']       = $temp[0]['city_name'];
                $upAry['address1']   = $temp[0]['town_name'];
            } else {
                $_SESSION['notice']['error'] = '検索条件に合致しません';
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
    if ($_SESSION['notice']['error']) {
        echo sprintf("noticeModal(%s);", jsonEncode($_SESSION['notice']['error']));
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
