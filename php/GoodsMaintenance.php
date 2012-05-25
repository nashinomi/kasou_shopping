<?php
//	正しく入力フォームからのアクセスかを判定
if($testID= $_POST['submit']){
	$testID=0;
	header("Location: http://$host$uri/listGoods.php");
	exit;
}
//	フォームデータを受け取る
$cID= $_POST['cID'];
$gID= $_POST['gID'];
$gName= $_POST['gName'];
$gunitPrice= $_POST['gunitPrice'];
//	入力判定
if(!$gPrice= $_POST['Price']){
	$gPrice= $gunitPrice*1.5;
}

$gStock= $_POST['gStock'];
$gNote= $_POST['gNote'];
$mID= $_POST['mID'];
$flag= $_POST['flag'];

//	画像パスとファイル名
$moveTo='';
$filename='';

//	とりあえずの初期化
$sql= '';
$get= '';

//	アドレスを保持
$host= $_SERVER['HTTP_HOST'];
$uri= rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

//	画像処理
if($_FILES['uploadimg']['name']){
	//	アップロードした画像を配置するパスを設定する
	$filePath='../img/goodsimg';
	$imgError='';

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
	}else if($_FILES['uploadimg']['size'][$i]> 102400){
		//	画像ファイルのサイズ上限をチェック
		$imgError.= 'ファイルサイズが大きすぎます100kb以下にしてください。<br />';
	}else if($_FILES['uploadimg']['size']== 0){
		//	画像ファイルのサイズ下限をチェック
		$imgError.= 'ファイルが存在しないか空のファイルです。';
	}else if($extension!= 'gif' && $extension!='jpg' && $extension!='png'){
		//	画像ファイルの拡張子チェック
		$imgError.= 'アップロード可能なファイルはgif,jpg,pngのみです';
	}else{
		//	ここでは格納ディレクトリの下に["upfile_"+現在のタイムスタンプ+連番+拡張子]で配置します
		$moveTo= $filePath.'/c'.$cID.'_'.time().$i.'.'.$extension;
			//	アップロードした一時ファイルを指定した場所へ移動します
		if(!move_uploaded_file($_FILES['uploadimg']['tmp_name'], $moveTo)){
			$imgError.= '画像のアップロードに失敗しました';
		}
	}
	if($imgError==''){
		//	ファイル名を求める
		$filename= $_FILES['uploadimg']['name'];
		$temp= mb_strpos($filename, '.');
		$filename= mb_substr($filename,0,$temp);
	}else{
		header("Location: http://$host$uri/GoodsError.php?img=1");
		exit;
	}
}

//		データベースに接続
if(!$db_link= pg_connect("host=localhost user=advancement password=arkloop2 dbname=advancement")){
	header("Location: http://$host$uri/php/GoodsError.php");
	exit;
}

if($flag==1){

	$sql= "SELECT * FROM goods";
	//	グッズID重複チェック
	if($result = pg_query($db_link, $sql)){
	$row = pg_num_rows($result); //検索結果の行数を取得
		for($i = 0; $i < $row; $i++){
			$arr = pg_fetch_array($result, $i, PGSQL_ASSOC); 		//検索結果の一行分を配列に格納
			if($arr['goodsid']==$gID){
				header("Location: http://$host$uri/GoodsError.php");
				exit;
			}
		}
	}

	//	レコード追加SQL
	$sql= sprintf("INSERT INTO goods(goodsid,categoryid,goodsname,unitprice,price,stock,goodsnotes,makerid,filepass,filename)
	VALUES('%s','%d','%s','%d','%d','%d','%s','%d','%s','%s')",
	pg_escape_string($gID),
	pg_escape_string($cID),
	pg_escape_string($gName),
	pg_escape_string($gunitPrice),
	pg_escape_string($gPrice),
	pg_escape_string($gStock),
	pg_escape_string($gNote),
	pg_escape_string($mID),
	pg_escape_string($moveTo),
	pg_escape_string($filename));		//	画像ファイル追加

	$result = pg_query($db_link, $sql);
	//	反転処理用ID
	$get= "?gID=$gID&cID=$cID";

}else if($flag==2){
	//	更新SQL
	$sql=sprintf("UPDATE goods SET categoryID= '%d', goodsName= '%s',unitprice='%d', price='%d', stock='%d',
	goodsNotes= '%s', makerID= '%d', filepass= '%s', filename= '%s' WHERE goodsID= '%s'",
	pg_escape_string($cID),
	pg_escape_string($gName),
	pg_escape_string($gunitPrice),
	pg_escape_string($gPrice),
	pg_escape_string($gStock),
	pg_escape_string($gNote),
	pg_escape_string($mID),
	pg_escape_string($moveTo),
	pg_escape_string($filename),
	pg_escape_string($gID));

	$result = pg_query($db_link, $sql);

	//	反転処理用ID
	$get= "?gID=$gID&cID=$cID";

}else if($flag==3){
	//	先に外部キーのあるファイルから消す(cart,usercart)
	$sql= "DELETE FROM usercart WHERE usercart.goodsID='$gID'";
	$result = pg_query($db_link, $sql);
	$sql= "DELETE FROM cart WHERE cart.goodsID='$gID'";
	$result = pg_query($db_link, $sql);
	//	最後に本体のグッズを消す
	$sql= "DELETE FROM goods WHERE goods.goodsID='$gID'";
	$result = pg_query($db_link, $sql);
	$get= "?gID=$gID";
}

//	接続を切る
pg_close($db_link);


// カレントディレクトリの別のページにリダイレクトします
$extra = 'listGoods.php';
$alert= "&aFlag=$flag";
header("Location: http://$host$uri/$extra$get$alert");
exit;

?>