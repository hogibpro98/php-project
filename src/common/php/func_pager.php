<?php
/* =======================================================================
 * データ配列から隠れている部分を削除
 * =======================================================================
 *
 *   [引数]
 *     ① データ配列(2次元) array[seq][field]
 *     ② 表示ページ番号
 *     ③ ページあたりのデータ数
 *
 *   [戻り値]
 *     $res[seq][field] = '画面情報に出す配列（２次元）'
 *
 * -----------------------------------------------------------------------
 */
function getPager($itemList, $page, $line)
{

    /* -- 変数設定 --------------------------------------------*/
    $res   = array();
    $page  = !$page ? 1 : $page;
    $start = 1 + ($page * $line) - $line;
    $end   = $start + $line - 1;    // array_sliceの場合、未使用

    /* -- 最終行の再設定 --------------------------------------*/
    $cnt = count($itemList);
    if ($end > $cnt) {
        $end = $cnt;
    }

    /* -- 配列から範囲抽出 ------------------------------------*/
    return array_slice($itemList, $start - 1, $line);

    /* -- データ返却 ------------------------------------------*/
}
/* =======================================================================
 * 画面描画(直接echoする関数)
 * =======================================================================
 *
 *   [引数]
 *     ① データ配列(2次元) array[seq][field]
 *     ② 表示ページ番号
 *     ③ ページあたりのデータ数
 *     ④ url 呼び出しquery引き継ぎのため
 *
 *   [戻り値]
 *     なし（画面描画のみ）
 *
 * -----------------------------------------------------------------------
 */
function dispPager($itemList, $page, $line, $url)
{

    /* -- 変数設定 --------------------------------------------*/
    $page  = !$page ? 1 : $page;
    $pagerRange = 2;  // $page の前後表示ページ番号数
    $pagerUnit = $pagerRange * 2 + 1;  // $page の前後表示ページ番号数
    $cnt = count($itemList); // データの総数
    $totalPage = ceil($cnt / $line); // 最大ページ数
    $query = '';

    $tempURL   = explode('?', $url);
    $tempQuery = isset($tempURL[1]) ? $tempURL[1] : '';
    if (mb_strlen($tempQuery) > 0) {
        $queryAry  = explode('&', $tempQuery);
        foreach ($queryAry as $idx => $string) {
            if (mb_strpos($string, 'page=') === false) {
                $query .= '&';
                $query .= $string;
            }
        }
    }

    $page = min($page, $totalPage);
    $start = max($page - $pagerRange, 1);
    $end = min($page + $pagerRange, $totalPage);
    if ($pagerUnit < $totalPage) {
        if ($start > $totalPage - $pagerUnit + 1) {
            $start = $totalPage - $pagerUnit + 1;
        }
        if ($end < $pagerUnit) {
            $end = $pagerUnit;
        }
    } else {
        $start = 1;
        $end = $totalPage;
    }

    /* -- ページネーション・コードを出力 ------------------------*/
    echo "<div class=\"pager\">";
    echo "<ul>";
    $prevpg = max($page - 1, 1);
    echo "<li><a href=\"?page=1$query\"><i class=\"fas fa-angle-double-left\"></i></a></li>"; // 先頭ページ
    echo "<li><a href=\"?page=$prevpg$query\"><i class=\"fas fa-chevron-left\"></i></a></li>"; // 前のページ
    for ($i = $start; $i <= $end; $i++) {
        if ($page == $i) {
            echo "<li class=\"is-current\"><a href=\"?page=$i$query\">$i</a></li>";
        } else {
            echo "<li class=\"\"><a href=\"?page=$i$query\">$i</a></li>";
        }
    }
    $nextpg = min($page + 1, $totalPage);
    echo "<li><a href=\"?page=$nextpg$query\"><i class=\"fas fa-chevron-right\"></i></a></li>"; // 次のページ
    echo "<li><a href=\"?page=$totalPage$query\"><i class=\"fas fa-angle-double-right\"></i></a></li>"; // 最後ページ

    echo "</ul>";
    echo "</div>";
}
/* =======================================================================
 * 記事詳細の画面表示
 * =======================================================================
 *
 *   [引数]
 *     ① 対象配列(データ群)
 *     ② 対象ID(現在の記事ID)
 *
 *   [戻り値]
 *     なし（画面描画のみ）
 *
 * -----------------------------------------------------------------------
 */
function dispDetailPager($tgtData, $keyId, $scriptName)
{

    /* -- データ判定 --------------------------------------------*/

    // 初期化
    $check  = null;
    $target = null;
    $pre    = null;
    $next   = null;

    // 判定処理
    foreach ($tgtData as $val) {

        // 現時点の記事
        if ($val['unique_id'] === $keyId) {

            // 前記事ID
            if ($target && !$pre) {
                $pre = $target;
            }
            // 次記事判定用フラグ
            $check = true;
        }

        // 記事IDを退避
        $target = $val['unique_id'];

        // 次記事ID
        if ($check && $target !== $keyId && !$next) {
            $next = $target;
        }
    }

    /* -- ページネーション・コードを出力 ------------------------*/
    echo '<div class="pager pager-detail">';
    echo '    <ul>';
    if ($pre) {
        echo '        <li><a href="' . $scriptName . '?id=' . $pre . '"><i class="fas fa-chevron-left"></i>前へ</a></li>';
    }
    if ($next) {
        echo '        <li><a href="' . $scriptName . '?id=' . $next . '">次へ<i class="fas fa-chevron-right"></i></a></li>';
    }
    echo '    </ul>';
    echo '</div>';
}
