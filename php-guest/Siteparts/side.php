<?php
/*
 * 		サイドバーに表示される処理まとめ
 */

//	ログインフォーム
function loginForm_side($ca_table, $stock, $u_table, $allprice){
	echo '<form method="post" action="session.php" id="loginForm">';
		if($u_table[0]['usertable']=='usercart'){
			echo '<p class="infomess">ようこそ　<strong>'.$u_table[0]['username'].'</strong>　さん</p>';
			echo '<p>カゴの商品：'.$stock.'点</p>';
			echo '<p>合計金額：'.number_format($allprice).'円</p>';
			echo '<input type="submit" value="Logout">';
		}else{
			echo 'ID：<input type="text" id="text" name="userid" />';
			echo 'PassWord：<input type="password" id="text" name="pass"  />';
			echo '<input type="submit" value="Login" id="submit" />';
		}
	echo "<input type='hidden' name='url' value= ".$_SERVER['PHP_SELF']." />";
	echo '</form>';
}

//	カテゴリー一覧リスト
function categoryView_list($c_table){
	$stylearr= array('kitchen_side', 'living_side', 'bed_side', 'bas_side', 'entrance_side', 'syu_side', 'zakka_side', 'dou_side', 'car_side', 'gakki_side');
	for($i=0; $i< count($c_table); ++$i){
		echo "<li class='".$stylearr[$i]."'><a href='listGoods.php?cID=".$c_table[$i]['categoryid']."'>
		<abbr>　　　　　<span class='delimiter'>(</span><span class='remark'>".$c_table[$i]['categoryname'].'</span></a></li>';
	}
}
?>