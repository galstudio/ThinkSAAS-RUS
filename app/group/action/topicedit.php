<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

if($TS_SITE['isallowedit'] && $TS_USER ['isadmin'] == 0) tsNotice('Правка пользователями записей отключена Свяжитесь с администратором!');

switch($ts){

	case "":
		$topicid = intval($_GET['topicid']);

		if($topicid == 0){
			header("Location: ".SITE_URL);
			exit;
		}

		$topicNum = $new['group']->findCount('group_topic',array(
			'topicid'=>$topicid,
		));

		if($topicNum==0){
			header("Location: ".SITE_URL);
			exit;
		}

		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));

		$strTopic['title'] = tsTitle($strTopic['title']);
		$strTopic['content'] = tsDecode($strTopic['content']);

		$strGroup = $new['group']->find('group',array(
			'groupid'=>$strTopic['groupid'],
		));

		$strGroupUser = $new['group']->find('group_user',array(
			'userid'=>$userid,
			'groupid'=>$strTopic['groupid'],
		));

		//print_r($strGroupUser);exit;

		if($strTopic['userid'] == $userid || $strGroup['userid']==$userid || $TS_USER['isadmin']==1 || $strGroupUser['isadmin']==1){
			$arrGroupType = $new['group']->findAll('group_topic_type',array(
				'groupid'=>$strGroup['groupid'],
			));

			//TAG
			$arrTags = aac('tag')->getObjTagByObjid('topic', 'topicid', $topicid);
			foreach($arrTags as $key=>$item){
				$arrTag[] = $item['tagname'];
			}
			$strTopic['tag'] = arr2str($arrTag);

			$title = 'Редактирование';
			include template("topic_edit");

		}else{

			header("Location: ".SITE_URL);
			exit;

		}
		break;

	case "do":

        $authcode = strtolower ( $_POST ['authcode'] );

        if ($TS_SITE['isauthcode']) {
            if ($authcode != $_SESSION ['verify']) {
                tsNotice ( "Код подтверждения введен неправильно, пожалуйста, введите заново!" );
            }
        }

		$topicid = intval($_POST['topicid']);
		$typeid = intval($_POST['typeid']);

		$title = trim($_POST['title']);

		//echo br2nl($_POST['content']);exit;

		$content = tsClean($_POST['content']);

		$iscomment = intval($_POST['iscomment']);
		$iscommentshow = intval($_POST['iscommentshow']);

		if($topicid == '' || $title=='' || $content=='') tsNotice("Не может быть пустым!");


		if($TS_USER['isadmin']==0){

			aac('system')->antiWord($title);
			aac('system')->antiWord($content);

		}

		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));

		$strGroup = $new['group']->find('group',array(
			'groupid'=>$strTopic['groupid'],
		));

		$strGroupUser = $new['group']->find('group_user',array(
			'userid'=>$userid,
			'groupid'=>$strTopic['groupid'],
		));

		if($strTopic['userid']==$userid || $strGroup['userid']==$userid || $TS_USER['isadmin']==1 || $strGroupUser['isadmin']==1){

			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'typeid' => $typeid,
				'title'=>$title,
				'content'=>$content,
				'iscomment' => $iscomment,
				'iscommentshow' => $iscommentshow,
			));

			$tag = trim($_POST['tag']);
			if($tag){
				aac('tag')->delIndextag('topic','topicid',$topicid);
				aac('tag') -> addTag('topic', 'topicid', $topicid, $tag);
			}

			header("Location: ".tsUrl('group','topic',array('id'=>$topicid)));

		}else{
			header("Location: ".SITE_URL);
			exit;
		}
		break;

}
