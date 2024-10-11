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
$upFml = array();

/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

// 検索場所
$type = h(filter_input(INPUT_GET, 'type'));

// 更新内容
$upAry = filter_input(INPUT_GET, 'upAry', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$upAry = $upAry ? $upAry : array();

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */
/* -- データ取得 ----------------------------------------*/

/* -- 検索結果反映 --------------------------------------*/

// 家族構成情報検索
if ($type === 'family'){
    
    // 初期化
    $upFmlDef['name']          = NULL;
    $upFmlDef['age']           = NULL;
    $upFmlDef['relation']      = NULL;
    $upFmlDef['relation_memo'] = NULL;
    $upFmlDef['job']           = NULL;
    $upFmlDef['remarks']       = NULL;
    
    // 入力内容がある時に検索実行
    if (!empty($upAry['common']['user_id'])){
                
        // マスタ取得
        $where = array();
        $where['delete_flg']   = 0;
        $where['user_id']      = $upAry['common']['user_id'];//'user00002618'
        $orderBy ='unique_id DESC';
        $limit = 3;
        $temp = select('mst_user_family', '*', $where, $orderBy, $limit);

        if (!empty($temp)){

            // ソート
            foreach ($temp as $seq => $val){
                $sort1_array[] = $val['unique_id'];
            }

//            array_multisort( $sort1_array, SORT_ASC, SORT_REGULAR, $temp);
            
            foreach ($temp as $val){
                
                // 家族構成情報を補完
                $tgtId = $val['unique_id'];

                // 氏名、続柄、続柄メモ、職業、備考
                $upFml[$tgtId]['name'] = !empty($val['name'])
                        ? $val['name']
                        : NULL;
                $upFml[$tgtId]['relation'] = !empty($val['relation_type'])
                        ? $val['relation_type']
                        : NULL;
                $upFml[$tgtId]['relation_memo'] = !empty($val['relation_memo'])
                        ? $val['relation_memo']
                        : NULL;
                $upFml[$tgtId]['job'] = !empty($val['business'])
                        ? $val['business']
                        : NULL;
                $upFml[$tgtId]['remarks'] = !empty($val['remarks'])
                        ? $val['remarks']
                        : NULL;

            }
            
        } else {
            $notice = '検索条件に合致しません';
        }

    }
}

/* -- データ送信 ----------------------------------------*/
$sendData = array();
$i =0;
foreach ($upFml as $ary){
    $i++;
    foreach ($ary as $key => $val){
        $sendData['upFml['.$i.']['.$key.']'] = $val;
    }
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
