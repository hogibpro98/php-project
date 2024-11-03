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
    $upData   = array();

    // 対象テーブル(メイン)
    $table = 'mst_uninsure';

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

    /*-- その他パラメータ ---------------------------------------*/

    // ページャー
    // $page = h(filter_input(INPUT_GET, 'page'));

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */
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

    /* -- 更新用配列作成 ----------------------------------------*/

    // 更新配列
    if ($btnEntry && $upAry) {
        // 対象KEYがあればupdate
        if ($keyId) {
            $upAry['unique_id'] = $keyId;
        }
        // チェックボックス解除対応(基本サービス区分)
        if (!isset($upAry['standard_flg'])) {
            $upAry['standard_flg'] = 0;
        }
        $upAry['start_day'] = empty($upAry['start_day']) ? null : $upAry['start_day'];
        $upAry['end_day'] = empty($upAry['end_day']) ? null : $upAry['end_day'];
        $upAry['price'] = empty($upAry['price']) ? null : $upAry['price'];
        $upAry['rate'] = empty($upAry['rate']) ? 0 : $upAry['rate'];

        // 更新配列
        $upData = $upAry;
    }

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    // 事業所マスタリスト
    $mstOffice = array();
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_office', 'unique_id, name', $where);
    foreach ($temp as $val) {
        $tempId    = $val['unique_id'];
        $tempName  = $val['name'];
        $mstOffice[$tempName] = $tempId;
    }

    // データ更新
    if ($btnEntry && $upData) {
        // if (count($upData)>0) {
        //     debug($upData);
        //     debug($loginUser);
        //     exit();
        // }

        // mst_uninsure へ格納
        $upData = $upData;
        // unset($upDataStaff['join']);
        $res = upsert($loginUser, $table, $upData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        } else {
            $keyId = $res;
        }

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
    // 事業所選択リスト作成
    $tgtOfficeList = array();
    $sql  = "SELECT *";
    $sql .= "  FROM mst_office AS moa";
    $sql .= " INNER JOIN (";
    $sql .= "  SELECT";
    $sql .= "    place_id,";
    $sql .= "    TYPE,";
    $sql .= "    MAX(record_no) as max_rec";
    $sql .= "  FROM mst_office";
    $sql .= "  WHERE delete_flg = 0";
    $sql .= "  GROUP BY place_id, type";
    $sql .= "  ) AS mob";
    $sql .= "  ON moa.place_id = mob.place_id";
    $sql .= " AND moa.type = mob.type";
    $sql .= " AND moa.record_no = mob.max_rec";
    $sql .= " ORDER BY moa.place_id";
    $tgtOfficeList = customSQL($sql);

    // mst_uninsure
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
        // 有効期間
        $tgtData['start_day'] = $tgtData['start_day'] != '' ? formatDateTime($tgtData['start_day'], 'Y/m/d') : '';
        $tgtData['end_day'] = $tgtData['end_day'] != '' ? formatDateTime($tgtData['end_day'], 'Y/m/d') : '';
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
    $dispCode = $tgtCode;
    $dispOfficeList = $tgtOfficeList;
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
