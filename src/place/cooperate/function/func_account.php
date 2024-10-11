<?php
//=====================================================================
// 連携データ作成
//=====================================================================
try {
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
require_once($_SERVER['DOCUMENT_ROOT'].'/user/list/function/func_user.php');

function getAccount($placeId = NULL, $month, $type1 = NULL, $type2 = NULL, $tgtUser = NULL) {

    /* -- 初期処理 --------------------------------------------------- */
    $res = array();
    $userAry = array();
    $firstDay = "";
    $idx = 0;
    $typeList = array();
   
    // 設定情報取得
    $config = getAccConfig();
    
    // 項目初期化データ取得
    $defData = initAccount($config);
    
    // タイプリスト読込
    $typeList = getAccType();
    
    // 検索条件(サービス利用区分)
    if (!$type1 && !$type2){
        $type1 = true;
        $type2 = true;
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
    foreach ($userList as $userId => $val) {

        // 初期化
        $flg = FALSE;

        // 利用者判定
        if ($tgtUser && $userId !== $tgtUser) {
            $flg = TRUE;
        }
//        // 事業所種別・看多機判定
//        $type = $val['service_type'];
//        if ((mb_strpos($type, '看多機　訪問介護') !== false 
//            || mb_strpos($type, '看多機　訪問看護') !== false
//            || mb_strpos($type, '看多機　通い') !== false
//            ) && !empty($type1)) {
//            $flg = FALSE;
//        }else{
//            $flg = TRUE;
//        }
//
//        // 事業所種別・訪問看護判定
//        if ((mb_strpos($type, '訪問看護　医療保険') !== false 
//            || mb_strpos($type, '訪問看護　介護保険') !== false
//            || mb_strpos($type, '定期巡回') !== false
//            ) && !empty($type2)) {
//            $flg = FALSE;
//        }else{
//            $flg = TRUE;
//        }
        
        // NG判定
        if ($flg) {
            unset($userList[$userId]);
            continue;
        }
        
        // サービス区分判定
        $serviceDiv = "";
        if(mb_strpos($val['service_type'], '医療') !== false){
            $serviceDiv = "2";
        }else{
            $serviceDiv = "1";
        }
        $userList[$userId]['service_div'] = $serviceDiv;

//        // 検索対象利用者
//        $userAry[] = $userId;
    }

    if (empty($userList)) {
        return $res;
    }
    
    $userList2 = getUserAry($userAry);    
    foreach($userList2 as $usrId2 => $users){
        // ngUserList
        $ngUserList = checkUserList($users);
        if($ngUserList){
            if(isset($userList[$usrId2])){
                unset($userList[$usrId2]);
            }
        }
    }
    
    foreach($userList as $userId2 => $dummy){
        // 検索対象利用者
        $userAry[] = $userId2;
    }
    

    $idx = 0;

    /* -- データ格納 ------------------------------------------------- */

    /* -- ヘッダー(00) --------------------------------- */
    $dat = $defData['00'];
    $dat['code'] = '00';
    $dat['user_name'] = NULL;
    $dat['f1'] = $firstDay;

    $cd = $dat['code'];
    $tgtData[$cd][$idx] = $dat;    

    /* -- 利用者基本情報(11) --------------------------- */
    foreach ($userList as $userId => $val) {

        // レコード設定
        $dat = $defData['11'];
        $dat['code'] = '11';
        $dat['user_name'] = $val['name'];
        $dat['f1'] = $val['other_id'];
        $dat['f2'] = $val['last_kana'] . $val['first_kana'];
        $dat['f3'] = $val['last_name'] . $val['first_name'];
        $dat['f4'] = $val['birthday'];
        $dat['f5'] = getTypeCode($val['sex'],$typeList['11-5']);
        $dat['f6'] = $val['prefecture'] . $val['area'] . $val['address1'] . $val['address2'] . $val['address3'];
        $dat['f7'] = NULL;

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    
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
        
        // 公費名称
        $ins3Name = isset($ins3List[$userId]['name']) ? $ins3List[$userId]['name'] : '';
        
        // レコード設定
        $dat = $defData['12'];
        $dat['code'] = '12';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = isset($userList[$userId]['service_div']) ? $userList[$userId]['service_div'] : NULL; 
        $dat['f3'] = $val['start_day'];
        $dat['f4'] = $val['end_day'];
        $dat['f5'] = $val['number2'];
        $dat['f6'] = $val['number3'];
        $dat['f7'] = $val['number1'];
        $dat['f8'] = $val['name'];
        $dat['f9'] = $val['upper_limit'];

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    
    }
    /* -- 利用者金融期間情報(13) ----------------------- */

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $temp = select('mst_user_pay', '*', $where);
    foreach ($temp as $val) {
        
        // 支払方法未設定は除外
        if(empty($val['method'])){
            continue;
        }
        
        // 支払方法が振替または引き落とし以外は除外
        if($val['method'] == '振込' || $val['method'] == '現金')
        {
            continue;
        }
        
        // 利用者
        $userId = $val['user_id'];

        // レコード設定
        $dat = $defData['13'];
        $dat['code'] = '13';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = getTypeCode($val['bank_type'], $typeList['13-2']);
        $dat['f3'] = $val['bank_code'];
        $dat['f4'] = $val['bank_name'];
        $dat['f5'] = $val['branch_code'];
        $dat['f6'] = $val['branch_name'];
        $dat['f7'] = getTypeCode($val['deposit_type'], $typeList['13-7']);
        $dat['f8'] = $val['deposit_code'];
        $dat['f9'] = $val['deposit_name'];
//        $dat['f10'] = getTypeCode($val['method'], $typeList['13-10']);
        
        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    
    }

    /* -- 介護保険証情報(21) --------------------------- */

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['start_day1 <='] = $lastDay;
    $where['end_day1 >='] = $firstDay;
    $temp = select('mst_user_insure1', '*', $where);
    foreach ($temp as $val) {
        
        if($val['care_rank'] == '自立' || empty($val['care_rank'])){
            continue;
        }
        
        if(empty($val['start_day1']) 
            || empty($val['end_day1'])
            || empty($val['insure_no'])
            || empty($val['insured_no'])
            || empty($val['start_day2'])
            || empty($val['end_day2'])){
            continue;
        }

        // 利用者
        $userId = $val['user_id'];
        
        // レコード設定
        $dat = $defData['21'];
        $dat['code'] = '21';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = $val['start_day1'];
        $dat['f3'] = $val['end_day1'];
        $dat['f4'] = sprintf("%08s", $val['insure_no']);
        $dat['f5'] = sprintf("%08s", $val['insured_no']);
        $dat['f6'] = getTypeCode($val['care_rank'], $typeList['21-6']);
        $dat['f7'] = $val['start_day2'];
        $dat['f8'] = $val['end_day2'];

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    
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

        // 給付率
        $rate = "00";
        $rate = sprintf('%02d',$val['rate']);
            
        // レコード設定
        $dat = $defData['22'];
        $dat['code'] = '22';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = $val['start_day'];
        $dat['f3'] = $val['end_day'];
        $dat['f4'] = $rate;

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    
    }
    /* -- 居宅サービス計画情報(23) --------------------- */

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

        // レコード設定
        $dat = $defData['23'];
        $dat['code'] = '23';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = $val['start_day'];
        $dat['f3'] = $val['end_day'];
        $dat['f4'] = getTypeCode($val['cancel_type'], $typeList['23-4']);

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    
    }

    /* -- 居宅介護支援事業者情報(24) ------------------- */
    // データ取得
    //    $where = array();
    //    $where['delete_flg']   = 0;
    //    $where['user_id']      = $userAry;
    //    $where['start_day <='] = $lastDay;
    //    $where['end_day >=']   = $firstDay;
    //    $temp = select('mst_user_office2', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        // レコード設定
        $dat = $defData['24'];
        $dat['code'] = '24';
        $dat['user_name'] = $userList[$userId]['name'];
        $dat['f1'] = $userList[$userId]['other_id'];
        $dat['f2'] = getTypeCode($val['plan_type'], $typeList['24-2']);
        $dat['f3'] = $val['office_code'];
        $dat['f4'] = $val['office_name'];

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    
    }

    /* -- 介護実績情報(25) ----------------------------- */
    $defMapAry = array();
    for ($i = 0; $i <= 31; $i++) {
        $defMapAry[$i] = 0;
    }

    // 実績親データ
    $rcdAry = NULL;
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
        
        // 看多機チェックボックス
        if(empty($type1)){
            if($val['service_name'] == '看多機 訪問介護'
                || $val['service_name'] == '看多機 訪問看護'
                || $val['service_name'] == '看多機 通い'
                || $val['service_name'] == '看多機 宿泊'){
             continue;
            }
        }
        
        // 訪問看護チェックボックス
        if(empty($type2)){
            if($val['service_name'] == '訪問看護 医療保険'
                || $val['service_name'] == '訪問看護 介護保険'
                || $val['service_name'] == '定期巡回'){
             continue;   
            }
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
    $rcdAddAry = NULL;
    $recAddList = array();
    $recAddData = array();
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_record_id'] = $rcdAry;
    $rcdAddTemp = select('dat_user_record_add', '*', $where);
    foreach ($rcdAddTemp as $val) {
        if (empty($val['add_id'])) {
            continue;
        }
        $rcdId = $val['user_record_id'];
        $svcId = $recData[$rcdId]['service_id'];
        $tgtMst = isset($svcMst[$svcId]) ? $svcMst[$svcId] : NULL;

        $recId = $val['user_record_id'];
        $addId = $val['add_id'];
        $uniqueId = $val['unique_id'];
        $userId = $recData[$recId]['user_id'];
        $start_time = $val['start_day'] ? $val['start_day'] : "";

        $val['charge']       = $recData[$recId]['charge'];
        $val['use_day']      = $recData[$recId]['use_day'];
        $val['start_time']   = $recData[$recId]['start_time'];
        $val['end_time']     = $recData[$recId]['end_time'];
        $val['add_name']     = $addMst[$addId]['name'];
        $val['user_id']      = $userId;
        $val['service_id']   = $svcId;
        $val['service_code'] = isset($svcData[$svcId]) ? $svcData[$svcId]['code'] : NULL;
        $val['service_name'] = isset($svcData[$svcId]) ? $svcData[$svcId]['name'] : NULL;

        $recAddData[$addId . $start_time] = $val;
        $recAddList[$userId][$addId . $start_time][$uniqueId] = $val;
    }
    // 送信データ作成
    foreach ($recAddList as $userId => $recAddList2) {
       
        $f2 = 0;
        $f14 = isset($ofcList[$userId]['person_name']) ? $ofcList[$userId]['person_name'] : "";
        $f15 = isset($ofcList[$userId]['fax']) ? $ofcList[$userId]['fax'] : "";
        foreach ($recAddList2 as $svcId => $recAddList3) {
            $f10Map = $defMapAry;
            $f11Map = $defMapAry;
            $f12Map = $defMapAry;
            foreach ($recAddList3 as $uniqueId => $val) {
                
                $rdcId   = $val['user_record_id'];
                $svcId   = $recData[$rdcId]['service_id'];
                $charge  = $recData[$rdcId]['charge'];
                if(isset($svcMst[$svcId]) === false){
                    continue;
                }
                $svcCode = $svcMst[$svcId]['code'];
                $svcName = $svcMst[$svcId]['name'];
                $svcType = $svcMst[$svcId]['type'];
                
                // 医療は対象外
                if (substr($svcCode, 0, 1) == 7) {
                    continue;
                }

                /* -- 介護提供日情報(F10)作成 --------------------- */
                $tgtDay = $val['use_day'];
                $tgtDayDate = formatDateTime($tgtDay, "d");
                $idxDay = intval($tgtDayDate);
                if ($idxDay < 1) {
                    continue;
                }
                $f10Map[$idxDay] = 1;

                // 自己負担の場合は設定する
                if(!empty($charge)) {
                    $f12Map[$idxDay] = 1;
                }
                /* -- 介護提供日情報(F11)作成 --------------------- */
                // 退院時共同指導の場合は回数を加算する
                else if (mb_strpos($svcName, "退院時共同指導") !== false && empty($charge)) {
                    $f11Map[$idxDay] += 1;
                }
                // 看護小規模初期加算の場合は加減算で指定された日付範囲に1を立てる
                // 日割の場合は日割期間に1を立てる
                else if ((mb_strpos($svcName, "看護小規模初期加算") !== false || mb_strpos($svcName, "日割") !== false)  && empty($charge) ) {
                    $d1 = dateToDay($val['start_day']);
                    $d2 = dateToDay($val['end_day']);
                    if ($d1 < 1 || $d2 < 1) {
                        continue;
                    }
                    for ($i = $d1; $i <= $d2; $i++) {
                        $f11Map[$i] = 1;
                    }
                }
            }

            // f2作成
            $f2++;
            // f3作成
            $f3 = "1";
            // 単純加算の判定
            if (mb_strpos($svcName, "加算") !== false) {
                $f3 = "3";
            }
            $f5 = "";
            $f6 = "";
            if (mb_strpos($svcName, "ターミナルケア") !== false) {
                $f5 = "9999";
                $f6 = "9999";
            } else {
                $f5 = $recData[$rdcId]['start_time'] ? str_replace(":", "", $recData[$rdcId]['start_time']) : "";
                $f6 = $recData[$rdcId]['end_time'] ? str_replace(":", "", $recData[$rdcId]['end_time']) : "";
            }
            // データ区分を日々に設定
            $f7 = "1";
            
            // 訪問看護区分
            $f8 = "1";
            if (mb_strpos($svcType, "定期巡回") !== false) {
                $f8 = "2";
            }
            // 看多機能区分
            $f9 = "";
            if (mb_strpos($svcType, "通い") !== false) {
                $f9 = "1";
            } else if (mb_strpos($svcType, "宿泊") !== false) {
                $f9 = "2";
            } else if (mb_strpos($svcType, "訪問介護") !== false) {
                $f9 = "3";
            } else if (mb_strpos($svcType, "訪問看護") !== false) {
                $f9 = "4";
            }

            $f10Data = arryMapToStr($f10Map);
            $f11Data = arryMapToStr($f11Map);
            $f12Data = arryMapToStr($f12Map);

            $f13 = "";
//            $useDay = $recAddData[$svcId]['use_day'];
            $useDay = $val['use_day'];
            $d = dateToDay($useDay, "d") ? sprintf("%02d", dateToDay($useDay, "d")) : "";
            $md = dateToDay($useDay, "md") ? sprintf("%04d", dateToDay($useDay, "md")) : "";
            $ymd = dateToDay($useDay, "Ymd") ? sprintf("%06d", dateToDay($useDay, "Ymd")) : "";

            // 特定のサービスコードにより出力内容を変える
            switch($svcCode){
                case '134003':
                case '634003':
                case '774003':
                    $f13 = $d;
                    break;
                case '134004':
                    $f13 = $d;
                    break;
                case '137000':
                case '776100':
                    $f13 = $ymd;
                    break;
                default:
                    $f13 = "";
            }

            // レコード設定
            $dat = $defData['25'];
            $dat['code'] = '25';
            $dat['user_name'] = isset($userList[$userId]['name']) ? $userList[$userId]['name'] : NULL;
            $dat['f1'] = isset($userList[$userId]['other_id']) ? $userList[$userId]['other_id'] : NULL;
            $dat['f2'] = sprintf('%d', $f2);
            $dat['f3'] = $f3;
            $dat['f4'] = $svcCode;
            $dat['f5'] = $f5;
            $dat['f6'] = $f6;
            $dat['f7'] = $f7;
            $dat['f8'] = $f8;
            $dat['f9'] = $f9;
            $dat['f10'] = $f10Data;
            $dat['f11'] = $f11Data;
            $dat['f12'] = $f12Data;
            $dat['f13'] = $f13;
            $dat['f14'] = $f14;
            $dat['f15'] = $f15;
            $dat['user_id'] = $userId;
            $dat['charge'] = $userId;
            
            // 格納
            $cd = $dat['code'];
            $tgtData[$cd][$idx++] = $dat;    

            // $f14と$f15はユーザ単位で一度だけなので初期化する
            $f14 = "";
            $f15 = "";
        }
    }
    
    /* -- 医療保険証情報(31) --------------------------- */

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
        
        $f4  = "";
        $f5 = $val['number3'] . '・' . $val['number4'];

        // 特例措置による経過措置の有無
        $f9 = "";
        if ($val['type1'] === "高齢者") {
            $f5 = $val['number1'];
            $f9 = $val['select1'] ? $val['select1'] : "";
        }

        $f12 = "";
        if ($val['type1'] !== "公費のみ") {
            $f12 = $val['name'];
            $f4  = $val['number1'];
        }

        $f16 = "";
        if ($val["type1"] !== "後期高齢者") {
            $f5 = $val['number1'];
            $f16 = $val['number5'];
        }

        // レコード設定
        $dat = $defData['31'];
        $dat['code'] = '31';
        $dat['user_name'] = isset($userList[$userId]['name']) ? $userList[$userId]['name'] : NULL;
        $dat['f1'] = isset($userList[$userId]['other_id']) ? $userList[$userId]['other_id'] : NULL;
        $dat['f2'] = $val['start_day'];
        $dat['f3'] = $val['end_day'];
        $dat['f4'] = $f4;
        $dat['f5'] = $f5;
        $dat['f6'] = getTypeCode($val['type1'], $typeList['31-6']);
        $dat['f7'] = getTypeCode($val['type2'], $typeList['31-7']);
        $dat['f8'] = getTypeCode($val['type3'], $typeList['31-8']); 
        $dat['f9'] = $f9;
        $dat['f10'] = empty($val['select2']) ? "0" : "1";
        $dat['f11'] = $val['number2'];
        $dat['f12'] = $f12;
        $dat['f13'] = getTypeCode($val['type4'], $typeList['31-13']);
        $dat['f14'] = NULL;
        $dat['f15'] = NULL;
        $dat['f16'] = $f16;

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    
    }
    /* -- 利用者状態情報(32) --------------------------- */

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userAry;
    $where['direction_start <='] = $lastDay;
    $where['direction_end >='] = $firstDay;
    $where['status'] = '完成';
    $temp = select('doc_instruct', '*', $where);

    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        $f14 = "";
        if ($val['seriously_child'] === '超重症児') {
            $f14 = "1";
        } else if ($val['seriously_child'] === '準超重症児') {
            $f14 = "2";
        }else{
            $f14 = "0";
        }

        $f15 = "";
        if ($val['attached8'] === "該当する") {
            $f15 = "1";
        }else{
            $f15 = "0";
        }

        $f16 = "";
        if ($f15 === "1") {
            $dtl = $val['attached8_detail'];
            foreach ($typeList['32-16'] as $tgtName => $code) {
                if (mb_strpos($dtl, $tgtName) !== false) {
                    $f16 .= empty($f16) ? $code : "," . $code;
                }
            }
        }

        // レコード設定
        $dat = $defData['32'];
        $dat['code'] = '32';
        $dat['user_name'] = isset($userList[$userId]['name']) ? $userList[$userId]['name'] : NULL;
        $dat['f1'] = isset($userList[$userId]['other_id']) ? $userList[$userId]['other_id'] : NULL;
        $dat['f2'] = $val['judgement_day'];
        $dat['f3'] = NULL;
        $dat['f4'] = $val['sickness1'];
        $dat['f5'] = $val['sickness2'];
        $dat['f6'] = $val['sickness3'];
        $dat['f7'] = $val['sickness4'];
        $dat['f8'] = $val['sickness5'];
        $dat['f9'] = $val['sickness6'];
        $dat['f10'] = $val['sickness7'];
        $dat['f11'] = $val['sickness8'];
        $dat['f12'] = $val['sickness9'];
        $dat['f13'] = $val['sickness10'];
        $dat['f14'] = $f14;
        $dat['f15'] = $f15;
        $dat['f16'] = $f16;

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    
    }
    /* -- サービス開始終了情報(33)---------------------- */

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
        $f3 = "";
        if(empty($val['end_day']) || strpos($val['end_day'], "2999-") || "0000-00-00"){
            $f3 = "99999999";
        }else{
            $f3 = $val['end_day'];
        }
        $f4 = "";
        $f4 = getTypeCode($val['start_type'], $typeList['33-4']);
        $f4 = !empty($f4) ? $f4 : "0";
 
        // 前ステーション訪問日 
        $f5Map = $defMapAry;
        $f5Data = arryMapToStr($f5Map);

        // 退院日
        $f6 = "";
        if ($val['start_type'] === '入院') {
            $f6 = $val['end_day'];
        }

        // 訪問終了の状況
        $f7 = getTypeCode($val['cancel_reason'], $typeList['33-7']);
        $f8 = NULL;
        $f9 = NULL;
        if(!empty($val['death_place'])){
            $f10 = getTypeCode($val['death_place'], $typeList['33-10']);
        }else{
            $f10 = "";
        }
        // 死亡状況
        if ($f7 == "4") {
            $f8 = !empty($val['death_day']) ? formatDateTime($val['death_day'], 'Ymd') : NULL;
            $f9 = !empty($val['death_day']) && !empty($val['death_time']) ? formatDateTime($val['death_time'], 'Hi') : NULL;
            $f10 = getTypeCode($val['death_place'], $typeList['33-10']);
        }

        // 死亡場所施設又はその他
        $f11 = "";
        if ($f10 === 2 || $f10 === 5) {
            $f11 = !empty($val['death_reason']) ? $val['death_reason'] : NULL;
        }

        // レコード設定
        $dat = $defData['33'];
        $dat['code'] = '33';
        $dat['user_name'] = isset($userList[$userId]['name']) ? $userList[$userId]['name'] : NULL;
        $dat['f1'] = isset($userList[$userId]['other_id']) ? $userList[$userId]['other_id'] : NULL;
        $dat['f2'] = $val['start_day'];
        $dat['f3'] = $f3;
        $dat['f4'] = $f4;
        $dat['f5'] = $f5Data;
        $dat['f6'] = $f6;
        $dat['f7'] = $f7;
        $dat['f8'] = $f8;
        $dat['f9'] = $f9;
        $dat['f10'] = $f10;
        $dat['f11'] = $f11;

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    

    }
    /* -- 指示書情報(34) ------------------------------- */

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
        
        $f3 = getTypeCode($val['direction_kb'], $typeList['34-3']);
        $f6 = "";
        $f7 = "";
        $f9 = "";
        $f10 = "";
        $f11 = "";
        $f12 = "";
        $f13 = "";
        if ($val['direction_kb'] === '通常指示') {
            
            $f10 = $val['other_station1_address'];
            $f11 = $val['other_station1'];
            $f12 = $val['other_station2_address'];
            $f13 = $val['other_station2'];
        }

        // レコード設定
        $dat = $defData['34'];
        $dat['code'] = '34';
        $dat['user_name'] = isset($userList[$userId]['name']) ? $userList[$userId]['name'] : NULL;
        $dat['f1'] = isset($userList[$userId]['other_id']) ? $userList[$userId]['other_id'] : NULL;
        $dat['f2'] = getTypeCode($val['care_kb'], $typeList['34-2']);
        $dat['f3'] = getTypeCode($val['direction_kb'], $typeList['34-3']);
        $dat['f4'] = $val['direction_start'];
        $dat['f5'] = $val['direction_end'];
        $dat['f6'] = $val['hospital'];
        $dat['f7'] = $val['doctor'];
        $dat['f8'] = $val['plan_day'];
        $dat['f9'] = $f9;
        $dat['f10'] = $f10;
        $dat['f11'] = $f11;
        $dat['f12'] = $f12;
        $dat['f13'] = $f13;

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;    
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
    // データ取得
    $mstSvcInfo = array();
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service', '*', $where);
    foreach ($temp as $val) {
        $svcId = $val['unique_id']; 
        $typeNm = $val['type']; 
        $mstSvc[$typeNm] = $val;
        $mstSvcInfo[$svcId] = $val;
    }
    
    // 送信データ作成
    foreach ($recAddList as $userId => $recAddList2) {
        $f2 = 0;
        $f13 = "";
        $f14 = isset($ofcList[$userId]['person_name']) ? $ofcList[$userId]['person_name'] : "";
        $f15 = isset($ofcList[$userId]['fax']) ? $ofcList[$userId]['fax'] : "";
        foreach ($recAddList2 as $svcId => $recAddList3) {
            $inputFlg = false;
            $day = "";
            $f7Map = $defMapAry;
            $f8Map = $defMapAry;
            $f9Map = $defMapAry;
            $f10Map = $defMapAry;
            $f11Map = $defMapAry;
            $f12Map = $defMapAry;
            foreach ($recAddList3 as $uniqueId => $val) {
        
                // 親のサービスID,サービス名、タイプを取得
                $rcdId      = $val['user_record_id'];
                $rcdSvcName = $recData[$rcdId]['service_name'];
                $svcId      = $recData[$rcdId]['service_id'];
                $svcName    = isset($mstSvcInfo[$svcId]['name']) ? $mstSvcInfo[$svcId]['name'] : NULL;
                $svcType    = isset($mstSvcInfo[$svcId]['type']) ? $mstSvcInfo[$svcId]['type'] : NULL;
                
                // 医療は対象外
                $svcCode = $svcMst[$svcId]['code'];
                if (substr($svcCode, 0, 1) != 7) {
                    continue;
                }
                
                // サービス利用区分名称によるチェック
                if (searchSvcName($rcdSvcName, $type1, $type2)){
                    continue;
                }
                
                // 36医療情報は下記のサービスの場合スキップする
                //if(mb_strpos($rcdSvcName, "看多機 訪問介護") !== false
                //    || mb_strpos($rcdSvcName, "看多機 訪問看護") !== false
                //    || mb_strpos($rcdSvcName, "看多機 通い") !== false
                //    || mb_strpos($rcdSvcName, "看多機 宿泊") !== false
                //    ){
                //    continue;
                //}
                
                // サービス項目IDを設定する
                $f4 = getTypeCode($svcName, $typeList['36-4']);

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
                $careJob = $recData[$rcdId]['care_job'];
                if(mb_strpos($careJob,"准看護師") !== false){
                    $f9Map[$day] = 2;
                }else if(mb_strpos($careJob,"専門の研修を受けた看護師") !== false){
                    $f9Map[$day] = 5;
                }else if(mb_strpos($careJob,"看護師") !== false){
                    $f9Map[$day] = 1;
                }else if(mb_strpos($careJob,"理学療法士") !== false){
                    $f9Map[$day] = 3;
                }else if(mb_strpos($careJob,"作業療法士") !== false){
                    $f9Map[$day] = 4;
                }
                
                // 同一建物に訪問した利用者
                $visitorNum = $recData[$rcdId]['visitor_num'];
                if($visitorNum === "同一日に２人"){
                    $f10Map[$day] = 1;
                }else if($visitorNum === "同一日に3人以上"){
                    $f10Map[$day] = 2;
                }                
                
                // 特別地域加算有無
                if($recData[$rcdId]['area_add'] === "有"){
                    $f11Map[$day] = 1;
                }
                
                $insStation = $recData[$rcdId]['ins_station'];
                if ($svcName === "緊急訪問看護加算" || $svcName === "緊急訪問看護加算（精神）") {
                    
                    // 緊急指示先ステーション	
                    if($insStation === "指示書情報の他の指示先ステーション1"){
                        $f12Map[$day] = 1;
                    }else if($insStation === "指示書情報の他の指示先ステーション2"){
                        $f12Map[$day] = 2;
                    }
                }
                
                /* -- 介護提供日情報(F11)作成 --------------------- */
                // 退院時共同指導の場合は回数を加算する
                if (mb_strpos($svcName,"退院時共同指導") !== false) {
                    $f11Map[$day] += 1;
                }
                
                // 看護小規模初期加算の場合は加減算で指定された日付範囲に1を立てる
                // 日割の場合は日割期間に1を立てる
                if ($svcName === "看護小規模初期加算" || mb_strpos($svcName, "日割") !== false) {
                    $d1 = dateToDay($val['start_day']);
                    $d2 = dateToDay($val['end_day']);
                    if ($d1 < 1 || $d2 < 1) {
                        continue;
                    }
                    for ($i = $d1; $i <= $d2; $i++) {
                        $f11Map[$i] = 1;
                    }
                }
                
                // データ存在チェック用
                $inputFlg = true;
            }
            // データ存在チェック
            if (!$inputFlg){
                continue;
            }
            
            // f2作成
            $f2++;
            // f3作成
            $f3 = "1";
            // 単純加算の判定
            if (mb_strpos($svcName, "加算") !== false) {
                $f3 = "2";
            }
            $f4 = getTypeCode($svcName, $typeList['36-4']);

            $f5 = "";
            $f6 = "";
            if (mb_strpos($svcName, "ターミナルケア") !== false) {
                $f5 = "9999";
                $f6 = "9999";
            } else {
                // 日付変換
                $f5 = $recData[$rcdId]['start_time'] ? str_replace(":", "", $recData[$rcdId]['start_time']) : "";
                $f6 = $recData[$rcdId]['end_time'] ? str_replace(":", "", $recData[$rcdId]['end_time']) : "";
            }

//            $svcCode = $recAddData[$svcId]['service_code'];
            $useDay  = $val['use_day'];
            $d       = dateToDay($useDay, "d") ? sprintf("%02d", dateToDay($useDay, "d")) : "";
            $md      = dateToDay($useDay, "md") ? sprintf("%04d", dateToDay($useDay, "md")) : "";
            $ymd     = dateToDay($useDay, "Ymd") ? sprintf("%06d", dateToDay($useDay, "Ymd")) : "";

            $f7Data = arryMapToStr($f7Map);
            $f8Data = arryMapToStr($f8Map);
            $f9Data = arryMapToStr($f9Map);
            $f10Data = arryMapToStr($f10Map);
            $f11Data = arryMapToStr($f11Map);
            $f12Data = arryMapToStr($f12Map);
            
            $f14 = "";
            $f15 = "";

            // レコード設定
            $dat = $defData['36'];
            $dat['code'] = '36';
            $dat['user_name'] = isset($userList[$userId]['name']) ? $userList[$userId]['name'] : NULL;
            $dat['f1'] = isset($userList[$userId]['other_id']) ? $userList[$userId]['other_id'] : NULL;
            $dat['f2'] = sprintf('%d', $f2);
            $dat['f3'] = $f3;
            $dat['f4'] = $f4;
            $dat['f5'] = $f5;
            $dat['f6'] = $f6;
            $dat['f7'] = $f7Data;
            $dat['f8'] = $f8Data;
            $dat['f9'] = $f9Data;
            $dat['f10'] = $f10Data;
            $dat['f11'] = $f11Data;
            $dat['f12'] = $f12Data;
            $dat['f13'] = $f13;
            $dat['f14'] = $f14;
            $dat['f15'] = $f15;

            // 格納
            $cd = $dat['code'];
            $tgtData[$cd][$idx++] = $dat;    
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
    $where['status'] = '完成';
    $temp = select('doc_report', '*', $where);
    foreach ($temp as $val) {

        // 利用者
        $userId = $val['user_id'];

        // レコード設定
        $dat = $defData['38'];
        $dat['code'] = '38';
        $dat['user_name'] = isset($userList[$userId]['name']) ? $userList[$userId]['name'] : NULL;
        $dat['f1'] = isset($userList[$userId]['other_id']) ? $userList[$userId]['other_id'] : NULL;
        $dat['f2'] = $val['report_day'];
        $dat['f3'] = $val['gaf_score'];
        $dat['f4'] = $val['gaf_date'];

        // 格納
        $idx++;
        $res[$idx] = $dat;
    }
    /* -- 実費利用料情報(41) --------------------------- */
    
    // データ取得
    $mstSvc = array();
    $where = array();
    $where['delete_flg'] = 0;
    $temp = select('mst_service', '*', $where);
    foreach ($temp as $val) {
        $svcId = $val['unique_id']; 
        $typeNm = $val['type']; 
        $mstSvc[$typeNm] = $val;
        $mstSvcInfo[$svcId] = $val;
    }

    // データ取得
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_record_id'] = $rcdAry;
    $temp = select('dat_user_record_jippi', '*', $where);
    foreach ($temp as $val) {
        
        // 利用者、利用日
        $rcdId   = $val['user_record_id'];
        $tgtRcd  = $recData[$rcdId];
        $userId  = $recData[$rcdId]['user_id'];
        $tgtDay  = $recData[$rcdId]['use_day'];
        $svcName = $recData[$rcdId]['service_name'];
        $svcId   = $recData[$rcdId]['service_id'];
        $typeCd  = isset($mstSvcInfo[$svcId]['code']) ? $mstSvcInfo[$svcId]['code'] : NULL;
        
        // サービス利用区分名称によるチェック
        if (searchSvcName($svcName, $type1, $type2)){
            continue;
        }
        
        // サービス種別設定
        if(mb_strpos($svcName, "医療") !== false){
            $f4 ="70";
        }elseif(!empty($typeCd)){
            $f4 =  substr($typeCd, 0, 2);
        }
        
        // 消費税率計算
        $val['rate'] = empty($val['rate']) ? 0 : $val['rate'];
        if(empty($val['rate']) || $val['rate'] === 0){
            $f10 = "000.000";
        }else{
            $f10 = number_format(intVal($val['rate']) / 100);
        }
        
        // レコード設定
        $dat = $defData['41'];
        $dat['code'] = '41';
        $dat['user_name'] = isset($userList[$userId]['name']) ? $userList[$userId]['name'] : NULL;
        $dat['f1'] = isset($userList[$userId]['other_id']) ? $userList[$userId]['other_id'] : NULL;
        $dat['f2'] = formatDateTime($tgtDay,"d");
        $dat['f3'] = $val['type'];
        $dat['f4'] = $f4;
        $dat['f5'] = 2;
        $dat['f6'] = NULL;
        $dat['f7'] = NULL;
        $dat['f8'] = $val['price'];
        $dat['f9'] = getTypeCode($val['zei_type'], $typeList['41-9']);
        $dat['f10'] = $f10;
        $dat['f11'] = getTypeCode($val['subsidy'], $typeList['41-11']);

        // 格納
        $cd = $dat['code'];
        $tgtData[$cd][$idx++] = $dat;
    }
    
    /* -- データ変換処理(文字数調整、日付型変換) --------------------- */
    $tgtData = changeAccount($tgtData, $config);

    $res['error'] = array(); 
    $res['data'] = array(); 
     
    /* -- 必須チェック --------------------- */
    $errMsg = checkAccount($tgtData, $config);
    if($errMsg){
        $_SESSION['notice']['error'][] = sprintf("出力データに%d件のエラーが含まれています。", count($errMsg));
        
        $res['error'] = $errMsg;
        
        // エラー発生時にCSV出力しない場合は即時リターン
        //return $res;
    }
   
    /* -- データ格納 ----------------------- */
    $idx = 0;
    foreach ($tgtData as $code => $tgtData){
        foreach ($tgtData as $index => $val){
            $res['data'][$idx++] = $val;
        }
    }
    /* -- 返却 ----------------------------------------------------- */
    return $res;
}

// =======================================================================
// 日付分布データ作成関数
//  $dataArry       ：データ配列
//  $tgtColumnName  ：抽出項目
// =======================================================================
function monthMapConv($dataArry, $tgtColumnName) {
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
function arryMapToStr($mapAry) {
    $result = "";
    // 文字列作成
    for ($i = 1; $i <= 31; $i++) {
        $result .= $mapAry[$i];
    }
    return $result;
}

function dateToDay($date) {
    $idx = intval(formatDateTime($date, "d"));
    return $idx;
}

// =======================================================================
// コンダクト連携行初期化
// =======================================================================
function initAccount($config){
    $def = array();
    foreach ($config as $no =>$config2){
        $def[$no] = array();
        $def[$no]['code'] = $no;
        $def[$no]['user_name'] = null;
        foreach ($config2 as $key => $dummy){
            $def[$no][$key] = null;
        }
    }
    return $def;
}

// =======================================================================
// コンダクト連携データ変換
// =======================================================================
function changeAccount($tgtData, $config){
    
    foreach($tgtData as $code => $tgtData2){
        foreach($tgtData2 as $index => $rowData){

            // データ種別
            $tgtRowCnfg = $config[$code];

            foreach($rowData as $colName => $val){

                if($colName === 'code' || $colName === 'user_name'){
                    continue;
                }
                
                
                if(isset($tgtRowCnfg[$colName]) === false){
                    continue;
                }
                
                // 項目毎の設定内容
                $colCnfg = array();
                $colCnfg = explode("^",$tgtRowCnfg[$colName]);
                
                // 日付変換
                $dataType = $colCnfg[5];
                $dataFmt  = $colCnfg[6];
                $dataDef  = $colCnfg[7];
               
                if($dataType === "date"){
                    if($val === "29991231"
                        || $val === "2999-12-31"
                        || $val === "2999-01-01"
                        || $val === "0000-00-00"
                        || $val === "00000000"
                        || $val === "99999999"
                        || strpos($val, "2999-") !== false){
                       $val = !empty($dataDef) ? $dataDef : '';
                    }else{
                        // 書式変換
                        if(!empty($val)){
                            $val = formatDateTime($val, $dataFmt);
                        }
                    }
                } 

                // 文字数調整
                $size = $colCnfg[3];
                if($size !== "-"){
                    $val = trimStrWidth($val, $size, "");
                }        
                $tgtData[$code][$index][$colName] = $val;
            }
        }
    }
    
    return $tgtData;
}

// =======================================================================
// コンダクト連携データチェック（必須、整合性）
// =======================================================================
function checkAccount($tgtData, $config){

    // 初期化
    $errorMsg = array();
    
    foreach($tgtData as $cd => $tgtData2){
        if($cd === '00'){
            continue;
        }

        // データ種別
        $tgtRowCnfg = $config[$cd];

        foreach($tgtData2 as $index => $rowData){
            foreach($rowData as $colName => $val){

                //if($colName === 'code' || $colName === 'user_name'){
                if($colName === 'code' || $colName === 'user_name' || $colName === 'user_id' || $colName === 'charge' ){
                    continue;
                }

                // 項目毎の設定内容
                $colCnfg = array();
                $colCnfg = explode("^",$tgtRowCnfg[$colName]);
                
                // データ区分、項目名、必須、チェック対象カラム、チェック対象値
                $title1   = $colCnfg[0];
                $title2   = $colCnfg[1];
                $required = $colCnfg[2];
                $chkCol   = $colCnfg[9];
                $chkVal   = $colCnfg[10];

               // 条件付きチェックデータ
                $chkDat = !empty($rowData[$chkCol]) ? $rowData[$chkCol] : '';
                $optMsg = "関連カラム:".$chkCol."、関連カラム値:".$chkDat;
                
                // [1]必須チェック (データ存在のみ)
                if($required == 1){
                    if($val === ""){
                        $errorMsg[] = sprintf("%s.%s:%s-%s,%s(%s),%s", $title1, $title2, $cd, $colName, $rowData['user_name'], $rowData['f1'], '必須項目未設定エラー');
                    }
                }
                // [2]条件付き必須チェック (特定のカラムがNULLではない場合に必須とする)
                if($required == 2){
                    if (!empty($chkDat)){
                        if ($val === "" && $val != "99999999"){
                            $errorMsg[] = sprintf("%s.%s:%s-%s,%s(%s),%s,%s", $title1, $title2, $cd, $colName, $rowData['user_name'], $rowData['f1'], '必須項目未設定エラー',$optMsg);
                        }
                    }
                }
                // [3]条件付き必須チェック (特定のカラムが特定の設定値の場合に必須とする)
                if ($required == 3){
                    if(!empty($chkVal) && !empty($chkDat) && mb_strpos($chkVal, $chkDat) !== false){
                        if($val === "" && $val != "99999999"){
                            $errorMsg[] = sprintf("%s.%s:%s-%s,%s(%s),%s,%s", $title1, $title2, $cd, $colName, $rowData['user_name'], $rowData['f1'], '必須項目未設定エラー',$optMsg);
                        }
                    }
                }
                // [4]条件付き必須チェック (特定のカラムが特定の設定値以外の場合に必須とする)
                if ($required == 4){
                    if(!empty($chkVal) && !empty($chkDat) && $chkDat != $chkVal){
                        if($val === "" && $val != "99999999"){
                            $errorMsg[] = sprintf("%s.%s:%s-%s,%s(%s),%s,%s", $title1, $title2, $cd, $colName, $rowData['user_name'], $rowData['f1'], '必須項目未設定エラー',$optMsg);
                        }
                    }
                }
            }
        }
    }
    return $errorMsg;
}

// =======================================================================
// 選択肢コード変換
// =======================================================================
function getTypeCode($target,$typeList){
    $res = isset($typeList[$target]) ? $typeList[$target] : NULL;
    return $res;
}

// =======================================================================
// コンダクト連携config取得
// =======================================================================
function getAccConfig(){
    
    // 初期化
    $config = array();
    $config['00'] = array();
    $config['11'] = array();
    $config['12'] = array();
    $config['13'] = array();
    $config['21'] = array();
    $config['22'] = array();
    $config['23'] = array();
    $config['24'] = array();
    $config['25'] = array();
    $config['31'] = array();
    $config['32'] = array();
    $config['33'] = array();
    $config['34'] = array();
    $config['35'] = array();
    $config['36'] = array();
    $config['37'] = array();
    $config['38'] = array();
    $config['41'] = array();
    
    // 00:ヘッダ情報
    $config['00']['f1']  = "ヘッダ情報^対象年月^1^6^^date^Ym^^^^";

    // 11:利用者基本情報
    $config['11']['f1']  = "利用者基本情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['11']['f2']  = "利用者基本情報^フリガナ^1^100^^string^^^^^";
    $config['11']['f3']  = "利用者基本情報^氏名^1^100^^string^^^^^";
    $config['11']['f4']  = "利用者基本情報^生年月日^1^8^^date^Ymd^^^^";
    $config['11']['f5']  = "利用者基本情報^性別^1^1^11-5^string^^^^^";
    $config['11']['f6']  = "利用者基本情報^住所^1^120^^string^^^^^";
    $config['11']['f7']  = "利用者基本情報^所要時間（分）^0^3^^string^^^^^";

    // 12:公費情報
    $config['12']['f1']  = "公費情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['12']['f2']  = "公費情報^対象ｻｰﾋﾞｽ^1^1^12-2^string^^^^^";
    $config['12']['f3']  = "公費情報^有効期間開始年月日^1^8^^date^Ymd^^^^";
    $config['12']['f4']  = "公費情報^有効期間終了年月日^1^8^^date^Ymd^99999999^^^";
    $config['12']['f5']  = "公費情報^負担者番号^1^8^^string^^^^^";
    $config['12']['f6']  = "公費情報^受給者番号^1^7^^string^^^^^";
    $config['12']['f7']  = "公費情報^法別番号^1^3^^string^^^^^";
    $config['12']['f8']  = "公費情報^公費名称^1^32^^string^^^^^";
    $config['12']['f9']  = "公費情報^本人負担額^0^6^^string^^^^^";

    // 13:利用者金融機関情報
    $config['13']['f1']  = "利用者金融機関情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['13']['f2']  = "利用者金融機関情報^金融機関区分^1^1^13-2^string^^^^^";
    $config['13']['f3']  = "利用者金融機関情報^金融機関コード^1^4^^string^^^^^";
    $config['13']['f4']  = "利用者金融機関情報^金融機関名^1^20^^string^^^^^";
    $config['13']['f5']  = "利用者金融機関情報^支店コード^1^3^^string^^^^^";
    $config['13']['f6']  = "利用者金融機関情報^支店名^1^20^^string^^^^^";
    $config['13']['f7']  = "利用者金融機関情報^預金種別^1^1^13-7^string^^^^^";
    $config['13']['f8']  = "利用者金融機関情報^口座番号^1^7^^string^^^^^";
    $config['13']['f9']  = "利用者金融機関情報^預金者名^1^30^^string^^^^^";
//    $config['13']['f10']  = "利用者金融機関情報^支払方法^1^1^13-10^string^^^^^";

    // 21:介護保険証情報
    $config['21']['f1']  = "介護保険証情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['21']['f2']  = "介護保険証情報^認定有効期間開始年月日^1^8^^date^Ymd^^^^";
    $config['21']['f3']  = "介護保険証情報^認定有効期間終了年月日^1^8^^date^Ymd^^^^";
    $config['21']['f4']  = "介護保険証情報^保険者番号^1^8^^string^^^^^";
    $config['21']['f5']  = "介護保険証情報^被保険者番号^1^10^^string^^^^^";
    $config['21']['f6']  = "介護保険証情報^要介護状態区分^1^2^21-6^string^^^^^";
    $config['21']['f7']  = "介護保険証情報^区分支給限度額管理期間開始日付^0^8^^date^Ymd^^^^";
    $config['21']['f8']  = "介護保険証情報^区分支給限度額管理期間終了日付^0^8^^date^Ymd^^^^";

    // 22:給付情報
    $config['22']['f1']  = "給付情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['22']['f2']  = "給付情報^有効期間開始年月日^1^8^^date^Ymd^^^^";
    $config['22']['f3']  = "給付情報^有効期間終了年月日^1^8^^date^Ymd^99999999^^^";
    $config['22']['f4']  = "給付情報^給付率^1^2^^string^^^^^";

    // 居宅サービス計画情報
    $config['23']['f1']  = "居宅サービス計画情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['23']['f2']  = "居宅サービス計画情報^居宅サービス開始年月日^1^8^^date^Ymd^^^^";
    $config['23']['f3']  = "居宅サービス計画情報^居宅サービス中止年月日^1^8^^date^Ymd^99999999^^^";
    $config['23']['f4']  = "居宅サービス計画情報^中止理由^4^1^23-4^string^^^^f3^99999999";

    // 24:居宅介護支援事業者情報
    $config['24']['f1']  = "居宅介護支援事業者情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['24']['f2']  = "居宅介護支援事業者情報^サービス計画作成者区分^1^1^24-2^string^^^^^";
    $config['24']['f3']  = "居宅介護支援事業者情報^事業所番号^4^10^^string^^^^f2^2";
    $config['24']['f4']  = "居宅介護支援事業者情報^事業所名称^4^50^^string^^^^f2^2";

    // 介護実績情報
    $config['25']['f1']  = "介護実績情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['25']['f2']  = "介護実績情報^サービスＩＤ^1^2^^string^^^^^";
    $config['25']['f3']  = "介護実績情報^サービスコード区分^1^1^25-3^string^^^^^";
    $config['25']['f4']  = "介護実績情報^サービスコード^1^6^^string^^^^^";
    $config['25']['f5']  = "介護実績情報^開始時間^1^4^^date^Hi^^^^";
    $config['25']['f6']  = "介護実績情報^終了時間^1^4^^date^Hi^^^^";
    $config['25']['f7']  = "介護実績情報^データ区分^1^1^25-7^string^^^^^";
    $config['25']['f8']  = "介護実績情報^訪問看護区分^2^1^25-8^string^^^^^";
    $config['25']['f9']  = "介護実績情報^看多機能区分^2^1^25-9^string^^^^^";
    $config['25']['f10']  = "介護実績情報^提供日情報^1^31^^string^^^^^";
    $config['25']['f11']  = "介護実績情報^算定日・回数情報^1^31^^string^^^^^";
    $config['25']['f12']  = "介護実績情報^自己負担情報^1^31^^string^^^^^";
    $config['25']['f13']  = "介護実績情報^摘要内容^2^20^^string^^^^^";
    $config['25']['f14']  = "介護実績情報^計画作成担当者名^0^36^^string^^^^^";
    $config['25']['f15']  = "介護実績情報^居宅介護支援事業所FAX番号^0^30^^string^^^^^";

    // 医療保険証情報";
    $config['31']['f1']  = "医療保険証情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['31']['f2']  = "医療保険証情報^有効期間開始年月日^1^8^^date^Ymd^^^^";
    $config['31']['f3']  = "医療保険証情報^有効期間終了年月日^1^8^^date^Ymd^99999999^^^";
    $config['31']['f4']  = "医療保険証情報^保険者番号^1^8^^string^^^^^";
    $config['31']['f5']  = "医療保険証情報^記号・番号または被保険者番号^0^28^^string^^^^^";
    $config['31']['f6']  = "医療保険証情報^保険区分^1^1^31-6^string^^^^^";
    $config['31']['f7']  = "医療保険証情報^本人区分^1^1^31-7^string^^^^^";
    $config['31']['f8']  = "医療保険証情報^所得区分^1^1^31-8^string^^^^^";
    $config['31']['f9']  = "医療保険証情報^特例措置による経過措置の有無^0^1^31-9^string^^^^^";
    $config['31']['f10']  = "医療保険証情報^退職者医療制度該当区分^0^1^31-10^string^^^^^";
    $config['31']['f11']  = "医療保険証情報^法別番号^2^2^^string^^^^^";
    $config['31']['f12']  = "医療保険証情報^保険名称^2^32^^string^^^^^";
    $config['31']['f13']  = "医療保険証情報^職務上の事由^1^1^31-13^string^^^^^";
    $config['31']['f14']  = "医療保険証情報^レセプト特記^0^48^^string^^^^^";
    $config['31']['f15']  = "医療保険証情報^レセプト特記事項^0^416^^string^^^^^";
    $config['31']['f16']  = "医療保険証情報^被保険者番号及び記号・番号の枝番^0^4^^string^^^^^";

    // 利用者状態情報";
    $config['32']['f1']  = "利用者状態情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['32']['f2']  = "利用者状態情報^判定年月日^1^8^^date^Ymd^^^^";
    $config['32']['f3']  = "利用者状態情報^心身の状態^0^250^^string^^^^^";
    $config['32']['f4']  = "利用者状態情報^主たる傷病名１^0^50^^string^^^^^";
    $config['32']['f5']  = "利用者状態情報^主たる傷病名２^0^50^^string^^^^^";
    $config['32']['f6']  = "利用者状態情報^主たる傷病名３^0^50^^string^^^^^";
    $config['32']['f7']  = "利用者状態情報^主たる傷病名４^0^50^^string^^^^^";
    $config['32']['f8']  = "利用者状態情報^主たる傷病名５^0^50^^string^^^^^";
    $config['32']['f9']  = "利用者状態情報^主たる傷病名６^0^50^^string^^^^^";
    $config['32']['f10']  = "利用者状態情報^主たる傷病名７^0^50^^string^^^^^";
    $config['32']['f11']  = "利用者状態情報^主たる傷病名８^0^50^^string^^^^^";
    $config['32']['f12']  = "利用者状態情報^主たる傷病名９^0^50^^string^^^^^";
    $config['32']['f13']  = "利用者状態情報^主たる傷病名１０^0^50^^string^^^^^";
    $config['32']['f14']  = "利用者状態情報^状態区分１^0^1^32-14^string^^^^^";
    $config['32']['f15']  = "利用者状態情報^状態区分２^0^1^32-15^string^^^^^";
    $config['32']['f16']  = "利用者状態情報^別表８用疾病コード^0^-^32-16^string^^^^^";

    // 開始終了情報";
    $config['33']['f1']  = "サービス開始終了情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['33']['f2']  = "サービス開始終了情報^訪問看護開始年月日^1^8^^date^Ymd^^^^";
    $config['33']['f3']  = "サービス開始終了情報^訪問看護終了年月日^1^8^^date^Ymd^99999999^^^";
    $config['33']['f4']  = "サービス開始終了情報^開始区分^1^1^33-4^string^^^^^";
    $config['33']['f5']  = "サービス開始終了情報^前ステーション訪問日^3^31^^string^^^^f4^1";
    $config['33']['f6']  = "サービス開始終了情報^退院日^3^8^^date^Ymd^^^f4^3";
    $config['33']['f7']  = "サービス開始終了情報^訪問終了の状況^4^2^33-7^string^^^^f3^99999999";
    $config['33']['f8']  = "サービス開始終了情報^死亡の状況（年月日）^3^8^^date^Ymd^^^f7^4";
    $config['33']['f9']  = "サービス開始終了情報^死亡の状況（時刻）^3^4^^string^^^^f7^4";
    $config['33']['f10']  = "サービス開始終了情報^死亡の状況（場所）^3^1^33-10^string^^^^f7^4";
    $config['33']['f11']  = "サービス開始終了情報^死亡場所施設又はその他^3^20^^string^^^^f7^4";

    // 指示書情報";
    $config['34']['f1']  = "指示書情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['34']['f2']  = "指示書情報^看護区分^1^1^34-2^string^^^^^";
    $config['34']['f3']  = "指示書情報^指示区分^1^1^34-3^string^^^^^";
    $config['34']['f4']  = "指示書情報^指示期間開始年月日^1^8^^date^Ymd^^^^";
    $config['34']['f5']  = "指示書情報^指示期間終了年月日^1^8^^date^Ymd^^^^";
    $config['34']['f6']  = "指示書情報^指示書を交付した主治医の属する医療機関名称^3^32^^string^^^^f3^1";
    $config['34']['f7']  = "指示書情報^主治医名^2^50^^string^^^^f3^1";
    $config['34']['f8']  = "指示書情報^計画書年月日^0^8^^date^Ymd^^^^";
    $config['34']['f9']  = "指示書情報^報告書年月日^0^8^^date^Ymd^^^^";
    $config['34']['f10']  = "指示書情報^他の指示先ステーション所在地１^0^72^^string^^^^^";
    $config['34']['f11']  = "指示書情報^他の指示先ステーション名称１^0^72^^string^^^^^";
    $config['34']['f12']  = "指示書情報^他の指示先ステーション所在地２^0^72^^string^^^^^";
    $config['34']['f13']  = "指示書情報^他の指示先ステーション名称２^0^72^^string^^^^^";

    // 情報提供情報";
    $config['35']['f1']  = "情報提供情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['35']['f2']  = "情報提供情報^発行日^1^2^^date^d^^^^";
    $config['35']['f3']  = "情報提供情報^情報提供先様式区分^1^1^35-3^string^^^^^";
    $config['35']['f4']  = "情報提供情報^情報提供先の市区町村等の名称情報提供先の名称^1^50^^string^^^^^";
    $config['35']['f5']  = "情報提供情報^情報提供先区分^0^1^35-5^string^^^^^";

    // 医療実績情報";
    $config['36']['f1']  = "医療実績情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['36']['f2']  = "医療実績情報^サービスＩＤ^1^2^^string^^^^^";
    $config['36']['f3']  = "医療実績情報^サービス区分^1^1^36-3^string^^^^^";
    $config['36']['f4']  = "医療実績情報^サービス項目ＩＤ^1^2^36-4^string^^^^^";
    $config['36']['f5']  = "医療実績情報^開始時間^1^4^^date^Hi^^^^";
    $config['36']['f6']  = "医療実績情報^終了時間^1^4^^date^Hi^^^^";
    $config['36']['f7']  = "医療実績情報^提供日情報^1^31^^string^^^^^";
    $config['36']['f8']  = "医療実績情報^算定日・回数情報^1^31^^string^^^^^";
    $config['36']['f9']  = "医療実績情報^訪問した担当者の資格^0^31^36-9^string^^^^^";
    $config['36']['f10']  = "医療実績情報^同一建物に訪問した利用者数^0^31^36-10^string^^^^^";
    $config['36']['f11']  = "医療実績情報^特別地域加算有無^0^31^36-11^string^^^^^";
    $config['36']['f12']  = "医療実績情報^緊急時訪問看護を行った指示先ステーション名区分^0^31^36-12^string^^^^^";
    $config['36']['f13']  = "医療実績情報^直前の基本療養費算定日^0^-^^string^^^^^";
    $config['36']['f14']  = "医療実績情報^退院支援指導後による死亡日又は再入院日^0^-^^string^^^^^";
    $config['36']['f15']  = "医療実績情報^介護職員等と同行訪問した日^0^-^^string^^^^^";

    // 高額療養費情報";
    $config['37']['f1']  = "高額療養費情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['37']['f2']  = "高額療養費情報^有効期間開始年月日^1^8^^date^Ymd^^^^";
    $config['37']['f3']  = "高額療養費情報^有効期間終了年月日^1^8^^date^Ymd^99999999^^^";
    $config['37']['f4']  = "高額療養費情報^高額療養費の上限額^1^8^^string^^^^^";

    // 報告書情報";
    $config['38']['f1']  = "報告書情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['38']['f2']  = "報告書情報^報告書年月日^1^8^^date^Ymd^^^^";
    $config['38']['f3']  = "報告書情報^GAF^0^3^^string^^^^^";
    $config['38']['f4']  = "報告書情報^GAF判定年月日^0^8^^date^Ymd^^^^";

    // 実費利用料情報";
    $config['41']['f1']  = "実費利用料情報^利用者ＩＤ^1^-^^string^^^^^";
    $config['41']['f2']  = "実費利用料情報^利用日^1^2^^string^^^^^";
    $config['41']['f3']  = "実費利用料情報^利用項目名称^1^40^^string^^^^^";
    $config['41']['f4']  = "実費利用料情報^サービス種別^1^2^^string^^^^^";
    $config['41']['f5']  = "実費利用料情報^入力形式^1^1^41-5^string^^^^^";
    $config['41']['f6']  = "実費利用料情報^単価^0^7^^string^^^^^";
    $config['41']['f7']  = "実費利用料情報^数量^0^4^^string^^^^^";
    $config['41']['f8']  = "実費利用料情報^金額^0^9^^string^^^^^";
    $config['41']['f9']  = "実費利用料情報^消費税区分^0^1^41-9^string^^^^^";
    $config['41']['f10']  = "実費利用料情報^消費税率^3^7^^string^^^^f9^1";
    $config['41']['f11']  = "実費利用料情報^控除区分^0^1^41-11^string^^^^^";

    return $config;
}

// =======================================================================
// コンダクト連携用項目⇒コード変換
// =======================================================================
function getAccType(){
    
    // 初期化
    $typeList            = array();
    $typeList['11-5']    = array();
    $typeList['12-2']    = array();
    $typeList['13-2']    = array();
    $typeList['13-7']    = array();
    $typeList['13-10']   = array();
    $typeList['21-6']    = array();
    $typeList['23-4']    = array();
    $typeList['24-2']    = array();
    $typeList['25-3']    = array();
    $typeList['25-7']    = array();
    $typeList['25-8']    = array();
    $typeList['25-9']    = array();
    $typeList['31-6']    = array();
    $typeList['31-7']    = array();
    $typeList['31-8']    = array();
    $typeList['31-9']    = array();
    $typeList['31-10']   = array();
    $typeList['31-13']   = array();
    $typeList['32-14']   = array();
    $typeList['32-15']   = array();
    $typeList['32-16']   = array();
    $typeList['33-4']    = array();
    $typeList['33-7']    = array();
    $typeList['33-10']   = array();
    $typeList['34-2']    = array();
    $typeList['34-3']    = array();
    $typeList['35-3']    = array();
    $typeList['35-5']    = array();
    $typeList['36-3']    = array();
    $typeList['36-4']    = array();
    $typeList['36-9']    = array();
    $typeList['36-10']   = array();
    $typeList['36-11']   = array();
    $typeList['36-12']   = array();
    $typeList['41-5']    = array();
    $typeList['41-9']    = array();
    $typeList['41-11']   = array();
   
    // 性別
    $typeList['11-5']['男']           = '1';
    $typeList['11-5']['女']           = '2';
    $typeList['11-5']['男性']         = '1';
    $typeList['11-5']['女性']         = '2';
    $typeList['11-5']['不明']         = '3';

    // 対象サービス
    $typeList['12-2']['介護サービス'] = '1';
    $typeList['12-2']['医療サービス'] = '2';

    // 金融機関区分
    $typeList['13-2']['銀行']         = '1';
    $typeList['13-2']['信用金庫']     = '2';
    $typeList['13-2']['農協']         = '3';
    $typeList['13-2']['郵便局']       = '4';
    $typeList['13-2']['ゆうちょ銀行'] = '4';

    // 預金種別
    $typeList['13-7']['普通預金']     = '1';
    $typeList['13-7']['当座預金']     = '2';
    $typeList['13-7']['納税準備預金'] = '3';
    $typeList['13-7']['その他']       = '9';

    // 支払方法
    $typeList['13-10']['振替']        = '1';
    $typeList['13-10']['引き落とし']  = '1';
    $typeList['13-10']['振込']        = '2';
    $typeList['13-10']['現金']        = '3';

    // 要介護状態区分
    $typeList['21-6']['非該当']       = '01';
    $typeList['21-6']['要支援']       = '11';
    $typeList['21-6']['要支援1']      = '11';
    $typeList['21-6']['要支援2']      = '13';
    $typeList['21-6']['要介護1']      = '21';
    $typeList['21-6']['要介護2']      = '22';
    $typeList['21-6']['要介護3']      = '23';
    $typeList['21-6']['要介護4']      = '24';
    $typeList['21-6']['要介護5']      = '25';
    $typeList['21-6']['要支援１']     = '11';
    $typeList['21-6']['要支援２']     = '13';
    $typeList['21-6']['要介護１']     = '21';
    $typeList['21-6']['要介護２']     = '22';
    $typeList['21-6']['要介護３']     = '23';
    $typeList['21-6']['要介護４']     = '24';
    $typeList['21-6']['要介護５']     = '25';

    // 中止理由
    $typeList['23-4']['非該当']                 = '1';
    $typeList['23-4']['医療機関入院']           = '3';
    $typeList['23-4']['死亡']                   = '4';
    $typeList['23-4']['その他']                 = '5';
    $typeList['23-4']['介護老人福祉施設入所']   = '6';
    $typeList['23-4']['介護老人保険施設入所']   = '7';
    $typeList['23-4']['介護療養型医療施設入所'] = '8';

    // サービス計画作成者区分
    $typeList['24-2']['居宅支援作成']           = '1';
    $typeList['24-2']['居宅介護支援事業者作成'] = '1';
    $typeList['24-2']['被保険者自己作成']       = '2';
    $typeList['24-2']['自己作成']               = '2';
    $typeList['24-2']['予防支援作成']           = '3';

    // サービスコード区分
    $typeList['25-3']['基本(合成)']             = '1';
    $typeList['25-3']['単独加算 ']              = '3';

    // データ区分
    $typeList['25-7']['日々']                   = '1';
    $typeList['25-7']['月額定額']               = '2';

    // 訪問看護区分
    $typeList['25-8']['訪問看護']               = '1';
    $typeList['25-8']['定期巡回']               = '2';

    // 看多機能区分
    $typeList['25-9']['通い']                   = '1';
    $typeList['25-9']['泊まり']                 = '2';
    $typeList['25-9']['訪問介護']               = '3';
    $typeList['25-9']['訪問看護']               = '4';

    // 保険区分
    $typeList['31-6']['国保']                   = '1';
    $typeList['31-6']['社保']                   = '2';
    $typeList['31-6']['後期高齢者']             = '3';
    $typeList['31-6']['公費のみ']               = '4';
    $typeList['31-6']['労災']                   = '5';
    $typeList['31-6']['公害']                   = '6';
    $typeList['31-6']['その他']                 = '7';

    // 本人区分
    $typeList['31-7']['本人']                   = '1';
    $typeList['31-7']['被扶養者']               = '2';
    $typeList['31-7']['高齢者']                 = '3';
    $typeList['31-7']['義務教育就学前']         = '4';

    // 所得区分
    $typeList['31-8']['現役並みⅢ']             = '1';
    $typeList['31-8']['現役並みⅡ']             = '2';
    $typeList['31-8']['現役並みⅠ']             = '3';
    $typeList['31-8']['一般所得者']             = '4';
    $typeList['31-8']['低所得者Ⅱ']             = '5';
    $typeList['31-8']['低所得者Ⅰ']             = '6';
    $typeList['31-8']['不明']                   = '7';

    // 特例措置による経過措置の有無
    $typeList['31-9']['無']                     = '0';
    $typeList['31-9']['有']                     = '1';

    // 退職者医療制度該当区分
    $typeList['31-10']['該当なし']              = '0';
    $typeList['31-10']['該当あり']              = '1';

    // 職務上の事由
    $typeList['31-13']['なし']                  = '1';
    $typeList['31-13']['職務上']                = '2';
    $typeList['31-13']['下船後３カ月以内']      = '3';
    $typeList['31-13']['通勤災害']              = '4';

    // 状態区分１
    $typeList['32-14']['該当なし']              = '0';
    $typeList['32-14']['超重症児']              = '1';
    $typeList['32-14']['準超重症児']            = '2';

    // 状態区分２
    $typeList['32-15']['該当なし']              = '0';
    $typeList['32-15']['特掲診療料の施設基準等別表８に該当する'] = '2';

    // 別表８用疾病コード
    $typeList['32-16']['在宅悪性腫瘍等患者指導管理を受けている状態にある者']     = '41';
    $typeList['32-16']['在宅気管切開患者指導管理を受けている状態にある者']       = '42';
    $typeList['32-16']['気管カニューレを使用している状態にある者']               = '43';
    $typeList['32-16']['留置カテーテルを使用している状態にある者']               = '44';
    $typeList['32-16']['在宅自己腹膜灌流指導管理を受けている状態にある者']       = '45';
    $typeList['32-16']['在宅血液透析指導管理を受けている状態にある者']           = '46';
    $typeList['32-16']['在宅酸素療法指導管理を受けている状態にある者']           = '47';
    $typeList['32-16']['在宅中心静脈栄養法指導管理を受けている状態にある者']     = '48';
    $typeList['32-16']['在宅成分栄養経管栄養法指導管理を受けている状態にある者'] = '49';
    $typeList['32-16']['在宅自己導尿指導管理を受けている状態にある者']           = '50';
    $typeList['32-16']['在宅人工呼吸指導管理を受けている状態にある者']           = '51';
    $typeList['32-16']['在宅持続陽圧呼吸療法指導管理を受けている状態にある者']   = '52';
    $typeList['32-16']['在宅自己疼痛管理指導管理を受けている状態にある者']       = '53';
    $typeList['32-16']['在宅肺高血圧症患者指導管理を受けている状態にある者']     = '54';
    $typeList['32-16']['人工肛門又は人口膀胱を設置している状態にある者']         = '55';
    $typeList['32-16']['真皮を越える褥瘡の状態にある者']                         = '56';
    $typeList['32-16']['在宅患者訪問点滴注射管理指導料を算定している者']         = '57';

    // 開始区分
    $typeList['33-4']['訪問開始']               = '0';
    $typeList['33-4']['継続利用']               = '1';
    $typeList['33-4']['保険変更']               = '2';
    $typeList['33-4']['入院']                   = '3';

    // 訪問終了状況
    $typeList['33-7']['軽快']                   = '1';
    $typeList['33-7']['施設']                   = '2';
    $typeList['33-7']['医療機関']               = '3';
    $typeList['33-7']['死亡']                   = '4';
    $typeList['33-7']['その他']                 = '5';

    // 死亡の状況（場所）
    $typeList['33-10']['自宅']                  = '1';
    $typeList['33-10']['施設']                  = '2'; 
    $typeList['33-10']['病院']                  = '3';
    $typeList['33-10']['診療所']                = '4';
    $typeList['33-10']['その他']                = '5';

    // 看護区分
    $typeList['34-2']['一般']                   = '1';
    $typeList['34-2']['精神']                   = '2';

    // 指示区分
    $typeList['34-3']['通常指示']               = '1';
    $typeList['34-3']['特別指示']               = '2';

    // 情報提供先様式区分
    $typeList['35-3']['市区町村等向け（様式１）']         = '1';
    $typeList['35-3']['市区町村等向け（様式２）']         = '1';
    $typeList['35-3']['義務教育緒学校向け（様式３）']     = '2';
    $typeList['35-3']['主治医・医療機関等向け（様式４）'] = '3';

    //情報提供先：様式区分が1:市区町村等向け（様式１）の場合
    $typeList['35-5']['市（区）町村等']                   = '1';
    $typeList['35-5']['指定特定相談支援事業者等']         = '2';

    //情報提供先：様式区分が2:義務教育緒学校向け（様式３）の場合
    $typeList['35-5']['入園・入学']             = '1';
    $typeList['35-5']['転園・転学']             = '2';
    $typeList['35-5']['年度1回']                = '3';
    $typeList['35-5']['医療的ケアの変更']       = '4';

    //情報提供先：様式区分が3:主治医・医療機関等向け（様式４）の場合
    $typeList['35-5']['保険医療機関']           = '1';
    $typeList['35-5']['介護老人保健施設']       = '2';
    $typeList['35-5']['介護医療院']             = '3';

    // サービス区分
    $typeList['36-3']['基本療養費']             = '1';
    $typeList['36-3']['加算']                   = '2';

    // サービス項目ＩＤ
    $typeList['36-4']['基本療養費Ⅰ']                             = '01';
    $typeList['36-4']['基本療養費Ⅱ']                             = '02';
    $typeList['36-4']['基本療養費Ⅲ']                             = '03';
    $typeList['36-4']['精神基本療養費Ⅰ']                         = '04';
    $typeList['36-4']['精神基本療養費Ⅱ']                         = '05';
    $typeList['36-4']['精神基本療養費Ⅲ']                         = '06';
    $typeList['36-4']['精神基本療養費Ⅳ']                         = '07';
    $typeList['36-4']['退院支援指導加算']                         = '08';
    $typeList['36-4']['緊急訪問看護加算']                         = '09';
    $typeList['36-4']['緊急訪問看護加算（精神）']                 = '10';
    $typeList['36-4']['ターミナルケア療養費']                     = '11';
    $typeList['36-4']['ターミナルケア療養費１']                   = '11';
    $typeList['36-4']['ターミナルケア療養費２']                   = '12';
    $typeList['36-4']['退院支援指導加算（長時間）']               = '13';
    $typeList['36-4']['２４時間対応体制']                         = '02';
    $typeList['36-4']['特別管理加算']                             = '03';
    $typeList['36-4']['特別管理加算（重症度高）']                 = '04';
    $typeList['36-4']['退院時共同指導']                           = '05';
    $typeList['36-4']['特別管理指導加算']                         = '06';
    $typeList['36-4']['長時間訪問看護加算']                       = '07';
    $typeList['36-4']['長時間訪問看護加算（精神）']               = '08';
    $typeList['36-4']['在宅患者連携指導加算']                     = '09';
    $typeList['36-4']['在宅患者緊急時等カンファレンス加算']       = '10';
    $typeList['36-4']['乳幼児加算']                               = '11';
    $typeList['36-4']['複数名訪問看護加算（看護師等）']           = '13';
    $typeList['36-4']['複数名訪問看護加算（准看護師）']           = '14';
    $typeList['36-4']['複数名訪問看護加算（理学療法士等）']       = '15';
    $typeList['36-4']['複数名訪問看護加算（看護補助者）']         = '16';
    $typeList['36-4']['複数名訪問看護加算（看護師等（精神））']   = '17';
    $typeList['36-4']['複数名訪問看護加算（准看護師（精神））']   = '18';
    $typeList['36-4']['複数名訪問看護加算（作業療法士（精神））'] = '19';
    $typeList['36-4']['複数名訪問看護加算（看護補助者（精神））'] = '20';
    $typeList['36-4']['複数名訪問看護加算（精神保健福祉士）']     = '21';
    $typeList['36-4']['夜間・早朝加算']                           = '22';
    $typeList['36-4']['深夜加算']                                 = '23';
    $typeList['36-4']['夜間・早朝加算（精神）']                   = '24';
    $typeList['36-4']['深夜加算（精神）']                         = '25';
    $typeList['36-4']['精神重症患者支援管理連携加算１']           = '26';
    $typeList['36-4']['精神重症患者支援管理連携加算２']           = '27';
    $typeList['36-4']['看護・介護職員連携強化加算']               = '28';
    $typeList['36-4']['退院支援指導加算']                         = '29';
    $typeList['36-4']['緊急訪問看護加算']                         = '30';
    $typeList['36-4']['緊急訪問看護加算（精神）']                 = '31';
    $typeList['36-4']['退院支援指導加算（長時間）']               = '32';
    $typeList['36-4']['専門管理加算']                             = '33';
    $typeList['36-4']['遠隔死亡診断補助加算']                     = '34';

    // 訪問した担当者の資格
    $typeList['36-9']['看護師等']                 = '1';
    $typeList['36-9']['准看護師']                 = '2';
    $typeList['36-9']['理学療法士等']             = '3';
    $typeList['36-9']['作業療法士']               = '4';
    $typeList['36-9']['専門の研修を受けた看護師'] = '5';

    // 同一建物に訪問した利用者数
    $typeList['36-10']['同一日に２人']          = '1';
    $typeList['36-10']['同一日に３人以上']      = '2';

    // 特別地域加算有無
    $typeList['36-11']['無']                    = '0';
    $typeList['36-11']['有']                    = '1';

    // 緊急時訪問看護を行った指示先ステーション名区分
    $typeList['36-12']['未設定'] = '0';
    $typeList['36-12']['34：指示書情報 の他の指示先ステーション名称１'] = '1';
    $typeList['36-12']['34：指示書情報 の他の指示先ステーション名称２'] = '2';

    // 入力形式
    $typeList['41-5']['数量']                   = '0';
    $typeList['41-5']['金額実費入力']           = '1';

    // 消費税区分
    $typeList['41-9']['非課税']                 = '0';
    $typeList['41-9']['税別']                   = '0';
    $typeList['41-9']['課税']                   = '1';
    $typeList['41-9']['税込']                   = '1';

    // 控除区分
    $typeList['41-11']['控除対象外']            = '0';
    $typeList['41-11']['控除対象']              = '1';

    return $typeList;
}

// サービス利用区分の名称検索判定
function searchSvcName($name, $type1, $type2){
    
    // 初期値
    $res = true;
    
    // 利用区分サービス(看多機)
    if ($type1){
        if (mb_strpos($name,"看多機　訪問介護") !== false
            || mb_strpos($name,"看多機　訪問看護") !== false
            || mb_strpos($name,"看多機　通い") !== false
            || mb_strpos($name,"看多機　宿泊") !== false){
            $res = false;
        }
    }
    // 利用区分サービス(訪問介護)
    if ($res && $type2){
        if (mb_strpos($name,"訪問介護　医療保険") !== false
            || mb_strpos($name,"訪問介護　介護保険") !== false
            || mb_strpos($name,"定期巡回") !== false){
            $res = false;
        }
    }
    
    // 返却
    return $res;
}
/* =================================================== 
 * 例外処理
 * ===================================================
 */
} catch (Exception $e) {
    if ($execEnv === 'pro' || $execEnv === 'stg') {
        $_SESSION['notice']['error'] = !empty($err) ? $err : array();
        header("Location:" . ERROR_PAGE);
        exit();
    } else {
        debug($e);
        exit();
    }
}
?>