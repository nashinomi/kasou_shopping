<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>listGoods_cart 「カテゴリー商品一覧 - カゴシステム -」</title>
	<link rel="stylesheet" type="text/css" href="../css/mystyle.css">
</head>
<body background="../img/a32.jpg">

	<h2><a name="top"></a>■ショッピングモール「商品一覧」[ <a href= "../index.html">練習メニューに戻る</a> ]</h2>

<?php

if(!$db_link= pg_connect("host=localhost user=advancement password=arkloop2 dbname=advancement")){
	die('接続できませんでした');
}

//	cIDを取得する
if(!$cID= $_GET['cID']){
	if(!$cID= $_POST['cID']){
		$cID=0;
	}
}
//	gIDを取得する
if(!$gID= $_GET['gID']){
	if(!$gID= $_POST['gID']){
		$gID='';
	}
}


//	Order変数を受け取る(並び替え)---------------

//	pOrder判定
if(!$pOrder= $_GET['pOrder']){
	if(!$pOrder= $_POST['pOrder']){
		$pOrder= 0;
	}
}

if($pOrder!=0){
	$Ordername= 'pOrder';
	$Ordervalue= $pOrder;
}


//	降順昇順処理
if($pOrder==0){
	$linkOrder_p= "<a href='listGoods_cart.php?cID=$cID&pOrder=2'>▲ </a>";
	$linkOrder_p.= "<a href='listGoods_cart.php?cID=$cID&pOrder=1'>▼</a>";
}else if($pOrder== 1){
	$sqlOrder= " ORDER BY goods.price ";
	$linkOrder_p= "<a href='listGoods_cart.php?cID=$cID&pOrder=2'>▲</a>";
}else{
	$sqlOrder= " ORDER BY goods.price DESC ";
	$linkOrder_p= "<a href='listGoods_cart.php?cID=$cID&pOrder=1'>▼</a>";
}

//	mOrder判定
if(!$mOrder= $_GET['mOrder']){
	if(!$mOrder= $_POST['mOrder']){
		$mOrder= 0;
	}
}

if($mOrder!=0){
	$Ordername= 'mOrder';
	$Ordervalue= $mOrder;
}

//	降順昇順処理
if($mOrder==0){
	$linkOrder_m= "<a href='listGoods_cart.php?cID=$cID&mOrder=2'>▲ </a>";
	$linkOrder_m.= "<a href='listGoods_cart.php?cID=$cID&mOrder=1'>▼</a>";
}else if($mOrder== 1){
	$sqlOrder= " ORDER BY goods.makerid ";
	$linkOrder_m= "<a href='listGoods_cart.php?cID=$cID&mOrder=2'>▲</a>";
}else{
	$sqlOrder= " ORDER BY goods.makerid DESC ";
	$linkOrder_m= "<a href='listGoods_cart.php?cID=$cID&mOrder=1'>▼</a>";
}

//	gOrder判定
if(!$gOrder= $_GET['gOrder']){
	if(!$gOrder= $_POST['gOrder']){
		$gOrder= 0;
	}
	if($pOrder==0 && $mOrder==0 && $gOrder==0){	$gOrder=1;	}
}

if($gOrder!=0){
	$Ordername= 'gOrder';
	$Ordervalue= $gOrder;
}

//	降順昇順処理
if($gOrder==0){
	$linkOrder_g= "<a href='listGoods_cart.php?cID=$cID&gOrder=2'>▲</a>";
	$linkOrder_g.= "<a href='listGoods_cart.php?cID=$cID&gOrder=1'>▼</a>";
}else if($gOrder== 1){
	$sqlOrder= " ORDER BY goods.goodsID ";
	$linkOrder_g= "<a href='listGoods_cart.php?cID=$cID&gOrder=2'>▲</a>";
}else{
	$sqlOrder= " ORDER BY goods.goodsID DESC ";
	$linkOrder_g= "<a href='listGoods_cart.php?cID=$cID&gOrder=1'>▼</a>";
}

?>
	<hr>

	<form name="listform" method="post" action= "listGoods_cart.php">
		<select name="cID">
			<option value="0">すべて</option>


<?php

//	カテゴリーセレクト
if($result = pg_query($db_link, "select * from categories")){
	$row = pg_num_rows($result); //検索結果の行数を取得
	for($i = 0; $i < $row; $i++){
		$arr = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//検索結果の一行分を配列に格納
		if($cID== $arr['categoryid']){
			echo "<option value=".$arr['categoryid']." selected>".$arr['categoryname']."</option>";
		}else{
			echo "<option value=".$arr['categoryid'].">".$arr['categoryname']."</option>";
		}
	}
}

?>
		</select>
		<input type="submit" value="カテゴリーの変更" size="120px" >
		<input type="hidden" name= <?= $Ordername ?> value=<?= $Ordervalue ?>>
		<div align="right">
			<input type="button" value="カートの中身を見る" onclick="location.href='cartView.php'" style="width:120px" >
		</div>
	</form>

	<hr>

	<table class="viewtable">
		<tr id="head">
			<th id="view" width="3%"><br></th>
			<th id="view" width="10%">商品ID <?= $linkOrder_g ?></th>
			<th id="view" width="25%">商品名</th>
			<th id="view" width="7%">価格 <?= $linkOrder_p ?></th>
			<th id="view" width="15%">備考</th>
			<th id="view" width="5%">残数</th>
			<th id="view" width="20%">メーカー名 <?= $linkOrder_m ?></th>
			<th id="view" width="15%"></th>
		</tr>
<?php

//	テーブルを結合して昇順に並べたデータのSQL
//	動的なSQL構文指定
$sql= " SELECT * FROM goods, makers "
	 ." WHERE goods.makerID = makers.makerID ";
	 if($cID!= 0){
	 	$sql.= " and goods.categoryid =".$cID;
	 }
	 $sql.= $sqlOrder;


//	一覧表示テスト
if($result = pg_query($db_link, $sql)){
	$row = pg_num_rows($result);								 //検索結果の行数を取得
	$itemCount=1;
	for($i = 0; $i < $row; $i++){
		$arr = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//検索結果の一行分を配列に格納

		//	初期化
		$tagtext='';
				//	2色
		if(($i+1)% 2 ==0){
			$tagtext= 'two-b';
		}else{
			$tagtext= 'two-r';
		}

?>
		<tr class="<?= $tagtext ?>">
			<td id="view" align="right"><?= $itemCount ?></td>
			<td id="view" align="center"><?= $arr['goodsid'] ?></td>
			<td id="view" ><a href="goodsView.php?gID=<?= $arr['goodsid'] ?>"><?= $arr['goodsname'] ?></a></td>
			<td id="view" align="right">￥<?= number_format($arr['price']) ?></td>
			<td id="view"><?= $arr['goodsnotes'] ?></td>
			<td id="view"><?= $arr['stock'] ?>個</td>
			<td id="view" ><?= $arr['makername'] ?></td>
			<td id="view"><a href="addCart.php?gID=<?= $arr['goodsid'] ?>">買い物かごに入れる</a></td>
		</tr>
<?php

			//	10行区切りでページトップへのリンクを表示する
			if(($itemCount)% 10==0){
				echo "<tr id='toplink'><td colspan='6'><a href='#top'>ページトップへスクロール</a></td></tr>";
			}

			$itemCount++;
	}	//	end of for()
}	//	end of if()
?>
	</table>

<?php

//	アイテムが何も無い時の処理
if($itemCount== 1){
	echo "<br>このカテゴリーに登録された商品は、ありません。";
}
//	接続を切る
pg_close($db_link);

?>

</body>
</html>