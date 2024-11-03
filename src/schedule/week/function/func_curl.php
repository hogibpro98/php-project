<?php

// =======================================================================
// GMO API 操作関数
// =======================================================================
/*   0. responseLog               [レスポンスログ出力用関数]
 *   1. phpCurl                   [php curl 関数]
 *   2. phpCurlJson               [php curl 関数(json渡し)]
 *   3. sample                    [サンプル]
 *
 *
 * -----------------------------------------------------------------------
 */

// 決済サービスの場合 検証用カード番号
//    Visa  :4111111111111111
//    Master:5555555555554444
//    JCB   :3530111333300000

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
 * 暗号鍵取得
 * =============================================================================
 */
function getKeyCurl($ordData = array())
{

    // 初期化
    $res = array();

    // マーチャントシード、マーチャントID、決済タイプ、取引ID、金額
    $mctSd   = 'aaa';
    $mctId   = 'bbb';
    $mctType = '01';
    $orderId = $ordData['id'];
    $price   = $ordData['total_price'];
    $hashOrg = $mctSd . ',' . $mctId . ',' . $mctType . ',' . $orderId . ',' . $price;
    $hash    = hash('sha512', $hashOrg);

    // param作成
    $url = 'https://pay.veritrans.co.jp/web1/commodityRegist.action';
    $param = array();
    $param['MERCHANT_ID']          = $mctId;
    $param["ORDER_ID"]             = $orderId;
    $param["MERCHANTHASH"]         = $hash;
    $param["SESSION_ID"]           = 'testSessionID';
    $param["AMOUNT"]               = $price;
    $param["SETTLEMENT_TYPE"]      = $mctType;
    $param["CARD_INSTALLMENT_JPO"] = '10';
    $param["CARD_CAPTURE_FLAG"]    = '0';
    $param["DDD_ENABLE_FLAG"]      = '0';
    $param["AUTO_RETURN_FLAG"]     = '1';

    // テスト用パラメータ(本番時はコメントアウト)
    $param["DUMMY_PAYMENT_FLAG"]   = '1';

    // 通信実行
    $result = phpCurl($url, $param);

    // ログ出力
    responseLog($result);

    // 結果param解析
    parse_str($result['response'], $res);

    // データ返却
    return $res;
}
