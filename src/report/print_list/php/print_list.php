<?php
//=====================================================================
// 各種帳票
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
$tgtData  = array();

// 対象テーブル
$tblVst = 'doc_visit1';
$tblBed = 'doc_bedsore';
$tblIns = 'doc_instruct';
$tblPln = 'doc_plan';
$tblRpt = 'doc_report';

// 初期値
$dispData  = array();
$dispData2 = array();
$dispVst   = array();
$dispBed   = array();
$dispIns   = array();
$dispPln   = array();
$dispRpt   = array();
$dispData['other_id']    = NULL;
$dispData['user_name']   = NULL;
$week = formatDateTime(NOW, 'w');
$weekDisp = '('.$weekAry[$week].')';
$dispData['disp_report'] = formatDateTime(NOW, 'Y年m月d日').$weekDisp;
$dispData['disp_first']  = $dispData['disp_report'];
$dispData['staff_name']  = NULL;
$dispData['create_day']  = NULL;
$dispData['create_time'] = NULL;
$dispData['create_name'] = NULL;
$dispData['update_day']  = NULL;
$dispData['update_time'] = NULL;
$dispData['update_name'] = NULL;
$plnData = array();
$rptData = array();
$otherId = NULL;

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */


/*-- 検索用パラメータ ---------------------------------------*/

// KEY
$keyId = filter_input(INPUT_GET, 'id');

// 利用者ID
$userId = filter_input(INPUT_GET, 'user');
if (!$userId) {
    $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : NULL;
}

// 検索ボタン
$btnSearch = h(filter_input(INPUT_POST, 'btnSearch'));

// 検索配列
$search  = filter_input(INPUT_POST, 'search', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$search  = $search ? $search : array();
$search['user_id']  = !empty($search['user_id'])  ? $search['user_id']  : $userId;
$search['other_id'] = !empty($search['other_id']) ? $search['other_id'] : NULL;
$otherId = $search['other_id'];

/*-- 更新用パラメータ ---------------------------------------*/

// 更新ボタン
$btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

// 更新配列(訪問看護記録1)
$upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

// 更新配列(褥瘡計画書)
$upBed = filter_input(INPUT_POST, 'upBed', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upBed = $upBed ? $upBed : array();

// 更新配列(指示書)
$upIns = filter_input(INPUT_POST, 'upIns', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upIns = $upIns ? $upIns : array();

// 更新配列(計画書)
$upPln = filter_input(INPUT_POST, 'upPln', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upPln = $upPln ? $upPln : array();

// 更新配列(報告書)
$upRpt = filter_input(INPUT_POST, 'upRpt', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upRpt = $upRpt ? $upRpt : array();

// その他
$upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();

/*-- その他パラメータ ---------------------------------------*/



/* =================================================== 
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ----------------------------------------*/


/* =================================================== 
 * イベント本処理(データ登録)
 * ===================================================
 */

// 検索処理
if ($btnSearch){
    header("Location:". '/report/list/index.php?user='.$search['user_id'] );
    exit;
}

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */


/* -- 利用者マスタ -------------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,last_name,first_name,other_id';
$temp = select('mst_user',$target,$where);
foreach ($temp as $val){
    $tgtId      = $val['unique_id'];
    $tgtId2     = $val['other_id'];
    $lastName   = $val['last_name'];
    $firstName  = $val['first_name'];
    $val['name'] = $lastName.' '.$firstName;
    $userList[$tgtId]   = $val;
    $userList2[$tgtId2] = $val;
}

if ($userId && isset($userList[$userId])){
    $dispData['other_id']  = $userList[$userId]['other_id'];
    $dispData['user_name'] = $userList[$userId]['name'];
} elseif ($otherId && isset($userList[$userId])) {
    $dispData['other_id']  = $otherId;
    $dispData['user_name'] = $userList2[$otherId]['name'];
    $userId = $userList2[$otherId]['unique_id'];
}

// 対象key
$keyId = $userId;

/* -- スタッフマスタ -----------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,last_name,first_name';
$temp = select('mst_staff','*',$where);
foreach ($temp as $val){
    $tgtId       = $val['unique_id'];
    $val['name'] = $val['last_name'].$val['first_name'];
    $staffList[$tgtId] = $val;
}

/* -- 訪問看護記録 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id']    = $userId;
    $target = '*';
    $orderBy = 'report_day desc, unique_id desc';
    $temp = select($tblVst,$target,$where,$orderBy);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];
        
        $staffId = $val['staff_id'];
        $staffNm = '';
        if ($staffId){
            $staffNm = isset($staffList[$staffId])
                    ? $staffList[$staffId]['name']
                    : '';
        }
        $val['staff_name'] = $staffNm;

        // 0000-00-00をNULLにする
        $val['report_day']  = $val['report_day']  == "0000-00-00" ? NULL : $val['report_day'];

        // 格納
        $dispVst[$tgtId] = $val;
    }
}

/* -- 褥瘡計画 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id']    = $userId;
    $target = '*';
    $orderBy = 'plan_day desc, unique_id desc';
    $temp = select($tblBed,$target,$where,$orderBy);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];

        $staffId = $val['staff_id'];
        $staffNm = '';
        if ($staffId){
            $staffNm = isset($staffList[$staffId])
                    ? $staffList[$staffId]['name']
                    : '';
        }
        $val['staff_name'] = $staffNm;

        // 0000-00-00をNULLにする
        $val['plan_day']    = $val['plan_day']     == "0000-00-00" ? NULL : $val['plan_day'];
        $val['bedsore_day'] = $val['bedsore_day']  == "0000-00-00" ? NULL : $val['bedsore_day'];

        // 格納
        $dispBed[$tgtId] = $val;
    }
}
/* -- 指示書 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id']    = $userId;
    $target = '*';
    $orderBy = 'direction_start desc, unique_id desc';
    $temp = select($tblIns,$target,$where,$orderBy);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];
        
        $val['pdf_file'] = !empty($val['pdf_file'])
                ? '〇'
                : '';
        
        // 0000-00-00をNULLにする
        $val['direction_start']  = $val['direction_start']  == "0000-00-00" ? NULL : $val['direction_start'];
        $val['direction_end']    = $val['direction_end']  == "0000-00-00" ? NULL : $val['direction_end'];
        
        // 格納
        $dispIns[$tgtId] = $val;
    }
}

/* -- 計画書 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id']    = $userId;
    $target = 'unique_id, care_kb, report_day';
    $orderBy = 'report_day desc, unique_id desc';
    $temp = select($tblPln,$target,$where,$orderBy);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];
        
        $val['report_day'] = isset($val['report_day']) && $val['report_day'] == "0000-00-00" 
            ? NULL
            : $val['report_day']; 
        
        // 様式、該当月
        $val['type'] = '計画書';
        $val['month'] = !empty($val['report_day'])
                ? formatDateTime($val['report_day'], 'Y年m月')
                : '';
        
        // 格納
        $plnData[$tgtId] = $val;
    }
}
/* -- 報告書 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id']    = $userId;
    $target = 'unique_id, care_kb, report_day';
    $orderBy = 'report_day desc, unique_id desc';
    $temp = select($tblRpt,$target,$where,$orderBy);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];

        $val['report_day'] = isset($val['report_day']) && $val['report_day'] == "0000-00-00" 
            ? NULL
            : $val['report_day']; 
        
        // 様式、該当月
        $val['type'] = '報告書';
        $val['month'] = !empty($val['report_day'])
                ? formatDateTime($val['report_day'], 'Y年m月')
                : '';
        
        // 格納
        $rptData[$tgtId] = $val;
    }
}
/* -- その他 --------------------------------------------*/
if ($keyId){
    
    $dispData2 = array_merge_recursive($plnData, $rptData);

    if ($dispData2){
        foreach ($dispData2 as $key => $val){
            $sort_keys[$key] = $val['report_day'];
        }
        
        // ソート（該当月-降順）
        array_multisort($sort_keys, SORT_DESC, $dispData2);
    }
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
