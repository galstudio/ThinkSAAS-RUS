<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

if(aac('user')->isPublisher()==false) tsNotice('У вас нет прав на публикацию альбома!');

switch($ts){
	case "":
		$title = 'Создание альбома';
		include template("create");
		break;
	case "do":
		//
		$userid = aac('user')->isLogin();
		$albumname = trim($_POST['albumname']);
		$albumdesc = trim($_POST['albumdesc']);
		if($albumname == '') {
			tsNotice("Название альбома не может быть пустым!");
		}
		if ($TS_APP['isaudit']==1) {
			$isaudit = 1;
		} else {
			$isaudit = 0;
		}
		if($TS_USER['isadmin']==0){
			//
			aac('system')->antiWord($albumname);
			aac('system')->antiWord($albumdesc);
			//
		}
		$albumid = $new['photo']->create('photo_album',array(
			'userid'=>$userid,
			'albumname'=>$albumname,
			'albumdesc'=>$albumdesc,
			'isaudit'=>$isaudit,
			'addtime'=>date('Y-m-d H:i:s'),
			'uptime'=>date('Y-m-d H:i:s'),
		));
		header("Location: ".tsUrl('photo','upload',array('albumid'=>$albumid)));
		break;
}
