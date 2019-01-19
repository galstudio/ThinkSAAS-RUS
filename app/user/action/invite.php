<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();
$strUser = $new['user']->find('user_info',array(
	'userid'=>$userid,
));

switch($ts){
	case "":
		$codeNum = $new['user']->findCount('user_invites',array(
			'userid'=>$userid,
			'isused'=>0,
		));
		$arrCode = $new['user']->findAll('user_invites',array(
			'userid'=>$userid,
			'isused'=>0,
		));
		$title = 'Инвайты';
		include template("invite");
		break;

	case "code":
		$codeNum = $new['user']->findCount('user_invites',array(
			'userid'=>$userid,
			'isused'=>0,
		));
		if($codeNum == 0 && $TS_USER['isadmin']==1){
			for($i=1;$i<=10;$i++){
				$new['user']->create('user_invites',array(
					'userid'=>$userid,
					'invitecode'=>random(18).$userid,
					'addtime'=>time(),
				));
			}
		}
		header('Location: '.tsUrl('user','invite'));
		break;
}
