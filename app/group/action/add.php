<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = aac ( 'user' )->isLogin ();

//определение статуса публикатора
if(aac('user')->isPublisher()==false) tsNotice('Извините, но у вас нет разрешения на публикацию!');


switch ($ts) {
	// публикация
	case "" :

		$groupid = intval ( $_GET ['id'] );
		// группы
		$groupNum = $new ['group']->findCount ( 'group', array (
				'groupid' => $groupid
		) );

		if ($groupNum == 0) {
			header ( "Location: " . SITE_URL );
			exit ();
		}

		// участники
		$isGroupUser = $new ['group']->findCount ( 'group_user', array (
				'userid' => $userid,
				'groupid' => $groupid
		) );

		$strGroup = $new ['group']->find ( 'group', array (
				'groupid' => $groupid
		) );
		$strGroup ['groupname'] = stripslashes ( $strGroup ['groupname'] );

		if ($strGroup ['isaudit'] == 1) {
			tsNotice ( 'Группа еще не прошла модерацию, и в неё еще нельзя добавлять записи!' );
		}

		if ($strGroup ['ispost'] == 0 && $isGroupUser == 0 && $userid != $strGroup ['userid']) {
			tsNotice ( "В этой группе только участники могут добавлять записи! Присоединяйтесь к группе и пишите сколько влезет!" );
		}

		if ($strGroup ['ispost'] == 1 && $userid != $strGroup ['userid']) {
			tsNotice ( "В этой группе только руководители могут добавлять записи!" );
		}

		$arrGroupType = $new ['group']->findAll ( 'group_topic_type', array (
				'groupid' => $strGroup ['groupid']
		) );

		$title = 'Добавление записи';

		include template ( "add" );

		break;

	case "do" :


		$authcode = strtolower ( $_POST ['authcode'] );

		if ($TS_SITE['isauthcode']) {
			if ($authcode != $_SESSION ['verify']) {
				tsNotice ( "Код подтверждения введен неверно, пожалуйста, введите его заново!" );
			}
		}

		$groupid = intval ( $_POST ['groupid'] );
		$title = trim( $_POST ['title'] );

		$content =  tsClean( $_POST ['content'] );

		$typeid = intval ( $_POST ['typeid'] );
		$tag = $_POST ['tag'];

		$isTitle = $new ['group']->findCount ( 'group_topic', array (
				'title' => $title
		) );

		if ($isTitle > 0) {
			tsNotice ( 'Это повторяющийся заголовок, измените его!' );
		}

		$strGroup = $new ['group']->find ( 'group', array (
				'groupid' => $groupid
		) );

		if ($strGroup ['isaudit'] == 1) {
			tsNotice ( 'Группа еще не прошла модерацию, в ней пока нельзя добавлять записи!' );
		}

		if ($TS_USER ['isadmin'] == 0) {
			aac ( 'system' )->antiWord ( $title );
			aac ( 'system' )->antiWord ( $content );
			aac ( 'system' )->antiWord ( $tag );
		}

		$iscomment = intval ( $_POST ['iscomment'] );
		$iscommentshow = intval ( $_POST ['iscommentshow'] );

		if ($strGroup ['ispostaudit'] == 1) {
			$isaudit = 1;
		} else {
			$isaudit = 0;
		}

		if ($title == '' || $content == '') {
			tsNotice ( 'Для публикации записи нужно хоть что-то написать!' );
		}

		/**
		 * ******************
		 */
		$strPreTopic = $new ['group']->find ( 'group_topic', array (
				'userid' => $userid
		), 'topicid,title,addtime', 'addtime desc' );

		// print_r($strPreTopic);exit;

        /*
		$IntervalTime = time () - $strPreTopic ['addtime'];
		// if($strPreTopic && $IntervalTime<3600){
		if ($strPreTopic) {
			similar_text ( $strPreTopic ['title'], $title, $percent );
			if ($percent >= 90) {
				$new ['group']->update ( 'group_topic', array (
						'topicid' => $strPreTopic ['topicid']
				), array (
						'isaudit' => 1
				) );
				$isaudit = 1;
			}
		}
        */



		/**
		 * *****************
		 */

		$topicid = $new ['group']->create ( 'group_topic', array (
				'groupid' => $groupid,
				'typeid' => $typeid,
				'userid' => $userid,
				'locationid'=>aac('user')->getLocationId($userid),
				'title' => $title,
				'content' => $content,
				'iscomment' => $iscomment,
				'iscommentshow' => $iscommentshow,
				'isaudit' => $isaudit,
				'addtime' => time (),
				'uptime' => time ()
		) );

		$countUserTopic = $new ['group']->findCount ( 'group_topic', array (
				'userid' => $userid
		) );

		$new ['group']->update ( 'user_info', array (
				'userid' => $userid
		), array (
				'count_topic' => $countUserTopic
		) );

		/*
		if (preg_match_all ( '/@/', $content, $at )) {
			preg_match_all ( "/@(.+?)([\s|:]|$)/is", $content, $matches );

			$unames = $matches [1];

			$ns = "'" . implode ( "','", $unames ) . "'";

			$csql = "username IN($ns)";

			if ($unames) {

				$query = $db->fetch_all_assoc ( "select userid,username from " . dbprefix . "user_info where $csql" );

				foreach ( $query as $v ) {
					$content = str_replace ( '@' . $v ['username'] . '', '[@' . $v ['username'] . ':' . $v ['userid'] . ']', $content );
					$msg_content = 'Я упомянул тебя в записи <br /> посмотри:' . tsUrl ( 'group', 'topic', array (
							'id' => $topicid
					) );
					aac ( 'message' )->sendmsg ( $userid, $v ['userid'], $msg_content );
				}
				$new ['group']->update ( 'group_topic', array (
						'topicid' => $topicid
				), array (
						'content' => $content
				) );
			}
		}
		*/

		if ($typeid) {
			$topicTypeNum = $new ['group']->findCount ( 'group_topic', array (
					'typeid' => $typeid
			) );

			$new ['group']->update ( 'group_topic_type', array (
					'typeid' => $typeid
			), array (
					'count_topic' => $topicTypeNum
			) );
		}

		aac ( 'tag' )->addTag ( 'topic', 'topicid', $topicid, $tag );

		$count_topic_audit = $new ['group']->findCount ( 'group_topic', array (
				'groupid' => $groupid,
				'isaudit' => '1'
		) );

		$count_topic = $new ['group']->findCount ( 'group_topic', array (
				'groupid' => $groupid
		) );

		$today_start = strtotime ( date ( 'Y-m-d 00:00:00' ) );
		$today_end = strtotime ( date ( 'Y-m-d 23:59:59' ) );

		$count_topic_today = $new ['group']->findCount ( 'group_topic', "`groupid`='$groupid' and `addtime`>'$today_start' and `addtime`<'$today_end'" );

		$new ['group']->update ( 'group', array (
				'groupid' => $groupid
		), array (
				'count_topic' => $count_topic,
				'count_topic_audit' => $count_topic_audit,
				'count_topic_today' => $count_topic_today,
				'uptime' => time ()
		) );

		aac ( 'user' )->doScore ( $TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'] );


		header ( "Location: " . tsUrl('group', 'topic', array ('id' => $topicid)));
		break;
}
