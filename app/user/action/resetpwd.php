<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		$email = trim($_GET['mail']);
		$resetpwd = tsUrlCheck($_GET['set']);
        if(valid_email($email)==false){
            tsNotice('Недопустимая операция!');
        }
		$userNum = $new['user']->findCount('user',array(
			'email'=>$email,
			'resetpwd'=>$resetpwd,
		));
		if($email=='' || $resetpwd==''){
			tsNotice("Отправляйся на Марс и живи там!");
		}elseif($userNum == 0){
			tsNotice("Отправляйся на Марс и живи там!");
		}else{
			$title = 'Сброс пароля';
			include template("resetpwd");
		}
		break;

	case "do":
		$js = intval($_GET['js']);
		$email 	= trim($_POST['email']);
		$pwd 	= trim($_POST['pwd']);
		$repwd	= trim($_POST['repwd']);
		$resetpwd = trim($_POST['resetpwd']);
		if($email=='' || $pwd=='' || $repwd=='' || $resetpwd==''){
			getJson("Поля не могут быть пустыми!",$js);
		}
        if(valid_email($email)==false){
            getJson('Неверный адрес Email',$js);
        }
        $userNum = $new['user']->findCount('user',array(
            'email'=>$email,
            'resetpwd'=>$resetpwd,
        ));
        if($userNum == '0'){
			getJson("Отправляйся на Марс и живи там!",$js);
		}
			$salt = md5(rand());
			$new['user']->update('user',array(
				'email'=>$email,
			),array(
				'pwd'=>md5($salt.$pwd),
				'salt'=>$salt,
				'resetpwd'=>'',
			));
			getJson("Пароль был успешно изменен!",$js);
		break;
}
