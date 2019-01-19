<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		$userid = aac('user')->isLogin();
		$albumid = intval($_GET['albumid']);
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		$strAlbum['albumname'] = stripslashes($strAlbum['albumname']);
		$strAlbum['albumdesc'] = stripslashes($strAlbum['albumdesc']);
		if($userid != $strAlbum['userid']) {
			tsNotice('Недопустимая операция!');
		}
		$addtime = time();
		$title = 'Загрузка изображения';
		include template("upload");
		break;
	case "do":
        $userid = aac('user')->isLogin();
		$albumid = intval($_POST['albumid']);
		$addtime = intval($_POST['addtime']);
		if($albumid==0){
		    getJson('Недопустимая операция 1!');
        }
        if($addtime==0){
            getJson('Неверное время загрузки!');
        }
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		if($strAlbum==''){
            getJson('Недопустимая операция 2!');
        }
        if($strAlbum['userid']!=$userid){
            getJson('Недопустимая операция 3!');
        }
        $type = getImagetype($_FILES['file']['tmp_name']);
        if(!in_array($type,array('jpg','gif','png','jpeg'))){
            getJson('Недопустимая операция 4!');
        }
		$photoid = $new['photo']->create('photo',array(
			'albumid'=>$strAlbum['albumid'],
			'userid'=>$strAlbum['userid'],
			'locationid'=>aac('user')->getLocationId($strAlbum['userid']),
			'addtime'	=> date('Y-m-d H:i:s',$addtime),
		));
		//
		$arrUpload = tsUpload($_FILES['file'],$photoid,'photo',array('jpg','gif','png','jpeg'));
		if($arrUpload && $arrUpload['path'] && $arrUpload['url']){
			$new['photo']->update('photo',array(
				'photoid'=>$photoid,
			),array(
				'photoname'=>$arrUpload['name'],
				'phototype'=>$arrUpload['type'],
				'path'=>$arrUpload['path'],
				'photourl'=>$arrUpload['url'],
				'photosize'=>$arrUpload['size'],
			));
            $count_photo = $new['photo']->findCount('photo',array(
                'albumid'=>$albumid,
            ));
            $new['photo']->update('photo_album',array(
                'albumid'=>$albumid,
            ),array(
                'count_photo'=>$count_photo
            ));
			//
			aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts'],$strAlbum['userid']);
		}else{
		    $new['photo']->delete('photo',array(
		        'photoid'=>$photoid,
            ));
        }
		#echo $photoid;
        getJson('Изображение успешно загружено!');
		break;
}
