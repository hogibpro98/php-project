<?php

//=====================================================================
// 保険外マスタ詳細
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

    // 対象テーブル(メイン)
    $table = 'mst_staff';

    // 初期値
    $dispData = initTable($table);
    $dispData['create_day']  = null;
    $dispData['create_time'] = null;
    $dispData['create_name'] = null;
    $dispData['update_day']  = null;
    $dispData['update_time'] = null;
    $dispData['update_name'] = null;

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    /*-- 更新用パラメータ ---------------------------------------*/

    /*-- その他パラメータ ---------------------------------------*/

    // ページャー
    // $page = h(filter_input(INPUT_GET, 'page'));

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
    // mst_staff
    $temp = array();
    $where = array();
    $where['unique_id'] = $loginUser['unique_id'];
    $temp = select($table, '*', $where);
    if (!isset($temp[0])) {
        $err[] = '対象データの取得に失敗しました';
        throw new Exception();
    } else {
        $tgtData = $temp[0];
    }

    /* -- データ変換 --------------------------------------------*/
    if ($tgtData) {
        // 初回登録
        $temp = $tgtData['create_date'];
        $tgtData['create_day']  = formatDateTime($temp, 'Y/m/d');
        $tgtData['create_time'] = formatDateTime($temp, 'H:i');
        $temp = $tgtData['create_user'];
        $tgtData['create_name'] = isset($mstStaff[$temp]) ? $mstStaff[$temp] : null;
        // 更新情報
        $temp = $tgtData['update_date'];
        $tgtData['update_day']  = formatDateTime($temp, 'Y/m/d');
        $tgtData['update_time'] = formatDateTime($temp, 'H:i');
        $temp = $tgtData['update_user'];
        $tgtData['update_name'] = isset($mstStaff[$temp]) ? $mstStaff[$temp] : null;
    }

    $dispData = $tgtData;

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
