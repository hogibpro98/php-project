<?php
//=====================================================================
// 訪問看護2
//=====================================================================
try {
/* ===================================================
 * 初期処理
 * ===================================================
 */


/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'].'/common/php/com_start.php');

/*--変数定義-------------------------------------------------*/

// 初期化
$err      = array();
$_SESSION['notice']['error'] = array();
$dispData = array();
$dispPbl  = array();
$tgtData  = array();
$upData   = array();
$upPbl    = array();
$prbData  = array();
$otherWindowURL = array();

// 対象テーブル
$table1 = 'doc_visit2';
$table2 = 'doc_visit2_problem';

// 初期値
$dispData = initTable($table1);
$dispFml  = array();
$dispFcl  = array();
$keyId    = NULL;
$dispData['other_id']    = NULL;
$dispData['user_name']   = NULL;
$week = formatDateTime(NOW, 'w');
$weekDisp = '('.$weekAry[$week].')';
$dispData['disp_report'] = formatDateTime(NOW, 'Y年m月d日').$weekDisp;
$dispData['disp_first']  = $dispData['disp_report'];
$dispData['staff1_name'] = NULL;
$dispData['staff2_name'] = NULL;
$dispData['create_day']  = NULL;
$dispData['create_time'] = NULL;
$dispData['create_name'] = NULL;
$dispData['update_day']  = NULL;
$dispData['update_time'] = NULL;
$dispData['update_name'] = NULL;
$dispData['staff1_cd']   = NULL;
$dispData['staff2_cd']   = NULL;

$selHour = ['','00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
$selMinutes = ['','00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55'];

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// KEY
$keyId = filter_input(INPUT_GET, 'id');

// 印刷フラグ
$prt = filter_input(INPUT_GET, 'prt');

// 利用者ID
$userId = filter_input(INPUT_GET, 'user');
if (!$userId) {
    $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : NULL;
}

/*-- 更新用パラメータ ---------------------------------------*/

// 更新ボタン
$btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

// 削除ボタン
$btnDel = h(filter_input(INPUT_POST, 'btnDel'));

// 更新配列(訪問看護)
$upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

// 更新配列(訪問看護-課題)
$upPrb = filter_input(INPUT_POST, 'upPrb', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upPrb = $upPrb ? $upPrb : array();

// その他
$upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();

// 更新配列(時刻)
$upTime = filter_input(INPUT_POST, 'upTime', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upTime = $upTime ? $upTime : array();

// 利用者ID
if (!empty($_SESSION['user_id'])){
    $userId = $_SESSION['user_id'];
}

/*-- その他パラメータ ---------------------------------------*/

// 戻るボタン
$btnReturn = h(filter_input(INPUT_POST, 'btnReturn'));

// 印刷ボタン
$btnPrint = h(filter_input(INPUT_POST, 'btnPrint'));

/* ===================================================
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ----------------------------------------*/

// 更新配列
if ($btnEntry && $upAry){

    // 利用者
    $userId = $upAry['user_id'];

    // 対象KEY
    if ($keyId){
        $upAry['unique_id'] = $keyId;
    }

    // サービス提供日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['service_day']){
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), trim($upAry['service_day']));
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['service_day'] = $tgtDayAry[0];
    }

    // 次回サービス提供日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['next_day']){
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), trim($upAry['next_day']));
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['next_day'] = $tgtDayAry[0];
    }

    if(!empty($upTime['start_time_h'])
        && !empty($upTime['start_time_m'])
        && !empty($upTime['end_time_h'])
        && !empty($upTime['end_time_m'])
        ){
        $upAry['start_time']   = $upTime['start_time_h'].":".$upTime['start_time_m'];
        $upAry['end_time']     = $upTime['end_time_h'].":".$upTime['end_time_m'];
    }

    if(!empty($upTime['next_start_h'])
        && !empty($upTime['next_start_m'])
        && !empty($upTime['next_end_h'])
        && !empty($upTime['next_end_m'])
        ){
        $upAry['next_start']   = $upTime['next_start_h'].":".$upTime['next_start_m'];
        $upAry['next_end']     = $upTime['next_end_h'].":".$upTime['next_end_m'];
    }


    // 主治医次回診察日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['next_examination']){
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), trim($upAry['next_examination']));
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['next_examination'] = $tgtDayAry[0];
    }

    if (!empty($upDummy['deal_care'])){
        foreach ($upDummy['deal_care'] as $val){
            $upAry['deal_care'] = !empty($upAry['deal_care'])
                    ? $upAry['deal_care'].'^'.$val
                    : $val;
        }
    }

    // 重要フラグ更新
    $upAry['importantly'] = isset($upAry['importantly']) ? $upAry['importantly'] : NULL;

    // 更新配列
    $upData = $upAry;

}

/* -- 削除用配列作成 ----------------------------------------*/

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
if ($btnEntry && $upData){

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
if ($btnEntry && $upData){

    // DBへ格納
    $res = upsert($loginUser, $table1, $upData);

    if (isset($res['err'])){
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }

    // 親コード特定
    $keyId = $res;

    // ログテーブルに登録する
    setEntryLog($upData);

    foreach($upPrb as $idx => $dummy)
    {
        if(empty($upPrb[$idx]['visit2_id'])){
            $upPrb[$idx]['visit2_id'] = $keyId;
        }
    }

    // 課題
    if (!empty($upPrb)){
        $res = multiUpsert($loginUser, $table2, $upPrb);
        if (isset($res['err'])){
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upPrb);
    }

    // 画面遷移
    $_SESSION['user'] = $userId;
    $nextPage = $server['scriptName'].'?id='.$keyId.'&user='.$userId;
    header("Location:". $nextPage);
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
    header("Location:". $nextPage);
    exit();
}

// 戻るボタン
if ($btnReturn){

    if(isset($_SESSION['return_url'])){
        $nextPage = $_SESSION['return_url'];
        unset($_SESSION['return_url']);
        header("Location:". $nextPage);
        exit();
    }
    $nextPage = '/report/visit2_list/index.php';
    header("Location:". $nextPage);
    exit();
}


/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */



/* -- データ取得 --------------------------------------------*/

/* -- 汎用マスタ ---------------------------*/
$gnrList = getCode('訪問看護記録Ⅱ詳細');

/* -- 利用者マスタ -------------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,last_name,first_name,other_id';
$temp = select('mst_user',$target,$where);
foreach ($temp as $val){
    $tgtId      = $val['unique_id'];
    $lastName   = $val['last_name'];
    $firstName  = $val['first_name'];
    $val['name'] = $lastName.' '.$firstName;
    $userList[$tgtId] = $val;
}
if ($userId && isset($userList[$userId])){
    $dispData['other_id']  = $userList[$userId]['other_id'];
    $dispData['user_name'] = $userList[$userId]['name'];
}

/* -- スタッフマスタ -----------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,last_name,first_name';
$temp = select('mst_staff','*',$where);
foreach ($temp as $val){
    $tgtId       = $val['unique_id'];
    $staffList[$tgtId] = $val;
}

/* -- 訪問介護記録Ⅱ ------------------------*/
if ($keyId){

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id']  = $keyId;
    $temp = select($table1, '*', $where);

    if (isset($temp[0])){

        // テーブル値
        $tgtData = $temp[0];

        // 利用者ID、スタッフ１、スタッフ２
        if (!empty($tgtData['user_id'] ?? '')) {
            $userId = $tgtData['user_id'];
        }
        $tgtData['staff1_name'] = getStaffName($tgtData['staff1_id']);
        $tgtData['staff2_name'] = getStaffName($tgtData['staff2_id']);
        $tgtData['staff1_cd'] = isset($staffList[$tgtData['staff1_id']]['staff_id']) ? $staffList[$tgtData['staff1_id']]['staff_id'] : '';
        $tgtData['staff2_cd'] = isset($staffList[$tgtData['staff2_id']]['staff_id']) ? $staffList[$tgtData['staff2_id']]['staff_id'] : '';

        if (empty($tgtData['service_day']) && empty($tgtData['start_time']) && empty($tgtData['end_time'])) {

            $where = array();
            $where['delete_flg'] = 0;
            $where['unique_id']  = $tgtData['target_plan_id'];
            $plans = select('dat_user_plan', '*', $where);
            if (isset($plans[0])) {
                $tgtData['service_day'] = $plans[0]['use_day'];
                $tgtData['start_time'] = $plans[0]['start_time'];
                $tgtData['end_time'] = $plans[0]['end_time'];
            }
        }

        // 初回登録
        $tgtDate = $tgtData['create_date'];
        $tgtData['create_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['create_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtData['create_name'] = getStaffName($tgtData['create_user']);

        // 更新情報
        $tgtDate = $tgtData['update_date'];
        $tgtData['update_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['update_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtData['update_name'] = getStaffName($tgtData['update_user']);

        $tgtData['start_time'] = formatDateTime($tgtData['start_time'], 'H:i');
        $tgtData['end_time']   = formatDateTime($tgtData['end_time'], 'H:i');
        $tgtData['next_start'] = formatDateTime($tgtData['next_start'], 'H:i');
        $tgtData['next_end']   = formatDateTime($tgtData['next_end'], 'H:i');

        if (empty($tgtData['staff1_id'] ?? '')) {
            $tgtData['staff1_id'] = $loginUser['unique_id'];
            $tgtData['staff1_name'] = $loginUser['name'];
            $tgtData['staff1_cd'] = $loginUser['staff_id'];
        }

        // 格納
        $dispData = array_merge($dispData,$tgtData);
    }
}

/* -- 訪問看護（課題） -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['visit2_id']  = $keyId;
    $target = '*';
    $temp = select($table2,$target,$where);
    foreach ($temp as $val){
//        $tgtId = $val['problem_id'];
        $tgtId = $val['visit2_id'];
        $prbData[$tgtId] = $val;
    }
}

/* -- 計画書（課題） -------------------------------*/
if ($userId){

    // 計画書(親)から課題の利用者ID群を取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id']  = $userId;
    // 有効判定追加予定★
    $target = 'unique_id';
    $temp = select('doc_plan',$target,$where);
    $tgtIds = array();
    foreach ($temp as $val){
        $tgtIds[] = $val['unique_id'];
    }

    // 課題データ取得
    if ($tgtIds){

        // DBから取得
        $where = array();
        $where['delete_flg'] = 0;
        $where['plan_id'] = $tgtIds;
        $target = 'unique_id, problem';
        $temp = select('doc_plan_problem',$target,$where);

        // 未反映のデータのみを対象とする
        foreach ($temp as $val){
            $prbId = $val['unique_id'];
            if (!isset($prbData[$prbId])){
                $dat = array();
                $dat['problem_id'] = $prbId;
                $dat['problem']    = $val['problem'];
                $dat['comment']    = '';
                $prbData[$prbId]   = $dat;
            }
        }
    }
}

// 表示用データ
$dispPrb = $prbData;

/* -- その他 --------------------------------------------*/

// 帳票印刷
if ($btnPrint && $keyId){
    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&prt=true';
}
// 印刷処理
if ($prt){

    // 出力条件
    $search = array();
    $search['unique_id'] = $keyId;

    $res = printPDF('020', $search);
}

/* ===================================================
 * 例外処理
 * ===================================================
 */
} catch(Exception $e){
    if ($execEnv === 'pro' || $execEnv === 'stg'){
        $_SESSION['err'] = !empty($err) ? $err : array();
        header("Location:". ERROR_PAGE);
        exit();
    } else {
        debug($e);
        exit();
    }
}
