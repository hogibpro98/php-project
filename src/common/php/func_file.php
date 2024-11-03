<?php

//=============================================================================
//ファイル操作関数群
//=============================================================================


//=============================================================================
//ファイル情報チェック関数
//=============================================================================

/*
 * 概要
 * クライアントから、POSTされたファイル情報を取得
 * アップロードされるファイルが適正かをチェックする
 *
 * 引数
 * ① アップロードファイル情報:スーパーグローバル変数$_FILES['ポスト時のname属性']
 * ② 画像ファイル判定フラグ  :trueの場合、画像以外のファイルをエラーにする
 * ③ 許可拡張子文字列        :許可拡張子を^区切りで繋げた文字列
 *                             (php5.5では配列を定数化出来ない為)
 * ④ ファイル名正規表現      :ファイル名をチェックする際の正規表現パターン
 * ⑤
 *
 * 戻り値
 * 成功時、ファイル情報格納配列
 * 失敗時、エラーメッセージ
 *
 */

function getUploadFile(
    $file,
    $imageMode = false,
    $extension = FILE_ALLOW_EXTENSION,
    $match = FILE_ALLOW_NAME,
    $fileSize = FILE_MAX_DATASIZE
) {
    /*--変数定義-----------------------------------------------------------*/

    // 戻り値初期化
    $res = array();
    // 許可拡張子配列
    $extensionAry = explode('^', $extension);
    // アップロード失敗時メッセージ配列
    $errMsg = array();
    $errMsg[0] = null;
    $errMsg[1] = 'サイズオーバーのファイルが含まれています。';
    $errMsg[2] = 'サイズオーバーのファイルが含まれています。';
    $errMsg[3] = 'ファイル情報が途中で途切れています。';
    $errMsg[4] = 'ファイルが存在しません。';
    $errMsg[5] = '一時保存用フォルダが存在しません。';
    $errMsg[6] = '書き込みに失敗しました。';
    $errMsg[7] = '処理が中断されました。';

    /*--$_FILES構造チェック-----------------------------------------------*/
    if (!isset($file['name'])
    ||  !isset($file['type'])
    ||  !isset($file['tmp_name'])
    ||  !isset($file['error'])
    ||  !isset($file['size'])
    ||  count($file) !== 5
    ) {
        $res['err'][] = 'ファイル情報の取得に失敗しました。';
        return $res;
    }

    /*--アップロードファイル内容チェック----------------------------------*/
    // ファイルパス配列取得
    $pathInfo = pathinfo($file['name']);
    // ファイル名判定
    if (!preg_match($match, $pathInfo['filename'])) {
        $res['err'][] = '不正なファイル名が含まれています。';
    }
    // ファイルサイズ判定
    if ($fileSize < $file['size']) {
        $res['err'][] = 'サイズオーバーのファイルが含まれています。';
    }
    // 拡張子判定(php.ini拡張不可版)
    $ext = strtolower($pathInfo['extension']);
    if (!in_array($ext, $extensionAry, true)) {
        $res['err'][] = '許可されていない拡張子のファイルが含まれています。';
    }
    //ファイルのMINETYPE/拡張子を取得(php.ini拡張版)
    //$finfo = new finfo(FILEINFO_MIME_TYPE);
    //$mineType = $finfo->file($file['tmp_name']);
    //if (!in_array($mineType,$extension)){
    //    $res['err'][] = '許可されていない拡張子のファイルが含まれています。';
    //}
    // 画像判定
    if ($imageMode) {
        if (!exif_imagetype($file['tmp_name'])) {
            $res['err'][] = '画像以外のファイルが含まれています。';
        }
    }
    // アップロード時エラー判定
    if (isset($errMsg[$file['error']])) {
        $res['err'][] = $errMsg[$file['error']];
    }
    // htmlによるアップロード判定
    if (!is_uploaded_file($file['tmp_name'])) {
        $res['err'][] = '不正なアップロードファイルが含まれています。';
    }

    /*--戻り値返還-------------------------------------------------*/
    if (isset($res['err'])) {
        // エラー発生時、エラーメッセージを返す
        return $res;
    } else {
        // 正常時、$_FILES['ポスト時のname属性']を返す
        return $file;
    }
}

//=============================================================================
//複数ファイル情報成型関数
//=============================================================================
/*
 * 概要
 * ファイルアップのinputタグをmultiにしている場合、
 * $_FILES配列の内容チェック、配列整形に用いる
 *
 * 引数
 * ①ファイル情報格納配列:$_FILES['ポスト時のname属性']
 * ②ファイル名重複フラグ:trueの場合、重複不可判定を行う
 * ③ファイル数上限      :1回でアップできるファイル数の上限
 * ④合計ファイルサイズ  :1回でアップできる合計ファイルサイズの上限
 *
 * 戻り値
 * 成功時
 * ファイル情報格納配列
 * $res[1ファイル目][ファイル名]
 *     [1ファイル目][ファイルサイズ]
 *     [1ファイル目][ファイルタイプ]
 *     …
 *     [2ファイル目][ファイルサイズ]
 *     [2ファイル目][ファイルタイプ]
 *     …
 */


function formatUploadFiles(
    $files,
    $overlap  = true,
    $count    = FILE_MAX_SELECT,
    $postSize = POST_MAX_DATASIZE
) {
    // 戻り値初期化
    $res = array();

    /*--ファイル情報チェック----------------------------------------*/

    //$_FILES 構造チェック
    if (!isset($files['name'])
    ||  !isset($files['type'])
    ||  !isset($files['size'])
    ||  !isset($files['tmp_name'])
    ||  !isset($files['error'])
    ||  count($files) !== 5
    ) {
        $res['err'][] = 'ファイル情報の取得に失敗しました。';
        return $res;
    }
    // ファイル数チェック
    if (count($files['tmp_name']) > $count) {
        $res['err'][] = '選択ファイル上限数をオーバーしています。';
    }
    // 合計サイズチェック
    if ($postSize < array_sum($files['size'])) {
        $res['err'][] = '選択されたファイルの合計値が1回の送信の上限値をオーバーしています。';
    }
    // ファイル名の重複チェック
    if ($overlap) {
        if (count($files['name']) !== count(array_unique($files['name']))) {
            $res['err'][] = '選択されたファイルのファイル名が重複しています。';
        }
    }
    // 違反発見時、エラーメッセージを返す
    if (isset($res['err'])) {
        return $res;
    }

    /*--戻り値整形--------------------------------------------------*/

    foreach ($files as $key => $ary) {
        foreach ($ary as $idx => $val) {
            //配列の次元を入れ替え
            $res[$idx][$key] = $val;
        }
    }
    return $res;
}

//=============================================================================
//文字コード変換関数
//=============================================================================

/*
 * 概要
 * サーバーの標準文字コードと,PGの文字コードを変換する
 *
 * $mode true PGの文字コードに変換して返す
 * $mode false サーバーの文字コードに変換して返す
 *
 */

function fileEncode(
    $path,
    $mode = false,
    $serverEncode = SERVER_ENCODE_TYPE,
    $defaultEncode = DEFAULT_ENCODE_TYPE
) {
    // 戻り値初期化
    $res = array();
    // 変換用文字コード配列定義
    $encAry = array();
    if ($mode) {
        $encAry = array($serverEncode,$defaultEncode);
    } else {
        $encAry = array($defaultEncode,$serverEncode);
    }

    // 変換後文字コード、変換前文字コード取得
    $toEncode = $encAry[1];
    $fromEncode = mb_detect_encoding($path, $encAry, true);
    if (!$fromEncode) {
        $res['err'][] = '文字コードの取得に失敗しました。';
        return $res;
    }

    // 文字コード変換
    if ($fromEncode !== $toEncode) {
        $path = mb_convert_encoding($path, $toEncode, $fromEncode);
    }

    return $path;
}


//=============================================================================
//テンポラリファイル移動関数(アップロードファイルの保存)
//=============================================================================

/*
 * サーバーに一時保存状態のファイルを、指定されたディレクトリへ移行
 * windowsサーバーの場合、ファイル名の文字コードをSJIS_winに変換する
 *
 * $copyMode = true ファイル名をコピーする
 * $copyMode = false 旧ファイルを上書きする
 *
 * 成功時、移行後のパス付きファイル名を返す
 * 失敗時、エラーメッセージを返す
 *
 * ※関数実行前にディレクトリを生成しておくこと
 */

function moveUploadFile(
    $file,
    $dir,
    $copyMode = true,
    $perms = FILE_DEFAULT_PERMISSION,
    $allowDir = FILE_UPLOAD_DIR
) {
    // 戻り値初期化
    $res = array();

    /*--保存前チェック------------------------------------------------*/

    // 文字コード変換(PGの文字コードに合わせる)
    $dir = fileEncode($dir, true);
    if (isset($dir['err'])) {
        $res['err'] = $dir['err'];
        return $res;
    }
    // ディレクトリ妥当性チェック
    if (strpos($dir, $allowDir) === false) {
        $res['err'][] = '保存先の指定が誤っています。';
        return $res;
    }
    // ファイルパス生成
    $fileName = $file['name'];
    $path = $dir . '/' . $fileName;
    // 文字コード変換(サーバーの文字コードに合わせる)
    $path = fileEncode($path);
    if (isset($path['err'])) {
        $res['err'] = $path['err'];
        return $res;
    }
    // ファイル名重複判定
    if ($copyMode) {
        $path = checkOverlapPath($path);
        if (isset($path['err'])) {
            $res['err'] = $path['err'];
            return $res;
        }
    }
    /*--ファイル移動処理---------------------------------------------*/

    // テンポラリファイル名取得
    $tempName = $file['tmp_name'];
    // 保存用のディレクトリにファイルを移動
    if (!@move_uploaded_file($tempName, $path)) {
        // 失敗時
        $res['err'][] = $fileName . 'の保存に失敗しました。';
    } else {
        // パーミッションの設定
        if (!chmod($path, $perms)) {
            $res['err'][] = 'パーミッションの設定に失敗しました。';
        }
    }

    /*--戻り値を返す--------------------------------------------------*/

    if (isset($res['err'])) {
        return $res;
    } else {
        return $path;
    }
}

//=============================================================================
//テンポラリファイル移動関数(アップロードファイルの保存)
//=============================================================================

/*
 * サーバーに一時保存状態のファイルを、指定されたディレクトリへ移行
 * windowsサーバーの場合、ファイル名の文字コードをSJIS_winに変換する
 *
 * $copyMode = true ファイル名をコピーする
 * $copyMode = false 旧ファイルを上書きする
 *
 * 成功時、移行後のパス付きファイル名を返す
 * 失敗時、エラーメッセージを返す
 *
 * ※関数実行前にディレクトリを生成しておくこと
 */

function moveUploadFile2(
    $file,
    $dir,
    $copyMode = true,
    $perms = FILE_DEFAULT_PERMISSION
) {
    // 戻り値初期化
    $res = array();

    /*--保存前チェック------------------------------------------------*/

    // 文字コード変換(PGの文字コードに合わせる)
    $dir = fileEncode($dir, true);
    if (isset($dir['err'])) {
        $res['err'] = $dir['err'];
        return $res;
    }
    // ファイルパス生成
    $fileName = $file['name'];
    $path = $dir . '/' . $fileName;
    // 文字コード変換(サーバーの文字コードに合わせる)
    $path = fileEncode($path);
    if (isset($path['err'])) {
        $res['err'] = $path['err'];
        return $res;
    }
    // ファイル名重複判定
    if ($copyMode) {
        $path = checkOverlapPath($path);
        if (isset($path['err'])) {
            $res['err'] = $path['err'];
            return $res;
        }
    }
    /*--ファイル移動処理---------------------------------------------*/

    // テンポラリファイル名取得
    $tempName = $file['tmp_name'];
    // 保存用のディレクトリにファイルを移動
    if (!@move_uploaded_file($tempName, $path)) {
        // 失敗時
        $res['err'][] = $fileName . 'の保存に失敗しました。';
    } else {
        // パーミッションの設定
        if (!chmod($path, $perms)) {
            $res['err'][] = 'パーミッションの設定に失敗しました。';
        }
    }

    /*--戻り値を返す--------------------------------------------------*/

    if (isset($res['err'])) {
        return $res;
    } else {
        return $path;
    }
}


//=============================================================================
//ファイル、フォルダ存在判定関数(重複チェック)
//=============================================================================

/*
 * 指定したパスが存在するか否か判定(ファイル、ディレクトリに用いる)
 * 重複が存在しない場合、パラメータのパスをそのまま返す
 *
 * copyMode true
 * 重複が存在する場合、_1、_2、、、、の形でファイル名を変更して返す
 *
 * false
 * 重複が存在する場合、そのままのパスを返す
 *
 */


function checkOverlapPath(
    $path,
    $copyMode = true,
    $retry = 1000
) {
    $res = array();

    //文字コード変換
    $path = fileEncode($path);
    if (isset($path['err'])) {
        $res['err'] = $path['err'];
        return $res;
    }
    //キャッシュを除去して最新の情報を反映させる
    clearstatcache();
    //重複を判定
    if (!file_exists($path)) {
        return $path;
    }
    // ファイル重複不可の場合、終了
    if (!$copyMode) {
        $res['err'][] = 'ファイル名、またはフォルダ名が重複しています。';
        return $res;
    }

    /*--ファイル名複製(_1,_2,_3…)---------------------------------*/

    $pathInfo = pathinfo($path);
    //拡張子無し
    if (empty($pathInfo['extension'])) {
        for ($i = 1; $i < $retry; $i++) {
            $pathName = sprintf(
                '%s/%s_%s',
                $pathInfo['dirname'],
                $pathInfo['filename'],
                $i
            );
            if (!file_exists($pathName)) {
                return $pathName;
            }
        }
        //拡張子有り
    } else {
        for ($i = 1; $i < $retry; $i++) {
            $pathName = sprintf(
                '%s/%s_%s.%s',
                $pathInfo['dirname'],
                $pathInfo['filename'],
                $i,
                $pathInfo['extension']
            );
            if (!file_exists($pathName)) {
                return $pathName;
            }
        }
    }

    $res['err'][] = '同じ名称のファイル、またはフォルダが' . $retry . '個以上存在します。';
    return $res;
}

//=============================================================================
//ファイル削除関数
//=============================================================================

/*
 * パラメータとして、削除対象ファイルのパス(絶対参照)を渡す。
 * 削除対象ファイルが存在しているか判定し、削除処理を実行
 * 削除対象ファイルが存在しない場合、削除処理に失敗した場合エラーメッセージを返す
 *
 */

function deleteFile(
    $path,
    $allowDir = FILE_UPLOAD_DIR
) {

    $res = array();

    //ファイル、フォルダ文字コード変換(サーバーの文字コードに合わせる)
    $path = fileEncode($path);
    if (isset($path['err'])) {
        $res['err'] = $path['err'];
        return $res;
    }
    $allowDir = fileEncode($allowDir);
    if (isset($allowDir['err'])) {
        $res['err'] = $allowDir['err'];
        return $res;
    }

    //削除許可ディレクトリ判定
    if (strpos($path, $allowDir) === false) {
        $res['err'][] = '指定されたファイルは、削除対象外のフォルダに属しています。';
        return $res;
    }

    /*削除処理実行*/

    //キャッシュを除去して最新の判定結果を反映させる
    clearstatcache();

    //削除可否チェック
    if (!is_writable($path)) {
        //削除可能ファイルがない場合失敗
        $res['err'][] = '削除可能ファイルが存在しません。';
        return $res;
    }

    //削除処理
    if (!unlink($path)) {
        //削除処理失敗時、エラーメッセージを返す
        $res['err'][] = '削除処理に失敗しました。';
        return $res;

    } else {
        return true;
    }
}

//=============================================================================
//新規フォルダ作成関数
//=============================================================================

/*
 *
 * copyMode = true  既にフォルダが存在する場合、フォルダ名を変更して新規作成
 * copyMode = false 既にフォルダが存在する場合、フォルダを作成しない
 *
 * 戻り値
 * 成功時、作成したフォルダ名(パス)を返す
 * 失敗時、エラーメッセージを返す
 *
 */

function createDirectory(
    $dir,
    $copyMode = false,
    $retry = 1000,
    $perms = FILE_DEFAULT_PERMISSION
) {
    $res = array();

    //文字コード変換
    $dir = fileEncode($dir);
    if (isset($dir['err'])) {
        $res['err'] = $dir['err'];
        return $res;
    }

    /*ディレクトリ存在判定*/

    //キャッシュを除去して最新の判定結果を反映させる
    clearstatcache();

    if (is_dir($dir)) {
        if ($copyMode) {
            for ($i = 1; $i < $retry; $i++) {
                $temp = $dir . '_' . $i;

                if (is_dir($temp)) {
                    $dir = $temp;
                    break;
                }
            }

            if ($dir !== $temp) {
                $res['err'][] = '同じ名称のフォルダが' . $retry . '個以上存在します。';
                return $res;
            }
        } else {
            return $dir;
        }
    }

    /*ディレクトリ作成*/

    //失敗時
    if (!mkdir($dir, $perms, $dir)) {
        $res['err'][] = 'フォルダの作成に失敗しました。';
        return $res;
    }

    //パーミッションの設定
    if (!chmod($dir, $perms)) {

        $res['err'][] = 'パーミッションの設定に失敗しました。';
        return $res;
    }

    return $dir;
}

//=============================================================================
//ファイルコピー関数
//=============================================================================

/*
 * $sourceには、ファイル名まで込みのパスを指定
 * $destには、コピー先のディレクトリまでを指定
 *
 * $copyModeがtrueの場合、コピー先に同じ名前のファイルが存在する場合、
 * _1,_2の形式でファイル名をコピーして追加する
 *
 * $copyModeがfalseの場合、コピー処理を行わずに終了する
 *
 * 成功時、戻り値としてコピー後のファイルのパスを返す
 * 失敗時、エラーメッセージを返す
 *
 */

function copyFile(
    $source,
    $dest,
    $copyMode = true,
    $retry = 1000
) {
    $res = array();

    //キャッシュを除去して最新の判定結果を反映させる
    clearstatcache();

    /*コピー元ファイル存在チェック*/
    //文字コード変換
    $source = fileEncode($source);
    if (isset($source['err'])) {
        $res['err'] = $source['err'];
        return $res;
    }
    //存在判定
    if (!is_file($source)) {
        $res['err'][] = 'コピー元ファイルが存在しません。';
        return $res;
    }
    /*コピー先ディレクトリ存在チェック(なければ作成)*/
    $dest = createDirectory($dest);
    if (isset($dest['err'])) {
        $res['err'] = $dest['err'];
        return $res;
    }

    /*コピー先パス生成*/
    $fileName = pathinfo($source, PATHINFO_BASENAME);
    $dest .= '/' . $fileName;
    if ($copyMode) {
        $dest = checkOverlapPath($dest);
        if (isset($dest['err'])) {
            $res['err'] = $dest['err'];
            return $res;
        }
    }

    /*コピー処理*/
    if (!@copy($source, $dest)) {
        $res['err'][] = 'ファイルのコピー処理に失敗しました。';
        return $res;
    } else {
        return $dest;
    }
}


//=============================================================================
//フォルダ内ファイル名取得関数
//=============================================================================

/*
 * 指定したフォルダ内のファイル名称を取得する
 *
 * getMode falseの場合、指定したフォルダの直下のファイル情報のみ取得
 *         trueの場合、子、孫以下のフォルダの情報まで取得可能
 *
 * returnMode falseの場合、サーバーから取得したファイル名をそのままの文字コードで返す
 *            trueの場合、PGの文字コードに変換して返す
 *
 * extensionAryに拡張子を指定した場合、指定した拡張子のみを対象とする
 *
 *
 */

function getFileList(
    $dir,
    $getMode = false,
    $returnMode = false,
    $extensionAry = array()
) {
    $res = array();

    //キャッシュを除去して最新の情報を反映させる
    clearstatcache();

    //文字コード変換
    $dir = fileEncode($dir);
    if (isset($dir['err'])) {
        $res['err'] = $dir['err'];
        return $res;
    }

    //フォルダの存在判定,読み込み判定
    if (!is_readable($dir)) {
        return $res;
    }

    //フォルダのオブジェクトを生成
    $dirItr = new DirectoryIterator($dir);

    //ファイル情報情報一時取得
    $tempFiles = array();
    foreach ($dirItr as $child) {

        //  ./、../(自フォルダ、親フォルダ)をスキップ、、、無限ループ防止
        if ($child->isDot()) {
            continue;
        }

        //読み込み可能ファイルのみに対して処理
        if ($child->isFile()
        &&  $child->isReadable()
        ) {
            //パス付ファイル名を取得
            $tempFiles[] = $child->getPathname();
        }

        //子、孫フォルダを発見した場合掘り進む
        if ($getMode === true
        &&  !$child->isDot()
        &&  $child->isDir()
        &&  $child->isReadable()
        ) {
            getFileListChild($child->getPathname(), $tempFiles);
        }
    }

    //拡張子絞り込み
    if (!empty($extensionAry)) {
        foreach ($tempFiles as $pathName) {
            $ext = pathinfo($pathName, PATHINFO_EXTENSION);
            if (in_array($ext, $extensionAry, true)) {
                $res[] = $pathName;
            }
        }
    } else {
        $res = $tempFiles;
    }

    //戻り値変換
    if ($returnMode) {
        foreach ($res as $index => $pathName) {
            $temp = fileEncode($pathName, true);
            if (isset($temp['err'])) {
                return $temp;
            } else {
                $res[$index] = $temp;
            }
        }
    }

    return $res;
}


/*----子フォルダ、孫フォルダ探索用再帰関数-----------------------------------*/

function getFileListChild($dir, &$res)
{

    $dir = new DirectoryIterator($dir);

    foreach ($dir as $child) {

        //読み込み可能ファイルのみに対して処理
        if ($child->isFile()
        &&  $child->isReadable()
        ) {
            //パス付ファイル名を取得
            $res[] = $child->getPathname();
        }

        //子、孫フォルダを発見した場合掘り進む
        if (!$child->isDot()
        &&  $child->isDir()
        &&  $child->isReadable()
        ) {
            //再帰処理
            getFileListChild($child->getPathname(), $res);
        }
    }
}

//=============================================================================
//ディレクトリ削除関数
//=============================================================================

/*
 * 指定したディレクトリ以下のディレクトリ、ファイルを一括削除する
 *
 */

function deleteDir(
    $dir,
    $safeMode = true,
    $allowDir = FILE_UPLOAD_DIR
) {
    $res = array();

    //削除許可ディレクトリ判定
    if ($safeMode
    &&  strpos($dir, $allowDir) === false) {
        $res['err'][] = '対象のディレクトリは削除できません。';
        return $res;
    }

    //文字コード変換
    $dir = fileEncode($dir);
    if (isset($dir['err'])) {
        $res['err'] = $dir['err'];
        return $res;
    }

    //キャッシュを除去して最新の情報を反映させる
    clearstatcache();

    //フォルダの存在判定
    if (!is_dir($dir)) {
        return true;
        //        $res['err'][] = '指定されたディレクトリは存在しません。';
        //        return $res;
    }

    //フォルダのオブジェクトを生成
    $dirItr = new DirectoryIterator($dir);

    //ファイル情報情報一時取得
    $tempFiles = array();
    foreach ($dirItr as $child) {

        //  ./、../(自フォルダ、親フォルダ)をスキップ、、、無限ループ防止
        if ($child->isDot()) {
            continue;
        }

        //ファイル発見時削除
        if ($child->isFile()) {
            if (!@unlink($child->getPathname())) {
                $res['err'][] = '削除できないファイルが含まれています。';
            }
        }

        //子、孫フォルダを発見した場合掘り進む
        if ($child->isDir()) {
            deleteDirChild($child->getPathname(), $res);
        }
    }

    //ディレクトリ削除
    if (!@rmdir($dir)) {
        $res['err'][] = '指定したディレクトリの削除に失敗しました。';
    }

    //戻り値変換
    if (isset($res['err'])) {
        $res['err'] = array_unique($res['err']);
        return $res;

    } else {
        return true;
    }
}


/*----子フォルダ、孫フォルダ探索用再帰関数-----------------------------------*/

function deleteDirChild($dir, &$res)
{

    $dirItr = new DirectoryIterator($dir);

    foreach ($dirItr as $child) {

        //  ./、../(自フォルダ、親フォルダ)をスキップ、、、無限ループ防止
        if ($child->isDot()) {
            continue;
        }

        //ファイルに対して処理
        if ($child->isFile()) {
            if (!@unlink($child->getPathname())) {
                $res['err'][] = '削除できないファイルが含まれています。';
            }
        }

        //子、孫フォルダを発見した場合掘り進む
        if ($child->isDir()) {
            //再帰処理
            deleteDirChild($child->getPathname(), $res);
        }
    }

    //ディレクトリ削除
    if (!@rmdir($dir)) {
        $res['err'][] = '削除できないディレクトリが含まれています。';
    }
}


//=============================================================================
// ファイル情報更新関数
//=============================================================================

/*
 * 概要
 * カラム毎に、新ファイルの保管、DBへの登録、旧ファイルの削除を行う
 * 失敗時エラーメッセージを返す
 *
 * 引数
 * ①ファイル情報格納配列
 * ②保存先ディレクトリ ※ dir1/dir2/dir3 末尾に'/'は付けない
 * ③処理タイプ(編集:edit、コピー:copy)
 *   edit→クリアが押されていれば、旧レコードと紐付けされたファイルを削除
 *   copy→クリアが押されていなければ、コピー元レコードと紐付けされたファイルを複製

 * ④保存対象テーブル
 * ⑤保存対象レコードのID
 * ⑥保存対象カラム名(パスの格納先)
 * ⑦削除対象カラム名
 * ⑧更新前レコード情報
 *
 *
 */


function fileDataUpdate($user, $files, $dir, $table, $id, $columns, $oldData, $type = 'edit')
{

    /*変数初期化*/
    // 戻り値
    $res = array();
    // DB登録用配列
    $data = array();
    $data['unique_id'] = $id;
    // エラーメッセージ一時格納用配列
    $err = array();

    /*--カラム毎、登録処理or登録解除処理振り分け-----------------------------*/
    $addCols  = array();
    $delCols  = array();
    $copyCols = array();

    foreach ($columns as $col => $clear) {
        // 画像追加
        // mod 1レコードずつの更新とするように修正
        //       if (!empty($files[$col]['name'])){
        //           $addCols[$col] = $files[$col];
        if (!empty($files['name'])) {
            $addCols[$col] = $files;

            // 画像削除(クリアボタン押下＆編集時＆更新前カラムに保存先が存在)
        } elseif ($clear && $type === 'edit' && !empty($oldData[$col])) {
            $delCols[$col] = SV_ROOT . $oldData[$col];
            $data[$col] = null;

            // 画像コピー(クリアボタン非押下＆コピー=新規時＆更新前カラムに保存先が存在)
        } elseif (!$clear && $type === 'copy' && !empty($oldData[$col])) {
            $copyCols[$col] = SV_ROOT . $oldData[$col];
        }
    }

    /*--アップロードファイル保存処理------------------------------------------*/
    $dir = SV_ROOT . $dir;
    foreach ($addCols as $col => $file) {
        $oldFile = '';
        // 編集時、旧紐付けファイル削除
        if ($type === 'edit' && !empty($oldData[$col])) {
            $oldFile = SV_ROOT . $oldData[$col];
        }
        $result = fileUpload($file, $dir, $id, $oldFile);

        /*-- 画像圧縮処理(ここから) ---------------------------------*/
        $temp = pathinfo($result);
        $resizefilePath1 = $temp['dirname'] . '/' . $temp['filename'] . '_resize500_.' . $temp['extension'];
        $resizefilePath2 = $temp['dirname'] . '/' . $temp['filename'] . '_resize800_.' . $temp['extension'];

        // コピー後圧縮処理 (Windowsの場合)
        if (strpos(PHP_OS, 'WIN') !== false) {

            // コピー後圧縮処理 (Linuxの場合)
        } else {
            $original_image = new Imagick($result);
            $original_image->resizeImage(500, null, Imagick::FILTER_LANCZOS, 1);
            $original_image->writeImage($resizefilePath1);

            $original_image = new Imagick($result);
            $original_image->resizeImage(800, null, Imagick::FILTER_LANCZOS, 1);
            $original_image->writeImage($resizefilePath2);
        }
        /*-- 画像圧縮処理(ここまで) ---------------------------------*/

        if (isset($result['err'])) {
            $err[$col] = $result['err'];
        } else {
            $result = substr($result, strlen(SV_ROOT));
            $data[$col] = $result;
        }
    }

    /*--ファイルコピー処理----------------------------------------------------*/
    // コピー先ディレクトリ定義
    $dest = $dir . '/' . $id;
    foreach ($copyCols as $col => $source) {
        $result = copyFile($source, $dest);
        if (isset($result['err'])) {
            $err[$col] = $result['err'];
        } else {
            $result = substr($result, strlen(SV_ROOT));
            $data[$col] = $result;
        }
    }

    /*--DBデータ登録処理------------------------------------------------------*/
    if (!upsert($user, $table, $data)) {
        $err['all'][] = 'アップロードファイルの更新に失敗しました。';
    }

    /*--不要ファイル削除処理--------------------------------------------------*/
    foreach ($delCols as $col => $path) {
        $path = fileEncode($path);
        if (is_file($path)) {
            $result = deleteFile($path);
            if (isset($result['err'])) {
                $err[$col] = $result['err'];
                $err[$col][] = '旧ファイルの削除に失敗しました。';
            }
        }

        /*-- 画像圧縮処理(ここから) ---------------------------------*/
        $temp = pathinfo($path);
        $resizefilePath1 = $temp['dirname'] . '/' . $temp['filename'] . '_resize500_.' . $temp['extension'];
        $resizefilePath2 = $temp['dirname'] . '/' . $temp['filename'] . '_resize800_.' . $temp['extension'];
        if (is_file($resizefilePath1)) {
            $result = deleteFile($resizefilePath1);
            if (isset($result['err'])) {
                $err[$col] = $result['err'];
                $err[$col][] = '旧ファイルの削除に失敗しました。';
            }
        }
        if (is_file($resizefilePath2)) {
            $result = deleteFile($resizefilePath2);
            if (isset($result['err'])) {
                $err[$col] = $result['err'];
                $err[$col][] = '旧ファイルの削除に失敗しました。';
            }
        }
        /*-- 画像圧縮処理(ここまで) ---------------------------------*/
    }

    /*--戻り値作成------------------------------------------------------------*/
    foreach ($err as $col => $msgAry) {
        //エラーメッセージの重複を取り除く
        $msgAry = array_unique($msgAry);
        foreach ($msgAry as $msg) {
            $res['err'][] = $msg;
        }
        if ($col !== 'all') {
            $res['err'][] = $col . 'の更新に失敗しました。';
        }
    }

    if (isset($res['err'])) {
        return $res;
    } else {
        return true;
    }
}

//=========================================================
// ファイル名作成関数
//=========================================================
/*
 * 概要
 *  接頭辞に、timestampを36進数(0-9,a-z)に変換した文字列を足して返す
 *
 * 引数
 *  ①ファイル名接頭辞(string)
 *
 * 戻り値
 *  文字列(string)
 */
function createFileName($str = '')
{
    usleep(100);
    $time = str_replace(array('.',' '), '', (string)microtime(false));
    return $str . base_convert($time, 10, 36);
}

//=============================================================================
// アップロードファイル登録関数
//=============================================================================

/*
 * 概要
 * アップロードされた一時テンポラリファイルを指定ディレクトリに移動する
 * 紐付けすべきDBのレコードにすでにデータが存在する場合、対象ファイルを削除する
 *
 * 引数
 * ①ファイル情報格納配列
 * ②保存対象ディレクトリ
 * ③DB保存対象レコードのID
 * ④旧ファイルデータ(削除対象)
 *
 * 戻り値
 * 成功時、移動後のファイルパス
 * 失敗時、エラーメッセージ
 */

function fileUpload($file, $dir, $id, $oldFile)
{

    $res = array();
    /*旧画像削除*/
    $oldFile = fileEncode($oldFile);
    if (is_file($oldFile)) {
        $result = deleteFile($oldFile);
        if (isset($result['err'])) {
            $res['err'] = $result['err'];
            $res['err'][] = '旧ファイルの削除に失敗しました。';
            return $res;
        }
    }

    /*一時保存ファイル移動処理*/

    // ファイル情報取得
    $file = getUploadFile($file);
    if (isset($file['err'])) {
        $res['err'] = $file['err'];
        $res['err'][] = 'ファイルアップロードに失敗しました。';
        return $res;
    }

    // ファイル保管ディレクトリ作成(ディレクトリが存在しない場合のみ)
    $dir .= '/' . $id;
    $dir = createDirectory($dir);
    if (isset($dir['err'])) {
        $res['err'] = $dir['err'];
        $res['err'][] = 'ファイルアップロードに失敗しました。';
        return $res;
    }

    // ファイル移動(保存)
    $path = moveUploadFile($file, $dir);
    if (isset($path['err'])) {
        $res['err'] = $path['err'];
        $res['err'][] = 'ファイルアップロードに失敗しました。';
        return $res;
    }

    //文字コード変換(サーバーエンコードからPGのエンコードへ変換)
    $path = fileEncode($path, true);
    if (isset($path['err'])) {
        $res['err'] = $path['err'];
        $res['err'][] = 'ファイルアップロードに失敗しました。';
        return $res;
    }

    return $path;
}

// ダウンロード関数
function download($dir, $fileName)
{
    // ex. dir: SV_ROOT/tmp/download/
    // ex. $fileName: test.pdf
    $filePath = $dir . $fileName;

    // ファイルを読み込みできない場合はエラー
    if (!is_readable($filePath)) {
        die($filePath);
    }

    // 出力処理
    header('Content-Disposition: attachment; filename=' . $fileName);
    header('X-Content-Type-Options: nosniff');
    header("Content-Type: application/octet-stream");
    header('Content-Disposition: attachment; filename=' . $fileName);
    header("Content-Transfer-Encoding: binary");
    header('Content-Length: ' . filesize($filePath));

    // 出力バッファリングをすべて無効化する
    while (ob_get_level()) {
        ob_end_clean();
    }

    // 出力
    readfile($filePath);

    exit;
}

// デバッグ用 入力文字列→16進数に変換
function mb_ordHex($str)
{
    $res = null;
    $ary = str_sprit();
    foreach ($ary as $chr) {
        $res .= dechex(ord($chr)) . ' ';
    }
    return $res;
}

//=============================================================
// リサイズ画像出力関数(リサイズ処理phpのパス,クエリにして返す)
//=============================================================
function imgResize($image, $width, $height)
{
    return sprintf(ORIGIN_URL . "/common/php/com_resize.php?image=%s&width=%s&height=%s", $image, $width, $height);
}
//=============================================================================
// アップロードファイル登録関数
//=============================================================================

/*
 * 概要
 * アップロードされた一時テンポラリファイルを指定ディレクトリに移動する
 * 紐付けすべきDBのレコードにすでにデータが存在する場合、対象ファイルを削除する
 *
 * 引数
 * ①ファイル情報格納配列
 * ②保存対象ディレクトリ
 * ③DB保存対象レコードのID
 *
 * 戻り値
 * 成功時、移動後のファイルパス
 * 失敗時、エラーメッセージ
 */

function fileUpload2($file, $dir, $oldFile)
{

    $res = array();
    /*旧画像削除*/
    $oldFile = fileEncode($oldFile);
    if (is_file($oldFile)) {
        $result = deleteFile($oldFile);
        if (isset($result['err'])) {
            $res['err'] = $result['err'];
            $res['err'][] = '旧ファイルの削除に失敗しました。';
            return $res;
        }
    }

    /*一時保存ファイル移動処理*/
    // ファイル情報取得
    $file = getUploadFile($file);
    if (isset($file['err'])) {
        $res['err'] = $file['err'];
        $res['err'][] = 'ファイルアップロードに失敗しました。';
        return $res;
    }

    // ファイル移動(保存)
    $path = moveUploadFile2($file, $dir);
    if (isset($path['err'])) {
        $res['err'] = $path['err'];
        $res['err'][] = 'ファイルアップロードに失敗しました。';
        return $res;
    }

    /*-- 画像圧縮処理(ここから) ---------------------------------*/
    $temp = pathinfo($oldFile);
    $resizefilePath1 = $temp['dirname'] . '/' . $temp['filename'] . '_resize500_.' . $temp['extension'];
    $resizefilePath2 = $temp['dirname'] . '/' . $temp['filename'] . '_resize800_.' . $temp['extension'];

    // コピー後圧縮処理 (Windowsの場合)
    if (strpos(PHP_OS, 'WIN') !== false) {

        // コピー後圧縮処理 (Linuxの場合)
    } else {
        $original_image = new Imagick($oldFile);
        $original_image->resizeImage(500, null, Imagick::FILTER_LANCZOS, 1);
        $original_image->writeImage($resizefilePath1);

        $original_image = new Imagick($oldFile);
        $original_image->resizeImage(800, null, Imagick::FILTER_LANCZOS, 1);
        $original_image->writeImage($resizefilePath2);
    }
    /*-- 画像圧縮処理(ここまで) ---------------------------------*/

    //文字コード変換(サーバーエンコードからPGのエンコードへ変換)
    $path = fileEncode($path, true);
    if (isset($path['err'])) {
        $res['err'] = $path['err'];
        $res['err'][] = 'ファイルアップロードに失敗しました。';
        return $res;
    }

    return $path;
}

//=============================================================
// コピーディレクトリ関数 (ディレクトリ単位のコピー)
//=============================================================
function copyDir($oldDir, $newDir)
{

    // 新旧ディレクトリ
    $oldDir = rtrim($oldDir, '/') . '/';
    $newDir = rtrim($newDir, '/') . '/';

    // コピー元ディレクトリが存在すればコピーを行う
    if (is_dir($oldDir)) {

        // コピー先ディレクトリが存在しなければ作成する
        if (!is_dir($newDir)) {
            mkdir($newDir);
            chmod($newDir, 0755);
        }

        // ディレクトリを開く
        if ($handle = opendir($oldDir)) {

            // ディレクトリ内のファイルを取得する
            while (false !== ($file = readdir($handle))) {
                if ($file === '.' || $file === '..' || mb_strpos($file, 'Thumbs.db') !== false) {
                    continue;
                }
                // 下の階層にディレクトリが存在する場合は再帰処理を行う
                if (is_dir($oldDir . $file)) {
                    copyDir($oldDir . $file, $newDir . $file);
                } else {
                    copy($oldDir . $file, $newDir . $file);
                }
            }
            closedir($handle);
        }
    }
}
