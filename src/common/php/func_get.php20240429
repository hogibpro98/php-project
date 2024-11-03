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

function getData($table, $search = array(), $orderBy = 'unique_id ASC', $limit = NULL) {

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

function getMultiData($table, $tables, $target = NULL, $where = array(),
    $joinCol = array(), $orderBy = NULL, $limit = NULL) {

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
    $res = multiSelect($table, $tables, $target, $where, $joinWhere, $orderBy);

    /* -- データ返却 ------------------------------------------ */
    return $res;
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

function getCode($keyGroup = NULL, $keyType = NULL) {

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
    $limit = NULL;
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

function getUserList($placeId = NULL, $search = array(), $orderBy = 'unique_id ASC') {

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

        if($val['end_day'] === '0000-00-00'){
            $val['end_day'] = '2100-12-31';
        }
        
        // 契約中／停止中判定
//        $search['status'] = !empty($search['status']) ? $search['status'] : '契約中';
        if (!empty($search['status'])){
            
            if ($search['status'] === '契約中'){
                if ($val['start_day'] > $search['end_day']){
                    continue;
                }
                if (!empty($val['end_day']) && $val['end_day'] < $search['start_day']){
                    continue;
                }
            }
            
            if ($search['status'] === '停止中'){
                if ($val['start_day'] < $search['end_day'] 
                    && !empty($val['end_day']) 
                    && $val['end_day'] > $search['start_day']){
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
        $val['name_kana'] = $val['last_kana'] . ' ' . $val['first_kana'];
        $res[$tgtId] = $val;
    }

// 返却
    return $res;
}

/* =======================================================================
 * 利用者情報取得
 * =======================================================================
 */

function getUserInfo($userId, $multi = NULL) {

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
            $val['user_kana'] = $val['last_kana'] . ' ' . $val['first_kana'];
            $res[$temp['unique_id']] = $val;
        }
    } else {
        if (isset($temp[0])) {
            $res = $temp[0];
            $res['user_name'] = $res['last_name'] . ' ' . $res['first_name'];
            $res['user_kana'] = $res['last_kana'] . ' ' . $res['first_kana'];
        }
    }

// 返却
    return $res;
}

/* =======================================================================
 * スタッフリスト取得
 * =======================================================================
 */

function getStaffList($placeId = NULL) {

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
        $tgtOfc[$tgtId] = TRUE;
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
        if (isset($tgtOfc[$ofc1]) || isset($tgtOfc[$ofc2])){
            $tgtId = $val['staff_id'];
            $tgtStaff[] = $tgtId;
        }
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

function getCareRank($userId, $tgtDay = TODAY) {

// 初期化
    $res = NULL;

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

function getStaffName($staffId) {

    // 初期化
    $res = NULL;

    // スタッフマスタの情報取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $staffId;
    $target = 'last_name, first_name';
    $temp = select('mst_staff', $target, $where);

    // 値を格納
    $res = $temp ? $temp[0]['last_name'] . $temp[0]['first_name'] : NULL;

    // データ返却
    return $res;
}

/* =======================================================================
 * スタッフコード取得
 * =======================================================================
 */

function getStaffCode($staffId) {

    // 初期化
    $res = NULL;

    // スタッフマスタの情報取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $staffId;
    $target = 'staff_id';
    $temp = select('mst_staff', $target, $where);

    // 値を格納
    $res = $temp ? $temp[0]['staff_id'] : NULL;

    // データ返却
    return $res;
}

/* =======================================================================
 * 事業所リスト作成
 * =======================================================================
 */

function getOfficeList($placeId = NULL, $start = TODAY, $end = TODAY) {

// 初期化
    $res = NULL;
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
        if($val['end_day'] &&  $val['end_day'] <= $end ){
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

function getOfficeName($tgtId, $tgtDay = TODAY, $type = NULL) {

// 初期化
    $res = NULL;

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
            
            if ($val['end_day']){
                if ($val['end_day'] < $tgtDay){
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

function getPlaceList() {

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

function getPlaceInfo($placeId = NULL) {

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

function getServiceConfig($svcId) {

    // 初期化
    $res['code'] = NULL;
    $res['name'] = NULL;
    
    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['unique_id'] = $svcId;
    $target = 'type,code,name';
    $orderBy = 'unique_id ASC';
    $temp = select('mst_service', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $res['type'] = $val['type'];
        $res['code'] = $val['code'];
        $res['name'] = $val['name'];
    }
    
    // データ返却
    return $res;
}

// =======================================================================
// サービス内容が対象データとなるか判定する
// =======================================================================
function chkSvcType($serviceName, $type1, $type2) {

    // 訪問看護、看多機両方指定されている場合は無条件にOK
//    if (!empty($type1) && !empty($type2)) {
//        return true;
//    }
    // 訪問看護、看多機両方指定されてい場合はfalse
    if (empty($type1) && empty($type2)) {
        return false;
    }
    // サービス内容が設定されていない場合はfalse
    else if (empty($serviceName)) {
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
function getTotal($search) {
    
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
        $userList = getUserList($search['place_id'], $search);
        foreach ($userList as $val){
            $userIds[] = $val['unique_id'];
        }
        
        // 基本情報
        if (isset($search['target']['standard'])){
            $res['standard'] = $userList;
        }
        // 介護保険証情報
        if (isset($search['target']['insure1'])){
            $where = array();
            $where['user_id'] = $userIds;
            $res['insure1'] = getData('mst_user_insure1',$where);
        }
        // 医療保険証情報
        if (isset($search['target']['insure3'])){
            $where = array();
            $where['user_id'] = $userIds;
            $res['insure3'] = getData('mst_user_insure3',$where);
        }
        // 流入流出情報
        if (isset($search['target']['introduct'])){
            $where = array();
            $where['user_id'] = $userIds;
            $res['introduct'] = getData('mst_user_introduct',$where);
        }
        // 連絡先情報
        if (isset($search['target']['family'])){
            $where = array();
            $where['user_id'] = $userIds;
            $res['family'] = getData('mst_user_family',$where);
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
        $userList = getUserList($search['place_id'], $search);
        foreach ($userList as $val){
            $userIds[] = $val['unique_id'];
        }
        
        // 利用者スケジュール
        if (isset($search['target']['user_plan'])){
            $where = array();
            $where['user_id'] = $userIds;
            if (!empty($search['start_day'])){
                $where['use_day >='] = $search['start_day'];
            }
            if (!empty($search['end_day'])){
                $where['use_day <='] = $search['end_day'];
            }
            $planData    = getData('dat_user_plan',$where);
            $res['user_plan'] = $planData;
            foreach ($planData as $val){
                $planIds[] = $val['unique_id'];
            }
        }
        // 利用者スケジュール内訳
        if (isset($search['target']['service']) && $planIds){
            $where = array();
            $where['user_plan_id'] = $planIds;
            $res['service'] = getData('dat_user_plan_service',$where);
        }
        // 加減算情報
        if (isset($search['target']['add']) && $planIds){
            $where = array();
            $where['user_plan_id'] = $planIds;
            $res['add'] = getData('dat_user_plan_add',$where);
        }
        // 実費情報
        if (isset($search['target']['jippi']) && $planIds){
            $where = array();
            $where['user_plan_id'] = $planIds;
            $res['jippi'] = getData('dat_user_plan_jippi',$where);
        }
    }
    /* -- 従業員スケジュール ------------------------------- */
    if ($search['type'] === '従業員スケジュール') {
        
        // 初期化
        $stfIds            = array();
        $res['staff_plan'] = array();
        
        // 従業員
        $plcId = $search['place_id'];
        $stfList = getStaffList($search['place_id']);
        foreach ($stfList as $val){
            $stfIds[] = $val['unique_id'];
        }
        
        // 従業員スケジュール
        if (isset($search['target']['staff_plan'])){
            $where = array();
            $where['staff_id'] = $stfIds;
            if (!empty($search['start_day'])){
                $where['target_day >='] = $search['start_day'];
            }
            if (!empty($search['end_day'])){
                $where['target_day <='] = $search['end_day'];
            }
            $planData    = getData('dat_staff_plan',$where);
            
            $res['staff_plan'] = $planData;
            foreach ($planData as $val){
                $planIds[] = $val['unique_id'];
            }
        }
    }
    
    /* -- 返却 --------------------------------------------- */
    return $res;
}

// 前回体重取得関数
// 1:前回体重、2:前月体重、3:前々月体重
function getPastWeight($userId=null, $keyId=null){

    // 初期化
    $res    = array();
    $res[1] = null;
    $res[2] = null;
    $res[3] = null;
    
    // パラメータチェック
    if (!$userId){
        return $res;
    }
    
    // KEY日付、KEY月
    $keyDay   = TODAY;
    $keyMonth = THISMONTH;
    if ($keyId){
        $where = array();
        $where['unique_id'] = $keyId;
        $target = 'unique_id,service_day';
        $keyData = select('doc_kantaki', $target, $where);
        if ($keyData['service_day']){
            $keyDay   = $keyData['service_day'];
            $keyMonth = formatDateTime($keyData['service_day'],'Y-m');
        }
    }
    
    // 前月、前々月
    $dt = new DateTime($keyDay);
    $month1 = $dt->modify('first day of -1 month')->format('Y-m');
    $dt = new DateTime($keyDay);
    $month2 = $dt->modify('first day of -2 month')->format('Y-m');
    
    // 過去データの取得
    $search = array();
    $search['user_id']        = $userId;
    $search['service_day <='] = $keyDay;
    $target  = 'service_day,body_weight';
    $orderBy = 'service_day DESC';
    $temp = select('doc_kantaki', $target, $search, $orderBy);
    foreach ($temp as $val){
        
        // 1:前回体重
        if (!$res[1]){
            $res[1] = $val['body_weight'];
        }
        // 2:前月体重
        if (!$res[2] && mb_strpos($val['service_day'], $month1) !== false){
            $res[2] = $val['body_weight'];
        }
        // 3:前々月体重
        if (!$res[3] && mb_strpos($val['service_day'], $month2) !== false){
            $res[3] = $val['body_weight'];
        }
    }

    // データ返却
    return $res;
}

// =======================================================================
// デバッグ関数
// =======================================================================
function debug($target = array()) {
    echo '<pre>';
    print_r($target);
    echo '</pre>';
}

?>