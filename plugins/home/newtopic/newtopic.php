<?php
defined('IN_TS') or die('Access Denied.');

function newtopic(){
	global $db;
	$arrTopic = aac('group')->findAll('group_topic',array(
		'isaudit'=>0,
	),'uptime desc','topicid,userid,groupid,title,label,count_comment,uptime',35);
	foreach($arrTopic as $key=>$item){
			$arrTopic[$key]['title']=tsTitle($item['title']);
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
	}
	include template('newtopic','newtopic');
}
function newtopic_css(){
	echo '<link href="'.SITE_URL.'plugins/home/newtopic/style.css" rel="stylesheet" type="text/css" />';
}
addAction('home_index_left','newtopic');
addAction('pub_header_top','newtopic_css');
