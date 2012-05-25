<html>
<head>
	<title>クリエイトテーブル</title>
	<style type="text/css">
		h2{background:#00FF7F; color:white;}
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body background="../img/a32.jpg" >
<?php
//	データベース接続 -Land-
if(!$db_link= pg_connect("host=localhost user=advancement password=arkloop2 dbname=advancement")){
	die('接続できませんでした');
}
echo "接続完了<br />";
$sql='';

//	テーブルを削除ってみる
/*
if(!pg_query($db_link, "drop table makers")){
	echo "無理<br />";
}
*/

/*	//	1_makers
$sql="create table makers (
 makerID INT not null,
 makerName VARCHAR(60) not null,
 makerPhone VARCHAR(80),
 makerAddress VARCHAR(40),
 makerNotes VARCHAR(200),
 delFlg CHAR(1) DEFAULT 0,
 updateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(makerID)
)";
*/


/*//	2_categories
$sql= "create table categories (
 categoryID INT not null,
 categoryName VARCHAR(20) not null,
 categoryNotes VARCHAR(100),
 delFlg CHAR(1) DEFAULT 0,
 updateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(categoryID)
)";
*/

/*//	3_goods
$sql= "create table goods (
 goodsID VARCHAR(9) not null,
 categoryID INT  references categories(categoryID),
 goodsName VARCHAR(40) not null,
 unitPrice INT not null,
 price INT not null,
 stock INT not null,
 goodsNotes VARCHAR(100),
 makerID INT  references makers(makerID),
 filePass VARCHAR(200),
 fileName VARCHAR(40),
 delFlg CHAR(1) DEFAULT 0,
 updateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(goodsID)
)";
*/

/*//	4_cart
$sql="create table cart (
 sessionID VARCHAR(64) not null,
 goodsID VARCHAR(18) not null references goods(goodsID),
 amount INT not null,
 delFlg CHAR(1) DEFAULT 0,
 updateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(sessionID, goodsID)
)";
*/

/*//	6_usercart
$sql= "create table usercart (
 userID VARCHAR(32) not null,
 goodsID VARCHAR(18) not null references goods(goodsID),
 amount INT not null,
 delFlg CHAR(1) DEFAULT 0,
 updateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(userID, goodsID)
)";
*/

/*//	7_loginuser
$sql= "create table loginuser(
 userID VARCHAR(32) not null,
 pass VARCHAR(18),
 delFlg CHAR(1) DEFAULT 0,
 updateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(userID)
)";
*/

/*
//	5_mycart
$sql="create table mycart(
 goodsid VARCHAR(16) not null,
 mystock INT,
 PRIMARY KEY(goodsid)
)";
*/


if($sql!=''){
	if(pg_query($db_link, $sql)){
		echo "成功<br />";
	}
}

echo "終了<br />";

//	接続を切る
pg_close($db_link);
?>
</body>
</html>
