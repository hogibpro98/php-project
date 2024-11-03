<?php

//=====================================================================
// 保険外マスタ一覧
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
    $delData  = array();

    // 対象テーブル(メイン)
    $table = 'mst_uninsure';

    // 表示件数
    $line = 20;

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 検索配列
    $keyId = filter_input(INPUT_GET, 'id');

    // 検索配列
    $tgtSearch = array();
    $tgtSearch = filter_input(INPUT_GET, 'sAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    /*-- 更新用パラメータ ---------------------------------------*/

    $btnSearch = h(filter_input(INPUT_GET, 'btnSearch'));
    $btnClear = h(filter_input(INPUT_GET, 'btnClear'));
    // 削除ボタン
    $btnDelete = h(filter_input(INPUT_GET, 'btnDelete'));

    /*-- その他パラメータ ---------------------------------------*/

    // ページャー
    $page = h(filter_input(INPUT_GET, 'page'));

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */
    if ($btnClear && $tgtSearch) {
        $tgtSearch['type'] = '';
        $tgtSearch['code'] = '';
        $tgtSearch['name'] = '';
        $tgtSearch['range'] = '';
    }

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
    // コードマスタ(保険外マスタ向け)
    $tgtCode = array();
    $where = array();
    $where['group_div'] = '保険外マスタ';
    $where['delete_flg'] = 0;
    $orderBy = 'unique_id ASC';
    $temp = select('mst_code', 'unique_id, type, name', $where, $orderBy);
    foreach ($temp as $val) {
        $tempType  = $val['type'];
        $tempName  = $val['name'];
        $tgtCode[$tempType][] = $tempName;
    }

    // スタッフマスタリスト(フッタ用)
    $mstStaff = array();
    $temp = select('mst_staff', 'unique_id, last_name, first_name');
    foreach ($temp as $val) {
        $tempId    = $val['unique_id'];
        $tempName  = $val['last_name'] . $val['first_name'];
        $mstStaff[$tempId] = $tempName;
    }

    // 保険外マスタリスト
    $tgtData = array();
    $sql  = "SELECT * ";
    $sql .= "  FROM mst_uninsure";
    $sql .= " WHERE delete_flg = 0";
    if (isset($tgtSearch['code']) && $tgtSearch['code'] != '') {
        $sql .= "   AND CONCAT(code1, code2) = '" . $tgtSearch['code'] . "'";
    }
    if (isset($tgtSearch['type']) && $tgtSearch['type'] != '') {
        $sql .= "   AND type ='" . $tgtSearch['type'] . "'";
    }
    if (isset($tgtSearch['name']) && $tgtSearch['name'] != '') {
        $sql .= "   AND name LIKE '%" . $tgtSearch['name'] . "%'";
    }
    if (isset($tgtSearch['range']) && $tgtSearch['range'] == '') {
        $sql .= "   AND start_day <='" . date('Y-m-d') . "'";
        $sql .= "   AND end_day >='" . date('Y-m-d') . "'";
    }
    $sql .= " ORDER BY unique_id DESC";
    $temp = customSQL($sql);

    // $tgtData = array();
    // $where = array();
    // // 検索条件設定
    // if ($tgtSearch['code'] != '') {
    //     // SQL検索条件での指定ではなく、取得後の結果にて対応
    //     // $where['CONCAT(code1, code2)'] = $tgtSearch['code'];
    // }
    // if ($tgtSearch['type'] != '') {
    //     $where['type'] = $tgtSearch['type'];
    // }
    // if ($tgtSearch['name'] != '') {
    //     $where['name LIKE'] = $tgtSearch['name'];
    // }
    // if ($tgtSearch['range'] == '') {
    //     $where['start_day <='] = date('Y-m-d');
    //     $where['end_day >='] = date('Y-m-d');
    // }
    // $where['delete_flg'] = 0;
    // $orderBy = 'unique_id DESC';
    // $temp = select($table, '*', $where, $orderBy);

    /* -- データ変換 --------------------------------------------*/
    foreach ($temp as $val) {
        // KEY
        $tgtId = $val['unique_id'];

        // // 検索条件に合致しないレコードを除外
        // if ($tgtSearch['code'] != '') {
        //     if ($val['code1'].$val['code2'] != $tgtSearch['code']) { continue; }
        // }

        // 基本サービスコードとして使用
        $val['standard_flg'] = $val['standard_flg'] != '' ? '○' : '';

        // 税率NULLと0を0にする
        $val['rate'] = !empty($val['rate']) ? $val['rate'] : 0;

        // 日付書式
        $val['start_day'] = $val['start_day'] != '' ? formatDateTime($val['start_day'], 'Y/m/d') : '';
        $val['end_day'] = $val['end_day'] != '' ? formatDateTime($val['end_day'], 'Y/m/d') : '';

        // 初回登録
        $temp = $val['create_date'];
        $val['create_day']  = formatDateTime($temp, 'Y/m/d');
        $val['create_time'] = formatDateTime($temp, 'H:i');
        $temp = $val['create_user'];
        $val['create_name'] = isset($mstStaff[$temp]) ? $mstStaff[$temp] : null;
        // 更新情報
        $temp = $val['update_date'];
        $val['update_day']  = formatDateTime($temp, 'Y/m/d');
        $val['update_time'] = formatDateTime($temp, 'H:i');
        $temp = $val['update_user'];
        $val['update_name'] = isset($mstStaff[$temp]) ? $mstStaff[$temp] : null;

        $tgtData[$tgtId] = $val;
    }

    // ページャー
    $dispData = getPager($tgtData, $page, $line);
    $dispSearch = $tgtSearch;
    $dispCode = $tgtCode;

    // debug($dispData);
    // debug($dispCode);
    // exit();

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
