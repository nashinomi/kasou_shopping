<?php
include '../Model/User.php';
session_start();

//	ユーザー情報を管理するクラス
$cUser= new User();

// カレントディレクトリの別のページにリダイレクトします
$host= $_SERVER['HTTP_HOST'];
$uri= rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$url= $_POST["url"];


//	ログイン判定
if(!$_SESSION['userid']){

	//	初期化のつもり
	unset($_SESSION['userid']);
	unset($_SESSION['sessionid']);

	$userid = $_POST["userid"];
	$pass= $_POST["pass"];

	//	ユーザー情報を取得する
	$sql= "SELECT * FROM userinfo";
	$cUser->resultSQL($sql);
	if($cUser->checkUser($userid, $pass)==true){
		//	セッションID保管
		$_SESSION['userid']= $userid;
		$_SESSION['username']= $cUser->get_userName($cUser->get_index());
		$_SESSION['sessionid']= session_id();
		session_save_path();
		$extra = "top.php";
	}else{
		$extra = "top.php?loginerror=1";
	}
}else{
	unset($_SESSION['userid']);
	unset($_SESSION['sessionid']);
	$extra = "top.php";
}
header("Location: http://$host$uri/$extra");
?>