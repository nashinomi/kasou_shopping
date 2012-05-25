<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>updateGoods 「カテゴリー商品更新フォーム1」</title>
	<link rel="stylesheet" type="text/css" href="../css/mystyle.css">
	<script type="text/javascript" src="../js/checkAlert.js"></script>
	<script type="text/javascript">
		//	アクション変更処理 - test -
		function preView(){
			if(document.addform.uploadimg.value){
				//	actionの値を変えられるか？
				document.addform.action= "updateGoods.php";
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
</head>

<?php

function postvalue($value){

}

//	フォーム更新処理用
$addForm;
$imgError='';

//	データ受け取り
if(!$gID= $_GET['gID']){
	if(!$gID= $_POST['gID']){
		$gID='';
	}else{

	}
}
if(!$cID= $_GET['cID']){
	if(!$cID= $_POST['cID']){
		$cID='';
	}
}

if(!$gName= $_POST['gName']){
	$gName='';
}
if(!$gunitPrice= $_POST['gunitPrice']){
	$gunitPrice='';
}
if(!$Price= $_POST['Price']){
	$Price='';
}
if(!$gNote= $_POST['gNote']){
	$gNote='';
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
<body onload="init('<?= $imgError ?>')" background="../img/a32.jpg">


	<h2>■商品マスターメンテナンス[ 更新処理 ]</h2>

	<br>
	<br>

		<form method="POST"  name= "addform" onSubmit="return checkForm_add()" enctype="multipart/form-data" action="GoodsMaintenance.php">

	<div align="center">
		<table class="formtable">
			<caption>商品更新フォーム</caption>

			<tr>
				<th id="addform">商品ID</th>
				<td id="addform"><?= $gID ?></td>
			</tr>
			<tr>
				<th id="addform">カテゴリー</th>
				<td id="addform">
					<select name="cID">
<?php

if(!$db_link= pg_connect("host=localhost user=advancement password=arkloop2 dbname=advancement")){
	die('接続できませんでした');
}
//	カテゴリーセレクト
if($result = pg_query($db_link, "select * from categories")){
$row = pg_num_rows($result); //検索結果の行数を取得
	for($i = 0; $i < $row; $i++){
		$arr = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//検索結果の一行分を配列に格納
		if($cID== $arr['categoryid']){
			echo "<option value=".$arr['categoryid']." selected>".$arr['categoryname']."</option>";
		}else{
			echo "<option value=".$arr['categoryid'].">".$arr['categoryname']."</option>";
		}
	}
}
$result = pg_query($db_link, "select * from goods");
$row = pg_num_rows($result); //検索結果の行数を取得
for($i = 0; $i < $row; $i++){
	$arr = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//検索結果の一行分を配列に格納
	if($arr['goodsid']==$gID){
		break;
	}
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<th id="addform">商品名</th>
				<td id="addform"><input type="text" name="gName" size="50"  value="<?= $gName!='' ?$gName:$arr['goodsname'] ?>"></td>
			</tr>

			<tr>
				<th id="addform">単価</th>
				<td id="addform"><input type="text" name="gunitPrice" size="20" value="<?= $gunitPrice!='' ?$gunitPrice:$arr['unitprice'] ?>"></td>
			</tr>

			<tr>
				<th id="addform">価格</th>
				<td id="addform">
					<input type="text" name="Price" size="20" value="<?= $Price!='' ?$Price:$arr['price'] ?>">　※入力がない場合は単価の1.5倍となります
				</td>
			</tr>

			<tr>
				<th id="addform">備考</th>
				<td id="addform"><input type="text" name="gNote" size="50" value="<?= $gNote!='' ?$gNote:$arr['goodsnotes'] ?>"></td>
			</tr>

			<tr>
				<th id="addform">数量</th>
				<td id="addform">
					<select name="gStock">
<?php
				//	ストック
				for($i = 1; $i <= 10; $i++){
					if($gStock!=$i){
						if($arr['stock']==$i && $gStock==''){
							echo '<option value="'.$i.'" selected>'.$i .'</option>';
						}else{
							echo '<option value="'.$i.'">'.$i .'</option>';
						}
					}else{
						echo '<option value="'.$i.'" selected>'.$i .'</option>';
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
//	メーカーセレクト
if($result = pg_query($db_link, "select * from makers")){
$row = pg_num_rows($result); //検索結果の行数を取得
	for($i = 0; $i < $row; $i++){
		$arr_m = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//検索結果の一行分を配列に格納
		if($arr_m['makerid']== $arr['makerid']){
			echo "<option value=".$arr_m['makerid']." selected>".$arr_m['makername']."</option>";
		}else{
			echo "<option value=".$arr_m['makerid'].">".$arr_m['makername']."</option>";
		}
	}
}
//	接続を切る
pg_close($db_link);
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


		<input type="submit" value="更新" style="width:60px" >
		<input type="reset" value="リセット" style="width:60px">

		<br><br>

		<input type="button" value="更新の取り消し" onclick="location.href='listGoods.php'" style="width:120px" >
		<input type="hidden" name="gID" value=<?= $gID ?>>
		<input type="hidden" name="flag" value="2">

	</div>
	</form>
</body>

</html>