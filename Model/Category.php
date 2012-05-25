<?php
/*
 * 		カテゴリーテーブルの処理クラス
 */

include_once 'dbConnect.php';

class Category extends dbConnect{
	//	テーブルCategoriesデータ
	private $categoryTable;
	private $row_C;

	//	コンストラクタ
	function __construct(){
	}

	/*
	 * 	GETメソッド
	 */
	public function get_row_c()			{	return $this->row_C;						}
	public function get_cTable()		{	return $this->categoryTable;				}
	public function get_cID($i)			{	return $this->categoryTable[$i]['categoryid'];		}
	public function get_cName($i)		{	return $this->categoryTable[$i]['categoryname'];	}


	//	渡されたSQLの結果を保持する
	public function resultSQL($sql){
		//	配列の開放
		unset($categoryTable);
		$result = $this->db_query($sql);
		$this->row_C= $this->db_num_rows($result);
		for($i=0; $i< $this->row_C; ++$i){
			$arr = $this->db_fetch_array($result, $i, PGSQL_ASSOC);
			$this->categoryTable[$i]['categoryid']= $arr['categoryid'];
			$this->categoryTable[$i]['categoryname']= $arr['categoryname'];
		}
	}

}
?>