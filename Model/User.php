<?php
/*
 * 	User情報管理
 */
session_start();
include_once 'dbConnect.php';

class User extends dbConnect{
	//	User情報
	private $userTable;
	private $row_u;
	private $index;

	//	コンストラクタ
	function __construct(){
	}
	/*
	 * 	Getメソッド
	 */
	public function get_userTable()			{	return $this->userTable;					}
	public function get_userID($i)			{	return $this->userTable[$i]['userid'];		}
	public function get_userName($i)		{	return $this->userTable[$i]['username'];	}
	public function get_password($i)		{	return $this->userTable[$i]['pass'];		}
	public function get_index()				{	return $this->index;						}

	//	useridに値が入ってればそのまま、それ以外だとセッション値を返す
	public function userSet(){
		unset($userTable);
		if(!$_SESSION['userid']){
			$this->userTable[0]['userid']= session_id();
			$this->userTable[0]['username']= 'あなた';
			$this->userTable[0]['usertable']= 'cart';
			$this->userTable[0]['checkid']= 'sessionid';
		}else{
			$this->userTable[0]['userid']= $_SESSION['userid'];
			$this->userTable[0]['username']= $_SESSION['username'];
			$this->userTable[0]['usertable']= 'usercart';
			$this->userTable[0]['checkid']= 'userid';
		}
	}

	//	渡されたSQLの結果を保持する
	public function resultSQL($sql){
		//	配列の開放
		unset($userTable);
		$result = $this->db_query($sql);
		$this->row_u= $this->db_num_rows($result);
		for($i=0; $i< $this->row_u; ++$i){
			$arr = $this->db_fetch_array($result, $i, PGSQL_ASSOC);
			$this->userTable[$i]['userid']= $arr['userid'];
			$this->userTable[$i]['pass']= $arr['pass'];
			$this->userTable[$i]['username']= $arr['username'];
			$this->userTable[$i]['update']= $arr['updatedate'];
			$this->userTable[$i]['create']= $arr['createdate'];
		}
	}

	//	現在持ってるユーザー情報へアクセスするためのSQLを生成し返す
	public function createUsercart_SQL(){
		$sql= " SELECT * FROM goods, ".$this->userTable[0]['usertable']
				." WHERE goods.goodsid = ".$this->userTable[0]['usertable'].".goodsid "." and "
				.$this->userTable[0]['checkid']." = '".$this->userTable[0]['userid']."'"
				." ORDER BY ". $this->userTable[0]['usertable'].".goodsid";

		return $sql;
	}

	//	ユーザー登録
	public function insertUser($userid, $username, $pass){
		$sql= sprintf("INSERT INTO userinfo(userid, username, pass) VALUES('%s', '%s', '%s')"
		,pg_escape_string($userid) ,pg_escape_string($username), pg_escape_string($pass));
		$result = $this->db_query($sql);
	}

	//	IDとPASSの判定
	//	引数のIDとPASSが存在すればtrue,しなければfalse
	public function checkUser($userid, $pass){
		for($i=0; $i< $this->row_u; $i++){
			if($this->userTable[$i]['userid']==$userid && $this->userTable[$i]['pass']==$pass){
				$this->index= $i;
				return true;
			}
		}
		return false;
	}

	//	引数のIDが使用されていればtrue
	//	そうじゃなければfalse
	public function checkUserid($userid){
		for($i=0; $i< $this->row_u; $i++){
			if($this->userTable[$i]['userid']==$userid){
				return true;
			}
		}
		return false;
	}



	//	引数のIDのカートにいくつグッズが入っているかの判定
	public function checkStock($userid){
		$sql= " SELECT * FROM userinfo, usercart"
			." WHERE userinfo.userid = usercart.userid ";
		$this->db_query($sql);
		return $this->row_u;
	}
}
?>