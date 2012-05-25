<?php
	//	cIDを取得する
	if(!$cID= $_GET['cID']){
		if(!$cID= $_POST['cID']){
			$cID=0;
		}
	}
	if(!$gID= $_GET['gID']){
		if(!$gID= $_POST['gID']){
			$gID='';
		}
	}
	if(!$db_link= pg_connect("host=localhost user=advancement password=arkloop2 dbname=advancement")){
		die('接続できませんでした');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>listGoods 「カテゴリー商品一覧」</title>

	<link rel="stylesheet" type="text/css" href="../css/mystyle.css">
	<script type="text/javascript" src="../js/checkAlert.js"></script>

</head>
<body background="../img/a32.jpg">

	<h2><a name="top"></a>■カテゴリー別商品一覧[ <a href= "../index.html">練習メニューに戻る</a> ]</h2>
	<script>
		//	javascriptで値を取得する
		if(retrieveGETqs()){
			//	gIDをアラート表示
			alret_gID();
		}
	</script>

	<form name="listform" method="post" action= "listGoods.php">

	<table class="headtable">
		<tr>
			<td id="form">
				<select name="cID">
					<option value="0">すべて</option>

<?php

//	一覧表示テスト
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
			</td>

			<td id= "form">
				<input type= "button"  onClick="location.href='addGoods.php'" value="新しい商品の追加" size="120px">
			</td>


		</tr>

		<tr>
			<td id="text">
				カテゴリーを選択後、ボタンをクリックしてください。
			</td>
			<td id="text">
				新しい商品を追加する場合は、ボタンをクリックしてください。
			</td>
		</tr>
	</table>

	</form>

	<table class="viewtable">
		<tr id="head">
			<th id="view" width="3%"><br></th><th id="view" width="10%">商品ID</th>
			<th id="view" width="25%">商品名</th><th id="view" width="7%">価格<br>(単価)</th>
			<th id="view" width="20%">備考</th><th id="view" width="20%">メーカー名</th>
			<th id="view" width="7%">更新</th><th id="view" width="7%">削除</th>
		</tr>
<?php


//	テーブルを結合して昇順に並べたデータのSQL
//	動的なSQL構文指定
$sql= " SELECT * FROM goods, makers "
	 ." WHERE goods.makerID = makers.makerID ";
	 if($cID!= 0){
	 	$sql.= " and goods.categoryid =".$cID;
	 }
	 $sql.= " ORDER BY goods.goodsID ";


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

		//	反転処理の判定
		if($gID==$arr['goodsid']){
			$tagtext= 'addview';
		}
?>
		<tr class="<?= $tagtext ?>">
			<td id="view" align="right"><?= $itemCount ?></td>
			<td id="view" align="center"><?= $arr['goodsid'] ?></td>
			<td id="view" ><?= $arr['goodsname'] ?></td>
			<td id="view" align="right">￥<?= number_format($arr['price']) ?><br>
			(￥<?= number_format($arr['unitprice']) ?>)</td>
			<td id="view"><?= $arr['goodsnotes'] ?></td>
			<td id="view" ><?= $arr['makername'] ?></td>
			<td id="view"><a href="updateGoods.php?gID=<?= $arr['goodsid'] ?>&cID=<?= $arr['categoryid'] ?>">更新</a></td>
			<td id="view"><a href="deleteGoods.php?gID=<?= $arr['goodsid'] ?>&cID=<?= $arr['categoryid'] ?>">削除</a></td>
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