<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>listGoods_cart 「カテゴリー商品一覧 - カゴシステム -」</title>
	<link rel="stylesheet" type="text/css" href="../css/mystyle.css">
</head>
<body background="../img/a32.jpg">

	<h2>■ショッピングカート</h2>
<?php

//	データベース接続
if(!$db_link= pg_connect("host=localhost user=advancement password=arkloop2 dbname=advancement")){
	die('接続できませんでした');
}

//	削除処理
if($gID= $_GET['gID']){
	$sql= "DELETE FROM mycart WHERE goodsid='$gID'";
	$result = pg_query($db_link, $sql);
}

//	レコード多重更新処理 --
$r_count= 0;
while(true){
	$tempid= 'gID'.$r_count;
	$tempstk= 'stk'.$r_count;
	$t_stk=$_POST[$tempstk];
	if($t_gid= $_POST[$tempid]){
		$sql=sprintf("UPDATE mycart SET mystock= '%d' WHERE goodsID= '%s'",
		pg_escape_string($t_stk),
		pg_escape_string($t_gid));
		$result = pg_query($db_link, $sql);
	}else{
		break;
	}
	$r_count++;
}
?>
	<p align="center"><b>あなた様の買い物かご</b></p><br>

	<form method="POST" name= "stockform" action="cartView.php" >
	<table class="viewtable">
		<tr id="head">
			<th id="view" width="3%"><br></th><th id="view" width="10%">商品ID</th>
			<th id="view" width="25%">商品名</th><th id="view" width="20%">メーカー名</th>
			<th id="view" width="7%">価格</th><th id="view" width="5%">数量</th><th id="view" width="15%">合計金額</th>
			<th id="view" width="15%"></th>
		</tr>
<?php

//	テーブルを結合して昇順に並べたデータのSQL
//	動的なSQL構文指定
$sql= " SELECT * FROM goods, mycart, makers"
	 ." WHERE goods.makerID = makers.makerID and"
	 ." mycart.goodsid = goods.goodsid"
	 ." ORDER BY goods.goodsID";


//	一覧表示テスト
$result = pg_query($db_link, $sql);
$row = pg_num_rows($result);								 //検索結果の行数を取得
$itemCount=1;

for($i = 0; $i < $row; $i++){
	$arr = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//検索結果の一行分を配列に格納

	//	初期化
	$tagtext='';
	//	2色
	if(($i+1)% 2 ==0){
		$tagtext= ' id="two-b"';
	}else{
		$tagtext= ' id="two-r"';
	}
?>

		<tr<?= $tagtext ?>>
			<td id="view" align="right"><?= $itemCount ?></td>
			<td id="view" align="center"><?= $arr['goodsid'] ?></td>
			<td id="view" ><?= $arr['goodsname'] ?></td>
			<td id="view" ><?= $arr['makername'] ?></td>
			<td id="view" align="right">￥<?= number_format($arr['price']) ?></td>


				<td id="view" >
				<select  style="width:50" name= <?= 'stk'.$i ?>>
<?php
				//	ストック
				for($j = 1; $j <= $arr['stock']; $j++){
					if($arr['mystock']==$j){
						echo "<option value=$j selected>$j</option>";
					}else{
						echo "<option value=$j>$j</option>";
					}
				}
				$addstock+=$arr['mystock'];
				$totalprice=$arr['mystock']*$arr['price'];
				$allprice+=$totalprice;
?>
				</select>
				<input type="hidden" name= <?= 'gID'.$i ?> value=<?= $arr['goodsid'] ?>>
				</td>


			<td id="view" align="right">￥<?= number_format($totalprice); ?></td>


			<td id="view"><a href="cartView.php?gID=<?= $arr['goodsid'] ?>">買い物かごから削除する</a></td>
		</tr>
<?php
			$itemCount++;
}	//	end of for()
?>
		<tr id= "head" >
			<td id="view" colspan= "5" align="center"><b>注文合計</b></td>
			<td id="view" align="center"><?= $addstock ?>個</td>
			<td id="view" align="right">￥<?= number_format($allprice) ?></td>
			<td id="view"><br></td>
		</tr>
	</table>
	<br><br>

	<div align="center">

<?php
//	アイテムが何も無い時の処理
if($itemCount== 1){
	echo "<br><p><b>カゴには何も入っていません。</p>";
}
//	接続を切る
pg_close($db_link);

?>
		<input type="submit" value="数量の更新" style="width:120px" >
		<br>
		<br>
		<a href ="listGoods_cart.php">ショッピングに戻る</a>
	</div>
	</form>

</body>
</html>