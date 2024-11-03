<?php

//=====================================================================
// ニュース一覧
//=====================================================================
try {
    /* ===================================================
     * 初期処理
     * ===================================================
     */

    /*--共通ファイル呼び出し-------------------------------------*/
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

    /*--変数定義-------------------------------------------------*/

    // 初期化
    $err      = array();
    $notice   = array();
    $dispData = array();
    $tgtData  = array();
    $upData   = array();
    $delData  = array();

    // 対象テーブル(メイン)
    $table = 'mst_news';

    // 表示件数
    $line = 20;

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 検索ボタン
    $btnSearch = h(filter_input(INPUT_GET, 'btnSearch'));
    // $btnClear = h(filter_input(INPUT_GET, 'btnClear'));

    // 検索配列
    $keyId = filter_input(INPUT_GET, 'id');

    // 検索配列
    $tgtSearch = array();
    $tgtSearch = filter_input(INPUT_GET, 'sAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $tgtSearch['all_place'] = !empty($tgtSearch['all_place'])
            ? $tgtSearch['all_place']
            : null;
    $tgtSearch['release_date'] = !empty($tgtSearch['release_date'])
            ? $tgtSearch['release_date']
            : null;
    $tgtSearch['freeword'] = !empty($tgtSearch['freeword'])
            ? $tgtSearch['freeword']
            : null;
    $tgtSearch['readed'] = !empty($tgtSearch['readed'])
            ? $tgtSearch['readed']
            : null;

    /*-- 更新用パラメータ ---------------------------------------*/

    // 更新ボタン
    $btnEntry = h(filter_input(INPUT_POST, 'btnRead'));
    // 更新配列
    $upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upAry = $upAry ? $upAry : array();

    // 拠点ID
    $placeId = filter_input(INPUT_GET, 'place');
    if (!$placeId) {
        $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
    }

    /*-- その他パラメータ ---------------------------------------*/

    // ページャー
    $page = h(filter_input(INPUT_GET, 'page'));

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 更新用配列作成 ----------------------------------------*/

    // 更新配列
    if ($btnEntry && $upAry) {
        if (empty($upAry['unique_id'])) {
            unset($upAry['unique_id']);
        }
        $upAry['read_flg']  = '1';
        $upAry['staff_id']  = $loginUser['unique_id'];

        // 更新配列
        $upData = $upAry;
    }

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    // 既読処理
    // データ更新
    if ($btnEntry && $upData) {

        // dat_news_status へ格納
        $res = upsert($loginUser, 'dat_news_status', $upData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setEntryLog($upData);

        // 画面遷移
        $nextPage = $server['scriptName'];
        header("Location:" . $nextPage);
        exit;
    }

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/

    // お知らせステータス取得
    $newsIds = array();
    $statusList = array();
    $where = array();
    $where['delete_flg'] = 0;
    $where['staff_id'] = $loginUser['unique_id'];//$tgtStaff;
    $orderBy = 'news_id DESC';
    $temp = select('dat_news_status', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $newsId = $val['news_id'];
        $statusList[$newsId] = $val;
    }

    // お知らせNEWS一覧、検索年月リスト作成
    // mst_place
    $newsIds = array();
    $newsList = array();
    $dispCalendar = array();
    $tgtNewsMonth = array();

    $where = array();
    $where['delete_flg'] = 0;
    //$where['target'] = 1;
    //$where['place_id'] = $placeId;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_news', '*', $where, $orderBy);
    foreach ($temp as $val) {
        // 公開中データのみ対象とする
        if ($val['status'] != 1) {
            continue;
        }

        // 拠点ID絞込
        if (!empty($val['place_id']) && ($val['place_id'] !== $placeId)) {
            continue;
        }

        $createDate = formatDateTime($val['create_date'], 'Y/m/d');
        $searchMonth = formatDateTime($val['create_date'], 'Y年m月');
        $val['create_day'] = formatDateTime($val['create_date'], 'Y/m/d');
        $val['create_time'] = formatDateTime($val['create_date'], 'H:i');
        $val['update_day'] = formatDateTime($val['update_date'], 'Y/m/d');
        $val['update_time'] = formatDateTime($val['update_date'], 'H:i');
        $val['news_date'] = $createDate;
        $val['search_month'] = $searchMonth;

        $newsId = $val['unique_id'];
        $readFlg = isset($statusList[$newsId]['read_flg'])
            ? $statusList[$newsId]['read_flg'] : 0;
        $newsStatusId = isset($statusList[$newsId]['unique_id'])
            ? $statusList[$newsId]['unique_id'] : null;
        $val['news_status_id'] = $newsStatusId;

        // 年月一覧は既読分も含めて表示する
        $tgtNewsMonth[$searchMonth] = $val;

        // フリーワード検索
        if (!empty($tgtSearch['freeword'])) {
            $word = $val['title'] . " " . $val['detail'];
            if (mb_strpos($word, $tgtSearch['freeword']) === false) {
                // フリーワード検索に一致しなかった物を除外する
                continue;
            }
        }

        // 既読も表示するにチェックが無い場合は除外
        if (empty($tgtSearch['readed'])) {
            if ($readFlg == 1) {
                continue;
            }
        }

        // 年月で絞込表示を行う
        if (!empty($tgtSearch['release_date']) && $searchMonth !== $tgtSearch['release_date']) {
            continue;
        }

        $newsIds[] = $newsId;
        $val['read_flg'] = $readFlg;
        //    $newsList[$createDate]['header']['create_day'] = $createDate;
        //    $newsList[$createDate]['header']['title'] = $val['title'];
        $newsList[$createDate][$newsId] = $val;
    }

    $tgtData = $newsList;
    // ページャー
    $dispData = getPager($tgtData, $page, $line);
    $dispSearch = $tgtSearch;

    //$dispCode = $tgtCode;
    $dispNewsMonth = $tgtNewsMonth;

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
