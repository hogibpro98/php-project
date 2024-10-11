<?php
//=====================================================================
// [ajax]指示書情報検索
//=====================================================================
try {
/* =================================================== 
 * 初期処理
 * ===================================================
 */
 
/*--共通ファイル呼び出し-------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'].'/common/php/com_start.php');

/*--変数定義-------------------------------------------------*/
$notice      = NULL;
$sendData    = array();
$dispEvt     = array();
$temperature = "";
/* =================================================== 
 * 入力情報取得
 * ===================================================
 */

// KEY
$keyId     = h(filter_input(INPUT_POST, 'id'));
$userId    = h(filter_input(INPUT_POST, 'user_id'));

// サービス年月
$year = h(filter_input(INPUT_POST, 'year'));
$month = h(filter_input(INPUT_POST, 'month'));
$serviceDay = $year . '-'. $month;

/* =================================================== 
 * イベント後処理(描画用データ作成)
 * ===================================================
 */
/* -- データ取得 ----------------------------------------*/

/* -- イベント情報 -----------------------*/
$where = array();
$where['delete_flg'] = 0;
$where['report_id']  = $keyId;
$target = 'event_day,event_kb';
$temp = select('doc_report_event',$target,$where);
foreach ($temp as $val){
    $tgtDay = $val['event_day'];
    $dispEvt[$tgtDay] = $val['event_kb'];
}

/* -- カレンダー情報 -----------------------*/

// 対象月、次月
$tgtDay   = !empty($serviceDay) ? $serviceDay : TODAY;
$tgtAry   = explode('-', $tgtDay);
$tgtYear  = $tgtAry[0];
$tgtMonth = $tgtAry[0].'-'.$tgtAry[1];
$tgtMonthNum = $tgtAry[1];
$dt = new DateTime($tgtDay);
$nxtMonth = $dt->modify('first day of +1 month')->format('Y-m');

// カレンダー配列
$cldList1 = getMonthCalender($tgtMonth);
$cldList2 = getMonthCalender($nxtMonth);

// 共通エリア
$commonText = "";
$commonText .= '<!-- モーダルエリア -->';
$commonText .= '<ul class="sched_sign">';
$commonText .= '  <li><button type="button" name="" value="circle1"><img src="/common/image/sign_circle1.png" alt=""></button></li>';
$commonText .= '  <li><button type="button" name="" value="square"><img src="/common/image/sign_square.png" alt=""></button></li>';
$commonText .= '  <li><button type="button" name="" value="circle2"><img src="/common/image/sign_circle2.png" alt=""></button></li>';
$commonText .= '  <li><button type="button" name="" value="diamond"><img src="/common/image/sign_diamond.png" alt=""></button></li>';
$commonText .= '  <li><button type="button" name="" value="check"><img src="/common/image/sign_check.png" alt=""></button></li>';
$commonText .= '  <li><button type="button" name="" value="triangle"><img src="/common/image/sign_triangle.png" alt=""></button></li>';
$commonText .= '</ul>';
$commonText .= '<!-- 値の保持（name改変可） -->';

// 当月htmlタグ情報作成
$calender1 = "";
$calender1 .= '<table><!-- カレンダーモーダル -->';
foreach ($cldList1 as $cldList11){
    $calender1 .= '<tr>';
    foreach ($cldList11 as $day){
        $tgtDay = $day ? formatDateTime($day, 'j') : NULL;
        $class = isset($dispEvt[$day]) ? $dispEvt[$day] : NULL;
        $calender1 .= '<td class="sign-'. $class .'"><button type="button" class="calendar_open">'. $tgtDay .'</button>';
        $calender1 .= $commonText;
        $calender1 .= '<input type="hidden" name="upAry2['. $day .'][unique_id]" value="">';
        $calender1 .= '<input type="hidden" name="upAry2['. $day .'][event_kb]" value=""></td>';
    }
    $calender1 .= '</tr>';
}
$calender1 .= '</table>';

// 翌月htmlタグ情報作成
$calender2 = '';
$calender2 .= '<table><!-- カレンダーモーダル -->';
foreach ($cldList2 as $cldList21){
    $calender2 .= '<tr>';
    foreach ($cldList21 as $day){
        $tgtDay = $day ? formatDateTime($day, 'j') : NULL;
        $class = isset($dispEvt[$day]) ? $dispEvt[$day] : NULL;
        $calender2 .= '<td class="sign-'. $class .'"><button type="button" class="calendar_open">'. $tgtDay .'</button>';
        $calender2 .= $commonText;
        $calender2 .= '<input type="hidden" name="upAry2['. $day .'][unique_id]" value="">';
        $calender2 .= '<input type="hidden" name="upAry2['. $day .'][event_kb]" value=""></td>';
    }
    $calender2 .= '</tr>';
}
$calender2 .= '</table>';

// 訪問看護記録Ⅱから体温情報を取得する
if($userId){
    $tgtDay = !empty($serviceDay) ? $serviceDay : TODAY;
    $date   = new DateTime($tgtDay);
    // 月初を取得する
    $tgtDayFrom = $date->modify('first day of this months')->format('Y-m-d');
    // 月末を取得する
    $tgtDayTo = $date->modify('last day of this months')->format('Y-m-d');
    $btMin = 100.0;
    $btMax = 0.0;
    $where = array();
    $where['delete_flg'] = 0;
    $where['user_id'] = $userId;
    $where['service_day >= '] = $tgtDayFrom. "";
    $where['service_day <= '] = $tgtDayTo. "";
    $target = '*';
    $temp = select('doc_visit2',$target,$where);
    foreach ($temp as $val){
        if($val['temperature']){
        $bt = floatval($val['temperature']);
            // 最小値の更新 
            if($btMin > $bt)
            {
                $btMin = $bt;
            }
            // 最大値の更新
            if($btMax < $bt)
            {
                $btMax = $bt;
            }
        }
    }
    $btMinStr = $btMin === 100.0 ? "" : $btMin ;
    $btMaxStr = $btMax === 0.0 ? "" : $btMax ;
    $temperature = "体温：".$btMinStr."～".$btMaxStr;
}
/* -- その他 --------------------------------------------*/


/* -- データ送信 ----------------------------------------*/
$sendData = $calender1 .','.$calender2.','.$temperature;
echo $sendData;
exit();

/* =================================================== 
 * 例外処理
 * ===================================================
 */
} catch(Exception $e){
    debug($e);
    exit();
    $_SESSION['err'] = !empty($err) ? $err : array();
    header("Location:". ERROR_PAGE);
    exit();
}
