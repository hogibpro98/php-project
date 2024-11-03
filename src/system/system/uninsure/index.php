<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/php/com_start.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<!--COMMON-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/common.php'); ?>
<!--CONTENT-->
<title>保険外マスタ</title>
</head>

<body>
<div id="wrapper"><div id="base">
<!--HEADER-->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/common/parts/header.php'); ?>
<!--CONTENT-->
<article id="content">
<!--/// CONTENT_START ///-->
<h2>保険外マスタ</h2>
<div id="subpage"><div id="insurance-master" class="nursing">

<div class="cont_head">
	<div class="code">
		<span class="label_t text_blue">コード</span>
		<input type="text" name="コード" class="code_input" value="200001">
	</div>
	<div class="type">
		<span class="label_t text_blue">種類</span>
		<select>
			<option selected>自費</option>
			<option>食事朝</option>
			<option>食事昼</option>
			<option>食事夕</option>
			<option>食事</option>
			<option>おむつ</option>
			<option>医療品</option>
			<option>その他</option>
		</select>
	</div>
	<div class="code_name">
		<span class="label_t text_blue">コード名称</span>
		<input type="text" name="コード名称" class="codename" value="朝食">
	</div>
	<div class="expired">
		<input type="checkbox" name="expired_view" id="ex_view" checked><label for="ex_view">有効期間が切れたマスタ表示</label>
	</div>
	<div class="btn search">絞り込み</div>
	<div class="btn clear">クリア</div>
	<div class="btn add">新規登録</div>
</div>

<div class="wrap">
	<div class="cont_office_dummy cancel_act">
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
				<tr>
					<td><button>選択</button></td>
					<td>本社</td>
					<td>本社</td>
				</tr>
				<tr>
					<td><button>選択</button></td>
					<td>かえりえ東大宮</td>
					<td>訪問看護かえりえ東大宮</td>
				</tr>
				<tr>
					<td><button>選択</button></td>
					<td>かえりえ東大宮</td>
					<td>訪問看護かえりえ東大宮</td>
				</tr>
				<tr>
					<td><button>選択</button></td>
					<td>かえりえ東大宮</td>
					<td>訪問看護かえりえ東大宮</td>
				</tr>
				<tr>
					<td><button>選択</button></td>
					<td>かえりえ東大宮</td>
					<td>訪問看護かえりえ東大宮</td>
				</tr>
				<tr>
					<td><button>選択</button></td>
					<td>かえりえ東大宮</td>
					<td>訪問看護かえりえ東大宮</td>
				</tr>
				<tr>
					<td><button>選択</button></td>
					<td>かえりえ東大宮</td>
					<td>訪問看護かえりえ東大宮</td>
				</tr>
				<tr>
					<td><button>選択</button></td>
					<td>かえりえ東大宮</td>
					<td>訪問看護かえりえ東大宮</td>
				</tr>
			</tbody>
		</table>
	</div>
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
			<th class="tax_rate">税率</th>
			<th class="deductible">控除対象</th>
			<th class="office">使用事業所</th>
			<th class="modified">更新日時/ユーザ</th>
		</tr>
		<tr class="active">
			<td>20　0001</td>
			<td>自費</td>
			<td>宿泊</td>
			<td>宿泊費</td>
			<td>〇</td>
			<td>2022/01/01～9999/12/31</td>
			<td>3000円</td>
			<td>非課税</td>
			<td>0％</td>
			<td>控除対象</td>
			<td>全事業所</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr class="e_form">
			<td colspan="12">
				<div class="insurance_form">
					<div class="line1 code">
						<span class="label_t">コード</span>
						<input type="text" name="コード" class="code_01" value="20">
						<input type="text" name="コード" class="code_02" value="0001">	
					</div>
					<div class="line1 type">
						<span class="label_t">種類</span>
						<select>
							<option selected>自費</option>
							<option>食事朝</option>
							<option>食事昼</option>
							<option>食事夕</option>
							<option>食事</option>
							<option>おむつ</option>
							<option>医療品</option>
							<option>その他</option>
						</select>
					</div>
					<div class="line1 code_name">
						<span class="label_t">コード名称</span>
						<input type="text" name="コード名称" class="codename" value="宿泊代">
					</div>
					<div class="line1 invoice">
						<span class="label_t">請求書名称</span>
						<input type="text" name="請求書名称" class="invoice_name" value="宿泊費">
					</div>
					<div class="line1 remarks">
						<span class="label_t">備考</span>
						<textarea>メモメモメモメモメモメモメモメモメモメモメモ</textarea>
					</div>
					<div class="service_code">
						<input type="checkbox" name="s_code" id="s_code" checked disabled><label for="s_code" class="disabled">基本サービスコードとして使用</label>
					</div>
					<div class="line1 validity">
						<span class="label_t">有効期間</span>
						<input type="text" name="date" class="master_date date_no-Day" value="2021/01/01"><small>～</small><input type="text" name="date" class="master_date date_no-Day" value="9999/12/31">
					</div>
					<div class="line1 amount">
						<span class="label_t">金額</span>
						<input type="text" name="金額" class="amnt" value="3000円">
					</div>
					<div class="line1 tax_type">
						<span class="label_t">税区分</span>
						<select>
							<option>課税</option>
							<option selected>非課税</option>
						</select>
					</div>
					<div class="line1 tax_rate">
						<span class="label_t">税率</span>
						<input type="text" name="税率" class="t_rate" value="0%" disabled>
					</div>
					<div class="line1 deductible">
						<span class="label_t">控除対象</span>
						<select>
							<option selected>控除対象</option>
							<option>控除対象外</option>
						</select>
					</div>
					<div class="office">
						<span class="label_t">使用事業所</span>
						<input type="text" name="使用事業所" class="office_search" placeholder="事業所検索">
						<input type="text" name="使用事業所" class="s_result" value="全事業所" disabled>
					</div>
					<div class="info_box">
						<dl>
							<dt>初回登録：</dt>
							<dd>
								<p>2021/11/13   08:12</p>
								<p>佐藤 清人</p>
							</dd>
						</dl>
						<dl>
							<dt>更新日時：</dt>
							<dd>
								<p>2022/04/20   13:00</p>
								<p>志良堂美緒</p>
							</dd>
						</dl>
						<div class="btn_box">
							<span class="btn cancel">キャンセル</span>
							<span class="btn delete">削除</span>
							<span class="btn save">保存</span>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<tr class="active">
			<td>20　0001</td>
			<td>食事朝</td>
			<td>朝食代(刻み食・ミキサー食)</td>
			<td>朝食代</td>
			<td>〇</td>
			<td>2022/01/01～9999/12/31</td>
			<td>200円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr class="e_form">
			<td colspan="12">
				<div class="insurance_form">
					<div class="line1 code">
						<span class="label_t">コード</span>
						<input type="text" name="コード" class="code_01" value="20">
						<input type="text" name="コード" class="code_02" value="0001">	
					</div>
					<div class="line1 type">
						<span class="label_t">種類</span>
						<select>
							<option>自費</option>
							<option selected>食事朝</option>
							<option>食事昼</option>
							<option>食事夕</option>
							<option>食事</option>
							<option>おむつ</option>
							<option>医療品</option>
							<option>その他</option>
						</select>
					</div>
					<div class="line1 code_name">
						<span class="label_t">コード名称</span>
						<input type="text" name="コード名称" class="codename" value="朝食代(刻み食・ミキサー食)">
					</div>
					<div class="line1 invoice">
						<span class="label_t">請求書名称</span>
						<input type="text" name="請求書名称" class="invoice_name" value="朝食代">
					</div>
					<div class="line1 remarks">
						<span class="label_t">備考</span>
						<textarea>メモメモメモメモメモメモメモメモメモメモメモ</textarea>
					</div>
					<div class="service_code">
						<input type="checkbox" name="s_code" id="s_code"><label for="s_code">基本サービスコードとして使用</label>
					</div>
					<div class="line1 validity">
						<span class="label_t">有効期間</span>
						<input type="text" name="date" class="master_date date_no-Day" value="2021/01/01"><small>～</small><input type="text" name="date" class="master_date date_no-Day" value="9999/12/31">
					</div>
					<div class="line1 amount">
						<span class="label_t">金額</span>
						<input type="text" name="金額" class="amnt" value="300円">
					</div>
					<div class="line1 tax_type">
						<span class="label_t">税区分</span>
						<select>
							<option selected>課税</option>
							<option>非課税</option>
						</select>
					</div>
					<div class="line1 tax_rate">
						<span class="label_t">税率</span>
						<input type="text" name="税率" class="t_rate" value="10%">
					</div>
					<div class="line1 deductible">
						<span class="label_t">控除対象</span>
						<select>
							<option>控除対象</option>
							<option selected>控除対象外</option>
						</select>
					</div>
					<div class="office">
						<span class="label_t">使用事業所</span>
						<input type="text" name="使用事業所" class="office_search" placeholder="事業所検索" >
						<input type="text" name="使用事業所" class="s_result" value="かえりえ大橋、かえりえ竹ノ塚" disabled>
					</div>
					<div class="info_box">
						<dl>
							<dt>初回登録：</dt>
							<dd>
								<p>2021/11/13   08:12</p>
								<p>佐藤 清人</p>
							</dd>
						</dl>
						<dl>
							<dt>更新日時：</dt>
							<dd>
								<p>2022/04/20   13:00</p>
								<p>志良堂美緒</p>
							</dd>
						</dl>
						<div class="btn_box">
							<span class="btn cancel">キャンセル</span>
							<span class="btn delete">削除</span>
							<span class="btn save">保存</span>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>食事朝</td>
			<td>昼食代</td>
			<td>朝食代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>250円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>自費</td>
			<td>エンゼルケア代</td>
			<td>エンゼルケア</td>
			<td>〇</td>
			<td>2022/01/01～9999/12/31</td>
			<td>5000円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>おむつ</td>
			<td>おむつ・リハビリパンツ代</td>
			<td>おむつ・リハビリパンツ代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>50円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>食事朝</td>
			<td>昼食代</td>
			<td>朝食代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>200円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>食事朝</td>
			<td>昼食代</td>
			<td>朝食代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>200円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>食事朝</td>
			<td>昼食代</td>
			<td>朝食代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>200円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>食事朝</td>
			<td>昼食代</td>
			<td>朝食代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>200円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>食事朝</td>
			<td>昼食代</td>
			<td>朝食代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>200円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>食事朝</td>
			<td>昼食代</td>
			<td>朝食代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>200円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>食事朝</td>
			<td>昼食代</td>
			<td>朝食代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>200円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>食事朝</td>
			<td>昼食代</td>
			<td>朝食代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>200円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
		<tr>
			<td>20　0001</td>
			<td>食事朝</td>
			<td>昼食代</td>
			<td>朝食代</td>
			<td></td>
			<td>2022/01/01～9999/12/31</td>
			<td>200円</td>
			<td>課税</td>
			<td>10％</td>
			<td>控除対象外</td>
			<td>かえりえ大橋、山田 花子</td>
			<td>2022/04/20　13:00　志良堂美緒</td>
		</tr>
	</table>
</div>
<div id="pager">
	<div class="active">1</div>
	<div>2</div>
</div>






</div></div>
<!--/// CONTENT_END ///-->
</article>
<!--CONTENT-->
</div></div>
<p id="page"><a href="#wrapper">PAGE TOP</a></p>
</body>
</html>