<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "topicaudit":

		$topicid = intval($_POST['topicid']);

		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));

		if($strTopic['isaudit']==0){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>1,
			));

			echo 0;exit;

		}

		if($strTopic['isaudit']==1){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>0,
			));

			echo 1;exit;

		}

		break;

	case "joingroup":

		$userid = intval($TS_USER['userid']);

		$groupid = intval($_POST['groupid']);

		$strGroup = $new['group']->find('group',array(
			'groupid'=>$groupid
		));

		if($userid==0 || $groupid==0 || $strGroup==''){
			getJson('Вы должны сначало авторизоваться, прежде чем присоединиться к группе ',1,2,tsUrl('user','login'));
		}

		if($TS_USER['isadmin'] != 1){

			if($strGroup['joinway'] == 1) getJson('Эта группа закрыта для вступления!');

			if($strGroup['joinway'] == 2){
				$new['group']->replace('group_user_isaudit',array(
					'userid'=>$userid,
					'groupid'=>$strGroup['groupid'],
				),array(
					'userid'=>$userid,
					'groupid'=>$strGroup['groupid'],
				));

				getJson('Присоединитесь к группе, подождите, пока администратор не рассмотрит вашу заявку и не присоединит к группе.');

			}

			$userGroupNum = $new['group']->findCount('group_user',array('userid'=>$userid));

			if($userGroupNum >= $TS_APP['joinnum']) getJson('Общее количество групп, к которым вы присоединились, достигло '.$TS_APP['joinnum'].', вы не можете больше вступать в группы!');

			$groupUserNum = $new['group']->findCount('group_user',array(
				'userid'=>$userid,
				'groupid'=>$groupid,
			));

			if($groupUserNum > 0) getJson('Вы присоединились к группе!');

            if($TS_APP['ispayjoin']==1 && $strGroup['joinway']==3){

                $strUserPay = aac('pay')->getUserPay($userid);
                if($strUserPay['over']<$strGroup['price']){
                    getJson('У вас недостаточно средств, чтобы присоединиться к группе!');
                }
                aac('pay')->updatePay($userid,$strGroup['price'],1,' вступление в платную группу '.$strGroup['groupid']);

                aac('pay')->updatePay($strGroup['userid'],$strGroup['price'],0,' доступ к платной группе '.$strGroup['groupid']);
            }

		}

		$new['group']->create('group_user',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
			'addtime'=>time(),
		));

		$count_group = $new['group']->findCount('group_user',array(
			'userid'=>$userid,
		));
		$new['group']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'count_group'=>$count_group,
		));

		$count_user = $new['group']->findCount('group_user',array(
			'groupid'=>$groupid,
		));

		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'count_user'=>$count_user,
		));

		getJson('Вы успешно присоединились! ',1,1,tsUrl('group','show',array('id'=>$groupid)));

		break;

	case "exitgroup":

		$userid = intval($TS_USER['userid']);

		$groupid = intval($_POST['groupid']);

		$strGroup = $new['group']->find('group',array(
			'groupid'=>$groupid
		));

		if($userid==0 || $groupid==0 || $strGroup==''){
			getJson('Неверные действия');
		}

		if($strGroup['userid'] == $userid) getJson('Задача лидера трудна, держитесь до конца!');

		$new['group']->delete('group_user',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
		));

		$count_user = $new['group']->findCount('group_user',array(
			'groupid'=>$groupid,
		));

		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'count_user'=>$count_user,
		));

        getJson('Присоединились успешно! ',1,1,tsUrl('group','show',array('id'=>$groupid)));

		break;

	case "isrecommend":

		$js = intval($_GET['js']);

		$topicid = intval($_POST['topicid']);

		if($TS_USER['isadmin']==1 && $topicid){

			$strTopic = $new['group']->find('group_topic',array(
				'topicid'=>$topicid,
			));

			if($strTopic['isrecommend']==1){
				$new['group']->update('group_topic',array(
					'topicid'=>$topicid,
				),array(
					'isrecommend'=>0,
				));

				getJson('Рекомендация успешно отменена!',$js);

			}

			if($strTopic['isrecommend']==0){
				$new['group']->update('group_topic',array(
					'topicid'=>$topicid,
				),array(
					'isrecommend'=>1,
				));

				getJson('Успешно рекомендована',$js);

			}


		}else{

			getJson('Недопустимая операция',$js);

		}

		break;


    /**
     *
     */
    case "book":

        $userid = aac('user')->isLogin();
        $topicid = intval($_POST['topicid']);
        $book = trim($_POST['book']);

        if($topicid==0 || $book==''){
            echo 0;exit;
        }

        if($TS_USER['isadmin']==1){
            $new['group']->update('group_topic',array(
                'topicid'=>$topicid,
            ),array(
                'label'=>$book,
            ));
        }else{
            $new['group']->update('group_topic',array(
                'topicid'=>$topicid,
                'userid'=>$userid,
            ),array(
                'label'=>$book,
            ));
        }



        echo 1;exit;

        break;

}
