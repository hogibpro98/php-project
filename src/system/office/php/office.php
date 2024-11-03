<?php

//=====================================================================
// 事業所管理
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
    $err       = array();
    $_SESSION['notice']['error']   = array();
    $dispData  = array();
    $dispCar   = array();
    $dispPtl   = array();
    $tgtData   = array();
    $upData    = array();
    $upCarData = array();
    $upPtlData = array();
    $rcdList   = array();

    // 対象テーブル(メイン)
    $table = 'mst_office';

    // 初期値
    $dispData[0] = initTable($table);
    $dispData[1] = initTable($table);
    $dispData[0]['create_day']  = null;
    $dispData[0]['create_time'] = null;
    $dispData[0]['create_name'] = null;
    $dispData[0]['update_day']  = null;
    $dispData[0]['update_time'] = null;
    $dispData[0]['update_name'] = null;
    $dispData[0]['staff_id']    = null;
    $dispData[1]['staff_id']    = null;
    $dispPtl[0] = array();
    $dispPtl[1] = array();
    $dispCar[0] = array();
    $dispCar[1] = array();
    $rcdList[0] = array();
    $rcdList[1] = array();

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 拠点ID
    $plcId = h(filter_input(INPUT_GET, 'id'));
    $plcId_POST = h(filter_input(INPUT_POST, 'place_id'));

    // 看護小規模多機能
    $ofcId1_GET = h(filter_input(INPUT_GET, 'office1'));
    $ofcId1_POST = h(filter_input(INPUT_POST, 'office1'));
    $ofcId1 = $ofcId1_POST ? $ofcId1_POST : $ofcId1_GET;

    // 訪問看護
    $ofcId2_GET = h(filter_input(INPUT_GET, 'office2'));
    $ofcId2_POST = h(filter_input(INPUT_POST, 'office2'));
    $ofcId2 = $ofcId2_POST ? $ofcId2_POST : $ofcId2_GET;

    /*-- 更新用パラメータ ---------------------------------------*/

    // 更新ボタン(全体)
    $btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

    // 拠点選択による画面遷移
    if ($plcId_POST && !$btnEntry) {
        $nextPage = $server['scriptName'] . '?id=' . $plcId_POST;
        header("Location:" . $nextPage);
    }

    // 更新配列(事業所) [0]看多機、[1]訪問看護
    $upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upAry = $upAry ? $upAry : array();

    // 更新配列(自動車) [0]看多機、[1]訪問看護
    $upCar = filter_input(INPUT_POST, 'upCar', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upCar = $upCar ? $upCar : array();

    // 更新配列(巡回事業所) [0]看多機、[1]訪問看護
    $upPtl = filter_input(INPUT_POST, 'upPtl', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upPtl = $upPtl ? $upPtl : array();

    // 削除ボタン(自動車ID)
    $btnDelCar = h(filter_input(INPUT_POST, 'btnDelCar'));

    // 削除ボタン(巡回事業所ID)
    $btnDelPtl = h(filter_input(INPUT_POST, 'btnDelPtl'));

    // 履歴追加ボタン(事業所ID)
    $btnRcd = h(filter_input(INPUT_POST, 'btnRcd'));

    /*-- その他パラメータ ---------------------------------------*/

    /* ===================================================
     * マスタ取得
     * ===================================================
     */

    // 汎用マスタ
    $gnrList = getCode();

    // 拠点
    $plcMst = getData('mst_place');

    // 住所情報
    $where = array();
    $where['delete_flg'] = 0;
    $target = 'prefecture_name,city_name,town_name';
    $orderBy = "prefecture_id ASC ";
    $temp = select('mst_area', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $pref  = $val['prefecture_name'];
        $city  = $val['city_name'];
        $town = $val['town_name'];
        $areaMst[$pref][$city][$town] = true;
    }

    // スタッフ
    $staffMst = getData('mst_staff');


    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 更新用配列作成 ----------------------------------------*/

    // 更新配列
    if ($btnEntry && $upAry) {

        // null判定
        $upAry = setNull($upAry, 2);

        // 事業所
        foreach ($upAry as $type => $val) {

            // KEY
            if (empty($val['unique_id'])) {
                if (isset($val['unique_id'])) {
                    unset($val['unique_id']);
                }
            }

            // 拠点ID
            if ($plcId) {
                $val['place_id'] = $plcId;
            }

            // 履歴番号
            $val['record_no'] = !empty($val['record_no']) ? $val['record_no'] : 1;

            // チェックボックス
            //  総合マネジメント体制強化加算,訪問体制強化加算,特別地域訪問看護加算１・２
            //  訪問看護小規模事業所加算１・２,訪問看護中山間地域提供加算１・２
            //  緊急時訪問看護加算,24時間対応体制加算
            //$val['select3']  = isset($val['select3'])  ? $val['select3'] : NULL;
            //$val['select4']  = isset($val['select4'])  ? $val['select4'] : NULL;
            //$val['select7']  = isset($val['select7'])  ? implode(",", $val['select7']) : NULL;
            //$val['select8']  = isset($val['select8'])  ? implode(",", $val['select8']) : NULL;
            //$val['select9']  = isset($val['select9'])  ? implode(",", $val['select9']) : NULL;
            //$val['select7']  = isset($val['select7'])  ? $val['select7'] : NULL;
            //$val['select8']  = isset($val['select8'])  ? $val['select8'] : NULL;
            //$val['select9']  = !empty($val['select9'])  ? $val['select9'] : NULL;
            //$val['select10'] = !empty($val['select10']) ? $val['select10']: NULL;
            //$val['select13'] = isset($val['select13']) ? $val['select13']: NULL;
            //$val['select14'] = isset($val['select14']) ? $val['select14']: NULL;
            $val['add1_1_1'] = !empty($val['add1_1_1']) ? $val['add1_1_1'] : null;
            $val['add1_1_2'] = !empty($val['add1_1_2']) ? $val['add1_1_2'] : null;
            $val['add1_1_3'] = !empty($val['add1_1_3']) ? $val['add1_1_3'] : null;
            $val['add1_1_4'] = !empty($val['add1_1_4']) ? $val['add1_1_4'] : null;
            $val['add2_1_1'] = !empty($val['add2_1_1']) ? $val['add2_1_1'] : null;
            $val['add2_1_2'] = !empty($val['add2_1_2']) ? $val['add2_1_2'] : null;
            $val['add2_1_3'] = !empty($val['add2_1_3']) ? $val['add2_1_3'] : null;
            $val['add2_1_4'] = !empty($val['add2_1_4']) ? $val['add2_1_4'] : null;
            $val['add2_2_1'] = !empty($val['add2_2_1']) ? $val['add2_2_1'] : null;
            $val['add2_2_2'] = !empty($val['add2_2_2']) ? $val['add2_2_2'] : null;
            $val['add2_2_3'] = !empty($val['add2_2_3']) ? $val['add2_2_3'] : null;
            $val['add2_2_4'] = !empty($val['add2_2_4']) ? $val['add2_2_4'] : null;
            $val['add2_3_1'] = !empty($val['add2_3_1']) ? $val['add2_3_1'] : null;
            $val['add2_3_2'] = !empty($val['add2_3_2']) ? $val['add2_3_2'] : null;
            $val['add2_3_3'] = !empty($val['add2_3_3']) ? $val['add2_3_3'] : null;
            $val['add2_3_4'] = !empty($val['add2_3_4']) ? $val['add2_3_4'] : null;

            // 格納
            $upAry[$type] = $val;
        }

        // 自動車
        foreach ($upCar as $type => $upCar2) {
            foreach ($upCar2 as $key => $val) {
                if ($val['name']) {
                    if (empty($val['unique_id'])) {
                        unset($val['unique_id']);
                    }
                    $upCar[$type][$key] = $val;
                } else {
                    unset($upCar[$type][$key]);
                }
            }
        }

        // 巡回事業所
        foreach ($upPtl as $type => $upPtl2) {
            foreach ($upPtl2 as $key => $val) {
                if ($val['name'] || $val['type']) {
                    if (empty($val['unique_id'])) {
                        unset($val['unique_id']);
                    }
                    $upPtl[$type][$key] = $val;
                } else {
                    unset($upPtl[$type][$key]);
                }
            }
        }
    }

    // 削除用配列(自動車)
    if ($btnDelCar) {
        $upCarData = array();
        $upCarData['unique_id']  = $btnDelCar;
        $upCarData['delete_flg'] = 1;
    }

    // 削除用配列(巡回事業所)
    if ($btnDelPtl) {
        $upPtlData = array();
        $upPtlData['unique_id']  = $btnDelPtl;
        $upPtlData['delete_flg'] = 1;
    }

    // 履歴追加
    if ($btnRcd) {

        // 初期化
        $upData = array();

        // 登録済み情報取得
        $where = array();
        $where['unique_id'] = $btnRcd;
        $upData = select($table, '*', $where);
        $upData[1] = $upData[0];

        // 終了日,削除フラグ
        $upData[0]['end_day']    = YESTERDAY;
        $upData[0]['delete_flg'] = 1;

        // 履歴番号＋１,開始日設定
        $rcdNo = intval($upData[0]['record_no']);
        $upData[1]['record_no'] = $rcdNo + 1;
        $upData[1]['start_day'] = TODAY;

        unset($upData[1]['unique_id']);
        unset($upData[1]['create_date']);
        unset($upData[1]['create_user']);
        unset($upData[1]['update_date']);
        unset($upData[1]['update_user']);
    }

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    // データ更新
    if ($btnEntry && $upAry) {
        foreach ($upAry as $type => $upData) {
            if (!empty($plcId) && $upData['name']) {

                // mst_office
                $res = upsert($loginUser, $table, $upData);
                if (isset($res['err'])) {
                    $err[] = 'システムエラーが発生しました';
                    throw new Exception();
                }

                // ログテーブルに登録する
                setEntryLog($upData);

                $ofcId = $res;
                if ($type == 0) {
                    $ofcId1 = $ofcId;
                } else {
                    $ofcId2 = $ofcId;
                }

                // 新規のみグループIDを新規登録時のIDとする
                if (empty($upData['unique_id'])) {
                    $upData['office_group'] = $ofcId;
                    $upData['unique_id']    = $ofcId;
                    $res = upsert($loginUser, $table, $upData);
                    if (isset($res['err'])) {
                        $err[] = 'システムエラーが発生しました';
                        throw new Exception();
                    }

                    // ログテーブルに登録する
                    setEntryLog($upData);
                }

                // mst_car
                if (!empty($ofcId) && !empty($upCar[$type])) {
                    foreach ($upCar[$type] as $seq => $val) {
                        $val['office_id'] = $ofcId;
                        $upCarData[$seq] = $val;
                    }

                    $res = multiUpsert($loginUser, 'mst_car', $upCarData);
                    if (isset($res['err'])) {
                        $err[] = 'システムエラーが発生しました';
                        throw new Exception();
                    }

                    // ログテーブルに登録する
                    setMultiEntryLog($upCarData);
                }

                // mst_office_patrol
                if (!empty($ofcId) && !empty($upPtl[$type])) {
                    foreach ($upPtl[$type] as $seq => $val) {
                        $val['office_id'] = $ofcId;
                        $upPtlData[$seq] = $val;
                    }

                    $res = multiUpsert($loginUser, 'mst_office_patrol', $upPtlData);
                    if (isset($res['err'])) {
                        $err[] = 'システムエラーが発生しました';
                        throw new Exception();
                    }

                    // ログテーブルに登録する
                    setMultiEntryLog($upPtlData);
                }

                // セッションに事業所を積みなおし
                $staffType = $_SESSION['login']['type'];
                // 権限により全拠点、全事業所を対象とする
                if ($staffType == 'システム管理者' || $staffType == '法人管理者') {
                    $_SESSION['login']['place']  = getPlaceList();
                    $_SESSION['login']['office'] = getOfficeList();
                } else {
                    // 拠点リスト、事業所リストの初期化
                    $staffInfo           = array();
                    $staffInfo['place']  = array();
                    $staffInfo['office'] = array();
                    $_SESSION['place']   = null;

                    // 所属情報の取得
                    $where = array();
                    $where['delete_flg'] = 0;
                    $where['staff_id'] = $_SESSION['login']['unique_id'];
                    $target = 'place_id,place_name,office1_id,office1_name,office2_id,office2_name';
                    $temp = select('mst_staff_office', $target, $where);
                    foreach ($temp as $val) {
                        $plcId  = $val['place_id'];
                        if ($plcId) {
                            $staffInfo['place'][$plcId] = $val['place_name'];
                            $_SESSION['place'] = $plcId;
                        }
                        $ofcId1 = $val['office1_id'];
                        if ($ofcId1) {
                            $staffInfo['office'][$ofcId1] = $val['office1_name'];
                        }
                        $ofcId2 = $val['office2_id'];
                        if ($ofcId2) {
                            $staffInfo['office'][$ofcId2] = $val['office2_name'];
                        }
                    }
                    $_SESSION['login']['place']  = $staffInfo['place'];
                    $_SESSION['login']['office'] = $staffInfo['office'];
                }
            }
        }

        // 画面遷移
        $nextPage = $plcId
            ? $server['scriptName'] . '?id=' . $plcId . '&office1=' . $ofcId1 . '&office2=' . $ofcId2
            : $server['scriptName'];
        header("Location:" . $nextPage);
        ////    $nextPage = $server['scriptName'].'?id='.$plcId.'&office1='.$ofcId1.'&ofcId2='.$ofcId2;
        ////    header("Location:". $nextPage);
        //    exit();
    }

    // 履歴追加
    if ($btnRcd) {

        $res = multiUpsert($loginUser, $table, $upData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upData);

        // 画面遷移
        $nextPage = $plcId
            ? $server['scriptName'] . '?id=' . $plcId
            : $server['scriptName'];
        header("Location:" . $nextPage);

    }

    // 自動車削除
    if ($btnDelCar) {
        $res = upsert($loginUser, 'mst_car', $upCarData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setEntryLog($upCarData);
    }

    // 巡回事業所削除
    if ($btnDelPtl) {
        $res = upsert($loginUser, 'mst_office_patrol', $upPtlData);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setEntryLog($upPtlData);
    }
    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/

    // 事業所
    if ($plcId) {

        // mst_office
        $where = array();
        $where['place_id'] = $plcId;
        if ($ofcId1) {
            $where['unique_id'][] = $ofcId1;
        }
        if ($ofcId2) {
            $where['unique_id'][] = $ofcId2;
        }
        $orderBy = 'unique_id ASC';
        $temp = select($table, '*', $where, $orderBy);
        foreach ($temp as $val) {
            // 分類別定義
            if ($val['type'] == '看多機') {
                $key    = 0;
                $ofcId1 = $ofcId1 ?: $val['unique_id'];
            } else {
                $key    = 1;
                $ofcId2 = $ofcId2 ?: $val['unique_id'];
            }

            // 登録者更新者情報
            $val['create_day']   = formatDateTime($val['create_date'], 'Y/m/d');
            $val['create_time']  = formatDateTime($val['create_date'], 'H:i');
            $val['create_name']  = getStaffName($val['create_user']);
            $val['update_day']   = formatDateTime($val['update_date'], 'Y/m/d');
            $val['update_time']  = formatDateTime($val['update_date'], 'H:i');
            $val['update_name']  = getStaffName($val['update_user']);

            // 管理者情報
            $val['staff_id']     = null;
            $val['manager_name'] = null;
            if (!empty($val['manager_id'])) {
                $mngId = $val['manager_id'];
                $stfVal = $staffMst[$mngId];
                $val['manager_name'] = $stfVal['last_name'] . ' ' . $stfVal['first_name'];
                $val['staff_id'] = $stfVal['staff_id'];
            }

            // 表示データ格納
            $dispData[$key] = $val;
        }

        // 履歴番号格納
        $where = array();
        $where['place_id'] = $plcId;
        $orderBy = 'unique_id ASC';
        $temp = select($table, '*', $where, $orderBy);
        foreach ($temp as $val) {
            $key = $val['type'] == '看多機' ? 0 : 1;
            $rcdList[$key][$val['record_no']] = $val['unique_id'];
        }

        // mst_car
        if ($ofcId1 || $ofcId2) {
            $where = array();
            if ($ofcId1) {
                $where['office_id'][] = $ofcId1;
            }
            if ($ofcId2) {
                $where['office_id'][] = $ofcId2;
            }
            $orderBy = 'unique_id ASC';
            $temp = getData('mst_car', $where, $orderBy);
            foreach ($temp as $val) {
                $key = null;
                if ($val['office_id'] == $ofcId1) {
                    $key   = 0;
                    $tgtId = $val['unique_id'];
                } elseif ($val['office_id'] == $ofcId2) {
                    $key   = 1;
                    $tgtId = $val['unique_id'];
                }
                if ($key === 0 || $key === 1) {
                    $dispCar[$key][$tgtId] = $val;
                }
            }
        }

        // mst_office_patrol
        if ($ofcId1 || $ofcId2) {
            $where = array();
            if ($ofcId1) {
                $where['office_id'][] = $ofcId1;
            }
            if ($ofcId2) {
                $where['office_id'][] = $ofcId2;
            }
            $orderBy = 'unique_id ASC';
            $temp = getData('mst_office_patrol', $where, $orderBy);
            foreach ($temp as $val) {
                $key = null;
                if ($val['office_id'] == $ofcId1) {
                    $key   = 0;
                    $tgtId = $val['unique_id'];
                } elseif ($val['office_id'] == $ofcId2) {
                    $key   = 1;
                    $tgtId = $val['unique_id'];
                }
                if ($key === 0 || $key === 1) {
                    $dispPtl[$key][$tgtId] = $val;
                }
            }
        }
    }

    /* -- データ変換 --------------------------------------------*/

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
