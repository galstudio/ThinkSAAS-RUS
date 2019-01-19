<?php
defined('IN_TS') or die('Access Denied.');

class message extends tsApp{

	public function __construct($db){
        $tsAppDb = array();
		include 'app/message/config.php';

		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}

		parent::__construct($db);
	}


    /**
     * @param $userid       ID，0
     * @param $touserid     ID
     * @param $content
     * @param string $tourl
     */
    public function sendmsg($userid,$touserid,$content,$tourl=''){

		$userid = intval($userid);
		$touserid = intval($touserid);
		$content = addslashes(trim($content));

		if($touserid && $content){

			$messageid = $this->create('message',array(
				'userid'		=> $userid,
				'touserid'	=> $touserid,
				'content'	=> $content,
                'tourl'=>$tourl,
				'addtime'	=> time(),
			));

		}
	}


}
