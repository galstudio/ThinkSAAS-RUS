<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['userid']);
if($userid){
	$tsSystemAntiUser = aac('system')->antiUser();
	if($tsSystemAntiUser){
		if(in_array($userid,$tsSystemAntiUser)){
			aac('user')->logout();
		}
	}
	echo 1;
}else{
	echo 0;
}
