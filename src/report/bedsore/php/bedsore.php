<?php

//=====================================================================
// 褥瘡計画
//=====================================================================
try {
/* =================================================== 
 * 初期処理
 * ===================================================
 */

/* --共通ファイル呼び出し------------------------------------- */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

/* --変数定義------------------------------------------------- */

// 初期化
$err = array();
$_SESSION['notice']['error'] = array();
$dispData = array();
$dispFml = array();
$dispFcl = array();
$tgtData = array();
$upAry = array();
$upData = array();
$userId = NULL;
$otherWindowURL = array();

// 対象テーブル
$table1 = 'doc_bedsore';
$table2 = 'mst_user';
$table3 = 'mst_user_insure1';

// 初期値
$dispData = initTable($table1);
$dispFml = array();
$dispFcl = array();
$dispData['other_id'] = NULL;
$dispData['user_name'] = NULL;
$week = formatDateTime(NOW, 'w');
$weekDisp = '(' . $weekAry[$week] . ')';
$dispData['disp_report'] = formatDateTime(NOW, 'Y年m月d日') . $weekDisp;
$dispData['disp_first'] = $dispData['disp_report'];
$dispData['staff_name'] = NULL;
$dispData['create_day'] = NULL;
$dispData['create_time'] = NULL;
$dispData['create_name'] = NULL;
$dispData['update_day'] = NULL;
$dispData['update_time'] = NULL;
$dispData['update_name'] = NULL;
$dispData['nengo'] = NULL;
$dispData['wareki'] = NULL;
$dispData['birthday_disp'] = NULL;
$dispData['age'] = NULL;
$dispData['care_rank'] = NULL;
$dispData['staff1_cd'] = NULL;
$dispData['staff2_cd'] = NULL;
$dispData['staff1_name'] = NULL;
$dispData['staff2_name'] = NULL;

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

/* -- 検索用パラメータ --------------------------------------- */

// KEY
$keyId = filter_input(INPUT_GET, 'id');

// 複製
$copyId = filter_input(INPUT_GET, 'copy');
if (!$keyId && $copyId) {
    $keyId = $copyId;
}

// 利用者ID
$userId = filter_input(INPUT_GET, 'user');
if (!$userId) {
    $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : NULL;
}

/* -- 更新用パラメータ --------------------------------------- */

// 更新ボタン
$btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

// 複製ボタン
$btnCopy = h(filter_input(INPUT_POST, 'btnCopy'));

// 削除ボタン
$btnDel = h(filter_input(INPUT_POST, 'btnDel'));

// 更新配列
$upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

// その他
$upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();

/* -- その他パラメータ --------------------------------------- */

// 戻るボタン
$btnReturn = h(filter_input(INPUT_POST, 'btnReturn'));

// 印刷ボタン(親画面)
$btnPrint = h(filter_input(INPUT_POST, 'btnPrint'));

// 印刷フラグ(子画面)
$prt = filter_input(INPUT_GET, 'prt');

/* =================================================== 
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ---------------------------------------- */

// 更新配列
if (($btnEntry || $btnCopy) && $upAry) {

    // 利用者
    $userId = $upAry['user_id'];

    // 対象KEY
    if ($keyId && $btnEntry && !$copyId) {
        $upAry['unique_id'] = $keyId;
    }

    // 現在：褥瘡部位
    if (!empty($upDummy['bedsore_position_now'])) {
        $upAry['bedsore_position_now'] = implode('^', $upDummy['bedsore_position_now']);
    }

    // 過去：褥瘡部位
    if (!empty($upDummy['bedsore_position_past'])) {
        $upAry['bedsore_position_past'] = implode('^', $upDummy['bedsore_position_past']);
    }

    // 更新配列
    $upData = $upAry;
}

/* -- 削除用配列作成 ---------------------------------------- */

// 削除配列
if ($btnDel) {
    $upData['unique_id'] = $btnDel;
    $upData['delete_flg'] = '1';
}

/* =================================================== 
 * イベント本処理(データ登録)
 * ===================================================
 */

// 入力チェック
if (($btnEntry || $btnCopy) && $upData) {

//    // 名称
//    if (empty($upData['name'])){
//        $notice[] = '名称の指定がありません';
//    }
//    // セッションへ格納
//    if ($notice){
//        $_SESSION['notice']['error'] = $notice;
//        $btnEntry = NULL;
//    }
}
// 更新処理
if (($btnEntry || $btnCopy) && $upData) {

    // DBへ格納
    $res = upsert($loginUser, $table1, $upData);
    if (isset($res['err'])) {
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }

    $keyId = $res;
    
    // ログテーブルに登録する
    setEntryLog($upData);

    // 画面遷移
    $_SESSION['user'] = $userId;
    $nextPage = $server['scriptName'] . '?id=' . $keyId . '&user=' . $userId;
    header("Location:" . $nextPage);
    exit();
}

// データ削除
if ($btnDel && $upData) {
    // テーブルを更新
    $res = upsert($loginUser, $table1, $upData);
    if (isset($res['err'])) {
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }
    
    // ログテーブルに登録する
    setEntryLog($upData);

    // 自画面へ遷移
    $_SESSION['user'] = NULL;
    unset($_SESSION['user']);
    $nextPage = $server['scriptName'];
    header("Location:" . $nextPage);
    exit();
}

// 戻るボタン
if ($btnReturn) {
    $nextPage = '/report/bedsore_list/index.php';
    header("Location:" . $nextPage);
    exit();
}

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */



/* -- データ取得 -------------------------------------------- */

/* -- 汎用マスタ --------------------------- */
$gnrList = getCode('褥瘡計画');
$where = array();
$where['delete_flg'] = 0;
$where['group_div'] = '褥瘡計画';
$target = 'unique_id,name,type,remarks';
$orderBy = 'unique_id ASC';
$temp = select('mst_code', $target, $where, $orderBy);
foreach ($temp as $val) {
    $type = $val['type'];
    $tgtId = $val['unique_id'];
    $gmrList[$type][$tgtId] = $val;
}

/* -- 利用者マスタ ------------------------- */
$where = array();
$where['delete_flg'] = 0;
$target = '*';
$target .= ',birthday,prefecture,area,address1,address2,address3';
$temp = select('mst_user', $target, $where);
foreach ($temp as $val) {
    $tgtId = $val['unique_id'];
    $lastName = $val['last_name'];
    $firstName = $val['first_name'];
    $val['name'] = $lastName . ' ' . $firstName;
    // 年号
    $nengo = !empty($val['birthday']) ? chgAdToJpNengo($val['birthday']) : NULL;
    // 和暦
    $wareki = !empty($val['birthday']) ? chgAdToJpYear($val['birthday']) . '年' : NULL;
    // 生年月日
    //$val['birthday_disp'] = $nengo.$wareki.$val['birthday'];
    $val['birthday_disp'] = !empty($val['birthday']) ? chgAdToJpDate($val['birthday']) : NULL;
    // 年齢
    $val['age'] = !empty($val['birthday']) ? getAge($val['birthday']) . '歳' : NULL;
    // 住所
    $val['address'] = $val['prefecture'] . $val['area'] . $val['address1'] . $val['address2'] . $val['address3'];
    $userList[$tgtId] = $val;
}
if ($userId && isset($userList[$userId])) {
    $dispData['other_id'] = $userList[$userId]['other_id'];
    $dispData['user_name'] = $userList[$userId]['name'];
    $dispData['birthday_disp'] = $userList[$userId]['birthday_disp'];
    $dispData['age'] = $userList[$userId]['age'];
    $dispData['address'] = $userList[$userId]['address'];
    $dispData['care_rank'] = getCareRank($userId);
}

/* -- スタッフマスタ ----------------------- */
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,last_name,first_name';
$temp = select('mst_staff', '*', $where);
foreach ($temp as $val) {
    $tgtId = $val['unique_id'];
    $val['name'] = $val['last_name'].$val['first_name'];
    $staffList[$tgtId] = $val;
}

/* -- 褥瘡計画 ------------------------ */
if ($keyId) {
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $keyId;
    $temp = select($table1, '*', $where);

    if (isset($temp[0])) {

        // テーブル値
        $tgtData = $temp[0];

        // スタッフ名称
        if ($tgtData['staff_id']) {
            $stfId = $tgtData['staff_id'];
            $tgtData['staff1_cd'] = isset($staffList[$stfId]['staff_id']) ? $staffList[$stfId]['staff_id'] : NULL;
            $tgtData['staff1_name'] = getStaffName($stfId);
        } else {
            $tgtData['staff_id'] = $loginUser['staff_id'];
            $stfId = $tgtData['staff_id'];
            $tgtData['staff1_cd'] = isset($staffList[$stfId]['staff_id']) ? $staffList[$stfId]['staff_id'] : NULL;
            $tgtData['staff1_name'] = getStaffName($stfId);
        }

        if ($tgtData['report_staff']) {
            $stfId = $tgtData['report_staff'];
            $tgtData['staff2_cd'] = isset($staffList[$stfId]['staff_id']) ? $staffList[$stfId]['staff_id'] : NULL;
            $tgtData['staff2_name'] = getStaffName($stfId);
        } else {
            $tgtData['report_staff'] = $loginUser['staff_id'];
            $stfId = $tgtData['report_staff'];
            $tgtData['staff2_cd'] = isset($staffList[$stfId]['staff_id']) ? $staffList[$stfId]['staff_id'] : NULL;
            $tgtData['staff2_name'] = getStaffName($stfId);
        }

        // 初回登録
        $tgtDate = $tgtData['create_date'];
        $tgtData['create_day'] = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['create_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['create_user'];
        $tgtData['create_name'] = isset($staffList[$tgtUser]['name']) ? $staffList[$tgtUser]['name'] : NULL;

        // 更新情報
        $tgtDate = $tgtData['update_date'];
        $tgtData['update_day'] = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['update_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['update_user'];
        $tgtData['update_name'] = isset($staffList[$tgtUser]['name']) ? $staffList[$tgtUser]['name'] : NULL;

        // 格納
        $dispData = array_merge($dispData, $tgtData);
    }
}

/* -- その他 -------------------------------------------- */

// 帳票印刷
if (empty($prt) && $btnPrint && $keyId) {
    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'] . $server['scriptName'] . '?id=' . $keyId . '&prt=true';
}

// 印刷処理(別Windowで印刷)
if ($prt) {

    // 出力条件
    $search = array();
    $search['unique_id'] = $keyId;
    $res = printPDF('021', $search);
    $prt = false;
}

/* =================================================== 
 * 例外処理
 * ===================================================
 */
} catch (Exception $e) {
    if ($execEnv === 'pro' || $execEnv === 'stg') {
        $_SESSION['err'] = !empty($err) ? $err : array();
        header("Location:" . ERROR_PAGE);
        exit();
    } else {
        debug($e);
        exit();
    }
}
