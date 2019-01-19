<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();
switch($ts){
	case "":
		$photoid = intval($_GET['photoid']);
		$strPhoto = $new['photo']->find('photo',array(
			'photoid'=>$photoid,
		));
		$strPhoto['photoname'] = tsTitle($strPhoto['photoname']);
		$strPhoto['photodesc'] = tsTitle($strPhoto['photodesc']);
		if($strPhoto['userid']==$userid || $TS_USER['isadmin']==1){
			$title = 'Правка изображения';
			include template('photo_edit');
		}else{
			tsNotice('Недопустимая операция!');
		}
		break;
	case "do":
		$photoid = intval($_POST['photoid']);
		$photoname = trim($_POST['photoname']);
		$photodesc = trim($_POST['photodesc']);
		$new['photo']->update('photo',array(
			'photoid'=>$photoid,
		),array(
			'photoname'=>$photoname,
			'photodesc'=>$photodesc,
		));
		header('Location: '.tsUrl('photo','show',array('id'=>$photoid)));
		break;
}
