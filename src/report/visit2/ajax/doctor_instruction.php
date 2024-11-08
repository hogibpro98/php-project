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
$sendData    = array();
/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

// ユーザーID
$userId    = h(filter_input(INPUT_POST, 'user_id'));

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */
/* -- データ取得 ----------------------------------------*/

// 訪問看護記録Ⅱから医師からの指示を取得する
$doctorInstruction = "";
if($userId){
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userId;
    $orderBy = 'unique_id DESC';
    $limit = 1;
    $target = '*';
    $temp = select('doc_visit2',$target,$where, $orderBy, $limit);
    foreach ($temp as $val){
        $doctorInstruction = $val['doctor_instruction'];
    }
}
/* -- その他 --------------------------------------------*/


/* -- データ送信 ----------------------------------------*/
$sendData = $doctorInstruction;
echo $sendData;
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
