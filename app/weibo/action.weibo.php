<?php
defined('IN_TS') or die('Access Denied.');

class weiboAction extends weibo{

    public function index(){
        //dump($GLOBALS);
        $page = isset($_GET['page']) ? intval($_GET['page']) : '1';
        $url = tsUrl('weibo','index',array('page'=>''));
        $lstart = $page*20-20;
        $arrWeibo = $this->findAll('weibo',array(
            'isaudit'=>0,
        ),'uptime desc',null,$lstart.',20');
        foreach($arrWeibo as $key=>$item){
            $arrWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
            $arrWeibo[$key]['content'] = tsDecode($item['content']);
        }
        $weiboNum = $this->findCount('weibo',array(
            'isaudit'=>0,
        ));
        $pageUrl = pagination($weiboNum, 20, $page, $url);
        $arrHotWeibo = $this->findAll('weibo',null,'count_comment desc',null,10);
        foreach($arrHotWeibo as $key=>$item){
            $arrHotWeibo[$key]['content'] = tsDecode($item['content']);
            $arrHotWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
        }
        $title = 'Микроблог';
        include template('index');
    }

    public function add(){
        $js = intval($_GET['js']);
        $userid = aac('user')->isLogin(1);
		/*
        if(aac('user')->isPublisher()==false) {
            getJson('У вас нет прав на публикацию!',$js);
        }
		*/
        $content = tsClean($_POST['content']);
        if($content == '') {
            getJson('Содержание не может быть пустым!',$js);
        }
        $isaudit = 0;
        aac('system')->antiWord($content);
        $weiboid = $this->create('weibo',array(
            'userid'=>$userid,
            'locationid'=>aac('user')->getLocationId($userid),
            'content'=>$content,
            'isaudit'=>$isaudit,
            'addtime'=>date('Y-m-d H:i:s'),
            'uptime'=>date('Y-m-d H:i:s'),
        ));
        //feed
        /*
        $feed_template = '<span class="pl">Сказал(а):</span><div class="quote"><span class="inq">{content}</span> <span><a class="j a_saying_reply" href="{link}" rev="unfold">Ответить</a></span></div>';
        $feed_data = array(
            'link'	=> tsurl('weibo','show',array('id'=>$weiboid)),
            'content'	=> cututf8(t($content),'0','50'),
        );
        aac('feed')->add($userid,$feed_template,$feed_data);
        */
        //feed
        getJson('Опубликовано!',$js,2,tsurl('weibo','show',array('id'=>$weiboid)));
    }

    public function show(){
        $weiboid = intval($_GET['id']);
        $strWeibo = $this->getOneWeibo($weiboid);
        if($weiboid==0 || $strWeibo==''){
            ts404();
        }
        if($strWeibo['isaudit']==1){
            tsNotice('Обзор…');
        }
        //comment
        $page = isset($_GET['page']) ? intval($_GET['page']) : '1';
        $url = tsUrl('weibo','show',array('id'=>$weiboid,'page'=>''));
        $lstart = $page*20-20;
        $arrComments = $this->findAll('weibo_comment',array(
            'weiboid'=>$weiboid,
        ),'addtime desc','commentid',$lstart.',20');
        foreach($arrComments as $key=>$item){
            $arrComment[] = $this->getOneComment($item['commentid']);
        }
        $commentNum = $this->findCount('weibo_comment',array(
            'weiboid'=>$weiboid,
        ));
        $pageUrl = pagination($commentNum, 20, $page, $url);
        $arrWeibo = $this->findAll('weibo',array(
            'userid'=>$strWeibo['userid'],
        ),'addtime desc',null,20);
        foreach($arrWeibo as $key=>$item){
            if($item['content']==''){
                $arrWeibo[$key]['content'] = $strWeibo['user']['username'].' сообщений ('.$item['weiboid'].')';
            }
        }
        $weiboNum = $this->findCount('weibo',array(
            'userid'=>$strWeibo['userid'],
        ));
        if($weiboNum<20){
            $num = 20-$weiboNum;
            $userid = $strWeibo['userid'];
            $arrNewWeibo = $this->findAll('weibo',"`userid`!='$userid'",'addtime desc',null,$num);
            $arrWeibo = array_merge($arrWeibo, $arrNewWeibo);
        }
        if($strWeibo['content']==''){
            $title = $strWeibo['user']['username'].' сообщений ('.$strWeibo['weiboid'].')';
        }else{
            $title = cututf8(t(tsDecode($strWeibo['content'])),0,100,false);
        }
        include template('show');
    }

	public function photo(){
		$userid = intval($GLOBALS['TS_USER']['userid']);
		if($userid==0){
			echo 0;exit;
		}
		$content = tsClean($_POST['content']);
		if($GLOBALS['TS_USER']['isadmin']==0){
			aac('system')->antiWord($content);
		}
		$weiboid = $this->create('weibo',array(
			'userid'=>$userid,
			'content'=>$content,
			'isaudit'=>0,
			'addtime'=>date('Y-m-d H:i:s'),
			'uptime'=>date('Y-m-d H:i:s'),
		));
		$arrUpload = tsUpload ( $_FILES ['filedata'], $weiboid, 'weibo', array ('jpg','gif','png','jpeg' ) );
		if ($arrUpload) {
			$this->update ( 'weibo', array (
					'weiboid' => $weiboid
			), array (
					'path' => $arrUpload ['path'],
					'photo' => $arrUpload ['url']
			) );
			echo 3;exit;
		}else{
			echo 2;exit;
		}
	}

	public function addcomment(){
		$userid = aac('user')->isLogin();
		$weiboid = intval($_POST['weiboid']);
		$touserid = intval($_POST['touserid']);
		$content = tsClean($_POST['content']);
		if($content == ''){
			tsNotice('Содержание не может быть пустым!');
		}
		if($GLOBALS['TS_USER']['isadmin']==0){
			aac('system')->antiWord($content);
		}
		$commentid = $this->create('weibo_comment',array(
			'userid'=>$userid,
			'touserid'=>$touserid,
			'weiboid'=>$weiboid,
			'content'=>$content,
			'addtime'=>date('Y-m-d H:i:s'),
		));
		$commentNum = $this->findCount('weibo_comment',array(
			'weiboid'=>$weiboid,
		));
		$this->update('weibo',array(
			'weiboid'=>$weiboid,
		),array(
			'count_comment'=>$commentNum,
		));
		$strWeibo = $this->find('weibo',array(
			'weiboid'=>$weiboid,
		));
		if($strWeibo['userid'] != $userid){
			$msg_userid = '0';
			$msg_touserid = $strWeibo['userid'];
			$msg_content = 'Добавлено сообщение в ваш микроблог, проверьте его и ответьте.';
            $msg_tourl = tsUrl('weibo','show',array('id'=>$weiboid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);
		}
		tsHeaderUrl(tsUrl('weibo','show',array('id'=>$weiboid)));
	}

	public function deletecomment(){
		$userid = aac('user')->isLogin();
		$commentid = intval($_GET['commentid']);
		$strComment = $this->find('weibo_comment',array(
			'commentid'=>$commentid,
		));
		if($GLOBALS['TS_USER']['isadmin']==1 || $strComment['userid']==$userid){
			$this->delete('weibo_comment',array('commentid'=>$commentid));
			$count_comment = $this->findCount('weibo_comment',array(
				'weiboid'=>$strComment['weiboid'],
			));
			$this->update('weibo',array(
				'weiboid'=>$strComment['weiboid'],
			),array(
				'count_comment'=>$count_comment,
			));
			tsHeaderUrl(tsUrl('weibo','show',array('id'=>$strComment['weiboid'])));
		}else{
			tsNotice('Недопустимая операция!');
		}
	}

	public function deleteweibo(){
		$userid = aac('user')->isLogin();
		$weiboid = intval($_GET['weiboid']);
		$strWeibo = $this->find('weibo',array(
			'weiboid'=>$weiboid,
		));
		if($userid == $strWeibo['userid'] || $GLOBALS['TS_USER']['isadmin']==1){
			$this->delete('weibo',array(
				'weiboid'=>$weiboid,
			));
			$this->delete('weibo_comment',array(
				'weiboid'=>$weiboid,
			));
			if($strWeibo['photo']){
				unlink('uploadfile/weibo/'.$strWeibo['photo']);
			}
			tsHeaderUrl(tsUrl('weibo'));
		}else{
			tsNotice('Недопустимая операция!');
		}
	}

    public function admin(){

        if($GLOBALS['TS_USER']['isadmin']==1){
            include 'app/'.$GLOBALS['TS_URL']['app'].'/admin.'.$GLOBALS['TS_URL']['app'].'.php';
            $appAdmin = $GLOBALS['TS_URL']['app'].'Admin';
            $newAdmin = new $appAdmin($GLOBALS['db']);
            #$newAdmin->$GLOBALS['TS_URL']['mg']();
            $amg = $GLOBALS['TS_URL']['mg'];
            $newAdmin->$amg();
        }else{
            ts404();
        }
    }

    public function my(){
        if($GLOBALS['TS_USER']){
            include 'app/'.$GLOBALS['TS_URL']['app'].'/my.'.$GLOBALS['TS_URL']['app'].'.php';
            $appMy = $GLOBALS['TS_URL']['app'].'My';
            $newMy = new $appMy($GLOBALS['db']);
            $myFun = $GLOBALS['TS_URL']['my'];
            $newMy->$myFun();
        }else{
            ts404();
        }
    }

}
