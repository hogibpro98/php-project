<?php require_once(dirname(__FILE__) . "/php/log_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!--COMMON-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
<!--CONTENT-->
<title>ログ管理</title>
</head>

<body>
<div id="wrapper"><div id="base">
<!--HEADER-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
<!--CONTENT-->
<article id="content">
<!--/// CONTENT_START ///-->
<h2>ログ管理</h2>
<div id="subpage"><div id="log" class="nursing">

<form action="" class="p-form-validate" method="">
<div class="log-details">
	<div class="box1">
<!--		<div class="emp_n">
			<span class="label_t text_blue">従業員</span>
			<input type="text" name="employee_ID" id="emp_ID" value="0000079">
			<input type="text" name="employee_name" id="emp_name" value="佐藤 清人">
			<span class="n_search display_employee">Search</span>
		</div>-->
		<div class="date_period">
			<span class="label_t text_blue">対象期間</span>
			<input type="text" name="date" class="master_date date_no-Day date_start" value="2022/03/01">
                        <small>～</small>
                        <input type="text" name="date" class="master_date date_no-Day date_end" value="2022/03/31">
		</div>
	</div>
	<div class="box2">
<!--		<div class="user_n">
			<span class="label_t text_blue">利用者</span>
			<input type="text" name="user_ID" id="user_ID" value="00000009">
			<input type="text" name="user_name" id="username" value="大橋 花子">
			<span class="n_search display_user">Search</span>
		</div>-->
<!--		<div class="log_type">
			<span class="label_t text_blue">log種別</span>
			<select name="search[type]">
				<option value="">全て</option>
				<option value="">更新ログ</option>
				<option value="">エラーログ</option>
			</select>
		</div>-->
		<span class="label_t text_blue">画面</span>
		<select>
			<option>利用者一覧</option>
			<option>利用者基本情報</option>
			<option>利用者予定実績</option>
			<option>週間スケジュール</option>
			<option>画像関連一覧</option>
			<option>各種帳票</option>
			<option>指示書</option>
			<option>祷痛計画書</option>
		</select>
	</div>
<!--	<div class="gamen_use">
		<span class="label_t text_blue">画面</span>
		<select>
			<option>利用者一覧</option>
			<option>利用者基本情報</option>
			<option>利用者予定実績</option>
			<option>週間スケジュール</option>
			<option>画像関連一覧</option>
			<option>各種帳票</option>
			<option>指示書</option>
			<option>祷痛計画書</option>
		</select>
	</div>	-->
	<div class="s_control">
		<!--<span class="btn search">絞り込み</span>-->
                <input type="submit" name="btnSearch" value="絞り込み" class="btn search">
		<!--<span class="btn clear">クリア</span>-->
	</div>
	<!--<div class="btn excel">Excel出力</div>-->
</div>
</form>

<div class="wrap">
    <div class="dis_num">該当件数<b><?= count($tgtData) ?></b></div>
	<table class="dis_result">
		<tr>
			<th class="th_no">No</th>
			<th class="th_rec_date">記録日時</th>
<!--			<th class="th_emp-id">社員ID</th>
			<th class="th_emp-name">従業員氏名</th>
			<th class="th_office">契約事業所名</th>
			<th class="th_log-type">log種別</th>
			<th class="th_user-id">利用者ID</th>
			<th class="th_username">利用者氏名</th>-->
			<th class="th_gamen">画面</th>
			<!--<th class="th_device">デバイス</th>-->
			<!--<th class="th_sys-ip">システムIP</th>-->
			<!--<th class="th_host-ip">ホストIP</th>-->
			<!--<th class="th_access-no">アクセス<br/>人数</th>-->
			<th class="th_detail">登録内容</th>
		</tr>
<!--		<tr>
			<td>1</td>
			<td>2022/4/21  <span class="time">20:07:27</span></td>
			<td>000079</td>
			<td>佐藤 清人</td>
			<td>看多機かえりえ伊川谷有瀬</td>
			<td>ログイン</td>
			<td>00000009</td>
			<td>大橋 花子</td>
			<td>画面遷移_利<br/>用者情報詳細</td>
			<td>Windows</td>
			<td>10.64.6.18<br/>192.168.30.121</td>
			<td>54.238.110.192</td>
			<td>59</td>
		</tr>
		<tr>
			<td>1</td>
			<td>2022/4/21  <span class="time">20:07:27</span></td>
			<td>000079</td>
			<td>佐藤 清人</td>
			<td>訪問看護 かえりえ伊川谷</td>
			<td>ログイン(モバイル)</td>
			<td>00000009</td>
			<td>大橋 花子</td>
			<td>指示書</td>
			<td>iPhone/iPad</td>
			<td>192.168.30.121</td>
			<td>54.238.110.192</td>
			<td>59</td>
		</tr>
		<tr>
			<td>1</td>
			<td>2022/4/21  <span class="time">20:07:27</span></td>
			<td>000079</td>
			<td>佐藤 清人</td>
			<td>看多機かえりえ伊川谷有瀬</td>
			<td>CSV出力_基本情報</td>
			<td>83419999</td>
			<td>大橋 花子</td>
			<td>CSVデータ一覧</td>
			<td>Windows</td>
			<td>192.168.30.121</td>
			<td>54.238.110.192</td>
			<td>59</td>
		</tr>-->
	</table>
</div>






<?php dispPager($tgtData, $page, $line, $server['requestUri']) ?>




</div></div>
<!--/// CONTENT_END ///-->
</article>
<!--CONTENT-->
</div></div>
<p id="page"><a href="#wrapper">PAGE TOP</a></p>
</body>
</html>