<?php

//=====================================================================
// ルート表
//=====================================================================
try {
    /* ===================================================
    * 初期処理
    * ===================================================
    */

    /*--共通ファイル呼び出し-------------------------------------*/
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

    // 初期化
    $err         = array();
    $_SESSION['notice']['error']   = array();
    $dispData    = array();
    $tgtData     = array();
    $userIds     = array();
    $userList    = array();
    $planIds     = array();
    $planList    = array();
    $planInfo    = array();
    $addInfo     = array();
    $jpiInfo     = array();
    $upPlan      = array();
    $upAdd       = array();
    $upJippi     = array();
    $upSvc       = array();
    $upStf       = array();
    $upPlanData  = array();
    $upStfData   = array();
    $dtlData     = array();
    $upRoot      = array();
    $upRootData  = array();
    $rootMst     = array();
    $rootIds     = array();
    $officeIds   = array();
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

    $upUserPlanData    = array();
    $upUserPlanAddData = array();
    $upUserPlanJpiData = array();
    $upUserPlanSvcData = array();
    $upUserRcdData     = array();
    $upUserRcdAddData  = array();
    $upUserRcdJpiData  = array();
    $upUserRcdSvcData  = array();
    $upKtkData         = array();
    $upRootUserSvcData = array();

    // ルート件数
    $planCount = 0;
    $stfCount = 0;
    $unRoot = 0;
    $hitRoot[1] = 0;
    $hitRoot[2] = 0;

    // 時間軸リストの初期値設定
    $timeScaleList = [
        '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'
    ];

    /* ===================================================
     * 入力情報取得
     * ===================================================
    */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 拠点ID
    $placeId = filter_input(INPUT_GET, 'place');
    if (!$placeId) {
        $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
    }

    // 利用者ID
    $userId = filter_input(INPUT_GET, 'user');
    if (!$userId) {
        $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    // 対象日 ※デフォルト当日
    $tgtDay = filter_input(INPUT_POST, 'day');
    if (!$tgtDay) {
        $tgtDay = TODAY;
    }

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

    $selHour = ['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];
    $selMinutes = ['00','05','10','15','20','25','30','35','40','45','50','55'];

    /*-- 更新用パラメータ ---------------------------------------*/

    /*-- 利用者 ----------------------------------*/

    // 更新配列(利用者-計画)
    $upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upAry = $upAry ? $upAry : array();

    // 更新配列(利用者-加減算)
    $upAdd = filter_input(INPUT_POST, 'upAdd', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upAdd = $upAdd ? $upAdd : array();

    // 更新配列(利用者-実費)
    $upJippi = filter_input(INPUT_POST, 'upJippi', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upJippi = $upJippi ? $upJippi : array();

    // 更新配列(利用者-サービス詳細)
    $upSvc = filter_input(INPUT_POST, 'upSvc', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upSvc = $upSvc ? $upSvc : array();

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

    // 保存ボタン(利用者)
    $btnEditUserPlan = h(filter_input(INPUT_POST, 'btnEditUserPlan'));

    // 削除ボタン(利用者)
    $btnDelUserPlan = h(filter_input(INPUT_POST, 'btnDelUserPlan'));

    /*-- スタッフ --------------------------------*/

    // 更新配列(スタッフ)
    $upStf = filter_input(INPUT_POST, 'upStf', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upStf = $upStf ? $upStf : array();

    // 保存ボタン(スタッフ)
    $btnEntryStf = h(filter_input(INPUT_POST, 'btnEntryStf'));

    // 削除ボタン(スタッフ)
    $btnDelStf = h(filter_input(INPUT_POST, 'btnDelStf'));

    /*-- その他パラメータ ---------------------------------------*/

    // 更新配列（ルート）)
    $upRoot = filter_input(INPUT_POST, 'upRoot', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upRoot = $upRoot ? $upRoot : array();

    // ルート担当者を登録ボタン
    $btnRootEntry = h(filter_input(INPUT_POST, 'btnRootEntry'));

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
    */

    /* -- マスタ関連 --------------------------------------------*/

    // 加算マスタ
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_add', '*', $where);
    foreach ($temp as $val) {
        $type  = $val['type'];
        $tgtId = $val['code'];
        $addMst[$type][$tgtId] = $val['name'];
    }

    // サービスマスタ
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service', '*', $where);
    foreach ($temp as $val) {
        $type    = $val['type'];
        $tgtCd   = $val['code'];
        $tgtName = $val['name'];
        $svcMst[$type][$tgtCd] = $tgtName;
        $svcInfo[$tgtCd]   = $val;
        $svcName[$tgtName] = $val['unique_id'];
    }

    // サービス詳細リスト取得
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service_detail', '*', $where);
    foreach ($temp as $val) {
        $type  = $val['type'];
        $tgtId = $val['unique_id'];
        $svcDtlMst[$type][$tgtId] = $val['name'];
        $svcDtlInfo[$tgtId] = $val;
    }

    // 事業所リスト取得
    $where = array();
    $where['delete_flg'] = 0;
    $ofcList = select('mst_office', '*', $where);
    foreach ($ofcList as $val) {
        $officeIds[] = $val['unique_id'];
    }
    // コードマスタ取得
    $codeList = getCode();

    // ユーザー情報取得
    $temp = getUserList($placeId);
    foreach ($temp as $val) {
        $userId    = $val['unique_id'];
        $userIds[] = $userId;
        $userList[$userId] = $val;
    }

    // ルート予定マスタ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['target_day'] =  $tgtDay;
    $where['place_id'] = $placeId;
    $temp = select('dat_root_plan', '*', $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $val['staff_name'] = getStaffName($val['staff_id']);
        $rootMst[$tgtId] = $val;
    }

    // ルート設定マスタ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['place_id'] = $placeId;
    $temp = select('dat_root_plan', '*', $where);
    foreach ($temp as $val) {
        $rootIds[] = $val['unique_id'];
    }

    /* -- 更新用配列作成 ----------------------------------------*/

    /*-- 利用者 ----------------------------------*/

    // 編集保存ボタン-利用者予定
    if ($btnEditUserPlan) {
        $upUserPlanData    = $upUserPlan[$btnEditUserPlan];
        $tgtDat            = $upUserPlan[$btnEditUserPlan]['base_service'] ? $upUserPlan[$btnEditUserPlan]['base_service'] : 'dummy';
        $tgtAry            = explode('(', $tgtDat);
        $tgtName           = $tgtAry[0];

        // エラーは未実装
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

    //// 編集
    //if ($btnEditUserPlan){
    //
    //    debug($upUserPlan);exit;
    //
    //    /* -- 利用者予定 ---------------------------*/
    //    $schId = NULL;
    //    $dat = array();
    //    if (!empty($upAry['unique_id'])){
    //        $schId = $upAry['unique_id'];
    //        $dat['unique_id'] = $schId;
    //    }
    //    $dat['user_id']        = $upAry['user_id'];
    //    $dat['use_day']        = $upAry['use_day'];
    //    $dat['start_time']     = $upAry['start_time_h'] .":". $upAry['start_time_m'];
    //    $dat['end_time']       = $upAry['end_time_h'].":".$upAry['end_time_m'];
    //    $dat['office_id']      = $upAry['office_id'];
    //    $dat['staff_id']       = $upAry['staff_id'];
    //    $dat['service_id']     = $upAry['service_id'];
    //    $dat['service_name']   = $upAry['service_name'];
    //    $dat['jihi_flg']       = $upAry['jihi_flg'];
    //    $dat['jihi_price']     = $upAry['jihi_price'];
    //    $dat['record']         = $upAry['record'];
    //    $dat['service_item']   = $upAry['service_item'];
    //    $dat['staff_license']  = $upAry['staff_license'];
    //    $dat['visitor_num']    = $upAry['visitor_num'];
    //    $dat['area_add']       = $upAry['area_add'];
    //    $dat['ins_station']    = $upAry['ins_station'];
    //    $dat['qualification']  = $upAry['qualification'];
    //    $dat['no_people']      = $upAry['no_people'];
    //    $dat['condition1']     = $upAry['condition1'];
    //    $dat['status']         = $upAry['status'];
    //    $dat['protection_flg'] = $upAry['protection_flg'];
    //    $dat['root_name']      = $upAry['root_name'];
    //    $dat['schedule_id']    = $upAry['schedule_id'];
    //    $dat['kantaki']        = $upAry['kantaki'];
    //    $dat['care_job']       = $upAry['care_job'];
    //    $dat['root_id']        = $upAry['root_id'];
    //
    //    $upPlanData = $dat;
    //
    //    /* -- 加減算 --------------------------------------*/
    //    // 入力情報
    //    foreach ($upAdd as $field => $upAdd2){
    //        foreach ($upAdd2 as $seq => $val){
    //            $key = !empty($upAdd['unique_id'][$seq]) ? $upAdd['unique_id'][$seq] : $seq;
    //            $upAddData[$key][$field] = $val;
    //        }
    //    }
    //    // 登録済みで更新対象外は削除
    //    if ($schId && $upAddData){
    //        $where = array();
    //        $where['delete_flg']  = 0;
    //        $where['user_plan_id'] = $schId;
    //        $temp = select('dat_user_plan_add', 'unique_id', $where);
    //        foreach ($temp as $val){
    //            $tgtId = $val['unique_id'];
    //            if (!isset($upAddData[$tgtId])){
    //                $dat = array();
    //                $dat['unique_id']  = $tgtId;
    //                $dat['delete_flg'] = 1;
    //                $upAddData[$tgtId] = $dat;
    //            }
    //        }
    //    }
    //    /* -- 実費 ----------------------------------------*/
    //    // 入力情報
    //    foreach ($upJippi as $field => $upJippi2){
    //        foreach ($upJippi2 as $seq => $val){
    //            $key = !empty($upJippi['unique_id'][$seq]) ? $upJippi['unique_id'][$seq] : $seq;
    //            $upJippiData[$key][$field] = $val;
    //        }
    //    }
    //    // 登録済みで更新対象外は削除
    //    if ($schId && $upJippiData){
    //        $where = array();
    //        $where['user_plan_id'] = $schId;
    //        $temp = select('dat_user_plan_jippi', 'unique_id', $where);
    //        foreach ($temp as $val){
    //            $tgtId = $val['unique_id'];
    //            if (!isset($upJippiData[$tgtId])){
    //                $dat = array();
    //                $dat['unique_id']  = $tgtId;
    //                $dat['delete_flg'] = 1;
    //                $upJippiData[$tgtId] = $dat;
    //            }
    //        }
    //    }
    //    /* -- サービス詳細 --------------------------------*/
    //    // 入力情報
    //    foreach ($upSvc as $field => $upSvc2){
    //        foreach ($upSvc2 as $seq => $val){
    //            $key = !empty($upSvc['unique_id'][$seq]) ? $upSvc['unique_id'][$seq] : $seq;
    //            $upSvcData[$key][$field] = $val;
    //        }
    //    }
    //    foreach($upSvcData as $idx => $val)
    //    {
    //        $upSvcData[$idx]['start_time'] = $val['start_time_h'].":".$val['start_time_m'];
    //        $upSvcData[$idx]['end_time'] = $val['end_time_h'].":".$val['end_time_m'];
    //        unset($upSvcData[$idx]['start_time_h']);
    //        unset($upSvcData[$idx]['start_time_m']);
    //        unset($upSvcData[$idx]['end_time_h']);
    //        unset($upSvcData[$idx]['end_time_m']);
    //    }
    //    // 登録済みで更新対象外は削除
    //    if ($schId && $upSvcData){
    //        $where = array();
    //        $where['user_plan_id'] = $schId;
    //        $temp = select('dat_user_plan_service', 'unique_id', $where);
    //        foreach ($temp as $val){
    //            $tgtId = $val['unique_id'];
    //            if (!isset($upSvcData[$tgtId])){
    //                $dat = array();
    //                $dat['unique_id']  = $tgtId;
    //                $dat['delete_flg'] = 1;
    //                $upSvcData[$tgtId] = $dat;
    //            }
    //        }
    //    }
    //}

    //// 削除
    //if ($btnDelUserPlan) {
    //    $upPlanData['unique_id'] = $btnDelUser;
    //    $upPlanData['delete_flg'] = 1;
    //}

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

    /*-- スタッフ --------------------------------*/

    // 編集
    if ($btnEntryStf) {
        $dat = array();
        if ($btnEntryStf === "dupli") {
            unset($upStf['unique_id']);
        }
        if (!empty($upStf['unique_id'])) {
            $dat['unique_id'] = $upStf['unique_id'];
        }
        $dat['staff_id']   = $upStf['staff_id'];
        $dat['target_day'] = $upStf['target_day'];
        $dat['start_time'] = $upStf['start_time_h'] . ":" . $upStf['start_time_m'];
        $dat['end_time']   = $upStf['end_time_h'] . ":" . $upStf['end_time_m'];
        $dat['work']       = $upStf['work'];
        $dat['status']     = $upStf['status'];
        $dat['root_name']  = $upStf['root_name'];
        $dat['root_id']    = $upStf['root_id'];
        $dat['memo']       = $upStf['memo'];
        $upStfData = $dat;
    }

    // 削除
    if ($btnDelStf) {
        $upStfData['unique_id'] = $btnDelStf;
        $upStfData['delete_flg'] = 1;
    }

    /*-- ルート --------------------------------*/

    // ルート担当者設定
    if ($btnRootEntry) {
        foreach ($upRoot as $rootId => $val) {

            // ルートプランのstaff_idを設定する
            $rootPlanId                     = $val['unique_id'];
            $dat = array();
            $dat[$rootPlanId]['unique_id']  = $val['unique_id'];
            $dat[$rootPlanId]['root_id']    = $val['root_id'];
            $dat[$rootPlanId]['target_day'] = $val['target_day'];
            $dat[$rootPlanId]['staff_id']   = isset($val['staff_id']) ? $val['staff_id'] : null;
            $dat[$rootPlanId]['remarks']    = isset($val['remarks']) ? $val['remarks'] : null;
            $upRootData[] = $dat[$rootPlanId];

            // 利用者予定、従業員予定のstaff_idを設定する
            if (!empty($val['staff_id']) && $val['target_day']) {

                // 拠点に紐づく事業所を取得
                $offices = array();
                $temp2 = getOfficeList($val['place_id']);
                foreach ($temp2 as $grpId => $ofc) {
                    $offices[] = $ofc['unique_id'];
                }

                // 対象日と事業所から利用者スケジュール予定（親）のID取得
                $usrPlanIds = array();
                $where = array();
                $where['delete_flg'] = 0;
                $where['use_day']    = $val['target_day'];
                $where['office_id']  = $offices;
                $target = "unique_id, staff_id";
                $temp = select('dat_user_plan', $target, $where);
                foreach ($temp as $pln) {
                    $usrPlanIds[] =  $pln['unique_id'];
                }

                // 利用者スケジュール予定（サービス）の更新配列作成
                $where = array();
                $where['delete_flg']    = 0;
                $where['root_name']     = $val['root_name'];
                $where['user_plan_id']  = $usrPlanIds;
                $target = "unique_id";
                $temp = select('dat_user_plan_service', $target, $where);
                foreach ($temp as $pln) {
                    $upRootUserSvc = array();
                    $upRootUserSvc['unique_id'] = $pln['unique_id'];
                    $upRootUserSvc['staff_id']  = $val['staff_id'];
                    $upRootUserSvcData[] = $upRootUserSvc;
                }
            }
        }
    }

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    /*-- 利用者 ----------------------------------*/
    //if ($upPlanData) {
    //
    //    // スケジュール
    //    $res = upsert($loginUser, 'dat_user_plan', $upPlanData);
    //    if (isset($res['err'])) {
    //        $err[] = 'システムエラーが発生しました';
    //        throw new Exception();
    //    }
    //
    //    // 計画ID
    //    $schId = $res;
    //
    //    // 加減算
    //    if ($upAddData){
    //        foreach ($upAddData as $idx => $val){
    //            $val['user_plan_id'] = $schId;
    //            $upAddData[$idx] = $val;
    //        }
    //        $res = multiUpsert($loginUser, 'dat_user_plan_add', $upAddData);
    //        if (isset($res['err'])) {
    //            $err[] = 'システムエラーが発生しました';
    //            throw new Exception();
    //        }
    //    }
    //    // 実費
    //    if ($upJippiData){
    //        foreach ($upJippiData as $idx => $val){
    //            $val['user_plan_id'] = $schId;
    //            $upJippiData[$idx] = $val;
    //        }
    //        $res = multiUpsert($loginUser, 'dat_user_plan_jippi', $upJippiData);
    //        if (isset($res['err'])) {
    //            $err[] = 'システムエラーが発生しました';
    //            throw new Exception();
    //        }
    //    }
    //    // サービス詳細
    //    if ($upSvcData){
    //        foreach ($upSvcData as $idx => $val){
    //            $val['user_plan_id'] = $schId;
    //            $upSvcData[$idx] = $val;
    //        }
    //        $res = multiUpsert($loginUser, 'dat_user_plan_service', $upSvcData);
    //        if (isset($res['err'])) {
    //            $err[] = 'システムエラーが発生しました';
    //            throw new Exception();
    //        }
    //    }
    //
    //    // 画面遷移
    //    $day = $upPlanData['use_day'];
    //    header("Location:". '/schedule/route_edit/index.php?day='.$day);
    //    exit();
    //}

    // データ更新(利用者予定)
    if ($upUserPlanData) {
        $res = upsert($loginUser, 'dat_user_plan', $upUserPlanData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        $planId = $res;
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
    }
    // データ更新(利用者予定-実費)
    if ($upUserPlanJpiData) {
        foreach ($upUserPlanJpiData as $key => $val) {
            $upUserPlanJpiData[$key]['user_plan_id'] = $planId;
        }
        $res = multiUpsert($loginUser, 'dat_user_plan_jippi', $upUserPlanJpiData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
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
    }

    /*-- スタッフ --------------------------------*/
    if ($upStfData) {

        // スタッフ更新
        $res = upsert($loginUser, 'dat_staff_plan', $upStfData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // 画面遷移
        $day = $upStfData['target_day'];
        header("Location:" . '/schedule/route_day/index.php?day=' . $day);
        exit;
    }

    /*-- ルート情報 --------------------------------*/
    // ルート予定更新
    if ($upRootData) {
        $res = multiUpsert($loginUser, 'dat_root_plan', $upRootData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }

    // データ更新(利用者予定サービス)
    if ($btnRootEntry && $upRootUserSvcData) {
        $res = multiUpsert($loginUser, 'dat_user_plan_service', $upRootUserSvcData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
    */

    /* -- データ取得 --------------------------------------------*/
    // ルート予定マスタ更新後の再取得
    $rootMst = array();
    $rootPlnIds = array();
    $where = array();
    $where['delete_flg'] = 0;
    $where['target_day'] =  $tgtDay;
    $where['place_id'] = $placeId;
    $temp = select('dat_root_plan', '*', $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $val['staff_name'] = getStaffName($val['staff_id']);
        $rootMst[$tgtId] = $val;
        $rootPlnIds[] = $tgtId;
    }

    /* -- スタッフ予定 -------------------------------*/
    $where = array();
    $where['delete_flg'] = 0;
    $where['target_day'] = $tgtDay;
    $where['place_id']   = $placeId;
    //$where['root_id']    = $rootIds;
    $temp = select('dat_staff_plan', '*', $where);
    foreach ($temp as $val) {

        // 開始・終了時刻、更新者名
        $val['start_time']  = formatDateTime($val['start_time'], 'H:i');
        $val['end_time']    = formatDateTime($val['end_time'], 'H:i');
        $val['update_name'] = getStaffName($val['update_user']);

        // ルート、時刻、計画ID
        $val['root_name']   = !empty($val['root_name']) ? $val['root_name'] : '未割当';
        $root   = $val['root_name'];
        $time   = $val['start_time'];
        $planId = $val['unique_id'];

        // 格納
        $planList[$root][$time][$planId]['type'] = 'staff';
        $planList[$root][$time][$planId]['main'] = $val;

        // 未割当件数
        if ($val['root_name'] == '未割当') {
            $unRoot++;
        }
    }

    /* -- 利用者予定(親) -----------------------------*/
    $where = array();
    $where['delete_flg'] = 0;
    $where['use_day']    = $tgtDay;
    $where['office_id']  = $officeIds;
    $temp = select('dat_user_plan', '*', $where);
    foreach ($temp as $val) {

        // 計画ID、開始・終了時刻、更新者名
        $planId             = $val['unique_id'];
        $val['root_name'] = !empty($val['root_name']) ? $val['root_name'] : '未割当';
        $val['start_time']  = formatDateTime($val['start_time'], 'H:i');
        $val['end_time']    = formatDateTime($val['end_time'], 'H:i');
        // 更新者名
        $val['update_name'] = getStaffName($val['update_user']);

        // サービスID、基本サービス名称
        $svcId = $val['service_id'] ? $val['service_id'] : 'dummy';
        // $val['service_type'] = isset($svcInfo[$svcId]['type'])
        //         ? $svcInfo[$svcId]['type']
        //         : NULL;
        // $val['service_name'] = isset($svcInfo[$svcId]['name'])
        //         ? $svcInfo[$svcId]['name']
        //         : NULL;

        $val['service_type'] = $val['service_name'];
        $val['service_name'] = isset($svcInfo[$svcId]['name'])
            ? $svcInfo[$svcId]['name']
            : null;

        //基本サービスコード、基本サービス名称
        $tgtSvc = getServiceConfig($val['service_id']);
        $val['base_service_code'] = $tgtSvc['code'];
        $val['base_service_name'] = $tgtSvc['name'];
        $val['base_service'] = isset($svcInfo[$val['service_id']]) ? $tgtSvc['name'] . '(' . $tgtSvc['code'] . ')' : '';

        // 格納
        $planIds[]         = $planId;
        $planInfo[$planId] = $val;
    }

    /* -- その他計画関連 ------------------------------*/
    if (!empty($planIds)) {

        /* -- 予定情報 ----------------------------*/

        // 予定（加減算）
        $where = array();
        $where['delete_flg']  = 0;
        $where['user_plan_id'] = $planIds;
        $temp = select('dat_user_plan_add', '*', $where);
        foreach ($temp as $val) {

            // 計画情報、加減算ID
            $planId  = $val['user_plan_id'];
            $tgtPlan = $planInfo[$planId];
            $planAddId = $val['unique_id'];

            // 格納
            $addInfo[$planId][$planAddId] = $val;
        }

        // 予定（実費）
        $where = array();
        $where['delete_flg']   = 0;
        $where['user_plan_id'] = $planIds;
        $temp = select('dat_user_plan_jippi', '*', $where);
        foreach ($temp as $val) {

            // 計画ID、実費ID
            $planId  = $val['user_plan_id'];
            $planJpId = $val['unique_id'];

            // 格納
            $jpiInfo[$planId][$planJpId] = $val;
        }

        // 予定（サービス詳細）
        $where = array();
        $where['delete_flg']   = 0;
        $where['user_plan_id'] = $planIds;
        $temp = select('dat_user_plan_service', '*', $where);
        foreach ($temp as $val) {

            // 時刻フォーマット
            $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
            $val['end_time']   = formatDateTime($val['end_time'], 'H:i');
            $val['root_name']  = !empty($val['root_name']) ? $val['root_name'] : '未割当';

            // ルート、開始時刻、計画ID、サービス詳細ID
            $root      = $val['root_name'];
            $time      = $val['start_time'];
            $planId    = $val['user_plan_id'];
            $planSvcId = $val['unique_id'];

            // 格納
            $planList[$root][$time][$planId]['type'] = 'user';
            $planList[$root][$time][$planId]['service'][$planSvcId] = $val;
            $planList[$root][$time][$planId]['main'] = isset($planInfo[$planId])
                    ? $planInfo[$planId]
                    : array();
            $planList[$root][$time][$planId]['add'] = isset($addInfo[$planId])
                    ? $addInfo[$planId]
                    : array();
            $planList[$root][$time][$planId]['jippi'] = isset($jpiInfo[$planId])
                    ? $jpiInfo[$planId]
                    : array();

            // 未割当件数
            if ($val['root_name'] == '未割当') {
                $unRoot++;
            }
            // 宿泊、通い件数
            $svcId   = $val['service_id'] ? $val['service_id'] : 'dummy';
            $tgtMst  = isset($svcDtlInfo[$svcId]) ? $svcDtlInfo[$svcId] : array();
            $tgtType = !empty($tgtMst['type']) ? $tgtMst['type'] : null;

            if (!empty($tgtType)) {
                if (mb_strpos('通い', $tgtType) !== false) {
                    $hitRoot[1]++;
                }
                if (mb_strpos('宿泊', $tgtType) !== false) {
                    $hitRoot[2]++;
                }
            }
        }
    }

    /* -- 画面表示データ格納 ----------------------------*/

    // メインデータ
    $dispData = $planList;

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
