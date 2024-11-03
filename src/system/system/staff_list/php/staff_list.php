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
    $err      = array();
    $_SESSION['notice']['error']   = array();
    $dispData = array();
    $tgtData  = array();
    $upData   = array();
    $tgtOfc   = array();
    $ofcName  = array();
    $tgtStf   = array();
    $stfOfc   = array();
    $stfList  = array();

    // 対象テーブル(メイン)
    $table = 'mst_staff';

    // 表示件数
    $line = 100;

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 検索配列
    $search = filter_input(INPUT_GET, 'sAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $search['place']    = !empty($search['place']) ? $search['place'] : null;
    $search['staff_id'] = !empty($search['staff_id']) ? $search['staff_id'] : null;
    $search['name']     = !empty($search['name']) ? $search['name'] : null;
    $search['retired']  = !empty($search['retired']) ? $search['retired'] : null;

    /*-- 更新用パラメータ ---------------------------------------*/

    $btnSearch = h(filter_input(INPUT_GET, 'btnSearch'));
    $btnClear  = h(filter_input(INPUT_GET, 'btnClear'));

    /*-- その他パラメータ ---------------------------------------*/

    // ページャー
    $page = h(filter_input(INPUT_GET, 'page'));

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */
    if ($btnClear && $tgtSearch) {
        $tgtSearch['place'] = '';
        $tgtSearch['staff_id'] = '';
        $tgtSearch['name'] = '';
        $tgtSearch['retired'] = '';
    }

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/

    // 拠点リスト
    $search['placelist'] = getData('mst_place');

    // 拠点名称
    $where  = array();
    $target = 'unique_id,name';
    $temp = select('mst_place', $target, $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $plcName[$tgtId] = $val['name'];
    }

    // 事業所マスタ取得
    $where = array();
    $where['delete_flg'] = 0;
    if ($search['place']) {
        $where['place_id'] = $search['place'];
    }
    $target = 'unique_id,name';
    $temp = select('mst_office', $target, $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $tgtOfc[$tgtId]  = true;
        $ofcName[$tgtId] = $val['name'];
    }

    // 従業員所属事業所
    $where = array();
    $where['delete_flg'] = 0;
    $target = 'staff_id,office1_id,office2_id,place_id';
    $temp = select('mst_staff_office', $target, $where);
    foreach ($temp as $val) {
        $ofc1 = $val['office1_id'];
        $ofc2 = $val['office2_id'];
        if (isset($tgtOfc[$ofc1]) || isset($tgtOfc[$ofc2])) {
            $tgtId = $val['staff_id'];
            $tgtStf[] = $tgtId;
            $stfOfc[$tgtId]['place_id'] = $val['place_id'];
            $stfOfc[$tgtId]['office'][] = $ofc1;
            $stfOfc[$tgtId]['office'][] = $ofc2;
        }
    }

    // 従業員マスタ
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $tgtStf;
    if (!empty($search['staff_id'])) {
        $where['staff_id'] = $search['staff_id'];
    }
    if (!empty($search['retired'])) {
        $where['retired'] = 1;
    }
    $target = '*';
    $orderBy = 'unique_id ASC';
    $temp = select('mst_staff', $target, $where, $orderBy);
    foreach ($temp as $val) {

        // ID、名称
        $tgtId = $val['unique_id'];
        $val['name'] = $val['last_name'] . ' ' . $val['first_name'];

        // 名称検索
        if (!empty($search['name'])) {
            if (mb_strpos($val['name'], $search['name']) === false) {
                continue;
            }
        }

        // 格納
        $stfList[$tgtId] = $val;
    }

    /* -- データ変換 --------------------------------------------*/
    foreach ($stfList as $stfId => $val) {

        // 所属事業所
        $val['office'] = isset($stfOfc[$stfId]['office']) ? $stfOfc[$stfId]['office'] : array();

        // 拠点名称
        $plcId = isset($stfOfc[$stfId]['place_id']) ? $stfOfc[$stfId]['place_id'] : "";

        //  $plcId = isset($val['office'][0]) ? $val['office'][0] : 'dummy';
        $val['place_name'] = !empty($plcName[$plcId]) ? $plcName[$plcId] : null;

        // 事業所名称
        $val['office_name'] = null;
        $ofc = array();
        $ofcId1 = $val['office'][0];
        $ofcId2 = $val['office'][1];

        if ($ofcId1) {
            $ofc[] = isset($ofcName[$ofcId1]) ? $ofcName[$ofcId1] : null;
        }
        if ($ofcId2) {
            $ofc[] = isset($ofcName[$ofcId2]) ? $ofcName[$ofcId2] : null;
        }

        $val['office_name'] = implode("、", $ofc);

        // 格納
        $tgtData[$stfId] = $val;
    }

    // ページャー
    $dispData = getPager($tgtData, $page, $line);
    $dispSearch = $search;

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
