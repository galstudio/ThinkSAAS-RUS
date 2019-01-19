<?php

defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$groupid = intval($_GET['groupid']);

$strGroup = $new['group']->find('group',array(
	'groupid'=>$groupid,
));

if($strGroup['userid']==$userid || $TS_USER['isadmin']==1){

	switch($ts){

		case "":

			$arrTopic = $new['group']->findAll('group_topic',array(
				'groupid'=>$groupid,
				'isaudit'=>1,
			));

			$title = 'Модерация';
			include template('audit');

			break;

		case "do":

			$topicid = intval($_GET['topicid']);

			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>'0',
			));

			$count_topic_audit = $new['group']->findCount('group_topic',array(
				'groupid'=>$groupid,
				'isaudit'=>'1',
			));

			$count_topic = $new['group']->findCount('group_topic',array(
				'groupid'=>$groupid,
			));

			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'count_topic'=>$count_topic,
				'count_topic_audit'=>$count_topic_audit,
			));

			tsNotice('Модерация прошла успешно!');

			break;

		//удалить
		case "delete":
			$topicid = intval($_GET['topicid']);

			$new['group']->delTopic($topicid,$groupid);

			$count_topic_audit = $new['group']->findCount('group_topic',array(
				'groupid'=>$groupid,
				'isaudit'=>'1',
			));

			$count_topic = $new['group']->findCount('group_topic',array(
				'groupid'=>$groupid,
			));

			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'count_topic'=>$count_topic,
				'count_topic_audit'=>$count_topic_audit,
			));

			tsNotice('Модерация прошла успешно!');

			tsNotice('Успешно удалено');

			break;

	}
}else{
	tsNotice('Недопустимая операция!');
}
