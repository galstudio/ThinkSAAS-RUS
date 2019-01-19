<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":

		$groupid = intval($_GET['id']);

		$strGroup = $new['group']->getOneGroup($groupid);
		if($strGroup == '') {
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			$title = '404';
			include pubTemplate("404");
			exit;
		}

		$leaderId = $strGroup['userid'];

		$strLeader = aac('user')->getOneUser($leaderId);

		$strAdmin = $new['group']->findAll('group_user',array(
			'groupid'=>$strGroup['groupid'],
			'isadmin'=>'1',
			'isfounder'=>'0',
		));

		if(is_array($strAdmin)){
			foreach($strAdmin as $key=>$item){
				$arrAdmin[] = aac('user')->getOneUser($item['userid']);
				$arrAdmin[$key]['isadmin'] = $item['isadmin'];
			}
		}

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

		$url = tsUrl('group','user',array('id'=>$groupid,'page'=>''));

		$lstart = $page*40-40;

		$groupUserNum = $new['group']->findCount('group_user',array(

			'groupid'=>$groupid,
			'isadmin'=>0,
			'isfounder'=>0,

		));

		$groupUser = $new['group']->findAll('group_user',array(
			'groupid'=>$strGroup['groupid'],
			'isadmin'=>'0',
			'isfounder'=>'0',
		),'userid desc',null,$lstart.',40');
		//print_r($groupUser);

		if(is_array($groupUser)){
			foreach($groupUser as $key=>$item){
				$arrGroupUser[] = aac('user')->getOneUser($item['userid']);
				$arrGroupUser[$key]['isadmin'] = $item['isadmin'];
			}
		}

		$pageUrl = pagination($groupUserNum, 40, $page, $url);

		if($page > '1'){
			$titlepage = " - Страница ".$page."";
		}else{
			$titlepage='';
		}

		$title = 'Участник «'.$strGroup['groupname'].'» группы'.$titlepage;

		include template("user");

		break;

}
