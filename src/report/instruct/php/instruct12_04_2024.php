<?php
//=====================================================================
// 指示書
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
$dispUsr  = array();
$tgtData  = array();
$upData   = array();
$upFcl    = array();
$upFml    = array();
$upHsp    = array();
$userId   = NULL;
$usrhosp  = array();
$sickness = array();

// 対象テーブル
$table1 = 'doc_instruct';
$table2 = 'mst_user';

// 初期値
$dispData = initTable($table1);
$dispUsr  = array();
$dispData['other_id']    = NULL;
$dispData['user_name']   = NULL;
$week = formatDateTime(NOW, 'w');
$weekDisp = '('.$weekAry[$week].')';
$dispData['disp_report'] = formatDateTime(NOW, 'Y年m月d日').$weekDisp;
$dispData['disp_first']  = $dispData['disp_report'];
$dispData['staff_id']    = NULL;
$dispData['staff_cd']    = NULL;
$dispData['staff_name']  = NULL;
$dispData['create_day']  = NULL;
$dispData['create_time'] = NULL;
$dispData['create_name'] = NULL;
$dispData['update_day']  = NULL;
$dispData['update_time'] = NULL;
$dispData['update_name'] = NULL;
$dispData['nengo']       = NULL;
$dispData['wareki']      = NULL;
$dispData['birthday_disp'] = NULL;
$dispData['age']         = NULL;
$dispData['care_rank']   = NULL;
$dispData['address']     = NULL;

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// KEY
$keyId = filter_input(INPUT_GET, 'id');

// 複製
$copyId = filter_input(INPUT_GET, 'copy');
if (!$keyId && $copyId){
    $keyId = $copyId;
}

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

// その他
$upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();

/*-- その他パラメータ ---------------------------------------*/

// 印刷ボタン
$btnPrint = h(filter_input(INPUT_POST, 'btnPrint'));

// 戻るボタン
$btnReturn = h(filter_input(INPUT_POST, 'btnReturn'));

// PDF削除ボタン
$btnDelPdf = h(filter_input(INPUT_POST, 'btnDelPdf'));

// ファイル登録用フィールド定義
$columns = array();
$columns['pdf_file'] = FALSE;
    
/* =================================================== 
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* -- 更新用配列作成 ----------------------------------------*/

// 更新配列
if (($btnEntry || $btnCopy || $btnDelPdf) && $upAry){
    
    // 利用者
    $userId = $upAry['user_id'];
    //$_SESSION['user'] = $userId;
    
    // 対象KEY
    if ($keyId && !$copyId){
        $upAry['unique_id'] = $keyId;
    }
    
    // 作成日 YYYY年MM月DD日(W) → YYYY-MM-DD
    if ($upAry['report_day']){
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), $upAry['report_day']);
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['report_day'] = $tgtDayAry[0];
    }
    
    // 別表８内容
    if (!empty($upDummy['attached8_detail'])){
        $upAry['attached8_detail'] = implode('^', $upDummy['attached8_detail']);
    }
    
    // PDF削除
    if( $btnDelPdf){
        $upAry['pdf_file'] = NULL;
    }
    
    if($btnCopy){
        if(isset($upAry['unique_id'])){
            unset($upAry['unique_id']);
        }
    }
    
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
if (($btnEntry || $btnCopy) && $upData){
  
//    // 名称
//    if (empty($upData['name'])){
//        $notice[] = '名称の指定がありません';
//    }
}

// 更新処理
if (($btnEntry || $btnCopy || $btnDelPdf) && $upData){
    
    // DBへ格納
    $res = upsert($loginUser, $table1, $upData);
    if (isset($res['err'])){
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }

    // ログテーブルに登録する
    setEntryLog($upData);
    
    // 登録済み情報取得
    $fileId = $res;
    $where = array();
    $where['unique_id'] = $fileId;
    $temp = select($table1, '*', $where);
    $tgtData = $temp[0];

    if(!empty($_FILES['file'])){
        // ファイル更新
        $res2 = fileDataUpdate($loginUser, $_FILES['file'], '/upload/instruct', $table1, $fileId, $columns, $tgtData);
        if (isset($res['err'])) {
            $err[] = '画像の更新に失敗しました';
            throw new Exception();
        }
    }
    
    // KEY
    $keyId = $res;
    
    // 基本情報へ反映
    if ($btnCopy){
        $upHsp['user_id']   = $upData['user_id'];
        $upHsp['name']      = $upData['hospital'];
        $upHsp['disp_name'] = $upData['hospital_rece'];
        $upHsp['doctor']    = $upData['doctor'];
        $upHsp['tel1']      = $upData['tel1'];
        $upHsp['tel2']      = $upData['tel2'];
        $upHsp['fax']       = $upData['fax'];
        $res3 = upsert($loginUser, 'mst_user_hospital', $upHsp);
        
        // ログテーブルに登録する
        setEntryLog($upHsp);

    }
    
    $_SESSION['notice']['success'][] = "登録が完了しました";
        
    if(empty($userId)){
        if(!empty($tgtData['user_id'])){
            $userId = $tgtData['user_id'];
        }        
    }

    $userId = $tgtData['user_id'];
    
   
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
    $nextPage = '/report/instruct_list/index.php';
    header("Location:". $nextPage);
    exit();    
}

//// 帳票印刷
//if ($btnPrint && $keyId){
//    $otherWindowURL[] = $_SERVER['HTTP_ORIGIN'].$server['scriptName'].'?id='.$keyId.'&prt=001';
//}
//    

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */


/* -- データ取得 --------------------------------------------*/

/* -- 汎用マスタ ---------------------------*/
$gnrList = getCode('指示書');


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
    // 年号
    $nengo = !empty($val['birthday'])
            ? chgAdToJpNengo($val['birthday'])
            : NULL;
    // 和暦
    $wareki = !empty($val['birthday'])
            ? chgAdToJpYear($val['birthday']).'年'
            : NULL;
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
    $dispData['address']       = $userList[$userId]['address'];
    $dispData['care_rank']     = getCareRank($userId);
}

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

$dispData['staff_id'] = $dispData['staff_id'] 
    ? $dispData['staff_id'] 
    : $staffList[$loginUser['unique_id']]['unique_id'];

$stfId = $dispData['staff_id'];
$dispData['staff_cd'] = isset($staffList[$stfId]['staff_id']) 
    ? $staffList[$stfId]['staff_id']
    : $staffList[$loginUser['unique_id']]['staff_id'];

$dispData['staff_name'] = isset($staffList[$stfId]['name']) 
    ? $staffList[$stfId]['name'] 
    : $staffList[$loginUser['unique_id']]['name'];

/* -- 利用者マスタ医療機関履歴 -----------------------*/
$where = array();
$where['delete_flg'] = 0;
$where['user_id']  = $userId;
$orderBy = 'unique_id DESC';
$target = '*';
$temp = select('mst_user_hospital','*',$where, $orderBy);
if (isset($temp[0])){
    $usrhosp['hospital'] = $temp[0]['name'];
    $usrhosp['hospital_rece'] = $temp[0]['disp_name'];
    $usrhosp['doctor'] = $temp[0]['doctor'];
    $usrhosp['address1'] = $temp[0]['address'];
    $usrhosp['tel1'] = $temp[0]['tel1'];
    $usrhosp['tel2'] = $temp[0]['tel2'];
    $usrhosp['fax'] = $temp[0]['fax'];
}

/* -- 指示書情報取得 ------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id']  = $keyId;
    $orderBy = 'unique_id DESC';
    $temp = select($table1, '*', $where, $orderBy);
    
    if (isset($temp[0])){
        
        // テーブル値
        $tgtData = $temp[0];
        
        // スタッフ名称
        if ($tgtData['staff_id']){
            $stfId = $tgtData['staff_id'];
            $tgtData['staff_cd'] = isset($staffList[$stfId])
                    ? $staffList[$stfId]['staff_id']
                    : NULL;
            $tgtData['staff_name'] = isset($staffList[$stfId])
                    ? $staffList[$stfId]['name']
                    : NULL;
        }else{
            $tgtData['staff_id'] = $staffList[$loginUser['unique_id']]['staff_id'];
            $tgtData['name'] = $staffList[$loginUser['unique_id']]['name'];
        }
        if(!empty($tgtData['hospital']) && empty($tgtData['hospital_rece'])
            && empty($tgtData['doctor']) && empty($tgtData['address1'])
            && empty($tgtData['tel1']) && empty($tgtData['tel2'])
            && empty($tgtData['fax'])){

            // 基本情報に主治医情報が入っていれば優先的に表示
            $tgtData['hospital'] = isset($usrhosp['hospital'])
                ? $usrhosp['hospital'] 
                : NULL;
            $tgtData['hospital_rece'] = isset($usrhosp['hospital_rece']) 
                ? $usrhosp['hospital_rece'] 
                : NULL;
            $tgtData['doctor'] = isset($usrhosp['doctor']) 
                ? $usrhosp['doctor'] 
                : NULL;
            $tgtData['address1'] = isset($usrhosp['address1']) 
                ? $usrhosp['address1'] 
                : NULL;
            $tgtData['tel1'] = isset($usrhosp['tel1']) 
                ? $usrhosp['tel1'] 
                : NULL;
            $tgtData['tel2'] = isset($usrhosp['tel2']) 
                ? $usrhosp['tel2'] 
                : NULL;
            $tgtData['fax'] = isset($usrhosp['fax']) 
                ? $usrhosp['fax'] 
                : NULL;
        }
        
        $user                          = $tgtData['user_id'];
        $staff                         = $tgtData['staff_id'];
        if($user){
            $dispData['other_id']      = $userList[$user]['other_id'];
            $dispData['user_name']     = $userList[$user]['name'];
            $dispData['birthday_disp'] = $userList[$user]['birthday_disp'];
            $dispData['age']           = $userList[$user]['age'];
            $dispData['address']       = $userList[$user]['address'];
            $dispData['care_rank']     = getCareRank($user);
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
       
        // 別表７用主な病症名の配列を作成
        $sickness[] = $tgtData['sickness1']  ? $tgtData['sickness1'] : "";
        $sickness[] = $tgtData['sickness2']  ? $tgtData['sickness2'] : "";
        $sickness[] = $tgtData['sickness3']  ? $tgtData['sickness3'] : "";
        $sickness[] = $tgtData['sickness4']  ? $tgtData['sickness4'] : "";
        $sickness[] = $tgtData['sickness5']  ? $tgtData['sickness5'] : "";
        $sickness[] = $tgtData['sickness6']  ? $tgtData['sickness6'] : "";
        $sickness[] = $tgtData['sickness7']  ? $tgtData['sickness7'] : "";
        $sickness[] = $tgtData['sickness8']  ? $tgtData['sickness8'] : "";
        $sickness[] = $tgtData['sickness9']  ? $tgtData['sickness9'] : "";
        $sickness[] = $tgtData['sickness10'] ? $tgtData['sickness10'] : "";
        
        // 格納
        $dispData = array_merge($dispData,$tgtData);
    }
}

/* -- その他 --------------------------------------------*/

// 別表７初期表示用病名マスタ取得
$where = array();
$where['delete_flg'] = 0;
$where['name'] = $sickness;
$temp = select('mst_disease', '*', $where);
foreach ($temp as $key =>$val){
    // カナ情報が入れば不要
    $val['kana'] = isset($val['kana']) ? $val['kana'] : "";

    // 格納
    $name = $val['name'];
    $sickList[$name] = $val;
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
