<?php
defined('IN_TS') or die('Access Denied.');

$groupid = intval($_GET['id']);

$typeid = intval($_GET['typeid']);

$isshow = intval($_GET['isshow']);

$strGroup = $new['group']->getOneGroup($groupid);

if($strGroup['groupid'] == '') {
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;
}

if($strGroup['isaudit'] == 1) {
	tsNotice('Обзор групп…');
}

$title = $strGroup['groupname'];

$arrTopicTypes = $new['group']->findAll('group_topic_type',array(
	'groupid'=>$groupid,
));

if(is_array($arrTopicTypes)){
	foreach($arrTopicTypes as $item){
		$arrTopicType[$item['typeid']] = $item;
	}
}

$strLeader = aac('user')->getOneUser($strGroup['userid']);

$isGroupUser = '';
if(intval($TS_USER['userid'])){
	$strUser = aac('user')->getOneUser(intval($TS_USER['userid']));
	$isGroupUser = $new['group']->find('group_user',array(
		'userid'=>intval($TS_USER['userid']),
		'groupid'=>$groupid,
	));
}

if($strGroup['isaudit']=='1'){

	$arrRecommendGroup = $new['group']->getRecommendGroup('7');
	include template("group_isaudit");

}else{

	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	$lstart = $page*30-30;

	if($typeid > 0){
		$andType = " and `typeid`='$typeid'";
		$url = tsUrl('group','show',array('id'=>$groupid,'typeid'=>$typeid,'page'=>''));
	}else{
		$andType = '';
		$url = tsUrl('group','show',array('id'=>$groupid,'page'=>''));
	}

	$arrTopics = $new['group']->findAll('group_topic',"`groupid`='$groupid' ".$andType." and `isaudit`='0'",'istop desc,uptime desc',null,$lstart.',30');

	if( is_array($arrTopics)){
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['title'] = tsTitle($item['title']);
			$arrTopic[$key]['content'] = tsDecode($item['content']);
			$arrTopic[$key]['typename'] = $arrTopicType[$item['typeid']]['typename'];
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		}
	}

	$topicNum = $new['group']->findCount('group_topic',"`groupid`='$groupid' ".$andType);

	$pageUrl = pagination($topicNum, 30, $page, $url);

	$groupUser = $new['group']->findAll('group_user',array(
		'groupid'=>$groupid,
	),'addtime desc',null,8);

	if(is_array($groupUser)){
		foreach($groupUser as $item){
			$strUser = aac('user')->getOneUser($item['userid']);
			if($strUser){
				$arrGroupUser[] = $strUser;
			}else{
				$new['group']->delete('group_user',array(
					'userid'=>$item['userid'],
					'groupid'=>$groupid,
				));
			}
		}
	}

    $arrGroupAdmin = $new['group']->findAll('group_user',array(
        'groupid'=>$groupid,
        'isadmin'=>1,
    ));
    $arrGroupAdminUser = array();
    if($arrGroupAdmin){
        foreach($arrGroupAdmin as $key=>$item){
            $arrGroupUserId[] = $item['userid'];
        }
        $groupUserIds = arr2str($arrGroupUserId);
        $arrGroupAdminUser = $new['group']->findAll('user_info',"`userid` in ($groupUserIds)",'addtime desc','userid,username');
    }

	$strGroup ['tags'] = aac ( 'tag' )->getObjTagByObjid ( 'group', 'groupid', $strGroup ['groupid'] );

	if($page > 1){
		$title = $strGroup['groupname'].' - Страница '.$page.'';
	}

	if($strGroup['tags']){
		foreach($strGroup['tags'] as $key=>$item){
			$arrTag[] = $item['tagname'];
		}
		$sitekey = $strGroup['groupname'].','.arr2str($arrTag);
	}else{
		$sitekey = $strGroup['groupname'];
	}


	$sitedesc = tsCutContent($strGroup['groupdesc'],50);

	if($TS_CF['mobile']) $sitemb = tsUrl('moblie','group',array('ts'=>'show','groupid'=>$strGroup['groupid']));

	include template("show");

}
