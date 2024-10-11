<?php
//=====================================================================
// 看多機記録
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
$dispDrg  = array();
$dispExc  = array();
$dispGds  = array();
$dispStf  = array();
$dispVtl  = array();
$dispWtr  = array();
$tgtData  = array();
$upData   = array();
$upDrg    = array();
$upExc    = array();
$upGds    = array();
$upStf    = array();
$upVtl    = array();
$upWtr    = array();
$userId   = NULL;

$otherWindowURL = array();

// 対象テーブル
$table1 = 'doc_kantaki';
$tblDrg = 'doc_kantaki_drug';
$tblExc = 'doc_kantaki_excretion';
$tblGds = 'doc_kantaki_goods';
$tblStf = 'doc_kantaki_staff';
$tblVtl = 'doc_kantaki_vital';
$tblWtr = 'doc_kantaki_water';

// 初期値
$dispData = initTable($table1);
$dispData['other_id']    = NULL;
$dispData['user_name']   = NULL;
$week = formatDateTime(NOW, 'w');
$weekDisp = '('.$weekAry[$week].')';
$dispData['disp_report']     = formatDateTime(NOW, 'Y年m月d日').$weekDisp;
$dispData['disp_first']      = $dispData['disp_report'];
$dispData['staff_name']      = NULL;
$dispData['create_day']      = NULL;
$dispData['create_time']     = NULL;
$dispData['create_name']     = NULL;
$dispData['update_day']      = NULL;
$dispData['update_time']     = NULL;
$dispData['update_name']     = NULL;
$dispData['last_wght1']      = NULL;
$dispData['last_diff_wght1'] = NULL;
$dispData['last_wght2']      = NULL;
$dispData['last_diff_wght2'] = NULL;
$dispData['last_wght3']      = NULL;
$dispData['last_diff_wght3'] = NULL;
$dispData['exc_sum']         = NULL;

$wtrSum                      = NULL;
$excSum                      = NULL;
$lastWght1                   = NULL;
$lastWght2                   = NULL;
$lastWght3                   = NULL;
$diffWght1                   = NULL;
$diffWght2                   = NULL;
$diffWght3                   = NULL;

$selHour = ['','00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
$selMinutes = ['','00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55'];

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// KEY
$keyId = filter_input(INPUT_GET, 'id');

// 印刷パラメタ
$prt = filter_input(INPUT_GET, 'prt');

// 利用者ID
$userId = filter_input(INPUT_GET, 'user');
if (!$userId) {
    $userId = !empty($_SESSION['user']) ? $_SESSION['user'] : NULL;
}

// プランID
$planId = filter_input(INPUT_GET, 'plan');

/*-- 更新用パラメータ ---------------------------------------*/

// 更新ボタン
$btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));

// 削除ボタン
$btnDel = h(filter_input(INPUT_POST, 'btnDel'));

// 更新配列(看多機記録)
$upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

// 更新配列(時刻)
$upTime = filter_input(INPUT_POST, 'upTime', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upTime = $upTime ? $upTime : array();

// 更新配列(看多機記録-服薬)
$upDrg1 = filter_input(INPUT_POST, 'upDrg1', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDrg1 = $upDrg1 ? $upDrg1 : array();

// 更新配列(看多機記録-排泄)
$upExc1 = filter_input(INPUT_POST, 'upExc1', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upExc1 = $upExc1 ? $upExc1 : array();

// 更新配列(看多機記録-物品)
$upGds1 = filter_input(INPUT_POST, 'upGds1', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upGds1 = $upGds1 ? $upGds1 : array();

// 更新配列(看多機記録-スタッフ)
$upStf1 = filter_input(INPUT_POST, 'upStf1', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upStf1 = $upStf1 ? $upStf1 : array();

// 更新配列(看多機記録-バイタル)
$upVtl1 = filter_input(INPUT_POST, 'upVtl1', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upVtl1 = $upVtl1 ? $upVtl1 : array();

// 更新配列(看多機記録-水分摂取)
$upWtr1 = filter_input(INPUT_POST, 'upWtr1', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upWtr1 = $upWtr1 ? $upWtr1 : array();

// 身体図編集
$btnImageEdit = h(filter_input(INPUT_POST, 'btnImageEdit'));

$btnDelReceiptImg = h(filter_input(INPUT_POST, 'btnDelReceiptImg'));
$btnDelBfImg = h(filter_input(INPUT_POST, 'btnDelBfImg'));
$btnDellcImg = h(filter_input(INPUT_POST, 'btnDellcImg'));
$btnDelBtImg = h(filter_input(INPUT_POST, 'btnDelBtImg'));
$btnDelDnImg = h(filter_input(INPUT_POST, 'btnDelDnImg'));

// その他
$upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();

// 利用者ID
if (!empty($_SESSION['user_id'])){
    $userId = $_SESSION['user_id'];
}

// 更新配列(看多機記録-服薬)
$upFiles = filter_input(INPUT_POST, 'upFiles', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upFiles = $upFiles ? $upFiles : array();

/*-- その他パラメータ ---------------------------------------*/
if($btnImageEdit){
    // イメージ編集ボタン押下時に保存を動かす
    $btnEntry = '保存';
}

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
        $tgtDay = str_replace(array('年','月 ','日'), array('-','-',''), $upAry['service_day']);
        $tgtDayAry = explode('(', $tgtDay);
        $upAry['service_day'] = $tgtDayAry[0];
    }

    if(!empty($upTime['start_time_h'])
        && !empty($upTime['start_time_m'])
        && !empty($upTime['end_time_h'])
        && !empty($upTime['end_time_m'])
        ){
        $upAry['start_time']   = $upTime['start_time_h'].":".$upTime['start_time_m'];
        $upAry['end_time']     = $upTime['end_time_h'].":".$upTime['end_time_m'];
    }

    // スタッフ
    $upStf2 = array();
    $stfIds = array();
    $addSeq = 0;

    foreach ($upStf1['staff_id'] as $seq => $staffId){
        if (!empty($upStf1['staff_id'][$seq])){
            if (isset($upStf1['unique_id'][$seq])){
                $upStf2[$seq]['unique_id'] = $upStf1['unique_id'][$seq];
                $id = $upStf1['unique_id'][$seq];
                $stfIds[$id] =  $upStf1['unique_id'][$seq];
            }
            $upStf2[$seq]['staff_id'] = $upStf1['staff_id'][$seq];
            $upStf2[$seq]['name']     = $upStf1['name'][$seq];
            $upStf2[$seq]['license']  = isset($upStf1['license'][$seq])
                ? $upStf1['license'][$seq]
                : NULL;
            $addSeq ++;
        }
    }
    // 配列に無いデータを削除
    $where = array();
    $where['delete_flg'] = 0;
    $where['kantaki_id'] = $keyId;
    $temp = select('doc_kantaki_staff', '*', $where);
    foreach($temp as $val){
        $id = $val['unique_id'];
        if(isset($stfIds[$id]) === false || empty($val['staff_id'])){
            $addSeq++;
            $upStf2[$addSeq]['unique_id'] = $id;
            $upStf2[$addSeq]['delete_flg'] = 1;
        }
    }

    // サービス種類、身体介助、生活援助、医療処置、リハビリ
    if (!empty($upDummy['service_kind'])){
        foreach ($upDummy['service_kind'] as $val){
            $upAry['service_kind'] = !empty($upAry['service_kind'])
                    ? $upAry['service_kind'].'^'.$val
                    : $val;
        }
    }
    if (!empty($upDummy['body_assist'])){
        foreach ($upDummy['body_assist'] as $val){
            $upAry['body_assist'] = !empty($upAry['body_assist'])
                    ? $upAry['body_assist'].'^'.$val
                    : $val;
        }
    }
    if (!empty($upDummy['life_support'])){
        foreach ($upDummy['life_support'] as $val){
            $upAry['life_support'] = !empty($upAry['life_support'])
                    ? $upAry['life_support'].'^'.$val
                    : $val;
        }
    }
    if (!empty($upDummy['medical_procedures'])){
        foreach ($upDummy['medical_procedures'] as $val){
            $upAry['medical_procedures'] = !empty($upAry['medical_procedures'])
                    ? $upAry['medical_procedures'].'^'.$val
                    : $val;
        }
    }
    if (!empty($upDummy['rehabilitation'])){
        foreach ($upDummy['rehabilitation'] as $val){
            $upAry['rehabilitation'] = !empty($upAry['rehabilitation'])
                    ? $upAry['rehabilitation'].'^'.$val
                    : $val;
        }
    }
    // バイタル
    $upVtl2 = array();
    foreach ($upVtl1['counting_time'] as $seq => $time){
        if (!empty($upVtl1['counting_time'][$seq])){
            if (isset($upVtl1['unique_id'][$seq])){
                $upVtl2[$seq]['unique_id'] = $upVtl1['unique_id'][$seq];
            }
            $upVtl2[$seq]['counting_time']    = $upVtl1['counting_time'][$seq];
            $upVtl2[$seq]['temperature']      = $upVtl1['temperature'][$seq];
            $upVtl2[$seq]['pulse']            = $upVtl1['pulse'][$seq];
            $upVtl2[$seq]['blood_pressure1']  = $upVtl1['blood_pressure1'][$seq];
            $upVtl2[$seq]['blood_pressure2']  = $upVtl1['blood_pressure2'][$seq];
            $upVtl2[$seq]['spo2']             = $upVtl1['spo2'][$seq];
        }
    }

    // 水分摂取
    $upWtr2 = array();
    foreach ($upWtr1['counting_time'] as $seq => $time){
        if (!empty($upWtr1['counting_time'][$seq])){
            if (isset($upWtr1['unique_id'][$seq])){
                $upWtr2[$seq]['unique_id'] = $upWtr1['unique_id'][$seq];
            }
            $upWtr2[$seq]['counting_time'] = $upWtr1['counting_time'][$seq];
            $upWtr2[$seq]['amount']        = $upWtr1['amount'][$seq];
            $upWtr2[$seq]['method']        = $upWtr1['method'][$seq];
        }
    }

    // 排泄
    $upExc2 = array();
    foreach ($upExc1['counting_time'] as $seq => $time){
        if (!empty($upExc1['counting_time'][$seq])){
            if (isset($upExc1['unique_id'][$seq])){
                $upExc2[$seq]['unique_id'] = $upExc1['unique_id'][$seq];
            }
            $upExc2[$seq]['counting_time']      = $upExc1['counting_time'][$seq];
            $upExc2[$seq]['urination']          = isset($upExc1['urination'][$seq]) ? $upExc1['urination'][$seq] : NULL;
            $upExc2[$seq]['urination_quantity'] = $upExc1['urination_quantity'][$seq];
            $upExc2[$seq]['evacuation']         = isset($upExc1['evacuation'][$seq]) ? $upExc1['evacuation'][$seq] : NULL;
            $upExc2[$seq]['evacuation_memo']    = $upExc1['evacuation_memo'][$seq];
        }
    }

    // 物品使用
    $upGds2 = array();
    foreach ($upGds1['goods_name'] as $seq => $name){
        if (!empty($upGds1['goods_name'][$seq])){
            if (isset($upGds1['unique_id'][$seq])){
                $upGds2[$seq]['unique_id'] = $upGds1['unique_id'][$seq];
            }
            $upGds2[$seq]['goods_name'] = $upGds1['goods_name'][$seq];
            $upGds2[$seq]['quantity']   = $upGds1['quantity'][$seq];
        }
    }

    // 服薬
    $upDrg2 = array();
    foreach ($upDrg1['timing'] as $seq => $name){
        if (!empty($upDrg1['timing'][$seq])){
            if (isset($upDrg1['unique_id'][$seq])){
                $upDrg2[$seq]['unique_id'] = $upDrg1['unique_id'][$seq];
            }
            $upDrg2[$seq]['timing']   = $upDrg1['timing'][$seq];
            $upDrg2[$seq]['medicine'] = $upDrg1['medicine'][$seq];
        }
    }

    // 排尿／排便チェックボックスのケア


    // 服薬チェックボックスのケア
    $upAry['drug_bb'] = isset($upAry['drug_bb']) ? $upAry['drug_bb'] : NULL;
    $upAry['drug_ab'] = isset($upAry['drug_ab']) ? $upAry['drug_ab'] : NULL;
    $upAry['drug_bl'] = isset($upAry['drug_bl']) ? $upAry['drug_bl'] : NULL;
    $upAry['drug_al'] = isset($upAry['drug_al']) ? $upAry['drug_al'] : NULL;
    $upAry['drug_bd'] = isset($upAry['drug_bd']) ? $upAry['drug_bd'] : NULL;
    $upAry['drug_ad'] = isset($upAry['drug_ad']) ? $upAry['drug_ad'] : NULL;

    // 更新配列
    $upData = $upAry;

}

/* -- 削除用配列作成 ----------------------------------------*/

// 削除配列
if ($btnDel) {
    $upData['unique_id'] = $btnDel;
    $upData['delete_flg'] = '1';
}

// レシート画像削除
if($btnDelReceiptImg){
    $upData['unique_id']   = $btnDelReceiptImg;
    $upData['receipt_img'] = '';
    $btnEntry = TRUE;
}

// 朝食画像削除
if($btnDelBfImg){
    $upData['unique_id']   = $btnDelBfImg;
    $upData['breakfast_img'] = '';
    $btnEntry = TRUE;
}

// 昼食画像削除
if($btnDellcImg){
    $upData['unique_id']   = $btnDellcImg;
    $upData['lunch_img'] = '';
    $btnEntry = TRUE;
}

// おやつ画像削除
if($btnDelBtImg){
    $upData['unique_id']   = $btnDelBtImg;
    $upData['bite_img'] = '';
    $btnEntry = TRUE;
}

// 夕食画像削除
if($btnDelDnImg){
    $upData['unique_id']   = $btnDelDnImg;
    $upData['dinner_img'] = '';
    $btnEntry = TRUE;
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

    // 登録済み情報取得
    $where = array();
    $where['unique_id'] = $keyId;
    $temp = select('doc_kantaki', '*', $where);
    $tgtData = $temp[0];

    // 画像パス
    $imagePath = '/upload/user/kantaki/'.$keyId;

    // レシート画像ファイルの更新
    $columns = array();
    $columns['receipt_img'] = FALSE;
    if(!empty($_FILES['receipt_img']['name'])){
        $res = fileDataUpdate($loginUser, $_FILES['receipt_img'], $imagePath, 'doc_kantaki', $keyId, $columns, $tgtData);
        if (isset($res['err'])) {
            $err[] = '画像の更新に失敗しました';
            throw new Exception();
        }
    }

    // 朝食画像ファイルの更新
    $columns = array();
    $columns['breakfast_img'] = FALSE;
    if(!empty($_FILES['breakfast_img']['name'])){
        $res = fileDataUpdate($loginUser, $_FILES['breakfast_img'], $imagePath, 'doc_kantaki', $keyId, $columns, $tgtData);
        if (isset($res['err'])) {
            $err[] = '画像の更新に失敗しました';
            throw new Exception();
        }
    }

    // 昼食画像ファイルの更新
    $columns = array();
    $columns['lunch_img'] = FALSE;
    if(!empty($_FILES['lunch_img']['name'])){
        $res = fileDataUpdate($loginUser, $_FILES['lunch_img'], $imagePath, 'doc_kantaki', $keyId, $columns, $tgtData);
        if (isset($res['err'])) {
            $err[] = '画像の更新に失敗しました';
            throw new Exception();
        }
    }

    // おやつ画像ファイルの更新
    $columns = array();
    $columns['bite_img'] = FALSE;
    if(!empty($_FILES['bite_img']['name'])){
        $res = fileDataUpdate($loginUser, $_FILES['bite_img'], $imagePath, 'doc_kantaki', $keyId, $columns, $tgtData);
        if (isset($res['err'])) {
            $err[] = '画像の更新に失敗しました';
            throw new Exception();
        }
    }

    // 夕食画像ファイルの更新
    $columns = array();
    $columns['dinner_img'] = FALSE;
    if(!empty($_FILES['dinner_img']['name'])){
        $res = fileDataUpdate($loginUser, $_FILES['dinner_img'], $imagePath, 'doc_kantaki', $keyId, $columns, $tgtData);
        if (isset($res['err'])) {
            $err[] = '画像の更新に失敗しました';
            throw new Exception();
        }
    }

    // 服薬
    if (!empty($upDrg2)){
        foreach ($upDrg2 as $key => $val){
            $val['kantaki_id'] = $keyId;
            $upDrg2[$key] = $val;
        }
        $res = multiUpsert($loginUser, $tblDrg, $upDrg2);
        if (isset($res['err'])){
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upDrg2);
    }

    // 排泄
    if (!empty($upExc2)){
        foreach ($upExc2 as $key => $val){
            $val['kantaki_id'] = $keyId;
            $upExc2[$key] = $val;
        }
        $res = multiUpsert($loginUser, $tblExc, $upExc2);
        if (isset($res['err'])){
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }

        // ログテーブルに登録する
        setMultiEntryLog($upExc2);
    }

    // 物品
    if (!empty($upGds2)){
        foreach ($upGds2 as $key => $val){
            $val['kantaki_id'] = $keyId;
            $upGds2[$key] = $val;
        }
        $res = multiUpsert($loginUser, $tblGds, $upGds2);
        if (isset($res['err'])){
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        // ログテーブルに登録する
        setMultiEntryLog($upGds2);
    }

    // スタッフ
    if (!empty($upStf2)){
        foreach ($upStf2 as $key => $val){
            $val['kantaki_id'] = $keyId;
            $upStf2[$key] = $val;
        }
        $res = multiUpsert($loginUser, $tblStf, $upStf2);
        if (isset($res['err'])){
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        // ログテーブルに登録する
        setMultiEntryLog($upStf2);
    }

    // バイタル
    if (!empty($upVtl2)){
        foreach ($upVtl2 as $key => $val){
            $val['kantaki_id'] = $keyId;
            $upVtl2[$key] = $val;
        }
        $res = multiUpsert($loginUser, $tblVtl, $upVtl2);
        if (isset($res['err'])){
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        // ログテーブルに登録する
        setMultiEntryLog($upVtl2);
    }

    // 水分摂取
    if (!empty($upWtr2)){
        foreach ($upWtr2 as $key => $val){
            $val['kantaki_id'] = $keyId;
            $upWtr2[$key] = $val;
        }
        $res = multiUpsert($loginUser, $tblWtr, $upWtr2);
        if (isset($res['err'])){
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        // ログテーブルに登録する
        setMultiEntryLog($upWtr2);
    }

    // 身体図編集画面へ遷移
    if($btnImageEdit){
        // 画面遷移
        $nextPage = "/report/kantaki/edit.php".'?id='. $keyId .'&user='.$userId;
        header("Location:" . $nextPage);
        exit();
    }

    $_SESSION['notice']['success'][] = "登録が完了しました";

    // 画面遷移
    $_SESSION['user'] = $userId;
    $nextPage = "/report/kantaki/index.php".'?id='.$keyId.'&user='.$userId;
//    $nextPage = $server['scriptName'].'?id='.$keyId.'&user='.$userId;
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
    $nextPage = '/report/kantaki_list/index.php';
    header("Location:". $nextPage);
    exit();
}

/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- データ取得 --------------------------------------------*/

/* -- 汎用マスタ ---------------------------*/
$gnrList = getCode('看多機記録');

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
    $userId = $userList[$userId]['unique_id'];
}

/* -- スタッフマスタ -----------------------*/
$where = array();
$where['delete_flg'] = 0;
$target = 'unique_id, staff_id, last_name, first_name';
$temp = select('mst_staff','*',$where);
foreach ($temp as $val){
    $tgtId       = $val['unique_id'];
    $val['name'] = $val['last_name'].$val['first_name'];
    $staffList[$tgtId] = $val;
}

/* -- 看多機記録 ------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id']  = $keyId;
    $temp = select($table1, '*', $where);

    if (isset($temp[0])){

        // テーブル値
        $tgtData = $temp[0];

        // 利用者ID
        $userId = (!empty($tgtData['user_id']) ? $tgtData['user_id'] : $userId);

        if (empty($tgtData['service_day']) && empty($tgtData['start_time']) && empty($tgtData['end_time'])) {

            $where = array();
            $where['delete_flg'] = 0;
            $where['unique_id']  = $planId;
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

        $tgtData['start_time'] = formatDateTime($tgtData['start_time'], 'H:i');
        $tgtData['end_time'] = formatDateTime($tgtData['end_time'], 'H:i');

        // 格納
        $dispData = array_merge($dispData,$tgtData);
    }
}
/* -- 看多機記録-服薬 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['kantaki_id']  = $keyId;
    $target = '*';
    $temp = select($tblDrg,$target,$where);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];
        $dispDrg[$tgtId] = $val;
    }
}
/* -- 看多機記録-排泄 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['kantaki_id']  = $keyId;
    $target = '*';
    $temp = select($tblExc,$target,$where);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];

        // 排尿量の合計を算出
        if(isset($dispData['exc_sum']) === false){
            $dispData['exc_sum'] = 0;
        }
        $dispData['exc_sum'] += intval($val['urination_quantity']);

        $dispExc[$tgtId] = $val;
    }
}
/* -- 看多機記録-物品 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['kantaki_id']  = $keyId;
    $target = '*';
    $temp = select($tblGds,$target,$where);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];
        $dispGds[$tgtId] = $val;
    }
}
/* -- 看多機記録-スタッフ -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['kantaki_id']  = $keyId;
    $target = '*';
    $temp = select($tblStf,$target,$where);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];
        $stfId = $val['staff_id'];
        $val['other_id'] = $staffList[$stfId]['staff_id'];
        $dispStf[$tgtId] = $val;
    }
}

if (empty($dispStf)) {
    $dispStf[$loginUser['unique_id']] = $loginUser;
    $dispStf[$loginUser['unique_id']]['other_id'] = $loginUser['staff_id'];
}

/* -- 看多機記録-バイタル -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['kantaki_id']  = $keyId;
    $target = '*';
    $temp = select($tblVtl,$target,$where);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];
        $dispVtl[$tgtId] = $val;
    }
}
/* -- 看多機記録-水分摂取 -----------------------------*/
if ($keyId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['kantaki_id']  = $keyId;
    $target = '*';
    $temp = select($tblWtr,$target,$where);
    foreach ($temp as $val){
        $tgtId = $val['unique_id'];
        $dispWtr[$tgtId] = $val;
    }
}
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

    $res = printPDF('022', $search);
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
