<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>addGoods1 「カテゴリー商品追加フォーム」</title>
	<link rel="stylesheet" type="text/css" href="../css/mystyle.css">
	<script type="text/javascript" src="../js/checkAlert.js"></script>
	<script type="text/javascript">
		//	アクション変更処理 - test -
		function preView(){
			if(document.addform.uploadimg.value){
				//	actionの値を変えられるか？
				document.addform.action= "addGoods.php";
				document.addform.submit();
			}else{
				alert('画像ファイルが選択されていません。');
			}
		}
		//  && errormsg != undefined
		function init(errormsg){
			if(errormsg !='' && errormsg != "null"){
				alert(errormsg);
			}
		}
	</script>
<?php

//	フォーム更新処理用
$addForm;
$imgError='';

//	プレビュー処理
if(!$gID= $_POST['gID']){
	$gID='';
	$addForm['gid']= '<input type="text" name="gID">';
}else{
	$addForm['gid']= "<input type='text' name='gID' value='$gID'>";
}
if(!$cID= $_POST['cID']){
	$cID='';
}
if(!$gName= $_POST['gName']){
	$gName='';
	$addForm['gname']= "<input type='text' name='gName' size='50'>";
}else{
	$addForm['gname']= "<input type='text' name='gName' size='50' value=$gName>";
}
if(!$gunitPrice= $_POST['gunitPrice']){
	$gunitPrice='';
	$addForm['gunitprice']='<input type="text" name="gunitPrice" size="20">';
}else{
	$addForm['gunitprice']="<input type='text' name='gunitPrice' size='20' value=$gunitPrice>";
}
if(!$Price= $_POST['Price']){
	$Price='';
	$addForm['price']='<input type="text" name="Price" size="20">';
}else{
	$addForm['price']="<input type='text' name='Price' size='20' value=$Price>";
}
if(!$gNote= $_POST['gNote']){
	$gNote='';
	$addForm['gnote']='<input type="text" name="gNote" size="50">';
}else{
	$addForm['gnote']="<input type='text' name='gNote' size='50' value='$gNote'>";
}
if(!$gStock= $_POST['gStock']){
	$gStock='';
}
if(!$mID= $_POST['mID']){
	$mID='';
}

//	画像ファイル保持処理
if($_FILES['uploadimg']){
	//	アップロードした画像を配置するパスを設定する
	$filePath='upload';

	//	画像ファイルの拡張子を取得して判定
	$imgType= $_FILES['uploadimg']['type'];
	$extension='';
	if($imgType== 'image/gif'){
	$extension='gif';
	}else if($imgType== 'image/png'||$imgType=='image/x-png'){
		$extension='png';
	}else if($imgType== 'image/jpeg'||$imgType=='image/pjpeg'){
		$extension='jpg';
	}else if($extension==''){
		$imgError= '許可されていない拡張子です';
	}
		/*
		 * 勝手にpjepgからjpegに変えられてる？
			else if($imgType!= $checkImage['mime']){
			echo '$imgType： '.$imgType.'<br />';
			echo '$checkImage[mime]： '.$checkImage['mime'].'<br />';
			$error.= '拡張子が異なります<br />';
		}
		*/
	//	getimagesize()関数で画像かどうかの判定
	$checkImage= @getimagesize($_FILES['uploadimg']['tmp_name']);
	if($checkImage==FALSE){
		$imgError.='画像ファイルをアップロードしてください';
	}else if($_FILES['uploadimg']['size']> 102400){
		//	画像ファイルのサイズ上限をチェック
		$imgError.= 'ファイルサイズが大きすぎます100kb以下にしてください。';
	}else if($_FILES['uploadimg']['size']== 0){
		//	画像ファイルのサイズ下限をチェック
		$imgError.= 'ファイルが存在しないか空のファイルです。';
	}else if($extension!= 'gif' && $extension!='jpg' && $extension!='png'){
		//	画像ファイルの拡張子チェック
		$imgError.= 'アップロード可能なファイルはgif,jpg,pngのみです';
	}else{
		//	ここでは格納ディレクトリの下に["upfile_"+現在のタイムスタンプ+連番+拡張子]で配置します
		$moveTo= $filePath.'/upfile_'.time().$i.'.'.$extension;

		//	アップロードした一時ファイルを指定した場所へ移動します
		if(!move_uploaded_file($_FILES['uploadimg']['tmp_name'], $moveTo)){
			$imgError.= '画像のアップロードに失敗しました';
		}
	}

		if($imgError==''){
			//	画像ファイルを表示します
			$imgPass= $moveTo.'<br />';
			$filepass=  $_FILES['uploadimg']['name'];
			$imgView= '<tr><th id="addform">プレビュー</th><td id="addform" align="center"><img src="'.$moveTo.'" alt= "" /></td></tr>';
		}
}

?>

</head>
<body onLoad="init('<?= $imgError; ?>')" background="../img/a32.jpg">

	<h2>■商品マスターメンテナンス[ 追加処理 ]</h2>
	<br>
	<br>

	<form method="POST"  name= "addform" onSubmit="return checkForm_add()" enctype="multipart/form-data" action="GoodsMaintenance.php">
	<div align="center">
		<table class="formtable">
			<caption>商品追加フォーム</caption>

			<tr>
				<th id="addform">商品ID</th>
				<td id="addform">
					<?= $addForm['gid'] ?>　※XXX-XXXXX形式(Xは、数値)
				</td>
			</tr>
			<tr>
				<th id="addform">カテゴリー</th>
				<td id="addform">
					<select name="cID">
<?php

if(!$db_link= pg_connect("host=localhost user=advancement password=arkloop2 dbname=advancement")){
	die('接続できませんでした');
}
//	一覧表示テスト
if($result = pg_query($db_link, "select * from categories")){
$row = pg_num_rows($result); //検索結果の行数を取得
	for($i = 0; $i < $row; $i++){
		$arr = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//検索結果の一行分を配列に格納
		if($cID==$arr['categoryid']){
			echo "<option value=".$arr['categoryid']." selected>".$arr['categoryname']."</option>";
		}else{
			echo "<option value=".$arr['categoryid'].">".$arr['categoryname']."</option>";
		}
	}
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<th id="addform">商品名</th>
				<td id="addform"><?= $addForm['gname'] ?></td>
			</tr>

			<tr>
				<th id="addform">単価</th>
				<td id="addform"><?= $addForm['gunitprice'] ?></td>
			</tr>

			<tr>
				<th id="addform">販売価格</th>
				<td id="addform">
					<?= $addForm['price'] ?>　※入力がない場合は単価の1.5倍となります
				</td>
			</tr>

			<tr>
				<th id="addform">備考</th>
				<td id="addform"><?= $addForm['gnote'] ?></td>
			</tr>

			<tr>
				<th id="addform">数量</th>
				<td id="addform">
					<select name="gStock">
<?php
				//	ストック
				for($i = 1; $i <= 10; $i++){
					if($gStock==$i){
						echo '<option value="'.$i.'" selected>'.$i .'</option>';
					}else{
						echo '<option value="'.$i.'">'.$i .'</option>';
					}
				}
?>
					</select>
				</td>
			</tr>
			<tr>

				<th id="addform">メーカー</th>
				<td id="addform">
					<select name="mID">

<?php
//	一覧表示テスト
if($result = pg_query($db_link, "select * from makers")){
$row = pg_num_rows($result); //検索結果の行数を取得
	for($i = 0; $i < $row; $i++){
		$arr = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//検索結果の一行分を配列に格納
		if($mID==$arr['makerid']){
			echo "<option value=".$arr['makerid']." selected>".$arr['makername']."</option>";
		}else{
			echo "<option value=".$arr['makerid'].">".$arr['makername']."</option>";
		}
	}
}
?>

					</select>
				</td>
			</tr>
			<?php
				//	画像が読み込まれたときに呼び出す
				if($imgView){
					echo $imgView;
				}
			?>
			<tr>
				<th id="addform">画像ファイル</th>
				<td id="addform">
					<input type="file" name="uploadimg"  value="" />
					<input type="button"  onClick="preView()" value="確認" /><br />
					<p>※100kb未満の画像ファイル以外はアップできません。(jpg,png,gif)</p>
					※ 確認後は選択が解けるのでもう一度指定してください。
				</td>
			</tr>
		</table>

		<br>
		<p>上記の商品を追加します。よろしいですか？</p>


		<input type="submit" value="追加" style="width:60px" >
		<input type="reset" value="リセット" style="width:60px">

		<br><br>

		<input type="button" value="追加の取り消し" onclick="location.href='listGoods.php'" style="width:120px" >
		</div>
		<input type="hidden" name="flag" value="1">
		</form>

<?php
//	接続を切る
pg_close($db_link);
?>
</body>
</html>