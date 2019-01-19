<?php
defined('IN_TS') or die('Access Denied.');

$arrGroupsList = $new['my']->findAll('group_user',array(
    'userid'=>$strUser['userid'],
),null,'groupid',12);

foreach($arrGroupsList as $key=>$item){
    $strGroup = aac('group')->getOneGroup($item['groupid']);
    if($strGroup){
        $arrGroup[] = $strGroup;
    }else{
        $new['my']->delete('group_user',array(
            'userid'=>$strUser['userid'],
            'groupid'=>$item['groupid'],
        ));
    }
}

$joinGroupNum = $new['my']->findCount('group_user',array(
    'userid'=>$strUser['userid'],
));

$arrTopic = $new['my']->findAll('group_topic',array(
    'userid'=>$strUser['userid'],
),'addtime desc',null,10);

$arrArticle = $new ['my']->findAll ( 'article', array (
    'userid' => $strUser['userid'],
), 'addtime desc', null, 10 );



$title = 'Личный кабинет';
include template("index");
