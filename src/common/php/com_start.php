<?php

//=======================================================================
//   共通処理
//=======================================================================


/*-- 共通処理呼び出し ---------------------------------------------------------*/

/* セッション開始 */
if (session_id() == '') {
    session_start();
}

/* サーバ情報取得 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_server.php');

/* 定数情報取得 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_ini.php');

/* 日時情報取得 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_calendar.php');

/*-- 関数群呼び出し -----------------------------------------------------------*/

/* 外部情報変換 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_encode.php');
/* 配列操作 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_array.php');
/* ＤＢ操作関数 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_db.php');
/* ファイル操作関数 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_file.php');
// 文字数省略関数
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_trim.php');

/* 汎用データ取得関数 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_get.php');
/* 汎用データ登録関数 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_set.php');

/* ページャー関数 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_pager.php');
/* ログイン判定 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_user.php');
/* メール関数 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_mail.php');
/* PDF帳票出力 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_pdf.php');
/* EXCEL帳票出力 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_excel.php');
/* CSV出力処理 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_csv.php');
/* 外部API通信用関数 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/func_curl.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_service_type.php');