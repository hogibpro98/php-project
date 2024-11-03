<?php
//=====================================================================
// 計画書
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
$dispPrb  = array();
$tgtData  = array();
$upAry    = array();
$upData   = array();
$upPrb    = array();
$userId   = NULL;
$targetPerson = NULL;

$otherWindowURL = array();

// 対象テーブル
$table1 = 'doc_plan';
$table2 = 'doc_plan_problem';

// 初期値
$dispData = initTable($table1);
$dispFml  = array();
$dispData['other_id']    = NULL;
$dispData['user_name']   = NULL;
$week = formatDateTime(NOW, 'w');
$weekDisp = '('.$weekAry[$week].')';
$dispData['disp_report'] = formatDateTime(NOW, 'Y年m月d日').$weekDisp;
$dispData['disp_first']  = $dispData['disp_report'];
$dispData['staff_name']  = NULL;
$dispData['staff_cd']    = NULL;
$dispData['create_day']  = TODAY;
$dispData['create_time'] = NULL;
$dispData['create_name'] = NULL;
$dispData['update_day']  = NULL;
$dispData['update_time'] = NULL;
$dispData['update_name'] = NULL;
$dispData['target_person'] = NULL;
$dispData['nengo']       = NULL;
$dispData['wareki']      = NULL;
$dispData['birthday_disp'] = NULL;
$dispData['age']         = NULL;
$dispData['care_rank']   = NULL;
$dispData['address']     = NULL;
$dispData['staff1_name'] = NULL;
$dispData['staff2_name'] = NULL;
$dispData['staff1_cd']    = NULL;
$dispData['staff2_cd']    = NULL;
$dispData['manager_name'] = NULL;
$dispData['manager_cd']   = NULL;
$dispData['user_address'] = NULL;
$dispData['report_day'] = TODAY;
$dispData['validate_start'] = THISMONTHFIRST;
$dispData['validate_end'] = THISMONTHLAST;

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// KEY
$keyId = filter_input(INPUT_GET, 'id');

// 複製時のパラメータ
$copy = filter_input(INPUT_GET, 'copy');

// 印刷用パラメータ
$prt = filter_input(INPUT_GET, 'prt');

// 印刷用パラメータ
$careKb = filter_input(INPUT_GET, 'care_kb');

// 印刷用パラメータ
$targetPerson = filter_input(INPUT_GET, 'target_person');

// 利用者ID
$userId = filter_input(INPUT_GET, 'user');
if (!$userId) {
    $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : NULL;
}

// 拠点ID
$placeId = filter_input(INPUT_GET, 'place');
if (!$placeId) {
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : NULL;
}

/*-- 更新用パラメータ ---------------------------------------*/

// 更新ボタン
$btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

// 複製ボタン
$btnCopy = h(filter_input(INPUT_POST, 'btnCopy'));

// 削除ボタン
$btnDel = h(filter_input(INPUT_POST, 'btnDel'));

// 削除ボタン(計画書-問題)
$btnDelPrb = h(filter_input(INPUT_POST, 'btnDelPrb'));

// 報告書作成ボタン
$btnAdd = h(filter_input(INPUT_POST, 'btnAdd'));

// 更新配列(計画書)
$upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

// 更新配列(計画書-問題)
$upPrb = filter_input(INPUT_POST, 'upPrb', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upPrb = $upPrb ? $upPrb : array();

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
if (($btnEntry || $btnDelPrb || $btnAdd) && $upAry){
    
    // 利用者
    $userId = $upAry['user_id'];

    // 対象KEY
    if ($keyId && $btnEntry){
        $upAry['unique_id'] = $keyId;
    }
    
    // 作成日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['report_day']){
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), $upAry['report_day']);
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['report_day'] = $tgtDayAry[0];
    }

    // 問題
    $upPrb2 = array();
    if($upPrb){
        foreach ($upPrb['plan_day'] as $seq => $planDay){
            if (!empty($upPrb['plan_day'][$seq])){
                if (empty($copy) && isset($upPrb['unique_id'][$seq])){
                    $upPrb2[$seq]['unique_id'] = $upPrb['unique_id'][$seq];
                }
                
                $upPrb2[$seq]['plan_day']   = $upPrb['plan_day'][$seq];
                $upPrb2[$seq]['problem']    = $upPrb['problem'][$seq];
                $upPrb2[$seq]['solution']   = $upPrb['solution'][$seq];
                $upPrb2[$seq]['evaluation'] = $upPrb['evaluation'][$seq];
            }
        }
    }
    
    // 宛先指定
    if (isset($upDummy['target_person']) && $upDummy['target_person']){
        $upAry['target_person'] = implode('^', $upDummy['target_person']);
    }else{
        $upAry['target_person'] = "";
    }
    
    // 更新配列
    $upData = $upAry;
}

// 複製処理
if ($btnCopy){
    
    // 問題
    $upPrb2 = array();
    if($upPrb){
        foreach ($upPrb['plan_day'] as $seq => $planDay){
            if (!empty($upPrb['plan_day'][$seq])){
                if (isset($upPrb['unique_id'][$seq])){
                    $upPrb2[$seq]['unique_id'] = $upPrb['unique_id'][$seq];
                }
                $upPrb2[$seq]['plan_day']   = $upPrb['plan_day'][$seq];
                $upPrb2[$seq]['problem']    = $upPrb['problem'][$seq];
                $upPrb2[$seq]['solution']   = $upPrb['solution'][$seq];
                $upPrb2[$seq]['evaluation'] = $upPrb['evaluation'][$seq];
            }
        }
    }
    
    // 宛先を文字列に変換
    $upDummy['target_person'] = isset($upDummy['target_person']) 
        ? implode('^', $upDummy['target_person']) 
        : NULL;
    
    // セッションに入力途中の情報を格納
    $_SESSION['input'] = array();
    $_SESSION['input']['upAry']   = $upAry;
    $_SESSION['input']['upDummy'] = $upDummy;
    $_SESSION['input']['upPrb']   = $upPrb2;
    
    // 画面遷移
    $nextPage = '/report/plan/index.php?copy=true&user='.$userId;
    header("Location:". $nextPage);
    exit();
}

/* -- 報告書作成用配列作成 ----------------------------------------*/

// 報告書作成
if ($btnAdd){
    
    $dat = array();
    // 訪問看護区分、有効期間、利用者、担当者
    $dat['care_kb']        = $upAry['care_kb'];
    $dat['validate_start'] = $upAry['validate_start'];
    $dat['validate_end']   = $upAry['validate_end'];
    $dat['user_id']        = $upAry['user_id'];
    $dat['staff_id']       = $upAry['staff_id'];
    
    // 作成者１、作成者２、管理者
    $dat['create_staff1']  = $upAry['create_staff1'];
    $dat['staff1_name']    = $upDummy['staff1_name'];
    $dat['create_job1']    = $upAry['create_job1'];
    $dat['create_staff2']  = $upAry['create_staff2'];
    $dat['staff2_name']    = $upDummy['staff2_name'];
    $dat['create_job2']    = $upAry['create_job2'];
    $dat['manager']        = $upAry['manager'];

    // 医療機関名称、所在地、主治医、電話番号、FAX
    $dat['hospital']       = $upAry['hospital'];
    $dat['address']        = $upAry['address'];
    $dat['doctor']         = $upAry['doctor'];
    $dat['tel1']           = $upAry['tel1'];
    $dat['tel2']           = $upAry['tel2'];
    $dat['fax']            = $upAry['fax'];
    
    // 衛生材料（種類サイズ等）
    $dat['dealing']          = $upAry['dealing'];
    $dat['medical_material'] = $upAry['medical_material'];
    $dat['requirement']      = $upAry['requirement'];
    
    // 問題点等の作成
    if($upPrb2){
        $problem    = "";
        $solution   = "";
        $evaluation = "";
        foreach($upPrb2 as $idx => $val){
            $planDay     = $val['plan_day'];
            $problem    .= $val['problem']."\r\n";
            $solution   .= $val['solution']."\r\n";
            $evaluation .= $val['evaluation']."\r\n";
        }
    }
    $dat['condition_progress'] = $evaluation;
    
    // 格納
    $_SESSION['planInfo'] = $dat;

        // 画面遷移
    $_SESSION['user'] = $userId;
    $nextPage = '/report/report/index.php?user='.$userId;
    header("Location:". $nextPage);
    exit();
}

/* -- 削除用配列作成 ----------------------------------------*/

// 削除配列
if ($btnDel) {
    $upData['unique_id'] = $btnDel;
    $upData['delete_flg'] = '1';
}

// 削除配列
if ($btnDelPrb) {
    $seq = count($upPrb2) + 1;
    $upPrb2[$seq]['unique_id'] = $btnDelPrb;
    $upPrb2[$seq]['delete_flg'] = '1';
}

/* =================================================== 
 * イベント本処理(データ登録)
 * ===================================================
 */

// 入力チェック

// 更新処理
if (($btnEntry || $btnDelPrb) && $upData){

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

    // 問題
    if (!empty($upPrb2)){

        foreach ($upPrb2 as $key => $val){
            $val['plan_id'] = $keyId;
            $upPrb2[$key] = $val;
        }

        $res = multiUpsert($loginUser, $table2, $upPrb2);
        if (isset($res['err'])){
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upPrb2);       
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
    $nextPage = '/report/plan_list/index.php';
    header("Location:". $nextPage);
    exit();
}

if ($btnPrint && isset($upDummy['target_person']) === false){
    // 印刷時に宛先未指定はエラーとする
    $err[] = '宛先が指定されていません。';
    $upDummy['target_person'] = "";
}

// 帳票印刷
//if ($btnPrint && $keyId){
//    if (strpos($upData['target_person'], '主治医') !== FALSE){
//        $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&prt=001';
//    }
//    if (strpos($upData['target_person'], '利用者') !== FALSE){
//        $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&prt=002';    
//    }
//    if (strpos($upData['target_person'], 'ケアマネ') !== FALSE){
//        $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&prt=003';
//    }
//    if (strpos($upData['target_person'], 'その他') !== FALSE){
//        $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&prt=004';
//    }
//}

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- データ取得 --------------------------------------------*/

/* -- 汎用マスタ ---------------------------*/
$gnrList = getCode('計画書');

/* -- 利用者マスタ -------------------------*/
$where = array();
$where['delete_flg'] = 0;
$target  = 'unique_id,last_name,first_name,other_id';
$target .= ',birthday,prefecture,area,address1,address2,address3';
$temp = select('mst_user',$target,$where);
foreach ($temp as $val){
    $tgtId      = $val['unique_id'];
    $lastName   = $val['last_name'];
    $firstName  = $val['first_name'];
    $val['name'] = $lastName.' '.$firstName;
    // 生年月日
    $val['birthday_disp'] = !empty($val['birthday'])
                          ? chgAdToJpDate($val['birthday'])
                          : NULL;
    // 年齢
    $val['age'] = !empty($val['birthday'])
            ? getAge($val['birthday']).'歳'
            : NULL;
    // 住所
    $val['address'] = $val['prefecture'].$val['area'].$val['address1'].$val['address2'].$val['address3'];
    $userList[$tgtId] = $val;
}
if ($userId && isset($userList[$userId])){
    $dispData['other_id']      = $userList[$userId]['other_id'];
    $dispData['user_name']     = $userList[$userId]['name'];
    $dispData['birthday_disp'] = $userList[$userId]['birthday_disp'];
    $dispData['age']           = $userList[$userId]['age'];
    $dispData['user_address']  = $userList[$userId]['address'];
    $dispData['care_rank']     = getCareRank($userId);

}

/* -- スタッフマスタ -----------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id,last_name,first_name';
$temp = select('mst_staff','*',$where);
foreach ($temp as $val){
    $tgtId       = $val['unique_id'];
    $val['name'] = $val['last_name'].' '.$val['first_name'];
    $staffList[$tgtId] = $val;
}

/* -- 計画書 ------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id']  = $keyId;
    $temp = select($table1, '*', $where);
    
    if (isset($temp[0])){
        
        // テーブル値
        $tgtData = $temp[0];

        $stfId = isset($tgtData['staff_id']) ? $tgtData['staff_id'] : '';
        
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
        if ($tgtData['create_staff1']){
            $stfId = $tgtData['create_staff1'];
            $tgtData['staff1_name'] = isset($staffList[$stfId])
                    ? $staffList[$stfId]['name']
                    : NULL;

            $tgtData['staff1_cd'] = isset($staffList[$stfId]['staff_id'])
                    ? $staffList[$stfId]['staff_id']
                    : NULL;
        }
        if ($tgtData['create_staff2']){
            $stfId = $tgtData['create_staff2'];
            $tgtData['staff2_name'] = isset($staffList[$stfId])
                    ? $staffList[$stfId]['name']
                    : NULL;

            $tgtData['staff2_cd'] = isset($staffList[$stfId]['staff_id'])
                    ? $staffList[$stfId]['staff_id']
                    : NULL;
        }
        if ($tgtData['manager']){
            $stfId = $tgtData['manager'];
            $tgtData['manager_name'] = isset($staffList[$stfId])
                    ? $staffList[$stfId]['name']
                    : NULL;

            $tgtData['manager_cd'] = isset($staffList[$stfId]['staff_id'])
                    ? $staffList[$stfId]['staff_id']
                    : NULL;
        }
        
        
        
        // 初回登録
        $tgtDate = $tgtData['create_date'];  
        $tgtData['create_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['create_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['create_user'];
        $tgtData['create_name'] = isset($staffList[$stfId]['name'])
                ? $staffList[$stfId]['name']
                : NULL;

        // 更新情報
        $tgtDate = $tgtData['update_date'];
        $tgtData['update_day']  = formatDateTime($tgtDate, 'Y/m/d');
        $tgtData['update_time'] = formatDateTime($tgtDate, 'H:i');
        $tgtUser = $tgtData['update_user'];
        $tgtData['update_name'] = isset($staffList[$stfId]['name'])
                ? $staffList[$stfId]['name']
                : NULL;
        
        // 格納
        $dispData = array_merge($dispData,$tgtData);
    }
}

/* -- 管理者情報 -----------------------------*/

if (!$keyId){
    
    // 初期化
    $mgrId   = null;
    $mgrData = array();
    $ofcIds  = array();
    $dispData['manager']      = null;
    $dispData['manager_cd'] = null;
    $dispData['manager_name'] = null;

    $where = array();
    $where['place_id'] = $placeId;
    $where['type']     = '訪問看護';
    $temp = select('mst_office','*',$where);
    foreach ($temp as $val){
        $mgrId = $val['manager_id'];
    }

    // スタッフ名称
    if($mgrId){
        $where = array();
        $where['unique_id'] = $mgrId;
        $mgrData = getData('mst_staff', $where);

        // 格納
        if ($mgrData){
            $dispData['manager']   = $mgrId;
            $dispData['manager_cd'] = $mgrData['staff_id'];
            $dispData['manager_name'] = $mgrData['last_name'].$mgrData['first_name'];
        }
    }
}

/* -- 計画書-問題 情報 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['plan_id']  = $keyId;
    $target = '*';
    $temp = select($table2,$target,$where);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];

        $dispPrb[$tgtId] = $val;
    }
}

// 複製ボタン押下時の表示情報
if (!$keyId && $copy){
//    $dispData = array();
//    $dispData = $_SESSION['input']['upAry'];
    $dispData = array_merge($dispData, $_SESSION['input']['upAry']);
    $dispData = array_merge($dispData, $_SESSION['input']['upDummy']);
    $dispPrb  = array_merge($dispPrb , $_SESSION['input']['upPrb']);
}

/* -- その他 --------------------------------------------*/

// 帳票印刷
if ($btnPrint && $keyId){
    if(empty($userId)){
        $err[] = '利用者が選択されていません。';
        $_SESSION['notice']['error'] = $err;
    }
    
    $upDummy['target_person'] = implode("^", $upDummy['target_person']);
  
    if(isset($upDummy['target_person']) !== false && empty($err)){
        if ($dispData['care_kb'] == '訪問看護'){
            if (mb_strpos($upDummy['target_person'], '主治医') !== FALSE){
                $targetPerson = '主治医';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=001';
            }
            if (mb_strpos($upDummy['target_person'], '利用者') !== FALSE){
                $targetPerson = '利用者';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=001';
            }
            if (mb_strpos($upDummy['target_person'], 'ケアマネ') !== FALSE){
                $targetPerson = 'ケアマネ';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=001';
            }
            if (mb_strpos($upDummy['target_person'], 'その他') !== FALSE){
                $targetPerson = 'その他';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=001';
            }
        }
        if ($dispData['care_kb'] == '精神科訪問看護'){
            if (mb_strpos($upDummy['target_person'], '主治医') !== FALSE){
                $targetPerson = '主治医';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=001';
            }
            if (mb_strpos($upDummy['target_person'], '利用者') !== FALSE){
                $targetPerson = '利用者';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=001';
            }
            if (mb_strpos($upDummy['target_person'], 'ケアマネ') !== FALSE){
                $targetPerson = 'ケアマネ';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=001';
            }
            if (mb_strpos($upDummy['target_person'], 'その他') !== FALSE){
                $targetPerson = 'その他';
                $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&care_kb='.$dispData['care_kb'].'&target_person='.$targetPerson.'&prt=001';
            }
        }
    }
}
// 印刷処理
if ($prt){
    
    // 出力条件
    $search = array();
    $search['unique_id']      = $keyId;
    $search['care_kb']        = $dispData['care_kb'];
    $search['target_person']  = $targetPerson;    
        
    $res = printPDF($prt, $search);
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
