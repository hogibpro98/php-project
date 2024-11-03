<?php
/* =======================================================================
 * PDF用印刷関数
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
function printPDF($key = null, $search = array())
{

    /* -- 初期処理 -------------------------------------------------*/

    // 初期化
    $res = null;
    $printData   = array();
    $printConfig = array();
    $tgtData     = array();

    // 用紙サイズ(デフォルト：A4縦)
    $printConfig['AddPage']['format']      = 'A4';
    $printConfig['AddPage']['orientation'] = 'P';

    // 出力ファイル、テンプレートファイル
    $printConfig['output']   = $key . '_' . formatDateTime(NOW, 'YmdHis') . '.pdf';
    $printConfig['template'] = $_SERVER['DOCUMENT_ROOT'] . '/pdf/' . $key . '/template.pdf';

    /* -- 帳票別個別処理 -------------------------------------------*/

    // データ取得処理
    require_once($_SERVER['DOCUMENT_ROOT'] . '/pdf/' . $key . '/data.php');

    if (!$tgtData) {
        $res['err'] = '発行対象がありません';
        return $res;
    }

    // レイアウト定義
    require_once($_SERVER['DOCUMENT_ROOT'] . '/pdf/' . $key . '/layout.php');

    /* -- 出力処理 -------------------------------------------------*/
    if ($printData) {

        // 拡張
        require_once($_SERVER['DOCUMENT_ROOT'] . '/pdf/tcpdf/printExt.php');
        $printData = printExtensionApply($printData);

        // 出力
        require_once($_SERVER['DOCUMENT_ROOT'] . '/pdf/tcpdf/print.php');
    }

    /* -- 返却 -----------------------------------------------------*/
    return $res;
}

/* =======================================================================
 * レイアウト作成(テキスト)
 * =======================================================================
 *
 *   [引数]
 *     ① 印刷データ
 *     ② テキスト
 *     ③ x座標 開始位置
 *     ④ y座標 開始位置
 *     ⑤ 幅
 *     ⑥ 高さ
 *     ⑦ フォントサイズ
 *     ⑧ その他
 *
 *   [戻り値]
 *     $res
 *
 * -----------------------------------------------------------------------
 */
function makeTextPrt(
    $printData = array(),
    $txt = null,
    $x = 10,
    $y = 10,
    $w = 50,
    $h = 5,
    $size = 12,
    $align = 'L',
    $other = array()
) {

    // カウント
    $cnt = count($printData) + 1;

    // テキスト作成
    $printData[$cnt]['Text']['font']    = isset($other['font']) ? $other['font'] : 'ipamp';
    $printData[$cnt]['Text']['size']    = $size;
    $printData[$cnt]['Cell']['x']       = $x;
    $printData[$cnt]['Cell']['y']       = $y;
    $printData[$cnt]['Cell']['w']       = $w;
    $printData[$cnt]['Cell']['h']       = $h;
    $printData[$cnt]['Cell']['txt']     = $txt;
    $printData[$cnt]['Cell']['align']   = $align;
    $printData[$cnt]['Cell']['fill']    = isset($other['fill']) ? $other['fill'] : false;
    $printData[$cnt]['Cell']['calign']  = isset($other['calign']) ? $other['calign'] : 'T';
    $printData[$cnt]['Cell']['border']  = isset($other['border']) ? $other['border'] : false;
    $printData[$cnt]['Cell']['stretch'] = isset($other['stretch']) ? $other['stretch'] : 1;
    $printData[$cnt]['Line']['x1']      = isset($other['Line']['x1']) ? $other['Line']['x1'] : 0;
    $printData[$cnt]['Line']['y1']      = isset($other['Line']['y1']) ? $other['Line']['y1'] : 0;
    $printData[$cnt]['Line']['x2']      = isset($other['Line']['x2']) ? $other['Line']['x2'] : 0;
    $printData[$cnt]['Line']['y2']      = isset($other['Line']['y2']) ? $other['Line']['y2'] : 0;

    // 返却
    return $printData;
}

/* =======================================================================
 * レイアウト作成(テキスト) 改行
 * =======================================================================
 *
 *   [引数]
 *     ① 印刷データ
 *     ② テキスト
 *     ③ x座標 開始位置
 *     ④ y座標 開始位置
 *     ⑤ 幅
 *     ⑥ 高さ
 *     ⑦ フォントサイズ
 *     ⑧ その他
 *
 *   [戻り値]
 *     $res
 *
 * -----------------------------------------------------------------------
 */
function makeText2Prt(
    $printData = array(),
    $txt = null,
    $x = 10,
    $y = 10,
    $w = 50,
    $h = 5,
    $size = 12,
    $align = 'L',
    $other = array()
) {

    // カウント
    $cnt = count($printData) + 1;

    // テキスト作成
    $printData[$cnt]['Text']['font']         = isset($other['font']) ? $other['font'] : 'ipamp';
    $printData[$cnt]['Text']['size']         = $size;
    $printData[$cnt]['MultiCell']['x']       = $x;
    $printData[$cnt]['MultiCell']['y']       = $y;
    $printData[$cnt]['MultiCell']['w']       = $w;
    $printData[$cnt]['MultiCell']['h']       = $h;
    $printData[$cnt]['MultiCell']['txt']     = $txt;
    $printData[$cnt]['MultiCell']['align']   = $align;
    $printData[$cnt]['MultiCell']['border']  = isset($other['border']) ? $other['border'] : false;
    $printData[$cnt]['MultiCell']['fill']    = isset($other['fill']) ? $other['fill'] : false;
    $printData[$cnt]['MultiCell']['ln']      = isset($other['ln']) ? $other['ln'] : 0;

    // 返却
    return $printData;
}

/* =======================================================================
 * レイアウト作成(QRコード)
 * =======================================================================
 *
 *   [引数]
 *     ① 印刷データ
 *     ② テキスト
 *     ③ x座標 開始位置
 *     ④ y座標 開始位置
 *     ⑤ 幅
 *     ⑥ 高さ
 *     ⑦ フォントサイズ
 *     ⑧ その他(高さ)
 *
 *   [戻り値]
 *     $res
 *
 * -----------------------------------------------------------------------
 */
function makeQRPrt(
    $printData = array(),
    $code = null,
    $x = 10,
    $y = 10,
    $w = 50,
    $h = 5,
    $padding = 2,
    $other = array()
) {

    // カウント
    $cnt = count($printData) + 1;

    // QRコード作成
    $printData[$cnt]['2DBarcode']['code']    = $code;
    $printData[$cnt]['2DBarcode']['type']    = 'QRCODE,M';
    $printData[$cnt]['2DBarcode']['x']       = $x;
    $printData[$cnt]['2DBarcode']['y']       = $y;
    $printData[$cnt]['2DBarcode']['w']       = $w;
    $printData[$cnt]['2DBarcode']['h']       = $h;
    $printData[$cnt]['2DBarcode']['padding'] = $padding;
    $printData[$cnt]['2DBarcode']['align']   = isset($other['align']) ? $other['align'] : '';
    $printData[$cnt]['2DBarcode']['disort']  = isset($other['disort']) ? $other['disort'] : 'false';

    // 返却
    return $printData;
}


/* =======================================================================
 * 線
 * =======================================================================
 *
 *   [引数]
 *     ① 印刷データ
 *     ② テキスト
 *     ③ x座標 開始位置
 *     ④ y座標 開始位置
 *     ⑤ 幅
 *     ⑥ 高さ
 *     ⑦ フォントサイズ
 *     ⑧ その他(高さ)
 *
 *   [戻り値]
 *     $res
 *
 * -----------------------------------------------------------------------
 */
function makeLine($printData = array(), $x1 = 10, $y1 = 10, $x2 = 50, $y2 = 50, $style = array())
{

    // style指定
    if ($style) {
        $cnt = count($printData) + 1;
        $ary = array();
        $ary['width']      = !empty($style['width']) ? $style['width'] : 0.25;
        $ary['cap']        = !empty($style['cap']) ? $style['cap'] : 'square';
        $ary['join']       = !empty($style['join']) ? $style['join'] : 'miter';
        $ary['dash']       = !empty($style['dash']) ? $style['dash'] : 0;
        $ary['phase']      = !empty($style['phase']) ? $style['phase'] : 0;
        $ary['color']['r'] = !empty($style['r']) ? $style['r'] : 0;
        $ary['color']['g'] = !empty($style['g']) ? $style['g'] : 0;
        $ary['color']['b'] = !empty($style['b']) ? $style['b'] : 0;
        $printData[$cnt]['LineStyle'] = $ary;
    }

    // Line生成
    $cnt = count($printData) + 1;
    $printData[$cnt]['Line']['x1'] = $x1;
    $printData[$cnt]['Line']['y1'] = $y1;
    $printData[$cnt]['Line']['x2'] = $x2;
    $printData[$cnt]['Line']['y2'] = $y2;

    // 返却
    return $printData;
}
/* =======================================================================
 * 差し込み画像
 * =======================================================================
 *
 *   [引数]
 *     ① 印刷データ
 *     ② x座標 開始位置
 *     ③ y座標 開始位置
 *     ④ 幅
 *
 *   [戻り値]
 *     $res
 *
 * -----------------------------------------------------------------------
 */
function makeImage($printData = array(), $image = null, $x = 0, $y = 0, $w = 50, $h = 50, $type = "JPG")
{

    // 画像設定
    $cnt = count($printData) + 1;
    $ary = array();
    //    $ary['image'] = !empty($style['image']) ? $style['image'] : null;
    $ary['image'] = !empty($image) ? $image : null;
    $ary['x']     = !empty($x) ? $x : 0;
    $ary['y']     = !empty($y) ? $y : 0;
    $ary['w']     = !empty($w) ? $w : 50;
    $ary['h']     = !empty($h) ? $h : 50;
    $ary['type']  = !empty($type) ? $type : "JPEG";
    $printData[$cnt]['Image'] = $ary;

    // 返却
    return $printData;
}
