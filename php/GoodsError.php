<html>
<head>
	<title>エラーメッセージ</title>
	<style type="text/css">
		h2{background:#00FF7F; color:white;}
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body background="../img/a32.jpg" >
<?php
$gID= $_GET['gID'];

?>
<h2>■商品マスターメンテナンス ！追加エラー！</h2>
<div align="center">
<p>商品マスターへの追加処理でエラーが発生しました。<br>
指定された商品ID「<?= $gID ?>」はすでに、登録されている可能性があります。</p>
<a href= "addGoods.php">「商品追加フォーム」へ戻る</a><br>
<p>「商品追加フォーム」の入力データを保持したい場合は、ブラウザーの戻るをクリックしてください。</p>

</div>
<br>
<br>
</body>
</html>