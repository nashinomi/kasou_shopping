<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>カート確認</title>
	<link rel="stylesheet" type="text/css" href="../css/mystyle.css">
</head>
<body background="../img/a32.jpg" >
	<h2>■ カート</h2>
	<div align="center">
<?php

	$gID= $_GET['gID'];

	//	グッズテーブルに接続
	if(!$db_link= pg_connect("host=localhost user=advancement password=arkloop2 dbname=advancement")){
		echo '接続に失敗しました。';
	}
	$sql= "SELECT * FROM goods, categories, makers "
				."WHERE goods.makerID = makers.makerID and "
				."goods.categoryID = categories.categoryID and "
				."goods.goodsid =  '$gID'";
	//	グッズID重複チェック
	$result = pg_query($db_link, $sql);
	$arr = pg_fetch_array($result, 0, PGSQL_ASSOC); 		//	検索結果の一行分を配列に格納

	$mycartflag= false;

	$sql= "SELECT * FROM mycart";
	//	グッズID重複チェック
	$result = pg_query($db_link, $sql);
	$row = pg_num_rows($result); //検索結果の行数を取得
	for($i = 0; $i < $row; $i++){
		$arr_temp = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//	全クエリを配列に格納
		if($arr_temp['goodsid']==$gID){
			$mycartflag= true;
			break;
		}
	}

	if(!$mycartflag){
		//	カートテーブルを更新
		$sql= sprintf("INSERT INTO mycart(goodsid, mystock) VALUES('%s', '%d')", pg_escape_string($gID), pg_escape_string(1));
		$result = pg_query($db_link, $sql);
		echo '<p>以下の商品がカートに追加されました。</p>';
	}else{
		echo '<p>以下の商品はすでにカートに入っています。</p>';
	}

	//	接続を切る
	pg_close($db_link);
?>
	<br>
	<br>
		<table class="formtable">
		<tr>
			<th id="addform">商品ID</th>
			<td id="addform"><?= $arr['goodsid'] ?></td>
		</tr>
		<tr>
			<th id="addform">商品名</th>
			<td id="addform"><?= $arr['goodsname'] ?></td>
		</tr>
		<tr>
			<th id="addform">メーカー</th>
			<td id="addform"><?= $arr['makername'] ?></td>
		</tr>
		<tr>
			<th id="addform">カテゴリー</th>
			<td id="addform"><?= $arr['categoryname'] ?></td>
		</tr>

		<tr>
			<th id="addform">価格</th>
			<td id="addform">￥<?= number_format($arr['price']) ?></td>
		</tr>
		<tr>
			<th id="addform">備考</th>
			<td id="addform"><?= $arr['goodsnotes'] ?></td>
		</tr>
		</table>

		<p><a href="cartView.php">カートの中身を見る</a></p>
		<br><br>
		<p><a href="listGoods_cart.php">買い物を続ける</a></p>
	</div>
</body>
</html>