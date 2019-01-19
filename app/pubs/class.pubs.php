<?php
defined('IN_TS') or die('Access Denied.');

class pubs extends tsApp{
	public function __construct($db){
        $tsAppDb = array();
		include 'app/pubs/config.php';
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
		parent::__construct($db);
	}
}
