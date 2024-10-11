<?php
//=====================================================================
// [ajax]基本情報検索
//=====================================================================
try {
/* =================================================== 
 * 初期処理
 * ===================================================
 */
 
/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'].'/common/php/com_start.php');

/*--変数定義-------------------------------------------------*/
$notice   = NULL;
$sendData = array();

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

// 検索場所
$type = h(filter_input(INPUT_GET, 'type'));

// 更新内容
$upAry = filter_input(INPUT_GET, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

$upDummy = filter_input(INPUT_GET, 'upDummy', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upDummy = $upDummy ? $upDummy : array();


/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */
/* -- データ取得 ----------------------------------------*/



/* -- 検索結果反映 --------------------------------------*/

// 居宅情報検索
if ($type === 'office'){
    
    // 初期化
    $upAry['office']    = NULL;
    $upAry['person']    = NULL;
    $upAry['address2']  = NULL;
    $upAry['tel3']      = NULL;
    $upAry['fax2']      = NULL;
    
    // 入力内容がある時に検索実行
    if (!empty($upAry['user_id']) || !empty($upAry['first_day'])){
                
        // マスタ取得
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), $upAry['first_day']);

        $where = array();
        $where['delete_flg']   = 0;
        $where['user_id']      = $upAry['user_id'];
        $where['start_day <='] = $tgtDay;
        $where['end_day >=']   = $tgtDay;
        $temp = select('mst_user_office2', '*', $where);

        // 居宅情報を補完
        if (isset($temp[0])){
            
            // 事業所名称、担当者、所在地、電話番号、ＦＡＸ
            $upAry['office']   = !empty($temp[0]['office_name'])
                    ? $temp[0]['office_name']
                    : NULL;
            $upAry['person']   = !empty($temp[0]['person_name'])
                    ? $temp[0]['person_name']
                    : NULL;
            $upAry['address2']   = !empty($temp[0]['address'])
                    ? $temp[0]['address']
                    : NULL;
            $upAry['tel3']   = !empty($temp[0]['tel'])
                    ? $temp[0]['tel']
                    : NULL;
            $upAry['fax2']   = !empty($temp[0]['fax'])
                    ? $temp[0]['fax']
                    : NULL;

        } else {
            $notice = '検索条件に合致しません';
        }
    }
}

/* -- データ送信 ----------------------------------------*/
$sendData = array();
foreach ($upAry as $key => $val){
    $sendData['upAry['.$key.']'] = $val;
}
foreach ($upDummy as $key => $val){
    $sendData['upDummy['.$key.']'] = $val;
}
// -----------------------------------------------------------

if ($sendData){
    echo sprintf("setMultiValue(%s);",jsonEncode($sendData));
}

// メッセージ送信
if ($notice){
    echo sprintf("noticeModal(%s);",jsonEncode($notice));
}
exit();

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
