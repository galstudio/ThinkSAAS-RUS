<?php
defined('IN_TS') or die('Access Denied.');

$topicid = intval($_GET['topicid']);

switch($ts){
	case "ajax":

		$arrCollectUser = $db->fetch_all_assoc("select * from ".dbprefix."group_topic_collect where topicid='$topicid'");

		if(is_array($arrCollectUser)){
			foreach($arrCollectUser as $item){
				$strUser = aac('user')->getOneUser($item['userid']);
				$arrUser[] = $strUser;
			}
		}

		if($arrUser == ''){
			echo '<div style="color: #999999;margin-bottom: 10px;padding: 20px 0">Никто пока еще ничего не написал, будь первым!</div>';
		}else{
			include template("topic_collect");
		}

		break;
}
