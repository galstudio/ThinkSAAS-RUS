<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$arrGroupsList = $new['group']->findAll('group_user',array(
	'userid'=>$strUser['userid'],
),null,'groupid');


foreach($arrGroupsList as $key=>$item){
	$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
}

$arrCreateGroup = $new['group']->findAll('group',array(
    'userid'=>$strUser['userid'],
));

foreach($arrCreateGroup as $key=>$item){
    $arrCreateGroup[$key]['groupname'] = tsTitle($item['groupname']);
    if($item['photo']){
        $arrCreateGroup[$key]['photo'] = tsXimg($item['photo'],'group',120,120,$item['path'],1);
    }else{
        $arrCreateGroup[$key]['photo'] = SITE_URL.'public/images/group.jpg';
    }
}


$title = 'Мои группы';
include template('my/index');
