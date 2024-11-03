<?php require_once(dirname(__FILE__) . "/php/uninsure_edit.php"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!--COMMON-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
<!--CONTENT-->
<title>保険外マスタ詳細</title>
</head>

<body>
<div id="wrapper"><div id="base">
<!--HEADER-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
<!--CONTENT-->
<form action="" class="p-form-validate" method="post">
<article id="content">
<!--/// CONTENT_START ///-->
<h2>保険外マスタ詳細</h2>
<div id="subpage"><div id="insurance-master" class="nursing">

<div class="wrap">
	<div class="cont_office cancel_act">
		<div class="tit">事業所選択</div>
		<div class="close close_part">✕<span>閉じる</span></div>
		<table>
			<thead>
				<tr>
					<th></th>
					<th>事業所名</th>
					<th>契約事業所名</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($dispOfficeList as $val): ?>
				<tr>
					<td><button type="button" value="<?=$val['name'] ?>" onclick="setOffice(this)">選択</button></td>
					<td><?=$val['name'] ?></td>
					<td></td>
				</tr>
<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="insurance_form">
		<div class="line1 code" style="width:130px;">
			<span class="label_t">コード</span>
			<input type="text" name="upAry[code1]" class="code_01" style="width:45px;" value="<?= !empty($dispData['code1']) ? $dispData['code1'] : '' ?>">
			<input type="text" name="upAry[code2]" class="code_02" style="width:75px;" value="<?= !empty($dispData['code2']) ? $dispData['code2'] : '' ?>">	
		</div>
		<div class="line1 type" style="width:120px;">
			<span class="label_t">種類</span>
			<select name="upAry[type]" style="width:120px;">
				<option disabled <?= !empty($dispData['type']) && $dispData['type'] == '' ? 'selected' : '' ?> hidden></option>
<?php foreach ($dispCode['種類'] as $val): ?>
				<option value="<?=$val ?>" <?= !empty($dispData['type']) && $dispData['type'] == $val ? 'selected' : '' ?>><?= $val ?></option>
<?php endforeach; ?>
			</select>
		</div>
		<div class="line1 code_name">
			<span class="label_t">コード名称</span>
			<input type="text" name="upAry[name]" class="codename" value="<?= !empty($dispData['name']) ? $dispData['name'] : '' ?>">
		</div>
		<div class="line1 invoice">
			<span class="label_t">請求書名称</span>
			<input type="text" name="upAry[disp_name]" class="invoice_name" value="<?= !empty($dispData['disp_name']) ? $dispData['disp_name'] : '' ?>">
		</div>
		<div class="line1 remarks">
			<span class="label_t">備考</span>
			<textarea name="upAry[remarks]"><?=!empty($dispData['remarks']) ? $dispData['remarks'] : '' ?></textarea>
		</div>
		<div class="service_code">
			<input type="checkbox" name="upAry[standard_flg]" id="s_code" value="1" <?= (!empty($dispData['standard_flg']) && $dispData['standard_flg'] == '1') ? 'checked' : '' ?>><label for="s_code">基本サービスコードとして使用</label>
		</div>
		<div class="line1 validity">
			<span class="label_t">有効期間</span>
			<input type="date" name="upAry[start_day]" class="" value="<?=$dispData['start_day'] ?>">
                        <small>～</small>
                        <input type="date" name="upAry[end_day]" class="" value="<?=$dispData['end_day'] ?>">
		</div>
		<div class="line1 amount">
			<span class="label_t">金額</span>
			<input type="text" name="upAry[price]" class="amnt" value="<?= !empty($dispData['price']) ? $dispData['price'] : '' ?>">
		</div>
		<div class="line1 tax_type">
			<span class="label_t">税区分</span>
			<select name="upAry[zei_type]" onchange="setRate(this)">
				<option disabled <?= $dispData['zei_type'] == '' ? 'selected' : '' ?> hidden></option>
<?php foreach ($dispCode['税区分'] as $val): ?>
				<option value="<?= !empty($val) ? $val : '' ?>" <?= !empty($dispData['zei_type']) && $dispData['zei_type'] == $val ? 'selected' : '' ?>><?=$val ?></option>
<?php endforeach; ?>
			</select>
		</div>
		<div class="line1 tax_rate">
			<span class="label_t">税率</span>
			<input type="text" name="upAry[rate]" id="rate" class="t_rate" value="<?= !empty($dispData['rate']) ? $dispData['rate'] : '' ?>">
		</div>
		<div class="line1 deductible">
			<span class="label_t">控除対象</span>
			<select name="upAry[subsidy]">
				<option disabled <?= $dispData['subsidy'] == '' ? 'selected' : '' ?> hidden></option>
<?php foreach ($dispCode['控除対象'] as $val): ?>
				<option value="<?=$val ?>" <?= !empty($dispData['subsidy']) && $dispData['subsidy'] == $val ? 'selected' : '' ?>><?=$val ?></option>
<?php endforeach; ?>
			</select>
		</div>
		<div class="office">
			<span class="label_t">使用事業所</span>
			<input type="text" name="upAry[office]" id="office" class="office_search" placeholder="事業所検索" value="<?= !empty($dispData['office']) ? $dispData['office'] : '' ?>">
			<input type="text" name="upAry[office_]" id="office2" class="s_result" value="<?= !empty($dispData['office']) ? $dispData['office'] : '' ?>" disabled>
		</div>
		<div class="info_box">
			<dl>
				<dt>初回登録：</dt>
				<dd>
					<p><?= !empty($dispData['create_day']) ? $dispData['create_day'] : '' ?>   <?= !empty($dispData['create_time']) ? $dispData['create_time'] : '' ?></p>
					<p><?= !empty($dispData['create_name']) ? $dispData['create_name'] : '' ?></p>
				</dd>
			</dl>
			<dl>
				<dt>更新日時：</dt>
				<dd>
					<p><?= !empty($dispData['update_day']) ? $dispData['update_day'] : '' ?>   <?= !empty($dispData['update_time']) ? $dispData['update_time'] : '' ?></p>
					<p><?= !empty($dispData['update_name']) ? $dispData['update_name'] : '' ?></p>
				</dd>
			</dl>
			<!-- <div class="btn_box">
				<span class="btn cancel">キャンセル</span>
				<span class="btn delete">削除</span>
				<span class="btn save">保存</span>
			</div> -->
		</div>
	</div>
</div>

</div></div>
<!--/// CONTENT_END ///-->
<div class="fixed_navi uninsure-navi uninsure-d-navi">
	<div class="box">
		<div class="btn back pc" style="margin-top: 13px;"><a href="/system/uninsure_list">保険外マスタ一覧にもどる</a></div>
		<div class="controls">
			<div class="btn cancel"><a href="/system/uninsure_list">キャンセル</a></div>
			<button type="submit" name="btnEntry" value="true" class="btn save">保存</button>
		</div>
	</div>
</div>
</article>
</form >
<!--CONTENT-->
</div></div>
<p id="page"><a href="#wrapper">PAGE TOP</a></p>
<script>
function setOffice(t) {
	$("#office")[0].value = t.value;
	$("#office2")[0].value = t.value;
	$(".cont_office")[0].style.display="none";
}
function setRate(t) {
	if (t.options[t.selectedIndex].value == '非課税') {$("#rate")[0].value=0}
}
</script>
</body>
</html>