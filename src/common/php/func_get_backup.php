<?php
/* =======================================================================
 * 汎用テーブルデータ取得関数
 * =======================================================================
 *   [引数]
 *     1.テーブル名
 *     2.検索配列
 *     3.検索順
 *     4.最大件数
 *
 *   [戻り値]
 *     $res[field]     = 'テーブル格納データ' ←ID指定の場合
 *     $res[id][field] = 'テーブル格納データ'
 *
 * -----------------------------------------------------------------------
 */

function getData($table, $search = array(), $orderBy = 'unique_id ASC', $limit = null)
{

    /* -- 初期処理 -------------------------------------------- */

    // 初期化
    $res = array();

    /* -- データ取得 ------------------------------------------ */
    $where = array();
    foreach ($search as $key => $val) {
        if ($key === 'delete_flg' && $val === 'all') {
            // 何もしない
        } else {
            $where[$key] = $val;
        }
    }
    if (empty($search['delete_flg'])) {
        $where['delete_flg'] = 0;
    }

    $temp = select($table, '*', $where, $orderBy, $limit);
    /* -- データ変換 ------------------------------------------ */
    foreach ($temp as $val) {

        // ID
        $keyId = $val['unique_id'];

        // 格納
        if (!empty($search['unique_id'])) {
            $res = $val;
        } else {
            $res[$keyId] = $val;
        }
    }
    /* -- データ返却 ------------------------------------------ */
    return $res;
}

/* =======================================================================
 * 汎用複数テーブルデータ取得関数
 * =======================================================================
 *   [引数]
 *      ① 対象テーブル（メイン）
 *      ② 結合するテーブル（配列）
 *      ③ 対象カラム名
 *      ④ WHERE句に指定する検索条件
 *      ⑤ メインテーブルとの結合カラム名
 *      ⑥ ソート条件
 *      ⑦ 最大取得件数
 *
 *   [戻り値]
 *     $res[field]     = 'テーブル格納データ' ←ID指定の場合
 *     $res[id][field] = 'テーブル格納データ'
 *
 * -----------------------------------------------------------------------
 */

function getMultiData(
    $table,
    $tables,
    $target = null,
    $where = array(),
    $joinCol = array(),
    $orderBy = null,
    $limit = null
) {

    /* -- 初期処理 -------------------------------------------- */

    // 初期化
    $res = array();
    $joinWhere = array();

    /* -- 取得条件 -------------------------------------------- */

    // 検索条件
    foreach ($where as $key => $val) {
        if ($key === 'delete_flg' && $val === 'all') {
            unset($where[$key]);
        }
    }
    if (empty($where)) {
        $where[$table . '.delete_flg'] = 0;
        foreach ($tables as $val) {
            $where[$val . '.delete_flg'] = 0;
        }
    }

    // 対象カラム
    if (empty($target)) {
        $target = $table . '.*';
        foreach ($tables as $val) {
            $target .= ', ' . $val . '.*';
        }
    }

    // 結合条件
    if (empty($joinCol)) {
        foreach ($tables as $val) {
            $joinWhere[$val][$val . '.link_header'] = $table . '.unique_id';
        }
    } else {
        foreach ($joinCol as $col) {
            foreach ($tables as $val) {
                $joinWhere[$val][$val . '.' . $col] = $table . '.' . $col;
            }
        }
    }

    /* -- データ取得 ------------------------------------------ */
    return multiSelect($table, $tables, $target, $where, $joinWhere, $orderBy);

    /* -- データ返却 ------------------------------------------ */
}

/* =======================================================================
 * 汎用選択肢取得
 * =======================================================================
 *   [引数]
 *     ①選択肢分類
 *
 *   [戻り値]
 *   ＜type指定あり＞
 *     $res[type][id][field] = 'テーブル格納データ'
 *
 *   ＜type指定なし＞
 *     $res[id][field] = 'テーブル格納データ'
 *
 * -----------------------------------------------------------------------
 */

function getCode($keyGroup = null, $keyType = null)
{

    /* -- 初期処理 -------------------------------------------- */
    $res = array();

    /* -- データ取得 ------------------------------------------ */
    $where = array();
    $where['delete_flg'] = 0;
    if ($keyGroup) {
        $where['group_div'] = $keyGroup;
    }
    if ($keyType) {
        $where['type'] = $keyType;
    }
    $target = 'unique_id,name,group_div,type';
    $orderBy = 'unique_id ASC';
    $limit = null;
    $temp = select('mst_code', $target, $where, $orderBy, $limit);

    /* -- データ変換 ------------------------------------------ */
    foreach ($temp as $val) {

        // 分類,ID
        $group = $val['group_div'];
        $type = $val['type'];
        $name = $val['name'];
        $keyId = $val['unique_id'];

        // 格納
        if ($keyGroup && $keyType) {
            $res[$keyId] = $val['name'];
        } elseif ($keyGroup) {
            $res[$type][$keyId] = $val['name'];
        } else {
            $res[$group][$type][$keyId] = $val['name'];
        }
    }

    /* -- データ返却 ------------------------------------------ */
    return $res;
}

/* =======================================================================
 * 利用者リスト取得
 * =======================================================================
 */

function getUserList($placeId = null, $search = array(), $orderBy = 'unique_id ASC')
{

    // 初期化
    $res = array();
    $tgtOfc = array();
    $tgtUser = array();

    // 事業所マスタ取得
    $where = array();
    $where['delete_flg'] = 0;
    if ($placeId) {
        $where['place_id'] = $placeId;
    }
    $target = 'unique_id';
    $temp = select('mst_office', $target, $where);
    if (!$temp) {
        return $res;
    }
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        // 事業所ID判定
        if (!empty($search['office']) && $tgtId != $search['office']) {
            continue;
        }
        $tgtOfc[] = $tgtId;
    }

    // 利用者所属事業所
    $where = array();
    $where['delete_flg'] = 0;
    $where['office_id'] = $tgtOfc;
    $target = 'user_id,start_day,end_day';
    $temp = select('mst_user_office1', $target, $where);
    if (!$temp) {
        return $res;
    }
    foreach ($temp as $val) {
        $tgtId = $val['user_id'];
        // 利用者内部ID判定
        if (!empty($search['user_id']) && $tgtId != $search['user_id']) {
            continue;
        }
        // 対象範囲日付判定
        if (!empty($search['start_day']) && !empty($val['end_day']) && $val['end_day'] < $search['start_day']) {
            continue;
        }
        if (!empty($search['end_day']) && !empty($val['start_day']) && $val['start_day'] > $search['end_day']) {
            continue;
        }
        // 契約中／停止中判定
        if (!empty($search['status'])) {
            if (!isset($search['status']['停止中'])) {
                if ($val['start_day'] > TODAY) {
                    continue;
                }
                if (!empty($val['end_day']) && $val['end_day'] < TODAY) {
                    continue;
                }
            }
            if (!isset($search['status']['契約中'])) {
                if ($val['start_day'] <= TODAY) {
                    continue;
                }
                if (!empty($val['end_day']) && $val['end_day'] >= TODAY) {
                    continue;
                }
            }
        }
        $tgtUser[] = $tgtId;
    }

    // 利用者マスタ
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $tgtUser;
    if (!empty($search['service'])) {
        $where['service_type'] = $search['service'];
    }
    $target = '*';
    $temp = select('mst_user', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $val['name'] = $val['last_name'] . ' ' . $val['first_name'];
        $res[$tgtId] = $val;
    }

    // 返却
    return $res;
}

/* =======================================================================
 * 利用者情報取得
 * =======================================================================
 */

function getUserInfo($userId, $multi = null)
{

    // 初期化
    $res = array();

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $userId;
    $temp = select('mst_user', '*', $where);
    if ($multi) {
        foreach ($temp as $val) {
            $val['user_name'] = $val['last_name'] . ' ' . $val['first_name'];
            $res[$temp['unique_id']] = $val;
        }
    } else {
        if (isset($temp[0])) {
            $res = $temp[0];
            $res['user_name'] = $res['last_name'] . ' ' . $res['first_name'];
        }
    }

    // 返却
    return $res;
}

/* =======================================================================
 * スタッフリスト取得
 * =======================================================================
 */

function getStaffList($placeId = null)
{

    // 初期化
    $res = array();
    $tgtOfc = array();
    $tgtStaff = array();

    // 事業所マスタ取得
    $where = array();
    $where['delete_flg'] = 0;
    if ($placeId) {
        $where['place_id'] = $placeId;
    }
    $target = 'unique_id';
    $temp = select('mst_office', $target, $where);
    if (!$temp) {
        return $res;
    }
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $tgtOfc[$tgtId] = true;
    }

    // 従業員所属事業所
    $where = array();
    $where['delete_flg'] = 0;
    $target = 'staff_id,office1_id,office2_id';
    $temp = select('mst_staff_office', $target, $where);
    if (!$temp) {
        return $res;
    }
    foreach ($temp as $val) {
        $ofc1 = $val['office1_id'];
        $ofc2 = $val['office2_id'];
        if (isset($tgtOfc[$ofc1]) || isset($tgtOfc[$ofc2])) {
            $tgtId = $val['staff_id'];
        }
        $tgtStaff[] = $tgtId;
    }

    // 従業員マスタ
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $tgtStaff;
    $target = '*';
    $orderBy = 'unique_id ASC';
    $temp = select('mst_staff', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $val['name'] = $val['last_name'] . ' ' . $val['first_name'];
        $res[$tgtId] = $val;
    }

    // 返却
    return $res;
}

/* =======================================================================
 * 要介護度取得
 * =======================================================================
 */

function getCareRank($userId, $tgtDay = TODAY)
{

    // 初期化
    $res = null;

    // 介護保険証の情報取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userId;
    if ($tgtDay) {
        $where['start_day1 <='] = $tgtDay;
        $where['end_day1 >='] = $tgtDay;
    }
    $target = 'care_rank';
    $orderBy = 'unique_id ASC';
    $temp = select('mst_user_insure1', $target, $where, $orderBy);

    // 最新値を格納
    foreach ($temp as $val) {
        $res = $val['care_rank'];
    }

    // データ返却
    return $res;
}

/* =======================================================================
 * スタッフ名称取得
 * =======================================================================
 */

function getStaffName($staffId)
{

    // 初期化
    $res = null;

    // 介護保険証の情報取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $staffId;
    $target = 'last_name, first_name';
    $temp = select('mst_staff', $target, $where);

    // 値を格納
    return $temp ? $temp[0]['last_name'] . $temp[0]['first_name'] : null;

    // データ返却
}

/* =======================================================================
 * 事業所リスト作成
 * =======================================================================
 */

function getOfficeList($placeId = null, $start = TODAY, $end = TODAY)
{

    // 初期化
    $res = null;
    $tgtData = array();

    // 事業所マスタの取得
    $where = array();
    $where['delete_flg'] = 0;
    if ($placeId) {
        $where['place_id'] = $placeId;
    }
    $where['start_day <='] = $start;
    $orderBy = 'unique_id ASC';
    $temp = select('mst_office', '*', $where, $orderBy);

    // グループ単位で有効なレコードは一つとする
    foreach ($temp as $val) {
        if ($val['end_day'] &&  $val['end_day'] <= $end) {
            continue;
        }
        $group = $val['office_group'];
        $tgtData[$group] = $val;
    }

    // 形式変更 unique_idをKEYとする
    foreach ($tgtData as $group => $val) {
        $tgtId = $val['unique_id'];
        $res[$tgtId] = $val;
    }

    // 返却
    return $res;
}

/* =======================================================================
 * 事業書名称取得
 * =======================================================================
 */

function getOfficeName($tgtId, $tgtDay = TODAY, $type = null)
{

    // 初期化
    $res = null;

    // 利用者からの所属事業所名称取得
    if (!$type || $type === 'user') {
        $where = array();
        $where['delete_flg'] = 0;
        $where['user_id'] = $tgtId;
        if ($tgtDay) {
            $where['start_day <='] = $tgtDay;
        }
        $target = 'office_name, end_day';
        $orderBy = 'unique_id ASC';
        $temp = select('mst_user_office1', $target, $where, $orderBy);
        foreach ($temp as $val) {

            if ($val['end_day']) {
                if ($val['end_day'] < $tgtDay) {
                    continue;
                }
            }

            $res = $val['office_name'];
        }
        // マスタからの事業所名称取得
    } else {
        $where = array();
        $where['unique_id'] = $tgtId;
        $target = 'office_name';
        $orderBy = 'unique_id ASC';
        $temp = select('mst_user_office1', $target, $where, $orderBy);
        foreach ($temp as $val) {
            $res = $val['office_name'];
        }
    }

    // データ返却
    return $res;
}

/* =======================================================================
 * 拠点リスト取得
 * =======================================================================
 */

function getPlaceList()
{

    // 初期化
    $res = array();

    // マスタ情報取得
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_place', '*', $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res[$tgtId] = $val['name'];
    }

    // データ返却
    return $res;
}

/* =======================================================================
 * 拠点情報取得
 * =======================================================================
 */

function getPlaceInfo($placeId = null)
{

    // 初期化
    $res = initTable('mst_place');
    if (!$placeId) {
        return $res;
    }

    // マスタ情報取得
    $where = array();
    $where['unique_id'] = $placeId;
    $temp = select('mst_place', '*', $where);
    if (isset($temp[0])) {
        $res = $temp[0];
    }

    // データ返却
    return $res;
}

/* =======================================================================
 * サービス名称取得
 * =======================================================================
 */

function getServiceConfig($svcId)
{

    // 初期化
    $res['code'] = null;
    $res['name'] = null;

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $svcId;
    $target = 'code,name';
    $orderBy = 'unique_id ASC';
    $temp = select('mst_service', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $res['code'] = $val['code'];
        $res['name'] = $val['name'];
    }

    // データ返却
    return $res;
}

/* =======================================================================
 * 会計用連携データ作成
 * =======================================================================
 *   [引数]
 *     ① 拠点ID
 *     ② 対象月
 *     ③ サービス利用区分(看多機)
 *     ④ サービス利用区分(訪問看護)
 *     ⑤ 利用者ID
 *
 *   [戻り値]
 *     配列
 *
 * -----------------------------------------------------------------------
 */

function getAccount($placeId = null, $month, $type1 = null, $type2 = null, $tgtUser = null)
{

    /* -- 初期処理 --------------------------------------------------- */
    $res = array();
    $userAry = array();
    $idx = 0;

    // レコード初期値
    // データコード00
    $def00 = array();
    $def00['user_name'] = null;
    $def00['code'] = null;
    for ($i = 1; $i <= 1; $i++) {
        $def00['f' . $i] = null;
    }

    // データコード11
    $def11 = array();
    $def11['user_name'] = null;
    $def11['code'] = null;
    for ($i = 1; $i <= 7; $i++) {
        $def11['f' . $i] = null;
    }

    // データコード12
    $def12 = array();
    $def12['user_name'] = null;
    $def12['code'] = null;
    for ($i = 1; $i <= 9; $i++) {
        $def12['f' . $i] = null;
    }

    // データコード13
    $def13 = array();
    $def13['user_name'] = null;
    $def13['code'] = null;
    for ($i = 1; $i <= 10; $i++) {
        $def13['f' . $i] = null;
    }

    // データコード21
    $def21 = array();
    $def21['user_name'] = null;
    $def21['code'] = null;
    for ($i = 1; $i <= 8; $i++) {
        $def21['f' . $i] = null;
    }

    // データコード22
    $def22 = array();
    $def22['user_name'] = null;
    $def22['code'] = null;
    for ($i = 1; $i <= 4; $i++) {
        $def22['f' . $i] = null;
    }

    // データコード23
    $def23 = array();
    $def23['user_name'] = null;
    $def23['code'] = null;
    for ($i = 1; $i <= 4; $i++) {
        $def23['f' . $i] = null;
    }

    // データコード24
    $def24 = array();
    $def24['user_name'] = null;
    $def24['code'] = null;
    for ($i = 1; $i <= 4; $i++) {
        $def24['f' . $i] = null;
    }

    // データコード25
    $def25 = array();
    $def25['user_name'] = null;
    $def25['code'] = null;
    for ($i = 1; $i <= 15; $i++) {
        $def25['f' . $i] = null;
    }

    // データコード31
    $def31 = array();
    $def31['user_name'] = null;
    $def31['code'] = null;
    for ($i = 1; $i <= 16; $i++) {
        $def31['f' . $i] = null;
    }

    // データコード32
    $def32 = array();
    $def32['user_name'] = null;
    $def32['code'] = null;
    for ($i = 1; $i <= 16; $i++) {
        $def32['f' . $i] = null;
    }

    // データコード33
    $def33 = array();
    $def33['user_name'] = null;
    $def33['code'] = null;
    for ($i = 1; $i <= 11; $i++) {
        $def33['f' . $i] = null;
    }

    // データコード34
    $def34 = array();
    $def34['user_name'] = null;
    $def34['code'] = null;
    for ($i = 1; $i <= 13; $i++) {
        $def34['f' . $i] = null;
    }

    // データコード35
    $def35 = array();
    $def35['user_name'] = null;
    $def35['code'] = null;
    for ($i = 1; $i <= 5; $i++) {
        $def35['f' . $i] = null;
    }

    // データコード36
    $def36 = array();
    $def36['user_name'] = null;
    $def36['code'] = null;
    for ($i = 1; $i <= 15; $i++) {
        $def36['f' . $i] = null;
    }

    // データコード37
    $def37 = array();
    $def37['user_name'] = null;
    $def37['code'] = null;
    for ($i = 1; $i <= 5; $i++) {
        $def37['f' . $i] = null;
    }

    // データコード38
    $def38 = array();
    $def38['user_name'] = null;
    $def38['code'] = null;
    for ($i = 1; $i <= 4; $i++) {
        $def38['f' . $i] = null;
    }

    // データコード41
    $def41 = array();
    $def41['user_name'] = null;
    $def41['code'] = null;
    for ($i = 1; $i <= 11; $i++) {
        $def41['f' . $i] = null;
    }

    if (empty($type1) && empty($type2)) {
        return $res;
    }

    /* -- マスタ取得 ------------------------------------------------- */

    // 月初日、月末日
    $firstDay = $month . '-01';
    $dt = new DateTime($firstDay);
    $lastDay = $dt->modify('last day of this month')->format('Y-m-d');

    // 日付配列
    $calenadar = getCalendar($firstDay, $lastDay);

    // 事業所リスト
    $ofcList = getOfficeList($placeId);

    // 利用者リスト
    $userList = getUserList($placeId);

    //    debug($ofcList);
    //    debug($userList);


    foreach ($userList as $userId => $val) {

        // 初期化
        $flg = false;

        // 利用者判定
        if ($tgtUser && $userId !== $tgtUser) {
            $flg = true;
        }
        // 事業所種別・看多機判定

        $type = $val['service_type'];
        if ((mb_strpos($type, '看多機') !== false || mb_strpos($type, '看護小規模多機能') !== false) && empty($type1)) {
            $flg = true;
        }

        // 事業所種別・訪問看護判定
        if ((mb_strpos($type, '訪問看護') !== false || mb_strpos($type, '訪看') !== false) && empty($type2)) {
            $flg = true;
        }

        // NG判定
        if ($flg) {
            unset($userList[$userId]);
            continue;
        }

        // サービス区分判定
        $serviceDiv = "";
        if (mb_strpos($val['service_type'], '医療') !== false) {
            $serviceDiv = "2";
        } else {
            $serviceDiv = "1";
        }
        $userList[$userId]['service_div'] = $serviceDiv;

        // 検索対象利用者
        $userAry[] = $userId;
    }

    if (empty($userList)) {
        return $res;
    }

    /* -- データ格納 ------------------------------------------------- */

    /* -- ヘッダー(00) --------------------------------- */
    $dat = $def00;
    $dat['code'] = '00';
    $dat['f1'] = formatDateTime($firstDay, 'Ym');
    $res[$idx] = $dat;

    $sexType['男'] = 1;
    $sexType['女'] = 2;
    $sexType['男性'] = 1;
    $sexType['女性'] = 2;
    $sexType['不明'] = 3;

    /* -- 利用者基本情報(11) --------------------------- */
    foreach ($userList as $userId => $val) {

        $val['birthday'] = $val['birthday'] === "0000-00-00" ? null : $val['birthday'];

        // レコード設定
        $dat = $def11;
        $dat['code'] = '11';
        $dat['user_name'] = trimStrWidth($val['name'], 100, "");
        $dat['f1'] = $val['other_id'];
        $dat['f2'] = trimStrWidth($val['last_kana'] . $val['first_kana'], 100, "");
        $dat['f3'] = trimStrWidth($val['last_name'] . $val['first_name'], 100, "");
        $dat['f4'] = trimStrWidth(formatDateTime($val['birthday'], 'Ymd'), 8, "");
        $dat['f5'] = trimStrWidth($val['sex'] ? $sexType[$val['sex']] : 3, 1, "");
        $dat['f6'] = trimStrWidth($val['prefecture'] . $val['area'] . $val['address1'] . $val['address2'] . $val['address3'], 120, "");
        $dat['f7'] = null;

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }

    // データ取得
    $ins3List = array();
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['start_day <='] = $lastDay;
    $where['end_day >='] = $firstDay;
    $temp = select('mst_user_insure3', '*', $where);
    foreach ($temp as $val) {
        // 利用者
        $userId = $val['user_id'];

        $ins3List[$userId] = $val;
    }

    /* -- 公費情報(12) --------------------------------- */

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['start_day <='] = $lastDay;
    $where['end_day >='] = $firstDay;
    $temp = select('mst_user_insure4', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        $val['start_day'] = $val['start_day'] === "0000-00-00" ? null : $val['start_day'];
        $val['end_day'] = $val['end_day'] === "0000-00-00" ? null : $val['end_day'];

        // 公費名称
        $ins3Name = isset($ins3List[$userId]['name']) ? $ins3List[$userId]['name'] : '';

        // レコード設定
        $dat = $def12;
        $dat['code'] = '12';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = $userList[$userId]['service_div'];
        $dat['f3'] = trimStrWidth(formatDateTime($val['start_day'], 'Ymd'), 8, "");
        $dat['f4'] = trimStrWidth(formatDateTime($val['end_day'], 'Ymd'), 8, "");
        $dat['f5'] = trimStrWidth($val['number2'], 8, "");
        $dat['f6'] = trimStrWidth($val['number3'], 7, "");
        $dat['f7'] = trimStrWidth($val['number1'], 3, "");
        $dat['f8'] = trimStrWidth($ins3Name, 32, "");
        $dat['f9'] = trimStrWidth($val['upper_limit'], 6, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- 利用者金融期間情報(13) ----------------------- */

    // 銀行区分
    $bankType['銀行'] = 1;
    $bankType['信用金庫'] = 2;
    $bankType['農協'] = 3;
    $bankType['郵便局'] = 4;
    $bankType['ゆうちょ銀行'] = 4;

    $dpsType['普通預金'] = 1;
    $dpsType['当座預金'] = 2;
    $dpsType['納税準備預金'] = 3;
    $dpsType['その他'] = 9;

    $payType['振替'] = 1;
    $payType['引き落とし'] = 1;
    $payType['振込'] = 2;
    $payType['現金'] = 3;

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $temp = select('mst_user_pay', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        // レコード設定
        $dat = $def13;
        $dat['code'] = '13';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = trimStrWidth($val['bank_type'] ? $bankType[$val['bank_type']] : null, 1, "");
        $dat['f3'] = trimStrWidth($val['bank_code'], 4, "");
        $dat['f4'] = trimStrWidth($val['bank_name'], 20, "");
        $dat['f5'] = trimStrWidth($val['branch_code'], 3, "");
        $dat['f6'] = trimStrWidth($val['branch_name'], 20, "");
        $dat['f7'] = trimStrWidth($val['deposit_type'] ? $dpsType[$val['deposit_type']] : null, 1, "");
        $dat['f8'] = trimStrWidth($val['deposit_code'], 7, "");
        $dat['f9'] = trimStrWidth($val['deposit_name'], 30, "");
        $dat['f10'] = trimStrWidth($val['method'] ? $payType[$val['method']] : "", 1, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- 介護保険証情報(21) --------------------------- */

    $careType['非該当'] = '01';
    $careType['要支援'] = '11';
    $careType['要支援1'] = '11';
    $careType['要支援2'] = '13';
    $careType['要介護1'] = '21';
    $careType['要介護2'] = '22';
    $careType['要介護3'] = '23';
    $careType['要介護4'] = '24';
    $careType['要介護5'] = '25';
    $careType['要支援１'] = '11';
    $careType['要支援２'] = '13';
    $careType['要介護１'] = '21';
    $careType['要介護２'] = '22';
    $careType['要介護３'] = '23';
    $careType['要介護４'] = '24';
    $careType['要介護５'] = '25';

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['start_day1 <='] = $lastDay;
    $where['end_day1 >='] = $firstDay;
    $temp = select('mst_user_insure1', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        // 日付変換
        $val['start_day1'] = $val['start_day1'] === "0000-00-00" ? null : $val['start_day1'];
        $val['end_day1'] = $val['end_day1'] === "0000-00-00" ? null : $val['end_day1'];
        $val['start_day2'] = $val['start_day2'] === "0000-00-00" ? null : $val['start_day2'];
        $val['end_day2'] = $val['end_day2'] === "0000-00-00" ? null : $val['end_day2'];

        // レコード設定
        $dat = $def21;
        $dat['code'] = '21';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = trimStrWidth(formatDateTime($val['start_day1'], 'Ymd'), 8, "");
        $dat['f3'] = trimStrWidth(formatDateTime($val['end_day1'], 'Ymd'), 8, "");
        $dat['f4'] = trimStrWidth($val['insure_no'], 8, "");
        $dat['f5'] = trimStrWidth($val['insured_no'], 10, "");
        $dat['f6'] = trimStrWidth($val['care_rank'] ? $careType[$val['care_rank']] : "", 2, "");
        $dat['f7'] = trimStrWidth(formatDateTime($val['start_day2'], 'Ymd'), 8, "");
        $dat['f8'] = trimStrWidth(formatDateTime($val['end_day2'], 'Ymd'), 8, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- 給付情報(22) --------------------------- */

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['start_day <='] = $lastDay;
    $where['end_day >='] = $firstDay;
    $temp = select('mst_user_insure2', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        // 日付変換
        $val['start_day'] = $val['start_day'] === "0000-00-00" ? null : $val['start_day'];
        $val['end_day'] = $val['end_day'] === "0000-00-00" ? null : $val['end_day'];

        // 給付率
        $rate = "00";
        $rate = sprintf('%02d', $val['rate']);

        // レコード設定
        $dat = $def22;
        $dat['code'] = '22';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = trimStrWidth(formatDateTime($val['start_day'], 'Ymd'), 8, "");
        $dat['f3'] = trimStrWidth(formatDateTime($val['end_day'], 'Ymd'), 8, "");
        $dat['f4'] = trimStrWidth($rate, 2, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- 居宅サービス計画情報(23) --------------------- */

    $cxlType['非該当'] = 1;
    $cxlType['医療機関入院'] = 3;
    $cxlType['死亡'] = 4;
    $cxlType['その他'] = 5;
    $cxlType['介護老人福祉施設入所'] = 6;
    $cxlType['介護老人保険施設入所'] = 7;
    $cxlType['介護療養型医療施設入所'] = 8;

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['start_day <='] = $lastDay;
    $where['end_day >='] = $firstDay;
    $temp = select('mst_user_office2', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        // 日付変換
        $val['start_day'] = $val['start_day'] === "0000-00-00" ? null : $val['start_day'];
        $val['end_day'] = $val['end_day'] === "0000-00-00" ? null : $val['end_day'];

        // レコード設定
        $dat = $def23;
        $dat['code'] = '23';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = trimStrWidth(formatDateTime($val['start_day'], 'Ymd'), 8, "");
        $dat['f3'] = trimStrWidth(formatDateTime($val['end_day'], 'Ymd'), 8, "");
        $dat['f4'] = trimStrWidth(!empty($val['cancel_type']) ? $cxlType[$val['cancel_type']] : null, 1, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- 居宅介護支援事業者情報(24) ------------------- */

    // データ取得
    //    $where = array();
    //    $where['delete_flg']   = 0;
    //    $where['user_id']      = $userAry;
    //    $where['start_day <='] = $lastDay;
    //    $where['end_day >=']   = $firstDay;
    //    $temp = select('mst_user_office2', '*', $where);

    $plnType['居宅支援作成'] = 1;
    $plnType['居宅介護支援事業者作成'] = 1;
    $plnType['$plnType'] = 2;
    $plnType['予防支援作成'] = 3;

    foreach ($temp as $val) {

        if (isset($plnType[$val['plan_type']]) === false) {
            continue;
        }

        // 利用者
        $userId = $val['user_id'];

        // レコード設定
        $dat = $def24;
        $dat['code'] = '24';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = $plnType[$val['plan_type']];
        $dat['f3'] = trimStrWidth($val['office_code'], 10, "");
        $dat['f4'] = trimStrWidth($val['office_name'], 50, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }

    /* -- 介護実績情報(25) ----------------------------- */
    $defMapAry = array();
    for ($i = 0; $i <= 31; $i++) {
        $defMapAry[$i] = 0;
    }

    // 実績親データ
    $rcdAry = null;
    $sendData = array();
    $recData = array();
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['use_day >='] = $firstDay;
    $where['use_day <='] = $lastDay;
    $rcdList = select('dat_user_record', '*', $where);
    foreach ($rcdList as $val) {
        if ($val['status'] === 'キャンセル') {
            continue;
        }

        // 対象のサービス名称で無ければ除外
        if (chkSvcType($val['service_name'], $type1, $type2) === false) {
            continue;
        }

        $recId = $val['unique_id'];
        $rcdAry[] = $recId;
        $recData[$recId] = $val;
    }

    // サービスマスタ
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service', '*', $where);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $tgtCd = $val['code'];
        $svcMst[$tgtId] = $val;
        $svcData[$tgtCd] = $val;
    }

    // 加算マスタ
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_add', '*', $where);
    foreach ($temp as $val) {
        $type = $val['type'];
        $tgtId = $val['unique_id'];
        $addMst[$tgtId] = $val;
    }

    // 居宅支援事業所情報
    $ofcList = array();
    $userOfc = array();
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $temp = select('mst_user_office2', '*', $where);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $ofcList[$userId] = $val;
    }

    // 実績加減算データ
    $rcdAddAry = null;
    $recAddList = array();
    $recAddData = array();
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_record_id'] = $rcdAry;
    $rcdAddList = select('dat_user_record_add', '*', $where);
    foreach ($rcdAddList as $val) {
        if (empty($val['add_id'])) {
            continue;
        }
        $rcdId = $val['user_record_id'];
        $svcId = $recData[$rcdId]['service_id'];
        $tgtMst = isset($svcMst[$svcId]) ? $svcMst[$svcId] : null;

        $recId = $val['user_record_id'];
        $addId = $val['add_id'];
        $uniqueId = $val['unique_id'];
        $userId = $recData[$recId]['user_id'];
        $start_time = $val['start_day'] ? $val['start_day'] : "";

        $val['use_day'] = $recData[$recId]['use_day'];
        $val['start_time'] = $recData[$recId]['start_time'];
        $val['end_time'] = $recData[$recId]['end_time'];
        $val['add_name'] = $addMst[$addId]['name'];
        $val['user_id'] = $userId;
        $val['service_id'] = $svcId;
        $val['service_code'] = isset($svcData[$svcId]) ? $svcData[$svcId]['code'] : null;
        $val['service_name'] = isset($svcData[$svcId]) ? $svcData[$svcId]['name'] : null;

        $recAddData[$addId . $start_time] = $val;
        $recAddList[$userId][$addId . $start_time][$uniqueId] = $val;
    }
    // 送信データ作成
    foreach ($recAddList as $userId => $recAddList2) {
        $f2 = 0;
        $f14 = $ofcList[$userId]['person_name'] ? $ofcList[$userId]['person_name'] : "";
        $f15 = $ofcList[$userId]['fax'] ? $ofcList[$userId]['fax'] : "";
        foreach ($recAddList2 as $svcId => $recAddList3) {
            $f10Map = $defMapAry;
            $f11Map = $defMapAry;
            $f12Map = $defMapAry;
            foreach ($recAddList3 as $uniqueId => $val) {

                if (substr($val['service_code'], 0, 1) == 7) {
                    continue;
                }

                // 日付変換
                $val['use_day'] = $val['use_day'] === "0000-00-00" ? null : $val['use_day'];
                $val['start_day'] = $val['start_day'] === "0000-00-00" ? null : $val['start_day'];
                $val['end_day'] = $val['end_day'] === "0000-00-00" ? null : $val['end_day'];

                /* -- 介護提供日情報(F10)作成 --------------------- */
                $tgtDay = $val['use_day'];
                $tgtDayDate = formatDateTime($tgtDay, "d");
                $idxDay = intval($tgtDayDate);
                if ($idxDay < 1) {
                    continue;
                }
                $f10Map[$idxDay] = 1;

                /* -- 介護提供日情報(F11)作成 --------------------- */
                // 退院時共同指導の場合は回数を加算する
                if ($val['service_name'] === "退院時共同指導") {
                    $f11Map[$idxDay] += 1;
                }
                // 看護小規模初期加算の場合は加減算で指定された日付範囲に1を立てる
                // 日割の場合は日割期間に1を立てる
                elseif ($val['service_name'] === "看護小規模初期加算" || strpos($val['service_name'], "日割") !== false) {
                    $d1 = dateToDay($val['start_day']);
                    $d2 = dateToDay($val['end_day']);
                    if ($d1 < 1 || $d2 < 1) {
                        continue;
                    }
                    for ($i = $d1; $i <= $d2; $i++) {
                        $f11Map[$i] = 1;
                    }
                } else {
                    $f12Map[$idxDay] = 1;
                }
            }

            // f2作成
            $f2++;
            // f3作成
            $f3 = "1";
            // 単純加算の判定
            $svcName = $recAddData[$svcId]['service_name'];
            if (strpos($svcName, "加算") !== false) {
                $f3 = "3";
            }
            $f5 = "";
            $f6 = "";
            if (strpos($svcName, "ターミナルケア") !== false) {
                $f5 = "9999";
                $f6 = "9999";
            } else {
                $f5 = $recAddData[$svcId]['start_time'] ? str_replace(":", "", $recAddData[$svcId]['start_time']) : "";
                $f6 = $recAddData[$svcId]['end_time'] ? str_replace(":", "", $recAddData[$svcId]['end_time']) : "";
            }
            // データ区分を日々に設定
            $f7 = "1";
            // 訪問看護区分
            $f8 = "1";
            if (mb_strpos($svcName, "定期巡回") !== false) {
                $f8 = "2";
            }
            // 看多機能区分
            $f9 = "";
            if (mb_strpos($svcName, "通い") !== false) {
                $f9 = "1";
            } elseif (mb_strpos($svcName, "宿泊") !== false) {
                $f9 = "2";
            } elseif (mb_strpos($svcName, "訪問介護") !== false) {
                $f9 = "3";
            } elseif (mb_strpos($svcName, "訪問看護") !== false) {
                $f9 = "4";
            }

            $f10Data = arryMapToStr($f10Map);
            $f11Data = arryMapToStr($f11Map);
            $f12Data = arryMapToStr($f12Map);

            $f13 = "";
            $svcCode = $recAddData[$svcId]['service_code'];
            $useDay = $recAddData[$svcId]['use_day'];
            $d = dateToDay($useDay, "d") ? sprintf("%02d", dateToDay($useDay, "d")) : "";
            $md = dateToDay($useDay, "md") ? sprintf("%04d", dateToDay($useDay, "md")) : "";
            $ymd = dateToDay($useDay, "Ymd") ? sprintf("%06d", dateToDay($useDay, "Ymd")) : "";
            if ($svcCode === '134003') {
                $f13 = $md;
            } elseif ($svcCode === '634003') {
                $f13 = $md;
            } elseif ($svcCode === '774003') {
                $f13 = $md;
            } elseif ($svcCode === '134004') {
                $f13 = $d;
            } elseif ($svcCode === '137000') {
                $f13 = $Ymd;
            } elseif ($svcCode === '776100') {
                $f13 = $Ymd;
            } else {
                $f13 = "";
            }

            // レコード設定
            $dat = $def25;
            $dat['code'] = '25';
            $dat['user_name'] = $userList[$userId]['name'];
            $dat['f1'] = $userList[$userId]['other_id'];
            $dat['f2'] = trimStrWidth(sprintf('%d', $f2), 2, "");
            $dat['f3'] = trimStrWidth($f3, 1, "");
            $dat['f4'] = trimStrWidth($recAddData[$svcId]['service_code'], 6, "");
            $dat['f5'] = trimStrWidth($f5, 4, "");
            $dat['f6'] = trimStrWidth($f6, 4, "");
            $dat['f7'] = trimStrWidth($f7, 1, "");
            $dat['f8'] = trimStrWidth($f8, 1, "");
            $dat['f9'] = trimStrWidth($f9, 1, "");
            $dat['f10'] = trimStrWidth($f10Data, 31, "");
            $dat['f11'] = trimStrWidth($f11Data, 31, "");
            $dat['f12'] = trimStrWidth($f12Data, 31, "");
            $dat['f13'] = trimStrWidth($f13, 31, "");
            $dat['f14'] = trimStrWidth($f14, 31, "");
            $dat['f15'] = trimStrWidth($f15, 31, "");

            // 格納
            $idx++;
            $res[$idx] = $dat;

            // $f14と$f15はユーザ単位で一度だけなので初期化する
            $f14 = "";
            $f15 = "";
        }
    }

    /* -- 医療保険証情報(31) --------------------------- */

    $iryType['国保'] = 1;
    $iryType['社保'] = 2;
    $iryType['後期高齢者'] = 3;
    $iryType['公費のみ'] = 4;
    $iryType['労災'] = 5;
    $iryType['公害'] = 6;
    $iryType['その他'] = 7;

    $honType['本人'] = 1;
    $honType['被扶養者'] = 2;
    $honType['高齢者'] = 3;
    $honType['義務教育就学前'] = 4;

    $stkType['現役並みⅢ'] = 1;
    $stkType['現役並みⅡ'] = 2;
    $stkType['現役並みⅠ'] = 3;
    $stkType['一般所得者'] = 4;
    $stkType['低所得者Ⅱ'] = 5;
    $stkType['低所得者Ⅰ'] = 6;
    $stkType['不明'] = 7;

    $skmType['なし'] = 1;
    $skmType['職務上'] = 1;
    $skmType['下船後３カ月以内'] = 1;
    $skmType['通勤災害'] = 1;

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['start_day <='] = $lastDay;
    $where['end_day >='] = $firstDay;
    $temp = select('mst_user_insure3', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        // 日付変換
        $val['start_day'] = $val['start_day'] === "0000-00-00" ? null : $val['start_day'];
        $val['end_day'] = $val['end_day'] === "0000-00-00" ? null : $val['end_day'];

        $f5 = $val['number3'] . '・' . $val['number4'];

        // 特例措置による経過措置の有無
        $f9 = "";
        if ($val['type1'] === "高齢者") {
            $f9 = $val['select1'] ? $val['select1'] : "";
        }

        $f12 = "";
        if ($val['type1'] !== "公費のみ") {
            $f12 = $val['name'];
        }

        $f16 = "";
        if ($val["type1"] !== "後期高齢者") {
            $f5 = $val['number1'];
            $f16 = $val['number5'];
        }

        // レコード設定
        $dat = $def31;
        $dat['code'] = '31';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = trimStrWidth(formatDateTime($val['start_day'], 'Ymd'), 8, "");
        $dat['f3'] = trimStrWidth($val['end_day'] ? formatDateTime($val['end_day'], 'Ymd') : "99999999", 8, "");
        $dat['f4'] = trimStrWidth($val['number1'], 8, "");
        $dat['f5'] = trimStrWidth($f5, 28, "");
        $dat['f6'] = trimStrWidth(!empty($val['type1']) ? $iryType[$val['type1']] : null, 1, "");
        $dat['f7'] = trimStrWidth(!empty($val['type1']) ? $honType[$val['type2']] : null, 1, "");
        $dat['f8'] = trimStrWidth(!empty($val['type1']) ? $stkType[$val['type3']] : null, 1, "");
        $dat['f9'] = trimStrWidth($f9, 1, "");
        $dat['f10'] = trimStrWidth($val['select2'], 1, "");
        $dat['f11'] = trimStrWidth($val['number2'], 2, "");
        $dat['f12'] = trimStrWidth($f12, 32, "");
        $dat['f13'] = trimStrWidth(!empty($val['type4']) ? $skmType[$val['type4']] : null, 1, "");
        $dat['f14'] = null;
        $dat['f15'] = null;
        $dat['f16'] = trimStrWidth($f16, 4, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- 利用者状態情報(32) --------------------------- */

    // 別表８コード
    $attached8Dtl['在宅悪性腫瘍等患者指導管理を受けている状態にある者'] = 41;
    $attached8Dtl['在宅気管切開患者指導管理を受けている状態にある者'] = 42;
    $attached8Dtl['気管カニューレを使用している状態にある者'] = 43;
    $attached8Dtl['留置カテーテルを使用している状態にある者'] = 44;
    $attached8Dtl['在宅自己腹膜灌流指導管理を受けている状態にある者'] = 45;
    $attached8Dtl['在宅血液透析指導管理を受けている状態にある者'] = 46;
    $attached8Dtl['在宅酸素療法指導管理を受けている状態にある者'] = 47;
    $attached8Dtl['在宅中心静脈栄養法指導管理を受けている状態にある者'] = 48;
    $attached8Dtl['在宅成分栄養経管栄養法指導管理を受けている状態にある者'] = 49;
    $attached8Dtl['在宅自己導尿指導管理を受けている状態にある者'] = 50;
    $attached8Dtl['在宅人工呼吸指導管理を受けている状態にある者'] = 51;
    $attached8Dtl['在宅持続陽圧呼吸療法指導管理を受けている状態にある者'] = 52;
    $attached8Dtl['在宅自己疼痛管理指導管理を受けている状態にある者'] = 53;
    $attached8Dtl['在宅肺高血圧症患者指導管理を受けている状態にある者'] = 54;
    $attached8Dtl['人工肛門又は人口膀胱を設置している状態にある者'] = 55;
    $attached8Dtl['真皮を越える褥瘡の状態にある者'] = 56;
    $attached8Dtl['在宅患者訪問点滴注射管理指導料を算定している者'] = 57;

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['direction_start <='] = $lastDay;
    $where['direction_end >='] = $firstDay;
    $temp = select('doc_instruct', '*', $where);

    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        $f14 = "";
        if ($val['seriously_child'] === '超重症児') {
            $f14 = "1";
        } elseif ($val['seriously_child'] === '準超重症児') {
            $f14 = "2";
        }

        $f15 = "";
        if ($val['attached8'] = "該当する") {
            $f15 = "1";
        }

        $f16 = "";
        if ($f15 === "1") {
            $dtl = $val['attached8_detail'];
            foreach ($attached8Dtl as $tgtName => $code) {
                if (strpos($dtl, $tgtName) !== false) {
                    $f16 .= empty($f16) ? $code : "," . $code;
                }
            }
        }

        // レコード設定
        $dat = $def32;
        $dat['code'] = '32';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = trimStrWidth(formatDateTime($val['judgement_day'], 'Ymd'), 8, "");
        $dat['f3'] = null;
        $dat['f4'] = trimStrWidth($val['sickness1'], 50, "");
        $dat['f5'] = trimStrWidth($val['sickness2'], 50, "");
        $dat['f6'] = trimStrWidth($val['sickness3'], 50, "");
        $dat['f7'] = trimStrWidth($val['sickness4'], 50, "");
        $dat['f8'] = trimStrWidth($val['sickness5'], 50, "");
        $dat['f9'] = trimStrWidth($val['sickness6'], 50, "");
        $dat['f10'] = trimStrWidth($val['sickness7'], 50, "");
        $dat['f11'] = trimStrWidth($val['sickness8'], 50, "");
        $dat['f12'] = trimStrWidth($val['sickness9'], 50, "");
        $dat['f13'] = trimStrWidth($val['sickness10'], 50, "");
        $dat['f14'] = trimStrWidth($f14, 1, "");
        $dat['f15'] = trimStrWidth($f15, 1, "");
        $dat['f16'] = trimStrWidth($f16, 2, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- サービス開始終了情報(33)---------------------- */

    // 開始区分
    $startType['訪問開始'] = "0";
    $startType['継続利用'] = "1";
    $startType['保険変更'] = "2";
    $startType['入院'] = "3";

    // 訪問終了状況
    $cancelReason['軽快'] = "1";
    $cancelReason['施設'] = "2";
    $cancelReason['医療機関'] = "3";
    $cancelReason['死亡'] = "4";
    $cancelReason['その他'] = "5";

    // 死亡状況
    $deathPlace['自宅'] = "1";
    $deathPlace['施設'] = "2";
    $deathPlace['病院'] = "3";
    $deathPlace['診療所'] = "4";
    $deathPlace['その他'] = "5";

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['start_day <='] = $lastDay;
    $where['end_day >='] = $firstDay;
    $temp = select('mst_user_service', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        // 日付変換
        $val['start_day'] = $val['start_day'] === "0000-00-00" ? null : $val['start_day'];
        $val['end_day'] = $val['end_day'] === "0000-00-00" ? null : $val['end_day'];
        $val['death_day'] = $val['death_day'] === "0000-00-00" ? null : $val['death_day'];

        $f4 = isset($startType[$val['start_type']]) ? $startType[$val['start_type']] : "0";

        // 前ステーション訪問日
        $f5 = "";

        // 退院日
        $f6 = "";
        if ($val['start_type'] === '入院') {
            $f6 = formatDateTime($val['end_day'], 'Ymd');
        }

        // 訪問終了の状況
        $f7 = !isset($val['cancel_reason']) ? $cancelReason[$val['cancel_reason']] : "";
        $f8 = $val['death_day'];
        $f9 = !empty($val['death_day']) ? $val['death_time'] : "";
        if (!empty($val['death_place'])) {
            $f10 = $deathPlace[$val['death_place']];
        } else {
            $f10 = "";
        }
        // 死亡状況
        if ($f7 === 4) {
            $f8 = !empty($val['death_day']) ? formatDateTime($val['death_day'], 'Ymd') : "";
            $f9 = !empty($val['death_time']) ? formatDateTime($val['death_day'], 'Hi') : "";
            $f10 = !empty($val['death_place']) ? $deathPlace[$val['death_place']] : "";
        }

        // 死亡場所施設又はその他
        $f11 = "";
        if ($f10 === 2 || $f10 === 5) {
            $f11 = !empty($val['death_reason']) ? $val['death_reason'] : null;
        }

        // レコード設定
        $dat = $def33;
        $dat['code'] = '33';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = trimStrWidth(formatDateTime($val['start_day'], 'Ymd'), 8, "");
        $dat['f3'] = trimStrWidth(formatDateTime($val['end_day'], 'Ymd'), 8, "");
        $dat['f4'] = trimStrWidth($f4, 1, "");
        $dat['f5'] = trimStrWidth($f5, 31, "");
        $dat['f6'] = trimStrWidth($f6, 8, "");
        $dat['f7'] = trimStrWidth($f7, 2, "");
        $dat['f8'] = trimStrWidth($f8, 8, "");
        $dat['f9'] = trimStrWidth($f9, 4, "");
        $dat['f10'] = trimStrWidth($f10, 1, "");
        $dat['f11'] = trimStrWidth($f11, 20, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- 指示書情報(34) ------------------------------- */

    $careKb['一般'] = 1;
    $careKb['精神'] = 2;

    $directionKb['通常指示'] = 1;
    $directionKb['特別指示'] = 2;

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['direction_start <='] = $lastDay;
    $where['direction_end >='] = $firstDay;
    $temp = select('doc_instruct', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        // 日付変換
        $val['direction_start'] = $val['direction_start'] === "0000-00-00" ? null : $val['direction_start'];
        $val['direction_end'] = $val['direction_end'] === "0000-00-00" ? null : $val['direction_end'];
        $val['plan_day'] = $val['plan_day'] === "0000-00-00" ? null : $val['plan_day'];
        $val['report_day'] = $val['report_day'] === "0000-00-00" ? null : $val['report_day'];

        $f3 = isset($directionKb[$val['direction_kb']]) ? $directionKb[$val['direction_kb']] : "";
        $f6 = "";
        $f7 = "";
        $f9 = "";
        $f10 = "";
        $f11 = "";
        $f12 = "";
        $f13 = "";
        //        if ($directionKb[$val['direction_kb']] === 1) {
        if ($val['direction_kb'] === '通常指示') {

            $f10 = $val['other_station1_address'];
            $f11 = $val['other_station1'];
            $f12 = $val['other_station2_address'];
            $f13 = $val['other_station2'];
        }

        // レコード設定
        $dat = $def34;
        $dat['code'] = '34';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = trimStrWidth($careKb[$val['care_kb']], 1, "");
        $dat['f3'] = trimStrWidth($directionKb[$val['direction_kb']], 1, "");
        $dat['f4'] = trimStrWidth(formatDateTime($val['direction_start'], 'Ymd'), 8, "");
        $dat['f5'] = trimStrWidth(formatDateTime($val['direction_end'], 'Ymd'), 8, "");
        $dat['f6'] = trimStrWidth($val['hospital'], 32, "");
        $dat['f7'] = trimStrWidth($val['doctor'], 50, "");
        $dat['f8'] = trimStrWidth(formatDateTime($val['plan_day'], 'Ymd'), 8, "");
        $dat['f9'] = trimStrWidth($f9, 8, "");
        $dat['f10'] = trimStrWidth($f10, 72, "");
        $dat['f11'] = trimStrWidth($f11, 72, "");
        $dat['f12'] = trimStrWidth($f12, 72, "");
        $dat['f13'] = trimStrWidth($f13, 72, "");

        // 格納
        $idx++;
        $tgtData[34][$idx++] = $dat;
        $res[$idx] = $dat;
    }
    /* -- 情報提供情報(35) ----------------------------- */

    //    foreach ($temp as $val){
    //
    //        // 利用者
    //        $userId = $val['user_id'];
    //
    //        // レコード設定
    //        $dat = $def35;
    //        $dat['code']      = '35';
    //        $dat['user_name'] = $userList[$userId]['name'];
    //        $dat['f1']   = $userList[$userId]['other_id'];
    //        $dat['f2']   = NULL;
    //        $dat['f3']   = NULL;
    //        $dat['f4']   = NULL;
    //        $dat['f5']   = NULL;
    //
    //        // 格納
    //        $idx++;
    //        $res[$idx] = $dat;
    //    }
    /* -- 医療実績情報(36) ----------------------------- */

    $svcType['基本療養費Ⅰ'] = "01";
    $svcType['基本療養費Ⅱ'] = "02";
    $svcType['基本療養費Ⅲ'] = "03";
    $svcType['精神基本療養費Ⅰ'] = "04";
    $svcType['精神基本療養費Ⅱ'] = "05";
    $svcType['精神基本療養費Ⅲ'] = "06";
    $svcType['精神基本療養費Ⅳ'] = "07";
    $svcType['退院支援指導加算'] = "08";
    $svcType['緊急訪問看護加算'] = "09";
    $svcType['緊急訪問看護加算（精神）'] = "10";
    $svcType['ターミナルケア療養費'] = "11";
    $svcType['ターミナルケア療養費１'] = "11";
    $svcType['ターミナルケア療養費２'] = "12";
    $svcType['退院支援指導加算（長時間）'] = "13";
    $svcType['２４時間対応体制'] = "02";
    $svcType['特別管理加算'] = "03";
    $svcType['特別管理加算（重症度高）'] = "04";
    $svcType['退院時共同指導'] = "05";
    $svcType['特別管理指導加算'] = "06";
    $svcType['長時間訪問看護加算'] = "07";
    $svcType['長時間訪問看護加算（精神）'] = "08";
    $svcType['在宅患者連携指導加算'] = "09";
    $svcType['在宅患者緊急時等カンファレンス加算'] = "10";
    $svcType['乳幼児加算'] = "11";
    $svcType['複数名訪問看護加算（看護師等）'] = "13";
    $svcType['複数名訪問看護加算（准看護師）'] = "14";
    $svcType['複数名訪問看護加算（理学療法士等）'] = "15";
    $svcType['複数名訪問看護加算（看護補助者）'] = "16";
    $svcType['複数名訪問看護加算（看護師等（精神））'] = "17";
    $svcType['複数名訪問看護加算（准看護師（精神））'] = "18";
    $svcType['複数名訪問看護加算（作業療法士（精神））'] = "19";
    $svcType['複数名訪問看護加算（看護補助者（精神））'] = "20";
    $svcType['複数名訪問看護加算（精神保健福祉士）'] = "21";
    $svcType['夜間・早朝加算'] = "22";
    $svcType['深夜加算'] = "23";
    $svcType['夜間・早朝加算（精神）'] = "24";
    $svcType['深夜加算（精神）'] = "25";
    $svcType['精神重症患者支援管理連携加算１'] = "26";
    $svcType['精神重症患者支援管理連携加算２'] = "27";
    $svcType['看護・介護職員連携強化加算'] = "28";
    $svcType['退院支援指導加算'] = "29";
    $svcType['緊急訪問看護加算'] = "30";
    $svcType['緊急訪問看護加算（精神）'] = "31";
    $svcType['退院支援指導加算（長時間）'] = "32";
    $svcType['専門管理加算'] = "33";
    $svcType['遠隔死亡診断補助加算'] = "34";

    // 送信データ作成
    foreach ($recAddList as $userId => $recAddList2) {
        $f2 = 0;
        $f14 = $ofcList[$userId]['person_name'] ? $ofcList[$userId]['person_name'] : "";
        $f15 = $ofcList[$userId]['fax'] ? $ofcList[$userId]['fax'] : "";
        foreach ($recAddList2 as $svcId => $recAddList3) {
            $day = "";
            $f7Map = $defMapAry;
            $f8Map = $defMapAry;
            $f9Map = $defMapAry;
            $f10Map = $defMapAry;
            $f11Map = $defMapAry;
            $f12Map = $defMapAry;
            foreach ($recAddList3 as $uniqueId => $val) {

                /* -- 介護提供日情報(F10)作成 --------------------- */
                $tgtDay = $val['use_day'];
                $day = intval(formatDateTime($tgtDay, "d"));
                if (!$day) {
                    continue;
                }
                // 提供情報日に1加算する
                $f7Map[$day] = 1;
                $f8Map[$day] += 1;
                // 訪問した担当者の資格
                $f9Map[$day] = 2;
                $f10Map[$day] = 1;
                $f11Map[$day] = 1;
                $f12Map[$day] = 1;

                $f10Map[$day] = 1;

                /* -- 介護提供日情報(F11)作成 --------------------- */
                // 退院時共同指導の場合は回数を加算する
                if ($val['service_name'] === "退院時共同指導") {
                    $f11Map[$day] += 1;
                }
                // 看護小規模初期加算の場合は加減算で指定された日付範囲に1を立てる
                // 日割の場合は日割期間に1を立てる
                elseif ($val['service_name'] === "看護小規模初期加算" || mb_strpos($val['service_name'], "日割") !== false) {
                    $d1 = dateToDay($val['start_day']);
                    $d2 = dateToDay($val['end_day']);
                    if ($d1 < 1 || $d2 < 1) {
                        continue;
                    }
                    for ($i = $d1; $i <= $d2; $i++) {
                        $f11Map[$i] = 1;
                    }
                } else {
                    $f12Map[$day] = 1;
                }
            }

            // f2作成
            $f2++;
            // f3作成
            $f3 = "1";
            // 単純加算の判定
            $svcName = $recAddData[$svcId]['service_name'];
            if (mb_strpos($svcName, "加算") !== false) {
                $f3 = "2";
            }
            $f4 = isset($svcType[$svcName]) ? $svcType[$svcName] : "";

            $f5 = "";
            $f6 = "";
            if (mb_strpos($svcName, "ターミナルケア") !== false) {
                $f5 = "9999";
                $f6 = "9999";
            } else {
                // 日付変換
                $recAddData[$svcId]['start_day'] = $recAddData[$svcId]['start_day'] === "0000-00-00" ? null : $recAddData[$svcId]['start_day'];
                $recAddData[$svcId]['end_day'] = $recAddData[$svcId]['end_day'] === "0000-00-00" ? null : $recAddData[$svcId]['end_day'];

                $f5 = $recAddData[$svcId]['start_day'] ? str_replace(":", "", $recAddData[$svcId]['start_day']) : "";
                $f6 = $recAddData[$svcId]['end_day'] ? str_replace(":", "", $recAddData[$svcId]['end_day']) : "";
            }

            $svcCode = $recAddData[$svcId]['service_code'];
            $useDay = $recAddData[$svcId]['use_day'];
            $d = dateToDay($useDay, "d") ? sprintf("%02d", dateToDay($useDay, "d")) : "";
            $md = dateToDay($useDay, "md") ? sprintf("%04d", dateToDay($useDay, "md")) : "";
            $ymd = dateToDay($useDay, "Ymd") ? sprintf("%06d", dateToDay($useDay, "Ymd")) : "";

            $f7Data = arryMapToStr($f7Map);
            $f8Data = arryMapToStr($f8Map);
            $f9Data = arryMapToStr($f9Map);
            $f10Data = arryMapToStr($f10Map);
            $f11Data = arryMapToStr($f11Map);
            $f12Data = arryMapToStr($f12Map);
            $f13 = "";
            $f14 = "";
            $f15 = "";

            // レコード設定
            $dat = $def36;
            $dat['code'] = '36';
            $dat['user_name'] = $userList[$userId]['name'];
            $dat['f1'] = $userList[$userId]['other_id'];
            $dat['f2'] = trimStrWidth(sprintf('%d', $f2), 2, "");
            $dat['f3'] = trimStrWidth($f3, 1, "");
            $dat['f4'] = trimStrWidth($recAddData[$svcId]['service_code'], 2, "");
            $dat['f5'] = trimStrWidth($f5, 4, "");
            $dat['f6'] = trimStrWidth($f6, 4, "");
            $dat['f7'] = trimStrWidth($f7Data, 31, "");
            $dat['f8'] = trimStrWidth($f8Data, 31, "");
            $dat['f9'] = trimStrWidth($f9Data, 31, "");
            $dat['f10'] = trimStrWidth($f10Data, 31, "");
            $dat['f11'] = trimStrWidth($f11Data, 31, "");
            $dat['f12'] = trimStrWidth($f12Data, 31, "");
            $dat['f13'] = $f13;
            $dat['f14'] = $f14;
            $dat['f15'] = $f15;

            // 格納
            $idx++;
            $res[$idx] = $dat;
        }
    }

    /* -- 高額療養費情報(37) --------------------------- */

    //    // データ取得
    //    foreach ($temp as $val){
    //
    //        // 利用者
    //        $userId = $val['user_id'];
    //
    //        // レコード設定
    //        $dat = $def37;
    //        $dat['code']      = '37';
    //        $dat['user_name'] = $userList[$userId]['name'];
    //        $dat['f1']   = $userList[$userId]['other_id'];
    //        $dat['f2']   = NULL;
    //        $dat['f3']   = NULL;
    //        $dat['f4']   = NULL;
    //        $dat['f5']   = NULL;
    //
    //        // 格納
    //        $idx++;
    //        $res[$idx] = $dat;
    //    }
    /* -- 報告書情報(38) ------------------------------- */

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['validate_start >='] = $firstDay;
    $where['validate_end <='] = $lastDay;
    $temp = select('doc_report', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        // レコード設定
        $dat = $def38;
        $dat['code'] = '38';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = trimStrWidth(formatDateTime($val['report_day'], 'Ymd'), 8, "");
        $dat['f3'] = trimStrWidth(!empty($val['gaf_score']) ? $val['gaf_score'] : "", 3, "");
        $dat['f4'] = trimStrWidth(!empty($val['gaf_date'] && $val['gaf_date'] != "0000-00-00") ? formatDateTime($val['gaf_date'], 'Ymd') : "", 8, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- 実費利用料情報(41) --------------------------- */

    $subsidyType['控除対象外'] = 0;
    $subsidyType['控除対象'] = 1;

    $zeiType['非課税'] = 0;
    $zeiType['課税'] = 1;
    $zeiType['税込'] = 1;

    // データ取得
    $mstSvc = array();
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service', '*', $where);
    foreach ($temp as $val) {
        $typeNm = $val['type'];
        $mstSvc[$typeNm] = $val;
    }

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_record_id'] = $rcdAry;
    $temp = select('dat_user_record_jippi', '*', $where);
    foreach ($temp as $val) {

        // 日付変換
        $val['use_day'] = $val['use_day'] === "0000-00-00" ? null : $val['use_day'];

        // 利用者、利用日
        $rcdId = $val['user_record_id'];
        $tgtRcd = $recData[$rcdId];
        $userId = $recData[$rcdId]['user_id'];
        $tgtDay = $recData[$rcdId]['use_day'];
        $type   = $mstSvc['code'];
        $f4 =  substr($type, 0, 2);

        debug($f4);
        exit;
        // レコード設定
        $dat = $def41;
        $dat['code'] = '41';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = trimStrWidth(formatDateTime($tgtDay, 'd'), 2, "");
        $dat['f3'] = trimStrWidth($val['name'], 40, "");
        $dat['f4'] = $f4;
        $dat['f5'] = 2;
        $dat['f6'] = null;
        $dat['f7'] = null;
        $dat['f8'] = trimStrWidth($val['price'], 9, "");
        $dat['f9'] = trimStrWidth($val['zei_type'] ? $zeiType[$val['zei_type']] : null, 1, "");
        $dat['f10'] = trimStrWidth($val['rate'], 7, "");
        $dat['f11'] = trimStrWidth($val['subsidy'] ? $subsidyType[$val['subsidy']] : null, 1, "");

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- 返却 ----------------------------------------------------- */
    return $res;
}

// =======================================================================
// 日付分布データ作成関数
//  $dataArry       ：データ配列
//  $tgtColumnName  ：抽出項目
// =======================================================================
function monthMapConv($dataArry, $tgtColumnName)
{
    // マップデータ初期化
    $mapAry = array();
    for ($i = 0; $i <= 31; $i++) {
        $mapAry[$i] = "0";
    }
    // データ数分ループ
    foreach ($dataArry as $val) {
        // 対象データ抽出
        $date = $val[$tgtColumnName];
        if (empty($date)) {
            continue;
        }
        // ハイフン区切り3桁ではない場合は対象外
        $tgtAry = explode("-", $date);
        if (count($tgtAry) !== 3) {
            continue;
        }
        $day = $tgtAry[2];
        // 対象日に1を立てる
        $mapAry[intval($day)] = "1";
    }
    $result = "";
    // 文字列作成
    for ($i = 1; $i <= 31; $i++) {
        $result .= $mapAry[$i];
    }
    return $result;
}

// 日付配列を文字列に変換する
function arryMapToStr($mapAry)
{
    $result = "";
    // 文字列作成
    for ($i = 1; $i <= 31; $i++) {
        $result .= $mapAry[$i];
    }
    return $result;
}

function dateToDay($date)
{
    return intval(formatDateTime($date, "d"));
}
// コンダクト連携用終了日対応
function endDayCnv($endDay)
{
    //    if(empty($endDay){
    //
    //    }

}

// =======================================================================
// サービス内容が対象データとなるか判定する
// =======================================================================
function chkSvcType($serviceName, $type1, $type2)
{

    // 訪問看護、看多機両方指定されている場合は無条件にOK
    //    if (!empty($type1) && !empty($type2)) {
    //        return true;
    //    }
    // 訪問看護、看多機両方指定されてい場合はfalse
    if (empty($type1) && empty($type2)) {
        return false;
    }
    // サービス内容が設定されていない場合はfalse
    elseif (empty($serviceName)) {
        return false;
    }

    // サービス内容判定
    if (!empty($serviceName) && !empty($type1)) {
        if (mb_strpos($serviceName, '看多機') !== false || mb_strpos($serviceName, '看護小規模多機能') !== false) {
            return true;
        }
    }
    if (!empty($serviceName) && !empty($type2)) {
        if (mb_strpos($serviceName, '訪問看護') !== false || mb_strpos($serviceName, '訪看') !== false) {
            return true;
        }
    }
    return false;
}

// =======================================================================
// 集計CSVデータ取得
// =======================================================================
function getTotal($search)
{

    /* -- 初期処理 ----------------------------------------- */
    $res = array();

    /* -- 利用者情報 --------------------------------------- */
    if ($search['type'] === '利用者情報') {

        // 初期化
        $userIds          = array();
        $res['standard']  = array();
        $res['insure1']   = array();
        $res['insure3']   = array();
        $res['introduct'] = array();
        $res['family']    = array();

        // 利用者
        $plcId = $search['place'];
        $userList = getUserList($search['place'], $search);
        foreach ($userList as $val) {
            $userIds[] = $val['unique_id'];
        }

        // 基本情報
        if (isset($search['target']['standard'])) {
            $res['standard'] = $userList;
        }
        // 介護保険証情報
        if (isset($search['target']['insure1'])) {
            $where = array();
            $where['user_id'] = $userIds;
            $res['insure1'] = getData('mst_user_insure1', $where);
        }
        // 医療保険証情報
        if (isset($search['target']['insure3'])) {
            $where = array();
            $where['user_id'] = $userIds;
            $res['insure3'] = getData('mst_user_insure3', $where);
        }
        // 流入流出情報
        if (isset($search['target']['introduct'])) {
            $where = array();
            $where['user_id'] = $userIds;
            $res['introduct'] = getData('mst_user_introduct', $where);
        }
        // 連絡先情報
        if (isset($search['target']['family'])) {
            $where = array();
            $where['user_id'] = $userIds;
            $res['family'] = getData('mst_user_family', $where);
        }
    }
    /* -- 利用者スケジュール ------------------------------- */
    if ($search['type'] === '利用者スケジュール') {

        // 初期化
        $userIds          = array();
        $planIds          = array();
        $res['user_plan'] = array();
        $res['service']   = array();
        $res['add']       = array();
        $res['jippi']     = array();

        // 利用者
        $plcId = $search['place'];
        $userList = getUserList($search['place'], $search);
        foreach ($userList as $val) {
            $userIds[] = $val['unique_id'];
        }

        // 利用者スケジュール
        if (isset($search['target']['user_plan'])) {
            $where = array();
            $where['user_id'] = $userIds;
            if (!empty($search['start_day'])) {
                $where['user_day >='] = $search['start_day'];
            }
            if (!empty($search['end_day'])) {
                $where['user_day <='] = $search['end_day'];
            }
            $planData    = getData('dat_user_plan', $where);
            $res['user_plan'] = $planData;
            foreach ($planData as $val) {
                $planIds[] = $val['unique_id'];
            }
        }
        // 利用者スケジュール内訳
        if (isset($search['target']['service']) && $planIds) {
            $where = array();
            $where['user_plan_id'] = $planIds;
            $res['service'] = getData('dat_user_plan_service', $where);
        }
        // 加減算情報
        if (isset($search['target']['add']) && $planIds) {
            $where = array();
            $where['user_plan_id'] = $planIds;
            $res['add'] = getData('dat_user_plan_add', $where);
        }
        // 実費情報
        if (isset($search['target']['jippi']) && $planIds) {
            $where = array();
            $where['user_plan_id'] = $planIds;
            $res['jippi'] = getData('dat_user_plan_jippi', $where);
        }
    }
    /* -- 従業員スケジュール ------------------------------- */
    if ($search['type'] === '従業員スケジュール') {

        // 初期化
        $stfIds            = array();
        $res['staff_plan'] = array();

        // 従業員
        $plcId = $search['place'];
        $stfList = getStaffList($search['place']);
        foreach ($stfList as $val) {
            $stfIds[] = $val['unique_id'];
        }

        // 従業員スケジュール
        if (isset($search['target']['staff_plan'])) {
            $where = array();
            $where['staff_id'] = $stfIds;
            if (!empty($search['start_day'])) {
                $where['target_day >='] = $search['start_day'];
            }
            if (!empty($search['end_day'])) {
                $where['target_day <='] = $search['end_day'];
            }
            $planData    = getData('dat_staff_plan', $where);
            $res['staff_plan'] = $planData;
            foreach ($planData as $val) {
                $planIds[] = $val['unique_id'];
            }
        }
    }
    /* -- 返却 --------------------------------------------- */
    return $res;
}

// =======================================================================
// デバッグ関数
// =======================================================================
function debug($target = array())
{
    echo '<pre>';
    print_r($target);
    echo '</pre>';
}
