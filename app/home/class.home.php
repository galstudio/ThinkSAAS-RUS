<?php
defined('IN_TS') or die('Access Denied.');

class home extends tsApp{

	public function __construct($db){
        $tsAppDb = array();
		include 'app/home/config.php';

		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}

		parent::__construct($db);
	}

}
