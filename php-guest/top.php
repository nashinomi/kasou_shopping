<?php
include '../Model/Category.php';
include '../Model/Goods.php';
include '../Model/User.php';
include '../Model/Cart.php';
include './Siteparts/side.php';
include './Siteparts/content.php';
include './Siteparts/header.php';
session_start();
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
	<title>仮想ショッピング.web</title>
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
				<h2>!!! New !!!</h2>
				<div id="goodsList">
					<table>
						<?php
							//	追加された商品を表示させる
							$sql= "SELECT * FROM goods, categories, makers "
								 ." WHERE goods.makerID = makers.makerID and "
								 ." goods.categoryID = categories.categoryID "
								 ." ORDER BY goods.createdate DESC";
							$cGoods->resultSQL($sql);
							for($i=0; $i< 5; ++$i){
						?>
						<tr>
							<td><?= $i+1 ?>.</td>
							<td>ID：<?= $cGoods->get_gid($i) ?></td>
						</tr>
						<tr>
							<td id="tableCreatematch_bottom" rowspan="3" align="center">
								<a href="goodsView.php?$oodsid=<?= $cGoods->get_gid($i) ?>"><img src="<?= $cGoods->get_gFpass($i) ?>"></a>
							</td>
							<td>
								 商品名：<a href="goodsView.php?goodsid=<?= $cGoods->get_gid($i) ?>"><?= $cGoods->get_gName($i) ?></a>
							</td>
						</tr>
						<tr>
							<td>メーカー： <?= $cGoods->get_gMname($i) ?></td>
						</tr>
						<tr>
							<td id="tableCreatematch_bottom">
								値段： <?= number_format($cGoods->get_gprice($i)) ?>円
							</td>
						</tr>
		<?php
		//	カートボタン設置問題..。どうするか？
		/*<input type="button" onClick="location.href='./addCart.php?gID=<?= $cGoods->get_gid($i) ?>'" value="カートに入れる" />*/
		}
		?>
					</table>
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
				}).render().setUser('nashinomi_test').start();
			</script>
		</div>

		<div id="footer">
			<p>advancementprogram</p>
		</div>
	</div>
</body>
</html>