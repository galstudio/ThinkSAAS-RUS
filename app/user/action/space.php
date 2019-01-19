<?php
defined('IN_TS') or die('Access Denied.');

include 'userinfo.php';
$arrGroupUser = $new['user']->findAll('group_user',array(
	'userid'=>$userid,
));
if(is_array($arrGroupUser)){
	foreach($arrGroupUser as $key=>$item){
		$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
	}
}
$arrGuest = $new['user']->findAll('user_gb',array(
	'touserid'=>$strUser['userid'],
),'addtime desc',null,10);

foreach($arrGuest as $key=>$item){
	$arrGuest[$key]['content'] = tsDecode($item['content']);
	$arrGuest[$key]['user']=$new['user']->getOneUser($item['userid']);
}
$title = $strUser['username'];
include template("space");
