<?php
defined('IN_TS') or die('Access Denied.');

class search extends tsApp{
	public function __construct($db){
        $tsAppDb = array();
		include 'app/search/config.php';
		//APP
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
		parent::__construct($db);
	}
}
