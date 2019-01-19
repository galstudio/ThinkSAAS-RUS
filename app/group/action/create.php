<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

if(aac('user')->isPublisher()==false) tsNotice('Извините, но у вас нет разрешения на публикацию!');


switch($ts){

	case "":

		$userGroupNum = $new['group']->findCount('group_user',array(
			'userid'=>$userid
		));

		if($userGroupNum >= $TS_APP['joinnum'] && $TS_USER['isadmin']==0){
			tsNotice('Общее количество групп, к которым вы присоединились '.$TS_APP['joinnum'].', поэтому вы не можете создать группу!');
		}

		if($TS_APP['iscreate'] == 0 || $TS_USER['isadmin']==1){

			$arrCate = $new['group']->findAll('group_cate',array(

				'referid'=>0,

			));

			$title = 'Создание группы';

			include template("create");

		}else{

			tsNotice('Участникам нельзя создавать группы!');

		}
		break;

	case "do":

        $userGroupNum = $new['group']->findCount('group_user',array(
            'userid'=>$userid
        ));

        if($userGroupNum >= $TS_APP['joinnum'] && $TS_USER['isadmin']==0){
            tsNotice('Общее количество групп, к которым вы присоединились '.$TS_APP['joinnum'].', поэтому вы не можете создать группу!');
        }

		if($TS_APP['iscreate'] == 0 || $TS_USER['isadmin']==1){

			$groupname = trim($_POST['groupname']);
			$groupdesc = trim($_POST['groupdesc']);

			if($groupname=='' || $groupdesc=='') {
				tsNotice('Название и описание группы не могут быть пустыми!');
			}

			if($TS_USER['isadmin']!=1){
				aac('system')->antiWord($groupname);
				aac('system')->antiWord($groupdesc);
			}

			$isaudit = intval($TS_APP['isaudit']);
			if($TS_USER['isadmin']==1){
				$isaudit = 0;
			}

			$isGroup = $new['group']->findCount('group',array(
				'groupname'=>$groupname,
			));

			if($isGroup > 0) {
				tsNotice("Такое название группы уже существует. Пожалуйста, измените его!");
			}

			$groupid = $new['group']->create('group',array(
				'userid'	=> $userid,
				'groupname'	=> $groupname,
				'groupdesc'	=> $groupdesc,
				'isaudit'	=> $isaudit,
				'addtime'	=> time(),
			));

			$arrUpload = tsUpload($_FILES['photo'],$groupid,'group',array('jpg','gif','png','jpeg'));

			if($arrUpload){

				$new['group']->update('group',array(
					'groupid'=>$groupid,
				),array(
					'path'=>$arrUpload['path'],
					'photo'=>$arrUpload['url'],
				));
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

			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'count_user'=>1,
			));

			$cateid = intval($_POST['cateid']);
			if($cateid > 0){
				$count_group = $new['group']->findCount('group',array(
					'cateid'=>$cateid,
				));

				$new['group']->update('group_cate',array(
					'cateid'=>$cateid,
				),array(
					'count_group'=>$count_group,
				));

			}

			aac ( 'tag' )->addTag ( 'group', 'groupid', $groupid, $_POST['tag'] );

            aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts']);

			header("Location: ".tsUrl('group','show',array('id'=>$groupid)));
		}
		break;

}
