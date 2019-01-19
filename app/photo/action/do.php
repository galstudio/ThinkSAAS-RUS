<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "photo_del":
		$userid = aac('user')->isLogin();
		$photoid = intval($_GET['photoid']);
		$strPhoto = $new['photo']->find('photo',array(
			'photoid'=>$photoid,
		));
		if($strPhoto['userid']==$userid || $TS_USER['isadmin']==1) {
			$albumid = $strPhoto['albumid'];
			unlink('uploadfile/photo/'.$strPhoto['photourl']);
			$new['photo']->delete('photo',array(
				'photoid'=>$photoid,
			));
			$count_photo = $new['photo']->findCount('photo',array(
				'albumid'=>$albumid,
			));
			$new['photo']->update('photo_album',array(
				'albumid'=>$albumid,
			),array(
				'count_photo'=>$count_photo,
			));
			tsNotice('Изображение было успешно удалено!','Вернуться назад ',tsUrl('photo','album',array('id'=>$albumid)));
		}
		break;
	//
	case "comment_do":
		//
		$userid = aac('user')->isLogin();
		$photoid	= intval($_POST['photoid']);
		$content	= trim($_POST['content']);
		if($content==''){
		    tsNotice('Комментарий не может быть пустым!');
        }
		if($TS_USER['isadmin']==0){
			//
			aac('system')->antiWord($content);
			//
		}
		$commentid = $new['photo']->create('photo_comment',array(
			'photoid'			=> $photoid,
			'userid'			=> $userid,
			'content'	=> $content,
			'addtime'		=> time(),
		));
		header("Location: ".tsUrl('photo','show',array('id'=>$photoid)));
		break;
	//
	case "delcomment":
		//
		$userid = aac('user')->isLogin();
		$commentid = intval($_GET['commentid']);
		$strComment = $new['photo']->find('photo_comment',array(
			'commentid'=>$commentid,
		));
		$strTopic = $new['photo']->find('photo',array(
			'photoid'=>$strComment['photoid'],
		));
		if($userid == $strPhoto['userid'] || $TS_USER['isadmin']=='1'){
			$new['photo']->delete('photo_comment',array(
				'commentid'=>$commentid,
			));
			tsNotice("Комментарий успешно удален!");
		}else{
			tsNotice("Недопустимая операция!");
		}
		break;
}
