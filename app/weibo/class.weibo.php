<?php
defined('IN_TS') or die('Access Denied.');

class weibo extends tsApp{

	public function __construct($db){
        $tsAppDb = array();
		include 'app/weibo/config.php';
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
		parent::__construct($db);
	}

	public function getOneWeibo($weiboid){
		$strWeibo = $this->find('weibo',array(
			'weiboid'=>$weiboid,
		));
		$strWeibo['user']=aac('user')->getOneUser($strWeibo['userid']);
		$strWeibo['content'] = tsDecode($strWeibo['content']);
		return $strWeibo;
	}

	public function getOneComment($commentid){
		$strComment = $this->find('weibo_comment',array(
			'commentid'=>$commentid,
		));
		$strComment['content'] = tsDecode($strComment['content']);
		$strComment['user']=aac('user')->getOneUser($strComment['userid']);
		return $strComment;
	}
}
