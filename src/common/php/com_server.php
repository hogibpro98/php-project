<?php

//=======================================================================
//   サーバ変数取得
//=======================================================================

/* 各サーバ変数の取得 */

// 格納変数初期化
$server = array();
// URL情報
$server['requestUri'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
// スクリプト情報
$server['scriptName'] = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : null;
// クエリ情報
$server['query'] = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;
// IPアドレス＋ポート番号
$server['httpHost'] = isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : null;
// 実行ファイル
$server['phpSelf'] = isset($_SERVER['PHP_SELF']) ? basename($_SERVER['PHP_SELF']) : null;
// リファラ情報 (アクセス元）
$server['refere'] = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null;
// クライアント (名称,IPアドレス）
$server['remoteAddr'] = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
$server['remoteHost'] = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
// ↓※ 速度が低下するためホスト名は取得しない
//$server['remoteHost'] = isset($_SERVER["REMOTE_ADDR"])? gethostbyaddr($_SERVER["REMOTE_ADDR"]): null;
// ブラウザ情報
$server['userAgent'] = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : null;
// ドキュメントルート
$server['documentRoot'] = isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] : null;
// サーバーアドレス
$server['serverName'] = isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : null;
