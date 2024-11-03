<?php

//=====================================================================
// 拠点 編集
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
    $notice   = array();

    // 対象テーブル(メイン)
    $table = 'mst_place';

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

    // KEY
    $keyId = filter_input(INPUT_GET, 'place_id');


    /*-- 更新用パラメータ ---------------------------------------*/

    // 更新ボタン
    $btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

    // 更新配列
    $upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upAry = $upAry ? $upAry : array();


    /*-- その他パラメータ ---------------------------------------*/


    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 更新用配列作成 ----------------------------------------*/

    // 更新配列
    if ($btnEntry && $upAry) {

        // 対象KEY
        if ($keyId) {
            $upAry['unique_id'] = $keyId;
        }

        // 更新配列
        $upData = $upAry;

    }

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    // 入力チェック
    if ($btnEntry && $upData) {

        // 名称
        if (empty($upData['name'])) {
            $notice[] = '名称の指定がありません';
        }
        // 都道府県
        if (empty($upData['prefecture'])) {
            $notice[] = '都道府県の指定がありません';
        }
        // 市区町村
        if (empty($upData['area'])) {
            $notice[] = '市区町村の指定がありません';
        }
        // セッションへ格納
        if ($notice) {
            $_SESSION['notice']['error'] = $notice;
            $btnEntry = null;
        }
    }

    // 更新処理
    if ($btnEntry && $upData) {

        // DBへ格納
        $res = upsert($loginUser, $table, $upData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // 画面遷移
        $nextPage = $server['scriptName'] . '?place_id=' . $res;
        header("Location:" . $nextPage);
        exit;
    }

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/

    // 対象テーブル
    $where = array();
    if ($keyId) {
        $where['unique_id'] = $keyId;
        $temp = select($table, '*', $where);
    }

    // 存在チェック
    if ($keyId) {
        if (!isset($temp[0])) {
            $err[] = '対象データの取得に失敗しました';
            throw new Exception();
        } else {
            $tgtData = $temp[0];
        }
    }

    // スタッフマスタ
    $temp = select('mst_staff', 'unique_id,last_name,first_name');
    foreach ($temp as $val) {
        $tgtId    = $val['unique_id'];
        $tgtName  = $val['last_name'] . $val['first_name'];
        $staffMst[$tgtId] = $tgtName;
    }

    // 住所情報
    $where = array();
    $where['delete_flg'] = 0;
    $target = 'prefecture_name,city_name';
    $temp = select('mst_area', $target, $where);
    foreach ($temp as $val) {
        $pref = $val['prefecture_name'];
        $city = $val['city_name'];
        $areaMst[$pref][$city] = true;
    }

    /* -- データ変換 --------------------------------------------*/
    if ($tgtData) {

        // 初回登録
        $tgtDate = $tgtData['create_date'];
        $tgtData['create_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['create_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['create_user'];
        $tgtData['create_name'] = isset($staffMst[$tgtUser])
                ? $staffMst[$tgtUser]
                : null;

        // 更新情報
        $tgtDate = $tgtData['update_date'];
        $tgtData['update_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['update_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['update_user'];
        $tgtData['update_name'] = isset($staffMst[$tgtUser])
                ? $staffMst[$tgtUser]
                : null;
    }

    // 格納
    if ($tgtData) {
        $dispData = $tgtData;
    }

    /* -- その他 --------------------------------------------*/



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
