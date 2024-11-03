<?php

//=======================================================================
//   日時定義ファイル
//=======================================================================

//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 年
$dt = new DateTime();
define('THISYEAR', $dt->format('Y'));
// 今月
$dt = new DateTime();
define('THISMONTH', $dt->format('Y-m'));
// 今月 月初
$dt = new DateTime();
define('THISMONTHFIRST', $dt->modify('first day of this month')->format('Y-m-d'));
// 今月 月末
$dt = new DateTime();
define('THISMONTHLAST', $dt->modify('last day of this month')->format('Y-m-d'));
// 先月
$dt = new DateTime();
define('PREVMONTH', $dt->modify('first day of -1 month')->format('Y-m'));
// 翌月
$dt = new DateTime();
define('NEXTMONTH', $dt->modify('first day of +1 month')->format('Y-m'));
// 本日日付
$dt = new DateTime();
define('TODAY', $dt->format('Y-m-d'));
// 現在時
$dt = new DateTime();
define('NOW', $dt->format('Y-m-d H:i:s'));
// 昨日
$dt = new DateTime();
define('YESTERDAY', $dt->modify('-1 day')->format('Y-m-d'));
// 明日
$dt = new DateTime();
define('TOMORROW', $dt->modify('+1 day')->format('Y-m-d'));
// 終了日
define('ENDDAY', '2100-12-31');
// 終了日時
define('ENDDATE', '2100-12-31 23:59:59');

// 曜日
$weekAry[0] = '日';
$weekAry[1] = '月';
$weekAry[2] = '火';
$weekAry[3] = '水';
$weekAry[4] = '木';
$weekAry[5] = '金';
$weekAry[6] = '土';
$weekAry[7] = '祝';

$weekCls[0] = 'sun';
$weekCls[1] = 'mon';
$weekCls[2] = 'tue';
$weekCls[3] = 'wed';
$weekCls[4] = 'thu';
$weekCls[5] = 'fri';
$weekCls[6] = 'sat';

// 和暦選択肢
$wareki[] = '昭和';
$wareki[] = '平成';
$wareki[] = '令和';

/* ====================================================================
 * 1.事業年度取得関数
 * ====================================================================
 * 引数
 * ①$ymdhis[文字列] (datetime型として認識できる書式)
 *    年度を取得したい対象日を指定
 * ②$startDate[文字列] ('m-d'の書式,月日は0埋めすること)
 *    事業年度としての開始日を指定
 *
 * 戻り値 [数値]
 *    対象年度を数値で返す
 */
function getFiscalYear($ymdhis, $startDate)
{
    $dt = new DateTime($ymdhis);
    if ($dt->format('m-d') < $startDate) {
        return (int)$dt->format('Y') - 1;
    } else {
        return (int)$dt->format('Y');
    }
}
/* ====================================================================
 * 2.事業日付取得関数
 * ====================================================================
 * 引数
 * ①$ymdhis[文字列] (datetime型として認識できる書式)
 *    事業日を取得したい対象日時を指定
 * ②$startDate[文字列] ('H:i'の書式,時・分は0埋めすること)
 *    事業日の開始時間を指定
 *
 * 戻り値 [数値]
 *    対象事業日を数値で返す
 */
function getFiscalDate($ymdhis, $startTime)
{
    $dt = new DateTime($ymdhis);
    if ($dt->format('H:i') >= $startTime) {
        return $dt->format('Y-m-d');
    } else {
        return $dt->modify('-1 day')->format('Y-m-d');
    }
}
/* ====================================================================
 * 3.事業時間取得関数
 * ====================================================================
 * 引数
 * ①$ymdhis[文字列] (datetime型として認識できる書式)
 *    事業時間を取得したい対象日時を指定
 * ②$startDate[文字列] ('H:i'の書式,時・分は0埋めすること)
 *    事業日の開始時間を指定
 *
 * 戻り値 [数値]
 *    対象事業時間を数値で返す
 */
function getFiscalTime($ymdhis, $startTime)
{
    $dt = new DateTime($ymdhis);
    if ($dt->format('H:i') >= $startTime) {
        return $dt->format('H:i');
    } else {
        return sprintf('%02d:%02d', (int)$dt->format('H') + 24, (int)$dt->format('i'));
    }
}
/* ====================================================================
 * 4.事業時間→正規日時変換関数
 * ====================================================================
 * 引数
 * ①$ymd[文字列] ('Y-m-d'の書式)
 *    事業日を指定
 * ②$hi[文字列] ('H:i'の書式,時・分は0埋めすること)
 *    事業時間を指定
 *
 * 戻り値 [数値]
 *    事業日、事業時間から逆算して正規の日時を返す
 */
function formatFiscalTime($ymd, $hi)
{
    $dt = new DateTime($ymd);
    list($hour, $minute) = explode(':', $hi);
    $dt->setTime($hour, $minute, 0);
    return $dt->format('Y-m-d H:i:s');
}

// 日付の遅延(朝の6時までは前日とするなど)
function getDelayDate($targetDate, $delayTime)
{
    $dt = new DateTime($targetDate);
    return $dt->modify($delayTime . ' day')->format('Y-m-d');
}

// 時刻の遅延
function getDelayTime($targetDate, $delayTime)
{
    $dt = new DateTime($targetDate);
    return $dt->modify($delayTime . ' hour')->format('h:i:s');
}

// 日付の差
function getDiffDay($date1, $date2)
{

    // 指定日の準備
    $day1 = new DateTime($date1);
    $day2 = new DateTime($date2);

    // 日付の差を抽出
    $interval = $day1->diff($day2);

    // フォーマットして返却
    return $interval->format('%a');
}

// 時間の差
function getDiffTime($date1, $date2)
{

    // 指定日の準備
    $time1 = new DateTime($date1);
    $time2 = new DateTime($date2);

    // 日付の差を抽出
    $interval = $time1->diff($time2);

    // フォーマットして返却
    $h = $interval->format('%h');
    $i = $interval->format('%i');
    $s = $interval->format('%s');
    return $h . ':' . sprintf('%02d', $i) . ':' . sprintf('%02d', $s);
}

// 時間の差(分で返却)
function getDiffMin($date1, $date2)
{

    // 指定日の準備
    $time1 = new DateTime($date1);
    $time2 = new DateTime($date2);

    // 日付の差を抽出
    $interval = $time1->diff($time2);

    // フォーマットして返却
    $h = $interval->format('%h');
    $i = $interval->format('%i');
    $s = $interval->format('%s');
    return $h * 60 + $i;
}

// 日付の差によるNEW判定
function checkNew($date1 = NOW, $date2 = NOW, $days = 7)
{
    $diff = getDiffDay($date1, $date2);
    return $diff < $days ? true : false;
}

// 日時文字列を指定した書式に変換
function formatDateTime($str = NOW, $format = 'Y/m/d H:i:s')
{
    $dt = new DateTime($str);
    return $dt->format($format);
}

//---------------------------------------
// 引数1:検索開始日 yyyy-mm-dd
// 引数2:検索終了日 yyyy-mm-dd
// 戻り値:
//   $res[yyyy-mm-dd]['day']  = yyyy-mm-dd
//   $res[yyyy-mm-dd]['week'] = w
//---------------------------------------
// カレンダー配列作成
function getCalendar($start, $end)
{
    $day = $start;
    $dt  = new DateTime($day);
    while ($day <= $end) {
        $res[$day]['day']  = $day;
        $res[$day]['week'] = formatDateTime($day, 'w');
        $day = $dt->modify('+1days')->format('Y-m-d');
    }
    return $res;
}
//---------------------------------------
// 引数:(引数1:string型,引数2:int型)
// 引数1:年と月の文字列　例)'2018-05'
// 引数2:返り値のタイプ(省略可)　想定する値は1もしくは2
//        1->要素の文字列が当月以外の場合NULLに置き換えた配列を返す
//        2->月初めの週の日曜日から月終わりの週の土曜までの全ての日付を格納した配列を返す
//
// 戻り値:string型２重配列
// arary[第何週目かの情報(int)1~4,5,6][曜日((int)0~6,日~土)]=例)2018-05-01
//
//---------------------------------------
function getMonthCalender($month = '', $type = 1)
{

    /*引数確認-------------------------------------------------------------------------*/
    if (gettype($month) !== 'string' || ($type !== 1 && $type !== 2)) {
        return array();
    }

    if ($month === '') {
        // DateTime Object インスタンス化
        $datetime = new DateTime();
        // 月初めに移動
        $datetime->modify('first day of this month');
    } else {
        // 関数引数で渡される$monthを'-'で分割し、$inputに配列として格納
        $input = explode('-', $month);
        // '-'で分割された文字列が2個ではない場合は空配列を返す
        if (count($input) !== 2) {
            return array();
        }
        // 指定された年、月が存在しない場合は空配列を返す
        if (!checkdate(intval($input[1]), 1, intval($input[0]))) {
            return array();
        }
        // 関数引数で渡される$monthの入力形式を整形する
        $month = sprintf('%04d-%02d', intval($input[0]), intval($input[1]));
        // 月初めの日付を設定
        $firstDay = $month . '-01';
        // DateTime Object インスタンス化
        $datetime = new DateTime($firstDay);
    }

    /*変数定義-------------------------------------------------------------------------*/
    // 指定月の初日
    $monthFirstDay = $datetime->format('Y-m-01');
    // 指定月の最終日
    $monthEndDay = $datetime->format('Y-m-t');
    $youbi = $datetime->format('w');
    // 第１週の日曜日
    $day = $datetime->modify('-' . $youbi . 'days')->format('Y-m-d');
    //返り値用の配列を宣言
    $res = array();

    /*返り値用の配列にデータを格納--------------------------------------------------------*/
    // 週ループ
    $week = 1;
    while ($day <= $monthEndDay) {
        //返り値用の配列は2次元配列形式
        $res[$week] = array();
        // 曜日ループ
        for ($i = 0; $i < 7; $i++) {
            //データ格納
            $res[$week][$i] = $day;
            //日付を1日進める
            $day = $datetime->modify('+1days')->format('Y-m-d');
        }
        //第何週かの情報をインクリメント
        $week++;
    }

    /*返り値を対象月のみに限定-----------------------------------------------------------*/
    if ($type === 1) {
        foreach ($res as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                if ($value2 < $monthFirstDay) {
                    //該当月より以前
                    $res[$key1][$key2] = null;
                } elseif ($value2 > $monthEndDay) {
                    //該当月以降
                    $res[$key1][$key2] = null;
                }
            }
        }
    }
    return $res;
}
// 年カレンダー
function getYearCalendar($startDay, $endDay)
{
    $res = array();
    for ($i = new DateTime($startDay); $i <= new DateTimeImmutable($endDay); $i->modify('+1 day')) {
        $day = $i->format('Y-m-d');
        $ym  = formatDateTime($day, 'Y-m');
        $res[$ym][$day] = true;
    }
    return $res;
}
/**
 * 和暦変換(グレゴリオ暦が採用された「明治6年1月1日」以降に対応)
 * 引数：西暦(9999/99/99 or 9999-99-99)
 * 戻値：和暦
 */
function chgAdToJpDate($value)
{
    //和暦変換用データ
    $arr = array(
        array('date' => '2019-05-01', 'year' => '2019', 'name' => '令和'),// 新元号追加
        array('date' => '1989-01-08', 'year' => '1989', 'name' => '平成'),
        array('date' => '1926-12-25', 'year' => '1926', 'name' => '昭和'),
        array('date' => '1912-07-30', 'year' => '1912', 'name' => '大正'),
        array('date' => '1873-01-01', 'year' => '1868', 'name' => '明治'),// 明治6年1月1日以降
    );
    // 日付チェック
    if (chkDate($value) === false) {
        return '';
    }
    $arrad  = explode('-', str_replace('/', '-', $value));
    $addate = (int)sprintf('%d%02d%02d', (int)$arrad[0], (int)$arrad[1], (int)$arrad[2]);
    $result = '';
    foreach ($arr as $key => $row) {
        // 日付チェック
        if (chkDate($row['date']) === false) {
            return '';
        }
        $arrjp  = explode('-', str_replace('/', '-', $row['date']));
        $jpdate = (int)sprintf('%d%02d%02d', (int)$arrjp[0], (int)$arrjp[1], (int)$arrjp[2]);
        // 元号の開始日と比較
        if ($addate >= $jpdate) {
            // 和暦年の計算
            $year = sprintf('%d', ((int)$arrad[0] - (int)$row['year']) + 1);
            //			if ((int)$year === 1) {
            //				$year = '元';
            //			}
            // 和暦年月日作成
            $result = sprintf('%s%s年%d月%d日', $row['name'], $year, (int)$arrad[1], (int)$arrad[2]);
            break;
        }
    }
    return $result;
}
function chgAdToJpNengo($value)
{
    //和暦変換用データ
    $arr = array(
        array('date' => '2019-05-01', 'year' => '2019', 'name' => '令和'),
        array('date' => '1989-01-08', 'year' => '1989', 'name' => '平成'),
        array('date' => '1926-12-25', 'year' => '1926', 'name' => '昭和'),
        array('date' => '1912-07-30', 'year' => '1912', 'name' => '大正'),
        array('date' => '1873-01-01', 'year' => '1868', 'name' => '明治'),// 明治6年1月1日以降
    );
    // 日付チェック
    if (chkDate($value) === false) {
        return '';
    }
    $arrad  = explode('-', str_replace('/', '-', $value));
    $addate = (int)sprintf('%d%02d%02d', (int)$arrad[0], (int)$arrad[1], (int)$arrad[2]);
    $result = '';
    foreach ($arr as $key => $row) {
        // 日付チェック
        if (chkDate($row['date']) === false) {
            return '';
        }
        $arrjp  = explode('-', str_replace('/', '-', $row['date']));
        $jpdate = (int)sprintf('%d%02d%02d', (int)$arrjp[0], (int)$arrjp[1], (int)$arrjp[2]);
        // 元号の開始日と比較
        if ($addate >= $jpdate) {
            // 和暦年月日作成
            $result = $row['name'];
            break;
        }
    }
    return $result;
}
function chgAdToJpYear($value)
{
    //和暦変換用データ
    $arr = array(
        array('date' => '2019-05-01', 'year' => '2019', 'name' => '令和'),// 新元号追加
        array('date' => '1989-01-08', 'year' => '1989', 'name' => '平成'),
        array('date' => '1926-12-25', 'year' => '1926', 'name' => '昭和'),
        array('date' => '1912-07-30', 'year' => '1912', 'name' => '大正'),
        array('date' => '1873-01-01', 'year' => '1868', 'name' => '明治'),// 明治6年1月1日以降
    );
    // 日付チェック
    if (chkDate($value) === false) {
        return '';
    }
    $arrad  = explode('-', str_replace('/', '-', $value));
    $addate = (int)sprintf('%d%02d%02d', (int)$arrad[0], (int)$arrad[1], (int)$arrad[2]);
    $result = '';
    foreach ($arr as $key => $row) {
        // 日付チェック
        if (chkDate($row['date']) === false) {
            return '';
        }
        $arrjp  = explode('-', str_replace('/', '-', $row['date']));
        $jpdate = (int)sprintf('%d%02d%02d', (int)$arrjp[0], (int)$arrjp[1], (int)$arrjp[2]);
        // 元号の開始日と比較
        if ($addate >= $jpdate) {
            // 和暦年の計算
            $result = sprintf('%d', ((int)$arrad[0] - (int)$row['year']) + 1);
            break;
        }
    }
    return $result;
}

// 和暦(年号年) => 西暦
function chgJpToAdYear($wareki_year)
{

    $wareki_year = str_replace('元年', '1年', mb_convert_kana($wareki_year, 'n'));

    if (preg_match('!^(明治|大正|昭和|平成|令和)([0-9]+)年$!', $wareki_year, $matches)) {

        $era_name = $matches[1];
        $year = intval($matches[2]);

        if ($era_name === '明治') {
            $year += 1867;
        } elseif ($era_name === '大正') {
            $year += 1911;
        } elseif ($era_name === '昭和') {
            $year += 1925;
        } elseif ($era_name === '平成') {
            $year += 1988;
        } elseif ($era_name === '令和') {
            $year += 2018;
        }

        return $year . '年';
    }
    return null;
}
/**
 * 日付チェック
 * 引数：西暦(9999/99/99 or 9999-99-99)
 * 戻値：結果
 */
function chkDate($value)
{
    if ((strpos($value, '/') !== false) && (strpos($value, '-') !== false)) {
        return false;
    }
    $value   = str_replace('/', '-', $value);
    $pattern = '#^([0-9]{1,4})-(0[1-9]|1[0-2]|[1-9])-([0-2][0-9]|3[0-1]|[1-9])$#';
    preg_match($pattern, $value, $arrmatch);
    if ((isset($arrmatch[1]) === false) || (isset($arrmatch[2]) === false) || (isset($arrmatch[3]) === false)) {
        return false;
    }
    if (checkdate((int)$arrmatch[2], (int)$arrmatch[3], (int)$arrmatch[1]) === false) {
        return false;
    }

    return true;
}
// 年齢計算
function getAge($birthday)
{
    if (!$birthday) {
        return null;
    }
    $birthday = str_replace("-", "", $birthday);
    $today    = str_replace("-", "", TODAY);
    return floor(($today - $birthday) / 10000);
}
