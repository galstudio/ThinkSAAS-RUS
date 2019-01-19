<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['userid']);

if($userid==0){
	return false;
	exit;
}

switch($ts){

	case "upload":
		include template("ajax/upload");
		break;
	//
	case "net":
		include template("ajax/net");
		break;
	//Flash
	case "flash":
		$albumid = intval($_GET['albumid']);
		$addtime = time();
		include template("ajax/flash");
		break;
	//
	case "album":
		$isAlbum = $new['photo']->findCount('photo_album',array(
			'userid'=>$userid,
		));
		if($isAlbum == 0){
			$new['photo']->create('photo_album',array(
				'userid'=>$userid,
				'albumname'=>'Название альбома',
				'albumdesc'=>'Описание альбома',
				'addtime'=>time(),
				'uptime'=>time(),
			));
		}
		$arrAlbum = $new['photo']->findAll('photo_album',array(
			'userid'=>$userid,
		));
		include template("ajax/album");
		break;
	//
	case "photo":
		$albumid = intval($_GET['albumid']);
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = SITE_URL."index.php?app=photo&ac=ajax&ts=photo&albumid=".$albumid."&page=";
		$lstart = $page*6-6;
		$arrPhoto = $new['photo']->findAll('photo',array(
			'albumid'=>$albumid,
		),'photoid desc',null,$lstart.',6');
		$photoNum = $new['photo']->findCount('photo',array(
			'albumid'=>$albumid,
		));
		$pageUrl = pagination($photoNum, 6, $page, $url);
		include template("ajax/photo");
		break;
	//
	case "create":
		include template("ajax/create");
		break;
	case "create_do":
		$albumname = t($_POST['albumname']);
		if($albumname == '') qiMsg("Название альбома не может быть пустым!");
		$albumdesc = h($_POST['albumdesc']);
		$addtime = time();
		$uptime = time();
		$albumid = $new['photo']->create('photo_album',array(
			'userid'=>$userid,
			'albumname'=>$albumname,
			'albumdesc'=>$albumdesc,
			'addtime'=>time(),
			'uptime'=>time(),
		));
		header("Location: ".SITE_URL."index.php?app=photo&ac=ajax&ts=flash&albumid=".$albumid);
		break;
	//
	case "info":
		$albumid = intval($_GET['albumid']);
		$addtime = intval($_GET['addtime']);
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		if($strAlbum['userid'] != $userid) qiMsg("Недопустимая операция!");
		//
		$count_photo = $new['photo']->findCount('photo',array(
			'albumid'=>$albumid,
		));
		$new['photo']->update('photo_album',array(
			'albumid'=>$albumid,
		),array(
			'count_photo'=>$count_photo,
		));
		//
		if($strAlbum['albumface'] == ''){
			$strPhoto = $new['photo']->find('photo',"`albumid`='$albumid' and `userid`='$userid' and `addtime`>'$addtime'");
			$new['photo']->update('photo_album',array(
				'albumid'=>$albumid,
			),array(
				'albumface'=>$strPhoto['photourl'],
			));
		}
		$arrPhoto = $new['photo']->findAll('photo',"`albumid`='$albumid' and  `userid`='$userid' and `addtime`>'$addtime'");
		include template("ajax/info");
		break;
}
