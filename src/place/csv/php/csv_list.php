<?php
//=====================================================================
// CSV出力
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
$err                 = array();
$notice              = array();
$dispData            = array();
$tgtData             = array();
$tgtSearch           = array();
$upSearch            = array();
$output              = array();
$tgtSearch['type']                    = '利用者情報';
$tgtSearch['start_day']               = THISMONTHFIRST;
$tgtSearch['end_day']                 = THISMONTHLAST;
$tgtSearch['place_id']                = NULL;
$tgtSearch['office_id']               = NULL;
$tgtSearch['user_id']                 = NULL;
$tgtSearch['service_type']            = "医療保険訪問看護^看護小規模多機能^指定訪問看護^定期巡回^医療保険訪問看護+看護小規模多機能^医療保険訪問看護+指定訪問看護^医療保険訪問看護+定期巡回^医療訪看・指定訪看・看多機^指定訪看・看多機"; 
$tgtSearch['target'] = array();
$tgtSearch['target']['standard']      = NULL;
$tgtSearch['target']['insure1']       = NULL;
$tgtSearch['target']['insure3']       = NULL;
$tgtSearch['target']['introduct']     = NULL;
$tgtSearch['target']['family']        = '連絡先情報';
$tgtSearch['target']['user_plan']     = NULL;
$tgtSearch['target']['service']       = NULL;
$tgtSearch['target']['add']           = NULL;
$tgtSearch['target']['jippi']         = NULL;
$tgtSearch['target']['staff_plan']    = NULL;
$tgtSearch['target']['service_type']  = NULL;

// 対象テーブル(メイン)
$table = '';

// 表示件数
$line = 20;

/* ===================================================
 * 入力情報取得
 * ===================================================
 */

/*-- 検索用パラメータ ---------------------------------------*/

// 拠点ID
$placeId = filter_input(INPUT_GET, 'place');
if (!$placeId) {
    $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : NULL;
}

// 検索ボタン
$btnCsvEntry = h(filter_input(INPUT_POST, 'btnCsvEntry'));
$upSearch = filter_input(INPUT_POST, 'upSearch', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$upSearch = !empty($upSearch) ? $upSearch : array();

/*-- 更新用パラメータ ---------------------------------------*/

/*-- その他パラメータ ---------------------------------------*/

 /* =================================================== 
 * マスタ取得
 * ===================================================
 */

// 拠点一覧
$plcMst = getPlaceList(NULL);

// 事業所
$ofcList = getOfficeList();
foreach ($ofcList as $val){
    $ofcId = $val['unique_id'];
    $plcOfc[$ofcId] = $val;
}

// 汎用マスタ
$gnrList = getCode();   

// ユーザマスタ
$userList = getUserList();

// ページャー

/* ===================================================
 * イベント前処理(更新用配列作成、入力チェックなど)
 * ===================================================
 */

/* ===================================================
 * イベント本処理(データ登録)
 * ===================================================
 */

/* -- CSV用配列作成 ----------------------------------------*/
if($btnCsvEntry && $upSearch){

    if(empty($upSearch['place_id'])){
        $upSearch['place_id'] = $placeId;
    }
    $upSearch['service'] = !empty($upSearch['service_type']) ? impload("^", $upSearch['service_type']) : NULL;;
    $tgtSearch = $upSearch;
    
    $res = getTotal($tgtSearch);

    /* -- CSV出力ファイル作成 ---------------------------------- */

    // ヘッダー部
    // データ部(集計)
    foreach ($res as $div => $dataList) {
        foreach($dataList as $idx => $dat){
            $record = array();
            foreach($dat as $colName => $item){
                $record[] = isset($item) ? $item : '';
            }
            $output[] = $record;
        }
    }    
    
    /* -- CSV出力処理 ------------------------------------------ */
    $type="csvoutput";

    // ディレクトリ生成
    $dir = SV_ROOT . '/csv/' . $type . '/';

    if (!is_dir($dir)) {
        umask(0);
        if (!mkdir($dir, 0777)) {
            $err[] = 'CSV出力フォルダ作成に失敗しました。';
            throw new Exception();
        }
    }

    // ファイル名称、参照パス
    $filename = $type . '_' . date('YmdHis') . '.csv';
    $filepath = $dir . $filename;

    $_SESSION['file_path'] = $filepath;
    $_SESSION['file_name'] = $filename;

    // CSV出力処理
    writeCsv($filepath, $output);
    
    $file = file_get_contents($filepath);
    
    header("Content-Type: application/octet-stream");
    header('Content-Disposition: attachment; filename='.$filename); 
    header("Content-Transfer-Encoding: binary");
    header('Content-Length: '.filesize($filepath));
    
    echo $file;
//    exit();
    
}


/* ===================================================
 * イベント後処理(描画用データ作成)
 * ===================================================
 */

/* -- データ取得 --------------------------------------------*/

// ページャー
//$dispData = getPager($tgtData, $page, $line);
$dispSearch = $tgtSearch;

/* =================================================== 
 * 例外処理
 * ===================================================
 */
} catch(Exception $e){
    debug($e);
    exit();
    
    $_SESSION['err'] = !empty($err) ? $err : array();
    header("Location:". ERROR_PAGE);
    exit();
}
