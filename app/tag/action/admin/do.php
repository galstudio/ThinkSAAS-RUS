<?php
defined('IN_TS') or die('Access Denied.');

switch ($ts){
	case "adddo":
		$tag = trim($_POST['tag']);
		if($tag==''){
			qiMsg('Тег не может быть пустым!');
		}
		if($new['tag']->isTag($tag)==true) qiMsg('Тег уже существует, и добавлять его не нужно!');
		$new['tag']->create('tag',array(
			'tagname'=>$tag,
			'uptime'=>time(),
		));
		header('Location: '.SITE_URL.'index.php?app=tag&ac=admin&mg=list');
		break;

	case "isenable":
		$tagid = intval($_GET['tagid']);
		$isenable = intval($_GET['isenable']);
		$db->query("update ".dbprefix."tag set `isenable`='$isenable' where tagid = '$tagid'");
		qiMsg("Выполнено!");
		break;

	case "del":
		$tagid = intval($_GET['tagid']);
		$page = intval($_GET['page']);
		$new['tag']->delete('tag',array('tagid'=>$tagid));
		$new['tag']->delete('tag_article_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_bang_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_group_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_photo_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_study_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_topic_index',array('tagid'=>$tagid));
		$new['tag']->delete('tag_user_index',array('tagid'=>$tagid));
		header('Location: '.SITE_URL.'index.php?app=tag&ac=admin&mg=list&page='.$page);
		break;

	case "opt":
		$tagid = intval($_GET['tagid']);
		$strTag = $new['tag']->getOneTag($tagid);
		$tagname = t($strTag['tagname']);
		$tagNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."tag where `tagname`='$tagname'");
		if($tagNum['count(*)']==0){
			$db->query("update ".dbprefix."tag set `tagname`='$tagname' where `tagid`='$tagid'");
		}elseif($tagNum['count(*)']==1){
		}else{
			$arrTags = $db->fetch_all_assoc("select * from ".dbprefix."tag where `tagname`='$tagname'");
			foreach($arrTags as $item){
				$tagids = $item['tagid'];
				$db->query("update ".dbpreifx."tag_topic_index set `tagid`='$tagid' where `tagid`='$tagids'");
				$db->query("update ".dbpreifx."tag_article_index set `tagid`='$tagid' where `tagid`='$tagids'");
				$db->query("update ".dbpreifx."tag_user_index set `tagid`='$tagid' where `tagid`='$tagids'");
				$db->query("delete from ".dbprefix."tag where `tagid`='$tagids'");
				//tag
				$db->query("update ".dbprefix."tag set `tagname`='$tagname' where `tagid`='$tagid'");
			}
		}
		qiMsg("Успешно оптимизировано!");

		break;
}
