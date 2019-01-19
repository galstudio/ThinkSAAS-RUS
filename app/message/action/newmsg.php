<?php
defined('IN_TS') or die('Access Denied.');

	// id
	$userid = intval($_GET['userid']);
	if(!$userid) {
		echo '0';
	}

	$newMsgNum = $new['message']->findCount('message',array(
		'touserid'=>$userid,
		'isread'=>0,
	));

	if($newMsgNum == '0'){
		echo '0';
	}else{
		echo $newMsgNum;
	}
