<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
class MySql {

	public $queryCount = 0;
	public $conn;
	public $result;

    /**
     * SQL
     */
    public $arrSql;

	/**
	 * @param unknown $DB
	 */
	function __construct($DB) {


		if (! function_exists ( 'mysql_connect' )) {
			qiMsg ( 'Сервер PHP не поддерживает базу данных MySql' );
		}

		if ($DB ['host'] && $DB ['user']) {

			if (! $this->conn = mysql_connect ( $DB ['host'] . ':' . $DB ['port'], $DB ['user'], $DB ['pwd'] )) {
				qiMsg ( "Не удалось подключиться к базе данных, возможно, неверный логин или пароль к базе данных." );
			}
		}

		$this->query ( "SET NAMES 'utf8'" );

		if ($DB ['name'])
			mysql_select_db ( $DB ['name'], $this->conn ) or qiMsg ( "Указанная база данных не найдена" );

	}

	/**
	 * @param unknown $value
	 * @return string|number
	 */
	public function escape($value) {
		if (is_null ( $value ))
			return 'NULL';
		if (is_bool ( $value ))
			return $value ? 1 : 0;
		if (is_int ( $value ))
			return ( int ) $value;
		if (is_float ( $value ))
			return ( float ) $value;
		/*
		if (get_magic_quotes_gpc ()){
			$value = stripslashes ( $value );
		}
		*/
		return '\'' . mysql_real_escape_string ( $value, $this->conn ) . '\'';
	}

	/**
	 * SQL
	 * @param unknown $sql
	 * @param unknown $limit
	 * @return string
	 */
	public function setlimit($sql, $limit) {
		return $sql . " LIMIT {$limit}";
	}

	/**
	 * @param unknown $sql
	 * @return resource
	 */
	function query($sql) {

        $this->arrSql = $sql;

        $start_time = microtime(true);
		$this->result = mysql_query ( $sql, $this->conn );
        $end_time = microtime(true);
        $total_time = $end_time-$start_time;
		$this->queryCount ++;

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


		// SQL
		if (! $this->result) {
			$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
			$log .= "SQL:" . $sql . "\n";
			$log .= "ERROR:" . mysql_error () . "\n";
			$log .= "REQUEST_URI:" . $_SERVER['REQUEST_URI'] . "\n";
			$log .= "--------------------------------------\n";
			logging ( date ( 'Ymd' ) . '-mysql-error.txt', $log );
		}

		// SQL
		if ($GLOBALS['TS_CF'] ['logs']) {

			$log = "TIME:" . date ( 'Y-m-d :H:i:s' ) . "\n";
			$log .= "SQL:" . $sql . "\n";
			$log .= "--------------------------------------\n";
			logging ( date ( 'Ymd' ) . '-mysql.txt', $log );
		}

		return $this->result;
	}

	/**
	 * @param unknown $sql
	 * @param number $max
	 * @return multitype:
	 */
	function fetch_all_assoc($sql, $max = 0) {
		$query = $this->query ( $sql );
		while ( $list_item = mysql_fetch_assoc ( $query ) ) {

			$current_index ++;

			if ($current_index > $max && $max != 0) {
				break;
			}

			$all_array [] = $list_item;
		}

		return $all_array;
	}
	function once_fetch_assoc($sql) {
		$list = $this->query ( $sql );
		$list_array = mysql_fetch_assoc ( $list );
		return $list_array;
	}

	/**
	 * @param unknown $sql
	 * @return number
	 */
	function once_num_rows($sql) {
		$query = $this->query ( $sql );
		return mysql_num_rows ( $query );
	}

	/**
	 * @param unknown $query
	 * @return number
	 */
	function num_fields($query) {
		return mysql_num_fields ( $query );
	}

	/**
	 * INSERT ID
	 * @return number
	 */
	function insert_id() {
		return mysql_insert_id ( $this->conn );
	}

	/**
	 * @param unknown $arrData
	 * @param unknown $table
	 * @param string $where
	 * @return number
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
		return mysql_insert_id ( $this->conn );
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
	 * @return string
	 */
	function geterror() {
		return mysql_error ();
	}

	/**
	 * Get number of affected rows in previous MySQL operation
	 * @return number
	 */
	function affected_rows() {
        return mysql_affected_rows($this->conn);
	}
	function getMysqlVersion() {
		return @mysql_get_server_info ();
	}
	public function __destruct() {
		return mysql_close ( $this->conn );
	}
}
