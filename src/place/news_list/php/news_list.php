<?php

//=====================================================================
// お知らせ一覧
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
    $mstStaff = array();

    // 初期値
    $def['place_name']  = null;
    $def['status']      = null;
    $def['create_day']  = null;
    $def['create_time'] = null;
    $def['update_day']  = null;
    $def['update_time'] = null;
    $def['update_name'] = null;

    // 対象テーブル(メイン)
    $table = 'mst_news';

    // 表示件数
    $line = 20;

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 検索配列
    $keyId = filter_input(INPUT_GET, 'id');

    // 拠点ID
    $placeId = filter_input(INPUT_GET, 'place');
    if (!$placeId) {
        $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
    }

    // 検索配列
    $tgtSearch = array();
    $tgtSearch = filter_input(INPUT_GET, 'srchAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $tgtSearch['all_place'] = !empty($tgtSearch['all_place'])
            ? $tgtSearch['all_place']
            : null;
    $tgtSearch['release_date'] = !empty($tgtSearch['release_date'])
            ? $tgtSearch['release_date']
            : null;
    $tgtSearch['detail'] = !empty($tgtSearch['detail'])
            ? $tgtSearch['detail']
            : null;
    $tgtSearch['target'] = !empty($tgtSearch['target'])
            ? $tgtSearch['target']
            : null;

    /*-- 更新用パラメータ ---------------------------------------*/

    $btnSearch = h(filter_input(INPUT_GET, 'btnSearch'));
    // $btnClear = h(filter_input(INPUT_GET, 'btnClear'));
    // 削除ボタン
    $btnDelete = h(filter_input(INPUT_GET, 'btnDelete'));

    /*-- その他パラメータ ---------------------------------------*/

    // ページャー
    $page = h(filter_input(INPUT_GET, 'page'));

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */
    // 削除配列
    if ($btnDelete && $keyId) {
        $temp = array();
        $temp['unique_id'] = $keyId;
        $temp['delete_flg'] = '1';

        // 更新配列
        $delData = $temp;
    }

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */
    // データ削除
    if ($btnDelete && $delData) {

        // mst_news を更新
        $res = upsert($loginUser, $table, $delData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        } else {
            $keyId = $res;
        }

        // ログテーブルに登録する
        setEntryLog($delData);

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

    // スタッフマスタリスト
    $mstStaff = getStaffList();

    $tgtCont = !empty($tgtSearch['target']) ? $tgtSearch['target'] : null;

    // 拠点一覧取得
    $tgtPlace = array();
    $where = array();
    $where['delete_flg'] = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_place', '*', $where, $orderBy);
    foreach ($temp as $val) {
        if ($val['name'] === "本社") {
            continue;
        }
        $plcId = $val['unique_id'];
        $mstPlace[$plcId] = $val;
    }

    // お知らせ年月設定
    $tgtSearch['release_date'] = isset($tgtSearch['release_date']) ? $tgtSearch['release_date'] : null;

    // お知らせマスタリスト
    $where = array();
    $datAry = array();
    $where['delete_flg'] = 0;
    // 自拠点のみ取得
    if ($tgtCont === "3") {
        $where['place_id'] = $placeId;
    }
    $orderBy = 'unique_id DESC';
    $temp = select('mst_news', '*', $where, $orderBy);
    foreach ($temp as $val) {

        // フリーワード検索
        if ($tgtSearch['detail']) {
            $word = $val['title'] . " " . $val['detail'];
            if (mb_strpos($word, $tgtSearch['detail']) === false) {
                continue;
            }
        }
        // 自拠点＋共通以外を除外
        if ($tgtCont === "2") {
            if (!empty($val['place_id']) && $val['place_id'] !== $placeId) {
                continue;
            }
        }

        $releaseDay = formatDateTime($val['create_date'], 'Y-m');

        if (!empty($tgtSearch['release_date']) && $tgtSearch['release_date'] !== $releaseDay) {
            continue;
        }

        $dat = array();
        $plcId = $val['place_id'] ? $val['place_id'] : null;
        $plcName = isset($mstPlace[$plcId]) ? $mstPlace[$plcId]['name'] : '全拠点';

        $dat['unique_id']   = $val['unique_id'];
        $dat['title']       = $val['title'];
        $dat['detail']      = $val['detail'];
        $dat['status']      = $val['status'] == "1" ? "公開" : "非公開" ;
        $dat['place_id']    = $plcId;
        $dat['place_name']  = $plcName;
        $dat['create_day']  = $val['status'] == "1" ? formatDateTime($val['create_date'], 'Y/m/d') : null;
        $dat['create_time'] = $val['status'] == "1" ? formatDateTime($val['create_date'], 'H:i:s') : null;
        $dat['create_name'] = $val['status'] == "1" ? getStaffName($val['create_user']) : null;
        $dat['update_day']  = formatDateTime($val['update_date'], 'Y/m/d');
        $dat['update_time'] = formatDateTime($val['update_date'], 'H:i:s');
        $dat['update_name'] = getStaffName($val['update_user']);

        $tgtData[$dat['unique_id']] = $dat;
    }

    /* -- データ変換 --------------------------------------------*/

    // ページャー
    $dispData = getPager($tgtData, $page, $line);
    $dispSearch = $tgtSearch;

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
