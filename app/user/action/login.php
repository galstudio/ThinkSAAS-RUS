<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		if(intval($TS_USER['userid']) > 0) {
            header('Location: '.SITE_URL);exit;
        }
		$jump = $_SERVER['HTTP_REFERER'];
		$title = 'Авторизация';
		include template("login");
		break;

	case "do":
		$js = intval($_GET['js']);
        $ad = intval($_POST['ad']);
		$arrIp = aac('system')->antiIp();
		if(in_array(getIp(),$arrIp)){
			getJson('Ваш IP заблокирован и не может быть авторизован!',$js);
		}
		$jump = trim($_POST['jump']);
		$email = trim($_POST['email']);
		$pwd = trim($_POST['pwd']);
		$cktime = $_POST['cktime'];
		if($email=='' || $pwd=='') getJson('Email и пароль не могут быть пустыми!',$js);

        /*
        if($GLOBALS['TS_SITE']['ucenter']){
            require_once THINKAPP . '/ucenter/basic/conf/uc_config.php';
            require_once THINKAPP . '/ucenter/uc_client/client.php';
            require_once THINKAPP . '/ucenter/basic/common/function.php';
            $ucInfo = uc_user_login ( $email, $pwd, 2 );
            if ($ucInfo[0] <= 0) {
                getJson ( show_log_error ( $ucInfo[0] ), $js );
            }
        }
        */

		$isEmail = $new['user']->findCount('user',array(
			'email'=>$email,
		));

		$strUser = $new['user']->find('user',array(
			'email'=>$email,
		));
		if($isEmail == 0) getJson('Такого Email не существует, возможно, вы еще не зарегистрированы!',$js);
		if(md5($strUser['salt'].$pwd)!==$strUser['pwd']) getJson('Пароль неверный!',$js);
		$userData = $new['user']->find('user_info',array(
			'email'=>$email,
		));

		//session
		$sessionData = array(
			'userid' => $userData['userid'],
			'username'	=> $userData['username'],
			'path'	=> $userData['path'],
			'face'	=> $userData['face'],
			'isadmin'	=> $userData['isadmin'],
			'signin'=>$userData['signin'],
			'uptime'	=> $userData['uptime'],
		);
		$_SESSION['tsuser']	= $sessionData;
		//userid
		$userid = $userData['userid'];
		if($userData['uptime'] < strtotime(date('Y-m-d'))){
			aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts']);
		}
		$autologin = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
		$new['user']->update('user_info',array(
			'userid'=>$userid,
		),array(
			'ip'=>getIp(),  //ip
			'autologin'=>$autologin,
			'uptime'=>time(),
		));

		//Cookie, Email
		 if($cktime != ''){
			 setcookie("ts_email", $userData['email'], time()+$cktime,'/');
			 setcookie("ts_autologin", $autologin, time()+$cktime,'/');
		 }
        if($ad==1){
            getJson('Успешный вход!',$js,2,SITE_URL.'index.php?app=system');
        }
		if($jump != ''){
			getJson('Успешный вход!',$js,2,$jump);
		}else{
			if($TS_SITE['istomy']){
				getJson('Успешный вход!',$js,2,tsUrl('my'));
			}else{
				getJson('Успешный вход!',$js,2,SITE_URL);
			}
		}
		break;

	case "out":
		aac('user')->logout();
		header('Location: '.tsUrl('user','login'));
		break;
}
