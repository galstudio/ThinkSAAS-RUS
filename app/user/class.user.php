<?php
defined('IN_TS') or die('Access Denied.');

class user extends tsApp{

	public function __construct($db){
        $tsAppDb = array();
		include 'app/user/config.php';
		//APP
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}

		parent::__construct($db);
	}

	function getNewUser($num){
		$arrNewUserId = $this->findAll('user_info',null,'addtime desc','userid',$num);
		foreach($arrNewUserId as $item){
			$arrNewUser[] = $this->getOneUser($item['userid']);
		}
		return $arrNewUser;
	}

	function getHotUser($num){
		$arrNewUserId = $this->findAll('user_info',null,'uptime desc','userid',$num);
		foreach($arrNewUserId as $item){
			$arrHotUser[] = $this->getOneUser($item['userid']);
		}
		return $arrHotUser;
	}

	public function getFollowUser($num){
		$arrUserId = $this->findAll('user_info',null,'count_followed desc','userid',$num);
		foreach($arrUserId as $item){
			$arrFollowUser[] = $this->getOneUser($item['userid']);
		}
		return $arrFollowUser;
	}

	public function getScoreUser($num){
		$arrUserId = $this->findAll('user_info',null,'count_score desc','userid',$num);
		foreach($arrUserId as $item){
			$arrScoreUser[] = $this->getOneUser($item['userid']);
		}
		return $arrScoreUser;
	}

	function getOneUser($userid){

        $strUser = $this->find('user_info',array(
            'userid'=>$userid,
        ));

        if($strUser){

            $strUser['username'] = tsTitle($strUser['username']);
            $strUser['email'] = tsTitle($strUser['email']);
            $strUser['phone'] = tsTitle($strUser['phone']);
            $strUser['province'] = tsTitle($strUser['province']);
            $strUser['city'] = tsTitle($strUser['city']);
            $strUser['signed'] = tsTitle($strUser['signed']);
            $strUser['about'] = tsTitle($strUser['about']);
            $strUser['address'] = tsTitle($strUser['address']);

            if($strUser['face'] && $strUser['path']){
                $strUser['face'] = tsXimg($strUser['face'],'user',120,120,$strUser['path'],1);
            }elseif($strUser['face'] && $strUser['path']==''){
                $strUser['face']	= SITE_URL.'public/images/'.$strUser['face'];
            }else{
                $strUser['face']	= SITE_URL.'public/images/user_large.jpg';
            }
            $strUser['rolename'] = $this->getRole($strUser['allscore']);
        }else{
            $strUser = '';
        }

        return $strUser;
	}

	public function isUser($userid){

		$isUser = $this->findCount('user',array('userid'=>$userid));

		if($isUser == 0){
			//$this->toEmpty($userid);
			return false;
		}else{
			return true;
		}

	}

    /**
     * @param int $js
     * @param string $userkey
     * @return int
     */
    public function isLogin($js=0, $userkey=''){

		$userid = intval($_SESSION['tsuser']['userid']);

        if($js && $userid==0 && $userkey==''){
            getJson('Вы еще не вошли в систему!',$js);
        }

        #userkey userid
        if($js && $userid==0 && $userkey){
            $userid = $this->getUserIdByUserKey($userkey);
            return $userid;
        }

		if($userid>0){
			if($this->isUser($userid)){
				return $userid;
			}else{
				header("Location: ".tsUrl('user','login',array('ts'=>'out')));
				exit;
			}
		}else{
			header("Location: ".tsUrl('user','login',array('ts'=>'out')));
			exit;
		}
	}

	public function getOneArea($areaid){

		$strArea = $this->find('area',array('areaid'=>$areaid));
		return $strArea;

	}

	public function getRole($score){
		global $tsMySqlCache;
		$arrRole = fileRead('data/user_role.php');

		if($arrRole==''){
			$arrRole = $tsMySqlCache->get('user_role');
		}

		foreach($arrRole as $key=>$item){
			if($score > $item['score_start'] && $score <= $item['score_end'] || $score > $item['score_start'] && $item['score_end']==0 || $score >=0 && $score <= $item['score_end']){
				return $item['rolename'];
			}
		}
	}

	/*
	 * $userid ID
	 * $scorename
	 * $score
	 * @issx
	 */
	public function addScore($userid,$scorename,$score,$issx=0){
		if($userid && $scorename && $score){
            $starttime = strtotime(date('Y-m-d 00:00:01'));
            $endtime = strtotime(date('Y-m-d 23:59:59'));
            $strDayScore = $this->db->once_fetch_assoc("select SUM(score) as dayscore from ".dbprefix."user_score_log where `userid`='$userid' and  `status`='0' and `addtime`>='$starttime' and `addtime`<='$endtime'");
            if($strDayScore['dayscore']<$GLOBALS['TS_SITE']['dayscoretop'] || $issx==1){
                $this->create('user_score_log',array(
                    'userid'=>$userid,
                    'scorename'=>$scorename,
                    'score'=>$score,
                    'status'=>0,
                    'addtime'=>time(),
                ));
                $strUser = $this->find('user_info',array(
                    'userid'=>$userid,
                ));
                $strAllScore = $this->db->once_fetch_assoc("select SUM(score) as allscore from ".dbprefix."user_score_log where `userid`='$userid' and  `status`='0'");
                $this->update('user_info',array(
                    'userid'=>$userid,
                ),array(
                    'allscore'=>$strAllScore['allscore'],
                    'count_score'=>$strUser['count_score']+$score,
                ));
            }
		}
	}

	public function delScore($userid,$scorename,$score){
		if($userid && $scorename && $score){
			$strUser = $this->find('user_info',array(
				'userid'=>$userid,
			));
			if($strUser['count_score']>$score){
                $this->create('user_score_log',array(
                    'userid'=>$userid,
                    'scorename'=>$scorename,
                    'score'=>$score,
                    'status'=>1,
                    'addtime'=>time(),
                ));
                $this->update('user_info',array(
                    'userid'=>$userid,
                ),array(
                    'count_score'=>$strUser['count_score']-$score,
                ));
                return true;
            }else{
			    return false;
            }
		}
	}

	function doScore($app,$ac,$ts='',$uid=0,$mg=''){
		$userid = intval($_SESSION['tsuser']['userid']);
		if($uid) $userid=$uid;
		$strScore = $this->find('user_score',array(
			'app'=>$app,
			'action'=>$ac,
			'mg'=>$mg,
			'ts'=>$ts,
		));
		if($strScore && $userid){
			if($strScore['status']=='0'){
				$this->addScore($userid,$strScore['scorename'],$strScore['score']);
			}
			if($strScore['status']=='1'){
				$this->delScore($userid,$strScore['scorename'],$strScore['score']);
			}
		}
	}

	function toEmpty($userid){
		$strUser = $this->find('user_info',array(
			'userid'=>$userid,
		));

		//Email
		$isEmail = $this->findCount('anti_email',array(
			'email'=>$strUser['email'],
		));
		if($strUser['email'] && $isEmail==0){
			$this->create('anti_email',array(
				'email'=>$strUser['email'],
				'addtime'=>date('Y-m-d H:i:s'),
			));
		}

		//article
		$this->delete('article',array('userid'=>$userid));
		$this->delete('article_comment',array('userid'=>$userid));
		$this->delete('article_recommend',array('userid'=>$userid));

		//attach
		$this->delete('attach',array('userid'=>$userid));
		$this->delete('attach_album',array('userid'=>$userid));

		//user
		$this->delete('user',array('userid'=>$userid));
		$this->delete('user_info',array('userid'=>$userid));
		$this->delete('user_follow',array('userid'=>$userid));
		$this->delete('user_follow',array('userid_follow'=>$userid));
		$this->delete('user_gb',array('userid'=>$userid));
		$this->delete('user_gb',array('touserid'=>$userid));
		$this->delete('user_open',array('userid'=>$userid));
		$this->delete('user_scores',array('userid'=>$userid));
		$this->delete('user_score_log',array('userid'=>$userid));

		//group
		$this->delete('group',array('userid'=>$userid,));
		$this->delete('group_album',array('userid'=>$userid,));
		$this->delete('group_topic',array('userid'=>$userid));
		$this->delete('group_user',array('userid'=>$userid));
		$this->delete('group_topic_comment',array('userid'=>$userid));
		$this->delete('group_topic_collect',array('userid'=>$userid));


		//message
		$this->delete('message',array('userid'=>$userid));
		$this->delete('message',array('touserid'=>$userid));

		//photo
		$this->delete('photo',array('userid'=>$userid));
		$this->delete('photo_album',array('userid'=>$userid));
		$this->delete('photo_comment',array('userid'=>$userid));

		//tag
		$this->delete('tag_user_index',array('userid'=>$userid));

		//weibo
		$this->delete('weibo',array('userid'=>$userid));
		$this->delete('weibo_comment',array('userid'=>$userid));

		//event
		$this->delete('event',array('userid'=>$userid));
		$this->delete('event_comment',array('userid'=>$userid));
		$this->delete('event_users',array('userid'=>$userid));

		//ask
		$this->delete('ask_comment',array('userid'=>$userid));
		$this->delete('ask_comment_add',array('userid'=>$userid));
		$this->delete('ask_comment_op',array('userid'=>$userid));
		$this->delete('ask_question_add',array('userid'=>$userid));
		$this->delete('ask_topic',array('userid'=>$userid));
		$this->delete('ask_user_cate',array('userid'=>$userid));
		$this->delete('ask_user_score',array('userid'=>$userid));

	}

	//locationid
	function getLocationId($userid){
		$strUser = $this->find('user_info',array(
			'userid'=>$userid,
		),'locationid');

		return intval($strUser['locationid']);

	}

	//session
	function logout(){
		unset($_SESSION['tsuser']);
		session_destroy();
		setcookie("ts_email", '', time()+3600,'/');
		setcookie("ts_autologin", '', time()+3600,'/');
	}

    function signin(){

        $userid = intval($_SESSION['tsuser']['userid']);
        $zuotian = date('Y-m-d',strtotime("-1 day"));
        $jintian = date('Y-m-d');
        $zuotianSign = $this->find('sign',array(
            'userid'=>$userid,
            'addtime'=>$zuotian,
        ));
        $jintianSign = $this->find('sign',array(
            'userid'=>$userid,
            'addtime'=>$jintian,
        ));
        if($jintianSign==''){
            if($zuotianSign==''){
                $this->create('sign',array(
                    'userid'=>$userid,
                    'num'=>1,
                    'addtime'=>$jintian,
                ));
            }else{
                $this->create('sign',array(
                    'userid'=>$userid,
                    'num'=>$zuotianSign['num']+1,
                    'addtime'=>$jintian,
                ));
            }
            $this->doScore('user','signin');
            return true;
        }else{
            return false;
        }
    }

    public function isPublisher(){
        $publisher = $GLOBALS['TS_SITE']['publisher'];
        $userid = intval($GLOBALS['TS_USER']['userid']);
        if($publisher){
            $ispublisher = $this->findCount('user_info',array(
                'userid'=>$userid,
                $publisher=>1,
            ));
            if($ispublisher){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    /**
     * userid userkey
     * @param $userid
     * @return bool|string
     */
    public function getUserKeyByUserId($userid){
        include 'thinksaas/class.crypt.php';
        $crypt= new crypt();
        return $crypt->encrypt($userid,$GLOBALS['TS_SITE']['site_pkey']);
    }

    /**
     * userkey userid
     * @param $userkey
     */
    public function getUserIdByUserKey($userkey){
        include 'thinksaas/class.crypt.php';
        $crypt= new crypt();
        $userid = $crypt->decrypt($userkey,$GLOBALS['TS_SITE']['site_pkey']);

        $isUser = $this->findCount('user',array(
            'userid'=>$userid,
        ));

        if($isUser == 0){

            echo json_encode(array(

                'status'=> 0,
                'msg'=> 'Недопустимое действие!',
                'data'=> '',
            ));
            exit;
        }else{
            return $userid;
        }
    }

	public function __destruct(){

	}

}
