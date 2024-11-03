<?php

//=====================================================================
// ログ管理
//=====================================================================
try {
    /* ===================================================
     * 初期処理
     * ===================================================
     */

    /*--共通ファイル呼び出し-------------------------------------*/
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

    /*--変数定義-------------------------------------------------*/

    // 初期化
    $err      = array();
    $_SESSION['notice']['error']   = array();
    $dispData = array();
    $tgtData  = array();
    $upData   = array();
    // 対象テーブル(メイン)
    $table = 'log_entry';

    // 表示件数
    $line = 20;

    $screenNames['画像関連詳細']          = '/image/detail/php/detail.php';
    $screenNames['画像関連一覧']          = '/image/list/php/list.php';
    $screenNames['連携データ作成']        = '/place/cooperate/php/cooperate.php';
    $screenNames['CSVデータ出力']         = '/place/csv/php/csv_list.php';
    $screenNames['事業所からのお知らせ']  = '/place/news/php/news.php';
    $screenNames['お知らせ詳細']          = '/place/news_edit/php/news_edit.php';
    $screenNames['お知らせ管理']          = '/place/news_list/php/news_list.php';
    $screenNames['従業員予定実績']        = '/record/staff/php/list.php';
    $screenNames['利用者予定実績']        = '/record/user/php/list.php';
    $screenNames['帳票一括確認']          = '/report/all_list/php/all_list.php';
    $screenNames['褥瘡計画']                 = '/report/bedsore/php/bedsore.php';
    $screenNames['指示書']                = '/report/instruct/php/instruct.php';
    $screenNames['看多機記録']            = '/report/kantaki/php/body_image.php';
    $screenNames['看多機記録']            = '/report/kantaki/php/edit.php';
    $screenNames['看多機記録']            = '/report/kantaki/php/kantaki.php';
    $screenNames['計画書']                = '/report/plan/php/plan.php';
    $screenNames['各種帳票']              = '/report/list/php/print_list.php';
    $screenNames['経過記録']              = '/report/progress/php/progress.php';
    $screenNames['報告書']                = '/report/report/php/report.php';
    $screenNames['記録一覧']              = '/report/report_list/php/report_list.php';
    $screenNames['訪問看護記録Ⅰ']         = '/report/visit1/php/visit1.php';
    $screenNames['訪問看護記録Ⅱ詳細']     = '/report/visit2/php/visit2.php';
    $screenNames['ルート表']              = '/schedule/route_day/php/edit.php';
    $screenNames['ルート管理']            = '/schedule/route_edit/php/edit.php';
    $screenNames['週間スケジュール']      = '/schedule/week/php/week.php';
    $screenNames['アカウント情報']        = '/system/account/php/account_edit.php';
    $screenNames['ログ管理']              = '/system/log/php/log_list.php';
    $screenNames['事業所管理']            = '/system/office/php/office.php';
    $screenNames['拠点管理']              = '/system/place_edit/php/place_edit.php';
    $screenNames['従業員詳細']            = '/system/staff_edit/php/staff_edit.php';
    $screenNames['従業員一覧']            = '/system/staff_list/php/staff_list.php';
    $screenNames['保険外マスタ詳細']      = '/system/uninsure_edit/php/uninsure_edit.php';
    $screenNames['保険外マスタ一覧']      = '/system/uninsure_list/php/uninsure_list.php';
    $screenNames['利用者基本情報']        = '/user/edit/php/user_edit.php';
    $screenNames['利用者一覧']            = '/user/list/php/user_list.php';

    /* ===================================================
     * 入力情報取得
     * ===================================================
     */

    /*-- 検索用パラメータ ---------------------------------------*/

    // 検索配列
    $search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $search = $search ? $search : array();

    $search['start_day'] = !empty($search['start_day']) ? formatDateTime($search['start_day'], "Y-m-d") : THISMONTHFIRST;
    $search['end_day']   = !empty($search['end_day']) ? formatDateTime($search['end_day'], "Y-m-d") : null;//THISMONTHLAST;
    $search['screen']    = !empty($search['screen']) ? $search['screen'] : null;


    /*-- 更新用パラメータ ---------------------------------------*/


    /*-- その他パラメータ ---------------------------------------*/

    // ページャー
    $page = h(filter_input(INPUT_GET, 'page'));
    if (!empty($page)) {
        $page = 1;
    }

    /* ===================================================
     * イベント前処理(更新用配列作成、入力チェックなど)
     * ===================================================
     */

    /* -- 更新用配列作成 ----------------------------------------*/


    /* ===================================================
     * イベント本処理(データ登録)
     * ===================================================
     */


    /* ===================================================
     * イベント後処理(描画用データ作成)
     * ===================================================
     */

    /* -- データ取得 --------------------------------------------*/

    // スタッフリスト(有効のみ)
    $where = array();
    $where['delete_flg'] = 0;
    $orderBy = 'unique_id ASC';
    $temp = select('mst_staff', 'unique_id,name', $where, $orderBy);
    foreach ($temp as $val) {
        $stfList[$val['unique_id']] = $val['name'];
    }

    // ログ取得
    $where = array();
    $where['delete_flg'] = 0;
    if ($search['start_day']) {
        $where['create_date >='] = $search['start_day'] . ' 00:00:00';
    }
    if ($search['end_day']) {
        $where['create_date <='] = $search['end_day'] . ' 23:59:59';
    }
    if ($search['screen']) {
        $where['screen'] = $search['screen'];
    }
    $orderBy = 'unique_id DESC';
    $temp = select($table, '*', $where, $orderBy);
    foreach ($temp as $val) {

        // KEY
        $tgtId = $val['unique_id'];

        // 格納
        $tgtData[$tgtId] = $val;
    }

    /* -- その他 --------------------------------------------*/

    // ページャー
    $dispData = getPager($tgtData, $page, $line);


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
