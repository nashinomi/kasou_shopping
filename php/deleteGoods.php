<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>deleteGoods 「カテゴリー商品削除フォーム1」</title>
	<link rel="stylesheet" type="text/css" href="../css/mystyle.css">

</head>

<body background="../img/a32.jpg">

	<h2>■商品マスターメンテナンス[ 削除処理 ]</h2>

<?php
	if(!$gID= $_GET['gID']){
		$gID='';
	}

	//	グッズテーブルに接続
	if(!$db_link= pg_connect("host=localhost user=advancement password=arkloop2 dbname=advancement")){
		echo '接続に失敗しました。';
	}
	$sql= "SELECT * FROM goods, categories, makers "
				."WHERE goods.makerID = makers.makerID and "
				."goods.categoryID = categories.categoryID "
				."ORDER BY goodsID";
	//	グッズID重複チェック
	$result = pg_query($db_link, $sql);

	$row = pg_num_rows($result); //検索結果の行数を取得

	for($i = 0; $i < $row; $i++){
		$arr = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//検索結果の一行分を配列に格納
		if($arr['goodsid']==$gID){
			break;
		}
	}
	//	接続を切る
	pg_close($db_link);
?>
	<br>
	<br>

	<form method="POST" action="GoodsMaintenance.php">

	<div align="center">
		<table class="formtable">
			<caption>商品削除フォーム</caption>

			<tr>
				<th id="addform">商品ID</th>
				<td id="addform"><?= $arr['goodsid'] ?></td>
			</tr>
			<tr>
				<th id="addform">カテゴリー</th>
					<td id="addform"><?= $arr['categoryname'] ?></td>
			</tr>
			<tr>
				<th id="addform">商品名</th>
				<td id="addform"><?= $arr['goodsname'] ?></td>
			</tr>

			<tr>
				<th id="addform">単価</th>
				<td id="addform"><?= $arr['unitprice'] ?></td>
			</tr>

			<tr>
				<th id="addform">価格</th>
				<td id="addform"><?= $arr['price'] ?></td>
			</tr>

			<tr>
				<th id="addform">数量</th>
				<td id="addform"><?= $arr['stock'] ?></td>
			</tr>

			<tr>
				<th id="addform">備考</th>
				<td id="addform"><?= $arr['goodsnotes'] ?></td>
			</tr>
			<tr>
				<th id="addform">メーカー</th>
				<td id="addform"><?= $arr['makername'] ?></td>
			</tr>
		</table>

		<br>
		<p>上記の商品を削除します。よろしいですか？</p>


		<input type="submit" value="削除" style="width:60px">

		<br><br>

		<input type="button" value="削除の取り消し" onclick="location.href='listGoods.php'" style="width:120px" >
		<input type="hidden" name="gID" value=<?= $gID ?>>
		<input type="hidden" name="flag" value="3">

	</div>
	</form>

</body>

</html>