<?php
/*
 * 		インクルード
 */
include '../Model/Category.php';
include '../Model/Goods.php';
include '../Model/User.php';
include '../Model/Cart.php';
include './Siteparts/side.php';
include './Siteparts/content.php';
include './Siteparts/header.php';

//	セッションの開始
session_start();


//	gIDを取得する
if(!$key= $_GET['retrievalKey']){
	if(!$key= $_POST['retrievalKey']){
		$key='';
	}
}

//	gIDを取得する
if(!$cID= $_GET['cID']){
	if(!$cID= $_POST['cID']){
		$cID='';
	}
}
//	Order変数を受け取る(並び替え)
if(!$Order= $_POST['Order']){
	if(!$Order=$_GET['Order']){
		$Order= 0;
	}
}
//	リスト表示限界数値
if(!$itemCount=$_GET['itemCount']){
	$itemCount=0;
}

/*
 * 使用するクラスの生成
 */
$cGoods= new Goods();
$cUser= new User();
$cCart= new Cart();
$cCategory= new Category();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>商品一覧</title>
	<link rel="stylesheet" type="text/css" href="../css/styleview.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1 id="logoTitle">
				<a href="top.php"><img src="../img/logo01.png" alt="logo" /></a><br />
				ショッピング.web - AdvancementPrograming's -
			</h1>
			<?php retrievalForm_head(); 	?>
			<?php headerBar_head();			?>
		</div>

		<div id="main">
			<div id="content">
				<form name="sortform" method="get" id="goodssort" action= "listGoods.php">
				<?php

					$sql= "SELECT * FROM categories";
					$cCategory->resultSQL($sql);
					categorysort($cCategory->get_cTable(), $cID);
					$sqlOrder= goodssort($Order);

					//	オーダーSQL
					$sql= "SELECT * FROM goods, categories, makers "
						 ." WHERE goods.makerID = makers.makerID and "
						 ." goods.categoryID = categories.categoryID ";
						 if($cID){
						 	$sql.=" and goods.categoryID = '".$cID."'";
						 }
						 $sql.=$sqlOrder;
					$cGoods->resultSQL($sql);
				?>
					<input type="submit"  value="変更" size="120px" >
					<input type="hidden"  name="retrievalKey" value="<?= $key ?>">
				</form>

				<div id="goodsList">
					<h2>商品一覧リスト</h2>
					<?php
						//	商品名検索表示と通常表示処理
						if($key==''){
							goodsListview($cGoods->get_gTable(), $itemCount, $cID, $Order);
						}else{
							goodsListview($cGoods->Retrieval($key), $itemCount, $cID, $Order);
						}

						//	アイテムが何も無い時の処理
						if($cGoods->get_row_g()==0){
							echo "<br>このカテゴリーに登録された商品は、ありません。";
						}
					?>
				</div>
			</div>


			<div id="leftside" >
				<div id="loginForm">
					<h3>USERMENU</h3>
					<?php
						$cUser->userSet();
						$sql= $cUser->createUsercart_SQL();
						$cCart->resultSQL($sql);
						loginForm_side($cCart->get_cartTable(), $cCart->get_row_ca(), $cUser->get_userTable(), $cCart->gettotalPrice());
					?>
				</div>
					<div class="sideLink">
					<h3>カテゴリー</h3>
				<?php
					$cCategory= new Category();
					$sql= "SELECT * FROM categories";
					$cCategory->resultSQL($sql);
					categoryView_list($cCategory->get_cTable());
				?>
				</div>
			</div>
		</div>
		<div id="rightside">
			<?php retrievalForm_head(); ?>
			<!-- Twitter -->
			<script src="http://widgets.twimg.com/j/2/widget.js"></script>
			<script>
					new TWTR.Widget({
					  version: 2,
					  type: 'profile',
					  rpp: 4,
					  interval: 6000,
					  width: 170,
					  height: 300,
					  theme: {
					    shell: {
					      background: '#333333',
					      color: '#ffffff'
					    },
					    tweets: {
					      background: '#000000',
					      color: '#ffffff',
					      links: '#4aed05'
					    }
					  },
					  features: {
					    scrollbar: false,
					    loop: false,
					    live: false,
					    hashtags: true,
					    timestamp: true,
					    avatars: false,
					    behavior: 'all'
					  }
					}).render().setUser('Na2486').start();
				</script>
		</div>


		<div id="footer">
			<p>advancementprogram</p>
		</div>

	</div>
</body>
</html>