<?php
defined('IN_TS') or die('Access Denied.');

$userid = '0';
$touserid= aac('user')->isLogin();

$arrMessage = $new['message']->findAll('message',array(
	'userid'=>0,
	'touserid'=>$touserid,
),'addtime desc',null,10);

foreach($arrMessage as $key=>$item){
    $arrMessage[$key]['content'] = tsTitle($item['content']);
}

//isread
$new['message']->update('message',array(
	'userid'=>0,
	'touserid'=>$touserid,
	'isread'=>0,
),array(
	'isread'=>1,
));

$title = 'Системные сообщения';

include template("system");
