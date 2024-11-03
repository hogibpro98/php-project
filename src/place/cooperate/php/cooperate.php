<?php

//=====================================================================
// 連携データ作成
//=====================================================================
try {
    /* ===================================================
    * 初期処理
    * ===================================================
    */

    /*--共通ファイル呼び出し-------------------------------------*/
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_text.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/place/cooperate/function/func_account.php');

    /*--変数定義-------------------------------------------------*/

    // 初期化
    $err      = array();
    $_SESSION['notice']['error'] = array();
    $tgtUser  = null;
    $dispData = array();
    $makeList = array();
    $dispList = array();
    $errData  = array();
    $newData  = array();

    // 年選択肢
    for ($i = 2022; $i <= THISYEAR; $i++) {
        $slctYear[] = $i;
    }
    // 月選択肢
    $slctMonth[] = '01';
    $slctMonth[] = '02';
    $slctMonth[] = '03';
    $slctMonth[] = '04';
    $slctMonth[] = '05';
    $slctMonth[] = '06';
    $slctMonth[] = '07';
    $slctMonth[] = '08';
    $slctMonth[] = '09';
    $slctMonth[] = '10';
    $slctMonth[] = '11';
    $slctMonth[] = '12';

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 拠点ID
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;

    // 検索ボタン
    $btnSearch = h(filter_input(INPUT_POST, 'btnSearch'));

    // 検索配列
    $search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $search['other_id']  = !empty($search['other_id']) ? $search['other_id'] : null;
    $search['user_id']   = !empty($search['user_id']) ? $search['user_id'] : null;
    $search['user_name'] = !empty($search['user_name']) ? $search['user_name'] : null;
    $search['type']      = !empty($search['type']) ? $search['type'] : null;
    $search['year']      = !empty($search['year']) ? $search['year'] : formatDateTime(TODAY, 'Y');
    $search['month']     = !empty($search['month']) ? $search['month'] : formatDateTime(TODAY, 'm');
    $search['target']    = $search['year'] . '-' . $search['month'];

    /*-- 更新用パラメータ ---------------------------------------*/

    // 更新配列
    $upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    // 連携データ出力ボタン
    $btnMake = h(filter_input(INPUT_POST, 'btnMake'));

    // エラーデータ出力ボタン
    $btnError = h(filter_input(INPUT_POST, 'btnError'));

    // 出力対象
    $output = filter_input(INPUT_POST, 'upRowChk', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upRow = $output;

    // 自己負担対象
    $charge = h(filter_input(INPUT_POST, 'upCharge'));
    $charge = isset($charge) ? $charge : array();
    $users = array();

    if ($users) {
        foreach ($charge as $idx =>  $userId) {
            $users[] = $userId;
        }
    }
    $lastDay = (new DateTimeImmutable())->modify('last day of ' . $search['target'])->format('Y-m-d');

    $upDate = array();

    // 対象の実績データを取得する
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $users;
    $where['use_day >='] = $search['target'] . '-01';
    $where['use_day <='] = $lastDay;
    $temp = select('dat_user_record', '*', $where);
    foreach ($temp as $val) {
        $dat = array();
        $dat['unique_id'] = $val['unique_id'];
        $dat['charge']    = '自己負担';
        $upData[] = $dat;
    }

    // 実績データを更新する
    if ($btnMake && $upData) {
        $res = multiUpsert($loginUser, 'dat_user_record', $upData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }

    /*-- その他パラメータ ---------------------------------------*/
    //exit;

    /* ===================================================
     * マスタ取得
     * ===================================================
     */


    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 更新用配列作成 ----------------------------------------*/


    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    // データ検索
    if ($btnSearch) {
        //if ($btnSearch || $btnMake) {
        if ($search['user_id']) {
            $tgtUser = $search['user_id'];
        }

        unset($_SESSION['err_file_path']);
        unset($_SESSION['err_file_name']);
        unset($_SESSION['err_file_url']);
        unset($_SESSION['file_path']);
        unset($_SESSION['file_name']);
        unset($_SESSION['file_url']);

        $res = getAccount($placeId, $search['target'], $search['type'], $tgtUser);
        $dispData = isset($res['data']) ? $res['data'] : array();
        $errData  = isset($res['error']) ? $res['error'] : array();

        foreach ($dispData as $idx => $val) {
            // 出力対象で絞り込む
            if (isset($output[$idx])) {
                $makeList[] = $val;
                $dispData[$idx]['output'] = true;
            } else {
                //$dispData[$idx]['output'] = NULL;
                $dispData[$idx]['output'] = true;
            }
        }

        if ($makeList) {
            $res = makeDispCsv('account', $makeList, false);
        } else {
            $res = makeDispCsv('account', $dispData, false);
        }

        // エラーデータを出力する
        if (!empty($errData)) {
            /* -- CSVエラー出力処理 ------------------------------------------ */
            $type = "csvoutput";

            // ディレクトリ生成
            $dir = SV_ROOT . '/csv/' . $type . '/';
            $url = '/csv/' . $type . '/';

            if (!is_dir($dir)) {
                umask(0);
                if (!mkdir($dir, 2777)) {
                    $err[] = 'CSV出力フォルダ作成に失敗しました。';
                    throw new Exception();
                }
            }

            // ファイル名称、参照パス
            $errFilename = $type . '_' . date('YmdHis') . '_error.csv';
            $errFilepath = $dir . $errFilename;
            $errFileUrl =  $url . $errFilename;

            $output = array();
            foreach ($errData as $val) {

                $output[] = $val . "\r\n";
            }

            // CSV出力処理
            writeText($errFilepath, $output);

            $_SESSION['err_file_url'] = $errFileUrl;
            $_SESSION['err_file_path'] = $errFilepath;
            $_SESSION['err_file_name'] = $errFilename;

        }
    }

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */
    $csvFilePath = isset($_SESSION['file_path']) ? $_SESSION['file_path'] : null;
    $csvFileName = isset($_SESSION['file_name']) ? $_SESSION['file_name'] : null;
    $csvFileUrl = isset($_SESSION['file_url']) ? $_SESSION['file_url'] : null;
    $errFilePath = isset($_SESSION['err_file_path']) ? $_SESSION['err_file_path'] : null;
    $errFileName = isset($_SESSION['err_file_name']) ? $_SESSION['err_file_name'] : null;
    $errFileUrl = isset($_SESSION['err_file_url']) ? $_SESSION['err_file_url'] : null;

    /* -- データ取得 --------------------------------------------*/

    /* ===================================================
     * 例外処理
     * ===================================================
     */
} catch (Exception $e) {
    if ($execEnv === 'pro' || $execEnv === 'stg') {
        $_SESSION['notice']['error'] = !empty($err) ? $err : array();
        header("Location:" . ERROR_PAGE);
        exit;
    } else {
        debug($e);
        exit;
    }
}
