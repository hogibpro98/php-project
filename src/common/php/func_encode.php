<?php

//=======================================================================
//   外部データエンコード関数群
//=======================================================================

/* =======================================================================
 * htmlspecialchars
 * =======================================================================
 *
 * 　[使用方法]
 *      $res = h(①)
 *
 *   [引数]
 *      ① 変換チェックする前の文字列情報
 *
 *   [戻り値] 文字列
 *      変換した文字列情報を返却
 *
 * -----------------------------------------------------------------------
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
/* =======================================================================
 * htmlspecialchars_decode
 * =======================================================================
 *
 *   [使用方法]
 *      $res = hd(①)
 *
 *   [引数]
 *      ① 変換チェックする前の文字列情報
 *
 *   [戻り値] 文字列
 *      変換した文字列情報を返却
 *
 * -----------------------------------------------------------------------
 */
function hd($str)
{
    return htmlspecialchars_decode($str);
}

/* =======================================================================
 * php->JSON変換関数
 * =======================================================================
 *
 * 　[使用方法]
 *      $res = jsonEncode(①)
 *      jsonエンコードのフィルターを設定するための関数
 *
 *   [引数]
 *      ① 変換したい配列
 *
 *   [戻り値] 文字列
 *      変換した文字列情報を返却
 *
 * -----------------------------------------------------------------------
 */

function jsonEncode($ary)
{
    return json_encode($ary, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

// =======================================================================
// JSONファイル読み込み関数
// =======================================================================
/*
 * 概要
 *  指定したJSONファイルを、配列に変換して返す
 * 引数
 *  JSONファイルのパス
 * 戻り値
 *  配列
 */
function loadJsonFile($filePath = null)
{
    $res = array();
    $jsonData = null;
    // ファイルパスが渡っていない、もしくはファイルが存在しない場合
    if (!$filePath || !file_exists($filePath)) {
        return null;
    }
    // ファイル内容を全て取得
    $jsonData = file_get_contents($filePath, false, null);
    if (!$jsonData) {
        return null;
    }
    // BOMを削除して連想配列へ変換
    return json_decode(preg_replace('/^\xEF\xBB\xBF/', '', $jsonData), true);
}


/* =======================================================================
 * 制御コード抜き取り
 * =======================================================================
 */
function stripControl($str = null)
{
    $ctrlAry = array(
        "00", // NULl（ヌル）
        "01", // Start Of Heading（ヘッダ開始）
        "02", // Start of TeXt（テキスト開始）
        "03", // End of TeXt（テキスト終了）
        "04", // End Of Transmission（転送終了）
        "05", // ENQuiry（問合せ）
        "06", // ACKnowledge（肯定応答）
        "07", // BELl（ベル）
        "08", // Back Space（後退）
        /*    "09", // Horizontal Tabulation（水平タブ） */
        /*    "0A", // Line Feed（改行）*/
        "0B", // Vertical Tabulation（垂直タブ）
        "0C", // Form Feed（改ページ）
        /*    "0D", // Carriage Return（復帰）*/
        "0E", // Shift Out（シフトアウト）
        "0F", // Shift In（シフトイン）
        "10", // Data Link Escape（伝送制御拡張）
        "11", // Device Control 1（装置制御1）
        "12", // Device Control 2（装置制御2）
        "13", // Device Control 3（装置制御3）
        "14", // Device Control 4（装置制御4）
        "15", // Negative AcKnowledge（否定応答）
        "16", // SYNchronous idle（同期信号）
        "17", // End of Transmission Block（転送ブロック終了）
        "18", // CANcel（取消）
        "19", // End of Medium（媒体終端）
        "1A", // SUBstitute（置換）
        "1B", // ESCape（拡張）
        "1C", // File Separator（ファイル分離）
        "1D", // Group Separator（グループ分離）
        "1E", // Record Separator（レコード分離）
        "1F", // Unit Separator（ユニット分離）
        );

    $codeAry = array();
    foreach ($ctrlAry as $code) {
        $codeAry[] = hex2bin($code);
    }

    return str_replace($codeAry, "", $str);
}
