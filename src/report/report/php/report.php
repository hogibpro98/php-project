<?php
//=====================================================================
// 報告書
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
$dispData     = array();
$dispEvt      = array();
$tgtData      = array();
$upData       = array();
$upEvt        = array();
$upPrtData    = array();
$userId       = NULL;
$calDate      = NULL;
$targetPerson = NULL;
$otherWindowURL = array();

// 対象テーブル
$table1 = 'doc_report';
$table2 = 'mst_user';

// 初期値
$dispData = initTable($table1);
$dispData['other_id']      = NULL;
$dispData['user_name']     = NULL;
$week = formatDateTime(NOW, 'w');
$weekDisp = '('.$weekAry[$week].')';
$dispData['disp_report']   = formatDateTime(NOW, 'Y年m月d日').$weekDisp;
$dispData['disp_first']    = $dispData['disp_report'];
$dispData['staff_name']    = NULL;
$dispData['create_day']    = NULL;
$dispData['create_time']   = NULL;
$dispData['create_name']   = NULL;
$dispData['update_day']    = NULL;
$dispData['update_time']   = NULL;
$dispData['update_name']   = NULL;
$dispData['birthday_disp'] = NULL;
$dispData['age']           = NULL;
$dispData['care_rank']     = NULL;
$dispData['user_address']  = NULL;
$dispData['staff_cd']      = NULL;
$dispData['copy']          = NULL;

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// 検索用パラメータ
$search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$todayAry = explode('-', TODAY);

$search['year']  = isset($search['year'])  ? $search['year']  : $todayAry[0];
$search['month'] = isset($search['month']) ? $search['month'] : $todayAry[1];

if($search){
    $calDate = $search['year'] ."-". $search['month'];
}

// KEY
$keyId = filter_input(INPUT_GET, 'id');

// 複製
$copyId = filter_input(INPUT_GET, 'copy');
if (!$keyId && $copyId){
    $keyId = $copyId;
}

// 印刷用パラメータ
$prtNo = filter_input(INPUT_GET, 'prt');

// 印刷用パラメータ
$careKb = filter_input(INPUT_GET, 'care_kb');

// 印刷用パラメータ
$targetPerson = filter_input(INPUT_GET, 'target_person');

// 利用者ID
$userId = filter_input(INPUT_GET, 'user');
if (!$userId) {
    $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : NULL;
}

/*-- 更新用パラメータ ---------------------------------------*/

// 更新ボタン
$btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

// 複製ボタン
$btnCopy = h(filter_input(INPUT_POST, 'btnCopy'));

// 削除ボタン
$btnDel = h(filter_input(INPUT_POST, 'btnDel'));

// 更新配列(訪問看護)
$upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

// 担当者
$upTgtPsn = filter_input(INPUT_POST, 'upTgtPsn', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upTgtPsn = $upTgtPsn ? $upTgtPsn : array();

// 更新配列(イベント)
$upAry2 = filter_input(INPUT_POST, 'upAry2', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry2 = $upAry2 ? $upAry2 : array();

// その他
$upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();

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
if (($btnEntry || $btnCopy || $btnPrint) && $upAry){
    
    // 利用者
    $userId = $upAry['user_id'];
    
    // 対象KEY
    if ($keyId && $btnEntry && !$copyId){
        $upAry['unique_id'] = $keyId;
    }
    
    // 作成日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['report_day']){
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), $upAry['report_day']);
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['report_day'] = $tgtDayAry[0];
    }
    
    // 日付型0000-00-00 => NULL 変換 
    $upAry['report_day']      = $upAry['report_day']      != '0000-00-00' ? $upAry['report_day'] : NULL; 
    $upAry['validate_start']  = $upAry['validate_start']  != '0000-00-00' ? $upAry['validate_start'] : NULL; 
    $upAry['validate_end']    = $upAry['validate_end']    != '0000-00-00' ? $upAry['validate_end'] : NULL; 
    $upAry['information_day'] = $upAry['information_day'] != '0000-00-00' ? $upAry['information_day'] : NULL; 
    $upAry['gaf_date']        = $upAry['gaf_date']        != '0000-00-00' ? $upAry['gaf_date'] : NULL; 
    
    // 宛先指定
    $upAry['target_person'] = isset($upTgtPsn['target_person']) ? implode('^', $upTgtPsn['target_person']) : '' ;
    
    if($btnPrint){
        $upAry['print_day'] = TODAY;
    }
    // 更新配列
    $upData = $upAry;
    
    // イベント配列
    foreach ($upAry2 as $tgtDay => $val){
        if (empty($val['unique_id'] ?? '') && empty($val['event_kb'] ?? '')) {
            continue;
        }
            $dat = array();
            $dat['event_day'] = $tgtDay;
        $dat['event_kb']  = $val['event_kb'];
        if (!empty($val['unique_id'])) {
            $dat['unique_id'] = $val['unique_id'];
        }
            $upEvt[] = $dat;
        }
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
if (($btnEntry || $btnCopy) && $upData){

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
if (($btnEntry || $btnCopy) && $upData){

    // DBへ格納
    $res = upsert($loginUser, $table1, $upData);
    if (isset($res['err'])){
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }
    
    // KEY
    $keyId = $res;
    
    // ログテーブルに登録する
    setEntryLog($upData);
    
    // イベント
    foreach ($upEvt as $key => $val){
        $val['report_id'] = $keyId;
        $val['delete_flg'] = 0;
        if (!empty($val['unique_id']) && (empty($val['event_kb']))) {
            $val['delete_flg'] = 1;
        }
        $upEvt[$key] = $val;
    }
    if ($upEvt){
        $res = multiUpsert($loginUser, 'doc_report_event', $upEvt);
    }
    // ログテーブルに登録する
    setMultiEntryLog($upEvt);
    
    // 画面遷移
    $_SESSION['user'] = $userId;
    $nextPage = $server['scriptName'].'?id='.$keyId.'&user='.$userId;
    header("Location:". $nextPage);
    exit();
}

// 印刷日更新
if($btnPrint && $upData){
    // DBへ格納
    $res = upsert($loginUser, $table1, $upData);
    if (isset($res['err'])){
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }
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
    $nextPage = '/report/report_list2/index.php';
    header("Location:". $nextPage);
    exit();    
}

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- データ取得 --------------------------------------------*/

if (isset($_SESSION['planInfo'])){
    
    $dispData['care_kb'] = isset ($_SESSION['planInfo']['care_kb'])
            ? $_SESSION['planInfo']['care_kb']
            : NULL;
    $dispData['validate_start'] = isset ($_SESSION['planInfo']['validate_start'])
            ? $_SESSION['planInfo']['validate_start']
            : NULL;
    $dispData['validate_end'] = isset ($_SESSION['planInfo']['validate_end'])
            ? $_SESSION['planInfo']['validate_end']
            : NULL;
    $dispData['user_id'] = isset ($_SESSION['planInfo']['user_id'])
            ? $_SESSION['planInfo']['user_id']
            : NULL;
    $dispData['staff_id'] = isset ($_SESSION['planInfo']['staff_id'])
            ? $_SESSION['planInfo']['staff_id']
            : NULL;
    $dispData['create_staff1'] = isset ($_SESSION['planInfo']['staff1_name'])
            ? $_SESSION['planInfo']['staff1_name']
            : NULL;
    $dispData['create_job1'] = isset ($_SESSION['planInfo']['create_job1'])
            ? $_SESSION['planInfo']['create_job1']
            : NULL;
    $dispData['create_staff2'] = isset ($_SESSION['planInfo']['staff2_name'])
            ? $_SESSION['planInfo']['staff2_name']
            : NULL;
    $dispData['create_job2'] = isset ($_SESSION['planInfo']['create_job2'])
            ? $_SESSION['planInfo']['create_job2']
            : NULL;
    $dispData['manager'] = isset ($_SESSION['planInfo']['manager'])
            ? $_SESSION['planInfo']['manager']
            : NULL;
    $dispData['medical_institution'] = isset ($_SESSION['planInfo']['hospital'])
            ? $_SESSION['planInfo']['hospital']
            : NULL;
    $dispData['address'] = isset ($_SESSION['planInfo']['address'])
            ? $_SESSION['planInfo']['address']
            : NULL;
    $dispData['doctor'] = isset ($_SESSION['planInfo']['doctor'])
            ? $_SESSION['planInfo']['doctor']
            : NULL;
    $dispData['tel1'] = isset ($_SESSION['planInfo']['tel1'])
            ? $_SESSION['planInfo']['tel1']
            : NULL;
    $dispData['tel2'] = isset ($_SESSION['planInfo']['tel2'])
            ? $_SESSION['planInfo']['tel2']
            : NULL;
    $dispData['fax'] = isset ($_SESSION['planInfo']['fax'])
            ? $_SESSION['planInfo']['fax']
            : NULL;

    // 衛生素材等
    $dispData['material_term'] = isset ($_SESSION['planInfo']['dealing'])
            ? $_SESSION['planInfo']['dealing']
            : NULL;
    $dispData['material'] = isset ($_SESSION['planInfo']['medical_material'])
            ? $_SESSION['planInfo']['medical_material']
            : NULL;
    $dispData['material_use'] = isset ($_SESSION['planInfo']['requirement'])
            ? $_SESSION['planInfo']['requirement']
            : NULL;

    // 問題点等
    $dispData['condition_progress'] = isset ($_SESSION['planInfo']['condition_progress'])
            ? $_SESSION['planInfo']['condition_progress']
            : NULL;
    
    unset($_SESSION['planInfo']);
}

/* -- 汎用マスタ ---------------------------*/
$gnrList = getCode('報告書');

/* -- 利用者マスタ -------------------------*/
$where = array();
$where['delete_flg'] = 0;
$target  = '*';
$temp = select('mst_user',$target,$where);
foreach ($temp as $val){
    $tgtId      = $val['unique_id'];
    $lastName   = $val['last_name'];
    $firstName  = $val['first_name'];
    $val['name'] = $lastName.' '.$firstName;
    // 年号
    $nengo = !empty($val['birthday'])
            ? chgAdToJpNengo($val['birthday'])
            : NULL;
    // 和暦
    $wareki = !empty($val['birthday'])
            ? chgAdToJpYear($val['birthday']).'年'
            : NULL;
    // 生年月日
    $val['birthday_disp'] = !empty($val['birthday']) ? chgAdToJpDate($val['birthday']) : NULL;
    // 年齢
    $val['age'] = !empty($val['birthday'])
            ? getAge($val['birthday']).'歳'
            : NULL;
    // 住所
    $val['user_address'] = $val['prefecture'].$val['area'].$val['address1'].$val['address2'].$val['address3'];
    
    $userList[$tgtId] = $val;
}
if ($userId && isset($userList[$userId])){
    $dispData['other_id']      = $userList[$userId]['other_id'];
    $dispData['user_name']     = $userList[$userId]['name'];
    $dispData['birthday_disp'] = $userList[$userId]['birthday_disp'];
    $dispData['age']           = $userList[$userId]['age'];
    $dispData['user_address']       = $userList[$userId]['user_address'];
    
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userId;
    $where['start_day1 <='] = TODAY;
    $where['end_day1 >='] = TODAY;
    $target  = '*';
    $temp = select('mst_user_insure1',$target,$where);

    // 要介護ランク設定
    $dispData['care_rank'] = isset($temp[0]['care_rank']) ? $temp[0]['care_rank'] : NULL; 
}

/* -- スタッフマスタ -----------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,last_name,first_name';
$temp = select('mst_staff','*',$where);
foreach ($temp as $val){
    $tgtId       = $val['unique_id'];
    $val['name'] = $val['last_name']."".$val['first_name'];
    $staffList[$tgtId] = $val;
}

/* -- 訪問介護記録Ⅰ ------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id']  = $keyId;
    $temp = select($table1, '*', $where);
    
    if (isset($temp[0])){
        
        // テーブル値
        $tgtData = $temp[0];
        
        // スタッフ名称、スタッフコード
        if ($tgtData['staff_id']){
            $stfId = $tgtData['staff_id'];
            $tgtData['staff_name'] = isset($staffList[$stfId])
                    ? $staffList[$stfId]['name']
                    : NULL;
            
            $tgtData['staff_cd'] = isset($staffList[$stfId]['staff_id'])
                    ? $staffList[$stfId]['staff_id']
                    : NULL;
        }

        // 初回登録
        $tgtDate = $tgtData['create_date'];  
        $tgtData['create_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['create_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['create_user'];
        $tgtData['create_name'] = isset($staffList[$tgtUser]['name'])
                ? $staffList[$tgtUser]['name']
                : NULL;

        // 更新情報
        $tgtDate = $tgtData['update_date'];
        $tgtData['update_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['update_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['update_user'];
        $tgtData['update_name'] = isset($staffList[$tgtUser]['name'])
                ? $staffList[$tgtUser]['name']
                : NULL;
        
        // 印刷日
        $tgtData['print_day'] = !empty($tgtData['print_day']) 
                ? formatDateTime($tgtData['print_day'], 'Y/m/d')
                : null;
        
        // 格納
        if ($copyId) {
            // 複製時は一部の項目のみ
            $dispData['care_kb'] = $tgtData['care_kb'];
            $dispData['condition_progress'] = $tgtData['condition_progress'];
            $dispData['nursing_contents'] = $tgtData['nursing_contents'];
            $dispData['care_situation'] = $tgtData['care_situation'];
            $dispData['material'] = $tgtData['material'];
            $dispData['material_term'] = $tgtData['material_term'];
            $dispData['material_use'] = $tgtData['material_use'];
            $dispData['copy'] = $copyId;
        } else {
            // 通常の編集時
            $dispData = array_merge($dispData,$tgtData);
        }

        
        // イベント情報
        $where = array();
        $where['delete_flg'] = 0;
        $where['report_id']  = $keyId;
        $target = 'unique_id,event_day,event_kb';
        $temp = select('doc_report_event',$target,$where);
        foreach ($temp as $val){
            $tgtDay = $val['event_day'];
            $dispEvt[$tgtDay]['unique_id'] = $val['unique_id'];
            $dispEvt[$tgtDay]['event_kb'] = $val['event_kb'];
        }
    }
}

/* -- カレンダー情報 -----------------------*/

// 対象年リスト
$slctYear = array();
$slctYear[] = THISYEAR - 2;
$slctYear[] = THISYEAR - 1;
$slctYear[] = THISYEAR;

// 対象月リスト
$slctMonth = array();
$slctMonth[1]  = '01';
$slctMonth[2]  = '02';
$slctMonth[3]  = '03';
$slctMonth[4]  = '04';
$slctMonth[5]  = '05';
$slctMonth[6]  = '06';
$slctMonth[7]  = '07';
$slctMonth[8]  = '08';
$slctMonth[9]  = '09';
$slctMonth[10] = '10';
$slctMonth[11] = '11';
$slctMonth[12] = '12';

// 対象月、次月
$tgtDay   = !empty($dispData['service_day']) ? $dispData['service_day'] : TODAY;
$tgtAry   = explode('-', $tgtDay);
$tgtYear  = $tgtAry[0];
$tgtMonth = $tgtAry[0].'-'.$tgtAry[1];
$tgtMonthNum = $tgtAry[1];
$dt = new DateTime($tgtDay);
$nxtMonth = $dt->modify('first day of +1 month')->format('Y-m');

// カレンダー配列
$cldList1 = getMonthCalender($tgtMonth);
$cldList2 = getMonthCalender($nxtMonth);

/* -- その他 --------------------------------------------*/

// 帳票印刷
if ($btnPrint && $keyId){
    if(empty($userId)){
        $err[] = '利用者が選択されていません。';
        $_SESSION['notice']['error'] = $err;
    }
    if(isset($upData['target_person']) !== false && empty($err)){
        if ($dispData['care_kb'] == '訪問看護'){
            if (mb_strpos($upData['target_person'], '主治医') !== FALSE){
                $targetPerson = '主治医';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
            }
            if (mb_strpos($upData['target_person'], '利用者') !== FALSE){
                $targetPerson = '利用者';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
            }
            if (mb_strpos($upData['target_person'], 'ケアマネ') !== FALSE){
                $targetPerson = 'ケアマネ';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
            }
            if (mb_strpos($upData['target_person'], 'その他') !== FALSE){
                $targetPerson = 'その他';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
            }
        }
        if ($dispData['care_kb'] == '精神科訪問看護'){
            if (mb_strpos($upData['target_person'], '主治医') !== FALSE){
                $targetPerson = '主治医';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
            }
            if (mb_strpos($upData['target_person'], '利用者') !== FALSE){
                $targetPerson = '利用者';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
            }
            if (mb_strpos($upData['target_person'], 'ケアマネ') !== FALSE){
                $targetPerson = 'ケアマネ';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
            }
            if (mb_strpos($upData['target_person'], 'その他') !== FALSE){
                $targetPerson = 'その他';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
            }
        }
    }
//    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=009';
}

// 印刷処理
if ($prtNo){
    
    // 出力条件
    $search = array();
    $search['unique_id']       = $keyId;
    $search['care_kb']         = $careKb;    
    $search['target_person']   = $targetPerson;    
    $res = printPDF($prtNo, $search);
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
