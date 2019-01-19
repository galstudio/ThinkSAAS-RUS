<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "list":

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=topic&ts=list&page=';
		$lstart = $page*10-10;

		$arrTopic = $new['group']->findAll('group_topic',null,'addtime desc',null,$lstart.',10');

		$topicNum = $new['group']->findCount('group_topic');

		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_list");

		break;

	case "delete":
		$topicid = intval($_GET['topicid']);
		$groupid = intval($_GET['groupid']);

		$new['group']->delTopic($topicid,$groupid);

		qiMsg('Запись успешно удалена!');
		break;

	//модерация
	case "isaudit":

		$topicid = intval($_GET['topicid']);

		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));

		if($strTopic['isaudit']==0){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>1,
			));
		}

		if($strTopic['isaudit']==1){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>0,
			));
		}

		qiMsg('Операция успешно выполнена!');

		break;

	//удаление
	case "deletetopic":

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=topic&ts=deletetopic&page=';
		$lstart = $page*10-10;

		$arrTopic = $new['group']->findAll('group_topic',array('isdelete'=>'1'),'addtime desc',null,$lstart.',10');

		$topicNum = $new['group']->findCount('group_topic',array(
			'isdelete'=>'1',
		));

		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_delete");

		break;

	//правка
	case "edittopic":

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=topic&ts=edittopic&page=';
		$lstart = $page*10-10;

		$arrTopic = $new['group']->findAll('group_topic_edit',null,'addtime desc',null,$lstart.',10');

		$topicNum = $new['group']->findCount('group_topic_edit');

		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_edit");

		break;

	//обновление
	case "update":

		$topicid = intval($_GET['topicid']);

		$strTopic = $new['group']->find('group_topic_edit',array(
			'topicid'=>$topicid,
		));

		$new['group']->update('group_topic',array(
			'topicid'=>$topicid,
		),array(
			'title'=>$strTopic['title'],
			'content'=>$strTopic['content'],
		));

		$new['group']->update('group_topic_edit',array(
			'topicid'=>$topicid,
		),array(
			'isupdate'=>1,
		));

		qiMsg('Обновление успешно выполнено!');

		break;

	//просмотр изменений
	case "editview":
		$topicid = intval($_GET['topicid']);

		$strTopic = $new['group']->find('group_topic_edit',array(
			'topicid'=>$topicid,
		));

		include template('admin/topic_edit_view');
		break;

}
