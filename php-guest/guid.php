<?php
include '../Model/Category.php';
include '../Model/Goods.php';
include '../Model/User.php';
include '../Model/Cart.php';
include './Siteparts/side.php';
include './Siteparts/content.php';
include './Siteparts/header.php';
session_start();
//	グッズテーブルに接続
//unset($_SESSION['userid']);
//	gIDを取得する
if(!$goodsid= $_GET['goodsid']){
	if(!$goodsid= $_POST['goodsid']){
		$goodsid='';
	}
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
	<title>仮想ショッピング.web-ヘルプサイト</title>
	<link rel="stylesheet" type="text/css" href="../css/styleview.css">
	<script type="text/javascript" src="../js/checkAlert.js"></script>
</head>
<body>

<h2>■お客様ページ[ <a href= "../index.html">練習メニューに戻る</a> ]</h2>
	<div id="container">
		<div id="header">
			<h1 id="logoTitle">
				<img src="../img/logo01.png" alt="logo" /><br />
				ショッピング.web - AdvancementPrograming's -
			</h1>
			<?php retrievalForm_head(); 	?>
			<?php headerBar_head();			?>
		</div>

		<div id="main">
			<div id="content">
				<h2>工事中</h2>
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
				  width: 180,
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