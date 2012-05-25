<?php
/*
 * 	データベースの更新処理をまとめて振り分けるコントローラー
 */

/*
 * 		インクルード
 */
include '../Model/Category.php';
include '../Model/Goods.php';
include '../Model/Cart.php';
include '../Model/User.php';

// カレントディレクトリの別のページにリダイレクトします
$host= $_SERVER['HTTP_HOST'];
$uri= rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$url= $_POST["url"];

session_start();

//	制御フラグ
if(!$controll=$_POST['controll']){
	if(!$controll=$_GET['controll']){
		$controll= 99;
	}
}

if(!$goodsid=$_POST['goodsid']){
	if(!$goodsid=$_GET['goodsid']){
		$goodsid= '';
	}
}

/*
 * 	ユーザー登録value's
 */
if(!$username=$_POST['username']){
	if(!$username=$_GET['username']){
		$username= '';
	}
}
if(!$userid=$_POST['userid']){
	if(!$userid=$_GET['userid']){
		$userid= '';
	}
}
if(!$pass=$_POST['pass']){
	if(!$pass=$_GET['pass']){
		$pass= '';
	}
}


//	ログインしているかの判定
if(!$_SESSION['userid']){
	$user['login']= false;
	$user['id']= session_id();
}else{
	$user['login']= true;
	$user['id']= $_SESSION['userid'];
}

/*
 * 	Cart数量取得
 */
$count=0;

while(true){
	$cart;
	$temp= 'goodsid'.$count;
	//	値が入っていなければループをでる
	if(!$cart[$count]['goodsid']=$_POST[$temp]){
		break;
	}
	$temp= 'stock'.$count;
	$cart[$count]['amount']=$_POST[$temp];
	$count++;
}

/*
 * 	Controller
 */

try{
	switch($controll){
		case 4: $cCart= new Cart();
			$cCart->insertCartgoods($goodsid, $user);
			$extra='../php-guest/cartView.php';
			break;
		case 5: $cCart= new Cart();
			$cCart->updateCartgoods($cart, $user);
			$extra='../php-guest/cartView.php';
			break;
		case 6: $cCart= new Cart();
			$cCart->deleteCartgoods($goodsid, $user);
			$extra='../php-guest/cartView.php';
			break;
		case 7: $cUser= new User();
			if($cUser->checkUserid($userid)){
				$extra='../php-guest/adduser.php?error=1';
			}else{
				$cUser->insertUser($userid, $username, $pass);
				$_SESSION['userid']= $userid;						//	ログイン
				$_SESSION['username']= $username;
				$extra='../php-guest/top.php';
			}
			break;

		default: break;
	}
	header("Location: http://$host$uri/$extra");

}catch(Exception $e){

}


?>