<?php
//=====================================================================
// 帳票一括確認
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
$userId  = NULL;
$tgtUser = array();
$userMst = array();
$tgtData = array();

// 表示件数
$line = 20;

$reportNames['褥瘡計画']         = TRUE;
$reportNames['指示書']        = TRUE;
$reportNames['計画書']        = TRUE;
$reportNames['報告書']        = TRUE;
$reportNames['経過記録']      = TRUE;
$reportNames['看多機記録']    = TRUE;
$reportNames['訪問看護記録Ⅰ'] = TRUE;
$reportNames['訪問看護記録Ⅱ'] = TRUE;

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// 検索配列
$search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$search = $search ? $search : array();

$search['start_day'] = !empty($search['start_day']) ? formatDateTime($search['start_day'], "Y-m-d") : THISMONTHFIRST;
$search['end_day']   = !empty($search['end_day'])   ? formatDateTime($search['end_day'], "Y-m-d")   : NULL;//THISMONTHLAST;
$search['report']    = !empty($search['report'])    ? $search['report']    : NULL;
$search['status']    = !empty($search['status'])    ? $search['status']    : NULL;

/*-- 更新用パラメータ ---------------------------------------*/

/*-- その他パラメータ ---------------------------------------*/

// ページャー
$page = h(filter_input(INPUT_GET, 'page'));


/* =================================================== 
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ----------------------------------------*/


/* =================================================== 
 * イベント本処理(データ登録)
 * ===================================================
 */


/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- データ取得 --------------------------------------------*/

/* -- 汎用マスタ ---------------------------*/
$gnrList = getCode('帳票一括確認');

/* -- 利用者情報 ---------------------------*/
$userList =getData('mst_user');

/* -- スタッフ情報 ---------------------------*/
$staffList =getData('mst_staff');

/* -- 事業所情報 ---------------------------*/
$ofcList =getData('mst_office');

/* -- 褥瘡計画 ----------------------------------*/
$where  = array();
if(!empty($search['start_day'])){
    $where['create_date >='] = $search['start_day'];    
}
if(!empty($search['end_day'])){
    $where['create_date <='] = $search['end_day'];    
}
$temp = getData('doc_bedsore', $where);
foreach($temp as $idx => $val){
    $unqId                          = $val['unique_id'];
    $dat                = $val;
    $dat['report_name'] = '褥瘡計画';
    $dat['edit_url']    = '/report/bedsore/index.php?id='.$val['unique_id'].'&user='.$val['user_id'];
    $dat['copy_url']    = '/report/bedsore/index.php?copy='.$val['user_id'];
    $userInfo           = isset($userList[$val['user_id']]) ? $userList[$val['user_id']] : array();
    $staffInfo          = isset($staffList[$val['staff_id']]) ? $staffList[$val['staff_id']] : array(); 
    $dat['user_name']   = !empty($userInfo) ? $userInfo['last_name'].' '.$userInfo['first_name'] : NULL;
    $dat['user_kana']   = !empty($userInfo) ? $userInfo['last_kana'].' '.$userInfo['first_kana'] : NULL;
    $dat['person_name'] = !empty($staffInfo) ? $staffInfo['last_name'].' '.$staffInfo['first_name'] : NULL;

    // 帳票名が一致しない場合はcontinue
    if(!empty($search['report'])){
        if($search['report'] !== $dat['report_name']){
            continue;
        }
    }
    
    // 完成も表示にチェックが無い場合に除外
    if(empty($search['status'])){
        if($dat['status'] == "完成"){
            continue;
        }
    }
    
    $tgtData[$unqId]    = $dat;
}

/* -- 指示書 ---------------------------------*/
$where  = array();
if(!empty($search['start_day'])){
    $where['create_date >='] = $search['start_day'];    
}
if(!empty($search['end_day'])){
    $where['create_date <='] = $search['end_day'];    
}
$temp = getData('doc_instruct', $where);
foreach($temp as $idx => $val){
    $unqId              = $val['unique_id'];
    $dat                = array();
    $dat                = $val;
    $dat['report_name'] = '指示書';
    $dat['edit_url']    = '/report/instruct/index.php?id='.$val['unique_id'].'&user='.$val['user_id'];
    $dat['copy_url']    = '/report/instruct/index.php?copy='.$val['user_id'];
    $userInfo           = isset($userList[$val['user_id']]) ? $userList[$val['user_id']] : array();
    $staffInfo          = isset($staffList[$val['staff_id']]) ? $staffList[$val['staff_id']] : array(); 
    $dat['user_name']   = !empty($userInfo) ? $userInfo['last_name'].' '.$userInfo['first_name'] : NULL;
    $dat['user_kana']   = !empty($userInfo) ? $userInfo['last_kana'].' '.$userInfo['first_kana'] : NULL;
    $dat['person_name'] = !empty($staffInfo) ? $staffInfo['last_name'].' '.$staffInfo['first_name'] : NULL;

    // 帳票名が一致しない場合はcontinue
    if(!empty($search['report'])){
        if($search['report'] !== $dat['report_name']){
            continue;
        }
    }

    // 完成も表示にチェックが無い場合に除外
    if(empty($search['status'])){
        if($dat['status'] == "完成"){
            continue;
        }
    }

    $tgtData[$unqId]    = $dat;
}

/* -- 計画書 ---------------------------------*/
$where  = array();
if(!empty($search['start_day'])){
    $where['create_date >='] = $search['start_day'];    
}
if(!empty($search['end_day'])){
    $where['create_date <='] = $search['end_day'];    
}
$temp = getData('doc_plan', $where);
foreach($temp as $idx => $val){
    $unqId              = $val['unique_id'];
    $dat                = $val;
    $dat['report_name'] = '計画書';
    $dat['edit_url']    = '/report/plan/index.php?id='.$val['unique_id'].'&user='.$val['user_id'];
    $dat['copy_url']    = '/report/plan/index.php?copy='.$val['user_id'];
    $userInfo           = isset($userList[$val['user_id']]) ? $userList[$val['user_id']] : array();
    $staffInfo          = isset($staffList[$val['staff_id']]) ? $staffList[$val['staff_id']] : array(); 
    $dat['user_name']   = !empty($userInfo) ? $userInfo['last_name'].' '.$userInfo['first_name'] : NULL;
    $dat['user_kana']   = !empty($userInfo) ? $userInfo['last_kana'].' '.$userInfo['first_kana'] : NULL;
    $dat['person_name'] = !empty($staffInfo) ? $staffInfo['last_name'].' '.$staffInfo['first_name'] : NULL;

    // 帳票名が一致しない場合はcontinue
    if(!empty($search['report'])){
        if($search['report'] !== $dat['report_name']){
            continue;
        }
    }

    // 完成も表示にチェックが無い場合に除外
    if(empty($search['status'])){
        if($dat['status'] == "完成"){
            continue;
        }
    }

    $tgtData[$unqId]    = $dat;
}

/* -- 報告書 ---------------------------------*/
$where  = array();
if(!empty($search['start_day'])){
    $where['create_date >='] = $search['start_day'];    
}
if(!empty($search['end_day'])){
    $where['create_date <='] = $search['end_day'];    
}
$temp = getData('doc_report', $where);
foreach($temp as $idx => $val){
    $unqId              = $val['unique_id'];
    $dat                = $val;
    $dat['report_name'] = '報告書';
    $dat['edit_url']    = '/report/report/index.php?id='.$val['unique_id'].'&user='.$val['user_id'];
    $dat['copy_url']    = '/report/report/index.php?copy='.$val['user_id'];
    $userInfo           = isset($userList[$val['user_id']]) ? $userList[$val['user_id']] : array();
    $staffInfo          = isset($staffList[$val['staff_id']]) ? $staffList[$val['staff_id']] : array(); 
    $dat['user_name']   = !empty($userInfo) ? $userInfo['last_name'].' '.$userInfo['first_name'] : NULL;
    $dat['user_kana']   = !empty($userInfo) ? $userInfo['last_kana'].' '.$userInfo['first_kana'] : NULL;
    $dat['person_name'] = !empty($staffInfo) ? $staffInfo['last_name'].' '.$staffInfo['first_name'] : NULL;

    // 帳票名が一致しない場合はcontinue
    if(!empty($search['report'])){
        if($search['report'] !== $dat['report_name']){
            continue;
        }
    }

    // 完成も表示にチェックが無い場合に除外
    if(empty($search['status'])){
        if($dat['status'] == "完成"){
            continue;
        }
    }

    $tgtData[$unqId]    = $dat;
}

/* -- 経過記録 ---------------------------------*/
$where  = array();
if(!empty($search['start_day'])){
    $where['create_date >='] = $search['start_day'];    
}
if(!empty($search['end_day'])){
    $where['create_date <='] = $search['end_day'];    
}
$temp = getData('doc_progress', $where);
foreach($temp as $idx => $val){
    $unqId              = $val['unique_id'];
    $dat                = $val;
    $dat['report_name'] = '経過記録';
    $dat['edit_url']    = '/report/progress/index.php?id='.$val['unique_id'].'&user='.$val['user_id'];
    $dat['copy_url']    = '/report/progress/index.php?copy='.$val['user_id'];
    $userInfo           = isset($userList[$val['user_id']]) ? $userList[$val['user_id']] : array();
    $staffInfo          = isset($staffList[$val['staff_id']]) ? $staffList[$val['staff_id']] : array(); 
    $dat['user_name']   = !empty($userInfo) ? $userInfo['last_name'].' '.$userInfo['first_name'] : NULL;
    $dat['user_kana']   = !empty($userInfo) ? $userInfo['last_kana'].' '.$userInfo['first_kana'] : NULL;
    $dat['person_name'] = !empty($staffInfo) ? $staffInfo['last_name'].' '.$staffInfo['first_name'] : NULL;

    // 帳票名が一致しない場合はcontinue
    if(!empty($search['report'])){
        if($search['report'] !== $dat['report_name']){
            continue;
        }
    }

    // 完成も表示にチェックが無い場合に除外
    if(empty($search['status'])){
        if($dat['status'] == "完成"){
            continue;
        }
    }

    $tgtData[$unqId]    = $dat;
}

/* -- 看多機記録 -------------------------------*/
$where  = array();
if(!empty($search['start_day'])){
    $where['create_date >='] = $search['start_day'];    
}
if(!empty($search['end_day'])){
    $where['create_date <='] = $search['end_day'];    
}
$temp = getData('doc_kantaki', $where);
foreach($temp as $idx => $val){
    $unqId              = $val['unique_id'];
    $dat                = $val;
    $dat['report_name'] = '看多機記録';
    $dat['edit_url']    = '/report/kantaki/index.php?id='.$val['unique_id'].'&user='.$val['user_id'];
    $dat['copy_url']    = '/report/kantaki/index.php?copy='.$val['user_id'];
    $userInfo           = isset($userList[$val['user_id']]) ? $userList[$val['user_id']] : array();
    $staffInfo          = '';//isset($staffList[$val['staff_id']]) ? $staffList[$val['staff_id']] : array(); 
    $dat['user_name']   = !empty($userInfo) ? $userInfo['last_name'].' '.$userInfo['first_name'] : NULL;
    $dat['user_kana']   = !empty($userInfo) ? $userInfo['last_kana'].' '.$userInfo['first_kana'] : NULL;
    $dat['person_name'] = !empty($staffInfo) ? $staffInfo['last_name'].' '.$staffInfo['first_name'] : NULL;

    // 帳票名が一致しない場合はcontinue
    if(!empty($search['report'])){
        if($search['report'] !== $dat['report_name']){
            continue;
        }
    }

    // 完成も表示にチェックが無い場合に除外
    if(empty($search['status'])){
        if($dat['status'] == "完成"){
            continue;
        }
    }

    $tgtData[$unqId]    = $dat;
}

/* -- 訪問看護記録Ⅰ ----------------------------*/
$where  = array();
if(!empty($search['start_day'])){
    $where['create_date >='] = $search['start_day'];    
}
if(!empty($search['end_day'])){
    $where['create_date <='] = $search['end_day'];    
}
$temp = getData('doc_visit1', $where);
foreach($temp as $idx => $val){
    $unqId                          = $val['unique_id'];
    $dat                = $val;
    $dat['report_name'] = '訪問看護記録Ⅰ';
    $dat['edit_url']    = '/report/visit1/index.php?id='.$val['unique_id'].'&user='.$val['user_id'];
    $dat['copy_url']    = '/report/visit1/index.php?copy='.$val['user_id'];
    $userInfo           = isset($userList[$val['user_id']]) ? $userList[$val['user_id']] : array();
    $staffInfo          = isset($staffList[$val['staff_id']]) ? $staffList[$val['staff_id']] : array(); 
    $dat['user_name']   = !empty($userInfo) ? $userInfo['last_name'].' '.$userInfo['first_name'] : NULL;
    $dat['user_kana']   = !empty($userInfo) ? $userInfo['last_kana'].' '.$userInfo['first_kana'] : NULL;
    $dat['person_name'] = !empty($staffInfo) ? $staffInfo['last_name'].' '.$staffInfo['first_name'] : NULL;

    // 帳票名が一致しない場合はcontinue
    if(!empty($search['report'])){
        if($search['report'] !== $dat['report_name']){
            continue;
        }
    }

    // 完成も表示にチェックが無い場合に除外
    if(empty($search['status'])){
        if($dat['status'] == "完成"){
            continue;
        }
    }

    $tgtData[$unqId]    = $dat;
}

/* -- 訪問看護記録Ⅱ ----------------------------*/
$where  = array();
if(!empty($search['start_day'])){
    $where['create_date >='] = $search['start_day'];    
}
if(!empty($search['end_day'])){
    $where['create_date <='] = $search['end_day'];    
}
$temp = getData('doc_visit2', $where);
foreach($temp as $idx => $val){
    $unqId              = $val['unique_id'];
    $dat                = array();
    $dat                = $val;
    $dat['report_name'] = '訪問看護記録Ⅱ';
    $dat['edit_url']    = '/report/visit2/index.php?id='.$val['unique_id'].'&user='.$val['user_id'];
    $dat['copy_url']    = '/report/visit2/index.php?copy='.$val['user_id'];
    $userInfo           = isset($userList[$val['user_id']]) ? $userList[$val['user_id']] : array();
    $staffInfo          = isset($staffList[$val['staff1_id']]) ? $staffList[$val['staff1_id']] : array(); 
    $dat['user_name']   = !empty($userInfo) ? $userInfo['last_name'].' '.$userInfo['first_name'] : NULL;
    $dat['user_kana']   = !empty($userInfo) ? $userInfo['last_kana'].' '.$userInfo['first_kana'] : NULL;
    $dat['person_name'] = !empty($staffInfo) ? $staffInfo['last_name'].' '.$staffInfo['first_name'] : NULL;

    // 帳票名が一致しない場合はcontinue
    if(!empty($search['report'])){
        if($search['report'] !== $dat['report_name']){
            continue;
        }
    }

    // 完成も表示にチェックが無い場合に除外
    if(empty($search['status'])){
        if($dat['status'] == "完成"){
            continue;
        }
    }

    $tgtData[$unqId]    = $dat;
}

/* -- ソート処理 -----------------------------*/
krsort($tgtData);

/* -- その他 --------------------------------------------*/

// ページャー
$dispData = getPager($tgtData, $page, $line);

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
