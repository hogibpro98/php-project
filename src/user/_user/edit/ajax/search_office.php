<?php

//=====================================================================
// [ajax]住所検索(緊急連絡先)
//=====================================================================
try {
    /* ===================================================
     * 初期処理
     * ===================================================
     */

    /* --共通ファイル呼び出し------------------------------------- */
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

    /* --変数定義------------------------------------------------- */
    $notice = null;
    $sendData = "";
    $dispData = array();

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    // 検索場所
    $freeword = h(filter_input(INPUT_POST, 'freeword'));
    $prefecture = h(filter_input(INPUT_POST, 'prefecture'));
    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */
    /* -- データ取得 ---------------------------------------- */
    $where = array();
    $where['delete_flg'] = 0;
    $where['prefecture'] = $prefecture;
    //    $orderBy = "office_name ASC";
    //    $limit = 1000;
    $temp = select('mst_office_other', '*', $where);//, $orderBy);//, $limit);
    foreach ($temp as $val) {

        // 検索ワード作成
        $word = $val['wellness_no'] . " ";
        $word .= $val['corp_id'] . " ";
        $word .= $val['office_id'] . " ";
        $word .= $val['corp_name'] . " ";
        $word .= $val['office_name'] . " ";
        $word .= $val['prefecture'] . " ";
        $word .= $val['area'] . " ";
        $word .= $val['address'] . " ";
        $word .= $val['tel'] . " ";
        $word .= $val['fax'] . " ";

        // フリーワード絞込
        if ($freeword) {
            if (mb_strpos($word, $freeword) === false) {
                continue;
            }
        }
        $keyId = $val['unique_id'];
        $dispData[$keyId] = $val;
    }

    /* -- 送信データ作成 -------------------------------------- */
    $sendData .= '';
    $sendData .= '<table>';
    $sendData .= '  <thead>';
    $sendData .= '    <tr>';
    $sendData .= '      <th></th>';
    $sendData .= '      <th>事業所ID</th>';
    $sendData .= '      <th>事業所名</th>';
    $sendData .= '      <th>住所</th>';
    $sendData .= '    </tr>';
    $sendData .= '  </thead>';
    $sendData .= '  <tbody>';
    foreach ($dispData as $uqId => $val) {
        $sendData .= '<tr class="tr2" style="border:0;">';
        $sendData .= '  <td>';
        $sendData .= '    <button class="modal_selected" type="button" ';
        $sendData .= '      data-unique_id="' . $val['unique_id'] . '" ';
        $sendData .= '      data-office_id="' . $val['office_id'] . '" ';
        $sendData .= '      data-office_name="' . $val['corp_name'] . $val['office_name'] . '" ';
        $sendData .= '      data-address="' . $val['prefecture'] . $val['area'] . $val['address'] . '" ';
        $sendData .= '      data-tel="' . $val['tel'] . '" ';
        $sendData .= '      data-fax="' . $val['fax'] . '" ';
        $sendData .= '    >選択</button>';
        $sendData .= '  </td>';
        $sendData .= '  <td>' . $val['office_id'] . '</td>';
        $sendData .= '  <td>' . $val['corp_name'] . $val['office_name'] . '</td>';
        $sendData .= '  <td>' . $val['prefecture'] . $val['area'] . $val['address'] . '</td>';
        $sendData .= '</tr>';
    }
    $sendData .= '  </tbody>';
    $sendData .= '</table>';
    $sendData .= '<script>';
    $sendData .= '  $(function(){';
    $sendData .= '    $(".modal_selected").on("click", function(){';
    $sendData .= '      var officeCode = $(this).data("office_id");';
    $sendData .= '      var officeName = $(this).data("office_name");';
    $sendData .= '      var address = $(this).data("address");';
    $sendData .= '      var tel = $(this).data("tel");';
    $sendData .= '      var fax = $(this).data("fax");';

    $sendData .= '      $(".ofc2_office_code").val(officeCode);';
    $sendData .= '      $(".ofc2_office_name").val(officeName);';
    $sendData .= '      $(".ofc2_address").val(address);';
    $sendData .= '      $(".ofc2_tel").val(tel);';
    $sendData .= '      $(".ofc2_fax").val(fax);';
    $sendData .= '      $(".modal_office_close").parent().parent().remove();';
    $sendData .= '    });';
    $sendData .= '  });';
    $sendData .= '</script>';

    /* -- データ送信 ---------------------------------------- */
    if ($sendData) {
        echo $sendData;
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
