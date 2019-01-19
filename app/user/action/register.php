<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		if(intval($TS_USER['userid']) > 0) {
            header('Location: '.SITE_URL);exit;
        }
		//ID
		$fuserid = intval($_GET['fuserid']);
		$title = 'Регистрация';
		include template("register");
		break;

	case "do":
		//JS
		$js = intval($_GET['js']);
		$email		= trim($_POST['email']);
		$pwd			= trim($_POST['pwd']);
		$repwd		= trim($_POST['repwd']);
		$username		= t($_POST['username']);
		$fuserid = intval($_POST['fuserid']);
		$authcode = strtolower($_POST['authcode']);
        //суффикс Email
        $arrEmail = explode('@',$email);
        if($arrEmail[1]=='chacuo.net' || $arrEmail[1]=='mail.ru' || $arrEmail[1]=='yandex.ru' || $arrEmail[1]=='yandex.com' || $arrEmail[1]=='027168.net' || $arrEmail[1]=='027168.com'){
            getJson('Что-то ты, друг, напортачил!',$js);
        }

		/*Следующим IP запрещено входить или регистрироваться*/
		$arrIp = aac('system')->antiIp();
		if(in_array(getIp(),$arrIp)){
			getJson('Ваш IP заблокирован и не может быть авторизован!',$js);
		}
		if($TS_SITE['isinvite']=='1'){
			$invitecode = trim($_POST['invitecode']);
			if($invitecode == '') getJson('Код инвайта не может быть пустым!',$js);
			$codeNum = $new['user']->findCount('user_invites',array(
				'invitecode'=>$invitecode,
				'isused'=>0,
			));
			if($codeNum == 0) getJson('Код инвайта уже был использован, введите другой код инвайта!',$js);
		}
		$isEmail = $new['user']->findCount('user',array(
			'email'=>$email,
		));
		$isUserName = $new['user']->findCount('user_info',array(
			'username'=>$username,
		));
		if($email=='' || $pwd=='' || $repwd=='' || $username==''){
			getJson('Необходимые данные не могут быть пустыми!',$js);
		}
		if(valid_email($email) == false){
			getJson('Недопустимый Email!',$js);
		}
		if($isEmail > 0){
			getJson('Такой Email уже зарегистрирован!',$js);
		}
		if($pwd != $repwd){
			getJson('Пароли не совпадают!',$js);
		}
		if(count_string_len($username) < 4 || count_string_len($username) > 20){
			getJson('Длина логина/ника должна быть от 4 до 20 знаков!',$js);
		}
		if($isUserName > 0){
			getJson('Такой логин/ник уже существует, укажите другой!',$js);
		}
		if($TS_SITE['isauthcode']){
            if ($authcode != $_SESSION['verify']) {
                getJson('Код подтверждения введен неправильно, введите заново!', $js);
            }
		}
		$salt = md5(rand());
		$userid = $new['user']->create('user',array(
			'pwd'=>md5($salt.$pwd),
			'salt'=>$salt,
			'email'=>$email,
		));

		$new['user']->create('user_info',array(
			'userid'			=> $userid,
			'fuserid'	=> $fuserid,
			'username' 	=> $username,
			'email'		=> $email,
			'ip'			=> getIp(),
            'comefrom'=>'9',
			'addtime'	=> time(),
			'uptime'	=> time(),
		));

		$isGroup = $new['user']->find('user_options',array(
			'optionname'=>'isgroup',
		));

		if($isGroup['optionvalue']){
			$arrGroup = explode(',',$isGroup['optionvalue']);

			if($arrGroup){
				foreach($arrGroup as $key=>$item){
					$groupUserNum = $new['user']->findCount('group_user',array(
						'userid'=>$userid,
						'groupid'=>$item,
					));

					if($groupUserNum == 0){
						$new['user']->create('group_user',array(
							'userid'=>$userid,
							'groupid'=>$item,
							'addtime'=>time(),
						));

						$count_user = $new['user']->findCount('group_user',array(
							'groupid'=>$item,
						));

						$new['user']->update('group',array(
							'groupid'=>$item,
						),array(
							'count_user'=>$count_user,
						));

					}
				}
			}
		}

		$userData = $new['user']->find('user_info',array(
			'userid'=>$userid,
		),'userid,username,path,face,isadmin,signin,uptime');

		//session
		$_SESSION['tsuser']	= $userData;
		aac('message')->sendmsg(0,$userid,'Привет, '.$username.'! Ты удачно зарегистрировался на '.$TS_SITE['site_title'].'. Наслаждайтесь приятным общением и соблюдай правила этого сайта!');

		if($TS_SITE['isinvite']=='1'){
			$strInviteCode = $new['user']->find('user_invites',array(
				'invitecode'=>$invitecode,
			));

			$new['user']->create('user_follow',array(
				'userid'=>$userid,
				'userid_follow'=>$strInviteCode['userid'],
			));

			$new['user']->update('user_invites',array(
				'invitecode'=>$invitecode,
			),array(
				'isused'=>'1',
			));
		}

		//feed
		/*
		$feed_template = '<span class="pl">Пишет: </span><div class="quote"><span class="inq">{content}</span> <span><a class="j a_saying_reply" href="{link}" rev="unfold">Ответить</a></span></div>';
		$feed_data = array(
			'link'	=> tsurl('weibo','show',array('id'=>$weiboid)),
			'content'	=> cututf8(t($content),'0','50'),
		);
		aac('feed')->add($userid,$feed_template,$feed_data);
		*/
		//feed

		aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts']);
		getJson('Успешный вход!',$js,2,SITE_URL);
		break;
}
