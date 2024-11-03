<?php

//=====================================================================
// ルート管理
//=====================================================================
try {
    /* ===================================================
     * 初期処理
     * ===================================================
    */

    /*--共通ファイル呼び出し-------------------------------------*/
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

    // 初期化
    $err      = array();
    $_SESSION['notice']['error']   = array();
    $dispData   = array();
    $tgtData    = array();
    $userIds    = array();
    $userList   = array();
    $planIds    = array();
    $planList   = array();
    $planInfo   = array();
    $addInfo    = array();
    $jpiInfo    = array();
    $upPlan     = array();
    $upAdd      = array();
    $upJippi    = array();
    $upSvc      = array();
    $upStf      = array();
    $upRoot     = array();
    $upPlanData = array();
    $upStfData  = array();
    $upRootData = array();
    $rootMst    = array();
    $upAddAry    = array();
    $upAddData   = array();
    $upJippiAry  = array();
    $upJippiData = array();
    $upSvcAry    = array();
    $upSvcData   = array();
    $upPlanAry   = array();
    $upPlanData  = array();
    $rootIds     = array();
    $officeIds   = array();
    $planAllIds  = array();
    $tgtWeeks    = array();

    // ルート未割当件数
    $dat = array();
    $dat['cls'] = 'zero';
    $dat['num'] = 0;
    $unRoot = array();
    $unRoot[0] = $dat;
    $unRoot[1] = $dat;
    $unRoot[2] = $dat;
    $unRoot[3] = $dat;
    $unRoot[4] = $dat;
    $unRoot[5] = $dat;
    $unRoot[6] = $dat;

    // 時間軸リストの初期値設定
    $timeScaleList = [
        '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'
    ];

    $weekList = ['月', '火', '水', '木', '金', '土', '日'];

    // 複製用固定フィールド削除
    function unsetCopy($val)
    {
        unset($val['unique_id']);
        unset($val['create_date']);
        unset($val['create_user']);
        unset($val['update_date']);
        unset($val['update_user']);
        return $val;
    }

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

    // 曜日 ※デフォルト月曜日
    $selectWeek = filter_input(INPUT_GET, 'week');
    if (!$selectWeek) {
        $selectWeek = "0";
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

    // 保存ボタン(利用者)
    $btnEntryUser = h(filter_input(INPUT_POST, 'btnEntryUser'));

    // 複製保存ボタン(利用者)
    $btnDupliUser = h(filter_input(INPUT_POST, 'btnDupliUser'));

    // 削除ボタン(利用者)
    $btnDelUser = h(filter_input(INPUT_POST, 'btnDelUser'));

    /*-- スタッフ --------------------------------*/

    // 更新配列(スタッフ)
    $upStf = filter_input(INPUT_POST, 'upStf', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upStf = $upStf ? $upStf : array();

    // 保存ボタン(スタッフ)
    $btnEntryStf = h(filter_input(INPUT_POST, 'btnEntryStf'));

    // 削除ボタン(スタッフ)
    $btnDelStf = h(filter_input(INPUT_POST, 'btnDelStf'));

    /*-- ルート --------------------------------*/

    // 更新配列(ルート)
    $upRoot = filter_input(INPUT_POST, 'upRoot', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upRoot = $upRoot ? $upRoot : array();

    // 保存ボタン(ルート)
    $btnEntryRoot = h(filter_input(INPUT_POST, 'btnEntryRoot'));

    // 削除ボタン(ルート)
    $btnDelRoot = h(filter_input(INPUT_POST, 'btnDelRoot'));

    /*-- その他パラメータ ---------------------------------------*/

    /* ===================================================
     * マスタ取得処理
     * ===================================================
     */

    // 加算マスタ
    $addMst = array();
    $addMst2 = array();
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_add', '*', $where);
    foreach ($temp as $val) {
        $type  = $val['type'];
        $tgtId = $val['unique_id'];
        $addMst[$type][$tgtId] = $val['name'];
        $addMst2[$tgtId] = $val['name'];
    }

    // 保険外マスタ
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_uninsure', '*', $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $uisList[$tgtId] = $val;
    }

    // サービス詳細リスト
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service_detail', '*', $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $svcDtlMst[$tgtId] = $val;
    }

    // サービスマスタ
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service', '*', $where);
    foreach ($temp as $val) {
        $type  = $val['type'];
        $code  = $val['code'];
        $tgtId = $val['unique_id'];
        $name  = $val['name'];
        $svcMst[$type][$code] = $val['name'];
        $svcInfo[$tgtId]      = $val;
        $svcName[$name]       = $tgtId;
    }

    // サービス詳細リスト取得
    $svcDtlMst = array();
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
    $ofcList = getOfficeList($placeId);
    foreach ($ofcList as $idx => $dummy) {
        $officeIds[] =  $idx;
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

    // ルートマスタ
    $where = array();
    $where['delete_flg'] = 0;
    $where['place_id']   = $placeId;
    $temp = select('dat_root_config', '*', $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        //    $val['staff_name'] = getStaffName($val['staff_id']);
        $rootMst[$tgtId] = $val;
        $rootIds[] = $tgtId;
    }

    /* -- データ取得 --------------------------------------------*/

    /* -- スタッフ予定 -------------------------------*/
    //$where = array();
    //$where['delete_flg'] = 0;
    //$where['week']       = $selectWeek;
    //$where['root_id']    = $rootIds;
    //$temp = select('dat_staff_schedule', '*', $where);
    //foreach ($temp as $val) {
    //
    //    // 開始・終了時刻、更新者名
    //    $val['start_time']  = formatDateTime($val['start_time'], 'H:i');
    //    $val['end_time']    = formatDateTime($val['end_time'], 'H:i');
    //    $val['update_name'] = getStaffName($val['update_user']);
    //
    //    // ルート、時刻、計画ID
    //    $root   = !empty($val['root_name']) ? $val['root_name'] : '未割当';
    //    $time   = $val['start_time'];
    //    $planId = $val['unique_id'];
    //
    //    // 格納
    //    $planList[$root][$time][$planId]['type'] = 'staff';
    //    $planList[$root][$time][$planId]['main'] = $val;
    //}
    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
    */

    /* -- 入力チェック ------------------------------------------*/

    /*-- ルート追加 ----------------------------------*/

    if ($btnEntryRoot) {
        if (empty($placeId)) {
            $_SESSION['notice']['error'][] = '拠点を指定していません';
            $btnEntryRoot = null;
        }
        if (empty($upRoot['name'])) {
            $_SESSION['notice']['error'][] = 'ルート名が入力されていません';
            $btnEntryRoot = null;
        }

    }

    if ($btnDelRoot) {
        if (empty($placeId)) {
            $_SESSION['notice']['error'][] = '拠点を指定していません';
            $btnDelRoot = null;
        }
    }

    /* -- 更新用配列作成 ----------------------------------------*/

    /*-- 利用者 ----------------------------------*/

    // 編集
    if ($btnEntryUser  || $btnDupliUser) {

        /* -- 週間スケジュール ---------------------------*/
        $schId = null;
        $dat = array();
        if (!empty($upAry['unique_id'])) {
            $dat['unique_id'] = $upAry['unique_id'];
            $schId = $upAry['unique_id'];
        }
        $dat['week'] = isset($upAry['week']) ? $upAry['week'] : null;
        $dat['week_num'] = !empty($upAry['week_num'])
                ? implode('^', $upAry['week_num'])
                : null;
        $dat['user_id']      = $upAry['user_id'];
        $dat['office_id']    = $upAry['office_id'];
        $dat['start_time']   = $upAry['start_time_h'] . ":" . $upAry['start_time_m'];
        $dat['end_time']     = $upAry['end_time_h'] . ":" . $upAry['end_time_m'];

        $tgtDat              = $upAry['base_service'] ? $upAry['base_service'] : 'dummy';
        $tgtAry              = explode('(', $tgtDat);
        $tgtName             = $tgtAry[0];
        $dat['service_id']   = isset($svcName[$tgtName]) ? $svcName[$tgtName] : null;
        $dat['service_name'] = $upAry['service_name'];
        $dat['jihi_flg']     = !empty($upAry['jihi_flg']) ? 1 : 0;
        $dat['jihi_price']   = $upAry['jihi_price'];
        $dat['root_name']    = isset($upAry['root_name']) ? $upAry['root_name'] : null;
        $upPlanData = $dat;

        /* -- 加減算 --------------------------------------*/
        // 入力情報
        foreach ($upAdd as $field => $upAdd2) {
            foreach ($upAdd2 as $seq => $val) {
                $key = !empty($upAdd['unique_id'][$seq]) ? $upAdd['unique_id'][$seq] : $seq;
                $upAddData[$key][$field] = $val;
                if ($field == 'add_id') {
                    $upAddData[$key]['add_name'] = $val ? $addMst2[$val] : null;
                }
            }
        }
        // 登録済みで更新対象外は削除
        if ($schId && $upAddData) {
            $where = array();
            $where['delete_flg']  = 0;
            $where['schedule_id'] = $schId;
            $temp = select('dat_week_schedule_add', 'unique_id', $where);
            foreach ($temp as $val) {
                $tgtId = $val['unique_id'];
                if (!isset($upAddData[$tgtId])) {
                    $dat = array();
                    $dat['unique_id']  = $tgtId;
                    $dat['delete_flg'] = 1;
                    $upAddData[$tgtId] = $dat;
                }
            }
        }
        /* -- 実費 ----------------------------------------*/
        // 入力情報
        foreach ($upJippi as $field => $upJippi2) {
            foreach ($upJippi2 as $seq => $val) {
                $key = !empty($upJippi['unique_id'][$seq]) ? $upJippi['unique_id'][$seq] : $seq;
                $upJippiData[$key][$field] = $val;
                if ($field == 'uninsure_id') {
                    $upJippiData[$key]['name'] = $val ? $uisList[$val]['name'] : null;
                }
            }
        }
        // 登録済みで更新対象外は削除
        if ($schId && $upJippiData) {
            $where = array();
            $where['schedule_id'] = $schId;
            $temp = select('dat_week_schedule_jippi', 'unique_id', $where);
            foreach ($temp as $val) {
                $tgtId = $val['unique_id'];
                if (!isset($upJippiData[$tgtId])) {
                    $dat = array();
                    $dat['unique_id']  = $tgtId;
                    $dat['delete_flg'] = 1;
                    $upJippiData[$tgtId] = $dat;
                }
            }
        }
        /* -- サービス詳細 --------------------------------*/
        // 入力情報
        foreach ($upSvc as $field => $upSvc2) {
            foreach ($upSvc2 as $seq => $val) {
                $key = !empty($upSvc['unique_id'][$seq]) ? $upSvc['unique_id'][$seq] : $seq;
                $upSvcData[$key][$field] = $val;
                $upSvcData[$key]['service_id']   = $upPlanData['service_id'];
                $upSvcData[$key]['service_name'] = $upPlanData['service_name'];
                if ($field == 'service_detail_id') {
                    $upSvcData[$key]['type'] = $val ? $svcDtlMst[$val]['name'] : null;
                }
                if ($field == 'start_time') {
                    //                $upSvcData[$key]['start_time'] = $val ? $val.':00' : NULL;
                    $upSvcData[$key]['start_time'] = $val ? $val . ':00' : null;
                }
                if ($field == 'end_time') {
                    //                $upSvcData[$key]['end_time'] = $val ? $val.':00' : NULL;
                    $upSvcData[$key]['end_time'] = $val ? $val . ':00' : null;
                }
            }
        }
        foreach ($upSvcData as $idx => $val) {
            $upSvcData[$idx]['start_time'] = $val['start_time_h'] . ":" . $val['start_time_m'];
            $upSvcData[$idx]['end_time'] = $val['end_time_h'] . ":" . $val['end_time_m'];
            unset($upSvcData[$idx]['start_time_h']);
            unset($upSvcData[$idx]['start_time_m']);
            unset($upSvcData[$idx]['end_time_h']);
            unset($upSvcData[$idx]['end_time_m']);
        }
        // 登録済みで更新対象外は削除
        if ($schId && $upSvcData) {
            $where = array();
            $where['schedule_id'] = $schId;
            $temp = select('dat_week_schedule_service', 'unique_id', $where);
            foreach ($temp as $val) {
                $tgtId = $val['unique_id'];
                if (!isset($upSvcData[$tgtId])) {
                    $dat = array();
                    $dat['unique_id']  = $tgtId;
                    $dat['delete_flg'] = 1;
                    $upSvcData[$tgtId] = $dat;
                }
            }
        }
    }

    // 複製
    if ($btnDupliUser) {
        $day_week = $upAry['day_week'] ?: array();
        foreach ($day_week as $idx => $weekVal) {
            $upPlanData['week'] = $weekVal;
            $upPlanAry[$idx]  = $upPlanData;
            $upAddAry[$idx]   = $upAddData;
            $upJippiAry[$idx] = $upJippiData;
            $upSvcAry[$idx]   = $upSvcData;
        }
    }

    // 削除
    if ($btnDelUser) {
        $upPlanData['unique_id'] = $btnDelUser;
        //    $upPlanData['week']       = $selectWeek;
        $upPlanData['delete_flg'] = 1;
    }

    /*-- スタッフ --------------------------------*/

    // 編集
    if ($btnEntryStf) {
        $dat = array();

        if ($btnEntryStf === "dupli") {
            unsetCopy($upStf);
        }
        if (!empty($upStf['unique_id'])) {
            $dat['unique_id'] = $upStf['unique_id'];
        } else {
            if (isset($upStf['unique_id'])) {
                unset($upStf['unique_id']);
            }
        }

        $dat['week']       = $upStf['week'];
        $dat['week_num'] = !empty($upStf['week_num'])
        ? implode('^', $upStf['week_num'])
        : null;

        $dat['staff_id']   = $upStf['staff_id'];
        $dat['start_time']  = $upStf['start_time_h'] . ":" . $upStf['start_time_m'];
        $dat['end_time'] = $upStf['end_time_h'] . ":" . $upStf['end_time_m'];
        $dat['root_name']  = $upStf['root_name'];
        $dat['root_id']  = $upStf['root_id'];
        $dat['work']  = $upStf['work'];
        $dat['memo']  = $upStf['memo'];
        $upStfData = $dat;
    }

    // 削除
    if ($btnDelStf) {
        $upStfData['unique_id'] = $btnDelStf;
        $upStfData['week']      = $week;
        $upStfData['delete_flg'] = 1;
    }

    /*-- ルート --------------------------------*/
    // 編集
    if ($btnEntryRoot) {

        // 更新用配列(ルート設定)
        $dat = array();
        if (!empty($upRoot['unique_id'])) {
            $dat['unique_id']   = $upRoot['unique_id'];
        }
        $dat['name']            = $upRoot['name'];
        $dat['place_id']        = $placeId;
        $dat['root_type']       = !empty($upRoot['root_type'])
            ? implode('^', $upRoot['root_type'])
            : null;
        $dat['root_authority']  = 0;
        $upRootData = $dat;

        if (!empty($upRoot['unique_id'])) {

            // 登録済み利用者予定
            $where = array();
            $where['delete_flg'] = 0;
            $where['root_name']  = $upRootData['name'];
            $wehre['office_id']  = $officeIds;
            $temp = select('dat_week_schedule', 'unique_id,root_name', $where);
            foreach ($temp as $val) {
                $upPlanData['unique_id'] = $val['unique_id'];
                $upPlanData['root_name'] = $upRootData['name'];
            }
            // 登録済みスタッフ予定
            $where = array();
            $where['delete_flg'] = 0;
            $where['root_id']  = $upRoot['unique_id'];
            $temp = select('dat_staff_schedule', 'unique_id,root_name', $where);
            foreach ($temp as $val) {
                $upStfData['unique_id'] = $val['unique_id'];
                $upStfData['root_name'] = $upRootData['name'];
            }
        }
    }

    // 削除
    if ($btnDelRoot) {
        $upRootData['unique_id'] = $btnDelRoot;
        $upRootData['week']      = $selectWeek;
        $upRootData['delete_flg'] = 1;
    }

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    // 保存、削除処理
    if (($btnEntryUser || $btnDelUser) && $upPlanData) {

        // スケジュール
        $res = upsert($loginUser, 'dat_week_schedule', $upPlanData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // 計画ID
        $schId = $res;

        // 加減算
        if ($upAddData) {
            foreach ($upAddData as $idx => $val) {
                $val['schedule_id'] = $schId;
                $upAddData[$idx] = $val;
            }
            $res = multiUpsert($loginUser, 'dat_week_schedule_add', $upAddData);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }
        }
        // 実費
        if ($upJippiData) {
            foreach ($upJippiData as $idx => $val) {
                $val['schedule_id'] = $schId;
                $upJippiData[$idx] = $val;
            }
            $res = multiUpsert($loginUser, 'dat_week_schedule_jippi', $upJippiData);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }
        }
        // サービス詳細
        if ($upSvcData) {
            foreach ($upSvcData as $idx => $val) {
                $val['schedule_id'] = $schId;
                $upSvcData[$idx] = $val;
            }
            $res = multiUpsert($loginUser, 'dat_week_schedule_service', $upSvcData);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }
        }

        // 画面遷移
        $week = $upPlanData['week'];
        header("Location:" . '/schedule/route_edit/index.php?week=' . $week);
        exit;
    }

    /*-- 複製 --------------------------------*/

    // 利用者複製処理
    if ($btnDupliUser && $upPlanAry) {
        foreach ($upPlanAry as $key => $upPlanData) {

            // スケジュール
            $res = upsert($loginUser, 'dat_week_schedule', $upPlanData);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }

            // 計画ID
            $schId = $res;

            // 加減算
            if (!empty($upAddAry[$key])) {
                foreach ($upAddAry[$key] as $idx => $val) {
                    $val['schedule_id'] = $schId;
                    $upAddData[$idx] = $val;
                }
                $res = multiUpsert($loginUser, 'dat_week_schedule_add', $upAddData);
                if (isset($res['err'])) {
                    $err[] = 'システムエラーが発生しました';
                    throw new Exception();
                }
            }
            // 実費
            if (!empty($upJippiAry[$key])) {
                foreach ($upJippiAry[$key] as $idx => $val) {
                    $val['schedule_id'] = $schId;
                    $upJippiData[$idx] = $val;
                }
                $res = multiUpsert($loginUser, 'dat_week_schedule_jippi', $upJippiData);
                if (isset($res['err'])) {
                    $err[] = 'システムエラーが発生しました';
                    throw new Exception();
                }
            }
            // サービス詳細
            if (!empty($upSvcAry[$key])) {
                foreach ($upSvcAry[$key] as $idx => $val) {
                    $val['schedule_id'] = $schId;
                    $upSvcData[$idx] = $val;
                }
                $res = multiUpsert($loginUser, 'dat_week_schedule_service', $upSvcData);
                if (isset($res['err'])) {
                    $err[] = 'システムエラーが発生しました';
                    throw new Exception();
                }
            }
        }
        // 画面遷移
        $week = $upPlanData['week'];
        header("Location:" . '/schedule/route_edit/index.php?week=' . $week);
        exit;
    }

    /*-- スタッフ --------------------------------*/
    if (($btnEntryStf || $btnDelStf) && $upStfData) {

        // スタッフ更新
        $res = upsert($loginUser, 'dat_staff_schedule', $upStfData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // 画面遷移
        $week = $upStfData['week'];
        header("Location:" . '/schedule/route_edit/index.php?week=' . $week);
        exit;
    }

    /*-- ルート --------------------------------*/
    if (($btnEntryRoot || $btnDelRoot) && $upRootData) {



        // ルート更新
        $res = upsert($loginUser, 'dat_root_config', $upRootData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // 画面遷移
        header("Location:" . '/schedule/route_edit/index.php?week=' . $selectWeek);
        exit;
    }

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
    */

    /* -- データ取得 --------------------------------------------*/

    /* -- スタッフ予定 -------------------------------*/

    $where = array();
    $where['delete_flg'] = 0;
    $where['week']       = !empty($selectWeek) ? $selectWeek : "0";
    $where['place_id']   = $placeId;
    //$where['root_id']    = $rootIds;
    $temp = select('dat_staff_schedule', '*', $where);
    foreach ($temp as $val) {

        // 開始・終了時刻、更新者名
        $val['start_time']  = formatDateTime($val['start_time'], 'H:i');
        $val['end_time']    = formatDateTime($val['end_time'], 'H:i');
        $val['update_name'] = getStaffName($val['update_user']);

        // ルート、時刻、計画ID
        $root   = !empty($val['root_name']) ? $val['root_name'] : '未割当';
        $time   = $val['start_time'];
        $planId = $val['unique_id'];

        $week = !empty($selectWeek) ? $selectWeek : "0";
        if ($val['week'] !== $week) {
            continue;
        }

        // 格納
        $planList[$root][$time][$planId]['type'] = 'staff';
        $planList[$root][$time][$planId]['main'] = $val;

        //    // 未割当件数
        //    if ($val['root_name'] == '未割当'){
        //        $weekx = $val['week'];
        //        $unRoot[$weekx]['cls'] = NULL;
        //        $unRoot[$weekx]['num']++;
        //    }
    }

    /* -- 利用者予定(親) -----------------------------*/
    $where = array();
    $where['delete_flg'] = 0;
    $where['office_id']  = $officeIds;
    $temp = select('dat_week_schedule', '*', $where);
    foreach ($temp as $val) {

        // 計画ID、開始・終了時刻、更新者名
        $planId = $val['unique_id'];
        $planAllIds[] = $planId;
        if ($val['week'] !== $selectWeek) {
            continue;
        }

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
        $planIds[] = $planId;
        $planInfo[$planId] = $val;
    }

    /* -- その他計画関連 ------------------------------*/
    if (!empty($planIds)) {

        /* -- 予定情報 ----------------------------*/

        // 予定（加減算）
        $where = array();
        $where['delete_flg']  = 0;
        $where['schedule_id'] = $planIds;
        $temp = select('dat_week_schedule_add', '*', $where);
        foreach ($temp as $val) {

            // 計画情報、加減算ID
            $planId  = $val['schedule_id'];
            $tgtPlan = $planInfo[$planId];

            // 曜日、開始時刻、加減算ID
            $week = $weekAry[$tgtPlan['week']];
            $time = $tgtPlan['start_time'];
            $planAddId = $val['unique_id'];

            // 加減算名称
            $planList[$week][$time][$planId]['main']['add_name']
                    = !empty($planList[$week][$time][$planId]['main']['add_name'])
                    ? $planList[$week][$time][$planId]['main']['add_name'] . '<br>' . $val['add_name']
                    : $val['add_name'];

            // 格納
            $planList[$week][$time][$planId]['add'][$planAddId] = $val;
            $addInfo[$planId][$planAddId] = $val;
        }

        // 予定（実費）
        $where = array();
        $where['delete_flg']  = 0;
        $where['schedule_id'] = $planIds;
        $temp = select('dat_week_schedule_jippi', '*', $where);
        foreach ($temp as $val) {

            // 計画情報
            $planId  = $val['schedule_id'];
            $tgtPlan = $planInfo[$planId];

            // 曜日、開始時刻、実費ID
            $week = $weekAry[$tgtPlan['week']];
            $time = $tgtPlan['start_time'];
            $planJpId = $val['unique_id'];

            // 格納
            $planList[$week][$time][$planId]['jippi'][$planJpId] = $val;
            $jpiInfo[$planId][$planJpId] = $val;
        }

        // 予定（サービス詳細）
        $where = array();
        $where['delete_flg']  = 0;
        $where['schedule_id'] = $planIds;
        $temp = select('dat_week_schedule_service', '*', $where);
        foreach ($temp as $val) {

            // 時刻フォーマット
            $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
            $val['end_time']   = formatDateTime($val['end_time'], 'H:i');
            $val['root_name']  = !empty($val['root_name']) ? $val['root_name'] : '未割当';

            // ルート、開始時刻、計画ID、サービス詳細ID
            $root = $val['root_name'];
            $time = $val['start_time'];
            $planId    = $val['schedule_id'];
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
        }
    }

    // 利用者予定集計
    $tgtPlanIds = array();
    $where = array();
    $where['delete_flg'] = 0;
    $where['office_id']  = $officeIds;
    $temp = select('dat_week_schedule', '*', $where);
    foreach ($temp as $val) {
        $tgtPlanId = $val['unique_id'];
        $tgtPlanIds[] = $tgtPlanId;
        $tgtWeeks[$tgtPlanId]['week'] = $val['week'];
    }


    // 未割当数算出
    $where = array();
    $where['delete_flg'] = 0;
    $where['root_id']    = $rootIds;
    $temp = select('dat_staff_schedule', '*', $where);
    foreach ($temp as $val) {
        // 未割当件数
        if ($val['root_name'] == '未割当' && !empty($val['root_name'])) {
            $weekx = $val['week'];
            $unRoot[$weekx]['cls'] = null;
            $unRoot[$weekx]['num']++;
        }
    }

    $where = array();
    $where['delete_flg']   = 0;
    $where['schedule_id']  = $planAllIds;
    $temp = select('dat_week_schedule_service', '*', $where);
    foreach ($temp as $val) {
        // 未割当件数
        if ($val['root_name'] !== '未割当' && !empty($val['root_name'])) {
            continue;
        }
        $tgtPlnId = $val['schedule_id'];
        if (!empty($tgtWeeks[$tgtPlnId]['week'])) {
            $weekx = $tgtWeeks[$tgtPlnId]['week'];
            $unRoot[$weekx]['cls'] = null;
            $unRoot[$weekx]['num']++;
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
