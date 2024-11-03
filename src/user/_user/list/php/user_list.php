<?php

//=====================================================================
// 利用者編集 - 基本情報一覧
//=====================================================================
try {
    /* ===================================================
     * 初期処理
     * ===================================================
     */

    /*--共通ファイル呼び出し-------------------------------------*/
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/user/list/function/func_user.php');

    /*--変数定義-------------------------------------------------*/

    // 初期化
    $err       = array();
    $_SESSION['notice']['error']   = array();
    $dispData  = array();
    $tgtData   = array();
    $upData    = array();
    $tgtId     = array();
    $search    = array();
    $usrIdList = null;

    // 対象テーブル(メイン)
    $table = 'mst_user';

    // 表示件数
    $line = 200;

    // 初期値
    $dispData = initTable($table);
    $dispData['create_day']  = null;
    $dispData['create_time'] = null;
    $dispData['create_name'] = null;
    $dispData['update_day']  = null;
    $dispData['update_time'] = null;
    $dispData['update_name'] = null;

    // 初期値
    $dispData['standard']          = initTable('mst_user');
    $dispData['standard']['status']           = '停止中';
    $dispData['standard']['office2_name']     = null;
    $dispData['standard']['office2_person']   = null;
    $dispData['standard']['office2_tel']      = null;
    $dispData['standard']['medical_hospital'] = null;
    $dispData['standard']['medical_doctor']   = null;
    $dispData['standard']['medical_tel']      = null;
    $dispData['standard']['nengo']            = null;
    $dispData['standard']['wareki']           = null;
    $dispData['standard']['year']             = null;
    $dispData['standard']['month']            = null;
    $dispData['standard']['day']              = null;
    $dispData['standard']['sv_cls']           = null;
    $dispData['standard']['st_cls']           = null;
    $dispData['office1']['def']    = initTable('mst_user_office1');
    $dispData['office2']['def']    = initTable('mst_user_office2');
    $dispData['pay']               = initTable('mst_user_pay');
    $dispData['insure1']['def']    = initTable('mst_user_insure1');
    $dispData['insure1']['def']['start_year1']  = null;
    $dispData['insure1']['def']['start_month1'] = null;
    $dispData['insure1']['def']['start_dt1']    = null;
    $dispData['insure1']['def']['end_year1']    = null;
    $dispData['insure1']['def']['end_month1']   = null;
    $dispData['insure1']['def']['end_dt1']      = null;
    $dispData['insure1']['def']['start_year2']  = null;
    $dispData['insure1']['def']['start_month2'] = null;
    $dispData['insure1']['def']['start_dt2']    = null;
    $dispData['insure1']['def']['end_year2']    = null;
    $dispData['insure1']['def']['end_month2']   = null;
    $dispData['insure1']['def']['end_dt2']      = null;
    $dispData['insure2']['def']    = initTable('mst_user_insure2');
    $dispData['insure2']['def']['start_nengo']  = null;
    $dispData['insure2']['def']['start_year']   = null;
    $dispData['insure2']['def']['start_month']  = null;
    $dispData['insure2']['def']['start_dt']     = null;
    $dispData['insure2']['def']['end_nengo']    = null;
    $dispData['insure2']['def']['end_year']     = null;
    $dispData['insure2']['def']['end_month']    = null;
    $dispData['insure2']['def']['end_dt']       = null;
    $dispData['insure3']['def']    = initTable('mst_user_insure3');
    $dispData['insure3']['def']['ins3_start_nengo']   = null;
    $dispData['insure3']['def']['start_year']   = null;
    $dispData['insure3']['def']['start_month']  = null;
    $dispData['insure3']['def']['start_dt']     = null;
    $dispData['insure3']['def']['ins3_end_nengo']   = null;
    $dispData['insure3']['def']['end_year']     = null;
    $dispData['insure3']['def']['end_month']    = null;
    $dispData['insure3']['def']['end_dt']       = null;
    $dispData['insure4']['def']    = initTable('mst_user_insure4');
    $dispData['insure4']['def']['ins4_start_nengo']   = null;
    $dispData['insure4']['def']['start_year']   = null;
    $dispData['insure4']['def']['start_month']  = null;
    $dispData['insure4']['def']['start_dt']     = null;
    $dispData['insure4']['def']['ins4_end_nengo']   = null;
    $dispData['insure4']['def']['end_year']     = null;
    $dispData['insure4']['def']['end_month']    = null;
    $dispData['insure4']['def']['end_dt']       = null;
    $dispData['medical']           = initTable('mst_user_medical');
    $dispData['hospital']['def']   = initTable('mst_user_hospital');
    $dispData['drug']['def']       = initTable('mst_user_drug');
    $dispData['drug']['def']['disable'] = null;
    $dispData['service']['def']    = initTable('mst_user_service');
    $dispData['emergency']['def']  = initTable('mst_user_emergency');
    $dispData['person']['def']     = initTable('mst_user_person');
    $dispData['family']['def']     = initTable('mst_user_family');
    $dispData['introduct']['def']  = initTable('mst_user_introduct');
    $dispData['image']['def']      = initTable('mst_user_image');

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 検索ボタン
    $btnSearch = h(filter_input(INPUT_GET, 'btnSearch'));

    // 検索配列
    $search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $search['kana']      = !empty($search['kana']) ? $search['kana'] : null;
    $search['status']    = !empty($search['status']) ? $search['status'] : null;
    $search['service']   = !empty($search['service']) ? $search['service'] : null;
    $search['ng']        = !empty($search['ng']) ? $search['ng'] : null;
    $search['sort']      = !empty($search['sort']) ? $search['sort'] : null;
    $search['type']      = !empty($search['type']) ? $search['type'] : 1;
    $search['start_day'] = !empty($search['start_day']) ? $search['start_day'] : THISMONTHFIRST;
    $search['end_day']   = !empty($search['end_day']) ? $search['end_day'] : THISMONTHLAST;

    // 拠点ID
    $placeId = filter_input(INPUT_GET, 'place');
    if (!$placeId) {
        $placeId = !empty($_SESSION['place']) ? $_SESSION['place'] : null;
    }

    if (empty($btnSearch)) {
        //    $search['status']    = !empty($search['status'])    ? $search['status']    : "契約中";
        $search['status'] = "契約中";
    }

    /*-- 更新用パラメータ ---------------------------------------*/

    // 展開対象利用者
    $userAry = filter_input(INPUT_POST, 'userAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    // 展開処理ボタン
    $btnMakePlan = h(filter_input(INPUT_POST, 'btnMakePlanAll'));

    /*-- その他パラメータ ---------------------------------------*/

    // ページャー
    $page = h(filter_input(INPUT_GET, 'page'));


    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 更新用配列作成 ----------------------------------------*/

    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    // 展開処理
    if ($btnMakePlan && $userAry) {
        $res = makePlan($loginUser, $placeId, $userAry, $search['type'], $search['start_day'], $search['end_day']);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        $_SESSION['notice']['success'][] = '展開処理が正常に終了しました';
    }

    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/

    // 選択肢
    $codeList = getCode();

    // サービス利用区分 カラー定義用クラス
    $where = array();
    $where['delete_flg'] = 0;
    $where['group_div']  = '利用者基本情報_基本情報';
    $where['type']       = 'サービス利用区分';
    $target = 'name,remarks';
    $temp = select('mst_code', $target, $where);
    foreach ($temp as $val) {
        $name  = $val['name'];
        $class = $val['remarks'];
        $clrList[$name] = $class;
    }

    // 拠点に紐づくユーザ一覧取得
    $temp = getUserList($placeId, $search, $search['sort']);
    foreach ($temp as $usrId => $val) {
        $usrIdList[] = $usrId;
    }

    // ユーザー情報
    $userData = getUserAry($usrIdList, $search['sort']);

    // データ補完
    foreach ($userData as $userId => $val) {

        if (isset($val['standard']) === false) {
            continue;
        }

        // 初期化
        $dat = $val['standard'];

        // カナ検索
        if (!empty($search['kana'])) {
            $word = $dat['last_kana'] . '|' . $dat['first_kana'];
            if (strpos($word, $search['kana']) === false) {
                continue;
            }
        }

        // 契約状態判定
        if (!empty($val['office1'])) {
            $dat['status'] = '契約中';
            $dat['st_cls'] = 'status';
        } else {
            $dat['status'] = '停止中';
            $dat['st_cls'] = 'status2';
        }
        if ($search['status']) {
            if ($search['status'] !== $dat['status']) {
                continue;
            }
        }

        // 年齢、住所、TEL、要介護度
        $dat['age']       = getAge($dat['birthday']);
        $dat['address']   = $dat['prefecture'] . $dat['area'] . $dat['address1'] . $dat['address2'] . $dat['address3'];
        $dat['tel']       = $dat['tel1'];
        $dat['care_rank'] = isset($val['insure1'][0]['care_rank'])
                ? $val['insure1'][0]['care_rank']
                : null;
        $dat['sv_cls'] = null;
        if ($dat['service_type']) {
            $dat['sv_cls'] = isset($clrList[$dat['service_type']])
                    ? $clrList[$dat['service_type']]
                    : null;
        }

        // 前回展開日 ★未実装★
        $dat['last_day'] = null;

        // NG状態
        $chkAry = checkUserList($val);

        // NGメッセージ
        $dat['ng'] = null;
        foreach ($chkAry as $msg) {
            $dat['ng'] = $dat['ng'] ? $dat['ng'] . '<br>' . $msg : $msg;
        }

        // NG判定
        if ($search['ng'] && !$dat['ng']) {
            continue;
        }

        // 格納
        $tgtData[] = $dat;
    }


    /* -- その他 --------------------------------------------*/

    // ページャー
    $dispData = getPager($tgtData, $page, $line);

    //debug($dispData);
    //exit;
    /* ===================================================
     * 例外処理
     * ===================================================
     */
} catch (Exception $e) {
    if ($execEnv === 'pro' || $execEnv === 'stg') {
        $_SESSION['err'] = !empty($err) ? $err : array();
        header("Location:" . ERROR_PAGE);
        exit;
    } else {
        debug($e);
        exit;
    }
}
