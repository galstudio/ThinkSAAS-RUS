<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
class MySql {
	public $query_count = 0;
	public $conn;
	public $arrSql;
	private $num_rows;

	/**
	 * @param unknown $DB
	 */
	function __construct($DB) {
		$dsn = 'mysql:host=' . $DB ['host'] . ';dbname=' . $DB ['name'];

		try {
			$this->conn = new pdo ( $dsn, $DB ['user'], $DB ['pwd'], array (
					PDO::ATTR_PERSISTENT => true
			) );
			$this->query ( "set names 'utf8'" );
		} catch ( PDOException $e ) {
			echo $e->getMessage ();
			exit ();
		}
	}

	/**
	 * @param unknown $string
	 * @return string
	 */
	function escape($string) {
		return $this->conn->quote ( $string );
	}

	/**
	 * SQL
	 */
	public function setlimit($sql, $limit) {
		return $sql . " LIMIT {$limit}";
	}

	/**
	 * updata insert delete
	 * @param unknown $sql
	 * @return number
	 */
	function query($sql) {
		$this->arrSql [] = $sql;

        $start_time = microtime(true);
		$result = $this->conn->exec ( $sql );
        $end_time = microtime(true);
        $total_time = $end_time-$start_time;

        $run_time = number_format($total_time, 6);

        //sql
        if($GLOBALS['TS_CF']['slowsqllogs'] && $run_time>$GLOBALS['TS_CF']['slowsqllogs']){
            $log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
            $log .= "SQL:" . $sql . "\n";
            $log .= "RUN_TIME:" . $run_time . "\n";
            $log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
            $log .= "--------------------------------------\n";
            logging ( date ( 'Ymd' ) . '-mysqli-slow.txt', $log );
        }


		if (FALSE !== $result) {
			$this->num_rows = $result;
			return $result;
		} else {

			$poderror = $this->conn->errorInfo ();
			//SQL
			$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
			$log .= "SQL:" . $sql . "\n";
			$log .= "ERROR:" . $this->conn->errorInfo ( $poderror [2] ) . "\n";
			$log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
			$log .= "--------------------------------------\n";
			logging ( date ( 'Ymd' ) . '-mysql-error.txt', $log );


		}
	}

	/**
	 * @param unknown $sql
	 * @return multitype:
	 */
	function fetch_all_assoc($sql) {
		$this->conn->setAttribute ( PDO::ATTR_CASE, PDO::CASE_LOWER );
		$rows = $this->conn->prepare ( $sql );

		if ($this->conn->errorCode () != 00000) {

			$poderror = $this->conn->errorInfo ();
			//SQL
			$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
			$log .= "SQL:" . $sql . "\n";
			$log .= "ERROR:" . $this->conn->errorInfo ( $poderror [2] ) . "\n";
			$log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
			$log .= "--------------------------------------\n";
			logging ( date ( 'Ymd' ) . '-mysql-error.txt', $log );

		}

		$rows->execute ();

		$this->query_count += 1;

		$rows->setFetchMode ( PDO::FETCH_ASSOC ); // fetch_assoc
		return $rows->fetchAll ();
	}

	/**
	 * @param unknown $sql
	 * @param number $symbols
	 * @return string|Ambigous <string, mixed>
	 */
	function once_fetch_assoc($sql, $symbols = 0) {
		$this->conn->setAttribute ( PDO::ATTR_CASE, PDO::CASE_LOWER );
		$rows = $this->conn->prepare ( $sql );
		if ($this->conn->errorCode () != 00000) {
			if ($symbols == 0) {

				$poderror = $this->conn->errorInfo ();
				//SQL
				$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
				$log .= "SQL:" . $sql . "\n";
				$log .= "ERROR:" . $this->conn->errorInfo ( $poderror [2] ) . "\n";
				$log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
				$log .= "--------------------------------------\n";
				logging ( date ( 'Ymd' ) . '-mysql-error.txt', $log );

			} else {
				return "Error";
			}
		}

		$rows->execute ();

		$this->query_count += 1;
		$rows->setFetchMode ( PDO::FETCH_ASSOC ); // fetch_assoc
		$da = '';
		while ( $row = $rows->fetch () ) {
			$da = $row;
		}

		return $da;
	}

	/**
	 * @param unknown $sql
	 * @return number
	 */
	function once_num_rows($sql) {
		$rows = $this->conn->prepare ( $sql );
		$rows->execute ();
		$num = $rows->rowCount ();
		return $num;
	}

	/**
	 * INSERT ID
	 * @return string
	 */
	function insert_id() {
		return $this->conn->lastInsertId ();
	}

	/**
	 * @param unknown $arrData
	 * @param unknown $table
	 * @param string $where
	 * @return string
	 */
	function insertArr($arrData, $table, $where = '') {
		$Item = array ();
		foreach ( $arrData as $key => $data ) {
			$Item [] = "$key='$data'";
		}
		$intStr = implode ( ',', $Item );
		$sql = "insert into $table  SET $intStr $where";
		// echo $sql;
		$this->query ( "insert into $table  SET $intStr $where" );
		return $this->insert_id ();
	}

	/**
	 * Update
	 * @param unknown $arrData
	 * @param unknown $table
	 * @param string $where
	 * @return boolean
	 */
	function updateArr($arrData, $table, $where = '') {
		$Item = array ();
		foreach ( $arrData as $key => $date ) {
			$Item [] = "$key='$date'";
		}
		$upStr = implode ( ',', $Item );
		$this->query ( "UPDATE $table  SET  $upStr $where" );
		return true;
	}

	/**
	 * mysql
	 * @return Ambigous <>
	 */
	function geterror() {
		$result = $this->conn->errorInfo ();
		return $result [2];
	}
	function getMysqlVersion() {
		$Data = $this->once_fetch_assoc ( "SELECT version( ) AS version" );
		return $Data ['version'];
	}

	/**
	 * @param unknown $err
	 */
	function error($err) {
		$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
		$log .= "SQL:" . $err . "\n";
		$log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
		$log .= "--------------------------------------\n";
		logging ( date ( 'Ymd' ) . '-mysql-error.txt', $log );
	}
}
