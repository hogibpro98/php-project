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
    $notice = NULL;
    $sendData = "";
    $dispData = array();

    /* =================================================== 
     * 入力情報取得
     * ===================================================
     */

// 検索場所
    $freeword = h(filter_input(INPUT_POST, 'freeword'));
    $tgtName = h(filter_input(INPUT_POST, 'tgt_name'));
    $tgtFlg = h(filter_input(INPUT_POST, 'tgt_flg'));
    /* =================================================== 
     * イベント後処理(描画用データ作成)
     * ===================================================
     */
    /* -- データ取得 ---------------------------------------- */

    $sql = "";
    $sql .= "SELECT * FROM `mst_disease` ";
    $sql .= "WHERE name LIKE '%" . $freeword . "%' OR disp_name LIKE '%" . $freeword . "%' OR kana LIKE '%" . $freeword . "%';";
    $temp = customSQL($sql);
    foreach ($temp as $val) {
        $keyId = $val['unique_id'];
        $dispData[$keyId] = $val;
    }

    /* -- 送信データ作成 -------------------------------------- */
    $sendData .= '';
    $sendData .= '<table>';
    $sendData .= '  <thead>';
    $sendData .= '    <tr>';
    $sendData .= '      <th></th>';
    $sendData .= '      <th>傷病名コード</th>';
    $sendData .= '      <th>傷病名</th>';
    $sendData .= '      <th>カナ</th>';
    $sendData .= '      <th>別表７対象</th>';
    $sendData .= '    </tr>';
    $sendData .= '  </thead>';
    $sendData .= '  <tbody>';
    foreach ($dispData as $uqId => $val) {
        $sendData .= '<tr class="tr2" style="border:0;">';
        $sendData .= '  <td>';
        $sendData .= '    <button class="modal_selected" type="button" ';
        $sendData .= '      data-unique_id="' . $val['unique_id'] . '" ';
        $sendData .= '      data-disease_cd="' . $val['code'] . '" ';
        $sendData .= '      data-disease_name="' . $val['name'] . '" ';
        $sendData .= '      data-disease_disp_name="' . $val['disp_name'] . '" ';
        $sendData .= '      data-disease_kana="' . $val['kana'] . '" ';
        $sendData .= '      data-tgt7="' . $val['target_flg1'] . '" ';
        $sendData .= '      data-tgt8="' . $val['target_flg2'] . '" ';
        $sendData .= '    >選択</button>';
        $sendData .= '  </td>';
        $sendData .= '  <td>' . $val['code'] . '</td>';
        $sendData .= '  <td>' . $val['name'] . '</td>';
        $sendData .= '  <td>' . $val['kana'] . '</td>';
        $Flag7 = empty($val['target_flg1']) ? "" : "〇";
        $sendData .= '  <td style="text-align: center;">' . $Flag7  . '</td>';
        $sendData .= '</tr>';
    }
    $sendData .= '  </tbody>';
    $sendData .= '</table>';
    $sendData .= '<script>';
    $sendData .= '  $(function(){';
    $sendData .= '    $(".modal_selected").on("click", function(){';
    $sendData .= '      var code = $(this).data("disease_cd");';
    $sendData .= '      var name = $(this).data("disease_name");';
    $sendData .= '      var dispName = $(this).data("disease_disp_name");';
    $sendData .= '      var kana = $(this).data("disease_kana");';
    $sendData .= '      var tgt7 = $(this).data("tgt7");';
    $sendData .= '      var tgtIdx = $(this).data("tgtIdx");';
    $sendData .= '      if(tgt7 == "1"){';
    $sendData .= '        $(".' . $tgtFlg . '").addClass("select7");';
    $sendData .= '      }else{';
    $sendData .= '        $(".' . $tgtFlg . '").removeClass("select7");';
    $sendData .= '      }';
    $sendData .= '      $(".' . $tgtName . '").val(dispName);';
    $sendData .= '      $(".modal_setting").children().remove();';
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
    exit();

    /* =================================================== 
     * 例外処理
     * ===================================================
     */
} catch (Exception $e) {
    debug($e);
    exit();
    $_SESSION['err'] = !empty($err) ? $err : array();
    header("Location:" . ERROR_PAGE);
    exit();
}