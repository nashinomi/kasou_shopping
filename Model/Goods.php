<?php
/*
 * 		グッズテーブルの処理クラス
 */

include_once 'dbConnect.php';

class Goods extends dbConnect{
	//	テーブルCategoriesデータ
	private $goodsTable;
	private $row_G;

	//	コンストラクタ
	function __construct(){
	}

	/*
	 * 	GETメソッド
	 */
	public function get_row_g()			{	return $this->row_G;							}
	public function get_gTable()		{	return $this->goodsTable;						}
	public function get_gid($i)			{	return $this->goodsTable[$i]['goodsid'];		}
	public function get_gCid($i)		{	return $this->goodsTable[$i]['categoryid'];		}
	public function get_gName($i)		{	return $this->goodsTable[$i]['goodsname'];		}
	public function get_gPrice($i)		{	return $this->goodsTable[$i]['price'];			}
	public function get_gUprice($i)		{	return $this->goodsTable[$i]['unitprice'];		}
	public function get_gStock($i)		{	return $this->goodsTable[$i]['stock'];			}
	public function get_gNotes($i)		{	return $this->goodsTable[$i]['notes'];			}
	public function get_gMid($i)		{	return $this->goodsTable[$i]['makerid'];		}
	public function get_gMname($i)		{	return $this->goodsTable[$i]['makername'];		}
	public function get_gFpass($i)		{	return $this->goodsTable[$i]['filepass'];		}
	public function get_gFname($i)		{	return $this->goodsTable[$i]['filename'];		}
	public function get_gUpdate($i)		{	return $this->goodsTable[$i]['update'];			}
	public function get_gCreate($i)		{	return $this->goodsTable[$i]['create'];			}

	//	渡されたSQLの結果を保持する
	public function resultSQL($sql){
		//	配列の開放
		unset($goodsTable);
		$result = $this->db_query($sql);
		$this->row_G= $this->db_num_rows($result);
		for($i=0; $i< $this->row_G; ++$i){
			$arr = $this->db_fetch_array($result, $i, PGSQL_ASSOC);
			$this->goodsTable[$i]['goodsid']= $arr['goodsid'];
			$this->goodsTable[$i]['categoryid']= $arr['categoryid'];
			$this->goodsTable[$i]['goodsname']= $arr['goodsname'];
			$this->goodsTable[$i]['unitprice']= $arr['unitprice'];
			$this->goodsTable[$i]['price']= $arr['price'];
			$this->goodsTable[$i]['stock']= $arr['stock'];
			$this->goodsTable[$i]['notes']= $arr['goodsnotes'];
			$this->goodsTable[$i]['makerid']= $arr['makerid'];
			$this->goodsTable[$i]['makername']= $arr['makername'];
			$this->goodsTable[$i]['filepass']= !$arr['filepass'] ? '../img/noimage.jpg' : $arr['filepass'];
			$this->goodsTable[$i]['filename']= !$arr['filename'] ? 'noimage' : $arr['filename'];
			$this->goodsTable[$i]['update']= $arr['updatedate'];
			$this->goodsTable[$i]['create']= $arr['createdate'];
		}
	}

	//	キーワードが含まれるものだけ配列に格納して返す
	public function Retrieval($key){
		$matchlist;
		//	キーワードが含まれているかの判定処理
		for($i=0,$j=0; $i< $this->row_G; ++$i){
			//   strcmp($arr['goodsname'], 4$key)==0
			if(mb_strpos($this->goodsTable[$i]['goodsname'], $key) !== FALSE){
				$matchlist[$j]['goodsid']= $this->goodsTable[$i]['goodsid'];
				$matchlist[$j]['goodsname']= $this->goodsTable[$i]['goodsname'];
				$matchlist[$j]['makername']= $this->goodsTable[$i]['makername'];
				$matchlist[$j]['price']= $this->goodsTable[$i]['price'];
				$matchlist[$j]['filepass']= $this->goodsTable[$i]['filepass'];
				$j++;
			}
		}
		return $matchlist;
	}
}
?>