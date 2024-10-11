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
$_SESSION['notice']['error'] = array();
$err      = array();
$dispData = array();
$tgtUser = array();
$userMst = array();
$tgtData = array();
$reports_csv    = array();
$otherWindowURL = array();
$rptData        = array();
$userId         = NULL;
$placeId        = NULL;

// 表示件数
$line = 20;


/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// 検索配列
$search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$search = $search ? $search : array();

$search['kana']      = isset($search['kana'])      ? $search['kana']      : NULL;
$search['start_day'] = isset($search['start_day']) ? $search['start_day'] : THISMONTHFIRST;
$search['end_day']   = isset($search['end_day'])   ? $search['end_day']   : THISMONTHLAST;
$search['staff_id']  = isset($search['staff_id'])  ? $search['staff_id']  : NULL;
$search['staff_cd']  = isset($search['staff_cd'])  ? $search['staff_cd']  : NULL;
$search['hsp_name']  = isset($search['hsp_name'])  ? $search['hsp_name']  : NULL;
$search['ofc_check'] = isset($search['ofc_check']) ? $search['ofc_check'] : NULL;
$search['doc_type']  = isset($search['doc_type'])  ? $search['doc_type']  : NULL;
$search['ins_type']  = isset($search['ins_type'])  ? $search['ins_type']  : NULL;
$search['status']    = isset($search['status'])    ? $search['status']    : NULL;
$search['target']    = isset($search['target'])    ? $search['target']    : NULL;
$search['person']    = isset($search['person'])    ? $search['person']    : NULL;
$search['doc_type']['計画書']   = isset($search['doc_type']['計画書'])  ? $search['doc_type']['計画書']  : NULL;
$search['doc_type']['報告書']   = isset($search['doc_type']['報告書'])  ? $search['doc_type']['報告書']  : NULL;
$search['doc_type']['褥瘡計画書']  = isset($search['doc_type']['褥瘡計画書']) ? $search['doc_type']['褥瘡計画書'] : NULL;
$search['doc_type']['指示書']   = isset($search['doc_type']['指示書'])  ? $search['doc_type']['指示書']  : NULL;
$search['tgtPerson']  = isset($search['person'])    ? implode('^',$search['person']) : NULL;

/*-- 更新用パラメータ ---------------------------------------*/

// その他
$upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();

// 印刷除外
$ngPrt = filter_input(INPUT_POST, 'ngPrt', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$ngPrt = $ngPrt ? $ngPrt : array();
$allPrt = filter_input(INPUT_POST, 'allPrt', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$allPrt = $allPrt ? $allPrt : array();

$placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : NULL;

/*-- その他パラメータ ---------------------------------------*/

// 一括印刷ボタン
$btnPrt = h(filter_input(INPUT_POST, 'btnPrt'));

// CSVボタン
$btnCsv = h(filter_input(INPUT_POST, 'btnCsv'));

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
$temp = getUserList($placeId);
foreach ($temp as $tgtId => $val){
    // カナ検索
    if ($search['kana']){
        if (mb_strpos($val['last_kana'].$val['first_kana'], $search['kana']) === FALSE){
            continue;
        }
    }
    $tgtUser[] = $tgtId;
    $userMst[$tgtId] = $val;
}

/* -- 契約事業所 ---------------------------*/
foreach ($userMst as $tgtId => $val){

    // 0000-00-00をNULLにするbirthday
    $val['birthday']      = $val['birthday']      == "0000-00-00" ? NULL : $val['birthday'];
    $val['contract_date'] = $val['contract_date'] == "0000-00-00" ? NULL : $val['contract_date'];
    
    $val['office1']  = getOfficeName($tgtId);
    
    $userMst[$tgtId] = $val; 
}

/* -- 居宅支援事業所 ---------------------------*/
$where = array();
$where['delete_flg'] = 0;
$where['user_id'] = $tgtUser;
if ($search['start_day']){
    $where['start_day <='] = $search['start_day'];
}
if ($search['end_day']){
    $where['end_day >='] = $search['end_day'];
}
$target = 'user_id,office_name';
$temp = select('mst_user_office2', $target, $where);
foreach ($temp as $val){
    $tgtId = $val['user_id'];
    $userMst[$tgtId]['office2'] = $val['office_name']; 
}

/* -- 医療機関 ---------------------------*/
$where = array();
$where['delete_flg'] = 0;
$where['user_id'] = $tgtUser;
if ($search['start_day']){
    $where['start_day <='] = $search['start_day'];
}
if ($search['end_day']){
    $where['end_day >='] = $search['end_day'];
}
$target = 'user_id,name';
$temp = select('mst_user_hospital', $target, $where);
foreach ($temp as $val){
    $tgtId = $val['user_id'];
    $userMst[$tgtId]['hospital'] = $val['name']; 
}

/* -- 事業所・医療機関検索 ---------------------------*/
foreach ($userMst as $tgtId => $val){

    // 0000-00-00をNULLにする
//    $val['start_day'] = $val['start_day'] == "0000-00-00" ? NULL : $val['start_day'];
//    $val['end_day']    = $val['end_day']    == "0000-00-00" ? NULL : $val['end_day'];
    
    // 契約事業所、居宅支援事業所、医療機関
    $val['office1']  = isset($val['office1'])  ? $val['office1']  : NULL;
    $val['office2']  = isset($val['office2'])  ? $val['office2']  : NULL;
    $val['hospital'] = isset($val['hospital']) ? $val['hospital'] : NULL;
    $userMst[$tgtId] = $val;
    
    // 名称検索
    if ($search['hsp_name']){
        if ((strpos($val['office1'], $search['hsp_name']) === FALSE)
                && (strpos($val['office2'], $search['hsp_name']) === FALSE)
                && (strpos($val['hospital'], $search['hsp_name']) === FALSE)){
            unset($userMst[$tgtId]);
            unset($tgtUser[$tgtId]);
        }
    }
    // 居宅支援事業所空欄チェック
    if ($search['ofc_check']){
        if ($search['ofc_check'] == 'only' && $val['office2']){
            unset($userMst[$tgtId]);
            unset($tgtUser[$tgtId]);
        }
        if ($search['ofc_check'] == 'ng' && empty($val['office2'])){
            unset($userMst[$tgtId]);
            unset($tgtUser[$tgtId]);
        }
    }
}

/* -- 計画書 ---------------------------------*/
$type = '計画書';
if (!empty($search['doc_type'][$type])){
    
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $tgtUser;
    if ($search['start_day']){
        $where['report_day >='] = $search['start_day'];
    }
    if ($search['end_day']){
        $where['report_day <='] = $search['end_day'];
    }
    if ($search['staff_id']){
        $where['staff_id'] = $search['staff_id'];
    }
    $where['status'] = $search['status'];
    $target = 'unique_id,user_id,staff_id,report_day,status,create_date,care_kb';
    $temp = select('doc_plan', $target, $where);
    foreach ($temp as $val){
        $userId = $val['user_id'];
        $tgtMst = isset($userMst[$userId]) ? $userMst[$userId] : array();
        if (!$tgtMst){
            continue;
        }
        
        // 0000-00-00をNULLにする
        $val['report_day']    = $val['report_day']    == "0000-00-00" ? NULL : $val['report_day'];
        
        $dat = array();
        $dat['type']       = $type;
        $dat['unique_id']  = $val['unique_id'];
        $dat['status']     = $val['status'];
        $dat['care_kb']     = $val['care_kb'];
        $dat['date']       = formatDateTime($val['report_day'],'Y/m/d');
        $dat['user_name']  = $tgtMst['name'];
        $dat['office1']    = $tgtMst['office1'];
        $dat['office2']    = $tgtMst['office2'];
        $dat['hospital']   = $tgtMst['hospital'];
        $dat['staff_name'] = getStaffName($val['staff_id']);
        $sortKey = $val['create_date'].'-'.$val['unique_id'];
        $tgtData[$sortKey] = $dat;
        $rptData[$val['unique_id']] = $dat;
    }
}

/* -- 報告書 ---------------------------------*/
$type = '報告書';
if (isset($search['doc_type'][$type])){
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $tgtUser;
    if ($search['start_day']){
        $where['report_day >='] = $search['start_day'];
    }
    if ($search['end_day']){
        $where['report_day <='] = $search['end_day'];
    }
    if ($search['staff_id']){
        $where['staff_id'] = $search['staff_id'];
    }
    $where['status'] = $search['status'];
    $target = 'unique_id,user_id,staff_id,report_day,status,create_date,care_kb';
    $temp = select('doc_report', $target, $where);
    foreach ($temp as $val){
        $userId = $val['user_id'];
        $tgtMst = $userMst[$userId];
        
        // 0000-00-00をNULLにする
        $val['report_day']    = $val['report_day']    == "0000-00-00" ? NULL : $val['report_day'];

        $dat = array();
        $dat['type']       = $type;
        $dat['unique_id']  = $val['unique_id'];
        $dat['status']     = $val['status'];
        $dat['care_kb']     = $val['care_kb'];
        $dat['date']       = formatDateTime($val['report_day'],'Y/m/d');
        $dat['user_name']  = $tgtMst['name'];
        $dat['office1']    = $tgtMst['office1'];
        $dat['office2']    = $tgtMst['office2'];
        $dat['hospital']   = $tgtMst['hospital'];
        $dat['staff_name'] = getStaffName($val['staff_id']);
        $sortKey = $val['create_date'].'-'.$val['unique_id'];
        $tgtData[$sortKey] = $dat;
        $rptData[$val['unique_id']] = $dat;
    }
}
/* -- 褥瘡計画書 -----------------------*/
$type = '褥瘡計画書';
if (isset($search['doc_type'][$type])){
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $tgtUser;
    if ($search['start_day']){
        $where['bedsore_day >='] = $search['start_day'];
    }
    if ($search['end_day']){
        $where['bedsore_day <='] = $search['end_day'];
    }
    if ($search['staff_id']){
        $where['staff_id'] = $search['staff_id'];
    }
    $where['status'] = $search['status'];
    $target = 'unique_id,user_id,staff_id,bedsore_day,status,create_date';
    $temp = select('doc_bedsore', $target, $where);
    foreach ($temp as $val){
        $userId = $val['user_id'];
        $tgtMst = $userMst[$userId];

        // 0000-00-00をNULLにする
        $val['bedsore_day']    = $val['bedsore_day']    == "0000-00-00" ? NULL : $val['bedsore_day'];

        $dat = array();
        $dat['type']       = $type;
        $dat['unique_id']  = $val['unique_id'];
        $dat['status']     = $val['status'];
        $dat['date']       = formatDateTime($val['bedsore_day'],'Y/m/d');
        $dat['user_name']  = $tgtMst['name'];
        $dat['office1']    = $tgtMst['office1'];
        $dat['office2']    = $tgtMst['office2'];
        $dat['hospital']   = $tgtMst['hospital'];
        $dat['staff_name'] = getStaffName($val['staff_id']);
        $sortKey = $val['create_date'].'-'.$val['unique_id'];
        $tgtData[$sortKey] = $dat;
        $rptData[$val['unique_id']] = $dat;
    }
}
/* -- 指示書 ---------------------------------*/
$type = '指示書';
if (isset($search['doc_type'][$type])){
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $tgtUser;
    if ($search['start_day']){
        $where['direction_end <='] = $search['start_day'];
    }
    if ($search['end_day']){
        $where['direction_start >='] = $search['end_day'];
    }
    if ($search['staff_id']){
        $where['staff_id'] = $search['staff_id'];
    }
    $where['status'] = $search['status'];
    $target = 'unique_id,user_id,staff_id,direction_start,direction_end,status,create_date,care_kb';
    $temp = select('doc_instruct', $target, $where);
    foreach ($temp as $val){
        $userId = $val['user_id'];
        $tgtMst = $userMst[$userId];

        // 0000-00-00をNULLにする
        $val['direction_start']  = $val['direction_start']  == "0000-00-00" ? NULL : $val['direction_start'];
        $val['direction_end']    = $val['direction_end']    == "0000-00-00" ? NULL : $val['direction_end'];

        $dat = array();
        $dat['type']       = $type;
        $dat['unique_id']  = $val['unique_id'];
        $dat['status']     = $val['status'];
        $dat['care_kb']     = $val['care_kb'];
        $dat['date']       = formatDateTime($val['direction_start'],'Y/m/d')
                .'～<br>'.formatDateTime($val['direction_end'],'Y/m/d');
        $dat['user_name']  = $tgtMst['name'];
        $dat['office1']    = $tgtMst['office1'];
        $dat['office2']    = $tgtMst['office2'];
        $dat['hospital']   = $tgtMst['hospital'];
        $dat['staff_name'] = getStaffName($val['staff_id']);
        $sortKey = $val['create_date'].'-'.$val['unique_id'];
        $tgtData[$sortKey] = $dat;
        $rptData[$val['unique_id']] = $dat;
    }
}

/* -- ソート処理 -----------------------------*/
krsort($tgtData);
    
/* -- その他 --------------------------------------------*/

/* -- csv出力 --------------------------------------------*/
if ($btnCsv) {
}

//csvディレクトリ作成
if ($tgtData) {
    $type = 'reports_csv';
    $dir = SV_ROOT . '/csv/' . $type . '/';
    $dir2 = '/csv/' . $type . '/';
    
    if (!is_dir($dir)) {
        umask(0);
        if (!mkdir($dir, 0777)) {
            $err[] = 'CSV出力フォルダ作成に失敗しました。';
            throw new Exception();
        }
    }
    
    // csv作成
    $filename = $type . '_' . date('YmdHis') . '.csv';
    $filepath = $dir . $filename;
    $filepath2 = $dir2 . $filename;
    
    foreach ($tgtData as $index => $val) {
        $reports_csv[$index]['type']       = $val['type'];
        $reports_csv[$index]['status']     = $val['status'];
        $reports_csv[$index]['date']       = $val['date'];
        $reports_csv[$index]['user_name']  = $val['user_name'];
        $reports_csv[$index]['staff_name'] = $val['staff_name'];
        $reports_csv[$index]['office1']    = $val['office1'];
        $reports_csv[$index]['office2']    = $val['office2'];
        $reports_csv[$index]['hospital']   = $val['hospital'];
    }
    $csvHeader = ["帳票種類", "作成状態", "該当月", "利用者名", "担当者", "契約事業所", "居宅支援事業所", "医療機関"];
    array_unshift($reports_csv, $csvHeader);
    
    writeCsv($filepath, $reports_csv);
    
    $csv_file_path = $filepath2;
}

 /* -- 印刷処理 --------------------------------------------*/
 $targetPerson = "";
 if($btnPrt){
    $okPrt = array();
    // 印刷除外のものを印刷リストから外す
    foreach($allPrt as $key => $val) {
        if (isset($ngPrt[$key])) {
            $okPrt[$key] = array_diff($val, $ngPrt[$key]);
        } else {
            $okPrt[$key] = $val;
        }
    }
    $okPrt = array_filter($okPrt);

     // チェックボックスのIDとTYPEを判定
     foreach($okPrt as $type => $values){
        if($type === "計画書"){
            foreach ($values as $val) {
                
                $careKb = $rptData[$val]['care_kb'];
                
                if (mb_strpos($search['tgtPerson'],"主治医") !== FALSE){
                    $targetPerson = '主治医';
                    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].'/report/plan/index.php'.'?id='.$val.'&care_kb='.$careKb.'&target_person='.$targetPerson.'&prt=001';
                }
                if (mb_strpos($search['tgtPerson'],"利用者") !== FALSE){
                    $targetPerson = '利用者';
                    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].'/report/plan/index.php'.'?id='.$keyId.'&care_kb='.$careKb.'&target_person='.$targetPerson.'&prt=001';
                }
                if (mb_strpos($search['tgtPerson'],"ケアマネ") !== FALSE){
                    $targetPerson = 'ケアマネ';
                    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].'/report/plan/index.php'.'?id='.$keyId.'&care_kb='.$careKb.'&target_person='.$targetPerson.'&prt=001';
                }
                if (mb_strpos($search['tgtPerson'],"その他") !== FALSE){
                    $targetPerson = 'その他';
                    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].'/report/plan/index.php'.'?id='.$keyId.'&care_kb='.$careKb.'&target_person='.$targetPerson.'&prt=001';
                }
             }
        }
        if($type === "報告書"){
            foreach ($values as $val) {

                $careKb = $rptData[$val]['care_kb'];

                if (mb_strpos($search['tgtPerson'],"主治医") !== FALSE){
                    $targetPerson = '主治医';
                    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].'/report/report/index.php'.'?id='.$val.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
                }
                if (mb_strpos($search['tgtPerson'],"利用者") !== FALSE){
                    $targetPerson = '利用者';
                    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].'/report/report/index.php'.'?id='.$val.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
                }
                if (mb_strpos($search['tgtPerson'],"ケアマネ") !== FALSE){
                    $targetPerson = 'ケアマネ';
                    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].'/report/report/index.php'.'?id='.$val.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
                }
                if (mb_strpos($search['tgtPerson'],"その他") !== FALSE){
                    $targetPerson = 'その他';
                    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].'/report/report/index.php'.'?id='.$val.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
                }
             }
        }
        if($type === "褥瘡計画書"){
            foreach ($values as $val) {
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].'/report/bedsore/index.php'.'?id='.$val.'&prt=009';
             }
        }
     }
 }

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
