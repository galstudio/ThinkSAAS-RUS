<?php
defined('IN_TS') or die('Access Denied.');

function gobad($w){
	global $tsMySqlCache;
	$code = fileRead('data/plugins_pubs_gobad.php');
	if($code==''){
		$code = $tsMySqlCache->get('plugins_pubs_gobad');
	}
	echo stripslashes($code[$w]);
}
addAction('gobad','gobad');
