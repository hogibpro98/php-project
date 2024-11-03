<?php
/* =============================================================================
 * 週間スケジュールデータ取得関数
 * =============================================================================
 */
function getWeekData($user_id = "", $schedule_id = "")
{
    //-------------------------------------
    // スケジュール情報取得
    //-------------------------------------
    $scheduleAry = array();
    $scheduleData = array();
    $sql = "";
    $sql .= " SELECT";
    $sql .= "      dws.unique_id";
    $sql .= "     ,dws.delete_flg";
    $sql .= "     ,dws.create_date";
    $sql .= "     ,dws.create_user";
    $sql .= "     ,dws.update_date";
    $sql .= "     ,dws.update_user";
    $sql .= "     ,dws.user_id";
    $sql .= "     ,dws.start_time";
    $sql .= "     ,dws.end_time";
    $sql .= "     ,dws.week";
    $sql .= "     ,dws.week_num";
    $sql .= "     ,dws.office_id";
    $sql .= "     ,dws.service_name";
    $sql .= "     ,dws.service_id";
    $sql .= "     ,dws.jihi_flg";
    $sql .= "     ,dws.jihi_price";
    $sql .= "     ,dws.jippi_flg";
    $sql .= "     ,mu.other_id";
    $sql .= "     ,mu.last_name";
    $sql .= "     ,mu.first_name";
    $sql .= "     ,mu.last_kana";
    $sql .= "     ,mu.first_kana";
    $sql .= "     ,offc.name AS office_name";
    $sql .= "     ,offc.office_no";
    $sql .= "     ,stf.last_name AS stf_last_name";
    $sql .= "     ,stf.first_name AS stf_first_name";
    $sql .= "     ,stf.last_kana AS stf_last_kana";
    $sql .= "     ,stf.first_kana AS stf_first_kana";
    $sql .= "     ,stf.staff_id";
    $sql .= " FROM dat_week_schedule dws";
    $sql .= " INNER JOIN mst_user mu ";
    $sql .= "     ON dws.user_id = mu.unique_id";
    $sql .= " LEFT OUTER JOIN mst_office offc ";
    $sql .= "     ON dws.office_id = offc.unique_id";
    $sql .= " LEFT OUTER JOIN mst_staff stf ";
    $sql .= "     ON dws.update_user = stf.unique_id";
    $sql .= " WHERE";
    $sql .= "         mu.delete_flg = 0";
    $sql .= "     AND dws.delete_flg = 0";
    $sql .= "     AND offc.delete_flg = 0";
    $sql .= "     AND dws.user_id = '" . $user_id . "'";
    $sql .= " ORDER BY";
    $sql .= "     dws.unique_id ";
    $sql .= "  ;";
    $res = array();
    $base_schedule = array();
    $res = customSQL($sql);
    foreach ($res as $val) {
        $scheduleId = $val['unique_id'];
        $scheduleAry[] = $scheduleId;
        $base_schedule[$scheduleId]['unique_id'] = $val['unique_id'];
        $base_schedule[$scheduleId]['delete_flg'] = $val['delete_flg'];
        $base_schedule[$scheduleId]['create_date'] = $val['create_date'];
        $base_schedule[$scheduleId]['create_user'] = $val['create_user'];
        $base_schedule[$scheduleId]['update_date'] = $val['update_date'];
        $base_schedule[$scheduleId]['update_user'] = $val['update_user'];
        $base_schedule[$scheduleId]['user_id'] = $val['user_id'];
        $base_schedule[$scheduleId]['start_time'] = empty($val['start_time']) ? null : formatDateTime($val['start_time'], 'H:i');
        $base_schedule[$scheduleId]['end_time'] = empty($val['end_time']) ? null : formatDateTime($val['end_time'], 'H:i');
        $base_schedule[$scheduleId]['week'] = $val['week'];
        $base_schedule[$scheduleId]['week_num'] = $val['week_num'];
        $base_schedule[$scheduleId]['office_id'] = $val['office_id'];
        $base_schedule[$scheduleId]['service_name'] = $val['service_name'];
        $base_schedule[$scheduleId]['service_id'] = $val['service_id'];
        $base_schedule[$scheduleId]['jihi_flg'] = $val['jihi_flg'];
        $base_schedule[$scheduleId]['jihi_price'] = $val['jihi_price'];
        $base_schedule[$scheduleId]['jippi_flg'] = $val['jippi_flg'];
        $base_schedule[$scheduleId]['other_id'] = $val['other_id'];
        $base_schedule[$scheduleId]['last_name'] = $val['last_name'];
        $base_schedule[$scheduleId]['first_name'] = $val['first_name'];
        $base_schedule[$scheduleId]['last_kana'] = $val['last_kana'];
        $base_schedule[$scheduleId]['first_kana'] = $val['first_kana'];
        $base_schedule[$scheduleId]['office_name'] = $val['office_name'];
        $base_schedule[$scheduleId]['office_no'] = $val['office_no'];
        $base_schedule[$scheduleId]['stf_last_name'] = $val['stf_last_name'];
        $base_schedule[$scheduleId]['stf_first_name'] = $val['stf_first_name'];
        $base_schedule[$scheduleId]['stf_last_kana'] = $val['stf_last_kana'];
        $base_schedule[$scheduleId]['stf_first_kana'] = $val['stf_first_kana'];
        $base_schedule[$scheduleId]['staff_id'] = $val['staff_id'];
    }

    //-------------------------------------
    // スケジュール詳細取得
    //-------------------------------------
    $schedules_service = array();
    $res = array();
    $sql = "";
    $sql .= " SELECT";
    $sql .= "      dwss.unique_id";
    $sql .= "     ,dwss.user_id";
    $sql .= "     ,dwss.schedule_id";
    $sql .= "     ,dwss.service_id";
    $sql .= "     ,dwss.service_name";
    $sql .= "     ,dwss.service_detail_id";
    $sql .= "     ,dwss.type AS dwss_type";
    $sql .= "     ,dwss.start_time";
    $sql .= "     ,dwss.end_time";
    $sql .= "     ,dwss.root_name";
    $sql .= "     ,ms.unique_id AS ms_unique_id";
    $sql .= "     ,ms.type AS ms_type";
    $sql .= "     ,ms.code AS ms_code";
    $sql .= "     ,ms.name AS ms_name";
    $sql .= "     ,ms.remarks AS ms_remarks";
    $sql .= "     ,msd.unique_id AS msd_unique_id";
    $sql .= "     ,msd.type AS msd_type";
    $sql .= "     ,msd.name AS msd_name";
    $sql .= "     ,msd.remarks AS msd_remarks";
    $sql .= " FROM dat_week_schedule dws";
    $sql .= " LEFT OUTER JOIN dat_week_schedule_service dwss ";
    $sql .= "     ON dws.unique_id = dwss.schedule_id";
    $sql .= " LEFT OUTER JOIN mst_service ms ";
    $sql .= "     ON dwss.service_id = ms.unique_id";
    $sql .= " LEFT OUTER JOIN mst_service_detail msd ";
    $sql .= "     ON dwss.service_detail_id = msd.unique_id";
    $sql .= " INNER JOIN mst_user mu ";
    $sql .= "     ON dws.user_id = mu.unique_id";
    $sql .= " WHERE";
    $sql .= "         mu.delete_flg = 0";
    $sql .= "     AND dws.delete_flg = 0";
    $sql .= "     AND dwss.delete_flg = 0";
    $sql .= "     AND dws.user_id = '" . $user_id . "'";
    $sql .= " ORDER BY";
    $sql .= "     dwss.schedule_id ";
    $sql .= "    ,dwss.service_id ";
    $sql .= "    ,msd.type ";
    $sql .= "  ;";
    $res = customSQL($sql);
    foreach ($res as $val) {
        $schedule_id = $val['schedule_id'];
        $schedules_service[$schedule_id][] = $val;
    }

    //-------------------------------------
    // スケジュール加減算取得
    //-------------------------------------
    $schedules_add = array();
    $res = array();
    $sql = "";
    $sql .= " SELECT";
    $sql .= "     dwsa.unique_id";
    $sql .= "     ,dwsa.user_id";
    $sql .= "     ,dwsa.schedule_id";
    $sql .= "     ,dwsa.add_id";
    $sql .= "     ,dwsa.add_name";
    $sql .= "     ,DATE_FORMAT(dwsa.start_date,'%Y/%m/%d') AS start_date";
    $sql .= "     ,DATE_FORMAT(dwsa.end_date,'%Y/%m/%d') AS end_date";
    $sql .= " FROM dat_week_schedule dws";
    $sql .= " LEFT OUTER JOIN dat_week_schedule_add dwsa ";
    $sql .= "     ON dws.unique_id = dwsa.schedule_id";
    $sql .= " INNER JOIN mst_user mu ";
    $sql .= "     ON dws.user_id = mu.unique_id";
    $sql .= " WHERE";
    $sql .= "         mu.delete_flg = 0";
    $sql .= "     AND dws.delete_flg = 0";
    $sql .= "     AND dwsa.delete_flg = 0";
    $sql .= "     AND dws.user_id = '" . $user_id . "'";
    // if ($schedule_id) {
    // 	$sql .= "     AND dws.unique_id = '" . $schedule_id . "'";
    // }
    $sql .= " ORDER BY";
    $sql .= "     dws.unique_id ";
    $sql .= "    ,dwsa.schedule_id ";
    $sql .= "  ;";
    $res = customSQL($sql);
    foreach ($res as $val) {
        $schedule_id = $val['schedule_id'];
        $schedules_add[$schedule_id][] = $val;
    }

    //-------------------------------------
    // スケジュール実費取得
    //-------------------------------------
    $schedules_jippi = array();
    $res = array();
    $sql = "";
    $sql .= " SELECT";
    $sql .= "      dwsj.unique_id";
    $sql .= "     ,dwsj.user_id ";
    $sql .= "     ,dwsj.schedule_id ";
    $sql .= "     ,dwsj.uninsure_id ";
    $sql .= "     ,dwsj.type ";
    $sql .= "     ,dwsj.name ";
    $sql .= "     ,dwsj.price ";
    $sql .= "     ,dwsj.zei_type ";
    $sql .= "     ,dwsj.rate";
    $sql .= "     ,dwsj.subsidy";
    $sql .= " FROM dat_week_schedule dws";
    $sql .= " LEFT OUTER JOIN dat_week_schedule_jippi dwsj ";
    $sql .= "     ON";
    $sql .= "     dws.unique_id = dwsj.schedule_id";
    $sql .= " LEFT OUTER JOIN dat_week_schedule_service dwss ";
    $sql .= "     ON dws.unique_id = dwss.schedule_id";
    $sql .= " LEFT OUTER JOIN mst_service ms ";
    $sql .= "     ON dwss.service_id = ms.unique_id";
    $sql .= " LEFT OUTER JOIN mst_service_detail msd ";
    $sql .= "     ON dwss.service_detail_id = msd.unique_id";
    $sql .= " INNER JOIN mst_user mu ";
    $sql .= "     ON dws.user_id = mu.unique_id";
    $sql .= " WHERE";
    $sql .= "         mu.delete_flg = 0";
    $sql .= "     AND dws.delete_flg = 0";
    $sql .= "     AND dwsj.delete_flg = 0";
    $sql .= "     AND dwss.delete_flg = 0";
    $sql .= "     AND dws.user_id = '" . $user_id . "'";
    // if ($schedule_id) {
    // 	$sql .= "     AND dws.unique_id = '" . $schedule_id . "'";
    // }
    $sql .= " ORDER BY";
    $sql .= "     dws.unique_id ";
    $sql .= "    ,dwss.schedule_id ";
    $sql .= "    ,dwsj.schedule_id ";
    $sql .= "    ,dwss.service_id ";
    $sql .= "    ,msd.type ";
    $sql .= "  ;";
    $res = array();
    $res = customSQL($sql);
    foreach ($res as $val) {
        $schedule_id = $val['schedule_id'];
        $schedules_jippi[$schedule_id][] = $val;
    }

    // 結果セットに設定
    $scheduleData['base_schedule'] = $base_schedule;
    $scheduleData['schedules_service'] = $schedules_service;
    $scheduleData['schedules_add'] = $schedules_add;
    $scheduleData['schedules_jippi'] = $schedules_jippi;

    return $scheduleData;
}
