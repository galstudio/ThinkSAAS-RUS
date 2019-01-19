<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$touserid= intval($_GET['userid']);

if($userid == 0 || $touserid == 0) {
	exit;
};

$msgCount = $new['message']->findCount('message',"(userid='$userid' and touserid='$touserid') or (userid='$touserid' and touserid='$userid')");

if($msgCount ==0) {
	exit;
};

$arrMessage = $new['message']->findAll('message',"(userid='$userid' and touserid='$touserid') or (userid='$touserid' and touserid='$userid')",'addtime desc',null,10);

foreach($arrMessage as $key=>$item){
    $arrMessage[$key]['user'] = aac('user')->getOneUser($item['userid']);
    $arrMessage[$key]['content'] = tsTitle($item['content']);
}

$arrMessage = array_reverse($arrMessage);

//isread
$new['message']->update('message',array(
	'userid'=>$touserid,
	'touserid'=>$userid,
	'isread'=>0,
),array(
	'isread'=>1,
));

$title = 'Сообщения';

include template("msgbox");
