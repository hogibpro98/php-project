<?php

// =======================================================================
// 汎用データベース操作関数群
// =======================================================================
/*   ① connect      [データベース接続関数] *func_db内のみ使用
 *   ② select       [テーブル情報取得関数]
 *   ③ multiSelect  [テーブル情報複数取得関数]
 *   ④ insert       [新規レコード登録関数] *func_db内のみ使用
 *   ⑤ update       [同一レコード更新関数] *func_db内のみ使用
 *   ⑥ delete       [レコード物理削除関数]
 *   ⑦ getNewId     [最新ID取得関数]       *func_db内のみ使用
 *   ⑧ setNewId     [発番済ID更新関数]     *func_db内のみ使用
 *   ⑨ upsert       [追加/更新関数]
 *   ⑩ multiUpsert  [追加/更新関数 複数同時更新用]
 *   ⑪ multiInsert  [追加関数 複数同時更新用] *移行ツールのみ使用
 * -----------------------------------------------------------------------
 */

// =======================================================================
// データベース接続関数(connect)
// =======================================================================
/*
 *   [使用方法]
 *      $res = connect()
 *
 *   [引数]
 *      なし
 *
 *   [戻り値]
 *      PDOオブジェクト
 *
 * -----------------------------------------------------------------------
 */
function connect()
{

    try {
        // DB接続
        $pdo = new PDO(DB_INFO, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;

    } catch (PDOException $e) {
        print $e->getMessage();
    }
}
// =======================================================================
// テーブル情報取得関数(select)
// =======================================================================
/*
 * 　[使用方法]
 *      $res = select(①,②,③,④,⑤])
 *
 *   [引数]
 *      ① 対象テーブル
 *      ② 対象カラム名
 *      ③ 検索条件
 *      ④ 取得データ並び順
 *      ⑤ 最大取得件数
 *
 *   [戻り値]
 *       正常時-取得したデータの2次元配列
 *       失敗時-空配列
 * -----------------------------------------------------------------------
 */
function select(
    $table,
    $target = '*',
    $condition = array(),
    $orderBy = null,
    $limit = null
) {

    /*-- 初期処理 ------------------------------------*/

    // 戻り値初期化
    $res = array();

    // パラメータチェック
    if (!is_string($table)) {
        return $res;
    }
    if (!is_string($target)) {
        return $res;
    }
    if (!is_array($condition)) {
        return $res;
    }
    if (!is_NULL($orderBy) && !is_string($orderBy)) {
        return $res;
    }
    if (!is_NULL($limit) && !is_int($limit)) {
        return $res;
    }

    /*-- SQL文生成 -------------------------------------*/

    //バインド対象制御用変数定義
    $i = 0;

    foreach ($condition as $key => $val) {

        // 配列のデータなしは空とする
        if (empty($val) && is_array($val)) {
            $val = "";
        }

        // ユニーク処理
        if ($key === 'UNIQUE') {
            $where[] = $val;

            // 複数条件
        } elseif (is_array($val)) {
            $dat = null;
            foreach ($val as $valX) {
                if (strpos($key, 'LIKE') !== false) {
                    $dat = !empty($dat)
                        ? $dat . " OR " . $key . " '%" . $valX . "%'"
                        : $key . " '%" . $valX . "%'";
                } elseif (is_int($key)) {
                    $dat = !empty($dat)
                        ? $dat . " OR " . $key . " '" . $valX . "'"
                        : $key . " '" . $valX . "'";
                } else {
                    $dat = !empty($dat)
                        ? $dat . " OR " . $key . " = '" . $valX . "'"
                        : $key . " = '" . $valX . "'";
                }
            }
            $where[] = '(' . $dat . ')';
        } else {
            // 先頭文字列のみbind対象とする
            $keyAry = explode(' ', $key);
            // 半角スペースの有無により分岐
            // "カラム名 = :カラム名+制御数値" の文字列に変換
            if (strpos($key, ' ')) {
                $where[] = $key . " :" . $keyAry[0] . $i;
            } else {
                $where[] = $key . " = :" . $keyAry[0] . $i;
            }
        }
        $i++;
    }
    $sql = sprintf("SELECT %s FROM %s", $target, $table);
    if (!empty($where)) {
        $sql .= sprintf(" WHERE %s", implode(" AND ", $where));
    }
    if (!empty($orderBy)) {
        $sql .= sprintf(" ORDER BY %s", $orderBy);
    }
    if (!empty($limit)) {
        $sql .= sprintf(" LIMIT %s", $limit);
    }

    /*--DB操作-----------------------------------------*/
    $pdo = connect();
    $stmt = $pdo->prepare($sql);
    // バインド処理
    $i = 0;
    foreach ($condition as $key => $val) {

        // 配列のデータなしは空とする
        if (empty($val) && is_array($val)) {
            $val = "";
        }
        // 複数条件
        if ($key === 'UNIQUE' || is_array($val)) {
            // bindしない
        } else {
            // 真偽値変換
            if (is_bool($val)) {
                $val = false ? 'FALSE' : 'TRUE';
            }
            // 先頭文字列のみbind対象とする
            $keyAry = explode(' ', $key);
            // LIKE演算子有無により分岐
            if (strpos($key, 'LIKE') !== false) {
                $stmt->bindValue(':' . $keyAry[0] . $i, '%' . $val . '%');
            } else {
                $stmt->bindValue(':' . $keyAry[0] . $i, $val);
            }
        }
        $i++;
    }
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;

    return $res;
}
// =======================================================================
// テーブル情報取得関数(select)
// =======================================================================
/*
 * 　[使用方法]
 *      $res = select(①,②,③,④,⑤])
 *
 *   [引数]
 *      ① 対象テーブル（メイン）
 *      ② 結合するテーブル（配列）
 *      ③ 対象カラム名
 *      ④ WHERE句に指定する検索条件
 *      ⑤ JOIN ON句に指定する検索条件　※メインテーブルとの結合条件とする
 *      ⑥ ソート条件
 *      ⑦ 最大取得件数
 *      ⑧ 開始位置
 *
 *   [戻り値]
 *       正常時-取得したデータの2次元配列
 *       失敗時-空配列
 * -----------------------------------------------------------------------
 */
function multiSelect(
    $table,
    $jointables,
    $target = '*',
    $condition = array(),
    $joinconditions = array(),
    $orderBy = null,
    $limit = null,
    $offset = null
) {

    /*-- 初期処理 ------------------------------------*/

    // 戻り値初期化
    $res = array();

    // パラメータチェック
    if (!is_array($jointables)) {
        return $res;
    }
    if (!is_string($target)) {
        return $res;
    }
    if (!is_array($condition)) {
        return $res;
    }
    if (!is_array($joinconditions)) {
        return $res;
    }
    if (!is_NULL($orderBy) && !is_string($orderBy)) {
        return $res;
    }
    if (!is_NULL($limit) && !is_int($limit)) {
        return $res;
    }

    /*-- SQL文生成 -------------------------------------*/

    //バインド対象制御用変数定義
    $i = 0;

    foreach ($condition as $key => $val) {

        // ユニーク処理
        if ($key === 'UNIQUE') {
            $where[] = $val;

            // 複数条件
        } elseif (is_array($val)) {
            $dat = null;
            foreach ($val as $valX) {
                if (strpos($key, 'LIKE') !== false) {
                    $dat = !empty($dat)
                        ? $dat . " OR " . $key . " '%" . $valX . "%'"
                        : $key . " '%" . $valX . "%'";
                } elseif (is_int($key)) {
                    $dat = !empty($dat)
                        ? $dat . " OR " . $key . " '" . $valX . "'"
                        : $key . " '" . $valX . "'";
                } else {
                    $dat = !empty($dat)
                        ? $dat . " OR " . $key . " = '" . $valX . "'"
                        : $key . " = '" . $valX . "'";
                }
            }
            $where[] = '(' . $dat . ')';
        } else {
            // 半角スペースの有無により分岐
            // "カラム名 = :カラム名+制御数値" の文字列に変換
            if (strpos($key, 'IS NULL')) {
                $where[] = $key;
            } elseif (strpos($key, 'LIKE')) {
                $where[] = $key . " '%" . $val . "%'";
            } elseif (strpos($key, ' ')) {
                $where[] = $key . " '" . $val . "'";
            } else {
                $where[] = $key . " = '" . $val . "'";
            }
        }
        $i++;
    }


    $sql = sprintf("SELECT %s FROM %s", $target, $table);
    foreach ($jointables as $jointable) {
        $sql .= ' LEFT JOIN ' . $jointable;

        $joincondition = $joinconditions[$jointable];

        $joinon = array();

        foreach ($joincondition as $key => $val) {

            // ユニーク処理
            if ($key === 'UNIQUE') {
                $joinon[] = $val;

                // 複数条件
            } elseif (is_array($val)) {
                $dat = null;
                foreach ($val as $valX) {
                    if (strpos($key, 'LIKE') !== false) {
                        $dat = !empty($dat)
                            ? $dat . " OR " . $key . " '%" . $valX . "%'"
                            : $key . " '%" . $valX . "%'";
                    } elseif (is_int($key)) {
                        $dat = !empty($dat)
                            ? $dat . " OR " . $key . " '" . $valX . "'"
                            : $key . " '" . $valX . "'";
                    } else {
                        $dat = !empty($dat)
                            ? $dat . " OR " . $key . " = '" . $valX . "'"
                            : $key . " = '" . valX . "'";
                    }
                }
                $joinon[] = '(' . $dat . ')';
            } else {
                // 半角スペースの有無により分岐
                // "カラム名 = :カラム名+制御数値" の文字列に変換
                if (strpos($key, 'IS NULL')) {
                    $where[] = $key;
                } elseif (strpos($key, ' ')) {
                    $joinon[] = $key . " " . $val . "";
                } else {
                    $joinon[] = $key . " = " . $val . "";
                }
            }
        }

        if (!empty($joinon)) {
            $sql .= sprintf(" ON %s", implode(" AND ", $joinon));
        }

    }

    if (!empty($where)) {
        $sql .= sprintf(" WHERE %s", implode(" AND ", $where));
    }
    if (!empty($orderBy)) {
        $sql .= sprintf(" ORDER BY %s", $orderBy);
    }
    if (!empty($limit)) {
        $sql .= sprintf(" LIMIT %s", $limit);
    }
    if (!empty($offset)) {
        $sql .= sprintf(" OFFSET %s", $offset);
    }

    /*--DB操作-----------------------------------------*/
    $pdo = connect();
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;

    return $res;
}
// =======================================================================
// 新規レコード登録関数(insert) ※func_dbのみで使用
// =======================================================================
/*
 * 　[使用方法]
 *      $res = insert(①,②,③)
 *
 *   [引数]
 *      ① PDOオブジェクト
 *      ② 対象テーブル
 *      ③ 登録データ(1レコード分) 配列の要素にカラム、データ部に対象データ
 *
 *   [戻り値]
 *       execute()の実行結果(TRUE/FALSE)
 *
 * -----------------------------------------------------------------------
 */
function insert($pdo, $table, $data)
{

    /*--初期処理--------------------------------*/

    // 戻り値
    $res = false;
    $fields = array();
    $values = array();

    // パラメータチェック
    if (!is_object($pdo)) {
        return $res;
    }
    if (!is_string($table)) {
        return $res;
    }
    if (!is_array($data)) {
        return $res;
    }
    // ステータス、作成日時
    $data['delete_flg']  = isset($data['delete_flg']) ? $data['delete_flg'] : 0;
    $data['create_date'] = isset($data['create_date']) ? $data['create_date'] : NOW;

    /*--SQL文生成-------------------------------*/
    foreach ($data as $key => $val) {
        $fields[] = $key;
        $values[] = ':' . $key;
    }
    $sql = sprintf(
        "INSERT INTO %s (%s) VALUES (%s)",
        $table,
        implode(',', $fields),
        implode(',', $values)
    );

    /*--DB操作----------------------------------*/
    $stmt = $pdo->prepare($sql);

    foreach ($data as $key => $val) {
        $stmt->bindValue(':' . $key, $val);
    }
    return $stmt->execute();
}

// =======================================================================
// レコード更新関数(update) ※func_dbのみで使用
// =======================================================================
/*
 * 　[使用方法]
 *      $res = update(①,②,③,④)
 *
 *   [引数]
 *      ① PDOオブジェクト
 *      ② 対象テーブル
 *      ③ 更新内容
 *      ④ 更新条件
 *
 *   [戻り値] 文字列
 *      execute()の実行結果(TRUE/FALSE)
 *
 * -----------------------------------------------------------------------
 */
function update($pdo, $table, $setData, $term)
{

    /*-- 初期処理 -------------------------------*/
    $res = false;
    // パラメータチェック
    if (!is_object($pdo)) {
        return $res;
    }
    if (!is_string($table)) {
        return $res;
    }
    if (!is_array($setData)) {
        return $res;
    }
    if (!is_array($term)) {
        return $res;
    }
    // 更新条件初期化
    $params  = array();
    // sqlパーツ初期化
    $setSQL  = null;
    $termSQL = null;

    /*-- SQL文生成 ------------------------------*/
    // 更新データ
    foreach ($setData as $setKey => $setVal) {
        if (!empty($setSQL)) {
            $setSQL = $setSQL . ', ';
        }
        $setSQL = $setSQL . $setKey . ' = :set' . $setKey;
        $params[':set' . $setKey] = $setVal;
    }
    // 更新条件
    foreach ($term as $termKey => $termVal) {
        if (!empty($termSQL)) {
            $termSQL = $termSQL . ' AND ';
        }
        $termSQL = $termSQL . $termKey . ' = :term' . $termKey;
        $params[':term' . $termKey] = $termVal;
    }
    $sql = sprintf(
        "UPDATE %s SET %s WHERE %s",
        $table,
        $setSQL,
        $termSQL
    );

    /*--DB操作---------------------------------*/
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $result = $stmt->execute();
    return isset($setData['unique_id']) ? $setData['unique_id'] : true;
}

// =======================================================================
// テーブル情報削除関数(delete)
// =======================================================================
/*
 * ※ 処理内容未更新
 * 　[使用方法]
 *
 *   [引数]
 *
 *   [戻り値]
 *
 * -----------------------------------------------------------------------
 */
function delete($table = null, $data = array(), $retry = 3, $wait = 200000)
{

    /*-- 初期処理 -------------------------------*/
    $res = false;

    //パラメータチェック
    if (!is_string($table)) {
        return false;
    }
    if (!is_array($data)) {
        return false;
    }

    /*-- SQL文生成 ------------------------------*/
    foreach ($data as $key => $val) {
        $where[] = $key . " = :" . $key;
    }
    $pdo = connect();
    $sql = sprintf(
        "DELETE FROM %s WHERE %s",
        $table,
        implode(' AND ', $where)
    );
    $stmt = $pdo->prepare($sql);
    foreach ($data as $key => $val) {
        $stmt->bindValue(':' . $key, $val);
    }
    //成功するまで、指定回数繰返し（トランザクションエラー対策）
    for ($i = 0; $i < $retry; $i++) {
        $res  = $stmt->execute();
        if ($res) {
            break;
        }
        usleep($wait);
    }
    $pdo  = null;

    return $res;
}

// =======================================================================
// 新規ID発番関数
// =======================================================================
/*
 * 　[使用方法]
 *      $res = getNewId(①)
 *
 *   [引数]
 *      ① 対象テーブル
 *
 *   [戻り値]　配列
 *      $res['newId'] = 書式整形したID(現在の発番済値に+1したもの)
 *      $res['last']  = 最終発番番号(数値のみ)
 * -----------------------------------------------------------------------
 */
function getNewId($table, $add = 1)
{
    // 戻り値初期化
    $res = array();

    /*--発番情報取得-------------------------*/
    $target = '*';
    $where = array();
    $where['type'] = $table;
    $result = select('mst_number', $target, $where);

    /*--エラーチェック-----------------------*/
    // select結果確認
    if (!$result) {
        $res['err'][] = '新規ID取得に失敗しました。';
        return $res;
    }
    // 最新発番値取得
    $currentId = (int)$result[0]['last'];
    // 最大発番数確認
    if (strlen($currentId + 1) > $result[0]['digits']) {
        $res['err'][] = 'データ登録件数が上限を超えています。';
        return $res;
    }

    /*--戻り値生成---------------------------*/
    $format = $result[0]['initial'] . '%0' . $result[0]['digits'] . 'd';
    $res['newId']  = sprintf($format, $currentId + 1);
    $res['last']   = $currentId;
    $res['format'] = $format;
    return $res;
}

// =======================================================================
// 発番済ID情報更新関数
// =======================================================================
/*
 * 　[使用方法]
 *      $res = setNewId(①,②,③)
 *
 *   [引数]
 *      ① PDOオブジェクト
 *      ① 対象テーブル
 *      ② 発番済みID値
 *
 *   [戻り値]
 *      updateの処理結果(TRUE/FALSE)
 *
 * -----------------------------------------------------------------------
 */
function setNewId($pdo, $table, $seq)
{
    /*--更新条件生成------------*/
    $setData = array();
    $setData['last'] = $seq;
    $term    = array();
    $term['type'] = $table;

    /*--更新実行----------------*/
    return update($pdo, 'mst_number', $setData, $term);
}
// =======================================================================
// 新規、更新振り分け関数(upsert)
// =======================================================================
/*
 *   [使用方法]
 *      $res = upsert(①,②,③,[④]);
 *
 *   [引数]
 *      ①ログインユーザー
 *      ②テーブル
 *      ③対象データ
 *      ④対象ID
 *
 *   [戻り値]
 *      正常時
 *          配列[0] = 登録したデータのID
 *          配列[1] = 更新回数
 *      失敗時
 *          エラーメッセージ
 *
 * -----------------------------------------------------------------------
 */
function upsert($user, $table, $data, $retry = 3, $wait = 200000)
{

    /*-- 初期処理 ------------------------------------*/
    // 戻り値初期化
    $res = null;

    // パラメータ型チェック
    if (!is_string($table)) {
        $res['err'][] = 'パラメータチェックに失敗しました。(upsert)';
    }
    if (!is_array($data)) {
        $res['err'][] = 'パラメータチェックに失敗しました。(upsert)';
    }

    // 重複登録チェック
    //    if (isset($_SESSION['upsert'])){
    //        if ($data == $_SESSION['upsert']){
    //            $res['err'][] = '前回の登録内容と重複しています。(upsert)';
    //        }
    //    }

    // 重複チェック用配列
    //    $_SESSION['upsert'] = $data;

    // エラー退避
    if (isset($res['err'])) {
        return $res;
    }


    // 真偽値変換
    foreach ($data as $key => $val) {
        if (is_bool($val)) {
            $val = $val === false ? 'FALSE' : 'TRUE';
            $data[$key] = $val;
        }
    }

    // KeyId
    $keyId = !empty($data['unique_id']) ? $data['unique_id'] : null;

    //更新者、更新日時、作成者、作成日時
    $data['update_user'] = $user['unique_id'];
    $data['update_date'] = NOW;
    if (!$keyId) {
        $data['create_user'] = $user['unique_id'];
        $data['create_date'] = NOW;
    }
    if (!$keyId && !isset($data['delete_flg'])) {
        $data['delete_flg'] = 0;
    }

    /*-- DB接続 ------------------------------------*/
    $pdo = connect();
    $pdo->beginTransaction();

    /*--処理振り分け-------------------------------*/

    // 更新処理
    if ($keyId) {

        // 更新条件
        $term = array();
        $term['unique_id'] = $keyId;

        // データ処理
        $res = update($pdo, $table, $data, $term);
        if (!$res) {
            $pdo  = null;
            $res['err'][] = 'データ更新に失敗しました。(update)';
            return $res;
        }

        // 新規処理
    } else {

        // 新規ID取得
        $newAry = getNewId($table);

        if (isset($newAry['err'])) {
            $res['err'] = $newAry['err'];
            $res['err'][] = '新規データ登録に失敗しました。(getNewId)';
            return $res;
        } else {
            $keyId = $newAry['newId'];
            $data['unique_id'] = $keyId;
        }
        // レコード追加
        $res = insert($pdo, $table, $data);
        if (!$res) {
            $pdo  = null;
            $res['err'][] = '新規データ登録に失敗しました。(insert)';
            return $res;
        }
        // 発番管理テーブル更新
        $res = setNewId($pdo, $table, $newAry['last'] + 1);
        if (!$res) {
            $pdo  = null;
            $res['err'][] = '新規ID更新に失敗しました。(setNewId)';
            return $res;
        }
        // 返却用ID
        $res = $newAry['newId'];
    }

    // 結果反映処理繰り返し
    for ($i = 0; $i < $retry; $i++) {
        $result = $pdo->commit();
        if ($result) {
            break;
        }
        usleep($wait);
    }
    if (!$res) {
        $pdo = null;
        $res['err'] = 'データ登録に失敗しました。(upsert)';
        return $res;
    }

    $pdo = null;

    return $res;
}

//========================================================================
// 複数対応ver(multiUpsert)
//========================================================================
/*
 *   [使用方法]
 *      $res = multiUpsert(①,②,③,[④,⑤]);
 *
 *   [引数]
 *      ①ログインユーザー
 *      ②テーブル
 *      ③対象データ
 *      ※ 複数レコードを2次元配列にして渡す
 *         1次元目のキーに対象レコードのIDを指定する
 *
 *   [戻り値]
 *      正常時-TRUE
 *      失敗時-エラーメッセージ
 *
 * -----------------------------------------------------------------------
 */
function multiUpsert($user, $table, $upData, $retry = 3, $wait = 200000)
{

    /*--初期処理------------------------------------*/

    // 戻り値初期化
    $res = array();

    // 入力データ格納配列初期化
    $addList    = array();
    $appendList = array();
    $common     = array();

    // 共通項目
    $common['update_user'] = $user['unique_id'];
    $common['update_date'] = NOW;

    // パラメータ型チェック
    if (!is_string($table)) {
        $res['err'][] = 'パラメータチェックに失敗しました。(multiUpsert)';
    }
    if (!is_array($upData) || empty($upData)) {
        $res['err'][] = 'パラメータチェックに失敗しました。(multiUpsert)';
    }
    if (isset($res['err'])) {
        return $res;
    }
    // 真偽値変換
    foreach ($upData as $key1 => $upData1) {
        foreach ($upData1 as $key2 => $val) {
            if (is_bool($val)) {
                $val = $val === false ? 'FALSE' : 'TRUE';
            }
            $data[$key1][$key2] = $val;
        }
    }

    /*--登録前データ取得,処理振り分け--------------*/

    // 新規,更新 対象データ配列生成
    foreach ($data as $key => $record) {
        // ID無し→新規追加
        if (empty($record['unique_id'])) {
            $addList[] = $record;
            // ID有り→既存更新
        } else {
            $appendList[] = $record;
        }
    }
    /*-- 新規ID取得 ---------------------------*/
    if ($addList) {
        $newId = getNewId($table);
        if (isset($newId['err'])) {
            $res['err'] = $newId['err'];
            return $res;
        }
    }

    /*--DB操作---------------------------------------*/
    // DB接続
    $pdo = connect();
    $pdo->beginTransaction();

    // 新規データ登録
    if ($addList) {
        // レコード追加
        foreach ($addList as $record) {
            // ID,更新回数,共通項目追加
            $record += $common;
            $record['delete_flg']  = 0;
            $record['create_date'] = NOW;
            $record['create_user'] = $user['unique_id'];
            $record['unique_id'] = sprintf($newId['format'], ++$newId['last']);
            // 追加処理実行
            $result = insert($pdo, $table, $record);
            if (!$result) {
                $pdo = null;
                $res['err'][] = '新規データ登録に失敗しました。(multiUpsert)';
                return $res;
            }
            // 返却用ID
            $res[] = $record['unique_id'];
        }
        // 発番管理テーブル更新
        $result = setNewId($pdo, $table, $newId['last']);
        if (!$result) {
            $pdo = null;
            $res['err'][] = '新規ID更新に失敗しました。(multiUpsert)';
            return $res;
        }
    }
    // 既存データ更新
    if ($appendList) {
        // レコード更新
        foreach ($appendList as $key => $record) {
            // 更新者,更新時間
            $record += $common;
            // 更新条件
            $term = array();
            $term['unique_id'] = $record['unique_id'];
            // DB更新
            $result = update($pdo, $table, $record, $term);
            if (!$result) {
                $pdo = null;
                $res['err'][] = 'データ更新に失敗しました。(multiUpsert)';
                return $res;
            }
            // 返却用ID
            $res[] = $record['unique_id'];
        }
    }

    /*--処理結果反映、戻り値生成-----------------------*/
    // 結果反映処理繰り返し
    for ($i = 0; $i < $retry; $i++) {
        $result = $pdo->commit();
        if ($result) {
            break;
        }
        usleep($wait);
    }

    $pdo = null;

    return $res;
}
//========================================================================
// 複数対応ver(multiInsert)　※データ移行で利用
//========================================================================
/*
 *   [使用方法]
 *      $res = multiInsert(①,②,③,[④,⑤]);
 *
 *   [引数]
 *      ①ログインユーザー
 *      ②テーブル
 *      ③対象データ
 *      ※ 複数レコードを2次元配列にして渡す
 *         1次元目のキーに対象レコードのIDを指定する
 *
 *   [戻り値]
 *      正常時-TRUE
 *      失敗時-エラーメッセージ
 *
 * -----------------------------------------------------------------------
 */
function multiInsert($user, $table, $upData, $retry = 3, $wait = 200000)
{

    /*--初期処理------------------------------------*/

    // 戻り値初期化
    $res = array();

    // 入力データ格納配列初期化
    $addList    = array();
    $common     = array();

    // 共通項目
    $common['update_user'] = $user['unique_id'];
    $common['update_date'] = NOW;
    $common['create_user'] = $user['unique_id'];
    $common['create_date'] = NOW;
    $common['delete_flg']  = 0;

    // パラメータ型チェック
    if (!is_string($table)) {
        $res['err'][] = 'パラメータチェックに失敗しました。(multiInsert)';
    }
    if (!is_array($upData) || empty($upData)) {
        $res['err'][] = 'パラメータチェックに失敗しました。(multiInsert)';
    }
    if (isset($res['err'])) {
        return $res;
    }
    // 真偽値変換
    foreach ($upData as $key1 => $upData1) {
        foreach ($upData1 as $key2 => $val) {
            if (is_bool($val)) {
                $val = $val === false ? 'FALSE' : 'TRUE';
            }
            $data[$key1][$key2] = $val;
        }
    }

    /*--登録前データ取得,処理振り分け--------------*/

    // 新規対象データ配列生成
    foreach ($data as $key => $record) {
        $addList[] = $record;
    }

    /*--DB操作---------------------------------------*/
    // DB接続
    $pdo = connect();
    $pdo->beginTransaction();

    // 新規データ登録
    if ($addList) {
        // レコード追加
        foreach ($addList as $record) {
            // ID,更新回数,共通項目追加
            $record += $common;
            // 追加処理実行
            $result = insert($pdo, $table, $record);
            if (!$result) {
                $pdo = null;
                $res['err'][] = '新規データ登録に失敗しました。(multiInsert)';
                return $res;
            }
            // 返却用ID
            $res[] = $record['unique_id'];
        }
        $newId = preg_replace('/[^0-9]/', '', $record['unique_id']);

        // 発番管理テーブル更新
        $result = setNewId($pdo, $table, $newId);
        if (!$result) {
            $pdo = null;
            $res['err'][] = '新規ID更新に失敗しました。(multiInsert)';
            return $res;
        }
    }

    /*--処理結果反映、戻り値生成-----------------------*/
    // 結果反映処理繰り返し
    for ($i = 0; $i < $retry; $i++) {
        $result = $pdo->commit();
        if ($result) {
            break;
        }
        usleep($wait);
    }

    $pdo = null;

    return $res;
}
// =======================================================================
// テーブル情報取得関数(select)
// =======================================================================
/*
 * 　[使用方法]
 *      $res = select(①,②,③,④,⑤])
 *
 *   [引数]
 *      ① SQL(文字列)
 *      ② path
 *
 *   [戻り値]
 *       正常時-SQL結果
 * -----------------------------------------------------------------------
 */
function customSQL($sql, $path = '')
{

    /*--DB操作-----------------------------------------*/
    $pdo  = connect();
    if ($path) {
        $stmt = $pdo->prepare('SET search_path to ' . $path . ';');
        $stmt->execute();
        $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;

    return $res;
}
//========================================================================
// テーブル別初期化関数(MySQL)
//========================================================================
/*
 *   [戻り値]
 *      result[カラム名] = NULL
 *
 * -----------------------------------------------------------------------
 */
function initTable($table)
{
    $sql = sprintf("DESCRIBE " . $table . "");
    $pdo  = connect();
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $temp = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pdo  = null;
    foreach ($temp as $val) {
        $colName = $val['Field'];
        $res[$colName] = null;
    }
    return $res;
}
//========================================================================
// NULL埋め関数
//========================================================================
/*
 *   [戻り値]
 *      result[カラム名] = NULL
 *
 * -----------------------------------------------------------------------
 */
function setNull($ary = array(), $type = 1)
{
    if ($type == 1) {
        foreach ($ary as $key => $val) {
            if ($val === '') {
                $ary[$key] = null;
            }
        }
    }
    if ($type == 2) {
        foreach ($ary as $key1 => $ary2) {
            foreach ($ary2 as $key2 => $val) {
                if ($val === '') {
                    $ary[$key1][$key2] = null;
                }
            }
        }
    }
    return $ary;
}
