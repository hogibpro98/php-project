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
function getUserAry($keyId = array(), $orderByUser = 'unique_id ASC')
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
    $temp = select('mst_user', '*', $where, $orderByUser);
    foreach ($temp as $val) {
        $userId = $val['unique_id'];
        $res[$userId]['standard'] = $val;
    }

    // 所属事業所
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $where['start_day <='] = TODAY;
    //    $where['end_day >']   = TODAY;
    $target  = 'unique_id, user_id, start_day, end_day';
    $orderBy  = 'unique_id DESC';
    $temp = select('mst_user_office1', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $tgtId  = $val['unique_id'];

        // 終了日が設定されている場合のみ判定する
        if (!empty($val['end_day'])) {
            if ($val['end_day'] < TODAY) {
                continue;
            }
        }
        $res[$userId]['office1'][$tgtId] = $val;
    }

    // 居宅支援事業所
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $target  = 'unique_id, user_id, start_day, office_code';
    $target .= ', office_name, address, tel, fax, found_day';
    $target .= ', person_name, person_kana, plan_type';
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_office2', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $tgtId = $val['unique_id'];
        $res[$userId]['office2'][$tgtId] = $val;
    }

    // 支払方法
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $target  = 'unique_id, user_id, method, bank_type';
    $target .= ', bank_code, bank_name, branch_code, branch_name';
    $target .= ', deposit_type, deposit_code, deposit_name';
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_pay', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $res[$userId]['pay'] = $val;
    }

    // 介護保険証
    $where = array();
    $where['user_id']       = $keyId;
    $where['delete_flg']    = 0;
    $where['start_day1 <='] = TODAY;
    $where['end_day1 >']    = TODAY;
    $target  = 'unique_id, user_id, insure_no';
    $target .= ', start_day1, end_day1, start_day2, end_day2';
    $target .= ', insured_no, care_rank';
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_insure1', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $tgtId = $val['unique_id'];
        $res[$userId]['insure1'][$tgtId] = $val;
    }

    // 給付情報
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $target  = 'unique_id, user_id, start_day, end_day, rate';
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_insure2', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $tgtId = $val['unique_id'];
        $res[$userId]['insure2'][$tgtId] = $val;
    }

    // 医療保険証
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $target  = 'unique_id, user_id, start_day, name';
    $target .= ', type1, type2, type3, type4';
    $target .= ', number1, number2, number3, number4, number5';
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_insure3', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $tgtId = $val['unique_id'];
        $res[$userId]['insure3'][$tgtId] = $val;
    }

    // 公費
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $target  = 'unique_id, user_id, start_day, end_day';
    $target .= ', number2, number3, upper_limit, rate';
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_insure4', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $tgtId = $val['unique_id'];
        $res[$userId]['insure4'][$tgtId] = $val;
    }

    // 医療機関情報
    $where = array();
    $where['user_id']     = $keyId;
    $where['delete_flg']  = 0;
    $target  = 'unique_id, user_id, start_day, end_day';
    $target .= ', name, doctor, select1';
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_hospital', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $tgtId = $val['unique_id'];
        $res[$userId]['hospital'][$tgtId] = $val;
    }

    // 緊急連絡先
    $where = array();
    $where['user_id'] = $keyId;
    $where['delete_flg']  = 0;
    $target  = 'unique_id, user_id, name, kana';
    $target .= ', name, kana, tel1, post';
    $target .= ', prefecture, area, address1, address2';
    $orderBy = 'unique_id ASC';
    $temp = select('mst_user_emergency', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $res[$userId]['emergency'][] = $val;
    }

    // キーパーソン
    $where = array();
    $where['user_id'] = $keyId;
    $where['delete_flg']  = 0;
    $target  = 'unique_id, user_id, name, kana';
    $target .= ', relation, tel';
    $orderBy = 'unique_id DESC';
    $temp = select('mst_user_person', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $userId = $val['user_id'];
        $res[$userId]['person'][] = $val;
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
function checkUserList($userInfo)
{

    /* -- 初期処理 --------------------------------------------*/

    // 初期化
    $res = array();

    // パラメータチェック(新規の場合想定)
    if (!$userInfo) {
        return $res;
    }

    /* -- データ判定 ------------------------------------------*/

    // 基本情報タブ
    if (isset($userInfo['standard'])) {
        if (empty($userInfo['standard']['other_id'])
                || empty($userInfo['standard']['first_name'])
                || empty($userInfo['standard']['last_name'])
                || empty($userInfo['standard']['prefecture'])
                || empty($userInfo['standard']['area'])
                || empty($userInfo['standard']['address1'])
                || empty($userInfo['standard']['address2'])
                || empty($userInfo['standard']['post'])) {
            $res[1] = '基本情報の入力が不足しています';
        }
    }
    // 支払方法タブ
    if (isset($userInfo['pay'])) {
        if (empty($userInfo['pay']['method'])) {
            $res[2] = '支払方法の入力が不足しています';
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
                $res[2] = '支払方法の入力が不足しています';
            }
        }
    }

    // 保険証タブ
    if (isset($userInfo['insure1'])) {
        if (empty($userInfo['insure1'])
                || empty($userInfo['insure2'])
    //            || empty($userInfo['insure3'])
    //            || empty($userInfo['insure4'])
                || empty($userInfo['office2'])) {
            $res[3] = '保険証の入力が不足しています';

            foreach ($userInfo['insure1'] as $val) {
                if (empty($val['insure_no'])
                        || empty($val['start_day1'])
                        || empty($val['start_day2'])
                        || empty($val['insure_no'])
                        || empty($val['insured_no'])
                        || empty($val['care_rank'])) {
                    $res[3] = '保険証の入力が不足しています';
                }
            }
        }
    }

    if (isset($userInfo['insure2'])) {
        foreach ($userInfo['insure2'] as $val) {
            if (empty($val['start_day'])
                    || empty($val['rate'])) {
                $res[3] = '保険証の入力が不足しています';
            }
        }
    }

    if (isset($userInfo['office2'])) {
        foreach ($userInfo['office2'] as $val) {
            if (empty($val['start_day'])
                    || empty($val['office_code'])
                    || empty($val['office_name'])
                    || empty($val['tel'])
                    || empty($val['fax'])
                    || empty($val['person_name'])
                    || empty($val['plan_type'])) {
                $res[3] = '保険証の入力が不足しています';
            }
        }
    }

    // 医療情報タブ
    if (empty($userInfo['hospital'])) {
        $res[4] = '医療情報の入力が不足しています';
    }
    $userInfo['hospital'] = isset($userInfo['hospital']) ? $userInfo['hospital'] : array();
    foreach ($userInfo['hospital'] as $val) {
        if (empty($val['start_day'])
                || empty($val['name'])
                || empty($val['doctor'])
                || empty($val['select1'])) {
            $res[4] = '医療情報の入力が不足しています';
        }
    }

    // 緊急連絡先
    if (isset($userInfo['emergency'])) {
        if (empty($userInfo['emergency'][0]['kana'])
            || empty($userInfo['emergency'][0]['tel1'])) {
            $res[5] = '緊急連絡先の入力が不足しています';
        }
    }
    // 流入流出情報タブ
    // チェックしない

    /* -- データ返却 ------------------------------------------*/
    return $res;
}
