<?php

/* --共通ファイル呼び出し------------------------------------- */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

// try{
$response = "";
$uniqueId = h(filter_input(INPUT_POST, 'unique_id'));
$userId = h(filter_input(INPUT_POST, 'user_id'));
$binFileData = h(filter_input(INPUT_POST, 'bin_file_data'));
$lifeImage = h(filter_input(INPUT_POST, 'life_image'));
$imageJson = filter_input(INPUT_POST, 'image_json');
$loginUser = isset($_SESSION['login']) ? $_SESSION['login'] : array();
$staffId = "";
if (!isset($loginUser['unique_id'])) {
    $staffId = 'system';
    $loginUser = array();
    $loginUser['unique_id'] = 'system';
}

//ヘッダに「data:image/png;base64,」が付いているので、それは外す
$canvas = preg_replace("/data:[^,]+,/i", "", $binFileData);

//残りのデータはbase64エンコードされているので、デコードする
$canvas = base64_decode($canvas);

//まだ文字列の状態なので、画像リソース化
//$image = imagecreatefromstring($canvas);
// 透明色の有効
//imagesavealpha($image, TRUE);
// fileの書き出し
$tempPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/temp/';
if (!is_dir($tempPath)) {
    mkdir($tempPath, 2755);
}
$filename = $tempPath . $lifeImage;
$result = file_put_contents($filename, $canvas);

// 対象テーブル(メイン)
$table = 'doc_kantaki';

// 初期値
$now = date('Y/m/d H:i:s');

if (!empty($uniqueId)) {
    $upData['unique_id'] = $uniqueId;
}
$upData['life_image'] = !empty($lifeImage) ? $lifeImage : "";
$upData['image_json'] = !empty($imageJson) ? $imageJson : "";

// データ更新
if ($upData) {
    // DBへ格納
    $res = upsert($loginUser, $table, $upData);
    if (isset($res['err'])) {
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }
    
    // ログテーブルに登録する
    setEntryLog($upData);
    
    $imgId = $res;
    $newPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/kantaki/' . $imgId . '/';
    if (!is_dir($newPath)) {
        mkdir($newPath, 2755);
    }
    rename($filename, $newPath . $lifeImage);
    $upData = array();
    $upData['unique_id'] = $imgId;
    $upData['life_image'] = '/upload/kantaki/' . $imgId . '/' . $lifeImage;
    $res = upsert($loginUser, $table, $upData);
    
    // ログテーブルに登録する
    setEntryLog($upData);
}
$response = $imgId . ' result:' . $res;
//echo json_encode($response);
//echo $response;

//$nextPage = '/report/kantaki/index.php?' . $imgId;
//header("Location:" . $nextPage);
//exit();

echo $res;
exit();
