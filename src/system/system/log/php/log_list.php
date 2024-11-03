<?php

//=====================================================================
// ログ管理
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
    $_SESSION['notice']['error']   = array();
    $dispData = array();
    $tgtData  = array();
    $upData   = array();

    // 対象テーブル(メイン)
    $table = 'log_entry';

    // 表示件数
    $line = 20;

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 検索配列
    $search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $search = $search ? $search : array();

    $search['start_day'] = !empty($search['start_day']) ? $search['start_day'] : null;
    $search['end_day']   = !empty($search['end_day']) ? $search['end_day'] : null;
    $search['type']      = !empty($search['type']) ? $search['type'] : null;

    /*-- 更新用パラメータ ---------------------------------------*/


    /*-- その他パラメータ ---------------------------------------*/

    // ページャー
    $page = h(filter_input(INPUT_GET, 'page'));

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 更新用配列作成 ----------------------------------------*/


    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */


    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/

    // スタッフリスト(有効のみ)
    $where = array();
    $where['delete_flg'] = 0;
    $orderBy = 'unique_id ASC';
    $temp = select('mst_staff', 'unique_id,name', $where, $orderBy);
    foreach ($temp as $val) {
        $stfList[$val['unique_id']] = $val['name'];
    }

    // ログ取得
    $where = array();
    $where['delete_flg'] = 0;
    if ($search['start_day']) {
        $where['create_date >='] = $search['start_day'] . ' 00:00:00';
    }
    if ($search['end_day']) {
        $where['create_date <='] = $search['end_day'] . ' 23:59:59';
    }
    if ($search['type']) {
        $where['type'] = $search['type'];
    }
    $orderBy = 'unique_id DESC';
    $temp = select($table, '*', $where, $orderBy);


    /* -- データ変換 --------------------------------------------*/

    // 拠点リスト
    foreach ($temp as $val) {

        // KEY
        $tgtId = $val['unique_id'];




        // 格納
        $tgtData[$tgtId] = $val;
    }

    /* -- その他 --------------------------------------------*/

    // ページャー
    $dispData = getPager($tgtData, $page, $line);


    /* ===================================================
     * 例外処理
     * ===================================================
     */
} catch (Exception $e) {
    if ($execEnv === 'pro' || $execEnv === 'stg') {
        $_SESSION['err'] = !empty($err) ? $err : array();
        header("Location:" . ERROR_PAGE);
        exit;
    } else {
        debug($e);
        exit;
    }
}
