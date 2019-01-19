<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		$title = 'Вход в админпанель';
		include template("login");
		break;

	case "do":
		$email = trim($_POST['email']);
		$pwd = trim($_POST['pwd']);
		$cktime = $_POST['cktime'];
		if($email=='' || $pwd=='') qiMsg("Поля не могут быть пустыми!");
		$countAdmin = $new['system']->findCount('user',array(
			'email'=>$email,
		));
		if($countAdmin == 0) qiMsg('Такой Email не существует!');
		$strAdmin = $new['system']->find('user',array(
			'email'=>$email,
		));
		if(md5($strAdmin['salt'].$pwd)!==$strAdmin['pwd']) tsNotice('Неверный пароль пользователя!');
		$strAdminInfo = $new['system']->find('user_info',array(
			'email'=>$email,
		),'userid,username,isadmin');
		if($strAdminInfo['isadmin'] != 1) qiMsg("У вас нет прав на вход в админпанель!");
		$_SESSION['tsadmin'] = $strAdminInfo;
		header("Location: ".SITE_URL."index.php?app=system");
		break;

	case "out":
		unset($_SESSION['tsadmin']);
		header("Location: ".SITE_URL."index.php?app=system&ac=login");
		break;
}
