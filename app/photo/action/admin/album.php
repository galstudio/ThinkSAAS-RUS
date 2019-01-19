<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "list":
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$lstart = $page*10-10;
		$url = SITE_URL.'index.php?app=photo&ac=admin&mg=album&ts=list&page=';
		$arrAlbum = $new['photo']->findAll('photo_album',null,'albumid desc',null,$lstart.',10');
		$albumNum = $new['photo']->findCount('photo_album');
		$pageUrl = pagination($albumNum, 10, $page, $url);
		include template("admin/album_list");
		break;
	case "photo":
		$albumid = intval($_GET['albumid']);
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$lstart = $page*10-10;
		$url = SITE_URL.'index.php?app=photo&ac=admin&mg=album&ts=photo&albumid='.$albumid.'&page=';
		$arrPhoto = $db->fetch_all_assoc("select * from ".dbprefix."photo where albumid='$albumid' limit $lstart,10");
		$photo_num = $db->once_fetch_assoc("select count(photoid) from ".dbprefix."photo where albumid='$albumid'");
		$pageUrl = pagination($photo_num['count(photoid)'], 10, $page, $url);
		include template("admin/album_photo");
		break;
	case "del_album":
		$albumid = intval($_GET['albumid']);
		$db->query("delete from ".dbprefix."photo_album where albumid='$albumid'");
		$arrPhoto = $db->fetch_all_assoc("select * from ".dbprefix."photo where albumid='$albumid'");
		foreach($arrPhoto as $item){
			unlink('uploadfile/photo/'.$item['photourl']);
		}
		$db->query("delete from ".dbprefix."photo where albumid='$albumid'");
		qiMsg("Альбом был успешно удален!");
		break;
	case "del_photo":
		$photoid = intval($_GET['photoid']);
		$strPhoto = $db->once_fetch_assoc("select * from ".dbprefix."photo where photoid='$photoid'");
		$albumid = $strPhoto['albumid'];
		$db->query("delete from ".dbprefix."photo where photoid='$photoid'");
		unlink('uploadfile/photo/'.$strPhoto['photourl']);
		$count_photo = $db->once_num_rows("select * from ".dbprefix."photo where albumid='$albumid'");
		$db->query("update ".dbprefix."photo_album set `count_photo`='$count_photo' where albumid='$albumid'");
		qiMsg("Изображение успешно удалено!");
		break;
	case "face":
		$photoid = intval($_GET['photoid']);
		$strPhoto = $db->once_fetch_assoc("select * from ".dbprefix."photo where photoid='$photoid'");
		$albumid = $strPhoto['albumid'];
		$albumface = $strPhoto['photourl'];
		$db->query("update ".dbprefix."photo_album set `albumface`='$albumface' where albumid='$albumid'");
		qiMsg("Обложка успешно установлена!");
		break;
	case "count":
		$arrAlbum = $db->fetch_all_assoc("select albumid from ".dbprefix."photo_album");
		foreach($arrAlbum as $item){
			$albumid = $item['albumid'];
			$count_photo = $db->once_num_rows("select photoid from ".dbprefix."photo where albumid='$albumid'");
			$db->query("update ".dbprefix."photo_album set `count_photo`='$count_photo' where albumid='$albumid'");
		}
		qiMsg("Статистика успешно пересчитана!");
		break;
	case "isrecommend":
		$albumid = intval($_GET['albumid']);
		$strAlbum = $db->once_fetch_assoc("select isrecommend from ".dbprefix."photo_album where `albumid`='$albumid'");
		if($strAlbum['isrecommend']==0){
			$db->query("update ".dbprefix."photo_album set `isrecommend`='1' where `albumid`='$albumid'");
		}else{
			$db->query("update ".dbprefix."photo_album set `isrecommend`='0' where `albumid`='$albumid'");
		}
		qiMsg("Альбом успешно рекомендован!");
		break;
    case "isaudit":
        $albumid = intval($_GET['albumid']);
        $strAlbum = $new['photo']->find('photo_album',array(
            'albumid'=>$albumid,
        ));
        if($strAlbum['isaudit']==0){
            $new['photo']->update('photo_album',array(
                'albumid'=>$albumid,
            ),array(
                'isaudit'=>1,
            ));
        }else{
            $new['photo']->update('photo_album',array(
                'albumid'=>$albumid,
            ),array(
                'isaudit'=>0,
            ));
        }
        qiMsg("Успешно проверено!");
        break;
	case "nophoto":
		$arrAlbum = $new['photo']->findAll('photo_album',"`count_photo`=0");
		foreach($arrAlbum as $key=>$item){
			$isPhoto = $new['photo']->findCount('photo',array(
				'albumid'=>$item['albumid'],
			));
			if($isPhoto == 0){
				$new['photo']->delete('photo_album',array(
					'albumid'=>$item['albumid'],
				));
			}else{
				$count_photo = $new['photo']->findCount('photo',array(
					'albumid'=>$item['albumid'],
				));
				$new['photo']->update('photo_album',array(
					'albumid'=>$item['albumid'],
				),array(
					'count_photo'=>$count_photo,
				));
			}
		}
		qiMsg('Пустые альбомы удалены!');
		break;
	case "isaudit":
		$albumid = intval($_GET['albumid']);
		$strAlbum = $new['attach']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		if($strAlbum['isaudit']==1){
			$new['attach']->update('photo_album',array(
				'albumid'=>$albumid,
			),array(
				'isaudit'=>0
			));
		}
		if($strAlbum['isaudit']==0){
			$new['attach']->update('photo_album',array(
				'albumid'=>$albumid,
			),array(
				'isaudit'=>1
			));
		}
		qiMsg('Операция выполнена!');
		break;
}
