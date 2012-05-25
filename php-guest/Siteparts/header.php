<?php
/*
 * 		ヘッド部分に表示されるパーツ処理まとめ
 */

//	商品名検索フォーム
function retrievalForm_head(){
	echo '<form method="post"  name="retrival" onSubmit="return checkRetrival()" action="listGoods.php"  id="retrievalForm">';
	echo '<span>商品名検索</span>';
	echo '<input type="text" id="text" name="retrievalKey" />';
	echo '<input type="submit" id="submit" value="検索" />';
	echo '</form>';
}

//	トップバー
function headerBar_head(){
	echo '<table class="headLink">';
	echo '<tr>';
	echo '<td><a href="top.php">トップ</a></td>';
	echo '<td><a href="listGoods.php">商品一覧</a></td>';
	echo '<td><a href="cartView.php">カゴの中を見る</a></td>';
	echo '<td><a href="adduser.php">新規登録</a></td>';
	echo '<td><a href="guid.php">ご利用案内</a></td>';
	echo '</tr>';
	echo '</table>';
}
?>