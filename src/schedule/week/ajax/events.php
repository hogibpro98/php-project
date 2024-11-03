<?php

try {
    /*--共通ファイル呼び出し-------------------------------------*/
    require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');
    $keyId = filter_input(INPUT_GET, 'user');
    if (!$keyId) {
        $keyId = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
    }
    debug($keyId);

    $events = array();
    $events[] = array();

    // 親スケジュールデータ取得
    $res = array();
    $sql = "";
    $sql .= " SELECT ";
    $sql .= "  dws.unique_id";
    $sql .= " ,dws.start_time";
    $sql .= " ,dws.end_time";
    $sql .= " ,dws.week";
    $sql .= " ,dws.week_num";
    $sql .= " ,dws.service_name";
    $sql .= " ,dws.service_id";
    $sql .= " FROM dat_week_schedule dws";
    $sql .= " WHERE ";
    $sql .= "     dws.delete_flg = 0";
    $sql .= " AND dws.user_id = '.$key_id.'";
    $sql .= " ;";
    $res = customSQL($sql);
    if (isset($res['err'])) {
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }
    // 親スケジュールデータ設定
    foreach ($res as $data) {
        //  週判定
        $date = "";
        // $date = $baseDate;
        if ($data['week'] == 0) {
            $date .= "2021-11-01";
        } elseif ($data['week'] == 1) {
            $date .= "2021-11-02";
        } elseif ($data['week'] == 2) {
            $date .= "2021-11-03";
        } elseif ($data['week'] == 3) {
            $date .= "2021-11-04";
        } elseif ($data['week'] == 4) {
            $date .= "2021-11-05";
        } elseif ($data['week'] == 5) {
            $date .= "2021-11-06";
        } elseif ($data['week'] == 6) {
            $date .= "2021-11-07";
        } else {
            $date = "";
        }
        if (!empty($data)) {
            $events[] = array('id' => $data['unique_id'], 'title' => $data['service_name'], 'start' => $date . ' ' . $data['start_time'], 'end' => $date . ' ' . $data['end_time'], 'color' => 'rgba(47, 120, 230, 0.3)', 'textColor' => 'black');
        }
    }

    // 子スケジュールデータ取得
    $res = array();
    $sql = "";
    $sql .= " SELECT";
    $sql .= "  dws.unique_id";
    $sql .= " ,dws.user_id";
    $sql .= " ,dws.week";
    $sql .= " ,dws.week_num";
    $sql .= " ,dwss.service_id ";
    $sql .= " ,dwss.service_name ";
    $sql .= " ,dwss.start_time";
    $sql .= " ,dwss.end_time";
    $sql .= " FROM dat_week_schedules_service dwss";
    $sql .= " INNER JOIN dat_week_schedule dws ON dws.unique_id = dwss.schedule_id ";
    $sql .= " WHERE ";
    $sql .= "       dws.delete_flg = 0 ";
    $sql .= "   AND dwss.delete_flg = 0 ";
    // $sql .= "   AND dws.user_id = '".$key_id."'";
    $sql .= " ;";
    $res = customSQL($sql);
    if (isset($res['err'])) {
        $err[] = 'システムエラーが発生しました';
        throw new Exception();
    }

    // 子スケジュールデータ設定
    foreach ($res as $data) {
        // 週判定
        $date = "";
        // $date = $baseDate;
        if ($data['week'] == 0) {
            $date .= "2021-11-01";
        } elseif ($data['week'] == 1) {
            $date .= "2021-11-02";
        } elseif ($data['week'] == 2) {
            $date .= "2021-11-03";
        } elseif ($data['week'] == 3) {
            $date .= "2021-11-04";
        } elseif ($data['week'] == 4) {
            $date .= "2021-11-05";
        } elseif ($data['week'] == 5) {
            $date .= "2021-11-06";
        } elseif ($data['week'] == 6) {
            $date .= "2021-11-07";
        } else {
            $date = "";
        }
        if (!empty($data)) {
            $events[] = array('id' => $data['unique_id'], 'title' => $data['service_name'], 'start' => $date . ' ' . $data['start_time'], 'end' => $date . ' ' . $data['end_time'], 'color' => 'rgba(255, 255, 255, 0.8)', 'textColor' => 'black');
        }
    }

    // 明示的に指定しない場合は、text/html型と判断される
    header("Content-type: application/json; charset=UTF-8");
    //JSONデータを出力
    echo json_encode($events);
    exit;
    /* ===================================================
 * 例外処理
 * ===================================================
*/
} catch (Exception $e) {
    if ($execEnv === 'pro' || $execEnv === 'stg') {
        $_SESSION['notice']['error'] = !empty($err) ? $err : array();
        header("Location:" . ERROR_PAGE);
        exit;
    } else {
        debug($e);
        exit;
    }
}
