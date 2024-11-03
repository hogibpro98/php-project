<?php

//=====================================================================
// お知らせ詳細
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
    $tgtPlace = array();
    $upAry    = array();
    $upData   = array();

    // 対象テーブル(メイン)
    $table = 'mst_news';

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

    // 検索配列
    $keyId = filter_input(INPUT_GET, 'id');

    /*-- 更新用パラメータ ---------------------------------------*/

    // 更新ボタン
    $btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

    // 更新配列
    $upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upAry = $upAry ? $upAry : array();

    // 拠点ID
    $placeId = filter_input(INPUT_GET, 'place');
    if (!$placeId) {
        $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
    }

    /*-- その他パラメータ ---------------------------------------*/

    // 一覧に戻る
    $btnRtn = h(filter_input(INPUT_POST, 'btnReturn'));
    if ($btnRtn) {
        $nextPage = '/place/news_list/';
        header("Location:" . $nextPage);
        exit;
    }

    // ページャー
    // $page = h(filter_input(INPUT_GET, 'page'));

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */
    // 入力チェック
    if ($btnEntry && $upAry) {
        if (empty($upAry['title'])) {
            $notice[] = 'タイトルの指定がありません';
        }
        if (empty($upAry['detail'])) {
            $notice[] = '内容の指定がありません';
        }
        if (!empty($notice)) {
            $btnEntry = null;
        }
    }

    $upAry['target'] = isset($upAry['target']) ? "1" : "0";

    // スタッフマスタリスト(フッタ用)
    $mstStaff = array();
    $temp = select('mst_staff', 'unique_id, last_name, first_name');
    foreach ($temp as $val) {
        $tempId    = $val['unique_id'];
        $tempName  = $val['last_name'] . $val['first_name'];
        $mstStaff[$tempId] = $tempName;
    }

    // mst_place
    $tgtPlace = array();
    $where = array();
    $where['delete_flg'] = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_place', '*', $where, $orderBy);
    // 存在チェック
    if (!isset($temp[0])) {
        $err[] = '拠点データの取得に失敗しました';
        throw new Exception();
    } else {
        $tgtPlace = $temp;
    }

    // mst_place
    $tgtPlace = array();
    $where = array();
    $where['delete_flg'] = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_place', '*', $where, $orderBy);
    foreach ($temp as $val) {
        //    if($val['name'] === "本社"){
        //        continue;
        //    }
        $plcId = $val['unique_id'];
        $plcId = $val['unique_id'];
        $mstPlace[$plcId] = $val;
    }

    /* -- 更新用配列作成 ----------------------------------------*/

    // 更新配列
    if ($btnEntry && $upAry) {
        // 対象KEYがあればupdate
        $dat = array();
        if ($keyId) {
            $dat['unique_id'] = $keyId;
        }
        $dat['title']    = $upAry['title'];
        $dat['detail']   = $upAry['detail'];
        $dat['status']   = $upAry['status'];
        $dat['target']   = 0;
        //    $dat['place_id'] = $upAry['target'] == "1" ? $upAry['place_id'] : NULL;
        $dat['place_id'] = !empty($upAry['place_id']) ? $upAry['place_id'] : null;
        $upData = $dat;
    }

    // データ更新
    if ($btnEntry && $upData) {
        // mst_news へ格納
        $res = upsert($loginUser, $table, $upData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        } else {
            $keyId = $res;
        }

        // ログテーブルに登録する
        setEntryLog($upData);

        // 画面遷移
        $nextPage = $server['scriptName'] . '?id=' . $keyId;
        header("Location:" . $nextPage);
        exit;
    }

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/
    // mst_news
    $temp = array();
    if ($keyId) {
        $where = array();
        $where['unique_id'] = $keyId;
        $temp = select($table, '*', $where);
        // 存在チェック
        if (!isset($temp[0])) {
            $err[] = '対象データの取得に失敗しました';
            throw new Exception();
        } else {
            $tgtData = $temp[0];
        }
    }

    /* -- データ変換 --------------------------------------------*/
    if ($tgtData) {
        // 公開日付
        $temp = $tgtData['create_date'];
        $tgtData['create_day']  = $tgtData['status'] == "1" ? formatDateTime($temp, 'Y/m/d') : null;
        $tgtData['create_time'] = $tgtData['status'] == "1" ? formatDateTime($temp, 'H:i') : null ;
        $temp = $tgtData['status'] == "1" ? $tgtData['create_user'] : null;
        $tgtData['create_name'] = isset($mstStaff[$temp]) ? $mstStaff[$temp] : null;
        // 更新情報
        $temp = $tgtData['update_date'];
        $tgtData['update_day']  = formatDateTime($temp, 'Y/m/d');
        $tgtData['update_time'] = formatDateTime($temp, 'H:i');
        $temp = $tgtData['update_user'];
        $tgtData['update_name'] = isset($mstStaff[$temp]) ? $mstStaff[$temp] : null;
        //    $tgtData['place_id'] = $placeId;
    }

    $dispData = array_merge($dispData, $tgtData);

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
