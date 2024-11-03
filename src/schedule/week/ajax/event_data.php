<?php
/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php');

$user_id = filter_input(INPUT_GET, 'user_id');
$schedule_id = filter_input(INPUT_GET, 'schedule_id');

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
$sql .= "     AND dws.unique_id = '" . $schedule_id . "'";
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
$sql .= "     ,ms.unique_id AS ms_unique_id";
$sql .= "     ,ms.type AS ms_type";
$sql .= "     ,ms.code AS ms_code";
$sql .= "     ,ms.name AS ms_name";
$sql .= "     ,ms.remarks AS ms_remarks";
$sql .= "     ,msd.unique_id AS msd_unique_id";
$sql .= "     ,msd.base_service_code AS msd_base_service_code";
$sql .= "     ,msd.type AS msd_type";
$sql .= "     ,msd.code AS msd_code";
$sql .= "     ,msd.name AS msd_name";
$sql .= "     ,msd.remarks AS msd_remarks";
$sql .= " FROM dat_week_schedule dws";
$sql .= " LEFT OUTER JOIN dat_week_schedules_service dwss ";
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
$sql .= "     AND dws.unique_id = '" . $schedule_id . "'";
$sql .= " ORDER BY";
$sql .= "     dwss.schedule_id ";
$sql .= "    ,dwss.service_id ";
$sql .= "    ,msd.base_service_code ";
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
$sql .= "     AND dws.unique_id = '" . $schedule_id . "'";
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
$sql .= " LEFT OUTER JOIN dat_week_schedules_service dwss ";
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
$sql .= "     AND dws.unique_id = '" . $schedule_id . "'";
$sql .= " ORDER BY";
$sql .= "     dws.unique_id ";
$sql .= "    ,dwss.schedule_id ";
$sql .= "    ,dwsj.schedule_id ";
$sql .= "    ,dwss.service_id ";
$sql .= "    ,msd.base_service_code ";
$sql .= "  ;";
$res = array();
$res = customSQL($sql);
foreach ($res as $val) {
    $schedule_id = $val['schedule_id'];
    $schedules_jippi[] = $val;
}

// 結果セットに設定
$scheduleData['base_schedule'] = $base_schedule;
$scheduleData['schedules_service'] = $schedules_service;
$scheduleData['schedules_add'] = $schedules_add;
$scheduleData['schedules_jippi'] = $schedules_jippi;
?>

<!--利用者スケジュール変更-->
<div id="calendarModal" class="new_default common_part1 root_commute cancel_act">
  <input type="hidden" id="schedule" neme="schedule">
  <div class="close close_part">✕<span>閉じる</span></div>
  <div class="sched_tit">利用者スケジュール変更</div>
  <div class="s_detail">
    <input type="hidden" name="upAry[unique_id]" value="<?= $scheduleData['base_schedule']['unique_id'] ?>">
    <div class="box1">
      <p class="mid">曜日/時刻</p>
      <p class="day_list">
        <span><input type="checkbox" name="曜日" id="bulk"><label for="bulk">一括</label></span>
        <span><input type="checkbox" name="曜日" id="day1" checked><label for="day1">月</label></span>
        <span><input type="checkbox" name="曜日" id="day2"><label for="day2">火</label></span>
        <span><input type="checkbox" name="曜日" id="day3"><label for="day3">水</label></span>
        <span><input type="checkbox" name="曜日" id="day4"><label for="day4">木</label></span>
        <span><input type="checkbox" name="曜日" id="day5"><label for="day5">金</label></span>
        <span><input type="checkbox" name="曜日" id="day6"><label for="day6">土</label></span>
        <span><input type="checkbox" name="曜日" id="day7"><label for="day7">日</label></span>
      </p>
      <p>
        <select class="s_month">
          <option selected>月</option>
          <option>火</option>
          <option>水</option>
          <option>木</option>
          <option>金</option>
          <option>土</option>
          <option>日</option>
        </select>
        <input type="text" name="upAry[start_time]" placeholder="時間" value="<?= $scheduleData['base_schedule']['start_time'] ?>"><small>～</small><input type="text" name="upAry[start_time]" placeholder="時間" value="<?= $scheduleData['base_schedule']['end_time'] ?>">
      </p>
      <p class="month_list">
        <span><input type="checkbox" name="upAry[week1]" value="<?= $upAry['week1'] ?>" id="week1" <?= strpos($scheduleData['base_schedule']['week_num'], '第1週') ? 'checked' : '' ?>><label for="month1">第1週</label></span>
        <span><input type="checkbox" name="upAry[week2]" value="<?= $upAry['week2'] ?>" id="week2" <?= strpos($scheduleData['base_schedule']['week_num'], '第2週') ? 'checked' : '' ?>><label for="month2">第2週</label></span>
        <span><input type="checkbox" name="upAry[week3]" value="<?= $upAry['week3'] ?>" id="week3" <?= strpos($scheduleData['base_schedule']['week_num'], '第3週') ? 'checked' : '' ?>><label for="month3">第3週</label></span>
        <span><input type="checkbox" name="upAry[week4]" value="<?= $upAry['week4'] ?>" id="week4" <?= strpos($scheduleData['base_schedule']['week_num'], '第4週') ? 'checked' : '' ?>><label for="month4">第4週</label></span>
        <span><input type="checkbox" name="upAry[week5]" value="<?= $upAry['week5'] ?>" id="week5" <?= strpos($scheduleData['base_schedule']['week_num'], '第5週') ? 'checked' : '' ?>><label for="month5">第5週</label></span>
        <span><input type="checkbox" name="upAry[week6]" value="<?= $upAry['week6'] ?>" id="week6" <?= strpos($scheduleData['base_schedule']['week_num'], '第6週') ? 'checked' : '' ?>><label for="month5">第6週</label></span>
      </p>
    </div>
    <div class="box1">
      <p class="mid">利用者</p>
      <p>
        <span name="upAry[user_name]" class="user_res"><?= $scheduleData['base_schedule']['last_name'] . ' ' . $scheduleData['base_schedule']['first_name'] ?></span>
        <span name="upAry[other_id]" class="label_t">(利用者ID: <?= $scheduleData['base_schedule']['other_id'] ?>)</span>
      </p>
    </div>
    <div class="box1">
      <p class="mid">実施事業所</p>
      <p>
        <span class="n_search">Search</span>
        <span name="upAry[office_name]" class="staff"><?= $scheduleData['base_schedule']['office_name'] ?></span>
        <span name="upAry[office_no]" class="staff_id">(ID:<?= $scheduleData['base_schedule']['office_no'] ?>)</span>
      </p>
    </div>
    <div class="box1">
      <p class="mid">サービス内容</p>
      <p>
        <span class="n_search">Search</span>
        <span name="upAry[service_name]" class="staff"><?= $scheduleData['base_schedule']['service_name'] ?></span>
      </p>
      <input type="hidden" name="upAry[service_id]" value="<?= $scheduleData['base_schedule']['service_id'] ?>">
      <p class="own_expense">
        <span>
          <label for="expense">自費</label>
          <input type="checkbox" name="upAry[jippi_flg]" id="expense" value="1" <?= $scheduleData['base_schedule']['jippi_flg'] === "1" ? 'checked' : '' ?>>
        </span>
        <span class="expense_val">
          <input type="text" name="upAry[jihi_price]" value="<?= $scheduleData['base_schedule']['jihi_price'] ?>"><label>円</label>
        </span>
      </p>
    </div>
    <div class="box1">
      <p class="mid">基本<br class="pc">サービスコード</p>
      <p>
        <span class="n_search">Search</span>
        <span class="staff"><?= $scheduleData['base_schedule']['service_name'] ?>(<?= $scheduleData['base_schedule']['service_id'] ?>) </span>
      </p>
    </div>
  </div>
  <div class="add_sub">
    <p class="mid">加減算</p>
    <ol>
      <?php foreach ($scheduleData['schedules_add'] as $val) : ?>
        <li>
          <select>
            <option disabled hidden>選択してください</option>
            <?php foreach ($addList as $addVal) : ?>
              <?php $select = $addVal['name'] === $val['add_name'] ? ' selected' : null; ?>
              <option name="upAry[dwsj_unique_id]" value="<?= $addVal['unique_id'] ?>" <?= $select ?>><?= $addVal['name'] ?></option>
            <?php endforeach; ?>
          </select>
          <p class="list_delete l_delete1">Delete</p>
          <p>
            <input type="text" name="upAry[start_date]" class="master_date date_no-Day date_start" value="<?= $val['start_date'] ?>"><small>～</small><input type="text" name="upAry[end_date]" class="master_date date_no-Day date_end" value="<?= $val['end_date'] ?>">
          </p>
        </li>
      <?php endforeach; ?>
      <li>
        <select class="default">
          <option disabled hidden selected>選択してください</option>
        </select>
        <p>
          <input type="text" name="date" class="" value="">
          <small>～</small>
          <input type="text" name="date" class="" value="">
        </p>
        <p class="list_delete l_delete1">Delete</p>
      </li>

    </ol>
    <p class="add_btn add_sub_btn">+</p>
  </div>
  <div class="cost">
    <div><label for="cost">実費</label><input type="checkbox" name="cost" id="cost" checked></div>
    <div class="btn cost_edit display_cost">実費編集</div>
    <ul class="display_cost">
      <?php foreach ($scheduleData['schedules_jippi'] as $val) : ?>
        <input type="hidden" name="upAry[dwsj_unique_id]" value="<?= $val['unique_id'] ?>">
        <li><?= $val['type'] ?>/<?= $val['name'] ?><br><?= $val['price'] ?>円(<?= $val['zei_type'] ?>・<?= $val['rate'] ?>％)/<?= $val['subsidy'] ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="service_dets">
    <div class="mid">サービス内容詳細</div>
    <div class="service_list">
      <?php foreach ($scheduleData['schedules_service'] as $val) : ?>
        <ul>
          <li>&nbsp;</li>
          <li>
            <input class="service_time" type="text" name="upAry[dwss_start_time]" value="<?= $val['start_time'] ?>">
            <small>～</small>
            <input class="service_time" type="text" name="upAry[dwss_end_time]" value="<?= $val['end_time'] ?>">
          </li>
          <li>
            <select>
              <?php foreach ($serviceList as $svList) : ?>
                <?php $select = $svList['name'] === $val['ms_name'] ? ' selected' : null; ?>
                <option name="upAry[ms_unique_id]" value="<?= $svList['ms_unique_id'] ?>" <?= $select ?>><?= $svList['name'] ?></option>
              <?php endforeach; ?>
            </select>
          </li>
          <li>
            <select>
              <?php foreach ($serviceDetailList as $svdList) : ?>
                <?php $select = $svdList['name'] === $val['msd_name'] ? ' selected' : null; ?>
                <option name="upAry[msd_unique_id]" value="<?= $svdList['msd_unique_id'] ?>" <?= $select ?>><?= $svdList['name'] ?></option>
              <?php endforeach; ?>
            </select>
          </li>
          <li>
            <p class="list_delete l_delete2">Delete</p>
          </li>
        </ul>
      <?php endforeach; ?>
    </div>
    <!-- <span class="btn add add_details">追加</span> -->
    <button type="submit" id="btn_add" class="btn add add_details">追加</button>
  </div>
  <div class="s_constrols">
    <p><span class="btn cancel">キャンセル</span></p>
    <p>
      <button type="submit" id="btn_delete" class="btn delete">削除</button>
      <span class="btn duplicate duplicate1">複製</span>
      <button type="submit" id="btnSave" name="btnSave" class="btn save">保存</button>
    </p>
  </div>
  <div class="update">
    最終更新:
    <span class="time"><?= $scheduleData['base_schedule']['update_date'] ?></span>
    <span class="person"><?= $scheduleData['base_schedule']['stf_last_name'] . " " . $scheduleData['base_schedule']['stf_first_name'] ?></span>
  </div>
</div>