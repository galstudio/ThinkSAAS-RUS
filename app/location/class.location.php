<?php
defined('IN_TS') or die('Access Denied.');
class location extends tsApp{

	public function __construct($db){
        $tsAppDb = array();
		include 'app/location/config.php';

		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}

		parent::__construct($db);
	}

	public function __destruct(){

	}

}
