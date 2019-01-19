<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

switch($ts){
	case "add":
		$touserid = intval($_GET['touserid']);
		if($userid == $touserid || !$touserid) tsNotice("Вы не можете отправлять сообщения самому себе!");
		$strUser = $new['user']->getOneUser($userid);
		$strTouser = $new['user']->getOneUser($touserid);
		if(!$strTouser) tsNotice("Получатель не указан!");
		$title = "Отправка сообщения";
		include template("message_add");
		break;

	case "do":
		$msg_userid = $userid;
		$msg_touserid = intval($_POST['touserid']);
		$msg_content = trim($_POST['content']);
		aac('system')->antiWord($msg_content);
		aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		header("Location: ".tsUrl('message','my'));
		break;
}
