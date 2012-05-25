<?php
/*
 * 	Cart情報管理
 */

include_once 'dbConnect.php';

class Cart extends dbConnect{
	//	User情報
	private $cartTable;
	private $row_ca;
	private $allprice;

	//	コンストラクタ
	function __construct(){
	}
	/*
	 * 	Getメソッド
	 */
	public function get_row_ca()			{	return $this->row_ca;						}
	public function get_cartTable()			{	return $this->cartTable;					}
	public function get_cartID($i)			{	return $this->cartTable[$i]['userid'];		}
	public function get_cartAmount($i)		{	return $this->cartTable[$i]['amont'];		}
	//public function get_cartAllprice()		{	return $this->allprice;						}

	//	渡されたSQLの結果を保持する
	public function resultSQL($sql){
		//	配列の開放
		unset($cartTable);
		$result = $this->db_query($sql);
		$this->row_ca= $this->db_num_rows($result);
		for($i=0; $i< $this->row_ca; ++$i){
			$arr = $this->db_fetch_array($result, $i, PGSQL_ASSOC);
			$this->cartTable[$i]['goodsid']=	 $arr['goodsid'];
			$this->cartTable[$i]['userid']= $arr['userid'];
			$this->cartTable[$i]['sessionid']= $arr['sessionid'];
			$this->cartTable[$i]['amount']= $arr['amount'];
			$this->cartTable[$i]['categoryid']= $arr['categoryid'];
			$this->cartTable[$i]['goodsname']= $arr['goodsname'];
			$this->cartTable[$i]['unitprice']= $arr['unitprice'];
			$this->cartTable[$i]['price']= $arr['price'];
			$this->cartTable[$i]['stock']= $arr['stock'];
			$this->cartTable[$i]['notes']= $arr['goodsnotes'];
			$this->cartTable[$i]['makerid']= $arr['makerid'];
			$this->cartTable[$i]['makername']= $arr['makername'];
			$this->cartTable[$i]['filepass']= !$arr['filepass'] ? '../img/noimage.jpg' : $arr['filepass'];
			$this->cartTable[$i]['filename']= !$arr['filename'] ? 'noimage' : $arr['filename'];
			$this->cartTable[$i]['update']= $arr['updatedate'];
			$this->cartTable[$i]['create']= $arr['createdate'];
		}
	}

	//	合計金額を求める
	public function gettotalPrice(){
		for($i=0; $i < $this->row_ca; ++$i){
			$totalprice=$this->cartTable[$i]['amount'] * $this->cartTable[$i]['price'];
			$this->allprice+=$totalprice;
		}
		return $this->allprice;
	}


	//	カート更新処理、主に数量
	public function updateCartgoods($cart, $user){
		//	ユーザー判定
		if($user['login'])	{	$cartname= 'usercart';	}
		else				{	$cartname= 'cart';		}

		for($i=0; $i< count($cart); ++$i){
			$sql=sprintf("UPDATE $cartname SET amount= '%d' WHERE goodsID= '%s'",
			pg_escape_string($cart[$i]['amount']),
			pg_escape_string($cart[$i]['goodsid']));
			$result=$this->db_query($sql);
		}
	}

	//	カート追加処理
	public function insertCartgoods($goodsid, $user){
	//	cartテーブルに追加する処理(sessionid使用)

		$mycartflag= false;
		if($user['login']){
			$cartname= 'usercart';
			$userid= 'userid';
		}else{
			$cartname= 'cart';
			$userid= 'sessionid';
		}

		$sql= "SELECT * FROM $cartname WHERE $userid = '".$user['id']."'";
		$this->resultSQL($sql);
		$i = 0;

		for(; $i < $this->row_ca; $i++){
			if($this->cartTable[$i]['goodsid']==$goodsid){
				$mycartflag= true;
				break;
			}
		}
		if(!$mycartflag){
			//	カートテーブルを更新
			$sql= sprintf("INSERT INTO $cartname($userid, goodsid, amount) VALUES('%s', '%s', '%d')"
			,pg_escape_string($user['id']), pg_escape_string($goodsid), pg_escape_string(1));
			$result = $this->db_query($sql);
			//$this->resultSQL_Insert($sql);
			//$cartmess= '以下の商品がカートに追加されました。';
		}else{
			//$cartmess= '以下の商品はすでにカートに入っています。';
		}
	}
	//	カートからグッズ削除処理
	public function deleteCartgoods($goodsid, $user){
		//	ユーザー判定
		if($user['login']){
			$cartname= 'usercart';
			$userid= 'userid';
		}else{
			$cartname= 'cart';
			$userid= 'sessionid';
		}

		$sql= "DELETE FROM $cartname WHERE $cartname.$userid='".$user['id']."' and $cartname.goodsid='$goodsid'";
		$result = $this->db_query($sql);
	}
}
?>