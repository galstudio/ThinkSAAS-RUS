<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$strUser = aac('user')->getOneUser($userid);

if($strUser['locationid']==0){
	$arrLocation = $new['location']->findAll('location');
	if($arrLocation==''){
		tsNotice('Район еще не настроен, и присоединиться пока нельзя!');
	}

	$title = 'Присоединиться к району';
	include template('index');
	exit;
}

header('Location: '.tsUrl('location','show',array('id'=>$strUser['locationid'])));
exit;
