<?php

//=====================================================================
// 利用者編集
//=====================================================================
try {
    /* ===================================================
     * 初期処理
     * ===================================================
     */
    echo 'A';
    exit;
    /*--共通ファイル呼び出し-------------------------------------*/
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/user/edit/function/func_user.php');

    /*--変数定義-------------------------------------------------*/
    //unset($_SESSION['input']);
    // 初期化
    $err      = array();
    $tgtData  = array();
    $userData = array();
    $delFlg   = false;
    $entryFlg = null;

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

    // 重複関連
    $dplIds = array();
    $dplList = array();
    $ofc2Data = array();
    $dplIcon['other_id']  = null;
    $dplIcon['kana']      = null;
    $dplIcon['birthday']  = null;

    // ファイル登録用フィールド定義
    $columns = array();
    $columns['image'] = false;


    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 利用者ID
    $userId = h(filter_input(INPUT_GET, 'user'));

    // タブNo
    $tabAct = h(filter_input(INPUT_POST, 'tab'));
    if ($tabAct) {
        $nextPage = $server['scriptName'] . '?user=' . $userId . '&tab=' . $tabAct;
        header("Location:" . $nextPage);
        exit;
    }
    $tab = h(filter_input(INPUT_GET, 'tab'));
    $tab = ($tab < 7) && ($tab > 0) ? $tab : 1;


    // 新規時には基本情報タブのみを選択許可
    if (!$userId && $tab != 1) {
        $_SESSION['notice']['error'][] = '基本情報が登録されていません';
        $tab = 1;
    }

    // 表示条件
    $search =  filter_input(INPUT_GET, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $search = $search ? $search : array();

    $search['sijisyo']      = !empty($search['sijisyo']) ? 1 : 0;
    $search['drg_disp_flg'] = !empty($search['drg_disp_flg']) ? 1 : 0;

    /*-- 更新用パラメータ ---------------------------------------*/

    // 更新ボタン
    $btnEntry = h(filter_input(INPUT_POST, 'btnEntry'));
    if ($btnEntry) {
        $_SESSION['input'] = array();
    }

    // 更新配列(基本情報)
    $upAry = filter_input(INPUT_POST, 'upAry', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upAry = $upAry ? $upAry : array();

    // 更新配列(支払方法)
    $upPay = filter_input(INPUT_POST, 'upPay', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upPay = $upPay ? $upPay : array();

    // 更新配列(所属事業所)
    $upOfc1 = filter_input(INPUT_POST, 'upOfc1', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upOfc1 = $upOfc1 ? $upOfc1 : array();

    // 更新配列(居宅支援事業所)
    $upOfc2 = filter_input(INPUT_POST, 'upOfc2', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upOfc2 = $upOfc2 ? $upOfc2 : array();

    // 更新配列(介護保険証)
    $upIns1 = filter_input(INPUT_POST, 'upIns1', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upIns1 = $upIns1 ? $upIns1 : array();

    // 更新配列(給付情報)
    $upIns2 = filter_input(INPUT_POST, 'upIns2', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upIns2 = $upIns2 ? $upIns2 : array();

    // 更新配列(医療保険証)
    $upIns3 = filter_input(INPUT_POST, 'upIns3', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upIns3 = $upIns3 ? $upIns3 : array();

    // 更新配列(公費)
    $upIns4 = filter_input(INPUT_POST, 'upIns4', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upIns4 = $upIns4 ? $upIns4 : array();

    // 更新配列(医療情報)
    $upMdc = filter_input(INPUT_POST, 'upMdc', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upMdc = $upMdc ? $upMdc : array();

    // 更新配列(医療機関履歴)
    $upHsp = filter_input(INPUT_POST, 'upHsp', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upHsp = $upHsp ? $upHsp : array();

    // 更新配列(薬剤情報)
    $upDrg = filter_input(INPUT_POST, 'upDrg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upDrg = $upDrg ? $upDrg : array();

    // 更新配列(サービス)
    $upSvc = filter_input(INPUT_POST, 'upSvc', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upSvc = $upSvc ? $upSvc : array();

    // 更新配列(緊急連絡先)
    $upEmg = filter_input(INPUT_POST, 'upEmg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upEmg = $upEmg ? $upEmg : array();

    // 更新配列(キーパーソン)
    $upPsn = filter_input(INPUT_POST, 'upPsn', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upPsn = $upPsn ? $upPsn : array();

    // 更新配列(家族構成)
    $upFml = filter_input(INPUT_POST, 'upFml', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upFml = $upFml ? $upFml : array();

    // 更新配列(紹介履歴)
    $upInt = filter_input(INPUT_POST, 'upInt', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upInt = $upInt ? $upInt : array();

    // 更新配列(画像)
    $upImg = filter_input(INPUT_POST, 'upImg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upImg = $upImg ? $upImg : array();

    // 加工用更新配列
    $upDummy = filter_input(INPUT_POST, 'upDummy', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $upDummy = $upDummy ? $upDummy : array();

    // 削除ボタン
    $btnDelOfc1 = h(filter_input(INPUT_POST, 'btnDelOfc1'));
    $btnDelOfc2 = h(filter_input(INPUT_POST, 'btnDelOfc2'));
    $btnDelIns1 = h(filter_input(INPUT_POST, 'btnDelIns1'));
    $btnDelIns2 = h(filter_input(INPUT_POST, 'btnDelIns2'));
    $btnDelIns3 = h(filter_input(INPUT_POST, 'btnDelIns3'));
    $btnDelIns4 = h(filter_input(INPUT_POST, 'btnDelIns4'));
    $btnDelHsp  = h(filter_input(INPUT_POST, 'btnDelHsp'));
    $btnDelDrg  = h(filter_input(INPUT_POST, 'btnDelDrg'));
    $btnDelSvc  = h(filter_input(INPUT_POST, 'btnDelSvc'));
    $btnDelInt  = h(filter_input(INPUT_POST, 'btnDelInt'));
    $btnDelFml  = h(filter_input(INPUT_POST, 'btnDelFml'));
    $btnDelImg  = h(filter_input(INPUT_POST, 'btnDelImg'));
    $btnDelOffice  = h(filter_input(INPUT_POST, 'btnDelOffice'));

    // 入力確認(Yes)
    $btnEntryFix  = h(filter_input(INPUT_POST, 'btnEntryFix'));
    if ($btnEntryFix) {
        $btnEntry = true;
        $input = $_SESSION['input'];
        if (!empty($input['standard'])) {
            $upAry = $input['standard'];
        }
        if (!empty($input['dummy'])) {
            $upDummy = $input['dummy'];
        }
        if (!empty($input['pay'])) {
            $upPay = $input['pay'];
        }
        if (!empty($input['office1'])) {
            $office1 = $input['office1'];
        }
        if (!empty($input['office2'])) {
            $office2 = $input['office2'];
        }
        if (!empty($input['upOfc1'])) {
            $upOfc1 = $input['upOfc1'];
        }
        if (!empty($input['upOfc2'])) {
            $upOfc2 = $input['upOfc2'];
        }
        if (!empty($input['insure1'])) {
            $upIns1 = $input['insure1'];
        }
        if (!empty($input['insure2'])) {
            $upIns2 = $input['insure2'];
        }
        if (!empty($input['insure3'])) {
            $upIns3 = $input['insure3'];
        }
        if (!empty($input['insure4'])) {
            $upIns4 = $input['insure4'];
        }
        if (!empty($input['medical'])) {
            $upMdc = $input['medical'];
        }
        if (!empty($input['hospital'])) {
            $upHsp = $input['hospital'];
        }
        if (!empty($input['drug'])) {
            $upDrg = $input['drug'];
        }
        if (!empty($input['service'])) {
            $upSvc = $input['service'];
        }
        if (!empty($input['emrgency'])) {
            $upEmg = $input['emergency'];
        }
        if (!empty($input['personal'])) {
            $upPsn = $input['personal'];
        }
        if (!empty($input['family'])) {
            $upFml = $input['family'];
        }
        if (!empty($input['introduct'])) {
            $upInt = $input['introduct'];
        }
        if (!empty($input['image'])) {
            $upImg = $input['image'];
        }
    }

    // 入力確認(No)
    $btnEntryNo = h(filter_input(INPUT_POST, 'btnEntryNo'));
    //if ($btnClr){
    //    if (isset($_SESSION['input'])){
    //        unset($_SESSION['input']);
    //    }
    //}

    /*-- その他パラメータ ---------------------------------------*/

    // 一覧に戻る
    $btnRtn = h(filter_input(INPUT_POST, 'btnReturn'));
    if ($btnRtn) {
        $nextPage = '/user/list/';
        header("Location:" . $nextPage);
        exit;
    }

    //if ($btnEntry){
    //    debug($upIns4);
    //    debug($upDummy);
    //    exit;
    //}
    //debug($_SESSION);
    //exit;

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 入力内容判定 ------------------------------------------*/
    if ($btnEntry) {

        // 基本情報
        if ($tab == 1) {

            /* -- 禁忌 --------------------------------------------------- */

            // 利用者IDなし → NG
            //    if (empty($upAry['other_id'])){
            //        $_SESSION['notice']['error'][] = '利用者IDの指定がありません';
            //    }
            // 契約事業所
            if (empty($userId)) {
                if (empty($upOfc1[0]['office_id'])) {
                    $_SESSION['notice']['error'][] = '事業所IDの指定がありません';
                }
                if (empty($upOfc1[0]['office_name'])) {
                    $_SESSION['notice']['error'][] = '事業所名称の指定がありません';
                }
            } else {
                $where = array();
                $where['delete_flg'] = 0;
                $where['user_id']    = $userId;
                $temp = select('mst_user_office1', 'unique_id', $where);
                if (!$temp) {
                    if (empty($upOfc1[0]['office_id'])) {
                        $_SESSION['notice']['error'][] = '事業所IDの指定がありません';
                    }
                    if (empty($upOfc1[0]['office_name'])) {
                        $_SESSION['notice']['error'][] = '事業所名称の指定がありません';
                    }
                } else {
                    if (empty($upOfc1[0]['office_id']) && empty($upOfc1[0]['office_name'])) {
                        unset($upOfc1[0]);
                    }
                }
            }

            // カナ(苗字、名前)
            if (empty($upAry['last_kana'])) {
                $_SESSION['notice']['error'][] = 'カナ(苗字)の指定がありません';
            }
            if (empty($upAry['first_kana'])) {
                $_SESSION['notice']['error'][] = 'カナ(名前)の指定がありません';
            }

            // 性別
            if (empty($upAry['sex'])) {
                $_SESSION['notice']['error'][] = '性別の指定がありません';
            }

            // 生年月日
            if (empty($upDummy['std_year'])) {
                $_SESSION['notice']['error'][] = '生年月日(年)の指定がありません';
            }
            if (empty($upDummy['std_month'])) {
                $_SESSION['notice']['error'][] = '生年月日(月)の指定がありません';
            }
            if (empty($upDummy['std_day'])) {
                $_SESSION['notice']['error'][] = '生年月日(日)の指定がありません';
            }
            if (empty($upDummy['std_year']) || empty($upDummy['std_month']) || empty($upDummy['std_day'])) {
                unset($upDummy['std_year']);
                unset($upDummy['std_month']);
                unset($upDummy['std_day']);
                unset($upAry['birthday']);
            }

            // 住所(都道府県,市区町村,町域)
            if (empty($upAry['prefecture'])) {
                $_SESSION['notice']['error'][] = '住所(都道府県)の指定がありません';
            }
            if (empty($upAry['area'])) {
                $_SESSION['notice']['error'][] = '住所(市区町村)の指定がありません';
            }
            if (empty($upAry['address1'])) {
                $_SESSION['notice']['error'][] = '住所(町域)の指定がありません';
            }

            // 電話番号
            if (empty($upAry['tel1'])) {
                $_SESSION['notice']['error'][] = '電話番号の指定がありません';
            }

            // サービス利用区分
            if (empty($upAry['service_type'])) {
                $_SESSION['notice']['error'][] = 'サービス利用区分の指定がありません';
            }

            /* -- 警告 --------------------------------------------------- */

            // 利用者ID重複
            if (!empty($upAry['other_id'])) {
                $where = array();
                $where['delete_flg'] = 0;
                $where['other_id'] = $upAry['other_id'];
                $temp = select('mst_user', 'unique_id', $where);
                $chkCnt = !empty($upAry['unique_id']) ? 1 : 0;
                if (count($temp) > $chkCnt) {
                    $_SESSION['notice']['warning'][] = '利用者IDが重複しています';
                }
            }

            // カナ名重複
            if (!empty($upAry['last_kana']) || !empty($upAry['first_kana'])) {
                $where = array();
                $where['delete_flg'] = 0;
                $where['last_kana']  = $upAry['last_kana'];
                $where['first_kana'] = $upAry['first_kana'];
                $temp = select('mst_user', 'unique_id', $where);
                $chkCnt = !empty($upAry['unique_id']) ? 1 : 0;
                if (count($temp) > $chkCnt) {
                    $_SESSION['notice']['warning'][] = 'カナ名が重複しています';
                }
            }

            // 生年月日重複
            if (!empty($upDummy['std_year']) && !empty($upDummy['std_month']) && !empty($upDummy['std_day'])) {
                // 西暦→和暦
                $upAry['birthday'] = $upDummy['std_year'] . '-' . $upDummy['std_month'] . '-' . $upDummy['std_day'];
            } elseif (!empty($upDummy['std_nengo']) && !empty($upDummy['std_wareki']) && !empty($upDummy['std_month']) && !empty($upDummy['std_day'])) {
                // 和暦→西暦
                $upDummy['std_year'] = str_replace('年', '', chgJpToAdYear($upDummy['std_nengo'] . $upDummy['std_wareki']));
                $upAry['birthday'] = $upDummy['std_year'] . '-' . $upDummy['std_month'] . '-' . $upDummy['std_day'];
            } else {
                $upAry['birthday'] = null;
            }
            if (!empty($upAry['birthday'])) {
                $where = array();
                $where['delete_flg'] = 0;
                $where['birthday'] = $upAry['birthday'];
                $temp = select('mst_user', 'unique_id', $where);
                $chkCnt = !empty($upAry['unique_id']) ? 1 : 0;
                if (count($temp) > $chkCnt) {
                    $_SESSION['notice']['warning'][] = '生年月日が重複しています';
                }
            }

            if (!empty($upImg) && $tab == 1) {
                foreach ($upImg as $key => $img) {
                    if (!empty($_FILES[$key]['name']) && empty($img['tag'])) {
                        $_SESSION['notice']['error'][] = 'タグが設定されていません';
                        break;
                    }
                }
            }

            // 利用者指定がなく、基本情報以外を登録(禁忌)
        } elseif (!$userId && $tab != 1) {
            $_SESSION['notice']['error'][] = '基本情報が登録されていません';
        }

        // 保険証
        if ($tab == 3) {

            if ($userId) {

                // 認定日
                if (!empty($upDummy['ins1_certif_nengo']) && !empty($upDummy['ins1_certif_year']) && !empty($upDummy['ins1_certif_month']) && !empty($upDummy['ins1_certif_dt'])) {
                    $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins1_certif_nengo'] . $upDummy['ins1_certif_year'] . '年'));
                    $upIns1['certif_day'] = $tgtYear . '-' . $upDummy['ins1_certif_month'] . '-' . $upDummy['ins1_certif_dt'];
                } else {
                    $upIns1['certif_day'] = null;
                }
                // 介護保険証 有効期間重複チェック(禁忌)
                if (!empty($upDummy['ins1_start_nengo']) && !empty($upDummy['ins1_start_year1']) && !empty($upDummy['ins1_start_month1']) && !empty($upDummy['ins1_start_dt1'])) {
                    $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins1_start_nengo'] . $upDummy['ins1_start_year1'] . '年'));
                    $upIns1['start_day1'] = $tgtYear . '-' . $upDummy['ins1_start_month1'] . '-' . $upDummy['ins1_start_dt1'];
                } else {
                    $upIns1['start_day1'] = null;
                }
                if (!empty($upDummy['ins1_end_nengo']) && !empty($upDummy['ins1_end_year1']) && !empty($upDummy['ins1_end_month1']) && !empty($upDummy['ins1_end_dt1'])) {
                    $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins1_end_nengo'] . $upDummy['ins1_end_year1'] . '年'));
                    $upIns1['end_day1'] = $tgtYear . '-' . $upDummy['ins1_end_month1'] . '-' . $upDummy['ins1_end_dt1'];
                } else {
                    $upIns1['end_day1'] = null;
                }
                if (!empty($upIns1['start_day1']) || !empty($upIns1['end_day1'])) {
                    $tgtSt = !empty($upIns1['start_day1'])
                            ? $upIns1['start_day1']
                            : '0000-00-00 00:00:00';
                    $tgtEd = !empty($upIns1['end_day1'])
                            ? $upIns1['end_day1']
                            : '2099-12-31 23:59:59';
                    $where = array();
                    $where['delete_flg'] = 0;
                    $where['user_id'] = $userId;
                    $temp = select('mst_user_insure1', 'start_day1,end_day1,unique_id', $where);
                    if ($temp) {
                        $insChk = false;
                        foreach ($temp as $val) {
                            // 自身データはチェック対象外
                            if ($upIns1['unique_id'] === $val['unique_id']) {
                                continue;
                            }
                            $stDay = $val['start_day1'];
                            $edDay = $val['end_day1'] ? $val['end_day1'] : '2099-12-31 23:59:59';

                            if (($stDay < $tgtSt) && ($edDay < $tgtSt)
                                    || (($stDay > $tgtEd) && ($edDay > $tgtEd))) {
                                // 判定OKは何もしない
                            } else {
                                // 判定NG
                                $insChk = true;
                                break;
                            }
                        }
                        if ($insChk) {
                            $_SESSION['notice']['warning'][] = '介護保険証の認定有効期間が重複しています';
                        }
                    }
                }
            }
        }
    }

    // 入力判定NGの場合、入力途中の情報をセッションへ退避
    if (!empty($_SESSION['notice']['error'])) {
        $btnEntry = null;
        $_SESSION['input']['standard'] = $upAry;
        $_SESSION['input']['upDummy']  = $upDummy;
        $_SESSION['input']['office1']  = $upOfc1;
    }

    /* -- 削除用配列作成 ----------------------------------------*/

    // 削除時は更新配列を初期化
    if ($btnDelOfc1
            || $btnDelOfc2
            || $btnDelIns1
            || $btnDelIns2
            || $btnDelIns3
            || $btnDelIns4
            || $btnDelHsp
            || $btnDelDrg
            || $btnDelSvc
            || $btnDelInt
            || $btnDelFml
            || $btnDelImg) {
        $upAry   = array();
        $upPay   = array();
        $upOfc1  = array();
        $upOfc2  = array();
        $upIns1  = array();
        $upIns2  = array();
        $upIns3  = array();
        $upIns4  = array();
        $upMdc   = array();
        $upHsp   = array();
        $upDrg   = array();
        $upSvc   = array();
        $upEmg   = array();
        $upPsn   = array();
        $upFml   = array();
        $upInt   = array();
        $upImg   = array();
        $upDummy = array();
    }

    // 削除用更新配列
    if ($btnDelOfc1) {
        $upOfc1[0]['unique_id']  = $btnDelOfc1;
        $upOfc1[0]['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelOfc2) {
        $upOfc2['unique_id']  = $btnDelOfc2;
        $upOfc2['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelIns1) {
        $upIns1['unique_id']  = $btnDelIns1;
        $upIns1['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelIns2) {
        $upIns2[0]['unique_id']  = $btnDelIns2;
        $upIns2[0]['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelIns3) {
        $upIns3['unique_id']  = $btnDelIns3;
        $upIns3['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelIns4) {
        $upIns4['unique_id']  = $btnDelIns4;
        $upIns4['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelHsp) {
        $upHsp['unique_id']  = $btnDelHsp;
        $upHsp['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelDrg) {
        $upDrg[0]['unique_id']  = $btnDelDrg;
        $upDrg[0]['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelSvc) {
        $upSvc['unique_id']  = $btnDelSvc;
        $upSvc['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelInt) {
        $upInt['unique_id']  = $btnDelInt;
        $upInt['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelFml) {
        $upFml[0]['unique_id']  = $btnDelFml;
        $upFml[0]['delete_flg'] = 1;
        $btnEntry = true;
    }
    if ($btnDelImg) {
        $upImg[0]['unique_id']  = $btnDelImg;
        $upImg[0]['delete_flg'] = 1;
        $btnEntry = true;
    }

    if ($btnDelOffice) {
        $upOfc1[0]['unique_id']  = $btnDelOffice;
        $upOfc1[0]['delete_flg'] = 1;
        $btnEntry = true;
    }

    /* -- 更新用配列作成 ----------------------------------------*/
    if ($btnEntry) {

        // 空データ判定
        $upAry  = formatDelAry('mst_user', $upAry);
        $upPay  = formatDelAry('mst_user_pay', $upPay);
        $upOfc1 = formatDelAry('mst_user_office1', $upOfc1, 2);
        $upOfc2 = formatDelAry('mst_user_office2', $upOfc2);
        $upIns1 = formatDelAry('mst_user_insure1', $upIns1);
        $upIns2 = formatDelAry('mst_user_insure2', $upIns2, 2);
        $upIns3 = formatDelAry('mst_user_insure3', $upIns3);
        $upIns4 = formatDelAry('mst_user_insure4', $upIns4);
        $upMdc  = formatDelAry('mst_user_medical', $upMdc);
        $upHsp  = formatDelAry('mst_user_hospital', $upHsp);
        $upDrg  = formatDelAry('mst_user_drug', $upDrg, 2);
        $upSvc  = formatDelAry('mst_user_service', $upSvc);
        $upEmg  = formatDelAry('mst_user_emergency', $upEmg, 2);
        $upPsn  = formatDelAry('mst_user_person', $upPsn, 2);
        $upFml  = formatDelAry('mst_user_family', $upFml, 2);
        $upInt  = formatDelAry('mst_user_introduct', $upInt);
        $upImg  = formatDelAry('mst_user_image', $upImg, 2);
    }

    // 入力情報補完
    if ($btnEntry) {
        //    debug($upMdc);
        //    debug($upHsp);
        //    debug($upDrg);
        //    exit;
        // 基本情報 生年月日
        if ($upAry) {
            if (!empty($upDummy['std_year']) && !empty($upDummy['std_month']) && !empty($upDummy['std_day'])) {
                // 西暦→和暦
                $upAry['birthday'] = $upDummy['std_year'] . '-' . $upDummy['std_month'] . '-' . $upDummy['std_day'];
            } elseif (!empty($upDummy['std_nengo']) && !empty($upDummy['std_wareki']) && !empty($upDummy['std_month']) && !empty($upDummy['std_day'])) {
                // 和暦→西暦
                $upDummy['std_year'] = str_replace('年', '', chgJpToAdYear($upDummy['std_nengo'] . $upDummy['std_wareki']));
                $upAry['birthday'] = $upDummy['std_year'] . '-' . $upDummy['std_month'] . '-' . $upDummy['std_day'];
            } else {
                $upAry['birthday'] = null;
            }
        }

        //    debug($upOfc1);exit;
        //
        //    // 契約事業所
        //    if($upOfc1 && $userId){
        //
        //        $ofc1List = array();
        //        $where    = array();
        //        $where['delete_flg'] = 0;
        //        $where['user_id'] = $userId;
        //        $temp = select('mst_user_office', 'unique_id', $where);
        //        foreach($temp as $val){
        //            $ofc1List[$val] = TRUE;
        //        }
        //
        ////        foreach($upOfc1 as $val){
        ////            $ofc1List[$val['unique_id']]
        ////        }
        //    }

        // 画像
        if ($upImg) {
            foreach ($upImg as $key => $val) {

                // 登録日
                if (!empty($val['unique_id']) && !isset($val['entry_day'])) {
                    $val['entry_day'] = TODAY;
                }

                // 格納
                $upImg[$key] = $val;
            }
        }

        // 介護保険証
        if ($upIns1) {

            // 認定日
            if (!empty($upDummy['ins1_certif_nengo']) && !empty($upDummy['ins1_certif_year']) && !empty($upDummy['ins1_certif_month']) && !empty($upDummy['ins1_certif_dt'])) {
                $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins1_certif_nengo'] . $upDummy['ins1_certif_year'] . '年'));
                $upIns1['certif_day'] = $tgtYear . '-' . $upDummy['ins1_certif_month'] . '-' . $upDummy['ins1_certif_dt'];
            } else {
                $upIns1['certif_day'] = null;
            }
            // 有効期限
            if (!empty($upDummy['ins1_start_nengo']) && !empty($upDummy['ins1_start_year1']) && !empty($upDummy['ins1_start_month1']) && !empty($upDummy['ins1_start_dt1'])) {
                $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins1_start_nengo'] . $upDummy['ins1_start_year1'] . '年'));
                $upIns1['start_day1'] = $tgtYear . '-' . $upDummy['ins1_start_month1'] . '-' . $upDummy['ins1_start_dt1'];
            } else {
                $upIns1['start_day1'] = null;
            }
            if (!empty($upDummy['ins1_end_nengo']) && !empty($upDummy['ins1_end_year1']) && !empty($upDummy['ins1_end_month1']) && !empty($upDummy['ins1_end_dt1'])) {
                $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins1_end_nengo'] . $upDummy['ins1_end_year1'] . '年'));
                $upIns1['end_day1'] = $tgtYear . '-' . $upDummy['ins1_end_month1'] . '-' . $upDummy['ins1_end_dt1'];
            } else {
                $upIns1['end_day1'] = null;
            }
            // 介護保険証 区分支給限度額管理期間
            if (!empty($upDummy['ins1_start_nengo2']) && !empty($upDummy['ins1_start_year2']) && !empty($upDummy['ins1_start_month2']) && !empty($upDummy['ins1_start_dt2'])) {
                $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins1_start_nengo2'] . $upDummy['ins1_start_year2'] . '年'));
                $upIns1['start_day2'] = $tgtYear . '-' . $upDummy['ins1_start_month2'] . '-' . $upDummy['ins1_start_dt2'];
            } else {
                $upIns1['start_day2'] = null;
            }
            if (!empty($upDummy['ins1_end_nengo2']) && !empty($upDummy['ins1_end_year2']) && !empty($upDummy['ins1_end_month2']) && !empty($upDummy['ins1_end_dt2'])) {
                $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins1_end_nengo2'] . $upDummy['ins1_end_year2'] . '年'));
                $upIns1['end_day2'] = $tgtYear . '-' . $upDummy['ins1_end_month2'] . '-' . $upDummy['ins1_end_dt2'];
            } else {
                $upIns1['end_day2'] = null;
            }
        }

        // 給付情報 有効期限
        if ($upIns2 && !$btnDelIns2) {
            foreach ($upIns2 as $key => $val) {

                // 初期化
                $upIns2[$key]['start_day'] = null;
                $upIns2[$key]['end_day'] = null;

                if (isset($upDummy['ins2'][$key])) {
                    $dmyAry = $upDummy['ins2'][$key];
                    if (!empty($dmyAry['start_nengo']) && !empty($dmyAry['start_year']) && !empty($dmyAry['start_month']) && !empty($dmyAry['start_dt'])) {
                        $tgtYear = str_replace('年', '', chgJpToAdYear($dmyAry['start_nengo'] . $dmyAry['start_year'] . '年'));
                        $upIns2[$key]['start_day'] = $tgtYear . '-' . $dmyAry['start_month'] . '-' . $dmyAry['start_dt'];
                    }
                    if (!empty($dmyAry['end_nengo']) && !empty($dmyAry['end_year']) && !empty($dmyAry['end_month']) && !empty($dmyAry['end_dt'])) {
                        $tgtYear = str_replace('年', '', chgJpToAdYear($dmyAry['end_nengo'] . $dmyAry['end_year'] . '年'));
                        $upIns2[$key]['end_day'] = $tgtYear . '-' . $dmyAry['end_month'] . '-' . $dmyAry['end_dt'];
                    }
                }
            }
        }

        // 医療保険証 有効期間
        if ($upIns3) {
            if (!empty($upDummy['ins3_start_nengo']) && !empty($upDummy['ins3_start_year']) && !empty($upDummy['ins3_start_month']) && !empty($upDummy['ins3_start_dt'])) {
                $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins3_start_nengo'] . $upDummy['ins3_start_year'] . '年'));
                $upIns3['start_day'] = $tgtYear . '-' . $upDummy['ins3_start_month'] . '-' . $upDummy['ins3_start_dt'];
            } else {
                $upIns3['start_day'] = null;
            }
            if (!empty($upDummy['ins3_end_nengo']) && !empty($upDummy['ins3_end_year']) && !empty($upDummy['ins3_end_month']) && !empty($upDummy['ins3_end_dt'])) {
                $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins3_end_nengo'] . $upDummy['ins3_end_year'] . '年'));
                $upIns3['end_day'] = $tgtYear . '-' . $upDummy['ins3_end_month'] . '-' . $upDummy['ins3_end_dt'];
            } else {
                $upIns3['end_day'] = null;
            }
        }

        // 公費 開始/終了
        if ($upIns4
                || (!empty($upDummy['ins4_start_nengo']) && !empty($upDummy['ins4_start_year']) && !empty($upDummy['ins4_start_month']) && !empty($upDummy['ins4_start_dt']))
                || (!empty($upDummy['ins4_end_nengo']) && !empty($upDummy['ins4_end_year']) && !empty($upDummy['ins4_end_month']) && !empty($upDummy['ins4_end_dt']))) {
            if (!empty($upDummy['ins4_start_nengo']) && !empty($upDummy['ins4_start_year']) && !empty($upDummy['ins4_start_month']) && !empty($upDummy['ins4_start_dt'])) {
                $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins4_start_nengo'] . $upDummy['ins4_start_year'] . '年'));
                $upIns4['start_day'] = $tgtYear . '-' . $upDummy['ins4_start_month'] . '-' . $upDummy['ins4_start_dt'];
            } else {
                $upIns4['start_day'] = null;
            }
            if (!empty($upDummy['ins4_end_nengo']) && !empty($upDummy['ins4_end_year']) && !empty($upDummy['ins4_end_month']) && !empty($upDummy['ins4_end_dt'])) {
                $tgtYear = str_replace('年', '', chgJpToAdYear($upDummy['ins4_end_nengo'] . $upDummy['ins4_end_year'] . '年'));
                $upIns4['end_day'] = $tgtYear . '-' . $upDummy['ins4_end_month'] . '-' . $upDummy['ins4_end_dt'];
            } else {
                $upIns4['end_day'] = null;
            }
        }

        // 主治医情報
        echo 'Aaaa';
        exit;
        if ($upHsp) {
            debug($upHsp);
            exit;
            if (!empty($upHsp['name']) && (mb_strlen($upHsp['name']) < 16)) {
                $upHsp['disp_name'] = $upHsp['name'];
            }
        }

    }


    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */

    // 基本情報
    if ($btnEntry && $upAry && $tab == 1) {
        $res = upsert($loginUser, 'mst_user', $upAry);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
        $userId = $res;
    }
    // 支払方法
    if ($btnEntry && $upPay && $tab == 2) {
        $upPay['user_id'] = $userId;
        $res = upsert($loginUser, 'mst_user_pay', $upPay);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 所属事業所
    if ($btnEntry && $upOfc1 && $tab == 1) {
        foreach ($upOfc1 as $key => $val) {
            $upOfc1[$key]['user_id'] = $userId;
        }
        $res = multiUpsert($loginUser, 'mst_user_office1', $upOfc1);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 居宅支援事業所
    if ($btnEntry && $upOfc2 && $tab == 3) {
        $upOfc2['user_id'] = $userId;
        $res = upsert($loginUser, 'mst_user_office2', $upOfc2);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 介護保険証
    if ($btnEntry && $upIns1 && $tab == 3) {
        $upIns1['user_id'] = $userId;
        $res = upsert($loginUser, 'mst_user_insure1', $upIns1);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 給付情報
    if ($btnEntry && $upIns2 && $tab == 3) {
        foreach ($upIns2 as $key => $val) {
            $upIns2[$key]['user_id'] = $userId;
        }
        $res = multiUpsert($loginUser, 'mst_user_insure2', $upIns2);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 医療保険証
    if ($btnEntry && $upIns3 && $tab == 3) {
        $upIns3['user_id'] = $userId;
        $res = upsert($loginUser, 'mst_user_insure3', $upIns3);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 公費
    if ($btnEntry && $upIns4 && $tab == 3) {
        $upIns4['user_id'] = $userId;
        $res = upsert($loginUser, 'mst_user_insure4', $upIns4);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 医療情報
    if ($btnEntry && $upMdc && $tab == 4) {
        $upMdc['user_id'] = $userId;
        $res = upsert($loginUser, 'mst_user_medical', $upMdc);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 医療機関履歴
    if ($btnEntry && $upHsp && $tab == 4) {
        $upHsp['user_id'] = $userId;

        $upHsp['select1'] = isset($upHsp['select1']) ? 1 : 0;
        $res = upsert($loginUser, 'mst_user_hospital', $upHsp);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 薬剤情報
    if ($btnEntry && $upDrg && $tab == 4) {
        foreach ($upDrg as $key => $val) {
            $upDrg[$key]['user_id'] = $userId;
        }

        $res = multiUpsert($loginUser, 'mst_user_drug', $upDrg);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // サービス
    if ($btnEntry && $upSvc && $tab == 4) {
        $upSvc['user_id'] = $userId;
        $res = upsert($loginUser, 'mst_user_service', $upSvc);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 緊急連絡先
    if ($btnEntry && $upEmg && $tab == 5) {
        foreach ($upEmg as $key => $val) {
            $upEmg[$key]['user_id'] = $userId;
        }
        $res = multiUpsert($loginUser, 'mst_user_emergency', $upEmg);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // キーパーソン
    if ($btnEntry && $upPsn && $tab == 5) {
        foreach ($upPsn as $key => $val) {
            $upPsn[$key]['user_id'] = $userId;
        }
        $res = multiUpsert($loginUser, 'mst_user_person', $upPsn);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 家族構成
    if ($btnEntry && $upFml && $tab == 5) {
        foreach ($upFml as $key => $val) {
            $upFml[$key]['user_id'] = $userId;
        }
        $res = multiUpsert($loginUser, 'mst_user_family', $upFml);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }

    // 紹介履歴
    if ($btnEntry && $upInt && $tab == 6) {

        $upInt['user_id'] = $userId;
        $res = upsert($loginUser, 'mst_user_introduct', $upInt);
        if (isset($res['err'])) {
            $err[] = 'システムエラーが発生しました';
            throw new Exception();
        }
    }
    // 画像
    if ($btnEntry && $upImg && $tab == 1) {
        foreach ($upImg as $key => $upImg2) {

            // DB更新
            $upImg2['user_id'] = $userId;
            $upImg2['entry_day'] = date('Y-m-d');
            $res = upsert($loginUser, 'mst_user_image', $upImg2);
            if (isset($res['err'])) {
                $err[] = 'システムエラーが発生しました';
                throw new Exception();
            }

            // 登録済み情報取得
            $imgId = $res;
            $where = array();
            $where['unique_id'] = $imgId;
            $temp = select('mst_user_image', '*', $where);
            $tgtData = $temp[0];

            // ファイル更新
            $res = fileDataUpdate($loginUser, $_FILES[$key], '/upload/user', 'mst_user_image', $imgId, $columns, $tgtData);
            if (isset($res['err'])) {
                $err[] = '画像の更新に失敗しました';
                throw new Exception();
            }

        }
    }
    // 登録の正常完了
    if ($btnEntry) {

        // セッション配列の削除
        if (!empty($_SESSION['input'])) {
            unset($_SESSION['input']);
        }

        // 画面制御
        $nextPage = $server['scriptName'] . '?user=' . $userId . '&tab=' . $tab;
        header("Location:" . $nextPage);
        exit;
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

    // 住所情報
    $where = array();
    $where['delete_flg'] = 0;
    $target = '*';
    $orderBy = "prefecture_id ASC";
    $temp = select('mst_area', $target, $where, $orderBy);
    foreach ($temp as $val) {
        $pref = $val['prefecture_name'];
        $city = $val['city_name'];
        $areaMst[$pref][$city] = true;
    }

    // スタッフ情報
    $stfList = getData('mst_staff');

    // ユーザー情報
    $userData = getUser($userId);

    // 居宅支援事業所一覧
    $where = array();
    $where['delete_flg']  = 0;
    $target = "office_code, office_name, address, tel, fax";
    $orderBy = 'unique_id ASC';
    $temp = select('mst_user_office2', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $ofcCode = $val['office_code'];
        $val['found_day'] = $val['found_day'] == '0000-00-00' ? null : $val['found_day'];
        $ofc2Data[$ofcCode] = $val;
    }

    // 契約事業所取得
    $where = array();
    $where['delete_flg']  = 0;
    $target = "*";
    $orderBy = 'unique_id ASC';
    $temp = select('mst_user_office1', '*', $where, $orderBy);
    foreach ($temp as $val) {
        $ofcId = $val['office_id'];
        $val['start_day'] = $val['start_day'] == '0000-00-00' ? null : $val['start_day'];
        $val['end_day'] = $val['end_day'] == '0000-00-00' ? null : $val['end_day'];
        $ofc1Data[$ofcId] = $val;
    }

    // NG判定
    $ngList = checkUser($userData);

    // 入力途中の反映
    if (!empty($_SESSION['input'])) {
        $input = $_SESSION['input'];
        if (!empty($input['standard'])) {
            $userData['standard'] = array_merge($userData['standard'], $input['standard']);
        }
        if (!empty($input['dummy'])) {
            $userData['standard'] = array_merge($userData['standard'], $input['dummy']);
        }
        if (!empty($input['pay'])) {
            $userData['pay'] = array_merge($userData['pay'], $input['pay']);
        }
        if (!empty($input['office1'])) {
            $userData['office1'] = $input['office1'];
        }
        if (!empty($input['office2'])) {
            $userData['office2']['def'] = $input['office2'];
        }
        if (!empty($input['upOfc1'])) {
            $userData['upOfc1']['def'] = $input['upOfc1'];
        }
        if (!empty($input['upOfc2'])) {
            $userData['upOfc2']['def'] = $input['upOfc2'];
        }
        if (!empty($input['upOfc2'])) {
            $upOfc2 = $input['upOfc2'];
        }
        if (!empty($input['insure1'])) {
            $userData['insure1']['def'] = $input['insure1'];
        }
        if (!empty($input['upIns2'])) {
            $userData['insure2']['def'] = $input['insure2'];
        }
        if (!empty($input['insure3'])) {
            $userData['insure3']['def'] = $input['insure3'];
        }
        if (!empty($input['insure4'])) {
            $userData['insure4']['def'] = $input['insure4'];
        }
        if (!empty($input['medical'])) {
            $userData['medical'] = array_merge($userData['medical'], $input['medical']);
        }
        if (!empty($input['hospital'])) {
            $userData['hospital']['def'] = $input['hospital'];
        }
        if (!empty($input['drug'])) {
            $userData['drug']['def'] = $input['drug'];
        }
        if (!empty($input['service'])) {
            $userData['service']['def'] = $input['service'];
        }
        if (!empty($input['emergency'])) {
            $userData['emergency']['def'] = $input['emergency'];
        }
        if (!empty($input['personal'])) {
            $userData['personal']['def'] = $input['personal'];
        }
        if (!empty($input['family'])) {
            $userData['family']['def'] = $input['family'];
        }
        if (!empty($input['introduct'])) {
            $userData['introduct']['def'] = $input['introduct'];
        }
        if (!empty($input['image'])) {
            $userData['image']['def'] = $input['image'];
        }


        //    $input['standard'] = isset($input['standard']) ? $input['standard'] : array();
        //    $input['upDummy']  = isset($input['upDummy'])  ? $input['upDummy']  : array();
        //    $input['upOfc1']   = isset($input['upOfc1'])   ? $input['upOfc1']   : array();
        //    $merge = array_merge($input['standard'],$input['upDummy']);
        //    $userData['standard'] = array_merge($userData['standard'],$merge);
        //    $dispData['office1']['def'] = array_merge($dispData['office1']['def'],$input['upOfc1']);
    }

    /* -- データ補完 --------------------------------------------*/
    if ($userData) {
        foreach ($userData as $key => $val) {

            switch ($key) {

                case 'standard':

                    // 年齢
                    $birthDay = !empty($val['birthday']) ? $val['birthday'] : null;
                    $userData[$key]['age'] = getAge($birthDay);

                    // 生年月日
                    if (!empty($val['birthday'])) {
                        $temp = explode('-', $val['birthday']);
                        $userData[$key]['year']   = !empty($temp[0]) ? $temp[0] : null;
                        $userData[$key]['month']  = !empty($temp[1]) ? $temp[1] : null;
                        $userData[$key]['day']    = !empty($temp[2]) ? $temp[2] : null;
                        $tgtYear = explode('年', chgAdToJpDate($val['birthday']));
                        $userData[$key]['nengo']  = mb_substr($tgtYear[0], 0, 2);
                        $userData[$key]['wareki'] = mb_substr($tgtYear[0], 2);
                    }

                    // サービス利用区分
                    if (!empty($val['service_type'])) {
                        $val['sv_cls'] = isset($clrList[$val['service_type']])
                                ? $clrList[$val['service_type']]
                                : null;
                    }

                    // 更新情報
                    $userData[$key]['create_day'] = !empty($val['create_date'])
                        ? formatDateTime($val['create_date'], 'Y/m/d')
                        : null;
                    $userData[$key]['create_time'] = !empty($val['create_date'])
                        ? formatDateTime($val['create_date'], 'H:i')
                        : null;
                    $createUserId = !empty($val['create_user']) ? $val['create_user'] : 'dummy';
                    $userData[$key]['create_name'] = !empty($stfList[$createUserId])
                        ? $stfList[$createUserId]['last_name'] . ' ' . $stfList[$createUserId]['first_name']
                        : null;
                    $userData[$key]['update_day'] = !empty($val['update_date'])
                        ? formatDateTime($val['update_date'], 'Y/m/d')
                        : null;
                    $userData[$key]['update_time'] = !empty($val['update_date'])
                        ? formatDateTime($val['update_date'], 'H:i')
                        : null;
                    $updateUserId = !empty($val['update_user']) ? $val['update_user'] : 'dummy';
                    $userData[$key]['update_name'] = !empty($stfList[$updateUserId])
                        ? $stfList[$updateUserId]['last_name'] . ' ' . $stfList[$updateUserId]['first_name']
                        : null;

                    $tgtData[$key] = array_merge($dispData[$key], $userData[$key]);
                    break;

                case 'emergency':
                case 'person':
                    foreach ($userData[$key] as $dat) {
                        $tgtData[$key][] = $dat;
                    }
                    for ($i = 0; $i < 3; $i++) {
                        if (!isset($tgtData[$key][$i])) {
                            $tgtData[$key][$i] = $dispData[$key]['def'];
                        }
                    }
                    break;

                case 'office1':
                    $tgtData['standard']['status'] = '停止中';
                    $tgtData['standard']['st_cls'] = 'status2';
                    foreach ($userData[$key] as $dat) {
                        if (empty($dat['end_day'])) {
                            $dat['end_day'] = '9999-12-31';
                        }
                        if (!empty($dat['start_day']) && !empty($dat['end_day'])) {
                            if ($dat['start_day'] <= TODAY && $dat['end_day'] >= TODAY) {
                                $tgtData['standard']['status'] = '契約中';
                                $tgtData['standard']['st_cls'] = 'status';
                            }
                        }
                    }
                    $tgtData[$key] = array_merge($dispData[$key], $userData[$key]);
                    break;

                case 'office2':
                    foreach ($userData[$key] as $dat) {
                        if (empty($dat['end_day'])) {
                            $dat['end_day'] = '9999-12-31';
                        }
                        if ($dat['start_day'] <= TODAY && $dat['end_day'] >= TODAY) {
                            $tgtData['standard']['office2_name']   = $dat['office_name'];
                            $tgtData['standard']['office2_person'] = $dat['person_name'];
                            $tgtData['standard']['office2_tel']    = $dat['tel'];
                        }
                    }
                    $tgtData[$key] = array_merge($dispData[$key], $userData[$key]);
                    break;

                case 'hospital':
                    foreach ($userData[$key] as $seq => $dat) {
                        if (!empty($search['sijisyo']) && empty($dat['select1'])) {
                            unset($userData[$key][$seq]);
                        }
                        $tgtData['standard']['medical_hospital'] = $dat['name'];
                        $tgtData['standard']['medical_doctor']   = $dat['doctor'];
                        $tgtData['standard']['medical_tel']      = $dat['tel1'];
                    }
                    $tgtData[$key] = array_merge($dispData[$key], $userData[$key]);
                    break;

                case 'insure1':
                    foreach ($userData[$key] as $seq => $dat) {
                        if (!empty($dat['start_day1'])) {
                            $tgtYear = explode('年', chgAdToJpDate($dat['start_day1']));
                            $userData[$key][$seq]['ins1_start_nengo'] = mb_substr($tgtYear[0], 0, 2);
                            $userData[$key][$seq]['start_year1']      = mb_substr($tgtYear[0], 2);
                            $temp = explode('-', $dat['start_day1']);
                            $userData[$key][$seq]['start_month1'] = $temp[1];
                            $userData[$key][$seq]['start_dt1']    = $temp[2];
                        } else {
                            $userData[$key][$seq]['start_year1']  = null;
                            $userData[$key][$seq]['start_month1'] = null;
                            $userData[$key][$seq]['start_dt1']   = null;
                        }
                        if (!empty($dat['end_day1'])) {
                            $tgtYear = explode('年', chgAdToJpDate($dat['end_day1']));
                            $userData[$key][$seq]['ins1_end_nengo'] = mb_substr($tgtYear[0], 0, 2);
                            $userData[$key][$seq]['end_year1']      = mb_substr($tgtYear[0], 2);
                            $temp = explode('-', $dat['end_day1']);
                            $userData[$key][$seq]['end_month1'] = $temp[1];
                            $userData[$key][$seq]['end_dt1']    = $temp[2];
                        } else {
                            $userData[$key][$seq]['end_year1']  = null;
                            $userData[$key][$seq]['end_month1'] = null;
                            $userData[$key][$seq]['end_dt1']    = null;
                        }
                        if (!empty($dat['start_day2'])) {
                            $tgtYear = explode('年', chgAdToJpDate($dat['start_day2']));
                            $userData[$key][$seq]['ins1_start_nengo2'] = mb_substr($tgtYear[0], 0, 2);
                            $userData[$key][$seq]['start_year2']       = mb_substr($tgtYear[0], 2);
                            $temp = explode('-', $dat['start_day2']);
                            $userData[$key][$seq]['start_month2'] = $temp[1];
                            $userData[$key][$seq]['start_dt2']   = $temp[2];
                        } else {
                            $userData[$key][$seq]['start_year2']  = null;
                            $userData[$key][$seq]['start_month2'] = null;
                            $userData[$key][$seq]['start_dt2']   = null;
                        }
                        if (!empty($dat['end_day2'])) {
                            $tgtYear = explode('年', chgAdToJpDate($dat['end_day2']));
                            $userData[$key][$seq]['ins1_end_nengo2'] = mb_substr($tgtYear[0], 0, 2);
                            $userData[$key][$seq]['end_year2']       = mb_substr($tgtYear[0], 2);
                            $temp = explode('-', $dat['end_day2']);
                            $userData[$key][$seq]['end_month2'] = $temp[1];
                            $userData[$key][$seq]['end_dt2']    = $temp[2];
                        } else {
                            $userData[$key][$seq]['end_year2']  = null;
                            $userData[$key][$seq]['end_month2'] = null;
                            $userData[$key][$seq]['end_dt2']    = null;
                        }
                        // 認定日
                        if (!empty($dat['certif_day'])) {
                            $tgtYear = explode('年', chgAdToJpDate($dat['certif_day']));
                            $userData[$key][$seq]['ins1_certif_nengo'] = mb_substr($tgtYear[0], 0, 2);
                            $userData[$key][$seq]['certif_year']       = mb_substr($tgtYear[0], 2);
                            $temp = explode('-', $dat['certif_day']);
                            $userData[$key][$seq]['certif_month'] = $temp[1];
                            $userData[$key][$seq]['certif_dt']    = $temp[2];
                        } else {
                            $userData[$key][$seq]['certif_year']  = null;
                            $userData[$key][$seq]['certif_month'] = null;
                            $userData[$key][$seq]['certif_dt']    = null;
                        }
                    }
                    $tgtData[$key] = array_merge($dispData[$key], $userData[$key]);
                    break;

                case 'insure2':
                case 'insure3':
                case 'insure4':
                    foreach ($userData[$key] as $seq => $dat) {
                        if (!empty($dat['start_day'])) {
                            $tgtYear = explode('年', chgAdToJpDate($dat['start_day']));
                            $userData[$key][$seq]['start_nengo'] = mb_substr($tgtYear[0], 0, 2);
                            $userData[$key][$seq]['start_year']  = mb_substr($tgtYear[0], 2);
                            $temp = explode('-', $dat['start_day']);
                            $userData[$key][$seq]['start_month'] = $temp[1];
                            $userData[$key][$seq]['start_dt']    = $temp[2];
                        } else {
                            $userData[$key][$seq]['start_nengo']  = null;
                            $userData[$key][$seq]['start_year']  = null;
                            $userData[$key][$seq]['start_month'] = null;
                            $userData[$key][$seq]['start_dt']    = null;
                        }
                        if (!empty($dat['end_day'])) {
                            $tgtYear = explode('年', chgAdToJpDate($dat['end_day']));
                            $userData[$key][$seq]['end_nengo'] = mb_substr($tgtYear[0], 0, 2);
                            $userData[$key][$seq]['end_year']  = mb_substr($tgtYear[0], 2);
                            $temp = explode('-', $dat['end_day']);
                            $userData[$key][$seq]['end_month'] = $temp[1];
                            $userData[$key][$seq]['end_dt']    = $temp[2];
                        } else {
                            if ($key == 'insure2') {
                                $userData[$key][$seq]['ins2_end_nengo']  = null;
                            } elseif ($key == 'insure3') {
                                $userData[$key][$seq]['ins3_end_nengo']  = null;
                            } else {
                                $userData[$key][$seq]['ins4_end_nengo']  = null;
                            }
                            $userData[$key][$seq]['end_nengo'] = null;
                            $userData[$key][$seq]['end_year']  = null;
                            $userData[$key][$seq]['end_month'] = null;
                            $userData[$key][$seq]['end_dt']    = null;
                        }
                    }
                    $tgtData[$key] = array_merge($dispData[$key], $userData[$key]);
                    break;

                case 'drug':
                    foreach ($userData[$key] as $seq => $dat) {
                        if (empty($search['drg_disp_flg'])) {
                            if (!empty($dat['end_day']) && ($dat['end_day'] != '0000-00-00') && ($dat['end_day'] < TODAY)) {
                                unset($userData[$key][$seq]);
                            } else {
                                $userData[$key][$seq]['disable'] = false;
                            }
                        } else {
                            if (!empty($dat['end_day']) && ($dat['end_day'] != '0000-00-00') && ($dat['end_day'] < TODAY)) {
                                $userData[$key][$seq]['disable'] = true;
                            } else {
                                $userData[$key][$seq]['disable'] = false;
                            }
                        }
                    }
                    $tgtData[$key] = array_merge($dispData[$key], $userData[$key]);
                    break;

                default:
                    $tgtData[$key] = array_merge($dispData[$key], $userData[$key]);
                    break;
            }
        }
    }

    /* -- データ変換 --------------------------------------------*/
    // 描画データ
    if ($tgtData) {
        $dispData = $tgtData;
        //    debug($dispData['office1']);
    }

    //debug($_SESSION);
    //debug($dispData);
    //exit;
    //debug($dispData['hospital']);
    //debug($userData['hospital']);
    //debug($dispData['insure1']);
    //exit;
    /* -- その他 --------------------------------------------*/

    // 重複チェック
    if ($tab == 1) {

        // 利用者ID重複
        if (!empty($dispData['standard']['other_id'])) {
            $where = array();
            $where['delete_flg'] = 0;
            $where['other_id']   = $dispData['standard']['other_id'];
            $temp = select('mst_user', 'unique_id', $where);
            $cnkCnt = !empty($dispData['standard']['unique_id']) ? 1 : 0;
            if (count($temp) > $cnkCnt) {
                $dplIcon['other_id'] = true;
                foreach ($temp as $val) {
                    $tgtId = $val['unique_id'];
                    if ($tgtId !== $userId) {
                        $dplIds[$tgtId] = $tgtId;
                    }
                }
            }
        }
        // カナ名・生年月日重複
        if (!empty($dispData['standard']['last_kana'])
                || !empty($dispData['standard']['first_kana'])
                || !empty($dispData['standard']['birthday'])) {
            $where = array();
            $where['delete_flg'] = 0;
            $where['last_kana']  = $dispData['standard']['last_kana'];
            $where['first_kana'] = $dispData['standard']['first_kana'];
            $where['birthday']   = $dispData['standard']['birthday'];
            $temp = select('mst_user', 'unique_id', $where);
            $cnkCnt = !empty($dispData['standard']['unique_id']) ? 1 : 0;
            if (count($temp) > $cnkCnt) {
                $dplIcon['birthday'] = true;
                foreach ($temp as $val) {
                    $tgtId = $val['unique_id'];
                    if ($tgtId !== $userId) {
                        $dplIds[$tgtId] = $tgtId;
                    }
                }
            }
        }
    }

    /* -- 重複リスト -------------------*/
    if ($dplIds) {

        // ユーザーマスタ
        $where = array();
        $where['delete_flg'] = 0;
        $where['unique_id']  = $dplIds;
        $orderBy = 'unique_id ASC';
        $dplUser = select('mst_user', 'unique_id,last_kana,first_kana,birthday', $where, $orderBy);

        // 事業所マスタ
        $where = array();
        $where['delete_flg'] = 0;
        $where['user_id']    = $dplIds;
        $orderBy = 'unique_id ASC';
        $temp = select('mst_user_office1', 'user_id,start_day,end_day,office_name', $where, $orderBy);
        foreach ($temp as $val) {
            if ($val['start_day'] <= TODAY && ($val['end_day'] >= TODAY)) {
                $dplOfc[$val['user_id']] = $val['office_name'];
            }
        }

        // 重複利用者リスト作成
        foreach ($dplUser as $val) {
            $dat = array();
            $tgtId = $val['unique_id'];
            $dat['unique_id']   = $tgtId;
            $dat['kana']        = $val['last_kana'] . $val['first_kana'];
            $dat['birthday']    = $val['birthday'];
            $dat['office_name'] = isset($dplUser[$tgtId])
                    ? $dplOfc[$tgtId]
                    : null;
            $dplList[$tgtId] = $dat;
        }
    }

    /* -- タブ遷移 ---------------------*/
    //if ($tabAct){
    //    // 入力状況チェック
    ////    require_once($_SERVER['DOCUMENT_ROOT']."/user/edit/function/confirm.php");
    //}

    /* -- 登録確認 ---------------------*/
    if ($entryFlg || !empty($_SESSION['confirm']['entry'])) {

        // セッションへ格納
        $input = array();
        if ($upAry) {
            $input['standard'] = $upAry;
        }
        if ($upDummy) {
            $input['dummy'] = $upDummy;
        }
        if ($upPay) {
            $input['pay'] = $upPay;
        }
        if ($office1) {
            $input['office1'] = $office1;
        }
        if ($office2) {
            $input['office2'] = $office2;
        }
        if ($upOfc1) {
            $input['$upOfc1'] = $upOfc1;
        }
        if ($upOfc2) {
            $input['$upOfc2'] = $upOfc2;
        }
        if ($upIns1) {
            $input['insure1'] = $upIns1;
        }
        if ($upIns2) {
            $input['insure2'] = $upIns2;
        }
        if ($upIns3) {
            $input['insure3'] = $upIns3;
        }
        if ($upIns4) {
            $input['insure4'] = $upIns4;
        }
        if ($upMdc) {
            $input['medical'] = $upMdc;
        }
        if ($upHsp) {
            $input['hospital'] = $upHsp;
        }
        if ($upDrg) {
            $input['drug'] = $upDrg;
        }
        if ($upSvc) {
            $input['service'] = $upSvc;
        }
        if ($upEmg) {
            $input['emergency'] = $upEmg;
        }
        if ($upPsn) {
            $input['person'] = $upPsn;
        }
        if ($upFml) {
            $input['family'] = $upFml;
        }
        if ($upInt) {
            $input['introduct'] = $upInt;
        }
        if ($upImg) {
            $input['image'] = $upImg;
        }
        if ($input) {
            $_SESSION['input'] = $input;
        }

        // 表示メッセージ
        $_SESSION['confirm']['entry'][] = '保存していない情報があります。<br>保存しますか？';
    }

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
