<?php
defined('IN_TS') or die('Access Denied.');
//проверка авторизации

switch($ts){
	case "":
		$userid = aac('user')->isLogin();
		$strUser = $new['user']->getOneUser($userid);
		$title = 'Подтверждение пользователя';
		include template('verify');
		break;
	//отправка подтверждения
	case "post":
		$userid = aac('user')->isLogin();
		$strUser = $new['user']->find('user_info',array(
			'userid'=>$userid,
		));
		if($strUser['verifycode']==''){
			$verifycode = random(11);
			$new['user']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'verifycode'=>$verifycode,
			));
		}else{
			$verifycode = $strUser['verifycode'];
		}

		$email = $strUser['email'];

		//сообщение
		$subject = 'Проверка подлинности пользователя '.$TS_SITE['site_title'];
		$content = 'Уважаемый '.$strUser['username'].'，<br />Пожалуйста, перейдите по ссылке, для подтверждения своего email: <a href="'.$TS_SITE['link_url'].'index.php?app=user&ac=verify&ts=do&email='.$email.'&verifycode='.$verifycode.'">подтвердить подлинность Email</a>';

		$result = aac('mail')->postMail($email,$subject,$content);

		if($result == '0'){
			tsNotice("Проверка не засчитана, возможно, ваш адрес электронной почты неправильный!");
		}elseif($result == '1'){
			tsNotice("Система отправила письмо с подтверждением на ваш адрес электронной почты, пожалуйста, проверьте его как можно скорее!");
		}
		break;

	//получение подтверждения
	case "do":
		$email = tsFilter($_GET['email']);
		$verifycode = tsFilter($_GET['verifycode']);

		$verify = $new['user']->findCount('user_info',array(
			'email'=>$email,
			'verifycode'=>$verifycode,
		));

		if($verify > 0){

			$new['user']->update('user_info',array(
				'email'=>$email,
			),array(
				'isverify'=>'1',
			));
			tsNotice("Ваш Email успешно подтвержден!",'Нажмите для перехода на главную страницу!',SITE_URL);
		}else{
			tsNotice("Проверка Email не засчитана!");
		}

		break;

	//修изменение Email
	case "setemail":

		$userid = aac('user')->isLogin();


		$strUser = $new['user']->getOneUser($userid);

		$email = trim($_POST['email']);

		if($email=='') tsNotice('Поле Email не может быть пустым!');

		if(valid_email($email) == false) tsNotice('Указан недопустимый Email!');

		if($email != $strUser['email']){
			$emailNum = $new['user']->findCount('user',array(
				'email'=>$email,
			));

			if($emailNum > 0) tsNotice("Такой Email уже существует. Пожалуйста, укажите другой адрес электронной почты!");

			//обновление Email
			$new['user']->update('user',array(
				'userid'=>$strUser['userid'],
			),array(
				'email'=>$email,
			));

			//修изменить информацию и установить пользователя непроверенным
			$new['user']->update('user_info',array(
				'userid'=>$strUser['userid'],
			),array(
				'email'=>$email,
				'isverify'=>'0',
			));

			tsNotice('Email аккаунта был успешно изменен. Пожалуйста, вернитесь для повторной проверки!');

		}else{
			tsNotice('Новый Email не может совпадать со старым!');
		}

		break;

	//необходимо загрузить аватар
	case "face":

		$userid = aac('user')->isLogin();

		$strUser = $new['user']->getOneUser($userid);

		$title = 'Загрузка аватара';
		include template('verify_face');
		break;

	case "facedo":

		$userid = aac('user')->isLogin();

		if($_FILES['picfile']){

			//загрузка
			$arrUpload = tsUpload($_FILES['picfile'],$userid,'user',array('jpg','gif','png','jpeg'));

			if($arrUpload){

				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'path'=>$arrUpload['path'],
					'face'=>$arrUpload['url'],
				));

				$filesize=abs(filesize('uploadfile/user/'.$arrUpload['url']));
				if($filesize<=0){
					$new['user']->update('user_info',array(
						'userid'=>$userid,
					),array(
						'path'=>'',
						'face'=>'',
					));

				}else{


                    #ограничение размера аватара 1М
				    if($filesize>1048576){
				        tsNotice('Пожалуйста, выберите изображение аватара размером не более 1M');
                    }

					//обновление кеша аватара
					$_SESSION['tsuser']['face'] = $arrUpload['url'];
					$_SESSION['tsuser']['path'] = $arrUpload['path'];

					tsDimg($arrUpload['url'],'user','120','120',$arrUpload['path']);

					header('Location: '.tsUrl('user','verify',array('ts'=>'face')));
				}

			}else{
				tsNotice('Ошибка изменения аватара!');
			}

		}

		break;
}
