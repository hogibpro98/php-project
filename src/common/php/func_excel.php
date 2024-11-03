<?php

//=======================================================================
//   外部データエンコード関数群
//=======================================================================

/* =======================================================================
 * 数値→アルファベット変換関数
 * =======================================================================
 *   [引数]
 *      ① 数値
 *
 *   [戻り値] 文字列
 *      アルファベット
 *
 * -----------------------------------------------------------------------
 */
function toAlpha($number)
{
    $alphabet =   array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $alpha_flip = array_flip($alphabet);
    if ($number <= 25) {
        return $alphabet[$number];
    } elseif ($number > 25) {
        $dividend = ($number + 1);
        $alpha = '';
        $modulo;
        while ($dividend > 0) {
            $modulo = ($dividend - 1) % 26;
            $alpha = $alphabet[$modulo] . $alpha;
            $dividend = floor(($dividend - $modulo) / 26);
        }
        return $alpha;
    }
}
/* =======================================================================
 * エクセル帳票関数
 * =======================================================================
 *
 *   [引数]
 *     ① 帳票ID
 *     ② 検索条件
 *
 *   [戻り値]
 *     $res
 *
 * -----------------------------------------------------------------------
 */
function printExcel($key = null, $search = array())
{

    // 初期化
    $tgtData  = array();
    $fileName = $key;

    // パス情報
    $tgtPath  = $_SERVER['DOCUMENT_ROOT'] . '/excel/' . $key;
    $outPath  = $tgtPath . '/output.xlsx';
    $template = $tgtPath . '/template.xlsx';

    // データ取得
    require_once($tgtPath . '/data.php');

    // 帳票出力
    if ($tgtData) {
        $res = writeExcel($outPath, $tgtData, $template, true, $fileName);
    }
}

/* =======================================================================
 * エクセル書き込み関数
 * =======================================================================
 *
 *   [引数]
 *      ① 出力ファイルパス
 *      ② 出力対象データ
 *      ③ ダウンロードファイル名称
 *      ④ ダウンロード出力
 *      ⑤ テンプレートパス
 *
 *   [戻り値] 文字列
 *      変換した文字列情報を返却
 *
 * -----------------------------------------------------------------------
 */
function writeExcel($outPath, $data, $templatePath = null, $output = true, $fileName = null)
{

    $res = null;

    // ライブラリ読込
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/plugin/PHPExcel-1.8/Classes/PHPExcel.php');

    // シート設定
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $book = $objReader->load($templatePath);

    //シート設定
    $book->setActiveSheetIndex(0);
    $objSheet = $book->getActiveSheet();

    // =====================================================================

    // データ出力
    foreach ($data as $rowNo => $record) {
        foreach ($record as $colNo => $string) {
            $point = toAlpha($colNo) . $rowNo;
            $objSheet->setCellValue($point, $string);
        }
    }

    // Excel2007形式で保存
    $objWriter = PHPExcel_IOFactory::createWriter($book, "Excel2007");
    $objWriter->save($outPath);

    // ダウンロード処理
    if ($output) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit;
    }

    return $res;
}
