<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
        if ($GLOBALS['TS_USER']){
            header('Location: '.SITE_URL);
            exit;
        }
		$title = 'Восстановление пароля';
		include template("forgetpwd");

		break;

	case "do":
		$js = intval($_GET['js']);
		$email	= trim($_POST['email']);
        if(valid_email($email)==false){
            getJson('Неверный Email адрес',$js);
        }
		$emailNum = $new['user']->findCount('user',array(
			'email'=>$email,
		));
		if($email==''){
			getJson('Адрес Email не может быть пустым!',$js);
		}elseif($emailNum == '0'){
			getJson("Такой Email не существует, возможно, вы еще не зарегистрированы!",$js);
		}else{
			$resetpwd = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
			$new['user']->update('user',array(
				'email'=>$email,
			),array(
				'resetpwd'=>$resetpwd,
			));
			$subject = 'Восстановление пароля '.$TS_SITE['site_title'];

			$content = 'Ваша регистрационная информация:<br />Email: '.$email.'<br />Ссылка на сброс пароля:<br /><a href="'.$TS_SITE['site_url'].'index.php?app=user&ac=resetpwd&mail='.$email.'&set='.$resetpwd.'">'.$TS_SITE['site_url'].'index.php?app=user&ac=resetpwd&mail='.$email.'&set='.$resetpwd.'</a>';
			$result = aac('mail')->postMail($email,$subject,$content);
			if($result == '0'){
				getJson('Информация, необходимая для восстановления пароля, является неполной!',$js);
			}elseif($result == '1'){
				getJson('Система отправила письмо на ваш Email, пожалуйста, проверьте его!',$js);
			}
		}
		break;
}
