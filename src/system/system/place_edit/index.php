<?php require_once(dirname(__FILE__) . "/php/place_edit.php"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!--COMMON-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
<!--CONTENT-->
<script src="/system/place_edit/js/place.js"></script>
<title>拠点管理</title>
</head>

<body>
<div id="wrapper"><div id="base">
<!--HEADER-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
<!--CONTENT-->
<article id="content">
<!--/// CONTENT_START ///-->
<h2>拠点管理</h2>
<div id="subpage"><div id="base_kanri-detail" class="nursing">


<form action="" class="p-form-validate" method="post">
<div class="wrap">

	<div class="base_box nurse_record">
		<div class="box1">
			<dl>
				<dt><label for="base-name">拠点名称<span class="req">*</span></label></dt>
				<dd><input type="text" id="base-name" class="validate[required]" name="upAry[name]" value="<?= $dispData['name'] ?>"></dd>
			</dl>
		</div>
		<div class="box2">
			<dl>
				<dt><label for="post-code">郵便番号</label></dt>
				<dd class="f-keyData" data-tg_url='/system/place_edit/ajax/address_ajax.php?type=post'>
                                    <input type="text" id="post" name="upAry[post]" value="<?= $dispData['post'] ?>" class="f-keyVal">
                                </dd>
			</dl>
			<dl>
				<dt><label>住所<span class="req">*</span></label></dt>
				<dd><div class="box-i">
					<div><label for="base-prefecture">都道府県</label>
						<select id="prefecture" name="upAry[prefecture]">
                                                    <option value="">▼選択</option>
                                                    <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                    <?php $select = $pref === $dispData['prefecture'] ? ' selected' : null; ?>
                                                    <option value="<?= $pref ?>"<?= $select ?>><?= $pref ?></option>
                                                    <?php endforeach; ?>
                                                    <!-- ※クラス名で市区町村と連携 -->
						</select>
					</div>
					<div><label for="base-municipal">市区町村</label>
						<select id="municipal" name="upAry[area]">
                                                    <option value="">▼選択</option>
                                                    <?php foreach ($areaMst as $pref => $areaMst2): ?>
                                                    <?php foreach ($areaMst2 as $city => $areaMst3): ?>
                                                    <?php $select = $city === $dispData['area'] ? ' selected' : null; ?>
                                                    <option class="<?= $pref ?>" value="<?= $city ?>"<?= $select ?>><?= $city ?></option>
                                                    <?php endforeach; ?>
                                                    <?php endforeach; ?>
						</select>
					</div>
					<div><label for="base-town">町域</label><input type="text" id="town" name="upAry[address1]" value="<?= $dispData['address1'] ?>"></div>
					<div><label for="base-houseno">番地以降</label><input type="text" id="houseno" name="upAry[address2]" value="<?= $dispData['address2'] ?>"></div>
				</div></dd>
			</dl>
		</div>
	</div>

	<div class="nurse_record record9">
		<div class="i_register">
			<span class="label_t text_blue">初回登録：</span>
			<span class="label_t hidzuke"><?= $dispData['create_day'] ?></span>
			<span class="label_t time"><?= $dispData['create_time'] ?></span>
			<span class="label_t"><?= $dispData['create_name'] ?></span>|
		</div>
		<div class="l_update">
			<span class="label_t text_blue">更新日時：</span>
			<span class="label_t hidzuke"><?= $dispData['update_day'] ?></span>
			<span class="label_t time"><?= $dispData['update_time'] ?></span>
			<span class="label_t"><?= $dispData['update_name'] ?></span>
		</div>
	</div>
</div>







</div></div>
<!--/// CONTENT_END ///-->
<div class="fixed_navi staff-navi">
	<div class="box">
		<div class="controls">
                    
			<div class="btn cancel"><a href="/system/place_list/">一覧へ戻る</a></div>
			<!--<div class="btn save"><a href="base.html">保存</a></div>-->
                        <button type="submit" name="btnEntry" value="true" class="btn save">保存</button>
		</div>
	</div>
</div>
</form>

</article>
<!--CONTENT-->
</div></div>
<p id="page"><a href="#wrapper">PAGE TOP</a></p>
</body>
</html>