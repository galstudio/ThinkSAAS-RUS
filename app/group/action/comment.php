<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin($js,$userkey);
switch($ts){

    /**
     *
     * index.php?app=group&ac=comment&ts=do&js=1
     * post
     * @userkey
     * @topicid
     * @content
     * @ispublic
	 */
	case "do":

		$authcode = strtolower($_POST['authcode']);

		if ($TS_SITE ['isauthcode']) {
			if ($authcode != $_SESSION ['verify']) {
				getJson ( "Код подтверждения введен неправильно, пожалуйста, введите его заново!" ,$js,0);
			}
		}

		$topicid	= intval($_POST['topicid']);
		$content	= tsClean($_POST['content']);

        $ispublic = intval($_POST['ispublic']);

		if($TS_USER['isadmin']==0){
			aac('system')->antiWord($content,$js);
		}

		if($content==''){
			getJson('Необходимо хоть что-то написать! ',$js);
		}else{
			$commentid = $new['group']->create('group_topic_comment',array(
				'topicid'	=> $topicid,
				'userid'	=> $userid,
				'content'	=> $content,
                'ispublic'=>$ispublic,
				'addtime'=> time(),
			));

			$count_comment = $new['group']->findCount('group_topic_comment',array(
				'topicid'=>$topicid,
			));

			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'count_comment'=>$count_comment,
				'uptime'=>time(),
			));

			aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts']);

			$strTopic = $new['group']->find('group_topic',array(
				'topicid'=>$topicid,
			));

			if($strTopic['userid'] != $TS_USER['userid']){

				$msg_userid = '0';
				$msg_touserid = $strTopic['userid'];
				$msg_content = 'К вашей записи «'.$strTopic['title'].'» оставлен комментарий, проверьте его и напишите ответ';
                $msg_tourl = tsUrl('group','topic',array('id'=>$topicid));
				aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);

			}

			getJson('Комментарий добавлен ',$js,1,tsUrl('group','topic',array('id'=>$topicid)));

		}

		break;

    case "recomment":



        $referid = intval($_POST['referid']);
        $topicid = intval($_POST['topicid']);
        $content = tsClean($_POST['content']);

        $new['group']->create('group_topic_comment',array(
            'referid'=>$referid,
            'topicid'=>$topicid,
            'userid'=>$userid,
            'content'=>$content,
            'addtime'=>time(),
        ));

        $count_comment = $new['group']->findCount('group_topic_comment',array(
            'topicid'=>$topicid,
        ));

        $new['group']->update('group_topic',array(
            'topicid'=>$topicid,
        ),array(
            'count_comment'=>$count_comment,
            'uptime'=>time(),
        ));

        $strTopic = $new['group']->find('group_topic',array(
            'topicid'=>$topicid,
        ));

        $strComment = $new['group']->find('group_topic_comment',array(
            'commentid'=>$referid,
        ));

        if($topicid && $strTopic['userid'] != $TS_USER['userid']){
            $msg_userid = '0';
            $msg_touserid = $strTopic['userid'];
            $msg_content = 'К вашей записи «'.$strTopic['title'].'» оставлен комментарий, проверьте его и напишите ответ';
            $msg_tourl = tsUrl('group','topic',array('id'=>$topicid));
            aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);
        }

        if($referid && $strComment['userid'] != $TS_USER['userid']){
            $msg_userid = '0';
            $msg_touserid = $strComment['userid'];
            $msg_content = 'Кто-то прокомментировал вашу запись «'.$strTopic['title'].'» перейдите и прочитайте';
            $msg_tourl = tsUrl('group','topic',array('id'=>$topicid));
            aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);
        }

        echo 0;exit;

        break;



	//удалить
	case "delete":

		$commentid = intval($_GET['commentid']);

		$strComment = $new['group']->find('group_topic_comment',array(
			'commentid'=>$commentid,
		));

		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$strComment['topicid'],
		));

		$strGroup = $new['group']->find('group',array(
			'groupid'=>$strTopic['groupid'],
		));

		if($strTopic['userid']==$userid || $strGroup['userid']==$userid || $TS_USER['isadmin']==1 || $strComment['userid']==$userid){

			$new['group']->delComment($commentid);

			$count_comment = $new['group']->findCount('group_topic_comment',array(
				'topicid'=>$strTopic['topicid'],
			));

			$new['group']->update('group_topic',array(
				'topicid'=>$strTopic['topicid'],
			),array(
				'count_comment'=>$count_comment,
			));

            aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts'],$strComment['userid']);
		}

		header("Location: ".tsUrl('group','topic',array('id'=>$strComment['topicid'])));

		break;
}
