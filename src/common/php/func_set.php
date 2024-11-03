<?php

// 展開更新用 配列作成(ルート予定) from makePlan()
function makeRoot($upRootPlan, $placeId, $day, $rootCfg)
{
    foreach ($rootCfg as $tgtId => $val) {
        $dat = array();
        $dat['place_id']   = $placeId;
        $dat['root_id']    = $tgtId;
        $dat['target_day'] = $day;
        $dat['name']       = $val['name'];
        $upRootPlan[] = $dat;
    }
    return $upRootPlan;
}
// 展開更新用 配列作成(利用者予定) from makePlan()
function makePlanUser($schData)
{
    $res = array();
    $res['schedule_id']  = $schData['unique_id'];
    $res['use_day']      = $schData['day'];
    $res['user_id']      = $schData['user_id'];
    $res['start_time']   = $schData['start_time'];
    $res['end_time']     = $schData['end_time'];
    $res['office_id']    = $schData['office_id'];
    $res['service_id']   = $schData['service_id'];
    $res['service_name'] = $schData['service_name'];
    $res['jihi_flg']     = $schData['jihi_flg'];
    $res['jihi_price']   = $schData['jihi_price'];
    //    $res['root_name']    = $schData['root_name'];
    $res['root_name']    = !empty($schData['root_name']) ? $schData['root_name'] : '未割当' ;
    return $res;
}
// 展開更新用 配列作成(加減算) from makePlan()
function makePlanAdd($schData)
{
    $res = array();
    $res['add_id']    = $schData['add_id'];
    $res['add_name']  = $schData['add_name'];
    $res['start_day'] = $schData['start_day'];
    $res['end_day']   = $schData['end_day'];
    return $res;
}
// 展開更新用 配列作成(実費) from makePlan()
function makePlanJpi($schData)
{
    $res = array();
    $res['uninsure_id'] = $schData['uninsure_id'];
    $res['type']        = $schData['type'];
    $res['name']        = $schData['name'];
    $res['price']       = $schData['price'];
    $res['zei_type']    = $schData['zei_type'];
    $res['rate']        = $schData['rate'];
    $res['subsidy']     = $schData['subsidy'];
    return $res;
}
// 展開更新用 配列作成(サービス) from makePlan()
function makePlanSvc($schData)
{
    $res = array();
    $res['start_time']   = $schData['start_time'];
    $res['end_time']     = $schData['end_time'];
    $res['service_id']   = $schData['service_id'];
    $res['service_name'] = $schData['service_name'];
    $res['service_detail_id'] = $schData['service_detail_id'];
    $res['type']         = $schData['type'];
    if (empty($res['root_name']) || $res['root_name'] !== '未割当') {
        $res['root_name']    = !empty($schData['root_name']) ? $schData['root_name'] : '未割当' ;
    }
    // name,staff_id
    return $res;
}
// 展開更新用 配列作成(スタッフ予定) from makePlan()
function makePlanStf($schData)
{
    $res = array();
    $res['schedule_id']  = $schData['unique_id'];
    $res['target_day']   = $schData['day'];
    $res['staff_id']     = $schData['staff_id'];
    $res['start_time']   = $schData['start_time'];
    $res['end_time']     = $schData['end_time'];
    $res['work']         = $schData['work'];
    $res['root_name']    = $schData['root_name'];
    $res['root_id']      = $schData['root_id'];
    $res['memo']         = $schData['memo'];
    $res['place_id']     = $schData['place_id'];
    return $res;
}
// 週数判定 from makePlan()
function checkWeek($monthCld, $day, $data)
{
    $qt = null;
    foreach ($monthCld as $weekNum => $tgtWeek) {
        foreach ($tgtWeek as $week => $checkDay) {
            if ($checkDay === $day) {
                $qt = $weekNum;
            }
        }
    }
    if (!$qt || mb_strpos($data, $qt) === false) {
        return true;
    }
    return false;
}

/* =======================================================================
 * 展開処理 from 週間スケジュール、利用者一覧
 * =======================================================================
 *   [引数]
 *     ① ログインユーザー配列
 *     ② 拠点ID
 *     ③ 利用者配列
 *     ④ 処理タイプ 1:差分のみ,2:削除新規
 *     ⑤ 展開開始日 yyyy-mm-dd
 *     ⑥ 展開終了日 yyyy-mm-dd
 *
 *   [戻り値]
 *     NULL
 *
 * -----------------------------------------------------------------------
 */
function makePlan($loginUser, $placeId, $userAry, $type, $start, $end)
{

    /* -- 初期処理 --------------------------------------------*/
    $debug        = false;

    $res          = array();
    $calendar     = array();
    $monthCld     = array();
    $stfAry       = array();
    $rootIds      = array();

    $tgtSch       = array();
    $tgtPlan      = array();

    $userSchPlan  = array();
    $stfSchPlan   = array();

    $rootCfg      = array();
    $userSch      = array();
    $userSchAdd   = array();
    $userSchJpi   = array();
    $userSchSvc   = array();
    $stfSch       = array();

    $rootPlan     = array();
    $userPlan     = array();
    $userPlanAdd  = array();
    $userPlanJpi  = array();
    $userPlanSvc  = array();
    $stfPlan      = array();

    $upRootPlan   = array();
    $upUserPlan   = array();
    $upUserAdd    = array();
    $upUserJpi    = array();
    $upUserSvc    = array();
    $upStfPlan    = array();

    /* -- パラメータチェック ----------------------------------*/

    // 処理タイプ
    if ($type != 1 && $type != 2) {
        $res['err'] = '処理タイプの指定が不正です';
        return $res;
    }
    // 日付不正
    if ($end < $start) {
        $res['err'] = '日付の指定が不正です';
        return $res;
    }

    /* -- マスタ取得 ------------------------------------------*/

    // カレンダー
    $calendar = getCalendar($start, $end);

    // スタッフ
    $stfList = getStaffList($placeId);
    foreach ($stfList as $val) {
        $tgtId = $val['unique_id'];
        $stfAry[] = $tgtId;
    }

    /* -- 登録済みデータ取得 ----------------------------------*/

    /* -- ルート設定 -----------------------------*/
    $where = array();
    $where['delete_flg'] = 0;
    $where['place_id']   = $placeId;
    $temp = select('dat_root_config', '*', $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $rootCfg[$tgtId] = $val;
        $rootIds[] = $tgtId;
    }
    /* -- スケジュール ---------------------------*/

    // 利用者計画
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $temp = select('dat_week_schedule', '*', $where);
    foreach ($temp as $val) {
        $week  = $val['week'];
        $tgtId = $val['unique_id'];
        $tgtSch[] = $tgtId;
        $schInfo[$tgtId] = $val;
        $userSch[$week][$tgtId] = $val;
    }
    //  加減算
    if ($tgtSch) {
        $where = array();
        $where['delete_flg']  = 0;
        $where['schedule_id'] = $tgtSch;
        $temp = select('dat_week_schedule_add', '*', $where);
        foreach ($temp as $val) {
            $schId  = $val['schedule_id'];
            $tgtId  = $val['unique_id'];
            $userSchAdd[$schId][$tgtId] = $val;
        }
    }
    //  実費
    if ($tgtSch) {
        $where = array();
        $where['delete_flg']  = 0;
        $where['schedule_id'] = $tgtSch;
        $temp = select('dat_week_schedule_jippi', '*', $where);
        foreach ($temp as $val) {
            $schId  = $val['schedule_id'];
            $tgtId  = $val['unique_id'];
            $userSchJpi[$schId][$tgtId] = $val;
        }
    }
    //  サービス
    if ($tgtSch) {
        $where = array();
        $where['delete_flg']  = 0;
        $where['schedule_id'] = $tgtSch;
        $temp = select('dat_week_schedule_service', '*', $where);
        foreach ($temp as $val) {
            $schId  = $val['schedule_id'];
            $tgtId  = $val['unique_id'];
            $userSchSvc[$schId][$tgtId] = $val;
        }
    }
    // スタッフ計画
    $where = array();
    $where['delete_flg'] = 0;
    $where['root_id']   = $rootIds;
    $temp = select('dat_staff_schedule', '*', $where);
    foreach ($temp as $val) {
        $week  = $val['week'];
        $tgtId  = $val['unique_id'];
        $stfSch[$week][$tgtId] = $val;
    }

    /* -- 展開済み予定 ---------------------------*/

    // ルート予定
    $where = array();
    $where['delete_flg'] = 0;
    $where['target_day >='] = $start;
    $where['target_day <='] = $end;
    $where['place_id']      = $placeId;
    $temp = select('dat_root_plan', '*', $where);
    foreach ($temp as $val) {
        $tgtDay   = $val['target_day'];
        $tgtId    = $val['unique_id'];
        $rootPlan[$tgtDay][$tgtId] = $val;
    }

    // 利用者予定
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['use_day >='] = $start;
    $where['use_day <='] = $end;
    $temp = select('dat_user_plan', '*', $where);
    foreach ($temp as $val) {
        $day    = $val['use_day'];
        $schId  = $val['schedule_id'];
        $tgtId  = $val['unique_id'];
        $tgtPlan[] = $tgtId;
        $userPlan[$tgtId] = $val;
        $userSchPlan[$day][$schId] = $tgtId;
    }
    //  加減算
    if ($tgtPlan) {
        $where = array();
        $where['delete_flg']  = 0;
        $where['user_plan_id'] = $tgtPlan;
        $temp = select('dat_user_plan_add', '*', $where);
        foreach ($temp as $val) {
            $planId = $val['user_plan_id'];
            $tgtId  = $val['unique_id'];
            $userPlanAdd[$tgtId] = $val;
        }
    }
    //  実費
    if ($tgtPlan) {
        $where = array();
        $where['delete_flg']   = 0;
        $where['user_plan_id'] = $tgtPlan;
        $temp = select('dat_user_plan_jippi', '*', $where);
        foreach ($temp as $val) {
            $tgtId = $val['unique_id'];
            $userPlanJpi[$tgtId] = $val;
        }
    }
    //  サービス
    if ($tgtPlan) {
        $where = array();
        $where['delete_flg']   = 0;
        $where['user_plan_id'] = $tgtPlan;
        $temp = select('dat_user_plan_service', '*', $where);
        foreach ($temp as $val) {
            $tgtId = $val['unique_id'];
            $userPlanSvc[$tgtId] = $val;
        }
    }
    // スタッフ予定
    $where = array();
    $where['delete_flg'] = 0;
    $where['staff_id']   = $stfAry;
    $where['target_day >='] = $start;
    $where['target_day <='] = $end;
    $temp = select('dat_staff_plan', '*', $where);
    foreach ($temp as $val) {
        $day    = $val['target_day'];
        $schId  = $val['schedule_id'];
        $tgtId  = $val['unique_id'];
        $stfPlan[$tgtId] = $val;
        $stfSchPlan[$day][$schId] = $tgtId;
    }

    /* -- 更新配列 --------------------------------------------*/

    /* -- 展開済みデータの削除 ----------------------*/
    if ($type == 2) {

        // ルート予定
        foreach ($rootPlan as $tgtDay => $rootPlan2) {
            foreach ($rootPlan2 as $tgtId => $val) {
                $dat = array();
                $dat['unique_id']  = $tgtId;
                $dat['delete_flg'] = 1;
                $upRootPlan[$tgtId] = $dat;
            }
        }

        // 利用者予定
        foreach ($userPlan as $tgtId => $val) {
            $dat = array();
            $dat['unique_id']  = $tgtId;
            $dat['delete_flg'] = 1;
            if (empty($val['protection_flg'])) {
                $upUserPlan[$tgtId] = $dat;
            }
        }
        //  加減算
        foreach ($userPlanAdd as $tgtId => $val) {
            $dat = array();
            if (!isset($val['schedule_id'])) {
                continue;
            }
            $schId = $val['schedule_id'];
            $dat['unique_id']  = $tgtId;
            $dat['delete_flg'] = 1;
            if (isset($upUserPlan[$schId]) && empty($val['status'])) {
                $upUserAdd[$tgtId] = $dat;
            }
        }
        //  実費
        foreach ($userPlanJpi as $tgtId => $val) {
            $dat = array();
            if (!isset($val['schedule_id'])) {
                continue;
            }
            $schId = $val['schedule_id'];
            $dat['unique_id']  = $tgtId;
            $dat['delete_flg'] = 1;
            if (isset($upUserPlan[$schId]) && empty($val['status'])) {
                $upUserJpi[$tgtId] = $dat;
            }
        }
        //  サービス
        foreach ($userPlanSvc as $tgtId => $val) {
            $dat = array();
            if (!isset($val['schedule_id'])) {
                continue;
            }
            $schId = $val['schedule_id'];
            $dat['unique_id']  = $tgtId;
            $dat['delete_flg'] = 1;
            if (isset($upUserPlan[$schId]) && empty($val['status'])) {
                $upUserSvc[$tgtId] = $dat;
            }
        }
        // スタッフ予定
        foreach ($stfPlan as $tgtId => $val) {
            $dat = array();
            $dat['unique_id']  = $tgtId;
            $dat['delete_flg'] = 1;
            if (empty($val['protection_flg'])) {
                $upStfPlan[$tgtId] = $dat;
            }
        }
    }

    /* -- 日別展開予定 ------------------------------*/
    $idx = 0;
    foreach ($calendar as $day => $val) {

        // 曜日、月数
        $week  = $val['week'];
        $month = formatDateTime($day, 'Y-m');

        // 月間カレンダー作成
        if (!isset($monthCld[$month])) {
            $monthCld[$month] = getMonthCalender($month);
        }

        // 展開処理(ルート)
        if ($type == 2 || ($type == 1 && !isset($rootPlan[$day]))) {
            $upRootPlan = makeRoot($upRootPlan, $placeId, $day, $rootCfg);
        }

        // 展開処理(利用者)
        if (isset($userSch[$week])) {
            foreach ($userSch[$week] as $schId => $val) {

                // 差分のみ更新でデータありは対象外
                if ($type == 1 && isset($userSchPlan[$day][$schId])) {
                    continue;
                }
                // 週数判定
                if (checkWeek($monthCld[$month], $day, $val['week_num'])) {
                    continue;
                }

                // KEY
                $idx++;

                // 利用者予定
                $val['day'] = $day;
                $upUserPlan[$idx] = makePlanUser($val);

                //  加減算
                if (isset($userSchAdd[$schId])) {
                    foreach ($userSchAdd[$schId] as $tgtId => $val) {
                        $val['day']  = $day;
                        $upUserAdd[$idx][] = makePlanAdd($val);
                    }
                }
                //  実費
                if (isset($userSchJpi[$schId])) {
                    foreach ($userSchJpi[$schId] as $tgtId => $val) {
                        $val['day']  = $day;
                        $upUserJpi[$idx][] = makePlanJpi($val);
                    }
                }
                //  サービス
                if (isset($userSchSvc[$schId])) {
                    foreach ($userSchSvc[$schId] as $tgtId => $val) {
                        $val['day']  = $day;
                        $upUserSvc[$idx][] = makePlanSvc($val);
                    }
                }
            }
        }
        // 展開処理(スタッフ)
        if (isset($stfSch[$week])) {
            foreach ($stfSch[$week] as $schId => $val) {

                // 差分のみ更新でデータありは対象外
                if ($type == 1 && isset($stfSchPlan[$day][$schId])) {
                    continue;
                }

                // 週数判定
                if (checkWeek($monthCld[$month], $day, $val['week_num'])) {
                    continue;
                }

                // スタッフ予定
                $val['day']  = $day;
                $upStfPlan[] = makePlanStf($val);
            }
        }
    }
    /* -- 更新処理 --------------------------------------------*/
    //    debug($upStfPlan);exit;
    // ルート
    if ($upRootPlan) {
        if ($debug) {
            debug($upRootPlan);
        } else {
            $res = multiUpsert($loginUser, 'dat_root_plan', $upRootPlan);
            if (isset($res['err'])) {
                $res['err'] = 'システムエラーが発生しました';
                return $res;
            }
            // ログテーブルに登録する
            setMultiEntryLog($upRootPlan);
        }
    }

    // 利用者
    foreach ($upUserPlan as $idx => $upUserPlanData) {

        // 利用者予定
        $planId = null;
        if ($debug) {
            debug($upUserPlanData);
        } else {
            $res = upsert($loginUser, 'dat_user_plan', $upUserPlanData);
            if (isset($res['err'])) {
                $res['err'] = 'システムエラーが発生しました';
                return $res;
            }
            $planId = $res;

            // ログテーブルに登録する
            setEntryLog($upUserPlanData);
        }
        //  加減算
        if (isset($upUserAdd[$idx])) {
            $upUserAddData = $upUserAdd[$idx];
            foreach ($upUserAddData as $seq => $val) {
                $val['user_plan_id'] = $planId;
                $upUserAddData[$seq] = $val;
            }
            if ($debug) {
                debug($upUserAddData);
            } else {
                $res = multiUpsert($loginUser, 'dat_user_plan_add', $upUserAddData);
                if (isset($res['err'])) {
                    $res['err'] = 'システムエラーが発生しました';
                    return $res;
                }
                // ログテーブルに登録する
                setMultiEntryLog($upUserAddData);
            }
        }
        //  実費
        if (isset($upUserJpi[$idx])) {
            $upUserJpiData = $upUserJpi[$idx];
            foreach ($upUserJpiData as $seq => $val) {
                $val['user_plan_id'] = $planId;
                $upUserJpiData[$seq] = $val;
            }
            if ($debug) {
                debug($upUserJpiData);
            } else {
                $res = multiUpsert($loginUser, 'dat_user_plan_jippi', $upUserJpiData);
                if (isset($res['err'])) {
                    $res['err'] = 'システムエラーが発生しました';
                    return $res;
                }
                // ログテーブルに登録する
                setMultiEntryLog($upUserJpiData);
            }
        }
        //  サービス
        if (isset($upUserSvc[$idx])) {
            $upUserSvcData = $upUserSvc[$idx];
            foreach ($upUserSvcData as $seq => $val) {
                $val['user_plan_id'] = $planId;
                $upUserSvcData[$seq] = $val;
            }
            if ($debug) {
                debug($upUserSvcData);
            } else {
                $res = multiUpsert($loginUser, 'dat_user_plan_service', $upUserSvcData);
                if (isset($res['err'])) {
                    $res['err'] = 'システムエラーが発生しました';
                    return $res;
                }
                // ログテーブルに登録する
                setMultiEntryLog($upUserSvcData);
            }
        }
    }
    // スタッフ
    if ($upStfPlan) {
        if ($debug) {
            debug($upStfPlan);
        } else {
            $res = multiUpsert($loginUser, 'dat_staff_plan', $upStfPlan);
            if (isset($res['err'])) {
                $res['err'] = 'システムエラーが発生しました';
                return $res;
            }

            // ログテーブルに登録する
            setMultiEntryLog($upStfPlan);
        }
    }

    /* -- データ返却 ------------------------------------------*/
    return array();
}

/* =======================================================================
 * 予定の分類名称を子に反映させる
 * =======================================================================
 *   [引数]
 *     ① ログインユーザー配列
 *     ② 拠点ID
 *
 *   [戻り値]
 *     更新したKeyId
 *
 * -----------------------------------------------------------------------
 */
function cnvSvcName($loginUser, $planId)
{

    $upData = array();

    // 予定情報取得
    $where =  array();
    $where['unique_id'] = $planId;
    $planData = getData('dat_user_plan', $where);
    $typeName = isset($planData['service_name']) ? $planData['service_name'] : null;

    // 予定(サービス詳細)
    $where = array();
    $where['user_plan_id'] = $planId;
    $svcList = getData('dat_user_plan_service', $where);

    // 更新配列作成
    foreach ($svcList as $val) {
        if (!empty($typeName) && $typeName !== $val['service_name']) {
            $dat = array();
            $dat['unique_id']    = $val['unique_id'];
            $dat['service_name'] = $typeName;
            $upData[] = $dat;
        }
    }
    // 更新処理
    if ($upData) {
        $res = multiUpsert($loginUser, 'dat_user_plan_service', $upData);

        // ログテーブルに登録する
        setMultiEntryLog($upData);
    }
}

/* =======================================================================
 * ログ登録
 * =======================================================================
 *   [引数]
 *     ① 更新配列
 *
 *   [戻り値]
 *     なし
 *
 * -----------------------------------------------------------------------
 */
function setEntryLog($upData)
{

    /*-- 初期化 ------------------------------------*/
    $res   = array();
    $retry = 1;
    $table = 'log_entry';

    /*-- 更新配列作成 ------------------------------*/
    $setData = array();
    $setData['delete_flg']  = 0;
    $setData['create_date'] = !empty($upData['create_date']) ? $upData['create_date'] : TODAY;
    $setData['create_user'] = !empty($upData['create_user']) ? $upData['create_user'] : 'logger';
    $setData['update_date'] = !empty($upData['update_date']) ? $upData['update_date'] : TODAY;
    $setData['update_user'] = !empty($upData['update_user']) ? $upData['update_user'] : 'logger';
    $setData['user_id']     = isset($upData['user_id']) ? $upData['user_id'] : null;
    $setData['screen']      = getScreen($_SERVER['SCRIPT_NAME']);
    foreach ($upData as $key => $val) {
        if ($key === 'unique_id'
            || $key === 'delete_flg'
            || $key === 'create_user'
            || $key === 'create_date'
            || $key === 'update_user'
            || $key === 'update_date') {
            continue;
        }

        $setData['entry_data'] = !empty($setData['entry_data'])
                ? $setData['entry_data'] . ',' . $key . ':' . $val
                : $key . ':' . $val;
    }



    /*-- DB接続 ------------------------------------*/
    $pdo = connect();
    $pdo->beginTransaction();

    /*-- 更新処理 ------------------------------------*/

    // 新規ID取得
    $newAry = getNewId($table);
    $keyId = $newAry['newId'];
    $setData['unique_id'] = $keyId;

    // レコード追加
    $res = insert($pdo, $table, $setData);

    // 発番管理テーブル更新
    $res = setNewId($pdo, $table, $newAry['last'] + 1);

    // 返却用ID
    $res = $newAry['newId'];

    // 結果反映処理繰り返し
    for ($i = 0; $i < $retry; $i++) {
        $result = $pdo->commit();
        if ($result) {
            break;
        }
        usleep($wait);
    }
    $pdo = null;
}

/* =======================================================================
 * ログ登録（multiUpsert用）
 * =======================================================================
 *   [引数]
 *     ① 更新配列
 *
 *   [戻り値]
 *     なし
 *
 * -----------------------------------------------------------------------
 */
function setMultiEntryLog($upDataArry)
{
    foreach ($upDataArry as $idx => $logData) {
        setEntryLog($logData);
    }
}

function getScreen($scriptName)
{

    $res         = null;
    $screenNames = array();

    // 画面一覧
    $screenNames['/index.php']                                           = 'ログイン';
    $screenNames['/image/detail/index.php']                              = '画像関連詳細';
    $screenNames['/image/detail/dialog/user.php']                        = '画像関連詳細';
    $screenNames['/image/detail/php/detail.php']                         = '画像関連詳細';
    $screenNames['/image/list/index.php']                                = '画像関連一覧';
    $screenNames['/image/list/dialog/user.php']                          = '画像関連一覧';
    $screenNames['/image/list/function/func_download.php']               = '画像関連一覧';
    $screenNames['/image/list/php/download.php']                         = '画像関連一覧';
    $screenNames['/image/list/php/list.php']                             = '画像関連一覧';
    $screenNames['/place/cooperate/index.php']                           = '連携データ作成';
    $screenNames['/place/cooperate/function/func_account.php']           = '連携データ作成';
    $screenNames['/place/cooperate/php/cooperate.php']                   = '連携データ作成';
    $screenNames['/place/csv/index.php']                                 = 'CSVデータ出力';
    $screenNames['/place/csv/php/csv_list.php']                          = 'CSVデータ出力';
    $screenNames['/place/news/index.php']                                = '事業所からのお知らせ';
    $screenNames['/place/news/php/news.php']                             = '事業所からのお知らせ';
    $screenNames['/place/news_edit/index.php']                           = 'お知らせ詳細';
    $screenNames['/place/news_edit/php/news_edit.php']                   = 'お知らせ詳細';
    $screenNames['/place/news_list/index.php']                           = 'お知らせ管理';
    $screenNames['/place/news_list/php/news_list.php']                   = 'お知らせ管理';
    $screenNames['/record/staff/index.php']                              = '従業員予定実績';
    $screenNames['/record/staff/ajax/change_protection.php']             = '従業員予定実績';
    $screenNames['/record/staff/dialog/fix_stf_dialog.php']              = '従業員予定実績';
    $screenNames['/record/staff/dialog/fix_usr_dialog.php']              = '従業員予定実績';
    $screenNames['/record/staff/dialog/stf_dupli_dialog.php']            = '従業員予定実績';
    $screenNames['/record/staff/dialog/stf_pln_edit_dialog.php']         = '従業員予定実績';
    $screenNames['/record/staff/dialog/stf_rec_chg_dialog.php']          = '従業員予定実績';
    $screenNames['/record/staff/dialog/stf_rec_dialog.php']              = '従業員予定実績';
    $screenNames['/record/staff/dialog/usr_dupli_dialog.php']            = '従業員予定実績';
    $screenNames['/record/staff/dialog/usr_pln_edit_dialog.php']         = '従業員予定実績';
    $screenNames['/record/staff/dialog/usr_rec_chg_dialog.php']          = '従業員予定実績';
    $screenNames['/record/staff/dialog/usr_rec_dialog.php']              = '従業員予定実績';
    $screenNames['/record/staff/php/list.php']                           = '従業員予定実績';
    $screenNames['/record/user/index.php']                               = '利用者予定実績';
    $screenNames['/record/user/ajax/change_protection.php']              = '利用者予定実績';
    $screenNames['/record/user/dialog/fix_stf_dialog.php']               = '利用者予定実績';
    $screenNames['/record/user/dialog/fix_usrsvc_dialog.php']            = '利用者予定実績';
    $screenNames['/record/user/dialog/fix_usr_dialog.php']               = '利用者予定実績';
    $screenNames['/record/user/dialog/stf_dupli_dialog.php']             = '利用者予定実績';
    $screenNames['/record/user/dialog/stf_pln_edit_dialog.php']          = '利用者予定実績';
    $screenNames['/record/user/dialog/stf_rec_chg_dialog.php']           = '利用者予定実績';
    $screenNames['/record/user/dialog/stf_rec_dialog.php']               = '利用者予定実績';
    $screenNames['/record/user/dialog/usr_dupli_dialog.php']             = '利用者予定実績';
    $screenNames['/record/user/dialog/usr_pln_edit_dialog.php']          = '利用者予定実績';
    $screenNames['/record/user/dialog/usr_pln_new_edit_dialog.php']      = '利用者予定実績';
    $screenNames['/record/user/dialog/usr_rec_chg_dialog.php']           = '利用者予定実績';
    $screenNames['/record/user/dialog/usr_rec_dialog.php']               = '利用者予定実績';
    $screenNames['/record/user/php/list.php']                            = '利用者予定実績';
    $screenNames['/report/all_list/index.php']                           = '帳票一括確認';
    $screenNames['/report/all_list/php/all_list.php']                    = '帳票一括確認';
    $screenNames['/report/bedsore/index.php']                            = '褥瘡計画';
    $screenNames['/report/bedsore/dialog/image_list_dialog.php']         = '褥瘡計画';
    $screenNames['/report/bedsore/dialog/nurse_search.php']              = '褥瘡計画';
    $screenNames['/report/bedsore/php/bedsore.php']                      = '褥瘡計画';
    $screenNames['/report/instruct/index.php']                           = '指示書';
    $screenNames['/report/instruct/ajax/search_disease.php']             = '指示書';
    $screenNames['/report/instruct/ajax/updateUserEdit.php']             = '指示書';
    $screenNames['/report/instruct/dialog/disease2.php']                 = '指示書';
    $screenNames['/report/instruct/dialog/disease_search_dialog.php']    = '指示書';
    $screenNames['/report/instruct/php/instruct.php']                    = '指示書';
    $screenNames['/report/kantaki/edit.php']                             = '看多機記録';
    $screenNames['/report/kantaki/index.php']                            = '看多機記録';
    $screenNames['/report/kantaki/ajax/ajax.php']                        = '看多機記録';
    $screenNames['/report/kantaki/ajax/ajax_body_image.php']             = '看多機記録';
    $screenNames['/report/kantaki/dialog/staff_search_dialog.php']       = '看多機記録';
    $screenNames['/report/kantaki/php/body_image.php']                   = '看多機記録';
    $screenNames['/report/kantaki/php/edit.php']                         = '看多機記録';
    $screenNames['/report/kantaki/php/kantaki.php']                      = '看多機記録';
    $screenNames['/report/plan/index.php']                               = '計画書';
    $screenNames['/report/plan/ajax/doctor_ajax.php']                    = '計画書';
    $screenNames['/report/plan/dialog/staff4.php']                       = '計画書';
    $screenNames['/report/plan/php/plan.php']                            = '計画書';
    $screenNames['/report/print_list/index.php']                         = '各種帳票';
    $screenNames['/report/print_list/php/print_list.php']                = '各種帳票';
    $screenNames['/report/progress/index.php']                           = '経過記録';
    $screenNames['/report/progress/php/progress.php']                    = '経過記録';
    $screenNames['/report/report/index.php']                             = '報告書';
    $screenNames['/report/report/ajax/calendar_ajax.php']                = '報告書';
    $screenNames['/report/report/ajax/doctor_ajax.php']                  = '報告書';
    $screenNames['/report/report/php/report.php']                        = '報告書';
    $screenNames['/report/report_list/index.php']                        = '記録一覧';
    $screenNames['/report/report_list/php/report_list.php']              = '記録一覧';
    $screenNames['/report/visit1/index.php']                             = '訪問看護記録Ⅰ';
    $screenNames['/report/visit1/ajax/doctor_ajax.php']                  = '訪問看護記録Ⅰ';
    $screenNames['/report/visit1/ajax/family_ajax.php']                  = '訪問看護記録Ⅰ';
    $screenNames['/report/visit1/ajax/office_ajax.php']                  = '訪問看護記録Ⅰ';
    $screenNames['/report/visit1/ajax/sick_ajax.php']                    = '訪問看護記録Ⅰ';
    $screenNames['/report/visit1/php/visit1.php']                        = '訪問看護記録Ⅰ';
    $screenNames['/report/visit2/index.php']                             = '訪問看護記録Ⅱ詳細';
    $screenNames['/report/visit2/ajax/doctor_instruction.php']           = '訪問看護記録Ⅱ詳細';
    $screenNames['/report/visit2/php/visit2.php']                        = '訪問看護記録Ⅱ詳細';
    $screenNames['/schedule/route_day/index.php']                        = 'ルート表';
    $screenNames['/schedule/route_day/ajax/service_schedule.php']        = 'ルート表';
    $screenNames['/schedule/route_day/ajax/staff_schedule.php']          = 'ルート表';
    $screenNames['/schedule/route_day/ajax/week_schedule.php']           = 'ルート表';
    $screenNames['/schedule/route_day/dialog/edit_dialog.php']           = 'ルート表';
    $screenNames['/schedule/route_day/dialog/staff_dupli_dialog.php']    = 'ルート表';
    $screenNames['/schedule/route_day/dialog/staff_edit_dialog.php']     = 'ルート表';
    $screenNames['/schedule/route_day/dialog/staff_search_dialog.php']   = 'ルート表';
    $screenNames['/schedule/route_day/php/edit.php']                     = 'ルート表';
    $screenNames['/schedule/route_edit/index.php']                       = 'ルート管理';
    $screenNames['/schedule/route_edit/ajax/service_schedule.php']       = 'ルート管理';
    $screenNames['/schedule/route_edit/ajax/staff_schedule.php']         = 'ルート管理';
    $screenNames['/schedule/route_edit/ajax/week_schedule.php']          = 'ルート管理';
    $screenNames['/schedule/route_edit/dialog/dupli_dialog.php']         = 'ルート管理';
    $screenNames['/schedule/route_edit/dialog/edit_dialog.php']          = 'ルート管理';
    $screenNames['/schedule/route_edit/dialog/root_edit_dialog.php']     = 'ルート管理';
    $screenNames['/schedule/route_edit/dialog/staff_dialog.php']         = 'ルート管理';
    $screenNames['/schedule/route_edit/dialog/staff_dupli_dialog.php']   = 'ルート管理';
    $screenNames['/schedule/route_edit/dialog/staff_edit_dialog.php']    = 'ルート管理';
    $screenNames['/schedule/route_edit/php/edit.php']                    = 'ルート管理';
    $screenNames['/schedule/week/index.php']                             = '週間スケジュール';
    $screenNames['/schedule/week/ajax/service_schedule.php']             = '週間スケジュール';
    $screenNames['/schedule/week/ajax/week_schedule.php']                = '週間スケジュール';
    $screenNames['/schedule/week/dialog/dupli_dialog.php']               = '週間スケジュール';
    $screenNames['/schedule/week/dialog/edit_dialog.php']                = '週間スケジュール';
    $screenNames['/schedule/week/dialog/user.php']                       = '週間スケジュール';
    $screenNames['/schedule/week/php/week.php']                          = '週間スケジュール';
    $screenNames['/system/account/index.php']                            = 'アカウント情報';
    $screenNames['/system/account/php/account_edit.php']                 = 'アカウント情報';
    $screenNames['/system/log/index.php']                                = 'ログ管理';
    $screenNames['/system/log/php/log_list.php']                         = 'ログ管理';
    $screenNames['/system/office/index.php']                             = '事業所管理';
    $screenNames['/system/office/dialog/manager_search_dialog.php']      = '事業所管理';
    $screenNames['/system/office/php/office.php']                        = '事業所管理';
    $screenNames['/system/place_edit/index.php']                         = '拠点管理';
    $screenNames['/system/place_edit/ajax/address_ajax.php']             = '拠点管理';
    $screenNames['/system/place_edit/php/place_edit.php']                = '拠点管理';
    $screenNames['/system/place_list/index.php']                         = '拠点管理';
    $screenNames['/system/place_list/php/place_list.php']                = '拠点管理';
    $screenNames['/system/place_list/php/staff_list.php']                = '拠点管理';
    $screenNames['/system/staff_edit/index.php']                         = '従業員詳細';
    $screenNames['/system/staff_edit/dialog/office_copy.php']            = '従業員詳細';
    $screenNames['/system/staff_edit/dialog/office_simple.php']          = '従業員詳細';
    $screenNames['/system/staff_edit/dialog/place.php']                  = '従業員詳細';
    $screenNames['/system/staff_edit/php/staff_edit.php']                = '従業員詳細';
    $screenNames['/system/staff_list/index.php']                         = '従業員一覧';
    $screenNames['/system/staff_list/php/staff_list.php']                = '従業員一覧';
    $screenNames['/system/uninsure/index.php']                           = '保険外マスタ';
    $screenNames['/system/uninsure_edit/index.php']                      = '保険外マスタ詳細';
    $screenNames['/system/uninsure_edit/php/uninsure_edit.php']          = '保険外マスタ詳細';
    $screenNames['/system/uninsure_list/index.php']                      = '保険外マスタ一覧';
    $screenNames['/system/uninsure_list/php/uninsure_list.php']          = '保険外マスタ一覧';
    $screenNames['/user/edit/index.php']                                 = '利用者基本情報';
    $screenNames['/user/edit/ajax/address_ajax.php']                     = '利用者基本情報';
    $screenNames['/user/edit/ajax/bank_ajax.php']                        = '利用者基本情報';
    $screenNames['/user/edit/ajax/post_ajax.php']                        = '利用者基本情報';
    $screenNames['/user/edit/ajax/post_check_ajax.php']                  = '利用者基本情報';
    $screenNames['/user/edit/ajax/search_office.php']                    = '利用者基本情報';
    $screenNames['/user/edit/ajax/user_memo_ajax.php']                   = '利用者基本情報';
    $screenNames['/user/edit/dialog/duplication.php']                    = '利用者基本情報';
    $screenNames['/user/edit/dialog/hospital.php']                       = '利用者基本情報';
    $screenNames['/user/edit/dialog/insurance.php']                      = '利用者基本情報';
    $screenNames['/user/edit/dialog/insurance3.php']                     = '利用者基本情報';
    $screenNames['/user/edit/dialog/insurance4.php']                     = '利用者基本情報';
    $screenNames['/user/edit/dialog/introduct.php']                      = '利用者基本情報';
    $screenNames['/user/edit/dialog/introduct_1.php']                    = '利用者基本情報';
    $screenNames['/user/edit/dialog/introduct_2.php']                    = '利用者基本情報';
    $screenNames['/user/edit/dialog/introduct_3.php']                    = '利用者基本情報';
    $screenNames['/user/edit/dialog/office2.php']                        = '利用者基本情報';
    $screenNames['/user/edit/dialog/office3.php']                        = '利用者基本情報';
    $screenNames['/user/edit/dialog/office4.php']                        = '利用者基本情報';
    $screenNames['/user/edit/dialog/office5.php']                        = '利用者基本情報';
    $screenNames['/user/edit/dialog/office_search_dialog.php']           = '利用者基本情報';
    $screenNames['/user/edit/dialog/service.php']                        = '利用者基本情報';
    $screenNames['/user/edit/function/confirm.php']                      = '利用者基本情報';
    $screenNames['/user/edit/function/func_user.php']                    = '利用者基本情報';
    $screenNames['/user/edit/php/user_edit.php']                         = '利用者基本情報';
    $screenNames['/user/list/index.php']                                 = '利用者一覧';
    $screenNames['/user/list/function/func_user.php']                    = '利用者一覧';
    $screenNames['/user/list/php/user_list.php']                         = '利用者一覧';

    return isset($screenNames[$scriptName]) ? $screenNames[$scriptName] : $scriptName;
}
