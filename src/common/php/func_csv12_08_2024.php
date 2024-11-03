<?php

/* =======================================================================
 * CSV関連
 * =======================================================================
 */

/* =======================================================================
 * CSVファイル読込関数
 * =======================================================================
 *
 *   [使用方法]
 *      $res = readCsv(①,②,③,④)
 *
 *   [引数]
 *      ① ファイルパス     : 文字列(必須) <例> /csv/xxx.csv 等
 *      ② エンコードフラグ : 真偽値(デフォルトはfalse)
 *                            trueの場合、対象ファイルを③で指定した文字コードに上書き
 *      ③ 変換後文字コード :文字列(デフォルトはutf-8) 変換したい文字コードを指定
 *      ④ 変換前文字コード :文字列(デフォルトは代表的なエンコード)検出したい文字コードのリスト
 *   [戻り値]  ※配列
 *      $res[row] = col1,col2,col3,････
 *
 *   ※終端の空行を除く仕様
 *
 * -----------------------------------------------------------------------
 */

function readCsv($filepath, $flg = false, $toEncode = 'UTF-8', $fromEncode = "ASCII,SJIS-win,UTF-8,JIS,EUC-JP,SJIS")
{
    $res = array();
    // ファイル文字コード変換
    /* ※※上書き失敗時の処理は、システム仕様に合わせて書き換え必要※※ */
    if ($flg) {
        // csvファイルを文字列として取得
        $txt = file_get_contents($filepath);
        // 文字コード変換
        $txt = mb_convert_encoding($txt, $toEncode, $fromEncode);
        // ファイル上書き
        if (!file_put_contents($filepath, $txt)) {
            return $res;
        }
    }
    // csv読み込み
    $file = new SplFileObject($filepath);
    $file->setFlags(SplFileObject::READ_CSV);
    foreach ($file as $line) {
        if (isset($line[0])) {
            $res[] = $line;
        }
    }
    // 変換後エンコードがUTF-8の場合、先頭セル[0][0]のBOMを''に置換
    if ($toEncode === 'UTF-8' && isset($res[0][0])) {
        $res[0][0] = preg_replace('/^\xEF\xBB\xBF/', '', $res[0][0]);
    }
    return $res;
}

/* =======================================================================
 * CSVファイル書込関数
 * =======================================================================
 *
 *   [使用方法]
 *      $res = writeCsv(①, ②)
 *
 *   [引数]
 *      ① ファイルパス   － 文字列(必須) <例> /csv/xxx.csv 等
 *      ② データ格納配列 － 配列(必須)
 *      ③ 変換先エンコード（任意）　未指定の場合　'SJIS-win'
 *
 *   [戻り値]  ※配列
 *      $res[row] = col1,col2,col3,････
 *
 *   ※終端の空行を除く仕様
 *
 * -----------------------------------------------------------------------
 */

function writeCsv2($filepath, $data, $encode = 'SJIS-win')
{
    $file = new SplFileObject($filepath, 'w');
    //    $file->fwrite(pack('C*', 0xEF, 0xBB, 0xBF));
    foreach ($data as $idx => $line) {
        // 文字コード変換。エクセルで開けるようにする
        mb_convert_variables($encode, 'UTF-8', $line);
        //$file->fputcsv($line);
        $lineData = "";
        $dataCode = $line['code'];

        if ($dataCode === "00") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
        } elseif ($dataCode === "11") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
        } elseif ($dataCode === "12") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
            $lineData .= isset($line['f8']) ? "," . "\"" . $line['f8'] . "\"" : "";
            $lineData .= isset($line['f9']) ? "," . "\"" . $line['f9'] . "\"" : "";
        } elseif ($dataCode === "13") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
            $lineData .= isset($line['f8']) ? "," . "\"" . $line['f8'] . "\"" : "";
            $lineData .= isset($line['f9']) ? "," . "\"" . $line['f9'] . "\"" : "";
            //            $lineData .= isset($line['f10']) ? "," . "\"" . $line['f10'] . "\"" : "";
        } elseif ($dataCode === "21") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
            $lineData .= isset($line['f8']) ? "," . "\"" . $line['f8'] . "\"" : "";
        } elseif ($dataCode === "22") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
        } elseif ($dataCode === "23") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
        } elseif ($dataCode === "24") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
        } elseif ($dataCode === "25") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
            $lineData .= isset($line['f8']) ? "," . "\"" . $line['f8'] . "\"" : "";
            $lineData .= isset($line['f9']) ? "," . "\"" . $line['f9'] . "\"" : "";
            $lineData .= isset($line['f10']) ? "," . "\"" . $line['f10'] . "\"" : "";
            $lineData .= isset($line['f11']) ? "," . "\"" . $line['f11'] . "\"" : "";
            $lineData .= isset($line['f12']) ? "," . "\"" . $line['f12'] . "\"" : "";
            $lineData .= isset($line['f13']) ? "," . "\"" . $line['f13'] . "\"" : "";
            $lineData .= isset($line['f14']) ? "," . "\"" . $line['f14'] . "\"" : "";
            $lineData .= isset($line['f15']) ? "," . "\"" . $line['f15'] . "\"" : "";
        } elseif ($dataCode === "31") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
            $lineData .= isset($line['f8']) ? "," . "\"" . $line['f8'] . "\"" : "";
            $lineData .= isset($line['f9']) ? "," . "\"" . $line['f9'] . "\"" : "";
            $lineData .= isset($line['f10']) ? "," . "\"" . $line['f10'] . "\"" : "";
            $lineData .= isset($line['f11']) ? "," . "\"" . $line['f11'] . "\"" : "";
            $lineData .= isset($line['f12']) ? "," . "\"" . $line['f12'] . "\"" : "";
            $lineData .= isset($line['f13']) ? "," . "\"" . $line['f13'] . "\"" : "";
            $lineData .= isset($line['f14']) ? "," . "\"" . $line['f14'] . "\"" : "";
            $lineData .= isset($line['f15']) ? "," . "\"" . $line['f15'] . "\"" : "";
            $lineData .= isset($line['f16']) ? "," . "\"" . $line['f16'] . "\"" : "";
        } elseif ($dataCode === "32") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
            $lineData .= isset($line['f8']) ? "," . "\"" . $line['f8'] . "\"" : "";
            $lineData .= isset($line['f9']) ? "," . "\"" . $line['f9'] . "\"" : "";
            $lineData .= isset($line['f10']) ? "," . "\"" . $line['f10'] . "\"" : "";
            $lineData .= isset($line['f11']) ? "," . "\"" . $line['f11'] . "\"" : "";
            $lineData .= isset($line['f12']) ? "," . "\"" . $line['f12'] . "\"" : "";
            $lineData .= isset($line['f13']) ? "," . "\"" . $line['f13'] . "\"" : "";
            $lineData .= isset($line['f14']) ? "," . "\"" . $line['f14'] . "\"" : "";
            $lineData .= isset($line['f15']) ? "," . "\"" . $line['f15'] . "\"" : "";
            $lineData .= isset($line['f16']) ? "," . "\"" . $line['f16'] . "\"" : "";
        } elseif ($dataCode === "33") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
            $lineData .= isset($line['f8']) ? "," . "\"" . $line['f8'] . "\"" : "";
            $lineData .= isset($line['f9']) ? "," . "\"" . $line['f9'] . "\"" : "";
            $lineData .= isset($line['f10']) ? "," . "\"" . $line['f10'] . "\"" : "";
            $lineData .= isset($line['f11']) ? "," . "\"" . $line['f11'] . "\"" : "";
        } elseif ($dataCode === "34") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
            $lineData .= isset($line['f8']) ? "," . "\"" . $line['f8'] . "\"" : "";
            $lineData .= isset($line['f9']) ? "," . "\"" . $line['f9'] . "\"" : "";
            $lineData .= isset($line['f10']) ? "," . "\"" . $line['f10'] . "\"" : "";
            $lineData .= isset($line['f11']) ? "," . "\"" . $line['f11'] . "\"" : "";
            $lineData .= isset($line['f12']) ? "," . "\"" . $line['f12'] . "\"" : "";
            $lineData .= isset($line['f13']) ? "," . "\"" . $line['f13'] . "\"" : "";
        } elseif ($dataCode === "35") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
        } elseif ($dataCode === "36") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
            $lineData .= isset($line['f8']) ? "," . "\"" . $line['f8'] . "\"" : "";
            $lineData .= isset($line['f9']) ? "," . "\"" . $line['f9'] . "\"" : "";
            $lineData .= isset($line['f10']) ? "," . "\"" . $line['f10'] . "\"" : "";
            $lineData .= isset($line['f11']) ? "," . "\"" . $line['f11'] . "\"" : "";
            $lineData .= isset($line['f12']) ? "," . "\"" . $line['f12'] . "\"" : "";
            $lineData .= isset($line['f13']) ? "," . "\"" . $line['f13'] . "\"" : "";
            $lineData .= isset($line['f14']) ? "," . "\"" . $line['f14'] . "\"" : "";
            $lineData .= isset($line['f15']) ? "," . "\"" . $line['f15'] . "\"" : "";
        } elseif ($dataCode === "37") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
        } elseif ($dataCode === "38") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
        } elseif ($dataCode === "41") {
            $lineData .= $line['code'];
            $lineData .= isset($line['f1']) ? "," . "\"" . $line['f1'] . "\"" : "";
            $lineData .= isset($line['f2']) ? "," . "\"" . $line['f2'] . "\"" : "";
            $lineData .= isset($line['f3']) ? "," . "\"" . $line['f3'] . "\"" : "";
            $lineData .= isset($line['f4']) ? "," . "\"" . $line['f4'] . "\"" : "";
            $lineData .= isset($line['f5']) ? "," . "\"" . $line['f5'] . "\"" : "";
            $lineData .= isset($line['f6']) ? "," . "\"" . $line['f6'] . "\"" : "";
            $lineData .= isset($line['f7']) ? "," . "\"" . $line['f7'] . "\"" : "";
            $lineData .= isset($line['f8']) ? "," . "\"" . $line['f8'] . "\"" : "";
            $lineData .= isset($line['f9']) ? "," . "\"" . $line['f9'] . "\"" : "";
            $lineData .= isset($line['f10']) ? "," . "\"" . $line['f10'] . "\"" : "";
            $lineData .= isset($line['f11']) ? "," . "\"" . $line['f11'] . "\"" : "";
        }
        $lineData .= "\r\n";
        //            $lineData = "\"" . implode("\",\"", $line) . "\"\n";

        $file->fwrite($lineData);
        $lineData = "";
    }
    $file = null;
}

/* =======================================================================
 * CSVファイル書込関数
 * =======================================================================
 *
 *   [使用方法]
 *      $res = writeCsv(①, ②)
 *
 *   [引数]
 *      ① ファイルパス   － 文字列(必須) <例> /csv/xxx.csv 等
 *      ② データ格納配列 － 配列(必須)
 *      ③ 変換先エンコード（任意）　未指定の場合　'SJIS-win'
 *
 *   [戻り値]  ※配列
 *      $res[row] = col1,col2,col3,････
 *
 *   ※終端の空行を除く仕様
 *
 * -----------------------------------------------------------------------
 */
function writeCsv($filepath, $data, $encode = 'SJIS-win')
{
    $file = new SplFileObject($filepath, 'w');
    $file->fwrite(pack('C*', 0xEF, 0xBB, 0xBF));
    foreach ($data as $line) {
        // 文字コード変換。エクセルで開けるようにする
        //mb_convert_variables($encode, 'UTF-8', $line);
        $file->fputcsv($line);
    }
    $file = null;
}

/* =======================================================================
 * CSVファイル書込関数(ファイルロックVer.)
 * =======================================================================
 *
 *   [使用方法]
 *      $res = writeCsvLock(①, ②)
 *
 *   [引数]
 *      ① ファイルパス   － 文字列(必須) <例> /csv/xxx.csv 等
 *      ② データ格納配列 － 配列(必須)
 *      ③ 変換先エンコード（任意）　未指定の場合　'SJIS-win'
 *
 *   [戻り値]  ※配列
 *      $res[row] = col1,col2,col3,････
 *
 *   ※終端の空行を除く仕様
 *
 * -----------------------------------------------------------------------
 */

function writeCsvLock($filepath, $data, $encode = 'SJIS-win')
{
    $file = new SplFileObject($filepath, 'w');
    if ($file->flock(LOCK_EX)) {
        foreach ($data as $line) {
            // 文字コード変換。エクセルで開けるようにする
            mb_convert_variables($encode, 'UTF-8', $line);
            $file->fputcsv($line);
        }
        $file->flock(LOCK_UN);
    }
}

/* =======================================================================
 * 標準CSV出力
 * =======================================================================
 *
 *   [引数]
 *    ①発行区分
 *    ②発行データ
 *
 *   [戻り値]
 *      なし
 *      CSVファイルを作成
 *
 * -----------------------------------------------------------------------
 */

function makeDispCsv($type = null, $dispData = array(), $headDisp = true)
{

    /* -- 初期値 ----------------------------------------------- */
    $res = array();
    $item = array();
    $output = array();

    /* -- パラメータチェック ----------------------------------- */
    if (!is_string($type)) {
        $res['err'] = '発行区分が不適切です';
        return $res;
    }
    if (!is_array($dispData)) {
        $res['err'] = '描画データが不適切です';
        return $res;
    }
    /* -- 発行区分別 出力対象定義 ------------------------------ */

    switch ($type) {
        case 'account':
            $item['code'] = '';
            $item['f1'] = '';
            $item['f2'] = '';
            $item['f3'] = '';
            $item['f4'] = '';
            $item['f5'] = '';
            $item['f6'] = '';
            $item['f7'] = '';
            $item['f8'] = '';
            $item['f9'] = '';
            $item['f10'] = '';
            $item['f11'] = '';
            $item['f12'] = '';
            $item['f13'] = '';
            $item['f14'] = '';
            $item['f15'] = '';
            $item['f16'] = '';
            break;

        default:
            break;
    }

    /* -- CSV出力ファイル作成 ---------------------------------- */

    // ヘッダー部
    if ($headDisp) {
        $output[] = $item;
    }
    // データ部(集計)
    foreach ($dispData as $dat) {
        $record = array();
        foreach ($item as $key => $name) {
            $record[$key] = isset($dat[$key]) ? $dat[$key] : "";
        }
        $output[] = $record;
    }

    /* -- CSV出力処理 ------------------------------------------ */

    // ディレクトリ生成
    $dir = SV_ROOT . '/csv/' . $type;
    $url = '/csv/' . $type;

    if (!is_dir($dir)) {
        umask(0);
        if (!mkdir($dir, 2777)) {
            $err[] = 'CSV出力フォルダ作成に失敗しました。';
            throw new Exception();
        }
    }

    // ファイル名称、参照パス
    $filename = $type . '_' . date('YmdHis') . '.csv';
    $filepath = $dir . "/" . $filename;
    $fileUrl  = $url . "/" . $filename;

    // CSV出力処理
    writeCsv2($filepath, $output);
    /*
      header("Content-Type: application/octet-stream");
      header('Content-Disposition: attachment; filename='.$filename);
      header("Content-Transfer-Encoding: binary");
      header('Content-Length: '.filesize($filepath));
      readfile($filepath);
     */

    $_SESSION['file_url']  = $fileUrl;
    $_SESSION['file_path'] = $filepath;
    $_SESSION['file_name'] = $filename;

    //    exit();
}
