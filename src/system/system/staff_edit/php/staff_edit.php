<?php

//=====================================================================
// 従業員一覧
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
    $err         = array();
    $_SESSION['notice']['error']   = array();
    $dispData    = array();
    $dispOfc     = array();
    $tgtData     = array();
    $upData      = array();
    $upOfcData   = array();

    // 対象テーブル(メイン)
    $table   = 'mst_staff';
    $scrName = '従業員マスタ';

    // 初期値
    $dispData = initTable($table);
    $dispData['birthAry']['Y']      = null;
    $dispData['birthAry']['m']      = null;
    $dispData['birthAry']['d']      = null;
    $dispData['birthAry']['nengo']  = null;
    $dispData['birthAry']['wareki'] = null;
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

    // 更新配列(mst_staff)
    $upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upAry = $upAry ? $upAry : array();

    // 更新配列(mst_staff_office)
    $upOfc = filter_input(INPUT_POST, 'upOfc', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upOfc = $upOfc ? $upOfc : array();

    // 削除ボタン
    $btnDel = h(filter_input(INPUT_POST, 'btnDel'));

    /*-- その他パラメータ ---------------------------------------*/

    //debug($upOfc);exit;
    /* ===================================================
     * マスタ取得
     * ===================================================
     */

    // 拠点
    $plcMst = getData('mst_place');

    // 事業所
    $ofcList = getOfficeList();
    foreach ($ofcList as $val) {
        $plcId = $val['place_id'];
        $ofcId = $val['unique_id'];
        $plcOfc[$plcId][$ofcId] = $val['name'];
    }

    // 汎用マスタ
    $gnrList = getCode();

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 入力チェック ----------------------------------------*/

    if ($btnEntry && empty($upOfc)) {
        $_SESSION['notice']['error'][] = '所属事業所の指定がありません';
        $btnEntry = null;
    }

    /* -- 更新用配列作成 ----------------------------------------*/

    // 更新配列(mst_staff)
    if ($btnEntry && $upAry) {

        // KEY
        if (empty($upAry['unique_id'])) {
            unset($upAry['unique_id']);
        }

        // 保有資格のテキスト化
        if (isset($upAry['license2']) && is_array($upAry['license2'])) {
            $upAry['license2'] = implode("^", $upAry['license2']);
        }
        // 誕生日
        if ($upAry['birthday']['Y'] && $upAry['birthday']['m'] && $upAry['birthday']['d']) {
            $upAry['birthday'] = $upAry['birthday']['Y'] . '-' . $upAry['birthday']['m'] . '-' . $upAry['birthday']['d'];
        }
        // 自動車免許、退職(チェックボックス判定)
        $upAry['driving_license'] = isset($upAry['driving_license']) ? $upAry['driving_license'] : 0;
        $upAry['retired']         = isset($upAry['retired']) ? $upAry['retired'] : 0;

        // 更新配列
        $upData = $upAry;
    }

    // 更新配列(mst_staff_office)
    if ($btnEntry && $upOfc) {
        foreach ($upOfc as $key => $val) {

            // KEY
            if (empty($val['unique_id'])) {
                unset($val['unique_id']);
            }

            // 拠点ID、名称
            $plcId = $val['place_id'] ?: 'dummy';
            $val['place_name'] = isset($plcMst[$plcId]['name'])
                    ? $plcMst[$plcId]['name']
                    : null;

            // 事業所1ID、名称
            $ofcId1 = $val['office1_id'] ?: 'dummy';
            $val['office1_name'] = isset($ofcList[$ofcId1]['name'])
                    ? $ofcList[$ofcId1]['name']
                    : null;

            // 事業所2ID、名称
            $ofcId2 = $val['office2_id'] ?: 'dummy';
            $val['office2_name'] = isset($ofcList[$ofcId2]['name'])
                    ? $ofcList[$ofcId2]['name']
                    : null;

            // 更新配列
            $upOfcData[$key] = $val;
        }
    }

    // 事業所削除
    if ($btnDel) {
        $upOfcData = array();
        $upOfcData[0]['unique_id']  = $btnDel;
        $upOfcData[0]['delete_flg'] = 1;
    }

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */
    // データ更新
    if ($btnEntry && $upData) {

        // mst_staff更新
        $res = upsert($loginUser, $table, $upData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        $keyId = $res;

        // mst_staff_office更新
        if (!empty($upOfcData)) {
            foreach ($upOfcData as $key => $val) {
                $val['staff_id'] = $keyId;
                $upOfcData[$key] = $val;
            }
            $res = multiUpsert($loginUser, 'mst_staff_office', $upOfcData);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }
        }
        // 画面遷移
        $nextPage = $server['scriptName'] . '?id=' . $keyId;
        header("Location:" . $nextPage);
        exit;
    }

    // 所属事業所データ削除
    if ($btnDel && $upOfcData) {

        // mst_staff_office更新
        $res = multiUpsert($loginUser, 'mst_staff_office', $upOfcData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
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
    if ($keyId) {

        // mst_staff
        $where = array();
        $where['unique_id'] = $keyId;
        $dispData = getData($table, $where);

        // mst_staff_office
        $where = array();
        $where['staff_id'] = $keyId;
        $dispOfc = getData('mst_staff_office', $where);
    }

    /* -- データ変換 --------------------------------------------*/

    // 誕生日
    if ($dispData['birthday']) {
        $dispData['birthAry']['Y'] = formatDateTime($dispData['birthday'], 'Y');
        $dispData['birthAry']['m'] = formatDateTime($dispData['birthday'], 'm');
        $dispData['birthAry']['d'] = formatDateTime($dispData['birthday'], 'd');
        $dispData['birthAry']['nengo']  = chgAdToJpNengo($dispData['birthday']);
        $dispData['birthAry']['wareki'] = chgAdToJpYear($dispData['birthday']);
    }

    // 更新情報
    if ($keyId) {
        $dispData['create_day']  = formatDateTime($dispData['create_date'], 'Y/m/d');
        $dispData['create_time'] = formatDateTime($dispData['create_date'], 'H:i');
        $dispData['create_name'] = getStaffName($dispData['create_user']);
        $dispData['update_day']  = formatDateTime($dispData['update_date'], 'Y/m/d');
        $dispData['update_time'] = formatDateTime($dispData['update_date'], 'H:i');
        $dispData['update_name'] = getStaffName($dispData['update_user']);
    }

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
