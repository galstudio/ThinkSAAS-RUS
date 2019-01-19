<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		$albumid = intval($_GET['id']);
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		//404
		if($strAlbum==''){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			$title = '404';
			include pubTemplate("404");
			exit;
		}
		if($strAlbum['isaudit']==1){
			tsNotice('Просмотр…');
		}
		$strAlbum['albumname'] = tsTitle($strAlbum['albumname']);
		$strAlbum['albumdesc'] = tsTitle($strAlbum['albumdesc']);
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('photo','album',array('id'=>$albumid,'page'=>''));
		$lstart = $page*20-20;
		$strUser = aac('user')->getOneUser($strAlbum['userid']);
		$arrPhoto = $new['photo']->findAll('photo',array(
			'albumid'=>$albumid,
		),'photoid desc',null,$lstart.',20');
		foreach($arrPhoto as $key=>$item){
			$arrPhoto[$key]['photodesc'] = tsTitle($item['photodesc']);
		}
		$photoNum = $new['photo']->findCount('photo',array(
			'albumid'=>$albumid,
		));
		$pageUrl = pagination($photoNum, 20, $page, $url);
		$title = $strAlbum['albumname'];
		include template("album");
		$new['photo']->update('photo_album',array(
			'albumid'=>$strAlbum['albumid'],
		),array(
			'count_view'=>$strAlbum['count_view']+1,
		));
		break;
	//
	case "edit":
		//
		$userid = aac('user')->isLogin();
		$albumid = intval($_GET['albumid']);
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		if($strAlbum['userid'] == $userid || $TS_USER['isadmin']==1) {
            $strAlbum['albumname'] = tsTitle($strAlbum['albumname']);
            $strAlbum['albumdesc'] = tsTitle($strAlbum['albumdesc']);
			$title = 'Правка альбома «'.$strAlbum['albumname'].'»';
			include template("album_edit");
		}else{
			tsNotice('Недопустимая операция!');
		}
		break;
	case "editdo":
		//
		$userid = aac('user')->isLogin();
		$albumid = intval($_POST['albumid']);
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		if($strAlbum['userid']==$userid || $TS_USER['isadmin']==1){
			$albumname = trim($_POST['albumname']);
			if($albumname == '') qiMsg("Название альбома не может быть пустым!");
			$albumdesc = trim($_POST['albumdesc']);
			if($TS_USER['isadmin']==0){
				//
				aac('system')->antiWord($albumname);
				aac('system')->antiWord($albumdesc);
				//
			}
			$new['photo']->update('photo_album',array(
				'userid'=>$strAlbum['userid'],
				'albumid'=>$strAlbum['albumid'],
			),array(
				'albumname'=>$albumname,
				'albumdesc'=>$albumdesc,
			));
			header("Location: ".tsUrl('photo','album',array('id'=>$albumid)));
		}else{
			tsNotice('Недопустимая операция!');
		}
		break;
	//
	case "info":
		//
		$userid = aac('user')->isLogin();
		$albumid = intval($_GET['albumid']);
		$addtime = intval($_GET['addtime']);
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
        $strAlbum['albumname'] = tsTitle($strAlbum['albumname']);
        $strAlbum['albumdesc'] = tsTitle($strAlbum['albumdesc']);
		if($strAlbum['userid'] != $userid) {
			tsNotice('Недопустимая операция!');
		}
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
			$strPhoto = $new['photo']->find('photo',array(
				'albumid'=>$strAlbum['albumid'],
			));
			$new['photo']->update('photo_album',array(
				'albumid'=>$albumid,
			),array(
				'path'=>$strPhoto['path'],
				'albumface'=>$strPhoto['photourl'],
			));
		}
		if($addtime){
			$arr = array(
				'albumid'=>$albumid,
				'addtime'=>date('Y-m-d H:i:s',$addtime),
			);
		}else{
			$arr = array(
				'albumid'=>$albumid,
			);
		}
		$arrPhoto = $new['photo']->findAll('photo',$arr);
		foreach($arrPhoto as $key=>$item){
			$arrPhoto[$key]['photoname'] = tsTitle($item['photoname']);
			$arrPhoto[$key]['photodesc'] = tsTitle($item['photodesc']);
		}
		$title = 'Правка «'.$strAlbum['albumname'].'»';
		include template("album_info");
		break;
	//
	case "info_do":
		//
		$userid = aac('user')->isLogin();
		$albumid = intval($_POST['albumid']);
		$albumface = intval($_POST['albumface']);
		$arrPhotoId = $_POST['photoid'];
		$arrPhotoDesc = $_POST['photodesc'];
        if(is_array($arrPhotoId)==false || is_array($arrPhotoDesc)==false){
            tsNotice('Недопустимая операция!');
        }
		if($TS_USER['isadmin']==0){
			foreach($arrPhotoDesc as $key=>$item){
				//
				aac('system')->antiWord($item);
				//
			}
		}
		foreach($arrPhotoDesc as $key=>$item){
            $item = str_replace('../','',$item);
            $item = str_replace('/','',$item);
			if($item){
				$photoid = intval($arrPhotoId[$key]);
				$new['photo']->update('photo',array(
					'photoid'=>$photoid,
				),array(
					'photodesc'=>trim($item),
				));
			}
		}
		//
        if (preg_match('#(..(\\|/)){2,}#sim', $albumface) != false) { die('request error');}
		if($albumface){
            $strPhoto = $new['photo']->find('photo',array(
                'photoid'=>$albumface,
            ));
			$new['photo']->update('photo_album',array(
				'userid'=>$userid,
				'albumid'=>$albumid,
			),array(
                'path'=>$strPhoto['path'],
				'albumface'=>$strPhoto['photourl'],
			));
		}
		header("Location: ".tsUrl('photo','album',array('id'=>$albumid)));
		break;
	//
	case "del":
		//
		$userid = aac('user')->isLogin();
		$albumid = intval($_GET['albumid']);
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		if($strAlbum['userid'] == $userid || $TS_USER['isadmin'] == 1) {
			$new['photo']->deletePhotoAlbum($strAlbum['albumid']);
		}
		header("Location: ".tsUrl('photo'));
		break;
}
