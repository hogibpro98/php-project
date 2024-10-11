<?php
//=====================================================================
// [ajax]指示書情報検索
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

// 主たる傷病名情報検索
if ($type === 'sick'){
    
    // 初期化
    $upAry['main_sickness']  = NULL;
    
    // 入力内容がある時に検索実行
    if (!empty($upDummy['other_id']) || !empty($upAry['staff_id']) || !empty($upAry['care_kb']) || !empty($upAry['first_day'])){
                
        // マスタ取得
        $tgtDay = str_replace(array('年','月','日'), array('-','-',''), $upAry['first_day']);
        
        $where = array();
        $where['delete_flg']        = 0;
        $where['user_id']           = $upDummy['other_id'];
        $where['staff_id']          = $upAry['staff_id'];
        $where['direction_start <= '] = $tgtDay;
        $where['direction_end >= ']   = $tgtDay;
        if ($upAry['care_kb'] == '訪問看護'){
            $where['care_kb']       = '一般';
        } elseif ($upAry['care_kb'] == '精神科訪問看護'){
            $where['care_kb']       = '精神';
        }
        $orderBy ='unique_id DESC';
        $temp = select('doc_instruct', '*', $where, $orderBy);

        // 主たる傷病名情報を補完
        if (isset($temp[0])){
            
            $upAry['main_sickness']   = !empty($temp[0]['sickness1'])
                    ? $temp[0]['sickness1']
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
