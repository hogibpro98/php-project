<?php
/* =======================================================================
 * 利用者情報取得関数
 * =======================================================================
 *   [引数]
 *     1.利用者ID
 *
 *   [戻り値]
 *     $res[standard]  '利用者基本情報'
 *     $res[office1]   '契約事業所'
 *     $res[office2]   '居宅支援事業所'
 *     $res[pay]       '支払方法'
 *     $res[insure1]   '介護保険証'
 *     $res[insure2]   '給付情報'
 *     $res[insure3]   '医療保険証'
 *     $res[insure4]   '公費'
 *     $res[medical]   '医療情報'
 *     $res[hospital]  '医療機関履歴'
 *     $res[drug]      '薬剤情報'
 *     $res[service]   'サービス情報'
 *     $res[emergency] '緊急連絡先'
 *     $res[person]    'キーパーソン'
 *     $res[family]    '家族情報'
 *     $res[introduct] '紹介機関'
 *     $res[image]     '画像'
 *
 * -----------------------------------------------------------------------
 */
function getUser($keyId = null)
{

    /* -- 初期処理 --------------------------------------------*/

    // 初期化
    $res = array();
    $res['standard']  = array();
    $res['office1']   = array();
    $res['office2']   = array();
    $res['pay']       = array();
    $res['insure1']   = array();
    $res['insure2']   = array();
    $res['insure3']   = array();
    $res['insure4']   = array();
    $res['medical']   = array();
    $res['hospital']  = array();
    $res['drug']      = array();
    $res['service']   = array();
    $res['emergency'] = array();
    $res['person']    = array();
    $res['family']    = array();
    $res['introduct'] = array();
    $res['image']     = array();

    // パラメータチェック
    if (!$keyId) {
        return $res;
    }

    /* -- データ取得 ------------------------------------------*/

    // 利用者基本情報
    $where = array();
    $where['unique_id']  = $keyId;
    $where['delete_flg'] = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user', '*', $where, $orderBy);
    $res['standard'] = isset($temp[0])
            ? $temp[0]
            : array();

    // 所属事業所
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_office1', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['office1'][$tgtId] = $val;
    }

    // 居宅支援事業所
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_office2', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['office2'][$tgtId] = $val;
    }

    // 支払方法
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_pay', '*', $where, $orderBy);
    $res['pay'] = isset($temp[0])
            ? $temp[0]
            : array();

    // 介護保険証
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_insure1', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['insure1'][$tgtId] = $val;
    }

    // 給付情報
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_insure2', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['insure2'][$tgtId] = $val;
    }

    // 医療保険証
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_insure3', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['insure3'][$tgtId] = $val;
    }

    // 公費
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_insure4', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['insure4'][$tgtId] = $val;
    }

    // 医療情報
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_medical', '*', $where, $orderBy);
    $res['medical'] = isset($temp[0])
            ? $temp[0]
            : array();

    // 医療機関情報
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_hospital', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['hospital'][$tgtId] = $val;
    }

    // 薬剤情報
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'start_day DESC,end_day DESC,unique_id DESC';
    $temp = select('mst_user_drug', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['drug'][$tgtId] = $val;
    }

    // サービス
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_service', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['service'][$tgtId] = $val;
    }

    // 緊急連絡先
    $where = array();
    $where['user_id'] = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id ASC';
    $temp = select('mst_user_emergency', '*', $where, $orderBy);
    $i = 0;
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        //        $res['emergency'][$tgtId] = $val;
        $res['emergency'][$i] = $val;
        $i++;
    }

    // キーパーソン
    $where = array();
    $where['user_id'] = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_person', '*', $where, $orderBy);
    $i = 0;
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        //        $res['person'][$tgtId] = $val;
        $res['person'][$i] = $val;
        $i++;
    }

    // 家族構成
    $where = array();
    $where['user_id'] = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_family', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['family'][$tgtId] = $val;
    }

    // 流入流出情報
    $where = array();
    $where['user_id'] = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_introduct', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['introduct'][$tgtId] = $val;
    }

    // 画像
    $where = array();
    $where['user_id'] = $keyId;
    $where['delete_flg']  = 0;
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_image', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $tgtId = $val['unique_id'];
        $res['image'][$tgtId] = $val;
    }

    /* -- データ返却 ------------------------------------------*/
    return $res;
}
/* =======================================================================
 * 利用者必須チェック関数
 * =======================================================================
 *   [引数]
 *     1.利用者情報
 *
 *   [戻り値]
 *     $err[xxxx] = TRUE or FALSE
 *
 * -----------------------------------------------------------------------
 */
function checkUser($userInfo)
{

    /* -- 初期処理 --------------------------------------------*/

    // 初期化
    $err = array();

    // パラメータチェック(新規の場合想定)
    if (!$userInfo) {
        return $err;
    }

    /* -- データ判定 ------------------------------------------*/

    // 基本情報タブ
    if (empty($userInfo['standard']['other_id'])
            || empty($userInfo['standard']['first_name'])
            || empty($userInfo['standard']['last_name'])
            || empty($userInfo['standard']['prefecture'])
            || empty($userInfo['standard']['area'])
            || empty($userInfo['standard']['address1'])
            || empty($userInfo['standard']['address2'])
            || empty($userInfo['standard']['post'])) {
        $err['tab1'] = true;
    }

    // 支払方法タブ
    if (empty($userInfo['pay']['method'])) {
        $err['tab2'] = true;
    } else {
        if (($userInfo['pay']['method'] !== '現金')
            && (empty($userInfo['pay']['bank_type'])
            || empty($userInfo['pay']['bank_code'])
            || empty($userInfo['pay']['bank_name'])
            || empty($userInfo['pay']['branch_code'])
            || empty($userInfo['pay']['branch_name'])
            || empty($userInfo['pay']['deposit_type'])
            || empty($userInfo['pay']['deposit_code'])
            || empty($userInfo['pay']['deposit_name']))) {
            $err['tab2'] = true;
        }
    }

    // 保険証タブ
    if (empty($userInfo['insure1'])
            || empty($userInfo['insure2'])
//            || empty($userInfo['insure3'])
//            || empty($userInfo['insure4'])
            || empty($userInfo['office2'])) {
        $err['tab3'] = true;
    }
    foreach ($userInfo['insure1'] as $val) {
        if (empty($val['insure_no'])
                || empty($val['start_day1'])
                || empty($val['start_day2'])
                || empty($val['insure_no'])
                || empty($val['insured_no'])
                || empty($val['care_rank'])) {
            $err['tab3'] = true;
            $err['insure1'][$val['unique_id']] = true;
        }
    }
    foreach ($userInfo['insure2'] as $val) {
        if (empty($val['start_day'])
                || empty($val['rate'])) {
            $err['tab3'] = true;
            $err['insure2'][$val['unique_id']] = true;
        }
    }
    foreach ($userInfo['office2'] as $val) {
        if (empty($val['start_day'])
                || empty($val['office_code'])
                || empty($val['office_name'])
                || empty($val['tel'])
                || empty($val['fax'])
                || empty($val['person_name'])
                || empty($val['plan_type'])) {
            $err['tab3'] = true;
            $err['office2'][$val['unique_id']] = true;
        }
    }

    // 医療情報タブ
    if (empty($userInfo['hospital'])) {
        $err['tab4'] = true;
    }
    foreach ($userInfo['hospital'] as $val) {
        if (empty($val['start_day'])
                || empty($val['name'])
                || empty($val['doctor'])
                || empty($val['select1'])) {
            $err['tab4'] = true;
            $err['hospital'][$val['unique_id']] = true;
        }
    }

    if (empty($userInfo['emergency'][0]['kana'])
        || empty($userInfo['emergency'][0]['tel1'])) {
        $err['tab5'] = true;
    }

    // 流入流出情報タブ
    // チェックしない

    /* -- データ返却 ------------------------------------------*/
    return $err;
}
/* =======================================================================
 * 空データ削除関数
 * =======================================================================
 *   [引数]
 *     1.upData
 *     2.階層
 *
 *   [戻り値]
 *     upData
 *
 * -----------------------------------------------------------------------
 */
function formatDelAry($table = null, $ary = array(), $type = 1)
{

    // 初期化
    $res = array();

    // 空NULL変換
    $ary = setNull($ary, $type);

    // テーブル別 判定対象外カラム
    $ngAry = getNgAry();

    // 1階層
    if ($type == 1) {
        $delFlg = true;
        foreach ($ary as $key => $val) {
            if (!isset($ngAry[$table][$key]) && $val) {
                $delFlg = false;
            }
        }
        if (!$delFlg) {
            $res = $ary;
        }
    }

    // 2階層
    if ($type == 2) {
        foreach ($ary as $seq => $ary2) {
            $delFlg = true;
            foreach ($ary2 as $key => $val) {
                if (!isset($ngAry[$table][$key]) && $val) {
                    $delFlg = false;
                }
            }
            if (!$delFlg) {
                $res[$seq] = $ary2;
            }
        }
    }

    // 返却
    return $res;
}

function getNgAry()
{
    $ngAry['mst_user']['household_type']          = true;
    $ngAry['mst_user']['service_type']            = true;
    $ngAry['mst_user']['household_type']          = true;
    $ngAry['mst_user']['bath_type']               = true;
    $ngAry['mst_user']['excretion_type']          = true;
    $ngAry['mst_user']['meal_memo']               = true;
    $ngAry['mst_user_pay']['user_id']             = true;
    $ngAry['mst_user_pay']['method']              = true;
    $ngAry['mst_user_pay']['bank_type']           = true;
    $ngAry['mst_user_pay']['deposit_type']        = true;
    $ngAry['mst_user_office1']['user_id']         = true;
    $ngAry['mst_user_office2']['user_id']         = true;
    //    $ngAry['mst_user_office2']['plan_type']       = TRUE;
    //    $ngAry['mst_user_office2']['cancel_type']     = TRUE;
    $ngAry['mst_user_insure1']['user_id']         = true;
    $ngAry['mst_user_insure2']['user_id']         = true;
    $ngAry['mst_user_insure3']['user_id']         = true;
    $ngAry['mst_user_insure3']['type1']           = true;
    $ngAry['mst_user_insure3']['type2']           = true;
    $ngAry['mst_user_insure3']['type3']           = true;
    $ngAry['mst_user_insure3']['type4']           = true;
    //$ngAry['mst_user_insure4']['user_id']         = TRUE;
    $ngAry['mst_user_hospital']['user_id']        = true;
    $ngAry['mst_user_drug']['user_id']            = true;
    $ngAry['mst_user_drug']['drug_usage']         = true;
    $ngAry['mst_user_hospital']['type1']          = true;
    $ngAry['mst_user_medical']['user_id']         = true;
    $ngAry['mst_user_service']['user_id']         = true;
    $ngAry['mst_user_service']['start_type']      = true;
    $ngAry['mst_user_service']['cancel_reason']   = true;
    $ngAry['mst_user_service']['death_place']     = true;
    $ngAry['mst_user_emergency']['user_id']       = true;
    $ngAry['mst_user_emergency']['together']      = true;
    $ngAry['mst_user_emergency']['relation_type'] = true;
    $ngAry['mst_user_emergency']['prefecture']    = true;
    $ngAry['mst_user_emergency']['area']          = true;
    $ngAry['mst_user_person']['user_id']          = true;
    $ngAry['mst_user_family']['user_id']          = true;
    $ngAry['mst_user_family']['type']             = true;
    $ngAry['mst_user_introduct']['user_id']       = true;
    $ngAry['mst_user_image']['user_id']           = true;

    return $ngAry;
}
