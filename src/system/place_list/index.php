<?php require_once(dirname(__FILE__) . "/php/place_list.php"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!--COMMON-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
<!--CONTENT-->
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
<div id="subpage"><div id="base_kanri" class="nursing">

<form action="" class="p-form-validate" method="post">
<div class="wrap">
	<div class="btn add"><a href="/system/place_edit">拠点追加</a></div>
	<table class="dis_result">
		<tr>
			<th class="th_base-name">拠点名</th>
			<th class="th_base-name">階層コード</th>
			<th class="th_post-code">郵便番号</th>
			<th class="th_base-address">住所</th>
			<th class="th_base-office">事業所/看多機</th>
			<th class="th_nursing">訪問看護</th>
			<th class="th_edit"></th>
			<th class="th_delete"></th>
		</tr>

                <?php foreach ($dispData as $tgtId => $val): ?>
		<tr>
			<td><?= $val['name'] ?></td>
			<td><?= $val['layer_code'] ?></td>
			<td><?= $val['post'] ?></td>
			<td><?= $val['prefecture'] . ' ' . $val['area'] ?></td>
                        <?php if (isset($val['看多機'])): ?>
			<td><a href="/system/office/?id=<?= $tgtId ?>"><?= $val['看多機']['name'] ?></a></td>
                        <?php else: ?>
			<td></td>
                        <?php endif; ?>
                        <?php if (isset($val['訪問看護'])): ?>
			<td><a href="/system/office/?id=<?= $tgtId ?>"><?= $val['訪問看護']['name'] ?></a></td>
                        <?php else: ?>
			<td></td>
                        <?php endif; ?>
			<td><div class="btn edit" style="padding:5px;font-size:14px;text-align: center;"><a href="/system/place_edit?place_id=<?= $tgtId ?>">編集</a></div></td>
			<td><div><button type="submit" name="btnDel" value="<?= $tgtId ?>" class="btn delete" style="padding:5px;font-size:14px;text-align: center;height:35.6px;">削除</button></div></td>
		</tr>
                <?php endforeach; ?>
<!--		<tr>
			<td>長野看多機拠点かえりえ</td>
			<td>111-1111</td>
			<td>東京都千代田区丸の内１－１－１</td>
			<td><a href="/system/office/index.php">看多機かえりえ伊川谷有瀬</a></td>
			<td><a href="/system/office/index.php">訪問看護かえりえ伊川谷有瀬</a></td>
			<td><div class="btn edit"><a href="/system/place_edit/index.php">編集</a></div></td>
		</tr>-->
	</table>

</div>
</form>


<!-- ページャー -->
<?php dispPager($tgtData, $page, $line, $server['requestUri']) ?>
<!--<div id="pager">
	<div class="active">1</div>
	<div>2</div>
</div>-->





</div></div>
<!--/// CONTENT_END ///-->
</article>
<!--CONTENT-->
</div></div>
<p id="page"><a href="#wrapper">PAGE TOP</a></p>
</body>
</html>