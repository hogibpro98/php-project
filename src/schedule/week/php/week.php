<?php

//=====================================================================
// 週間スケジュール
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
    $userIds  = array();
    $userList = array();
    $planIds  = array();
    $planList = array();
    $planInfo = array();
    $userInfo = array();
    $schId    = null;
    $upAddAry    = array();
    $upAddData   = array();
    $upJippiAry  = array();
    $upJippiData = array();
    $upJippiDel  = array();
    $upSvcAry    = array();
    $upSvcData   = array();
    $upPlanAry   = array();
    $upPlanData  = array();


    $dispData['月'] = array();
    $dispData['火'] = array();
    $dispData['水'] = array();
    $dispData['木'] = array();
    $dispData['金'] = array();
    $dispData['土'] = array();
    $dispData['日'] = array();

    // 時間軸リストの初期値設定
    $timeScaleList = [
        '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'
    ];

    $weekList = ['月', '火', '水', '木', '金', '土', '日'];

    $nextWeek['月'] = '火';
    $nextWeek['火'] = '水';
    $nextWeek['水'] = '木';
    $nextWeek['木'] = '金';
    $nextWeek['金'] = '土';
    $nextWeek['土'] = '日';
    $nextWeek['日'] = '月';

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

    // 検索ボタン
    $btnSearch = h(filter_input(INPUT_POST, 'btnSearch'));
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

    // 検索配列
    $search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    if (@$_GET['page'] == "user_list") {
        $search['type'] = @$_GET['type'];
        $search['start_day'] = @$_GET['start_day'];
        $search['end_day'] = @$_GET['end_day'];
    }

    $search['other_id']   = !empty($search['other_id']) ? $search['other_id'] : null;
    $search['user_id']    = !empty($search['user_id']) ? $search['user_id'] : null;
    $search['user_name']  = !empty($search['user_name']) ? $search['user_name'] : null;
    $search['type']       = !empty($search['type']) ? $search['type'] : 1;
    $search['start_day']  = !empty($search['start_day']) ? $search['start_day'] : THISMONTHFIRST;
    $search['end_day']    = !empty($search['end_day']) ? $search['end_day'] : THISMONTHLAST;

    /*-- 更新用パラメータ ---------------------------------------*/

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
    /*-- ルート --------------------------------*/

    // 利用者複製ボタン
    $btnCopyUser = h(filter_input(INPUT_POST, 'btnCopyUser'));

    // 展開処理ボタン
    $btnMakePlan = h(filter_input(INPUT_POST, 'btnMakePlan'));

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
    $svcDtlMst = array();
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
        $serviceIdArr[$type][$name]       = $tgtId;
    }

    // サービス詳細リスト取得
    $svcDtlInfo = array();
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service_detail', '*', $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $svcDtlInfo[$tgtId] = $val;
    }

    // 事業所リスト取得
    $ofcList = getOfficeList($placeId);

    // コードマスタ取得
    $codeList = getCode();

    // ユーザー情報取得
    $temp = getUserList($placeId);
    foreach ($temp as $val) {
        $tgtId    = $val['unique_id'];
        $userIds[] = $tgtId;
        $userList[$tgtId] = $val;
    }

    // 検索ID、氏名
    if ($userId) {
        $userInfo = getUserInfo($userId);
        $search['user_id'] = $userId;
        $search['other_id'] = $userInfo['other_id'];
        $search['user_name'] = $userInfo['last_name'] . $userInfo['first_name'];
    }

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- エラーチェック ----------------------------------------*/
    if ($btnEntryUser) {

        // 更新配列なし
        if (!$upAry) {
            $_SESSION['notice']['error'][] = '更新対象の取得に失敗しました';
            $btnEntryUser = null;
        }
        // 利用者指定なし
        if (empty($upAry['user_id'])) {
            $_SESSION['notice']['error'][] = '利用者を指定していません';
            $btnEntryUser = null;
        }
    }

    /* -- 更新用配列作成 ----------------------------------------*/

    /*-- 利用者 ----------------------------------*/

    // 編集
    if ($btnEntryUser || $btnDupliUser) {

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
        //$dat['service_id']   = isset($svcName[$tgtName]) ? $svcName[$tgtName] : NULL;
        $dat['service_id']   = isset($serviceIdArr[$upAry['service_name']][$tgtName]) ? $serviceIdArr[$upAry['service_name']][$tgtName] : null;
        $dat['service_name'] = $upAry['service_name'];
        $dat['jihi_flg']     = !empty($upAry['jihi_flg']) ? 1 : 0;
        $dat['jihi_price']   = $upAry['jihi_price'];
        //    $dat['root_name']    = isset($upAry['root_name']) ? $upAry['root_name'] : NULL;
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
        // 種類と項目名称が選択されていない実費情報は登録しない
        foreach ($upJippiData as $index => $jippi) {
            if ($jippi['type'] === '' && $jippi['uninsure_id'] === '') {
                unset($upJippiData[$index]);
            }
        }
        $upJippiData = array_values($upJippiData);
        // 登録済みで更新対象外は削除
        if ($schId && $upJippiData) {
            $where = array();
            $where['schedule_id'] = $schId;
            $where['delete_flg']  = 0;
            $temp = select('dat_week_schedule_jippi', 'unique_id', $where);
            foreach ($temp as $val) {
                $tgtId = $val['unique_id'];
                if (!isset($upJippiData[$tgtId])) {
                    $dat = array();
                    $dat['unique_id']  = $tgtId;
                    $dat['delete_flg'] = 1;
                    //$upJippiData[$tgtId] = $dat;
                    $upJippiDel[$tgtId] = $dat;
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
        $upPlanData['delete_flg'] = 1;
    }

    // 利用者複製
    if ($btnCopyUser) {

        // 遷移先利用者ID
        $tgtUser = $btnCopyUser;

        // 検索条件（すべて共通）
        $where = array();
        $where['delete_flg'] = 0;
        $where['user_id'] = $userId;

        // 利用者予定
        $temp = select('dat_week_schedule', '*', $where);
        foreach ($temp as $val) {
            $tgtId = $val['unique_id'];
            $val = unsetCopy($val);
            $val['user_id'] = $tgtUser;
            $upPlanAry[$tgtId] = $val;
        }
        // 加減算
        $temp = select('dat_week_schedule_add', '*', $where);
        foreach ($temp as $val) {
            $val = unsetCopy($val);
            $val['user_id'] = $tgtUser;
            $schId = $val['schedule_id'];
            $upAddAry[$schId][] = $val;
        }
        // 実費
        $temp = select('dat_week_schedule_jippi', '*', $where);
        foreach ($temp as $val) {
            $val = unsetCopy($val);
            $val['user_id'] = $tgtUser;
            $schId = $val['schedule_id'];
            $upJippiAry[$schId][] = $val;
        }
        // サービス
        $temp = select('dat_week_schedule_service', '*', $where);
        foreach ($temp as $val) {
            $val = unsetCopy($val);
            $val['user_id'] = $tgtUser;
            $schId = $val['schedule_id'];
            $upSvcAry[$schId][] = $val;
        }
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

        // ログテーブルに登録する
        setEntryLog($upPlanData);

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

            // ログテーブルに登録する
            setMultiEntryLog($upAddData);
        }
        // 実費
        if ($upJippiData) {
            $res = multiUpsert($loginUser, 'dat_week_schedule_jippi', $upJippiDel);
            foreach ($upJippiData as $idx => $val) {
                $val['schedule_id'] = $schId;
                $val['delete_flg'] = 0;
                $upJippiData[$idx] = $val;
            }
            $res = multiUpsert($loginUser, 'dat_week_schedule_jippi', $upJippiData);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }

            // ログテーブルに登録する
            setMultiEntryLog($upJippiData);
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

            // ログテーブルに登録する
            setMultiEntryLog($upSvcData);
        }

        // 画面遷移
        header("Location:" . '/schedule/week/index.php?user=' . $userId);
        exit;
    }

    /*-- 複製 --------------------------------*/

    // 利用者複製処理
    if (($btnCopyUser || $btnDupliUser) && $upPlanAry) {
        foreach ($upPlanAry as $key => $upPlanData) {

            // スケジュール
            $res = upsert($loginUser, 'dat_week_schedule', $upPlanData);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }

            // 計画ID
            $schId = $res;

            // ログテーブルに登録する
            setEntryLog($upPlanData);

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

                // ログテーブルに登録する
                setMultiEntryLog($upAddData);
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

                // ログテーブルに登録する
                setMultiEntryLog($upJippiData);
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

                // ログテーブルに登録する
                setMultiEntryLog($upSvcData);
            }
        }
        // 画面遷移
        $userId = $btnCopyUser ? $btnCopyUser : $userId;
        header("Location:" . '/schedule/week/index.php?user=' . $userId);
        exit;
    }

    // 再表示
    if ($btnSearch && $search['user_id']) {
        if ($search['user_id']) {
            $parmPlace = '&place=' . $search['place_id'];
        } else {
            $parmPlace = "";
        }
        header("Location:" . '/schedule/week/index.php?user=' . $search['user_id'] . $parmPlace);
        exit;
    }

    // 展開処理
    if ($btnMakePlan) {
        $userAry = array();
        $userAry[] = $userId;
        $res = makePlan($loginUser, $placeId, $userAry, $search['type'], $search['start_day'], $search['end_day']);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        $_SESSION['notice']['success'][] = '展開処理が正常に終了しました';
    }

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/

    /* -- 利用者予定(親) -----------------------------*/
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userId;
    $temp = select('dat_week_schedule', '*', $where);
    foreach ($temp as $val) {

        $val['week'] = $val['week'] !== null ? $val['week'] : 0;

        // 曜日、開始・終了時刻、計画ID
        $week   = $weekAry[$val['week']];
        $planId = $val['unique_id'];
        $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
        $val['end_time'] = formatDateTime($val['end_time'], 'H:i');
        $time   = $val['start_time'];

        // 更新者名
        $val['update_name']  = getStaffName($val['update_user']);

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

        // 予定情報保持
        $planIds[] = $planId;
        $planInfo[$planId] = $val;

        // 日跨ぎ対応
        $val['disp_start'] = $val['start_time'];
        $val['disp_end']   = $val['end_time'];
        if ($val['start_time'] > $val['end_time']) {

            // 日跨ぎあり
            $val['hmtg_flg']   = "1";

            // 前日
            $val['disp_end']   = '23:59';
            $planList[$week][$time][$planId]['main'] = $val;

            // 翌日
            $val['disp_start'] = '00:00';
            $val['disp_end']   = $val['end_time'];
            $time = $val['disp_start'];
            $week = $nextWeek[$week];
            $planList[$week][$time][$planId]['main'] = $val;

        } else {

            // 日跨ぎなし
            $val['hmtg_flg']   = "";
            $planList[$week][$time][$planId]['main'] = $val;
        }
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
        }

        // 予定（サービス詳細）
        $where = array();
        $where['delete_flg']  = 0;
        $where['schedule_id'] = $planIds;
        $temp = select('dat_week_schedule_service', '*', $where);
        foreach ($temp as $val) {

            // 計画情報
            $planId  = $val['schedule_id'];
            $tgtPlan = $planInfo[$planId];

            $val['start_time'] = formatDateTime($val['start_time'], 'H:i');
            $val['end_time'] = formatDateTime($val['end_time'], 'H:i');

            $svcdId = $val['service_detail_id'];
            $val['service_detail_name'] = isset($svcDtlInfo[$svcdId]) ? $svcDtlInfo[$svcdId]['name'] : null;

            // 曜日、開始時刻、サービス詳細ID
            $week = $weekAry[$tgtPlan['week']];
            $time = $tgtPlan['start_time'];
            $planSvcId = $val['unique_id'];

            // 日跨ぎ対応
            $val['disp_start'] = $val['start_time'];
            $val['disp_end']   = $val['end_time'];

            if ($val['start_time'] > $val['end_time']) {

                // 日跨ぎあり
                $val['hmtg_flg']   = "1";

                // 前日
                $val['disp_end']   = '23:59';
                $planList[$week][$time][$planId]['service'][$planSvcId] = $val;

                // 翌日
                $val['disp_start'] = '00:00';
                $val['disp_end']   = $val['end_time'];
                $time = $val['disp_start'];
                $week = $nextWeek[$week];
                $planList[$week][$time][$planId]['service'][$planSvcId] = $val;

            } else {

                // 所属曜日変更
                if ($tgtPlan['start_time'] > $tgtPlan['end_time']) {
                    if ($tgtPlan['start_time'] > $val['start_time']) {
                        $week = $nextWeek[$week];
                    }
                }

                // 日跨ぎなし
                $val['hmtg_flg'] = "";
                $planList[$week][$time][$planId]['service'][$planSvcId] = $val;
            }
        }
    }

    /* -- ルート未設定件数取得 ----------------------------*/

    // ルート未設定件数の初期化
    $rootNotSetCount = 0;

    // ルート未割当件数の取得
    $res = array();
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userId;
    $temp = select('dat_user_plan', '*', $where);
    foreach ($temp as $val) {
        if (empty($val['root_name']) || $val['root_name'] === "未割当") {
            $res[] = $val;
        }
    }
    // ルート未割当件数カウント
    if ($res) {
        $rootNotSetCount = count($res);
    }

    /* -- 画面表示データ格納 ----------------------------*/

    // メインデータ
    $dispData = $planList;
    //debug($dispData);exit;
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
