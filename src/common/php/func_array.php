<?php
/* =======================================================================
 * 配列操作関数群
 * =======================================================================


/* =======================================================================
 * 要素判定型マージ関数
 * =======================================================================
 *
 * 　要素に対して重複判定を行った配列のマージ関数
 *
 * -----------------------------------------------------------------------
 */
function mergeArrayIndex($ary1 = array(), $ary2 = array())
{
    $ary3 = $ary1;
    foreach ($ary2 as $key2 => $val2) {
        if (!isset($ary3[$key2])) {
            $ary3[$key2] = $val2;
        }
    }
    return $ary3;
}

/* =======================================================================
 * データ判定型マージ関数
 * =======================================================================
 *
 * 　データに対して重複判定を行った配列のマージ関数
 *   マージされる配列は連想配列ではなくなる点に注意
 *
 * -----------------------------------------------------------------------
 */
function mergeArrayData($ary1 = array(), $ary2 = array())
{
    $ary3 = array();
    $ary4 = array();
    foreach ($ary1 as $key1 => $val1) {
        if (!isset($ary3[$val1])) {
            $ary3[$val1] = 'dummy';
            $ary4[] = $val1;
        }
    }
    foreach ($ary2 as $key2 => $val2) {
        if (!isset($ary3[$val2])) {
            $ary3[$val2] = 'dummy';
            $ary4[] = $val2;
        }
    }
    return $ary4;
}

/* =======================================================================
 * 連想配列部分のみ抽出関数
 * =======================================================================
 *
 * 　キーが文字列型（連想配列）の要素だけを取り出す
 *
 * -----------------------------------------------------------------------
 */

function getAssociativeArray($ary)
{
    $res = array();
    foreach ($ary as $key => $val) {
        if (is_string($key)) {
            $res[$key] = $val;
        }
    }
    return $res;
}

/* =======================================================================
 * 2次元配列次元入れ替え関数
 * =======================================================================
 *
 * 　array['a']['b'] => array['b']['a']に変換
 *
 * -----------------------------------------------------------------------
 */

function reverseDimension($ary1)
{
    $res = array();
    foreach ($ary1 as $key1 => $ary2) {
        foreach ($ary1 as $key2 => $val) {
            $res[$key2][$key1] = $val;
        }
    }
    return $res;
}

/* =======================================================================
 * 重複データ除外関数 ※標準関数array_uniqueの代わり
 * =======================================================================
 *
 *   データが重複している要素(型判定込み)を取り除く
 *   （先頭に近い要素のキーを残す）
 *   ※多次元配列使用不可
 * -----------------------------------------------------------------------
 */

function arrayUnique($ary)
{
    $res = array();
    foreach ($ary as $key => $val1) {
        foreach ($res as $val2) {
            if ($val1 === $val2) {
                continue 2;
            }
        }
        $res[$key] = $val1;
    }
    return $res;
}

/* =======================================================================
 * 多次元配列の深さ測定関数
 * =======================================================================
 *
 *   多次元配列の次元の深さ（階層）を調べる
 *
 * -----------------------------------------------------------------------
 */
function depthArray($ary, $blank = false, $depth = 0)
{
    if (!is_array($ary)) {
        return $depth;
    } else {
        $depth++;
        $temp = ($blank) ? array($depth) : array(0);
        foreach ($ary as $value) {
            $temp[] = depthArray($value, $blank, $depth);
        }
        return max($temp);
    }
}

/* =======================================================================
 * 配列内容比較関数
 * =======================================================================
 *
 *   ※ 登録したいデータが既存のデータから変更されているか否かを判定する
 *
 *      第一引数に新たに登録したいデータ(配列)を指定
 *      第二引数に既存データ(配列)を指定
 *
 *      変更がある場合はtrue、変更がない場合はfalseを返す
 *      (第二引数が、第一引数の要素全てと同じキー・同じ値を持つ場合変更無しとみなす)
 *
 * -----------------------------------------------------------------------
 */

function compareArray($ary1, $ary2)
{
    foreach ($ary1 as $key => $val) {
        if (array_key_exists($key, $ary2)) {
            if ($val !== $ary2[$key]) {
                return true;
            }
        } else {
            return true;
        }
    }
    return false;
}

/* =======================================================================
 * 2次元配列検索関数
 * =======================================================================
 * dimensionArraySearch(①、②、③)
 *
 * パラメータ
 *  ① 検索対象配列 - 2次元配列、2元目のキーに対して検索を実行する
 *
 *  ② 検索条件配列 - 検索したいキーと、値をペアにした配列
 *                    複数キーを指定した場合、ANDで検索する
 *                    1キーに対して、値を配列として複数指定した場合ORで検索する
 *
 * 戻り値
 *  検索にヒットした配列を順に格納した配列(2次元)
 *
 * -----------------------------------------------------------------------
 */

function deepArraySearch($haystack, $needle)
{
    // 戻り値初期化
    $res = array();

    // 検索対象ループ
    foreach ($haystack as $hayKey => $hayAry) {
        // 検索条件ループ
        foreach ($needle as $nedKey => $nedVal) {
            // キー存在チェック
            if (!array_key_exists($nedKey, $hayAry)) {
                // 戻り値から除外
                continue 2;
            }
            // 検索条件一致チェック
            if (is_array($nedVal)) {
                foreach ($nedVal as $val) {
                    if ($hayAry[$nedKey] === $val) {
                        // 他キー検索へ進む
                        continue 2;
                    }
                }
                // 戻り値から除外
                continue 2;
            } else {
                if ($hayAry[$nedKey] !== $nedVal) {
                    // 戻り値から除外
                    continue 2;
                }
            }
        }
        $res[$hayKey] = $hayAry;
    }
    return $res;
}

/* =======================================================================
 * 配列の最初のキーを取得
 * =======================================================================
 */
function getFirstKey($array)
{
    reset($array);
    return key($array);
}
/* =======================================================================
 * 配列の最初の値を取得
 * =======================================================================
 */
function getFirstValue($array)
{
    return reset($array);
}
/* =======================================================================
 * 配列の最後のキーを取得
 * =======================================================================
 */
function getLastKey($array)
{
    end($array);
    return key($array);
}
/* =======================================================================
 * 配列の最後の値を取得
 * =======================================================================
 */
function getLastValue($array)
{
    return end($array);
}

// =======================================================================
// 平均値取得関数(要素の平均)
// =======================================================================

function getAverage($ary, $type = 'float')
{
    $sum = array_sum($ary);
    $cnt = count($ary);
    // ゼロ除算回避
    if ($sum === 0) {
        return 0;
    }
    switch ($type) {
        case 'int':
            return (int)($sum / $cnt);
            break;
        default:
            return (float)($sum / $cnt);
            break;
    }
}

// =======================================================================
// 標準偏差取得関数(要素の標準偏差)
// ①値保持配列 ②基準値 ③型
// =======================================================================

function getDeviation($ary, $avg = 0, $type = 'float')
{
    $variance = 0;
    foreach ($ary as $val) {
        $variance += pow($val - $avg, 2);
    }
    $variance = $variance / count($ary);
    switch ($type) {
        case 'int':
            return (int)sqrt($variance);
            break;
        default:
            return (float)sqrt($variance);
            break;
    }
}
