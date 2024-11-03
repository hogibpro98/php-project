<?php

//=====================================================================
// 拠点 一覧
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
    $table = 'mst_place';

    // 表示件数
    $line = 20;

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 検索配列
    //$search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
    //$search = $search ? $search : array();

    // フリーワード
    //$search['freeword']  = isset($search['freeword']) ? $search['freeword']  : NULL;


    /*-- 更新用パラメータ ---------------------------------------*/

    // 削除ボタン(※未実装)
    //$btnDel = h(filter_input(INPUT_POST, 'btnDel'));

    /*-- その他パラメータ ---------------------------------------*/

    // ページャー
    $page = h(filter_input(INPUT_GET, 'page'));

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 更新用配列作成 ----------------------------------------*/

    // 削除(※未実装)
    //if ($btnDel){
    //    $upData['unique_id']  = $btnDel;
    //    $upData['delete_flg'] = 1;
    //}

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    // 削除(※未実装)
    //if ($btnDel && $upData){
    //
    //    // データ更新
    //    $res = upsert($loginUser, $table, $upData);
    //
    //    // 画面遷移
    //    $nextPage = $server['scriptName'].'?page='.$page;
    //    header("Location:". $nextPage);
    //    exit();
    //}

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/

    // 事業所リスト(有効のみ)
    $where = array();
    $where['delete_flg'] = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_office', '*', $where, $orderBy);
    foreach ($temp as $val) {

        // 拠点ID、事業所ID、事業所分類、名称
        $plcId = $val['place_id'];
        $ofcId = $val['unique_id'];
        $type  = $val['type'];
        $name  = $val['name'];

        // 格納
        $ofcList[$plcId][$type]['id']   = $ofcId;
        $ofcList[$plcId][$type]['name'] = $val['name'];
    }

    // 拠点リスト取得
    $where = array();
    //if ($search['freeword']){
    //    $where['name LIKE '] = $search['freeword'];
    //}
    $orderBy = 'unique_id DESC';
    $temp = select($table, '*', $where, $orderBy);

    /* -- データ変換 --------------------------------------------*/

    // 拠点リスト
    foreach ($temp as $val) {

        // KEY
        $tgtId = $val['unique_id'];

        // 事業所 訪問看護
        $key = '訪問看護';
        if (isset($ofcList[$tgtId][$key])) {
            $tgtMst = $ofcList[$tgtId][$key];
            $val[$key]['id']   = $tgtMst['id'];
            $val[$key]['name'] = $tgtMst['name'];
        }
        // 事業所 看多機
        $key = '看多機';
        if (isset($ofcList[$tgtId][$key])) {
            $tgtMst = $ofcList[$tgtId][$key];
            $val[$key]['id']   = $tgtMst['id'];
            $val[$key]['name'] = $tgtMst['name'];
        }

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
