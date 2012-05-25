<?php
/*
 * 		データベース接続。切断など直接データベースに関わる処理
 * 		PostgresSQL用
 */

class dbConnect{
	//	データ接続
	private $dblink;

	protected function getlink(){	return $this->dblink;	}

	//	データベース接続
	protected function db_link(){
		if(!$this->dblink= pg_connect("host=localhost user=advancement password=arkloop2 dbname=advancement")){
			die('接続できませんでした');
		}
	}

	//	データベース切断
	protected function db_close(){
		//	接続を切る
		pg_close($this->dblink);
	}

	//	クエリのデータ総数を返す
	protected function db_num_rows($result){
		return pg_num_rows($result);
	}

	//	データベースの値を配列として取り出す
	protected function db_fetch_array($result, $i, $element){
		return pg_fetch_array($result, $i, $element);
	}
	protected function db_query($sql){
		$this->db_link();
		$result= pg_query($this->dblink, $sql);
		$this->db_close();
		return $result;
	}

	/*
	 * テンプレート
	 */
	public function resultSQL(){
	}
}
?>