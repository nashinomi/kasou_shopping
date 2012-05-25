<?php

include '../Model/Category.php';
include '../Model/User.php';
include '../Model/Cart.php';
include './Siteparts/side.php';
include './Siteparts/content.php';
include './Siteparts/header.php';

session_start();

//	useridを取得する
if(!$userid= $_GET['userid']){
	if(!$userid= $_POST['userid']){
		$userid='';
	}
}

//	usernameを取得する
if(!$username= $_GET['username']){
	if(!$username= $_POST['username']){
		$username='';
	}
}

$cUser= new User();
$mess= "<span>使用可能か調べる</span>";

//	useridが存在する時だけ判定を行う
if($userid){
	$sql= "SELECT * FROM loginuser";
	$cUser->resultSQL($sql);
	if($cUser->checkUserid($userid)){
		$mess= "<span class='not_userid'>*このIDは使用できません</span>";
	}else{
		$mess= "<span class='ok_userid'>*このIDは使用できます</span>";
	}
}

/*
 * 使用するクラスの生成
 */
$cUser= new User();
$cCart= new Cart();
$cCategory= new Category();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>ユーザー登録</title>
	<link rel="stylesheet" type="text/css" href="../css/styleview.css">
	<script type="text/javascript" src="../js/checkAlert.js"></script>
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
				<div id="Memberform">
					<h2>登録フォーム</h2>
					<?php echo $errmess; ?>
					<form method="post" name="menberform" onSubmit="" action="../Controller/GoodsMaintenanceMVC.php">
					<input type="hidden" name="controll" value="7" />
					<p>*印の項目は必ず入力してください。</p>
					<p>IDとPassWordは半角英数字のみで入力してください。</p>
					<table>
					<tr>
						<th>お名前*</th>
						<td><input type="text" name="username"  value="<?= $username?>"/></td>
					</tr>
					<tr>
						<th>ID*<span> (重複不可)</span></th>
						<td><input type="text" name="userid"  value="<?= $userid?>"/>
						<?php echo $mess; ?>
						<input type="button" onClick="checkuserid_p()" value="チェック""/>
						</td>
					</tr>
					<tr>
						<th>PassWord*</th>
						<td><input type="password" name="pass"  value="<?= $pass ?>" /></td>
					</tr>
					<tr>
						<th></th>
						<td></td>
					</tr>
					</table>
					<div class="formbutton">
						<input type="submit" value="同意して登録" />
						<input type="button"  onClick="" value="キャンセル" />
					</div>
					<div class="formbutton">
						<input type="reset" value="リセット" />
					</div>
					</form>
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

		<div id="footer">
			<p>advancementprogram</p>
		</div>
	</div>
</body>
</html>