<?php

//=====================================================================
// 利用者予定実績
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
    $dispData = array();
    $tgtData  = array();
    $userIds  = null;
    $userList = array();
    $planIds  = null;
    $planList = array();
    $planInfo = array();
    $rcdIds   = null;
    $rcdList  = array();
    $rcdInfo  = array();
    $upPlan   = array();
    $upRecord = array();
    $typeList = array();
    $addInfo  = array();
    $addInfo2 = array();
    $svcName  = array();
    $upAddSpn = array();

    $upUserPlanData    = array();
    $upUserPlanAddData = array();
    $upUserPlanJpiData = array();
    $upUserPlanSvcData = array();
    $upUserRcdData     = array();
    $upUserRcdAddData  = array();
    $upUserRcdJpiData  = array();
    $upUserRcdSvcData  = array();
    $upAddSpnData      = array();
    $upAddSpnDel        = array();
    $upKtkData         = array();
    $upHknData         = array();

    // 表示件数
    $line = 2000;

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 拠点ID
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
    $placeMst = array();
    $placeMst = getPlaceInfo($placeId);


    // 検索ボタン
    $btnSearch = h(filter_input(INPUT_POST, 'btnSearch'));

    // 検索配列
    $userId = filter_input(INPUT_GET, 'user');
    if (!$userId) {
        $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
    }
    $paramUser = array();
    $paramUser['other_id'] = null;
    $paramUser['user_name'] = null;
    if ($userId) {
        $paramUser = getUserInfo($userId);
    }

    // 検索配列
    $search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $search['user_range'] = !empty($search['user_range']) ? $search['user_range'] : null;
    $search['date_from']  = !empty($search['date_from']) ? $search['date_from'] : TODAY;
    $search['date_to']    = !empty($search['date_to']) ? $search['date_to'] : TODAY;
    $search['search1']    = !empty($search['search1']) ? $search['search1'] : null;
    $search['search2']    = !empty($search['search2']) ? $search['search2'] : null;
    $search2Str = !empty($search['search2']) ? implode('^', $search['search2']) : null;
    $search['search3']    = !empty($search['search3']) ? $search['search3'] : null;

    if (!$btnSearch) {
        if (isset($_SESSION['record_user_search'])) {
            $search = $_SESSION['record_user_search'];
        } else {
            $search['search1'] = '実績未のみ';
            $search['staff_id'] = $loginUser['staff_id'];

            $search['other_id'] = $paramUser['other_id'];
            $search['user_id'] = $userId;
            $search['user_name'] = $paramUser['user_name'];
        }
    } else {
        $_SESSION['record_user_search'] = $search;
    }
    if ($search['other_id'] == '') {
        $search['user_id'] = '';
        $search['user_name'] = '';
        $search['user_range'] = null;
    }
    $_SESSION['user'] = $search['user_id'];
    // サービス内容の表示内容
    $typeList['訪問看護　介護保険']['name']  = '訪問看護<br>介護保険';
    $typeList['訪問看護　介護保険']['class'] = 'cate cate1';
    $typeList['訪問看護　医療保険']['name']  = '訪問看護<br>医療保険';
    $typeList['訪問看護　医療保険']['class'] = 'cate cate2';
    $typeList['訪問看護　定期巡回']['name']  = '訪問看護<br>定期巡回';
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


    // 更新配列(期間指定加減算)
    $upAddSpn = filter_input(INPUT_POST, 'upAddSpn', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upAddSpn = $upAddSpn ? $upAddSpn : array();

    // 更新配列(看多機記録用)
    //  利用者予定ごとにhiddenで以下の項目を格納
    //   $upKtk[予定KEY]['unique_id']   = 連携用記録ID       ← "kantaki"
    //   $upKtk[予定KEY]['service_day'] = 日付(yyyy-mm-dd)   ← "use_day"
    //   $upKtk[予定KEY]['start_time']  = 開始時刻(hh:mm:ss) ← "start_time"
    //   $upKtk[予定KEY]['end_time']    = 終了時刻(hh:mm:ss) ← "end_time"
    $upKtk = filter_input(INPUT_POST, 'upKtk', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upKtk = $upKtk ? $upKtk : array();


    // 新規ボタン-利用者予定
    $btnNewUserPlan = h(filter_input(INPUT_POST, 'btnNewUserPlan'));


    // 編集保存ボタン-利用者予定
    $btnEditUserPlan = h(filter_input(INPUT_POST, 'btnEditUserPlan'));

    // 編集保存ボタン-利用者実績
    $btnEditUserRcd = h(filter_input(INPUT_POST, 'btnEditUserRcd'));


    // 実績確定ボタン-利用者予定
    $btnFixUser = h(filter_input(INPUT_POST, 'btnFixUser'));

    // 実績確定ボタン-利用者予定サービス
    $btnFixUserSvc = h(filter_input(INPUT_POST, 'btnFixUserSvc'));


    // 実績変更ボタン-利用者予定サービス
    $btnChgUserRcd = h(filter_input(INPUT_POST, 'btnChgUserRcd'));


    // キャンセルボン-利用者予定
    $btnCxlUser = h(filter_input(INPUT_POST, 'btnCxlUser'));

    // キャンセルボン-利用者予定サービス
    $btnCxlUserSvc = h(filter_input(INPUT_POST, 'btnCxlUserSvc'));


    // 削除ボタン-利用者予定
    $btnDelUserPlan = h(filter_input(INPUT_POST, 'btnDelUserPlan'));

    // 削除ボタン-利用者予定サービス
    $btnDelUserPlanSvc = h(filter_input(INPUT_POST, 'btnDelUserPlanSvc'));


    // 予定戻しボタン-利用者事績
    $btnDelUserRcd = h(filter_input(INPUT_POST, 'btnDelUserRcd'));

    // 予定戻しボタン-利用者事績サービス
    $btnDelUserRcdSvc = h(filter_input(INPUT_POST, 'btnDelUserRcdSvc'));


    // 期間指定加減算保存
    $btnEntrySpn = h(filter_input(INPUT_POST, 'btnEntrySpn'));


    // 看多機ボタン(予定のKEY)
    $btnKtk = h(filter_input(INPUT_POST, 'btnKantaki'));

    // 看多機ボタン(予定のKEY)
    $btnHokan2 = h(filter_input(INPUT_POST, 'btnHokan2'));

    /*-- その他パラメータ ---------------------------------------*/


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

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 更新用配列作成 ----------------------------------------*/

    // 新規ボタン-利用者予定
    if ($btnNewUserPlan) {
        $upUserPlanData = $upUserPlan[0];
        $tgtDat         = $upUserPlan[0]['base_service'] ? $upUserPlan[0]['base_service'] : 'dummy';
        $tgtAry         = explode('(', $tgtDat);
        $tgtName        = $tgtAry[0];
        $svcId          = isset($svcName[$tgtName]) ? $svcName[$tgtName] : null;
        $upUserPlanData['service_id']   = $svcId;
        //$upUserPlanData['service_name'] = $svcId && isset($svcInfo[$svcId]['type']) ? $svcInfo[$svcId]['type'] : NULL;
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

        // 予定(親)
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

        // 登録済み子データの一旦削除
        $where = array();
        $where['user_plan_id'] = $btnEditUserPlan;
        $temp = select('dat_user_plan_add', 'unique_id', $where);
        foreach ($temp as $val) {
            $tgtId = $val['unique_id'];
            $dat = array();
            $dat['unique_id']  = $tgtId;
            $dat['delete_flg'] = 1;
            $upUserPlanAddData[$tgtId] = $dat;
        }
        $temp = select('dat_user_plan_jippi', 'unique_id', $where);
        foreach ($temp as $val) {
            $tgtId = $val['unique_id'];
            $dat = array();
            $dat['unique_id']  = $tgtId;
            $dat['delete_flg'] = 1;
            $upUserPlanJpiData[$tgtId] = $dat;
        }
        $temp = select('dat_user_plan_service', 'unique_id', $where);
        foreach ($temp as $val) {
            $tgtId = $val['unique_id'];
            $dat = array();
            $dat['unique_id']  = $tgtId;
            $dat['delete_flg'] = 1;
            $upUserPlanSvcData[$tgtId] = $dat;
        }

        // 加減算(子)
        if (isset($upUserPlanAdd[$btnEditUserPlan])) {
            foreach ($upUserPlanAdd[$btnEditUserPlan] as $idx => $val) {
                $upUserPlanAddData[$idx] = $val;
            }
        }

        // 実費(子)
        if (isset($upUserPlanJpi[$btnEditUserPlan])) {
            foreach ($upUserPlanJpi[$btnEditUserPlan] as $idx => $val) {
                $dat = $val;
                $uinsId = !empty($val['uninsure_id']) ? $val['uninsure_id'] : 'dummy';
                $dat['name'] = isset($unInsInfo[$uinsId]['name']) ? $unInsInfo[$uinsId]['name'] : null;
                $upUserPlanJpiData[$idx] = $dat;
            }
        }

        // サービス詳細(子)
        if (isset($upUserPlanSvc[$btnEditUserPlan])) {
            foreach ($upUserPlanSvc[$btnEditUserPlan] as $idx => $val) {
                $dat = $val;
                $dat['start_time'] = $dat['start_time_h'] . ":" . $dat['start_time_m'];
                $dat['end_time']   = $dat['end_time_h'] . ":" . $dat['end_time_m'];
                unset($dat['start_time_h']);
                unset($dat['start_time_m']);
                unset($dat['end_time_h']);
                unset($dat['end_time_m']);
                $upUserPlanSvcData[$idx] = $dat;
            }
        }
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

        // 予定のステータスを「実施」に変更する
        // 予定情報
        $tgtPlan = array();
        $where = array();
        $where['unique_id'] = $userPlanId;
        $temp = select('dat_user_plan', '*', $where);
        $tgtPlan = $temp[0];

        // 予定(親)：更新配列作成
        $dat = array();
        $dat['unique_id']      = $tgtPlan['unique_id'];
        $dat['protection_flg'] = 1;
        $dat['status']         = '実施';
        $upUserPlanData = $dat;

        // サービス
        $where = array();
        $where['user_plan_id'] = $userPlanId;
        $where['delete_flg']   = 0;
        $temp = select('dat_user_plan_service', '*', $where);
        foreach ($temp as $val) {
            $dat = array();
            $dat['unique_id'] = $val['unique_id'];
            $dat['protection_flg'] = 1;
            $dat['status']         = '実施';
            $upUserPlanSvcData[] = $dat;
        }
    }

    // 編集保存ボタン-利用者実績
    if ($btnEditUserRcd) {
        if ($btnEditUserRcd === 0) {
            unset($upUserRcdAdd[$btnEditUserRcd]['unique_id']);
            //       $btnEditUserRcd = 0;
        }

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

        $upUserRcdData     = $upUserRcd[$btnEditUserRcd];
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

    // 実績確定ボタン-利用者予定
    if ($btnFixUser) {

        // 予定情報
        $where = array();
        $where['unique_id']  = $btnFixUser;
        $where['delete_flg'] = 0;
        $temp = select('dat_user_plan', '*', $where);
        $tgtPlan = $temp[0];

        // 利用者実績
        $dat = array();
        $dat['unique_id']        = $tgtPlan['unique_id'];
        $dat['protection_flg']   = 1;
        $dat['status']           = '実施';
        $upUserPlanData          = $dat;
        $tgtPlan['user_plan_id'] = $tgtPlan['unique_id'];
        $upUserRcdData  = $tgtPlan;
        unset($upUserRcdData['unique_id']);
        unset($upUserRcdData['protection_flg']);
        unset($upUserRcdData['status']);
        unset($upUserRcdData['schedule_id']);
        unset($upUserRcdData['kantaki']);
        unset($upUserRcdData['kantaki2']);

        // 子の実績があったら2レコードできてしまうためレコード存在チェック
        $where = array();
        $where['user_plan_id'] = $btnFixUser;
        $where['delete_flg']   = 0;
        $temp = select('dat_user_record', '*', $where);
        if (!empty($temp)) {
            $upUserRcdData['unique_id'] = $temp[0]['unique_id'];
        }

        // 加減算
        $where = array();
        $where['user_plan_id'] = $btnFixUser;
        $where['delete_flg']   = 0;
        $temp = select('dat_user_plan_add', '*', $where);
        foreach ($temp as $val) {
            unset($val['unique_id']);
            unset($val['user_plan_id']);
            $upUserRcdAddData[] = $val;
        }
        // 実費
        $where = array();
        $where['user_plan_id'] = $btnFixUser;
        $where['delete_flg']   = 0;
        $temp = select('dat_user_plan_jippi', '*', $where);
        foreach ($temp as $val) {
            unset($val['unique_id']);
            unset($val['user_plan_id']);
            $upUserRcdJpiData[] = $val;
        }
        // サービス
        $where = array();
        $where['user_plan_id'] = $btnFixUser;
        $where['delete_flg']   = 0;
        $temp = select('dat_user_plan_service', '*', $where);
        foreach ($temp as $val) {
            $dat = $val;
            $val['delete_flg'] = 1;
            $val['protection_flg'] = 1;
            $val['status']         = '実施';
            $upUserPlanSvcData[]   = $val;

            $dat['user_plan_service_id'] = $dat['unique_id'];
            unset($dat['unique_id']);
            unset($dat['protection_flg']);
            unset($dat['status']);
            unset($dat['user_plan_id']);
            $upUserRcdSvcData[] = $dat;
        }
    }
    // 実績確定ボタン-利用者予定サービス
    if ($btnFixUserSvc) {

        // 子の予定情報
        $where = array();
        $where['unique_id'] = $btnFixUserSvc;
        $temp = select('dat_user_plan_service', '*', $where);
        if (isset($temp[0])) {

            // 親の予定ID
            $svcData = $temp[0];
            $planId  = $svcData['user_plan_id'];

            // 子の予定更新情報
            $dat = array();
            $dat['unique_id']      = $btnFixUserSvc;
            $dat['protection_flg'] = 1;
            $dat['status']         = '実施';
            $upUserPlanSvcData[$btnFixUserSvc] = $dat;

            // 子の実績更新情報
            $dat = $svcData;
            unset($dat['unique_id']);
            unset($dat['delete_flg']);
            unset($dat['create_date']);
            unset($dat['create_user']);
            unset($dat['update_date']);
            unset($dat['update_user']);
            unset($dat['user_plan_id']);
            unset($dat['protection_flg']);
            unset($dat['status']);
            $dat['user_plan_service_id']   = $btnFixUserSvc;
            $upUserRcdSvcData[$btnFixUserSvc] = $dat;

            // 親の実績判定、なければ実績データ作成
            $where = array();
            $where['delete_flg'] = 0;
            $where['user_plan_id'] = $planId;
            $temp = select('dat_user_record', '*', $where);
            if (empty($temp)) {
                $where = array();
                $where['unique_id'] = $planId;
                $temp = select('dat_user_plan', '*', $where);
                if (isset($temp[0])) {
                    $dat = $temp[0];
                    unset($dat['protection_flg']);
                    unset($dat['schedule_id']);
                    unset($dat['kantaki']);
                    unset($dat['kantaki2']);
                    unset($dat['unique_id']);
                    unset($dat['delete_flg']);
                    unset($dat['create_date']);
                    unset($dat['create_user']);
                    unset($dat['update_date']);
                    unset($dat['update_user']);
                    $dat['user_plan_id'] = $planId;
                    $upUserRcdData = $dat;
                }
            } else {
                $rcdId = $temp[0]['unique_id'];
            }
        }
    }

    // キャンセルボタン-利用者予定
    if ($btnCxlUser) {

        // 利用者予定
        $dat = array();
        $dat['unique_id']      = $btnCxlUser;
        $dat['status']         = 'キャンセル';
        $dat['protection_flg'] = 1;
        $upUserPlanData = $dat;

        // 利用者予定サービス
        $where = array();
        $where['user_plan_id'] = $btnCxlUser;
        $temp = select('dat_user_plan_service', '*', $where);
        foreach ($temp as $val) {
            $tgtId = $val['unique_id'];
            $dat = array();
            $dat['unique_id']      = $val['unique_id'];
            $dat['status']         = 'キャンセル';
            $dat['protection_flg'] = 1;
            $upUserPlanSvcData[$tgtId] = $dat;
        }
    }
    // キャンセルボン-利用者予定サービス
    if ($btnCxlUserSvc) {
        $where = array();
        $where['unique_id'] = $btnCxlUserSvc;
        $temp = select('dat_user_plan_service', '*', $where);
        $dat = array();
        $dat['unique_id']      = $temp[0]['unique_id'];
        $dat['status']         = 'キャンセル';
        $dat['protection_flg'] = 1;
        $upUserPlanSvcData[$btnCxlUserSvc] = $dat;

        // サービス全てが予定キャンセルとなった場合
        $where = array();
        $where['user_plan_id'] = $temp[0]['user_plan_id'];
        $temp2 = select('dat_user_plan_service', '*', $where);
        $allFlg = true;
        foreach ($temp2 as $val) {
            if ($val['status'] !== 'キャンセル' && $val['unique_id'] !== $temp[0]['unique_id']) {
                $allFlg = false;
            }
        }
        if ($allFlg) {
            $dat = array();
            $dat['unique_id']      = $temp[0]['user_plan_id'];
            $dat['status']         = 'キャンセル';
            $dat['protection_flg'] = 1;
            $upUserPlanData = $dat;
        }
    }
    // 削除ボタン-利用者予定
    if ($btnDelUserPlan) {

        // 利用者予定
        $dat = array();
        $dat['unique_id']      = $btnDelUserPlan;
        $dat['status']         = 'キャンセル';
        $dat['delete_flg'] = 1;
        $dat['protection_flg'] = 1;
        $upUserPlanData = $dat;

        // 利用者予定サービス
        $where = array();
        $where['user_plan_id'] = $btnDelUserPlan;
        $temp = select('dat_user_plan_service', '*', $where);
        foreach ($temp as $val) {
            $tgtId = $val['unique_id'];
            $dat = array();
            $dat['unique_id']      = $val['unique_id'];
            $dat['delete_flg']     = 1;
            $dat['status']         = 'キャンセル';
            $dat['protection_flg'] = 1;
            $upUserPlanSvcData[$tgtId] = $dat;
        }
    }
    // 削除ボタン-利用者予定サービス
    if ($btnDelUserPlanSvc) {
        $where = array();
        $where['unique_id'] = $btnDelUserPlanSvc;
        $temp = select('dat_user_plan_service', '*', $where);
        $dat = array();
        $dat['unique_id']      = $temp[0]['unique_id'];
        $dat['status']         = 'キャンセル';
        $dat['delete_flg']     = 1;
        $dat['protection_flg'] = 1;
        $upUserPlanSvcData[$btnDelUserPlanSvc] = $dat;
    }
    // 予定戻しボタン-利用者実績
    if ($btnDelUserRcd) {

        // 登録済み実績
        $where = array();
        $where['unique_id'] = $btnDelUserRcd;
        $temp = select('dat_user_record', '*', $where);
        $rcdData = $temp[0];
        $planId = $rcdData['user_plan_id'];

        // 更新配列 実績
        $dat = array();
        $dat['unique_id']   = $btnDelUserRcd;
        $dat['delete_flg']  = 1;
        $upUserRcdData      = $dat;

        // 更新配列 予定
        $dat = array();
        $dat['unique_id']      = $planId;
        $dat['delete_flg']     = 0;
        $dat['status']         = null;
        $dat['protection_flg'] = null;
        $upUserPlanData        = $dat;

        // 登録済み実績サービス(削除)
        $where = array('user_record_id' => $btnDelUserRcd);
        $tempRecord = select('dat_user_record_service', '*', $where);
        foreach ($tempRecord as $record) {
            $datRecord = array();
            $datRecord['unique_id'] = $record['unique_id'];
            $datRecord['delete_flg'] = 1;
            $upUserRcdSvcData[] = $datRecord;
        }
        // 更新配列 予定サービス(復活)
        $where = array('user_plan_id' => $planId);
        $tempService = select('dat_user_plan_service', '*', $where);
        foreach ($tempService as $service) {
            $datService = array();
            $datService['unique_id'] = $service['unique_id'];
            $datService['delete_flg'] = 0;
            $datService['status'] = null;
            $datService['protection_flg'] = null;
            $upUserPlanSvcData[] = $datService;
        }
    }
    // 予定戻しボタン-利用者実績サービス
    if ($btnDelUserRcdSvc) {

        // 登録済み実績サービス
        $where = array();
        $where['unique_id'] = $btnDelUserRcdSvc;
        $temp = select('dat_user_record_service', '*', $where);
        $svcRcd = $temp[0];
        $planId = $svcRcd['user_plan_service_id'];

        // 更新配列 実績サービス
        $dat = array();
        $dat['unique_id']   = $btnDelUserRcdSvc;
        $dat['delete_flg']  = 1;
        $upUserRcdSvcData[] = $dat;

        // 更新配列 予定サービス
        $dat = array();
        $dat['unique_id']      = $planId;
        $dat['delete_flg']     = 0;
        $dat['status']         = null;
        $dat['protection_flg'] = null;
        $upUserPlanSvcData[]   = $dat;
        $planId = null;
    }

    // 期間指定 加減算保存
    if ($btnEntrySpn && $upAddSpn) {

        // 利用者
        $userId = $btnEntrySpn;

        // 一旦登録済みデータを削除
        $where = array();
        $where['delete_flg'] = 0;
        $where['user_id']    = $userId;
        $temp = select('dat_user_record_add', 'unique_id', $where);
        foreach ($temp as $val) {
            $val['delete_flg'] = 1;
            $upAddSpnDel[] = $val;
        }

        // 画面からの入力内容
        foreach ($upAddSpn as $val) {
            $val['user_id']    = $userId;
            $val['delete_flg'] = 0;
            $val['add_name'] = !empty($addInfo2[$val['add_id']])
                ? $addInfo2[$val['add_id']]['name']
                : null;
            $upAddSpnData[]  = $val;
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

        // 親の分類名を反映させる
        $res = cnvSvcName($loginUser, $planId);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
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
    //set delete flag for deleted records
    if ($btnEditUserRcd != "") {
        if (isset($rcdId)) {
            $where = (!empty($upUserRcdAddData)) ? ' and unique_id not in ("' . implode('","', array_keys($upUserRcdAddData)) . '")' : '';
            $sql = 'UPDATE dat_user_record_add set delete_flg=1 where user_record_id="' . $rcdId . '" and delete_flg=0 ' . $where;
            customSQL($sql);
        }
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

    // 看多機記録ボタン
    if ($btnKtk) {

        // 登録済み予定データ
        $planData = array();
        $where    = array();
        $where['unique_id'] = $btnKtk;
        $planData = getData('dat_user_plan', $where);
        if ($planData) {

            // 利用者ID、看多機ID
            $userId = $planData['user_id'];
            $ktkId = $planData['kantaki'];

            // 新規登録
            if (!$ktkId) {

                // 看多機記録作成
                $res = upsert($loginUser, 'doc_kantaki', $upKtkData);
                if (isset($res['err'])) {
                    $err[] = 'システムエラーが発生しました';
                    throw new Exception();
                }

                // 記録ID
                $ktkId  = $res;

                // ログテーブルに登録する
                setEntryLog($upKtkData);

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
            $_SESSION['return_url'] = '/record/user/index.php';
            header("Location:" . '/report/kantaki/index.php?id=' . $ktkId . '&user=' . $userId . '&plan=' . $btnKtk);
            exit;
        }
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
                $upHknData['user_id'] = $userId;
                $upHknData['target_plan_id'] = $btnHokan2;
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
            $_SESSION['return_url'] = '/record/user/index.php';
            header("Location:" . '/report/visit2/index.php?id=' . $hkn2Id . '&user=' . $userId);
            exit;
        }
    }

    // 期間指定 加減算保存
    if ($upAddSpnData) {

        // 削除
        if ($upAddSpnDel) {
            $res = multiUpsert($loginUser, 'dat_user_record_add', $upAddSpnDel);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }
        }
        // 更新
        $res = multiUpsert($loginUser, 'dat_user_record_add', $upAddSpnData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upAddSpnData);
    }

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/

    /* -- 利用者予定(親) -----------------------------*/
    $where = array();
    $where['delete_flg'] = 0;
    // 利用者
    if ($search['user_range'] == "利用者で絞り込む") {
        $where['user_id'] = $search['user_id'];
    } else {
        $where['user_id'] = $userIds;
    }
    // 条件2条件設定
    //if (!empty($search['search2'])) {
    //    $where['service_name LIKE'] = $search['search2'];
    //}
    // 予定キャンセル
    //if (empty($search['search3'])) {
    //    $where['status !='] = "キャンセル";
    //}
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

    $userRecords = array();
    if (!empty($search['search1'] ?? '')) {
        $userRecords = select('dat_user_record', '*', $where);
    }

    foreach ($temp as $val) {

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

        // 条件１：予定キャンセル判定
        if (empty($search['search3'])) {
            if ($val['status'] == 'キャンセル') {
                continue;
            }
        }

        // 条件２での判定
        if (!empty($search2Str) && $search2Str != "自費") {
            if (mb_strpos($search2Str, $tgtSvc['type']) === false) {
                continue;
            }
        }

        // 自費条件設定
        if (mb_strpos($search2Str, "自費") !== false) {
            if ($val['jihi_flg'] != 1) {
                continue;
            }
        }


        // 実績未のみなら実績のあるレコードは省く
        $checkRecord = array_filter($userRecords, function ($record) use ($val) {
            return $val['unique_id'] == $record['user_plan_id'];
        });
        if (!empty($checkRecord)) {
            continue;
        }

        // 格納
        $planIds[] = $planId;
        $planInfo[$planId] = $val;
        $planList[$userId][$useDay][$planId]['main'] = $val;

    }

    /* -- その他計画関連 ------------------------------*/
    if (!empty($planIds)) {

        /* -- 予定情報 ----------------------------*/

        // 予定（加減算）
        $where = array();
        $where['delete_flg']   = 0;
        $where['user_plan_id'] = $planIds;
        $temp = select('dat_user_plan_add', '*', $where);
        foreach ($temp as $val) {

            // 計画情報
            $planId  = $val['user_plan_id'];
            $tgtPlan = $planInfo[$planId];

            // 利用者ID、利用日、加減算ID
            $userId = $tgtPlan['user_id'];
            $useDay = $tgtPlan['use_day'];
            $planAddId = $val['unique_id'];
            $val['add_name'] = !empty($val['add_id'])
                ? $addInfo2[$val['add_id']]['name']
                : null;

            // 加減算名称(mainに格納)
            $planList[$userId][$useDay][$planId]['main']['add_name']
                    = !empty($planList[$userId][$useDay][$planId]['main']['add_name'])
                    ? $planList[$userId][$useDay][$planId]['main']['add_name'] . '<br>' . $val['add_name']
                    : $val['add_name'];

            // 格納
            $planList[$userId][$useDay][$planId]['add'][$planAddId] = $val;
        }

        // 予定（実費）
        $where = array();
        $where['delete_flg']   = 0;
        $where['user_plan_id'] = $planIds;
        $temp = select('dat_user_plan_jippi', '*', $where);
        foreach ($temp as $val) {

            // 計画情報
            $planId  = $val['user_plan_id'];
            $tgtPlan = $planInfo[$planId];

            // 利用者ID、利用日、実費ID
            $userId = $tgtPlan['user_id'];
            $useDay = $tgtPlan['use_day'];
            $planJpId = $val['unique_id'];

            // 格納
            $planList[$userId][$useDay][$planId]['jippi'][$planJpId] = $val;
        }

        // 予定（サービス詳細）
        $where = array();
        $where['delete_flg']   = 0;
        $where['user_plan_id'] = $planIds;
        $temp = select('dat_user_plan_service', '*', $where);
        foreach ($temp as $val) {

            // 更新日、更新者名称
            $val['update_date'] = formatDateTime($val['update_date'], 'Y-m-d H:i');
            $val['update_user'] = !empty($val['update_user']) ? $val['update_user'] : "";
            $val['update_name'] = !empty($val['update_user']) ? getStaffName($val['update_user']) : "";

            // 計画情報
            $planId  = $val['user_plan_id'];
            $tgtPlan = $planInfo[$planId];

            // 利用者ID、利用日、サービス詳細ID
            $userId = $tgtPlan['user_id'];
            $useDay = $tgtPlan['use_day'];
            $planSvcId = $val['unique_id'];

            // 最終更新日、対応者
            $val['update_date'] = formatDateTime($val['update_date'], 'H:i');
            $val['staff_name'] = !empty($val['staff_id']) ? getStaffName($val['staff_id']) : "";

            //開始時刻、終了時刻
            $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
            $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

            $val['service_name'] = isset($svcDtlInfo[$val['service_detail_id']]['name'])
                ? $svcDtlInfo[$val['service_detail_id']]['name'] : "";

            // 基本サービス名
            $val['sv_name'] = $tgtPlan['service_name'];

            // 格納
            $planList[$userId][$useDay][$planId]['service'][$planSvcId] = $val;
        }

        // ダブルブッキング判定
        foreach ($planList as $userId => $planList2) {
            foreach ($planList2 as $useDay => $planList3) {
                foreach ($planList3 as $planId => $planList4) {
                    if (!isset($planList4['service'])) {
                        continue;
                    }
                    foreach ($planList4['service'] as $planSvcId => $planList5) {
                        // 訪問看護以外はSKIP
                        if (mb_strpos($planList5['sv_name'], "訪問看護") === false) {
                            continue;
                        }
                        $stTime = $planList5['start_time'];
                        $edTime = $planList5['end_time'];

                        $wbList = $planList[$userId][$useDay];
                        foreach ($wbList as $pId => $wbList2) {
                            if (isset($wbList2['service']) === false) {
                                continue;
                            }
                            foreach ($wbList2['service'] as $sId => $wbList3) {
                                // 自分自身はSKIP
                                if ($planList5['unique_id'] === $wbList3['unique_id']) {
                                    continue;
                                }
                                $dat = $wbList3;
                                if (($dat['start_time'] <= $stTime && $dat['end_time'] > $stTime)
                                  || ($dat['start_time'] < $edTime && $dat['end_time'] >= $edTime)
                                  || ($dat['start_time'] >= $stTime && $dat['end_time'] <= $edTime)) {
                                    // 重複HIT
                                    $planList[$userId][$useDay][$planId]['service'][$planSvcId]['WB'] = 'WB';
                                }
                            }
                        }
                    }
                }
            }
        }

        /* -- 実績情報 ----------------------------*/

        // 実績(親)
        $where = array();
        $where['delete_flg']   = 0;
        $where['user_plan_id'] = $planIds;
        $temp = select('dat_user_record', '*', $where);
        foreach ($temp as $val) {

            // 計画情報
            $planId  = $val['user_plan_id'];
            $tgtPlan = $planInfo[$planId];

            // 利用者ID、利用日、実績ID
            $userId = $tgtPlan['user_id'];
            $useDay = $tgtPlan['use_day'];

            $rcdId  = $val['unique_id'];

            //更新日、更新者
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

            // 格納
            $rcdIds[] = $rcdId;
            $rcdInfo[$rcdId] = $val;
            $rcdList[$userId][$useDay][$rcdId]['main'] = $val;

        }

        // 実績（加減算）
        $where = array();
        $where['delete_flg'] = 0;
        $where['user_record_id'] = $rcdIds;
        $temp = select('dat_user_record_add', '*', $where);
        foreach ($temp as $val) {

            // 実績情報
            $rcdId  = $val['user_record_id'];
            $tgtRcd = $rcdInfo[$rcdId];

            // 利用者ID、利用日、加減算ID
            $userId = $tgtRcd['user_id'];
            $useDay = $tgtRcd['use_day'];
            $rcdAddId = $val['unique_id'];
            $val['add_name'] = !empty($val['add_id'])
                ? $addInfo2[$val['add_id']]['name']
                : null;

            // 加減算名称(mainに格納)
            $rcdList[$userId][$useDay][$rcdId]['main']['add_name']
                    = !empty($rcdList[$userId][$useDay][$rcdId]['main']['add_name'])
                    ? $rcdList[$userId][$useDay][$rcdId]['main']['add_name'] . '<br>' . $val['add_name']
                    : $val['add_name'];

            // 格納
            $rcdList[$userId][$useDay][$rcdId]['add'][$rcdAddId] = $val;
        }

        // 実績（実費）
        $where = array();
        $where['delete_flg'] = 0;
        $where['user_record_id'] = $rcdIds;
        $temp = select('dat_user_record_jippi', '*', $where);
        foreach ($temp as $val) {

            // 実績情報
            $rcdId  = $val['user_record_id'];
            $tgtRcd = $rcdInfo[$rcdId];

            // 利用者ID、利用日、実費ID
            $userId = $tgtRcd['user_id'];
            $useDay = $tgtRcd['use_day'];
            $rcdJpId = $val['unique_id'];

            // 格納
            $rcdList[$userId][$useDay][$rcdId]['jippi'][$rcdJpId] = $val;
        }

        // 実績（サービス詳細）
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
            $userId = $tgtRcd['user_id'];
            $useDay = $tgtRcd['use_day'];
            $rcdSvcId = $val['unique_id'];

            //開始時刻、終了時刻
            $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
            $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

            // 対応者
            $val['staff_name'] = !empty($val['staff_id']) ? getStaffName($val['staff_id']) : "";

            // 既存のSERVICE_NAMEを潰しているので要修正
            $val['service_name'] = isset($svcDtlInfo[$val['service_detail_id']]['name'])
                ? $svcDtlInfo[$val['service_detail_id']]['name']
                : "";

            // 格納
            $rcdList[$userId][$useDay][$rcdId]['service'][$rcdSvcId] = $val;
        }
    }

    /* -- 看多機記録取得 -----------------------------*/
    foreach ($planList as $userId => $planList2) {
        foreach ($planList2 as $useDay => $planList3) {
            foreach ($planList3 as $planId => $val) {
                $dat = $val['main'];

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
                $planList[$userId][$useDay][$planId]['main'] = $dat;
            }
        }
    }

    // 未実施数、現表示数
    $unRoot[1] = 0;
    $unRoot[2] = 0;
    foreach ($planList as $userId => $planList2) {
        foreach ($planList2 as $useDay => $planList3) {
            foreach ($planList3 as $planId => $val) {
                $unRoot[2]++;
                if (empty($val['main']['status'])) {
                    $unRoot[1]++;
                }
            }
        }
    }

    $dispPlan = $planList;
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
