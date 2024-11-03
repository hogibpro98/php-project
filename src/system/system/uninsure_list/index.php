<?php require_once(dirname(__FILE__) . "/php/uninsure_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!--COMMON-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
<!--CONTENT-->
<title>保険外マスタ一覧</title>
</head>

<body>
<div id="wrapper"><div id="base">
<!--HEADER-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
<!--CONTENT-->
<article id="content">
<!--/// CONTENT_START ///-->
<h2>保険外マスタ一覧</h2>
<div id="subpage"><div id="insurance-master" class="nursing">

<form action="" class="p-form-validate" method="get">
<div class="cont_head">
	<div class="code">
		<span class="label_t text_blue">コード</span>
		<input type="text" name="sAry[code]" class="code_input" value="<?=!empty($dispSearch['code']) ? $dispSearch['code'] : null  ?>">
	</div>
	<div class="type">
		<span class="label_t text_blue">種類</span>
		<select name="sAry[type]">
			<option <?= !empty($dispSearch['type']) && $dispSearch['type'] == '' ? 'selected' : '' ?>></option>
<?php foreach ($dispCode['種類'] as $val): ?>
                        <?php $select = (!empty($dispSearch['type']) && $dispSearch['type'] == $val) ? 'selected' : ''; ?>
			<option value="<?= $val ?>" <?= $select ?>><?=$val ?></option>
<?php endforeach; ?>
		</select>
	</div>
	<div class="code_name">
		<span class="label_t text_blue">コード名称</span>
		<input type="text" name="sAry[name]" class="codename" value="<?=!empty($dispSearch['name']) ? $dispSearch['name'] : null ?>">
	</div>
	<div class="expired">
		<input type="checkbox" name="sAry[range]" id="ex_view" value="1" <?= (!empty($dispSearch['range']) && $dispSearch['range'] != '') ? 'checked' : '' ?>><label for="ex_view">有効期間が切れたマスタ表示</label>
	</div>
	<button type="submit" name="btnSearch" value="true" class="btn search">絞り込み</button>
	<button type="submit" name="btnClear" value="true" class="btn clear">クリア</button>
	<div class="btn add"><a href="/system/uninsure_edit">新規登録</a></div>
</div>
</form>

<div class="wrap">
	<div class="dis_num">該当件数<b><?= is_null($dispData) ? 0 : count($dispData) ?></b></div>
	<table class="main_list">
		<tr>
			<th class="code">保険外<br>コード</th>
			<th class="type">種類</th>
			<th class="codename">コード名称</th>
			<th class="invoice">請求書名称</th>
			<th class="service_code">基本サービス<br>コードとして使用</th>
			<th class="validity">有効期間</th>
			<th class="amount">金額</th>
			<th class="tax_type">税区分</th>
			<th class="tax_rate" style="width:30px !important;">税率</th>
			<th class="deductible">控除対象</th>
			<th class="office">使用事業所</th>
			<th class="modified">更新日時/ユーザ</th>
                        <th style="width:250px"></th>
		</tr>
<?php foreach ($dispData as $tgtId => $val): ?>
		<tr>
			<form action="" class="p-form-validate" method="get">
			<input type="hidden" name="id" value="<?= $val['unique_id'] ?>">
			<td><?= $val['code1'] . '　' . $val['code2']  ?></td>
			<td><?= $val['type'] ?></td>
			<td><?= $val['name'] ?></td>
			<td><?= $val['disp_name'] ?></td>
			<td><?= $val['standard_flg'] ?></td>
			<td><?= $val['start_day'] . '～' . $val['end_day'] ?></td>
			<td><?= $val['price'] . '円' ?></td>
			<td><?= $val['zei_type'] ?></td>
			<td><?= $val['rate'] . '％' ?></td>
			<td><?= $val['subsidy'] ?></td>
			<td><?= $val['office'] ?></td>
			<td><?= $val['update_day'] . '　' . $val['update_time'] . '　' . $val['update_name'] ?></td>
			<td><div class="btn edit" style="min-width:50px"><a href="/system/uninsure_edit?id=<?= $tgtId ?>">編集</a></div></td>
			<td><div class="btn delete" style="min-width:50px"S><button type="submit" name="btnDelete" value="true" class="btn delete">削除</button></div></td>
			</form>
		</tr>
<?php endforeach; ?>
	</table>
</div>
<!-- ページャー -->
<?php dispPager($tgtData, $page, $line, $server['requestUri']) ?>

</div></div>
<!--/// CONTENT_END ///-->
</article>
<!--CONTENT-->
</div></div>
<p id="page"><a href="#wrapper">PAGE TOP</a></p>
</body>
</html>