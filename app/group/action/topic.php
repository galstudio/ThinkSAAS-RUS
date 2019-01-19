<?php
defined('IN_TS') or die('Access Denied.');

$topicid = intval($_GET['id']);

$strTopic = $new['group']->find('group_topic',array(
    'topicid'=>$topicid,
));


if($strTopic==''){
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    $title = '404';
    include pubTemplate("404");
    exit;
}

if($strTopic['isaudit']==1 && $GLOBALS['TS_USER']['isadmin']==0){
    tsNotice('Обзор записей…');
}

$strGroup = $new['group']->getOneGroup($strTopic['groupid']);

$strGroupUser = '';
if(intval($TS_USER['userid'])){
    $strGroupUser = $new['group']->find('group_user',array(
        'userid'=>intval($TS_USER['userid']),
        'groupid'=>$strTopic['groupid'],
    ));
}

if ($strGroup['isopen'] == '1' && $strGroupUser == '') {
    $title = $strTopic['title'];
    include template("topic_isopen");exit;
}elseif($strGroup['isopen'] == '1' && $TS_APP['ispayjoin']==1 && $strGroupUser['endtime']!='0000-00-00' && $strGroupUser['endtime'] <date('Y-m-d')){
    $title = $strTopic['title'];
    include template("topic_xuqi");exit;
}

$strTopic['title'] = tsTitle($strTopic['title']);

$tpUrl = tpPage($strTopic['content'],'group','topic',array('id'=>$topicid));

$strTopic['content'] = tsDecode($strTopic['content'],$tp);

$isComment = $new['group']->findCount('group_topic_comment', array(
    'userid' => intval($TS_USER['userid']),
    'topicid' => $strTopic['topicid'],
));

if($strTopic['iscommentshow']==1 && $isComment==0 && $strTopic['userid']!=intval($TS_USER['userid'])){
    $strTopic['content'] = '<div class="alert alert-info">Вы можете получить доступ к содержанию только после ответа! </div>';
}

if($strTopic['userid']==$TS_USER['userid']){

    if($strTopic['isdelete']=='1'){
        tsNotice('Ваш пост удален…');
    }

}

if ($strTopic['typeid'] != '0'){
    $strTopic['type'] = $new['group']->find('group_topic_type', array(
        'typeid' => $strTopic['typeid'],
    ));
}

$strTopic['content'] = @preg_replace("/\[@(.*)\:(.*)]/U","<a href='".tsUrl('user','space',array('id'=>'$2'))." ' rel=\"face\" uid=\"$2\"'>@$1</a>",$strTopic['content']);

$strTopic['tags'] = aac('tag')->getObjTagByObjid('topic', 'topicid', $topicid);
$strTopic['user'] = aac('user')->getOneUser($strTopic['userid']);

if($strTopic['tags']){
    foreach($strTopic['tags'] as $key=>$item){
        $arrTag[] = $item['tagname'];
    }
    $sitekey = arr2str($arrTag);
}else{
    $sitekey = $strTopic['title'];
}

$title = $strTopic['title'].' -> '.$strGroup['groupname'];

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$url = tsUrl('group', 'topic', array('id' => $topicid, 'page' => ''));

$lstart = $page * 15-15;

$arrComment = $new['group']->findAll('group_topic_comment',array(
    'topicid'=>$topicid,
),'addtime desc',null,$lstart.',15');

foreach($arrComment as $key => $item)
{
    $arrTopicComment[] = $item;
    $arrTopicComment[$key]['l'] = (($page-1) * 15) + $key + 1;
    $arrTopicComment[$key]['user'] = aac('user')->getOneUser($item['userid']);

    $arrTopicComment[$key]['content'] = tsDecode($item['content']);

    $arrTopicComment[$key]['recomment'] = $new['group']->recomment($item['referid']);

    ####вложение комментария####
    if($TS_APP['istopicattach']){
        $arrTopicComment[$key]['attach'] = $new['group']->getCommentAttach($item['commentid']);
    }
    ####вложение комментария####

}

$commentNum = $new['group']->findCount('group_topic_comment',array(
    'topicid'=>$strTopic['topicid'],
));

$pageUrl = pagination($commentNum, 15, $page, $url);

$arrHotTopic = $new['group']->getHotTopic(7);

$arrRecommendTopic = $new['group']->getRecommendTopic();

$arrGroupHotTopic = $new['group']->findAll('group_topic',array(
    'groupid'=>$strGroup['groupid'],
    'isaudit'=>0,
),'count_view desc','topicid,title',10);

$newTopic = $new['group']->findAll('group_topic',array(
    'isaudit'=>'0',
),'addtime desc','topicid,title',10);

####приложение вложений####
if($TS_APP['istopicattach']){
    $arrAttach = $new['group']->getTopicAttach($strTopic['topicid']);
}
####приложение вложений####

$sitedesc = cututf8(t($strTopic['content']),0,100);

include template('topic');

$new['group']->update('group_topic', array(
    'topicid' => $strTopic['topicid'],
), array(
    'count_view' => $strTopic['count_view'] + 1,
));
