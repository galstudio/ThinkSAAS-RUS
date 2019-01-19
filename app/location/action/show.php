<?php
defined('IN_TS') or die('Access Denied.');

$locationid = intval($_GET['id']);

$strLocation = $new['location']->find('location',array(
	'locationid'=>$locationid,
));

$strLocation['title'] = tsTitle($strLocation['title']);
$strLocation['content'] = tsTitle($strLocation['content']);

$arrArticle = $new['location']->findAll('article',array(
	'locationid'=>$locationid,
),'addtime desc',null,10);

/*
$arrAttach = $new['location']->findAll('attach',array(
	'locationid'=>$locationid,
),'addtime desc',null,10);
*/

$arrTopic = $new['location']->findAll('group_topic',array(
	'locationid'=>$locationid,
),'addtime desc',null,10);
foreach($arrTopic as $key=>$item){
    $arrTopic[$key]['title'] = tsTitle($item['title']);
	$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
	$arrTopic[$key]['group'] = $new['location']->find('group',array(
		'groupid'=>$item['groupid'],
	));
}

$arrPhoto = $new['location']->findAll('photo',array(
	'locationid'=>$locationid,
),'addtime desc',null,10);

$arrUser = $new['location']->findAll('user_info',array(
	'locationid'=>$locationid,
),'uptime desc',null,16);

$arrWeibo = $new['location']->findAll('weibo',array(
	'locationid'=>$locationid,
),'addtime desc',null,10);
foreach($arrWeibo as $key=>$item){
	$arrWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
}

$isLocationId = 0;
if($TS_USER['userid']){
	$strUser=$new['location']->find('user_info',array(
		'userid'=>$TS_USER['userid'],
	));
	$isLocationId = $strUser['locationid'];
}

$sitekey = $strLocation['title'];
$sitedesc = cututf8(t($strLocation['content']),0,100);
$title = $strLocation['title'];
include template('show');
