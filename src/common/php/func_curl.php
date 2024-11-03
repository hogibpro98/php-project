<?php

// =======================================================================
// API 操作関数
// =======================================================================
/*   1. responseLog               [レスポンスログ出力用関数]
 *   2. phpCurl                   [php curl 関数]
 *   3. phpCurlJson               [php curl 関数(json渡し)]
 *
 * -----------------------------------------------------------------------
 */

/* -- 定数定義 ----------------------------------------------------*/

// ひつじメニューのON,OFF
define("HTJ_FLG", true);

// 法人番号
define("HTJ_CMP_NO", "0001000");

// メニュー表示用URL
define("HTJ_URL_MENU", "https://test.h2-platform.com/Login/KantakiLogin/");
//define("HTJ_URL_MENU", "https://h2-platform.com/Login/KantakiLogin/");

// 未読件数API用URL
define("HTJ_URL_UNREAD", "https://test.day-web.com/hitsujiapi-hq/api/notReadCommentCnt/send-comment-count/");
//define("HTJ_URL_UNREAD", "https://www.accountability-web.com/hitsujiapi-hq/api/notReadCommentCnt/send-comment-count/");

/* =============================================================================
 * レスポンスログ出力用関数
 * =============================================================================
 */
function responseLog($ary)
{

    $output = array();

    // ディレクトリ生成
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/csv/orico/';
    if (!is_dir($dir)) {
        umask(0);
        if (!mkdir($dir, 0777)) {
            $err[] = 'CSV出力フォルダ作成に失敗しました。';
            throw new Exception();
        }
    }

    // ファイル名称、参照パス
    $filename = 'orico_' . date('YmdHis') . '.csv';
    $filepath = $dir . '/' . $filename;
    $output[][] = jsonEncode($ary);
    writeCsv($filepath, $output);

    return true;
}
/* =============================================================================
 * php curl 関数
 * =============================================================================
 */
function phpCurl($url, $param)
{

    // 初期化
    $res = array();

    // リクエストコネクションの設定
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);

    // セキュリティ例外(テストモード)
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    // リクエストボディの生成
    curl_setopt($curl, CURLOPT_POSTFIELDS, $param);

    // リクエスト送信
    $response = curl_exec($curl);
    $curlinfo = curl_getinfo($curl);
    curl_close($curl);

    $res['response'] = $response;
    $res['curlinfo'] = $curlinfo;

    return $res;
}
/* =============================================================================
 * php curl 関数(json渡し)
 * =============================================================================
 */
function phpCurlJson($url, $param)
{

    $res = array();

    $jsonParam = json_encode($param);

    // リクエストコネクションの設定
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, true);            // POST指定
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json;charset=UTF-8'));// HTTPヘッダー指定
    curl_setopt($curl, CURLOPT_URL, $url);             // 取得するURLを指定
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // 実行結果を文字列で返す
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // サーバー証明書の検証を行わない

    // リクエストボディの生成
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonParam);// 入力パラメータ指定

    // リクエスト送信
    $response = curl_exec($curl);
    $curlinfo = curl_getinfo($curl);
    curl_close($curl);

    $res['response'] = $response;
    $res['curlinfo'] = $curlinfo;

    return $res;
}


/* -- 固有関数 ---------------------------------------------*/


/* =============================================================================
 * ひつじ：メニュー取得
 * =============================================================================
 */
function getHtjMenu($loginUser)
{

    // 初期化
    $res   = null;
    $ofcId = null;
    $ofcNo = null;

    // ログイン情報チェック
    if (!$loginUser) {
        return $res;
    }
    // 従業員ID
    $stfId = $loginUser['unique_id'];

    // 事業所マスタ
    $where = array();
    $where['staff_id'] = $stfId;
    $temp = getData('mst_staff_office', $where);
    foreach ($temp as $val) {
        $ofcId = $val['office1_id'];
    }
    $where = array();
    $where['unique_id'] = $ofcId;
    $ofcVal = getData('mst_office', $where);
    $ofcNo = $ofcVal['other_code'];

    // 法人番号,メールアドレス,利用者番号
    $cmpNo = HTJ_CMP_NO;
    $mail = $loginUser['mail'];
    $usrNo = $loginUser['staff_id'];

    // 送信パラメータチェック
    if (!$cmpNo || !$ofcNo || !$mail || !$usrNo) {
        return $res;
    }

    // メニューURL作成
    $res = HTJ_URL_MENU;
    $res .= '?hojin_no=' . $cmpNo;
    $res .= '&jigyo_no=' . $ofcNo;
    $res .= '&mail_address=' . $mail;
    $res .= '&user_id=' . $usrNo;

    // データ返却
    return $res;
}
/* =============================================================================
 * ひつじ：未読件数取得
 * =============================================================================
 */
function getHtjUnread($loginUser)
{

    // 初期化
    $res   = 0;
    $ofcId = null;

    // ログイン情報チェック
    if (!$loginUser) {
        return $res;
    }

    // 従業員ID
    $stfId = $loginUser['unique_id'];

    // 事業所マスタ
    $where = array();
    $where['staff_id'] = $stfId;
    $temp = getData('mst_staff_office', $where);
    foreach ($temp as $val) {
        $ofcId = $val['office1_id'];
    }
    $where = array();
    $where['unique_id'] = $ofcId;
    $ofcVal = getData('mst_office', $where);
    $ofcNo = $ofcVal['other_code'];

    // 法人番号,メールアドレス,利用者番号
    $cmpNo = HTJ_CMP_NO;
    $mail  = $loginUser['mail'];
    $usrNo = $loginUser['staff_id'];

    // 送信パラメータチェック
    if (!$cmpNo || !$ofcNo || !$mail || !$usrNo) {
        return $res;
    }

    // API URL作成
    $url = HTJ_URL_UNREAD;

    // パラメータ作成
    $param['hojin_no'] = $cmpNo;
    $param['jigyo_no'] = $mail;
    $param['mail_address'] = $mail;
    $param['user_id'] = $usrNo;

    // 通信実行
    $result = phpCurlJson($url, $param);

    // レスポンス取得
    if (isset($result['response'])) {
        $res = $result['response'];
    }

    // 返却
    return $res;
}
