<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "base":
		$title = 'Настройки';
		include template("setting_base");
		break;

	case "basedo":

		$username = t($_POST['username']);
		$signed = t($_POST['signed']);
		$phone = t($_POST['phone']);
		$blog = t($_POST['blog']);
		$about = t($_POST['about']);
		$sex = t($_POST['sex']);

		if($TS_USER == '') {

			tsNotice("В личный кабинет доступ запрещен!");

		}
		if($username == '') {

			tsNotice("Необходимо указать ник!");

		}
		if(strlen($username) < 4 || strlen($username) > 20) {

			tsNotice("Длина ника должна быть от 4 до 20 символов!");

		}

		if($username != $strUser['username']){

			if($TS_APP['banuser']){

				$arrUserName = explode('|',$TS_APP['banuser']);
				if(in_array($username,$arrUserName)){
					tsNotice("Такой ник уже существует, пожалуйста, выберите другой!");
				}

			}

			$isUserName = $new['my']->findCount('user_info',array(
				'username'=>$username,
			));

			if($isUserName > 0) {

				tsNotice("Такой ник уже существует, пожалуйста, выберите другой!");

			}
		}

		if(intval($TS_USER['isadmin'])==0){
			//фильтр
			aac('system')->antiWord($username);
			aac('system')->antiWord($signed);
			aac('system')->antiWord($phone);
			//aac('system')->antiWord($blog);
			aac('system')->antiWord($about);
			//фильтр
		}


        //URL,Email
        /*
        if(filter_var($signed, FILTER_SANITIZE_URL) || filter_var($signed, FILTER_VALIDATE_EMAIL)){
            tsNotice('Подпись недопустима! Пожалуйста, измените и отправьте снова!');
        }

        if(filter_var($about, FILTER_SANITIZE_URL) || filter_var($about, FILTER_VALIDATE_EMAIL)){
            tsNotice('Информация о себе недопустима! Пожалуйста, измените и отправьте снова!');
        }
        */

		$new['my']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'username' => $username,
			'sex'	=> $sex,
			'signed'	=> $signed,
			'phone'	=> $phone,
			'about' => $about,
		));

		#session
        $_SESSION['tsuser']['username'] = $username;

		tsNotice("Основная информация была успешно обновлена!");

		break;


	case "face":

		$title = 'Настройка аватара';

		$arrFace = tsScanDir('uploadfile/user/face',1);

		include template("setting_face");

		break;
	case "facedo":

		if($_FILES['photo']){

			$arrUpload = tsUpload($_FILES['photo'],$userid,'user',array('jpg','gif','png','jpeg'));

			if($arrUpload){

				$new['my']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'path'=>$arrUpload['path'],
					'face'=>$arrUpload['url'],
				));

				$filesize=abs(filesize('uploadfile/user/'.$arrUpload['url']));
				if($filesize<=0){
					$new['my']->update('user_info',array(
						'userid'=>$userid,
					),array(
						'path'=>'',
						'face'=>'',
					));

					tsNotice('Загрузка аватара не удалась, вы можете использовать системный аватар по умолчанию!');

				}else{

					$_SESSION['tsuser']['face'] = $arrUpload['url'];
					$_SESSION['tsuser']['path'] = $arrUpload['path'];

					tsDimg($arrUpload['url'],'user','120','120',$arrUpload['path']);

					header('Location: '.tsUrl('my','setting',array('ts'=>'face')));
				}

			}else{
				tsNotice('Ошибка модификации аватара');
			}

		}

		break;

	case "pwd":

		$title = 'Изменение пароля';
		include template("setting_pwd");

		break;

	case "pwddo":

		$theUser = $new['my']->find('user',array(
			'userid'=>$strUser['userid'],
		));

		$oldpwd = trim($_POST['oldpwd']);
		$newpwd = trim($_POST['newpwd']);
		$renewpwd = trim($_POST['renewpwd']);

		if($oldpwd == '' || $newpwd=='' || $renewpwd=='') tsNotice("Все поля не могут быть пустыми!");

		if($newpwd != $renewpwd) tsNotice('Поля нового пароля не совпадают!');

		if(md5($theUser['salt'].$oldpwd) != $theUser['pwd']) tsNotice("Старый пароль был введен неправильно!");

		$salt = md5(rand());

		$new['my']->update('user',array(
			'userid'=>$strUser['userid'],
		),array(
			'pwd'=>md5($salt.$newpwd),
			'salt'=>$salt,
		));

		 tsNotice("Пароль был успешно изменен!");

		break;

	//修Email
	case "email":
		$title = 'Изменение Email';
		include template('setting_email');
		break;

	case "emaildo":

		$email = trim($_POST['email']);

		if($email=='') tsNotice('Email не может быть пустым!');

		if(valid_email($email) == false) tsNotice('Неверный ввод Email');

		if($email != $strUser['email']){
			$emailNum = $new['my']->findCount('user',array(
				'email'=>$email,
			));

			if($emailNum > 0) tsNotice("Учетная запись с таким Email уже существует!");

			//Email
			$new['my']->update('user',array(
				'userid'=>$strUser['userid'],
			),array(
				'email'=>$email,
			));

			$new['my']->update('user_info',array(
				'userid'=>$strUser['userid'],
			),array(
				'email'=>$email,
				'isverify'=>'0',
			));

			tsNotice('Изменение Email учетной записи прошло успешно! Используйте вместо догина '.$email.' для авторизации на сайте!');

		}else{
			tsNotice('Новый Email не может совпадать со старым!');
		}

		break;

	case "city":

		$title = 'Изменение адреса';
		include template("setting_city");
		break;

	case "citydo":

		$province = trim($_POST['province']);
		$city = trim($_POST['city']);


		$new['my']->update('user_info',array(
			'userid'=>$userid,
		),array(

			'province'=>$province,
			'city'=>$city,

		));

		tsNotice("Обновление адреса прошло успешно!");

		break;

	case "tag":

		$arrTag = aac('tag')->getObjTagByObjid('user','userid',$userid);

		$title = 'Изменение персональных тегов';
		include template("setting_tag");
		break;

	case "tagdo":
		break;

}
