<?php

//=====================================================================
// [ajax]銀行検索
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
    $upPay = filter_input(INPUT_GET, 'upPay', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upPay = $upPay ? $upPay : array();


    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */
    /* -- データ取得 ----------------------------------------*/



    /* -- 検索結果反映 --------------------------------------*/

    // 銀行名補完
    if ($type === 'bankCD') {
        $upPay['bank_name'] = null;
        if (!empty($upPay['bank_code'])) {

            // 銀行コード(ゼロ埋め)
            $upPay['bank_code'] = sprintf('%04d', $upPay['bank_code']);

            // マスタ取得
            $where = array();
            $where['delete_flg'] = 0;
            $where['bank_code']  = $upPay['bank_code'];
            $temp = select('mst_bank', 'bank_name', $where);

            // 銀行名を補完
            if (isset($temp[0])) {
                $upPay['bank_name'] = $temp[0]['bank_name'];
            } else {
                $notice = '検索条件に合致しません';
            }
        }
    }

    // 支店名補完
    if ($type === 'branchCD') {
        $upPay['bank_name']   = null;
        $upPay['branch_name'] = null;
        if (!empty($upPay['bank_code'])) {

            // 銀行コード(ゼロ埋め)
            $upPay['bank_code']   = sprintf('%04d', $upPay['bank_code']);
            $upPay['branch_code'] = sprintf('%03d', $upPay['branch_code']);

            // マスタ取得
            $where = array();
            $where['delete_flg']  = 0;
            $where['bank_code']   = $upPay['bank_code'];
            $where['branch_code'] = $upPay['branch_code'];
            $temp = select('mst_bank', 'bank_name,branch_name', $where);

            // 銀行名を補完
            if (isset($temp[0])) {
                $upPay['bank_name']   = $temp[0]['bank_name'];
                $upPay['branch_name'] = $temp[0]['branch_name'];
            } else {
                $notice = '検索条件に合致しません';
            }
        }
    }


    /* -- データ送信 ----------------------------------------*/
    $sendData = array();
    foreach ($upPay as $key => $val) {
        $sendData['upPay[' . $key . ']'] = $val;
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
