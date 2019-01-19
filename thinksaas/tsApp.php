<?php
// ///////////////////////////////////////////////////////////////////////
// ThinkSAAS Copyright (C) 2011 - 3000 ThinkSAAS.cn //
// //////////////////////////////////////////////////////////////////////
defined('IN_TS') or die('Access Denied.');
class tsApp {
	public $db;
	public function __construct($dbhandle) {
		$this->db = $dbhandle;
	}

    /**
     * @table
     * @param row
     * @param
     * @return bool
     */
	public function create($table, $row) {
		if (! is_array ( $row ) || empty ( $row ))
			return FALSE;
		$sql = '';
		if (empty ( $row ))
			return FALSE;
		foreach ( $row as $key => $value ) {
			$cols [] = $key;
			$vals [] = $this->escape ( $value );
		}
		$col = join ( ',', $cols );
		$val = join ( ',', $vals );

		$sql = "INSERT INTO " . dbprefix . $table . " ({$col}) VALUES ({$val})";

		if (FALSE != $this->db->query ( $sql )) {
			if ($newinserid = $this->db->insert_id ()) {
				return $newinserid;
			}
		}
		return FALSE;
	}

	/**
	 * @param table
	 * @param conditions
	 * @param row
	 */
	public function replace($table, $conditions, $row) {
		if ($this->find ( $table, $conditions )) {
			return $this->update ( $table, $conditions, $row );
		} else {
			if (! is_array ( $conditions ))
				qiMsg ( 'Условие метода замены должно быть массивом!' );
			return $this->create ( $table, $row );
		}
	}

	/**
	 * 修改数据，该函数将根据参数中设置的条件而更新表中数据
	 *
	 * @param table 数据表
	 * @param conditions 数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 * @param row 数组形式，修改的数据，此参数的格式用法与create的$row是相同的。在符合条件的记录中，将对$row设置的字段的数据进行修改。
	 */
	public function update($table, $conditions, $row) {
		$where = "";
		if (empty ( $row ))
			return FALSE;
		if (is_array ( $conditions )) {
			$join = array ();
			foreach ( $conditions as $key => $condition ) {
				$condition = $this->escape ( $condition );
				$join [] = "`{$key}` = {$condition}";
			}
			$where = "WHERE " . join ( " AND ", $join );
		} else {
			if (null != $conditions)
				$where = "WHERE " . $conditions;
		}
		foreach ( $row as $key => $value ) {
			$value = $this->escape ( $value );
			//$vals [] = "`$key` = $value";
			$vals [] = "{$key} = {$value}";
		}
		$values = join ( ", ", $vals );
		$sql = "UPDATE " . dbprefix . "{$table} SET {$values} {$where}";

		return $this->db->query ( $sql );
	}

	/**
	 * @param table
	 * @param conditions
	 */
	public function delete($table, $conditions) {
		$where = "";
		if (is_array ( $conditions )) {
			$join = array ();
			foreach ( $conditions as $key => $condition ) {
				$condition = $this->escape ( $condition );
				$join [] = "`{$key}` = {$condition}";
			}
			$where = "WHERE  " . join ( " AND ", $join ) . "";
		} else {
			if (null != $conditions)
				$where = "WHERE  " . $conditions . "";
		}
		$sql = "DELETE FROM " . dbprefix . "{$table} {$where}";
		return $this->db->query ( $sql );
	}

	/**
	 * @param table
	 * @param conditions
	 * @param fields
	 * @param sort
	 */
	public function find($table, $conditions = null, $fields = null, $sort = null) {
		if ($record = $this->findAll ( $table, $conditions, $sort, $fields, 1 )) {
			return array_pop ( $record );
		} else {
			return FALSE;
		}
	}

	/**
	 * @param table
	 * @param conditions
	 * @param sort
	 * @param fields
	 * @param limit
	 */
	public function findAll($table, $conditions = null, $sort = null, $fields = null, $limit = null) {
		$where = "";
		$fields = empty ( $fields ) ? "*" : $fields;
		if (is_array ( $conditions )) {
			$join = array ();
			foreach ( $conditions as $key => $condition ) {
				$condition = $this->escape ( $condition );
				$join [] = "`{$key}` = {$condition}";
			}
			$where = "WHERE " . join ( " AND ", $join );
		} else {
			if (null != $conditions)
				$where = "WHERE " . $conditions;
		}
		if (null != $sort) {
			$sort = "ORDER BY {$sort}";
		} else {
			$sort = "";
		}
		$sql = "SELECT {$fields} FROM " . dbprefix . "{$table} {$where} {$sort}";
		if (null != $limit)
			$sql = $this->db->setlimit ( $sql, $limit );
		return $this->db->fetch_all_assoc ( $sql );
	}

	/**
	 * @param value
	 */
	public function escape($value) {
		return $this->db->escape ( $value );
	}

	/**
	 * @param table
	 * @param conditions
	 */
	public function findCount($table, $conditions = null) {
		$where = "";
		if (is_array ( $conditions )) {
			$join = array ();
			foreach ( $conditions as $key => $condition ) {
				$condition = $this->escape ( $condition );
				$join [] = "`{$key}` = {$condition}";
			}
			$where = "WHERE " . join ( " AND ", $join );
		} else {
			if (null != $conditions)
				$where = "WHERE " . $conditions;
		}
		$sql = "SELECT COUNT(*) AS ts_counter FROM " . dbprefix . "{$table} {$where}";
		$result = $this->db->once_fetch_assoc ( $sql );

		return $result ['ts_counter'];
	}

	/**
	 * @param conditions
	 * @param field
	 * @param value
	 */
	public function updateField($table, $conditions, $field, $value) {
		return $this->update ( $table, $conditions, array (
				$field => $value
		) );
	}

    /**
     * @param sql
     */
    public function doSql($sql){
        return $this->db->query($sql);
    }

    /**
     * @param table
     * @param rows
     */
    public function createAll($table,$rows)
    {
        foreach($rows as $row)$this->create($table,$row);
    }

    /**
     * @param table
     * @param field
     * @param value
     */
    public function findBy($table,$field, $value)
    {
        return $this->find($table,array($field=>$value));
    }

    /**
     * SQL
     */
    public function dumpSql()
    {
        return end( $this->db->arrSql );
    }

    /**
     * update,create,delete,exec
     */
    public function affectedRows()
    {
        return $this->db->affected_rows();
    }


    /**
     * @param table
     * @param conditions
     * @param field
     * @param optval
     */
    public function incrField($table,$conditions, $field, $optval = 1)
    {
        $where = "";
        if(is_array($conditions)){
            $join = array();
            foreach( $conditions as $key => $condition ){
                $condition = $this->escape($condition);
                $join[] = "{$key} = {$condition}";
            }
            $where = "WHERE ".join(" AND ",$join);
        }else{
            if(null != $conditions)$where = "WHERE ".$conditions;
        }
        $values = "{$field} = {$field} + {$optval}";
        $sql = "UPDATE ".dbprefix."{$table} SET {$values} {$where}";
        return $this->db->query($sql);
    }

    /**
     * @param table
     * @param conditions
     * @param field
     * @param optval
     */
    public function decrField($table,$conditions, $field, $optval = 1)
    {
        return $this->incrField($table,$conditions, $field, - $optval);
    }

}
