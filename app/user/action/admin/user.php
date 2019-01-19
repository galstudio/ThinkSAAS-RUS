<?php
defined('IN_TS') or die('Access Denied.');

	switch($ts){
		case "list":
			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$userid = intval($_GET['userid']);
			$username = tsFilter($_GET['username']);
			$arrData = null;
			if($userid > 0 && $username==''){
				$arrData = array('userid'=>$userid);
			}elseif($userid==0 && $username != ''){
				$arrData = array('username'=>$username);
			}elseif($userid>0 && $username != ''){
				$arrData = array('userid'=>$userid,'username'=>$username);
			}
			$lstart = $page*20-20;
			$url = SITE_URL.'index.php?app=user&ac=admin&mg=user&ts=list&userid='.$userid.'&username='.$username.'&page=';
			$arrAllUser	= $new['user']->findAll('user_info',$arrData,'userid desc',null,$lstart.',20');
			$userNum = $new['user']->findCount('user_info');
			$pageUrl = pagination($userNum, 20, $page, $url);
			include template("admin/user_list");
			break;

		case "edit":
			$userid = $_GET['userid'];
			$strUser = $new['user']->getOneUser($userid);
			include template("admin/user_edit");
			break;

		case "view":
			$userid = $_GET['userid'];
			$strUser = $new['user']->getOneUser($userid);
			include template("admin/user_view");
			break;

		case "isenable":
			$userid = intval($_GET['userid']);
			$page = intval($_GET['page']);
			$strUser = $new['user']->find('user_info',array(
				'userid'=>$userid,
			));

			if($strUser['isenable']==0){
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'isenable'=>1,
				));

				//Id
				$isuser = $new['user']->findCount('anti_user',array(
					'userid'=>$userid,
				));
				if($isuser==0){
					$new['user']->create('anti_user',array(
						'userid'=>$userid,
						'addtime'=>date('Y-m-d H:i:s'),
					));
				}

				//IP
				$isip = $new['user']->findCount('anti_ip',array(
					'ip'=>$strUser['ip']
				));
				if($isip==0 && $strUser['ip']){
					$new['user']->create('anti_ip',array(
						'ip'=>$strUser['ip'],
						'addtime'=>date('Y-m-d H:i:s'),
					));
				}

			}

			if($strUser['isenable']==1){
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'isenable'=>0,
				));

				$new['user']->delete('anti_user',array(
					'userid'=>$userid,
				));
				$new['user']->delete('anti_ip',array(
					'ip'=>$strUser['ip'],
				));
			}

			#qiMsg('Успешно!');

            header('Location: '.SITE_URL.'index.php?app=user&ac=admin&mg=user&ts=list&page='.$page);

			break;

		case "pwd":
			$userid = intval($_GET['userid']);
			$strUser = $new['user']->find('user',array(
				'userid'=>$userid,
			));
			include template('admin/user_pwd');
			break;

		case "pwddo":
			$userid = intval($_POST['userid']);
			$pwd = trim($_POST['pwd']);
			if($pwd == '') qiMsg('Пароль не может быть пустым!');
			$strUser = $new['user']->find('user',array(
				'userid'=>$userid,
			));
			$salt = md5(rand());
			$new['user']->update('user',array(
				'userid'=>$userid,
			),array(
				'pwd'=>md5($salt.$pwd),
				'salt'=>$salt,
			));
			qiMsg('Пароль был успешно изменен: '.$pwd);

			break;

		case "deldata":
			$userid = intval($_GET['userid']);
			aac('user')->toEmpty($userid);
			qiMsg('Данные успешно очищены!');
			break;

		case "admin":
			$userid = intval($_GET['userid']);
			$strUser = $new['user']->find('user_info',array(
				'userid'=>$userid,
			));

			if($strUser['isadmin']==1){
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'isadmin'=>'0',
				));
			}elseif($strUser['isadmin']==0){
				$new['user']->update('user_info',array(
					'userid'=>$userid,
				),array(
					'isadmin'=>'1',
				));
			}
			qiMsg('Успешно!');
			break;

		//Email
		case "clean":
			$arrUser = $new['user']->findAll('user_info',array(
				'isenable'=>1,
			));
			foreach($arrUser as $key=>$item){
				aac('user')->toEmpty($item['userid']);
			}
			qiMsg('Пользовательский мусор очищен!');
			break;

		case "face":
			$userid = intval($_GET['userid']);

			$new['user']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'path'=>'',
				'face'=>'',
			));
			qiMsg('Успешно!');
            break;

        case "isrenzheng":
            $userid = intval($_GET['userid']);
            $strUser = $new['user']->find('user_info',array(
                'userid'=>$userid,
            ));

            if($strUser['isrenzheng']==0){
                $new['user']->update('user_info',array(
                    'userid'=>$userid,
                ),array(
                    'isrenzheng'=>1,
                ));

                $msg_userid = '0';
                $msg_touserid = $userid;
                $msg_content = 'Поздравляем, система сертифицировала вашу личную информацию!';
                aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
            }

            if($strUser['isrenzheng']==1){
                $new['user']->update('user_info',array(
                    'userid'=>$userid,
                ),array(
                    'isrenzheng'=>0,
                ));

                $msg_userid = '0';
                $msg_touserid = $userid;
                $msg_content = 'К сожалению, система отменила сертификацию вашей личной информации!';
                aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
            }
            qiMsg('Успешно!');
            break;
	}
