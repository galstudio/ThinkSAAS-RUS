<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	//список
	case "list":
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=group&ts=list&page=';
		$lstart = $page*10-10;
		$arrGroup = $db->fetch_all_assoc("select * from ".dbprefix."group order by addtime desc limit $lstart,10");
		$groupNum = $db->once_num_rows("select * from ".dbprefix."group");
		if(is_array($arrGroup)){
			foreach($arrGroup as $key=>$item){
				$arrAllGroup[] = $item;
				$arrAllGroup[$key]['groupdesc'] = cututf8($item['groupdesc'],0,40);
			}
		}
		$pageUrl = pagination($groupNum, 10, $page, $url);

		include template("admin/group_list");

		break;


    //рекомендовать
    case "recommend":

        $arrGroup = $new['group']->findAll('group',array(
            'isrecommend'=>1,
        ),'orderid asc','groupid,orderid,groupname,isrecommend');


        include template("admin/group_recommend");

        break;


    case "orderid":

        $arrGroupid = $_POST['groupid'];
        $arrOrderid = $_POST['orderid'];

        foreach($arrGroupid as $key=>$item){
            $new['group']->update('group',array(
                'groupid'=>intval($item)
            ),array(
                'orderid'=>intval($arrOrderid[$key])
            ));
        }

        qiMsg('Успешно изменено!');

        break;


	//правка
	case "edit":
		$groupid = intval($_GET['groupid']);
		$arrGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='$groupid'");
		include template("admin/group_edit");
		break;

	case "editdo":
		$groupid = intval($_POST['groupid']);

		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'userid'			=> intval($_POST['userid']),
		));

		qiMsg("小组信息修改成功！");
		break;

	//удалить
	case "del":
		$groupid = intval($_GET['groupid']);

		if($groupid == 1){
			qiMsg("Группа по-умолчанию не может быть удалена!");
		}

		$topicNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group_topic where `groupid`='$groupid'");

		if($topicNum['count(*)'] > 0){
			qiMsg("В группе есть записи, которые нельзя удалять!");
		}

		$db->query("DELETE FROM ".dbprefix."group WHERE groupid = '$groupid'");

		$db->query("DELETE FROM ".dbprefix."group_user WHERE groupid = '$groupid'");

		qiMsg("Группа была успешно удалена!");

		break;

	//модерация
	case "isaudit":

		$groupid = intval($_GET['groupid']);

		$strGroup = $db->once_fetch_assoc("select groupid,userid,groupname,isaudit from ".dbprefix."group where groupid='$groupid'");

        if($strGroup['isaudit']){

            $db->query("update ".dbprefix."group set `isaudit`='0' where groupid='$groupid'");

            //системное сообщение (модерация пройдена)
            $msg_userid = '0';
            $msg_touserid = $strGroup['userid'];
            $msg_content = 'Поздравляем, заявка поданная в группу «'.$strGroup['groupname'].'» была одобрена!';
            $msg_tourl = tsUrl('group','show',array('id'=>$groupid));
            aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);

        }else{

            $db->query("update ".dbprefix."group set `isaudit`='1' where groupid='$groupid'");

        }



		qiMsg("Операция удачно выполнена!");

		break;

	//рекомендация
	case "isrecommend":
		$groupid = intval($_GET['groupid']);

		$strGroup = $db->once_fetch_assoc("select groupid,userid,groupname,isrecommend from ".dbprefix."group where groupid='$groupid'");

		if($strGroup['isrecommend'] == 0){
			$db->query("update ".dbprefix."group set `isrecommend`='1' where groupid='$groupid'");

			//системное сообщение (модерация пройдена)
			$msg_userid = '0';
			$msg_touserid = $strGroup['userid'];
			$msg_content = 'Поздравляем, ваша группа «'.$strGroup['groupname'].'» рекомендована! Можете проверить.';
            $msg_tourl = tsUrl('group','show',array('id'=>$groupid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);

		}else{

			$db->query("update ".dbprefix."group set `isrecommend`='0' where groupid='$groupid'");

		}

		qiMsg("Операция выполнена удачно!");

		break;
}
