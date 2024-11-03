<?php

//=====================================================================
// 従業員予定実績
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
    $_SESSION['notice']['error'] = array();
    $dispPlan          = array();
    $dispRcd           = array();
    $tgtData           = array();
    $userIds           = null;
    $userList          = array();
    $planIds           = null;
    $planList          = array();
    $planInfo          = array();
    $rcdIds            = null;
    $rcdList           = array();
    $rcdInfo           = array();
    $stfIds            = null;
    $stfList           = array();
    $stfPlanIds        = null;
    $stfPlanInfo       = array();

    $upUserPlanData    = array();
    $upUserPlanAddData = array();
    $upUserPlanJpiData = array();
    $upUserPlanSvcData = array();
    $upUserPlanSvcData2 = array();
    $upUserRcdData     = array();
    $upUserRcdAddData  = array();
    $upUserRcdJpiData  = array();
    $upUserRcdSvcData  = array();
    $upUserRcdSvcData2 = array();
    $upStfPlanData     = array();
    $upStfRcdData      = array();
    $upKtkData         = array();

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
    $search['other_id']   = !empty($search['other_id']) ? $search['other_id'] : null;
    $search['staff_id']   = !empty($search['staff_id']) ? $search['staff_id'] : null;
    $search['staff_name'] = !empty($search['staff_name']) ? $search['staff_name'] : null;
    $search['date_from']  = !empty($search['date_from']) ? $search['date_from'] : TODAY;
    $search['date_to']    = !empty($search['date_to']) ? $search['date_to'] : TODAY;
    $search['search1']    = isset($search['search1']) ? $search['search1'] : null;
    $search['search3']    = isset($search['search3']) ? $search['search3'] : null;
    //$search['search1']    = !empty($search['search1'])    ? $search['search1']    : NULL;
    //$search['search3']    = !empty($search['search3'])    ? $search['search3']    : NULL;

    // サービス内容の表示内容
    $typeList['訪問看護　介護保険']['name']  = '訪問看護<br>介護保険';
    $typeList['訪問看護　介護保険']['class'] = 'cate cate1';
    $typeList['訪問看護　医療保険']['name']  = '訪問看護<br>医療保険';
    $typeList['訪問看護　医療保険']['class'] = 'cate cate2';
    $typeList['訪問看護　定期巡回']['name']  = '訪問看護　定期巡回';
    $typeList['訪問看護　定期巡回']['class'] = 'cate cate3';
    $typeList['看多機　訪問看護']['name']   = '看多機<br>訪問看護';
    $typeList['看多機　訪問看護']['class']  = 'cate cate4';
    $typeList['看多機　訪問介護']['name']   = '看多機<br>訪問介護';
    $typeList['看多機　訪問介護']['class']  = 'cate cate5';
    $typeList['看多機　宿泊']['name']      = '看多機<br>宿泊';
    $typeList['看多機　宿泊']['class']     = 'cate cate6';
    $typeList['看多機　通い']['name']      = '看多機<br>通い';
    $typeList['看多機　通い']['class']     = 'cate cate7';
    $typeList['その他']['name']           = 'その他';
    $typeList['その他']['class']          = 'cate';

    $selHour = ['','00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];
    $selMinutes = ['','00','05','10','15','20','25','30','35','40','45','50','55'];

    /*-- 更新用パラメータ ---------------------------------------*/

    // 更新配列(利用者予定)
    $upUserPlan = filter_input(INPUT_POST, 'upUserPlan', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upUserPlan = $upUserPlan ? $upUserPlan : array();

    // 更新配列(利用者予定-加減算)
    $upUserPlanAdd = filter_input(INPUT_POST, 'upUserPlanAdd', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upUserPlanAdd = $upUserPlanAdd ? $upUserPlanAdd : array();

    // 更新配列(利用者予定-実費)
    $upUserPlanJpi = filter_input(INPUT_POST, 'upUserPlanJpi', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upUserPlanJpi = $upUserPlanJpi ? $upUserPlanJpi : array();

    // 更新配列(利用者予定-サービス)
    $upUserPlanSvc = filter_input(INPUT_POST, 'upUserPlanSvc', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upUserPlanSvc = $upUserPlanSvc ? $upUserPlanSvc : array();


    // 更新配列(利用者実績)
    $upUserRcd = filter_input(INPUT_POST, 'upUserRcd', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upUserRcd = $upUserRcd ? $upUserRcd : array();

    // 更新配列(利用者実績-加減算)
    $upUserRcdAdd = filter_input(INPUT_POST, 'upUserRcdAdd', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upUserRcdAdd = $upUserRcdAdd ? $upUserRcdAdd : array();

    // 更新配列(利用者実績-実費)
    $upUserRcdJpi = filter_input(INPUT_POST, 'upUserRcdJpi', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upUserRcdJpi = $upUserRcdJpi ? $upUserRcdJpi : array();

    // 更新配列(利用者実績-サービス)
    $upUserRcdSvc = filter_input(INPUT_POST, 'upUserRcdSvc', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upUserRcdSvc = $upUserRcdSvc ? $upUserRcdSvc : array();


    // 更新配列(スタッフ-予定)
    $upStfPlan = filter_input(INPUT_POST, 'upStfPlan', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upStfPlan = $upStfPlan ? $upStfPlan : array();

    // 更新配列(スタッフ-実績)
    $upStfRcd = filter_input(INPUT_POST, 'upStfRcd', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upStfRcd = $upStfRcd ? $upStfRcd : array();

    // 更新配列(看多機記録用)
    //  利用者予定ごとにhiddenで以下の項目を格納
    //   $upKtk[予定KEY]['unique_id']   = 連携用記録ID       ← "kantaki"
    //   $upKtk[予定KEY]['service_day'] = 日付(yyyy-mm-dd)   ← "use_day"
    //   $upKtk[予定KEY]['start_time']  = 開始時刻(hh:mm:ss) ← "start_time"
    //   $upKtk[予定KEY]['end_time']    = 終了時刻(hh:mm:ss) ← "end_time"
    $upKtk = filter_input(INPUT_POST, 'upKtk', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upKtk = $upKtk ? $upKtk : array();

    // 新規ボタン-スタッフ予定
    $btnNewStfPlan = h(filter_input(INPUT_POST, 'btnNewStfPlan'));

    // 新規ボタン-利用者予定
    $btnNewUserPlan = h(filter_input(INPUT_POST, 'btnNewUserPlan'));


    // 編集保存ボタン-利用者予定
    $btnEditUserPlan = h(filter_input(INPUT_POST, 'btnEditUserPlan'));

    // 編集保存ボタン-利用者実績
    $btnEditUserRcd = h(filter_input(INPUT_POST, 'btnEditUserRcd'));

    // 編集保存ボタン-スタッフ予定
    $btnEditStfPlan = h(filter_input(INPUT_POST, 'btnEditStfPlan'));

    // 編集保存ボタン-スタッフ実績
    $btnEditStfRcd = h(filter_input(INPUT_POST, 'btnEditStfRcd'));


    // 実績確定ボタン-利用者予定
    $btnFixUser = h(filter_input(INPUT_POST, 'btnFixUser'));

    // 実績確定ボタン-スタッフ予定
    $btnFixStf = h(filter_input(INPUT_POST, 'btnFixStf'));


    // 実績変更ボタン-利用者予定サービス
    $btnChgUserRcd = h(filter_input(INPUT_POST, 'btnChgUserRcd'));


    // キャンセルボタン-利用者予定
    $btnCxlUser = h(filter_input(INPUT_POST, 'btnCxlUser'));

    // キャンセルボタン-スタッフ予定
    $btnCxlStf = h(filter_input(INPUT_POST, 'btnCxlStf'));


    // 削除ボタン-利用者予定
    $btnDelUserPlan = h(filter_input(INPUT_POST, 'btnDelUserPlan'));

    // 削除ボタン-スタッフ予定
    $btnDelStfPlan = h(filter_input(INPUT_POST, 'btnDelStfPlan'));


    // (削除)予定戻しボタン-利用者実績
    $btnDelUserRcd = h(filter_input(INPUT_POST, 'btnDelUserRcd'));

    // (削除)予定戻しボタン-スタッフ実績
    $btnDelStfRcd = h(filter_input(INPUT_POST, 'btnDelStfRcd'));


    // 看多機ボタン(予定のKEY)
    $btnKtk = h(filter_input(INPUT_POST, 'btnKantaki'));

    // 看多機ボタン(予定のKEY)
    $btnHokan2 = h(filter_input(INPUT_POST, 'btnHokan2'));

    /*-- その他パラメータ ---------------------------------------*/
    // 初期表示時のデフォルト設定する
    if (!$btnSearch
        && !$btnNewStfPlan && !$btnNewUserPlan
        && !$btnEditUserPlan && !$btnEditUserRcd
        && !$btnEditStfPlan && !$btnEditStfRcd
        && !$btnFixUser && !$btnFixStf
        && !$btnChgUserRcd
        && !$btnCxlUser && !$btnCxlStf
        && !$btnDelUserPlan && !$btnDelStfPlan
    ) {
        $search['search1'] = '実績未のみ';
        $search['staff_id'] = $loginUser['unique_id'];
        $search['other_id'] = $loginUser['staff_id'];
        $search['staff_name'] = $loginUser['name'];
    }

    /* ===================================================
    * マスタ取得
    * ===================================================
    */

    /* -- マスタ関連 --------------------------------------------*/

    // 加算マスタ
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_add', '*', $where);
    foreach ($temp as $val) {
        $addId = $val['unique_id'];
        $type  = $val['type'];
        $tgtId = $val['code'];
        $addInfo[$tgtId]       = $val;
        $addInfo2[$addId]      = $val;
        $addMst[$type][$tgtId] = $val['name'];
    }

    // サービスマスタ
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service', '*', $where);
    foreach ($temp as $val) {
        $type  = $val['type'];
        $tgtId = $val['unique_id'];
        $tgtCd = $val['code'];
        $name  = $val['name'];
        $svcInfo[$tgtId]       = $val;
        $svcMst[$type][$tgtCd] = $val['name'];
        $svcName[$name] = $tgtId;
    }

    // サービス詳細リスト取得
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service_detail', '*', $where);
    foreach ($temp as $val) {
        $type  = $val['type'];
        $tgtId = $val['unique_id'];
        $svcDtlInfo[$tgtId]       = $val;
        $svcDtlMst[$type][$tgtId] = $val['name'];
    }

    // 保険外マスタ
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_uninsure', '*', $where);
    foreach ($temp as $val) {
        $type    = $val['type'];
        $tgtId   = $val['unique_id'];
        $zeiType = $val['zei_type'];
        $subsidy = $val['subsidy'];
        $unInsType['type'][$type]        = true;
        $unInsType['zei_type'][$zeiType] = true;
        $unInsType['subsidy'][$subsidy]  = true;
        $unInsInfo[$tgtId]       = $val;
        $unInsMst[$type][$tgtId] = $val['name'];
    }

    // 事業所リスト取得
    $ofcList = getOfficeList($placeId);

    // コードマスタ取得
    $codeList = getCode();

    // ユーザー情報取得
    $userList = getUserList($placeId);
    foreach ($userList as $val) {
        $userIds[] = $val['unique_id'];
    }

    // スタッフリスト
    $stfList = getStaffList($placeId);

    /* ===================================================
    * イベント前処理(更新用配列作成、入力チェックなど)
    * ===================================================
    */

    /* -- 更新用配列作成 ----------------------------------------*/

    // 新規ボタン-スタッフ予定
    if ($btnNewStfPlan) {

        $upStfPlan[0]['start_time'] = $upStfPlan[0]['start_time_h'] . ":" . $upStfPlan[0]['start_time_m'];
        $upStfPlan[0]['end_time'] = $upStfPlan[0]['end_time_h'] . ":" . $upStfPlan[0]['end_time_m'];
        unset($upStfPlan[0]['start_time_h']);
        unset($upStfPlan[0]['start_time_m']);
        unset($upStfPlan[0]['end_time_h']);
        unset($upStfPlan[0]['end_time_m']);
        $upStfPlanData = $upStfPlan[0];
    }

    // 編集保存ボタン-利用者予定
    if ($btnNewUserPlan) {
        $upUserPlanData    = $upUserPlan[0];
        $tgtDat            = $upUserPlan[0]['base_service'] ? $upUserPlan[0]['base_service'] : 'dummy';
        $tgtAry            = explode('(', $tgtDat);
        $tgtName           = $tgtAry[0];
        $upUserPlanData['service_id']   = isset($svcName[$tgtName]) ? $svcName[$tgtName] : null;
        $upUserPlanData['service_name'] = $upUserPlan[0]['service_name'];
        unset($upUserPlanData['base_service']);

        $upUserPlanData['start_time']   = $upUserPlanData['start_time_h'] . ":" . $upUserPlanData['start_time_m'];
        $upUserPlanData['end_time']     = $upUserPlanData['end_time_h'] . ":" . $upUserPlanData['end_time_m'];
        unset($upUserPlanData['start_time_h']);
        unset($upUserPlanData['start_time_m']);
        unset($upUserPlanData['end_time_h']);
        unset($upUserPlanData['end_time_m']);

        $upUserPlanAddData = $upUserPlanAdd[0];
        $upUserPlanAddUniqueId = array_key_first($upUserPlanAddData);
        unset($upUserPlanAddData[$upUserPlanAddUniqueId]['unique_id']);

        $upUserPlanJpiData = $upUserPlanJpi[0];
        foreach ($upUserPlanJpiData as $index => $val) {
            $uinsId = $val['uninsure_id'];
            if (isset($upUserPlanJpiData[$index])) {
                $upUserPlanJpiData[$index]['name'] = isset($unInsInfo[$uinsId]['name']) ? $unInsInfo[$uinsId]['name'] : null;
            }
            unset($upUserPlanJpiData[$index]['unique_id']);
        }

        $upUserPlanSvcData = $upUserPlanSvc[0];
        foreach ($upUserPlanSvcData as $idx => $svcData) {
            $starth = isset($svcData['start_time_h']) ? $svcData['start_time_h'] : null;
            $startm = isset($svcData['start_time_m']) ? $svcData['start_time_m'] : null;
            $endh   = isset($svcData['end_time_h']) ? $svcData['end_time_h'] : null;
            $endm   = isset($svcData['end_time_m']) ? $svcData['end_time_m'] : null;

            $upUserPlanSvcData[$idx]['start_time']   =  !empty($starth) && !empty($startm) ? $starth . ":" . $startm : null;
            $upUserPlanSvcData[$idx]['end_time']     =  !empty($endh) && !empty($endm) ? $endh . ":" . $endm : null;

            unset($upUserPlanSvcData[$idx]['unique_id']);
            unset($upUserPlanSvcData[$idx]['start_time_h']);
            unset($upUserPlanSvcData[$idx]['start_time_m']);
            unset($upUserPlanSvcData[$idx]['end_time_h']);
            unset($upUserPlanSvcData[$idx]['end_time_m']);
        }
    }


    // 編集保存ボタン-利用者予定
    if ($btnEditUserPlan) {
        $upUserPlanData    = $upUserPlan[$btnEditUserPlan];
        $tgtDat            = $upUserPlan[$btnEditUserPlan]['base_service'] ? $upUserPlan[$btnEditUserPlan]['base_service'] : 'dummy';
        $tgtAry            = explode('(', $tgtDat);
        $tgtName           = $tgtAry[0];
        $upUserPlanData['service_id']   = isset($svcName[$tgtName]) ? $svcName[$tgtName] : null;
        $upUserPlanData['service_name'] = $upUserPlan[$btnEditUserPlan]['service_name'];
        unset($upUserPlanData['base_service']);

        $upUserPlanData['start_time']   = $upUserPlanData['start_time_h'] . ":" . $upUserPlanData['start_time_m'];
        $upUserPlanData['end_time']     = $upUserPlanData['end_time_h'] . ":" . $upUserPlanData['end_time_m'];
        unset($upUserPlanData['start_time_h']);
        unset($upUserPlanData['start_time_m']);
        unset($upUserPlanData['end_time_h']);
        unset($upUserPlanData['end_time_m']);

        $upUserPlanAddData = $upUserPlanAdd[$btnEditUserPlan];
        foreach ($upUserPlanJpi as $jpiId => $val) {
            if (isset($val['uninsure_id'])) {
                $uinsId = $val['uninsure_id'];
                $upUserPlanJpi[$jpiId]['name'] = $unInsInfo[$uinsId]['name'] ? $unInsInfo[$uinsId]['name'] : '';
            }
        }
        $upUserPlanJpiData = $upUserPlanJpi[$btnEditUserPlan];

        foreach ($upUserPlanSvc[$btnEditUserPlan] as $svcId => $val) {
            $upUserPlanSvc[$btnEditUserPlan][$svcId]['start_time']   = $upUserPlanSvc[$btnEditUserPlan][$svcId]['start_time_h'] . ":" . $upUserPlanSvc[$btnEditUserPlan][$svcId]['start_time_m'];
            $upUserPlanSvc[$btnEditUserPlan][$svcId]['end_time']     = $upUserPlanSvc[$btnEditUserPlan][$svcId]['end_time_h'] . ":" . $upUserPlanSvc[$btnEditUserPlan][$svcId]['end_time_m'];
            unset($upUserPlanSvc[$btnEditUserPlan][$svcId]['start_time_h']);
            unset($upUserPlanSvc[$btnEditUserPlan][$svcId]['start_time_m']);
            unset($upUserPlanSvc[$btnEditUserPlan][$svcId]['end_time_h']);
            unset($upUserPlanSvc[$btnEditUserPlan][$svcId]['end_time_m']);
        }

        $upUserPlanSvcData = $upUserPlanSvc[$btnEditUserPlan];
    }

    // 実績変更ボタン-利用者実績
    if ($btnChgUserRcd) {

        $userPlanId     = $btnChgUserRcd;
        $upUserRcdData  = $upUserRcd[0];
        $tgtDat         = $upUserRcd[0]['base_service'] ? $upUserRcd[0]['base_service'] : 'dummy';
        $tgtAry         = explode('(', $tgtDat);
        $tgtName        = $tgtAry[0];
        $svcId          = isset($svcName[$tgtName]) ? $svcName[$tgtName] : null;
        $upUserRcdData['service_id']   = $svcId;
        $upUserRcdData['service_name'] = $svcId && isset($svcInfo[$svcId]['type']) ? $svcInfo[$svcId]['type'] : null;
        unset($upUserRcdData['base_service']);

        $upUserRcdData['start_time']   = $upUserRcdData['start_time_h'] . ":" . $upUserRcdData['start_time_m'];
        $upUserRcdData['end_time']     = $upUserRcdData['end_time_h'] . ":" . $upUserRcdData['end_time_m'];
        unset($upUserRcdData['start_time_h']);
        unset($upUserRcdData['start_time_m']);
        unset($upUserRcdData['end_time_h']);
        unset($upUserRcdData['end_time_m']);

        // 利用者予定IDを設定
        $upUserRcdData['user_plan_id'] = $userPlanId;

        $upUserRcdAddData = $upUserRcdAdd[0];
        $upUserRcdJpiData = $upUserRcdJpi[0];
        foreach ($upUserRcdJpiData as $index => $val) {
            $uinsId = $val['uninsure_id'];
            if (isset($upUserRcdJpiData[$index])) {
                $upUserRcdJpiData[$index]['name'] = isset($unInsInfo[$uinsId]['name']) ? $unInsInfo[$uinsId]['name'] : null;
            }
        }

        $upUserRcdSvcData = $upUserRcdSvc[0];
        foreach ($upUserRcdSvcData as $idx => $svcData) {
            $starth = isset($svcData['start_time_h']) ? $svcData['start_time_h'] : null;
            $startm = isset($svcData['start_time_m']) ? $svcData['start_time_m'] : null;
            $endh   = isset($svcData['end_time_h']) ? $svcData['end_time_h'] : null;
            $endm   = isset($svcData['end_time_m']) ? $svcData['end_time_m'] : null;

            $upUserRcdSvcData[$idx]['start_time']   =  !empty($starth) && !empty($startm) ? $starth . ":" . $startm : null;
            $upUserRcdSvcData[$idx]['end_time']     =  !empty($endh) && !empty($endm) ? $endh . ":" . $endm : null;

            unset($upUserRcdSvcData[$idx]['start_time_h']);
            unset($upUserRcdSvcData[$idx]['start_time_m']);
            unset($upUserRcdSvcData[$idx]['end_time_h']);
            unset($upUserRcdSvcData[$idx]['end_time_m']);
        }
    }

    // 編集保存ボタン-利用者実績
    if ($btnEditUserRcd) {

        // 実績（加減算）にないカラムを除外する
        if (isset($upUserRcdAdd[$btnEditUserRcd])) {
            foreach ($upUserRcdAdd[$btnEditUserRcd] as $key => $val) {
                unset($upUserRcdAdd[$btnEditUserRcd][$key]['user_plan_id']);
            }
        }

        // 実績（実費）にないカラムを除外する
        if (isset($upUserRcdJpi[$btnEditUserRcd])) {
            foreach ($upUserRcdJpi[$btnEditUserRcd] as $key => $val) {
                unset($upUserRcdJpi[$btnEditUserRcd][$key]['user_plan_id']);
            }
        }

        // 実績（サービス）にないカラムを除外する
        if (isset($upUserRcdSvc[$btnEditUserRcd])) {
            foreach ($upUserRcdSvc[$btnEditUserRcd] as $key => $val) {
                unset($upUserRcdSvc[$btnEditUserRcd][$key]['user_plan_id']);
            }
        }

        $upUserRcdData    = $upUserRcd[$btnEditUserRcd];
        $tgtDat            = $upUserRcd[$btnEditUserRcd]['base_service'] ? $upUserRcd[$btnEditUserRcd]['base_service'] : 'dummy';
        $tgtAry            = explode('(', $tgtDat);
        $tgtName           = $tgtAry[0];
        $upUserRcdData['service_id']   = isset($svcName[$tgtName]) ? $svcName[$tgtName] : null;
        $upUserRcdData['service_name'] = $upUserRcd[$btnEditUserRcd]['service_name'];
        unset($upUserRcdData['base_service']);
        $upUserRcdData['start_time']   = $upUserRcdData['start_time_h'] . ":" . $upUserRcdData['start_time_m'];
        $upUserRcdData['end_time']     = $upUserRcdData['end_time_h'] . ":" . $upUserRcdData['end_time_m'];
        if (isset($upUserRcdData['start_time_h'])) {
            unset($upUserRcdData['start_time_h']);
        }
        if (isset($upUserRcdData['start_time_m'])) {
            unset($upUserRcdData['start_time_m']);
        }
        if (isset($upUserRcdData['end_time_h'])) {
            unset($upUserRcdData['end_time_h']);
        }
        if (isset($upUserRcdData['end_time_m'])) {
            unset($upUserRcdData['end_time_m']);
        }

        $upUserRcdAddData = $upUserRcdAdd[$btnEditUserRcd];

        foreach ($upUserRcdJpi as $jpiId => $val) {
            if (isset($val['uninsure_id'])) {
                $uinsId = $val['uninsure_id'];
                $upUserRcdJpi[$jpiId]['name'] = $unInsInfo[$uinsId]['name'] ? $unInsInfo[$uinsId]['name'] : '';
            }
        }
        $upUserRcdJpiData = $upUserRcdJpi[$btnEditUserRcd];

        foreach ($upUserRcdSvc[$btnEditUserRcd] as $svcId => $val) {
            $upUserRcdSvc[$btnEditUserRcd][$svcId]['start_time']   = $upUserRcdSvc[$btnEditUserRcd][$svcId]['start_time_h'] . ":" . $upUserRcdSvc[$btnEditUserRcd][$svcId]['start_time_m'];
            $upUserRcdSvc[$btnEditUserRcd][$svcId]['end_time']     = $upUserRcdSvc[$btnEditUserRcd][$svcId]['end_time_h'] . ":" . $upUserRcdSvc[$btnEditUserRcd][$svcId]['end_time_m'];
            if (isset($upUserRcdSvc[$btnEditUserRcd][$svcId]['start_time_h'])) {
                unset($upUserRcdSvc[$btnEditUserRcd][$svcId]['start_time_h']);
            }
            if (isset($upUserRcdSvc[$btnEditUserRcd][$svcId]['start_time_m'])) {
                unset($upUserRcdSvc[$btnEditUserRcd][$svcId]['start_time_m']);
            }
            if (isset($upUserRcdSvc[$btnEditUserRcd][$svcId]['end_time_h'])) {
                unset($upUserRcdSvc[$btnEditUserRcd][$svcId]['end_time_h']);
            }
            if (isset($upUserRcdSvc[$btnEditUserRcd][$svcId]['end_time_m'])) {
                unset($upUserRcdSvc[$btnEditUserRcd][$svcId]['end_time_m']);
            }
        }
        $upUserRcdSvcData = $upUserRcdSvc[$btnEditUserRcd];
    }

    // 編集保存ボタン-スタッフ予定
    if ($btnEditStfPlan) {

        $upStfPlan[$btnEditStfPlan]['start_time'] = $upStfPlan[$btnEditStfPlan]['start_time_h'] . ":" . $upStfPlan[$btnEditStfPlan]['start_time_m'];
        $upStfPlan[$btnEditStfPlan]['end_time'] = $upStfPlan[$btnEditStfPlan]['end_time_h'] . ":" . $upStfPlan[$btnEditStfPlan]['end_time_m'];
        unset($upStfPlan[$btnEditStfPlan]['start_time_h']);
        unset($upStfPlan[$btnEditStfPlan]['start_time_m']);
        unset($upStfPlan[$btnEditStfPlan]['end_time_h']);
        unset($upStfPlan[$btnEditStfPlan]['end_time_m']);

        $upStfPlanData = $upStfPlan[$btnEditStfPlan];
    }

    // 編集保存ボタン-スタッフ実績
    if ($btnEditStfRcd) {

        // 実績の更新配列作成
        $upStfRcdData = $upStfRcd[$btnEditStfRcd];
        $upStfRcdData['unique_id'] = $btnEditStfRcd;
        $upStfRcdData['start_time'] = $upStfRcdData['start_time_h'] . ":" . $upStfRcdData['start_time_m'];
        $upStfRcdData['end_time']   = $upStfRcdData['end_time_h'] . ":" . $upStfRcdData['end_time_m'];
        unset($upStfRcdData['start_time_h']);
        unset($upStfRcdData['start_time_m']);
        unset($upStfRcdData['end_time_h']);
        unset($upStfRcdData['end_time_m']);
    }

    // 実績確定ボタン-利用者予定
    if ($btnFixUser) {

        // 子の予定情報
        $where = array();
        $where['unique_id'] = $btnFixUser;
        $temp = select('dat_user_plan_service', '*', $where);
        if (isset($temp[0])) {

            // 親の予定ID、子の予定ID
            $svcData   = $temp[0];
            $planId    = $svcData['user_plan_id'];
            $planSvcId = $svcData['unique_id'];

            // 子の予定更新情報
            $dat = array();
            $dat['unique_id']      = $planSvcId;
            $dat['protection_flg'] = 1;
            $dat['status']         = '実施';
            $upUserPlanSvcData[$planSvcId] = $dat;

            // 子の実績情報新規作成
            $dat = $svcData;
            unset($dat['unique_id']);
            unset($dat['delete_flg']);
            unset($dat['create_date']);
            unset($dat['create_user']);
            unset($dat['update_date']);
            unset($dat['update_user']);
            unset($dat['schedule_id']);
            unset($dat['user_plan_id']);
            unset($dat['protection_flg']);
            unset($dat['status']);
            $dat['user_plan_service_id'] = $planSvcId;
            $upUserRcdSvcData[$planSvcId]  = $dat;

            // 親の実績判定、なければ実績データ作成
            $where = array();
            $where['delete_flg']   = 0;
            $where['user_plan_id'] = $planId;
            $temp = select('dat_user_record', '*', $where);
            if (empty($temp)) {
                // 予定から実績(親)を作成する
                $where = array();
                $where['unique_id'] = $planId;
                $temp2 = select('dat_user_plan', '*', $where);
                if (isset($temp2[0])) {
                    $dat = $temp2[0];
                    unset($dat['unique_id']);
                    unset($dat['protection_flg']);
                    unset($dat['kantaki']);
                    unset($dat['schedule_id']);
                    unset($dat['kantaki2']);
                    $dat['user_plan_id'] = $planId;
                    $upUserRcdData = $dat;
                }

                // 予定から実績（加減算）を作成する
                $where = array();
                $where['user_plan_id'] = $planId;
                $temp2 = select('dat_user_plan_jippi', '*', $where);
                foreach ($temp2 as $dat) {
                    unset($dat['unique_id']);
                    unset($dat['user_plan_id']);
                    if (empty($dat['add_id'])) {
                        continue;
                    }
                    $upUserRcdAddData = $dat;
                }

                // 予定から実績（実費）を作成する
                $where = array();
                $where['user_plan_id'] = $planId;
                $temp2 = select('dat_user_plan_jippi', '*', $where);
                if (isset($temp2[0])) {
                    $dat       = $temp2[0];
                    $jipPlanId = $dat['unique_id'];

                    $dat['user_plan_id'] = $planId;
                    unset($dat['unique_id']);
                    unset($dat['user_plan_id']);
                    $upUserRcdJpiData[$jipPlanId] = $dat;
                }

            } else {
                $recId = $temp[0]['unique_id'];
                $upUserRcdSvcData[$planSvcId]['user_record_id'] = $recId;
            }
        }
    }

    // 実績確定ボタン-スタッフ予定
    if ($btnFixStf) {
        // 予定情報取得
        $where = array();
        $where['unique_id'] = $btnFixStf;
        $temp = select('dat_staff_plan', '*', $where);
        if (isset($temp[0])) {
            $upStfPlan[$btnFixStf] = $temp[0];
            $upStfPlan[$btnFixStf]['status']         = '実施';
            $upStfPlan[$btnFixStf]['protection_flg'] = 1;
            $upStfPlanData = $upStfPlan[$btnFixStf];

            // 実績データ作成
            $upStfRcd = $upStfPlanData;
            $upStfRcd['staff_plan_id'] = $upStfPlanData['unique_id'];
            unset($upStfRcd['unique_id']);
            unset($upStfRcd['protection_flg']);
            unset($upStfRcd['schedule_id']);
            $upStfRcdData  = $upStfRcd;
        }
    }

    // キャンセルボタン-利用者予定
    if ($btnCxlUser) {
        // 子の予定情報からplanIdを取得
        $where = array();
        $where['unique_id'] = $btnCxlUser;
        $temp = select('dat_user_plan_service', '*', $where);
        if (isset($temp[0])) {
            $planId = $temp[0]['user_plan_id'];
        }
        $upUserPlanSvc[$planId]['unique_id']      = $btnCxlUser;
        $upUserPlanSvc[$planId]['user_plan_id']   = $planId;
        $upUserPlanSvc[$planId]['status']         = 'キャンセル';
        $upUserPlanSvc[$planId]['protection_flg'] = 1;
        $upUserPlanSvcData = $upUserPlanSvc;
    }

    // キャンセルボタン-スタッフ予定
    if ($btnCxlStf) {
        $upStfPlan[$btnCxlStf]['unique_id']      = $btnCxlStf;
        $upStfPlan[$btnCxlStf]['status']         = 'キャンセル';
        $upStfPlan[$btnCxlStf]['protection_flg'] = 1;
        $upStfPlanData = $upStfPlan[$btnCxlStf];
    }

    // 削除ボタン-利用者予定
    if ($btnDelUserPlan) {
        // 子の予定情報からplanIdを取得
        $where = array();
        $where['unique_id'] = $btnDelUserPlan;
        $temp = select('dat_user_plan_service', '*', $where);
        if (isset($temp[0])) {
            $planId = $temp[0]['user_plan_id'];
        }
        $upUserPlanSvc[$planId]['unique_id']    = $btnDelUserPlan;
        $upUserPlanSvc[$planId]['user_plan_id'] = $planId;
        $upUserPlanSvc[$planId]['delete_flg']   = 1;
        $upUserPlanSvcData = $upUserPlanSvc;
    }

    // 削除ボタン-スタッフ予定
    if ($btnDelStfPlan) {
        $upStfPlan[$btnDelStfPlan]['unique_id']  = $btnDelStfPlan;
        $upStfPlan[$btnDelStfPlan]['delete_flg'] = 1;
        $upStfPlanData = $upStfPlan[$btnDelStfPlan];
    }

    // (削除)予定戻しボタン-利用者実績
    if ($btnDelUserRcd) {

        // 登録済み実績サービス
        $where = array();
        $where['unique_id'] = $btnDelUserRcd;
        $temp      = select('dat_user_record_service', '*', $where);
        $svcRcd    = $temp[0];
        $planSvcId = $svcRcd['user_plan_service_id'];

        // 更新配列 実績サービス
        $dat = array();
        $dat['unique_id']   = $btnDelUserRcd;
        $dat['delete_flg']  = 1;
        $upUserRcdSvcData2 = $dat;

        // 更新配列 予定サービス
        $dat2 = array();
        $dat2['unique_id']      = $planSvcId;
        $dat2['status']         = null;
        $dat2['protection_flg'] = null;
        $upUserPlanSvcData2     = $dat2;
    }

    // (削除)予定戻しボタン-スタッフ実績
    if ($btnDelStfRcd) {

        $planId = $btnDelStfRcd;
        $upStfRcd[$btnDelStfRcd]['unique_id']  = $btnDelStfRcd;
        $upStfRcd[$btnDelStfRcd]['delete_flg'] = 1;
        $upStfRcdData = $upStfRcd[$btnDelStfRcd];

        $where = array();
        $where['unique_id'] = $btnDelStfRcd;
        $stfPlanId = select('dat_staff_record', 'staff_plan_id', $where);

        // 更新配列 スタッフ予定
        $dat = array();
        $dat['unique_id']      = $stfPlanId[0]['staff_plan_id'];
        $dat['status']         = null;
        $dat['protection_flg'] = null;
        $upStfPlanData        = $dat;

    }

    // 看多機記録
    if ($btnKtk) {
        $tgtKtk = $upKtk[$btnKtk];
        if (empty($tgtKtk['unique_id'])) {
            $upKtkData = $tgtKtk;
        } else {
            $ktkId = $tgtKtk['unique_id'];
        }
    }

    /* ===================================================
    * イベント本処理(データ登録)
    * ===================================================
    */

    // データ更新(利用者予定)
    if ($upUserPlanData) {
        $res = upsert($loginUser, 'dat_user_plan', $upUserPlanData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        $planId = $res;

        // ログテーブルに登録する
        setEntryLog($upUserPlanData);

    }
    // データ更新(利用者予定-加減算)
    if ($upUserPlanAddData) {
        foreach ($upUserPlanAddData as $key => $val) {
            $upUserPlanAddData[$key]['user_plan_id'] = $planId;
            if (empty($val['add_id'])) {
                $upUserPlanAddData[$key]['delete_flg'] = 1;
            }
        }
        $res = multiUpsert($loginUser, 'dat_user_plan_add', $upUserPlanAddData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upUserPlanAddData);
    }
    // データ更新(利用者予定-実費)
    foreach ($upUserPlanJpiData as $key => $val) {
        if ($val['type'] === '' && $val['uninsure_id'] === '') {
            unset($upUserPlanJpiData[$key]);
        }
    }
    if ($upUserPlanJpiData) {
        foreach ($upUserPlanJpiData as $key => $val) {
            $upUserPlanJpiData[$key]['user_plan_id'] = $planId;
        }
        $res = multiUpsert($loginUser, 'dat_user_plan_jippi', $upUserPlanJpiData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upUserPlanJpiData);
    }
    // データ更新(利用者予定-サービス)
    if ($upUserPlanSvcData) {
        foreach ($upUserPlanSvcData as $key => $val) {
            if (isset($planId)) {
                $upUserPlanSvcData[$key]['user_plan_id'] = $planId;
            }
        }
        $res = multiUpsert($loginUser, 'dat_user_plan_service', $upUserPlanSvcData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upUserPlanSvcData);
    }

    if ($btnDelUserRcd && $upUserPlanSvcData2) {
        $res = upsert($loginUser, 'dat_user_plan_service', $upUserPlanSvcData2);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setEntryLog($upUserPlanSvcData2);
    }


    // データ更新(利用者実績)
    if ($upUserRcdData) {
        $res = upsert($loginUser, 'dat_user_record', $upUserRcdData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        $rcdId = $res;

        // ログテーブルに登録する
        setEntryLog($upUserRcdData);
    }

    // データ更新(利用者実績-加減算)
    if ($upUserRcdAddData) {
        foreach ($upUserRcdAddData as $key => $val) {
            if (isset($rcdId)) {
                $upUserRcdAddData[$key]['user_record_id'] = $rcdId;
            }
        }
        $res = multiUpsert($loginUser, 'dat_user_record_add', $upUserRcdAddData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upUserRcdAddData);
    }
    // データ更新(利用者実績-実費)
    foreach ($upUserRcdJpiData as $key => $val) {
        if ($val['type'] === '' && $val['uninsure_id'] === '') {
            unset($upUserRcdJpiData[$key]);
        }
    }
    if ($upUserRcdJpiData) {
        foreach ($upUserRcdJpiData as $key => $val) {
            if (isset($rcdId)) {
                $upUserRcdJpiData[$key]['user_record_id'] = $rcdId;
            }
        }
        $res = multiUpsert($loginUser, 'dat_user_record_jippi', $upUserRcdJpiData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upUserRcdJpiData);
    }

    // データ削除(利用者実績-サービス)
    if ($btnDelUserRcd && $upUserRcdSvcData2) {
        $res = upsert($loginUser, 'dat_user_record_service', $upUserRcdSvcData2);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setEntryLog($upUserRcdSvcData2);
    }

    // データ更新(利用者実績-サービス)
    if ($upUserRcdSvcData) {

        foreach ($upUserRcdSvcData as $key => $val) {
            if (isset($rcdId)) {
                $upUserRcdSvcData[$key]['user_record_id'] = $rcdId;
            }
        }
        $res = multiUpsert($loginUser, 'dat_user_record_service', $upUserRcdSvcData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        // ログテーブルに登録する
        setMultiEntryLog($upUserRcdSvcData);
    }

    // データ更新(従業員予定)
    if ($upStfPlanData) {
        $res = upsert($loginUser, 'dat_staff_plan', $upStfPlanData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        // ログテーブルに登録する
        setEntryLog($upStfPlanData);
    }

    // データ更新(従業員実績)
    if ($upStfRcdData) {
        $res = upsert($loginUser, 'dat_staff_record', $upStfRcdData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        // ログテーブルに登録する
        setEntryLog($upStfRcdData);
    }

    // 看多機記録ボタン
    if ($btnKtk) {

        // 新規作成
        if ($upKtkData) {

            $userId = $upKtkData['user_id'];

            // 看多機記録作成
            $res = upsert($loginUser, 'doc_kantaki', $upKtkData);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }

            // ログテーブルに登録する
            setEntryLog($upKtkData);

            // 記録ID
            $ktkId = $res;

            // 予定情報更新
            $upUserPlanData = array();
            $upUserPlanData['unique_id'] = $btnKtk;
            $upUserPlanData['kantaki']   = $ktkId;
            $res = upsert($loginUser, 'dat_user_plan', $upUserPlanData);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }

            // ログテーブルに登録する
            setEntryLog($upUserPlanData);
        }

        // 記録画面へ遷移
        $_SESSION['return_url'] = '/record/staff/index.php';
        header("Location:" . '/report/kantaki/index.php?id=' . $ktkId . "&user=" . $userId);
        exit;
    }

    // 訪問看護記録Ⅱボタン
    if ($btnHokan2) {

        // 登録済み予定データ
        $planData = array();
        $where    = array();
        $where['unique_id'] = $btnHokan2;
        $planData = getData('dat_user_plan', $where);
        if ($planData) {

            // 利用者ID、看多機ID
            $userId = $planData['user_id'];
            $hkn2Id = $planData['kantaki'];

            // 新規登録
            if (!$hkn2Id) {

                // 訪問看護記録Ⅱ作成
                $res = upsert($loginUser, 'doc_visit2', $upHknData);
                if (isset($res['err'])) {
                    $err[] = 'システムエラーが発生しました';
                    throw new Exception();
                }

                // 記録ID
                $hkn2Id  = $res;

                // ログテーブルに登録する
                setEntryLog($upHknData);

                // 予定情報更新
                $upUserPlanData = array();
                $upUserPlanData['unique_id'] = $btnHokan2;
                $upUserPlanData['kantaki']   = $hkn2Id;
                $res = upsert($loginUser, 'dat_user_plan', $upUserPlanData);
                if (isset($res['err'])) {
                    $err[] = 'システムエラーが発生しました';
                    throw new Exception();
                }

                // ログテーブルに登録する
                setEntryLog($upUserPlanData);
            }

            // 記録画面へ遷移
            $_SESSION['return_url'] = '/record/staffS/index.php';
            header("Location:" . '/report/visit2/index.php?id=' . $hkn2Id . '&user=' . $userId);
            exit;
        }
    }

    /* ===================================================
    * イベント後処理(描画用データ作成)
    * ===================================================
    */

    /* -- データ取得 --------------------------------------------*/

    /* -- 従業員予定 -----------------------------*/
    $where = array();
    $where['delete_flg'] = 0;
    // スタッフ
    if ($search['staff_id']) {
        $where['staff_id'] = $search['staff_id'];
    }
    // 日付(From)絞込条件設定
    if (!empty($search['date_from'])) {
        $where['target_day >='] = $search['date_from'];
    }
    // 日付(To)絞込条件設定
    if (!empty($search['date_to'])) {
        $where['target_day <='] = $search['date_to'];
    }
    $temp = select('dat_staff_plan', '*', $where);
    foreach ($temp as $val) {

        // 実績未のみ表示
        if ($search['search1']) {
            if ($val['status'] === '実施') {
                continue;
            }
        }

        // キャンセルを含む
        if (!$search['search3']) {
            if ($val['status'] === 'キャンセル') {
                continue;
            }
        }

        // 従業員ID、利用日、計画ID
        $stfId   = $val['staff_id'];
        $tgtDay  = $val['target_day'];
        $planId  = $val['unique_id'];

        // 曜日、日付、更新日、更新者名
        $week = formatDateTime($tgtDay, 'w');
        $weekDisp = '(' . $weekAry[$week] . ')';
        //    $val['target_wareki'] = formatDateTime($useDay, 'Y年m月d日') . $weekDisp;
        $val['use_from_day'] = formatDateTime($tgtDay, 'Y年m月d日') . $weekDisp;
        $val['update_date'] = formatDateTime($val['update_date'], 'Y-m-d H:i');
        $val['update_name']   = getStaffName($val['update_user']);

        //開始時刻、終了時刻
        $time = $val['start_time'];
        $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
        $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

        // 格納
        $stfPlanIds[] = $planId;
        $stfPlanInfo[$planId] = $val;
        $planList[$tgtDay][$time][$planId]['type'] = 'staff';
        $planList[$tgtDay][$time][$planId]['main'] = $val;
    }

    // 利用者予定（親）
    $where = array();
    $where['delete_flg'] = 0;
    // スタッフ
    //$where['staff_id'] = $search['staff_id'];
    // 日付(From)絞込条件設定
    if (!empty($search['date_from'])) {
        $where['use_day >='] = $search['date_from'];
    }
    // 日付(To)絞込条件設定
    if (!empty($search['date_to'])) {
        $where['use_day <='] = $search['date_to'];
    }
    $orderBy = 'use_day ASC, start_time ASC';
    $temp = select('dat_user_plan', '*', $where, $orderBy);
    foreach ($temp as $val) {

        // キャンセルを含む
        if (!$search['search3']) {
            if ($val['status'] == 'キャンセル') {
                continue;
            }
        }

        // 実績未のみ表示
        if ($search['search1']) {
            if ($val['status'] == '実施') {
                continue;
            }
        }

        // 利用者ID、利用日、計画ID
        $userId = $val['user_id'];
        $useDay = $val['use_day'];
        $planId = $val['unique_id'];

        // 曜日、日付、更新日、更新者名
        $week = formatDateTime($useDay, 'w');
        $weekDisp = '(' . $weekAry[$week] . ')';
        $val['use_from_day'] = formatDateTime($useDay, 'Y年m月d日') . $weekDisp;
        $val['update_date'] = formatDateTime($val['update_date'], 'Y-m-d H:i');
        $val['update_name']  = getStaffName($val['update_user']);

        //開始時刻、終了時刻
        $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
        $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

        // 対応者
        $val['staff_name'] = !empty($val['staff_id']) ? getStaffName($val['staff_id']) : "";

        //基本サービスコード、基本サービス名称
        $tgtSvc = getServiceConfig($val['service_id']);
        $val['base_service_code'] = $tgtSvc['code'];
        $val['base_service_name'] = $tgtSvc['name'];
        $val['base_service'] = isset($svcInfo[$val['service_id']]) ? $tgtSvc['name'] . '(' . $tgtSvc['code'] . ')' : '';

        // 加減算名称（空）
        $val['add_name'] = null;

        // 格納
        $planIds[] = $planId;
        $planInfo[$planId] = $val;
        //    $planList[$useDay][$time][$planId]['main'] = $val;
    }

    // 利用者予定（サービス詳細）
    $where = array();
    $where['delete_flg']   = 0;
    $where['user_plan_id'] = $planIds;
    $where['staff_id'] = $search['staff_id'];
    $temp = select('dat_user_plan_service', '*', $where);
    foreach ($temp as $val) {

        // キャンセルを含む
        if (!$search['search3']) {
            if ($val['status'] == 'キャンセル') {
                continue;
            }
        }

        // 実績未のみ表示
        if ($search['search1']) {
            if ($val['status'] == '実施') {
                continue;
            }
        }

        // 更新日、更新者名称
        $val['update_date'] = formatDateTime($val['update_date'], 'Y-m-d H:i');
        $val['update_user'] = !empty($val['update_user']) ? $val['update_user'] : "";
        $val['update_name'] = !empty($val['update_user']) ? getStaffName($val['update_user']) : "";

        // 計画情報
        $planId  = $val['user_plan_id'];
        $tgtPlan = $planInfo[$planId];

        // 利用者ID、利用日、サービス詳細ID
        $val['user_id'] = $tgtPlan['user_id'];
        $stfId = $tgtPlan['staff_id'];
        $tgtDay = $tgtPlan['use_day'];
        $planSvcId = $val['unique_id'];

        // ユーザー情報取得
        $userInfo = getUserInfo($val['user_id']);

        // 対応者
        $val['staff_name'] = !empty($val['staff_id']) ? getStaffName($val['staff_id']) : "";
        $val['user_name']  = isset($userInfo['user_name']) ? $userInfo['user_name'] : '';

        //開始時刻、終了時刻
        $time = $val['start_time'];
        $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
        $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

        // 子に利用日とkantaki情報を渡す
        $val['use_day'] = $tgtPlan['use_day'];
        $val['kantaki'] = !empty($tgtPlan['kantaki']) ? $tgtPlan['kantaki'] : "";

        $val['service_name'] = isset($svcDtlInfo[$val['service_detail_id']]['name'])
            ? $svcDtlInfo[$val['service_detail_id']]['name']
            : "";

        // 基本サービス名
        $val['sv_name'] = $tgtPlan['service_name'];

        // 格納
        $planList[$tgtDay][$time][$planId]['type'] = 'user';
        $planList[$tgtDay][$time][$planId]['main'][$planSvcId] = $val;
    }

    // WB判定
    foreach ($planList as $tgtDay => $planList2) {
        foreach ($planList2 as $time => $planList3) {
            foreach ($planList3 as $planId => $planList4) {
                if ($planList4['type'] !== 'user') {
                    continue;
                }
                foreach ($planList4['main'] as $planSvcId => $planList5) {
                    // 訪問看護以外はSKIP
                    if (mb_strpos($planList5['sv_name'], "訪問看護") === false) {
                        continue;
                    }
                    $stTime = $planList5['start_time'];
                    $edTime = $planList5['end_time'];

                    $tgtList = $planList[$tgtDay];
                    foreach ($tgtList as $time2 => $tgtList2) {
                        foreach ($tgtList2 as $pId => $tgtList3) {
                            foreach ($tgtList3['main'] as $sId => $tgtList4) {
                                $dat = $tgtList4;
                                // 自分自身はSKIP
                                if ($planList5['unique_id'] === $tgtList4['unique_id']) {
                                    continue;
                                }

                                if (($dat['start_time'] <= $stTime && $dat['end_time'] > $stTime)
                                  || ($dat['start_time'] < $edTime && $dat['end_time'] >= $edTime)
                                  || ($dat['start_time'] >= $stTime && $dat['end_time'] <= $edTime)) {
                                    // 重複HIT
                                    $planList[$tgtDay][$time][$planId]['main'][$planSvcId]['WB'] = 'WB';
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /* -- 実績情報 ----------------------------*/

    // 従業員実績
    if ($stfPlanIds) {
        $where = array();
        $where['delete_flg']   = 0;
        $where['staff_plan_id'] = $stfPlanIds;
        $temp = select('dat_staff_record', '*', $where);
        foreach ($temp as $val) {

            // 従業員ID、利用日、実績ID
            $stfId  = $val['staff_id'];
            $tgtDay = $val['target_day'];
            $rcdId  = $val['unique_id'];

            //更新日、更新者
            $val['update_date'] = formatDateTime($val['update_date'], 'Y-m-d H:i');
            $val['update_name']  = getStaffName($val['update_user']);

            //開始時刻、終了時刻
            $time = $val['start_time'];
            $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
            $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

            // 対応者
            $val['staff_name'] = !empty($val['staff_id']) ? getStaffName($val['staff_id']) : "";

            // 格納
            $rcdList[$tgtDay][$time][$rcdId]['type'] = 'staff';
            $rcdList[$tgtDay][$time][$rcdId]['main'] = $val;
        }
    }
    // 利用者実績(親)
    if ($planIds) {
        $where = array();
        $where['delete_flg']   = 0;
        $where['user_plan_id'] = $planIds;
        $temp = select('dat_user_record', '*', $where);
        foreach ($temp as $val) {

            // 利用者ID、利用日、実績ID
            $userId = $val['user_id'];
            $useDay = $val['use_day'];
            $rcdId  = $val['unique_id'];

            //更新日、更新者
            $val['update_date'] = formatDateTime($val['update_date'], 'Y-m-d H:i');
            $val['update_name']  = getStaffName($val['update_user']);

            //開始時刻、終了時刻
            $time = $val['start_time'];
            $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
            $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

            // 対応者
            $val['staff_name'] = !empty($val['staff_id']) ? getStaffName($val['staff_id']) : "";

            //基本サービスコード、基本サービス名称
            $tgtSvc = getServiceConfig($val['service_id']);
            $val['base_service_code'] = $tgtSvc['code'];
            $val['base_service_name'] = $tgtSvc['name'];

            // 格納
            $rcdIds[] = $rcdId;
            $rcdInfo[$rcdId] = $val;
            //    $rcdList[$useDay][$time]['main'] = $val;

        }
    }

    // 利用者実績（サービス詳細）
    if ($rcdIds) {
        $where = array();
        $where['delete_flg'] = 0;
        $where['user_record_id'] = $rcdIds;
        $temp = select('dat_user_record_service', '*', $where);
        foreach ($temp as $val) {

            // 更新日、更新者名称
            $val['update_date'] = formatDateTime($val['update_date'], 'Y-m-d H:i');
            $val['update_user'] = !empty($val['update_user']) ? $val['update_user'] : "";
            $val['update_name'] = !empty($val['update_user']) ? getStaffName($val['update_user']) : "";

            // 実績情報
            $rcdId  = $val['user_record_id'];
            $tgtRcd = $rcdInfo[$rcdId];

            // 利用者ID、利用日、サービス詳細ID
            $val['user_id'] = $tgtRcd['user_id'];
            $stfId = $tgtRcd['staff_id'];
            $tgtDay = $tgtRcd['use_day'];
            $rcdSvcId = $val['unique_id'];

            //開始時刻、終了時刻
            $time = $val['start_time'];
            $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
            $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

            $val['service_name'] = isset($svcDtlInfo[$val['service_detail_id']]['name'])
                ? $svcDtlInfo[$val['service_detail_id']]['name']
                : "";

            // ユーザー情報取得
            $userInfo = getUserInfo($val['user_id']);

            // 対応者、利用者
            $val['staff_name'] = !empty($val['staff_id']) ? getStaffName($val['staff_id']) : "";
            $val['user_name']  = isset($userInfo['user_name']) ? $userInfo['user_name'] : '';

            // 格納
            $rcdList[$tgtDay][$time][$rcdId]['type'] = 'user';
            $rcdList[$tgtDay][$time][$rcdId]['main'][$rcdSvcId] = $val;
        }
    }

    // 事業所リスト取得
    $oficeIds = array();
    foreach ($ofcList as $id => $val) {
        $oficeIds[] = $val['unique_id'];
    }

    $userCnt = 0;
    $planIds2 = array();
    $where = array();
    $where['delete_flg'] = 0;
    $where['use_day']    = TODAY;
    $where['office_id']  = $oficeIds;
    $temp = select('dat_user_plan', '*', $where);
    foreach ($temp as $val) {
        $planIds2[] = $val['unique_id'];
    }

    $where = array();
    $where['delete_flg']   = 0;
    $where['user_plan_id'] = $planIds2;
    $where['staff_id']     = $search['staff_id'];
    $temp = select('dat_user_plan_service', '*', $where);
    foreach ($temp as $val) {
        if ($val['status'] === '実施' || $val['status'] === 'キャンセル') {
            continue;
        }
        $userCnt++;
    }

    $staffCnt = 0;
    $where = array();
    $where['delete_flg'] = 0;
    $where['target_day'] = TODAY;
    $where['staff_id'] = $search['staff_id'];
    $temp = select('dat_staff_plan', '*', $where);
    foreach ($temp as $val) {
        if ($val['status'] === '実施' || $val['status'] === 'キャンセル') {
            continue;
        }
        $staffCnt++;
    }

    $unRec = $userCnt + $staffCnt;

    /* -- 看多機記録取得 -----------------------------*/
    foreach ($planList as $tgtDay => $planList2) {
        foreach ($planList2 as $time => $planList3) {
            foreach ($planList3 as $planId => $planList4) {
                if ($planList4['type'] === "staff") {
                    continue;
                }
                foreach ($planList4['main'] as $planSvcId => $val) {
                    $dat = $val;

                    $dat['disable'] = "readonly";
                    if ($dat['kantaki']) {
                        if (mb_strpos($dat['service_name'], '訪問看護') === false) {
                            $table = 'doc_kantaki';
                            $where = array();
                            $where['unique_id'] = $dat['kantaki'];
                            $docData = getData($table, $where);
                            if ($docData['status'] == '完成') {
                                $dat['disable'] = "";
                            }
                        } else {
                            $dat['disable'] = "";
                        }
                    }
                    $planList[$tgtDay][$time][$planId]['main'][$planSvcId] = $dat;
                }
            }
        }
    }


    // 予定ソート
    ksort($planList);
    foreach ($planList as $tgtDay => $val) {
        ksort($planList[$tgtDay]);
    }
    $dispPlan = $planList;

    // 実績ソート
    ksort($rcdList);
    foreach ($rcdList as $tgtDay => $val) {
        ksort($rcdList[$tgtDay]);
    }
    $dispRcd  = $rcdList;

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
