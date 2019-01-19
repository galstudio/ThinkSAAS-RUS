<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		include 'userinfo.php';
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('user','follow',array('id'=>$strUser['userid'],'page'=>''));
		$lstart = $page*80-80;
		$arrUsers = $new['user']->findAll('user_follow',array(
			'userid'=>$strUser['userid'],
		),'addtime desc',null,$lstart.',80');
		$userNum = $new['user']->findCount('user_follow',array(
			'userid'=>$strUser['userid'],
		));
		$pageUrl = pagination($userNum, 80, $page, $url);
		if(is_array($arrUsers)){
			foreach($arrUsers as $item){
				$arrUser[] =  $new['user']->getOneUser($item['userid_follow']);
			}
		}
		$title = 'Подписки '.$strUser['username'];
		include template("follow");

		break;

	case "do":
		$userid = intval($TS_USER['userid']);
		$userid_follow = intval($_GET['userid']);
		if($userid == 0){
			echo json_encode(array(
				'status'=>0,
				'msg'=>'Вы еще не вошли в систему!',
			));
			exit;
		}
		if($userid == $userid_follow){
			echo json_encode(array(
				'status'=>0,
				'msg'=>'Вы не можете подписаться на себя!',
			));
			exit;
		}
		$isFollow = $new['user']->findCount('user_follow',array(
			'userid'=>$userid,
			'userid_follow'=>$userid_follow,
		));
		if($isFollow>0){
			echo json_encode(array(
				'status'=>1,
				'msg'=>'Вы уже подписаны на этого пользователя!',
			));
			exit;
		}
		$new['user']->create('user_follow',array(
			'userid'=>$userid,
			'userid_follow'=>$userid_follow,
		));
		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
		$followUser = $new['user']->find('user_info',array(
			'userid'=>$userid_follow,
		));
		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'count_follow'=>$strUser['count_follow']+1,
		));
		$new['user']->update('user_info',array(
			'userid'=>$userid_follow,
		),array(
			'count_followed'=>$followUser['count_followed']+1,
		));
		echo json_encode(array(
			'status'=>2,
			'msg'=>'Успешно!',
		));
		exit;
		break;

	case "un":
		$userid = intval($TS_USER['userid']);
		$userid_follow = intval($_GET['userid']);
		if($userid == 0){
			echo json_encode(array(
				'status'=>0,
				'msg'=>'Вы еще не вошли в систему!',
			));
			exit;
		}
		$new['user']->delete('user_follow',array(
			'userid'=>$userid,
			'userid_follow'=>$userid_follow,
		));
		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
		$followUser = $new['user']->find('user_info',array(
			'userid'=>$userid_follow,
		));
		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'count_follow'=>$strUser['count_follow']-1,
		));
		$new['user']->update('user_info',array(
			'userid'=>$userid_follow,
		),array(
			'count_followed'=>$followUser['count_followed']-1,
		));
		echo json_encode(array(
			'status'=>1,
			'msg'=>'Вы успешно отписались!',
		));
		exit;
		break;
}
