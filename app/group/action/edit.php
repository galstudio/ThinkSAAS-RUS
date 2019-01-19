<?php

defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$groupid = intval($_GET['groupid']);

$strGroup = $new['group']->find('group',array(
	'groupid'=>$groupid,
));

if($strGroup['userid']!=$userid && $TS_USER['isadmin']==0){
    tsNotice('非法操作！');
}

$strGroup['groupname'] = tsDecode($strGroup['groupname']);
$strGroup['groupdesc'] = tsDecode($strGroup['groupdesc']);

switch($ts){

    case "base":

        $arrTags = aac ( 'tag' )->getObjTagByObjid ( 'group', 'groupid', $groupid );
        foreach ( $arrTags as $key => $item ) {
            $arrTag [] = $item ['tagname'];
        }
        $strGroup ['tag'] = arr2str ( $arrTag );

        $title = 'Правка конфигурации группы';
        include template("edit_base");

        break;

    case "basedo":

        $groupname = trim($_POST['groupname']);
        $groupdesc = trim($_POST['groupdesc']);

        if($groupname=='' || $groupdesc=='') tsNotice("Название и описание группы не может быть пустым!");

        if($TS_USER['isadmin']!=1){
            aac('system')->antiWord($groupname);
            aac('system')->antiWord($groupdesc);
        }

        $isgroupname = $new['group']->findCount('group',array(
            'groupname'=>$groupname,
        ));

        if($isgroupname > 0 && $strGroup['groupname']!=$groupname) tsNotice('Такое название группы уже существует!');


        $new['group']->update('group',array(
            'groupid'=>$groupid,
        ),array(
            'groupname'	=> $groupname,
            'groupdesc'	=> $groupdesc,
            'joinway'		=> intval($_POST['joinway']),
            'price'		=> intval($_POST['price']),
            'ispost'	=> intval($_POST['ispost']),
            'isopen'		=> intval($_POST['isopen']),
            'ispostaudit'		=> intval($_POST['ispostaudit']),
        ));

        if ($_POST ['tag']) {
            aac ( 'tag' )->delIndextag ( 'group', 'groupid', $groupid );
            aac ( 'tag' )->addTag ( 'group', 'groupid', $groupid, $_POST ['tag'] );
        }

        tsNotice('Основная информация была успешно изменена!');

        break;

    case "icon":

        $title = 'Логотип группы';
        include template("edit_icon");

        break;

    case "icondo":

        $arrUpload = tsUpload($_FILES['photo'],$groupid,'group',array('jpg','gif','png','jpeg'));

        if($arrUpload){

            $new['group']->update('group',array(
                'groupid'=>$groupid,
            ),array(
                'path'=>$arrUpload['path'],
                'photo'=>$arrUpload['url'],
            ));

            tsDimg($arrUpload['url'],'group','200','200',$arrUpload['path']);

            tsNotice("Логотип группы был успешно изменен!");

        }else{
            tsNotice("Неудалось загрузить!");
        }

        break;

    case "privacy":

        $title = 'Права группы';
        include template("edit_privacy");

        break;

    case "type":

        $arrGroupType = $new['group']->findAll('group_topic_type',array(
            'groupid'=>$strGroup['groupid'],
        ));

        $title = 'Блоги группы';
        include template("edit_type");

        break;

    case "typeadd":

        $typename = trim($_POST['typename']);
        if($typename){
            $new['group']->create('group_topic_type',array(
                'groupid'=>$groupid,
                'typename'=>$typename,
            ));
        }

        header("Location: ".tsUrl('group','edit',array('ts'=>'type','groupid'=>$groupid)));
        break;

    case "typeedit":
        $typeid = intval($_POST['typeid']);
        $typename = trim($_POST['typename']);
        if($typeid && $typename){
            $new['group']->update('group_topic_type',array(
                'typeid'=>$typeid,
                'groupid'=>$groupid,
            ),array(
                'typename'=>$typename,
            ));
        }
        header("Location: ".tsUrl('group','edit',array('ts'=>'type','groupid'=>$groupid)));
        break;

    case "typedelete":

        $typeid = intval($_GET['typeid']);

        $new['group']->delete('group_topic_type',array(
            'typeid'=>$typeid,
            'groupid'=>$groupid,
        ));

        $new['group']->update('group_topic',array(
            'groupid'=>$groupid,
            'typeid'=>$typeid,
        ),array(
            'typeid'=>0,
        ));

        header("Location: ".tsUrl('group','edit',array('ts'=>'type','groupid'=>$groupid)));
        break;

    case "cate":

        $arrCate = $new['group']->findAll('group_cate',array(

            'referid'=>0,

        ));

        $strCate = $new['group']->find('group_cate',array(
            'cateid'=>$strGroup['cateid'],
        ));

        $strCate2 = $new['group']->find('group_cate',array(
            'cateid'=>$strGroup['cateid2'],
        ));

        $strCate3 = $new['group']->find('group_cate',array(
            'cateid'=>$strGroup['cateid3'],
        ));

        $title = 'Выбор категорий';
        include template("edit_cate");

        break;

    case "useraudit":

        $arrUserId = $new['group']->findAll('group_user_isaudit',array(
            'groupid'=>$groupid,
        ));
        foreach($arrUserId as $key=>$item){
            $arrUser[] = aac('user')->getOneUser($item['userid']);
        }

        $title = 'Модерация запросов';
        include template('edit_useraudit');
        break;

    case "userauditdo":

        $userid = intval($_GET['userid']);
        $status = intval($_GET['status']);

        if($status==0 && $userid){

            $new['group']->create('group_user',array(
                'userid'=>$userid,
                'groupid'=>$groupid,
                'addtime'=>time(),
            ));

            $count_group = $new['group']->findCount('group_user',array(
                'userid'=>$userid,
            ));
            $new['group']->update('user_info',array(
                'userid'=>$userid,
            ),array(
                'count_group'=>$count_group,
            ));

            $count_user = $new['group']->findCount('group_user',array(
                'groupid'=>$groupid,
            ));

            $new['group']->update('group',array(
                'groupid'=>$groupid,
            ),array(
                'count_user'=>$count_user,
            ));

        }

        $new['group']->delete('group_user_isaudit',array(
            'userid'=>$userid,
            'groupid'=>$groupid,
        ));

        header('Location: '.tsUrl('group','edit',array('groupid'=>$groupid,'ts'=>'useraudit')));

        break;

    case "transfer":

        $title = 'Передача группы';
        include template('edit_transfer');
        break;

    case "transferdo":


        $touserid = intval($_POST['touserid']);

        $strTouser = $new['group']->find('group_user',array(
            'userid'=>$touserid,
            'groupid'=>$groupid,
        ));

        if($strTouser==''){
            tsNotice('Пользователь не присоединился к группе. Группу можно передать только её участникам!');
        }

        $new['group']->update('group',array(
            'groupid'=>$groupid,
        ),array(
            'userid'=>$touserid,
        ));

        tsNotice('Передача группы прошла успешно!');

        break;

    case "adduser":

        $js = intval($_GET['js']);


        $userid = intval($_POST['userid']);

        if($userid==0){
            getJson('Введен неверный ID пользователя!',$js);
        }

        $isGroupUser = $new['group']->findCount('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));

        if($isGroupUser>0){
            getJson('Пользователь присоединился к группе!',$js);
        }

        $new['group']->create('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
            'addtime'=>time(),
        ));

        $msg_userid = '0';
        $msg_touserid = $userid;
        $msg_content = 'Поздравляем, вы стали участником группы «'.$strGroup['groupname'].'»! Проверьте.';
        $msg_tourl = tsUrl('group','show',array('id'=>$groupid));
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);


        getJson('Успешно выполнено!',$js,1);

        break;

    case "isadmin":

        $arrAdmin = $new['group']->findAll('group_user',array(
            'groupid'=>$groupid,
            'isadmin'=>1,
        ));

        $arrAdminUser = array();
        if($arrAdmin){
            foreach($arrAdmin as $key=>$item){
                $arrUserId[] = $item['userid'];
            }
            $userids = arr2str($arrUserId);

            $arrAdminUser = $new['group']->findAll('user_info',"`userid` in ($userids)",'addtime desc','userid,username');

        }

        $title = 'Администраторы группы';
        include template('edit_isadmin');

        break;

    case "isadmindo":

        $js = intval($_GET['js']);


        $userid = intval($_POST['userid']);

        if($userid==0){
            getJson('Введен неверный ID пользователя!',$js);
        }

        if($userid==$strGroup['userid']){
            getJson('ID пользователя не может быть ID группы!',$js);
        }

        $isGroupUser = $new['group']->findCount('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));

        if($isGroupUser==0){
            getJson('Введен ID пользователя, который не принадлежит к группе!',$js);
        }

        $new['group']->update('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ),array(
            'isadmin'=>1,
        ));

        $msg_userid = '0';
        $msg_touserid = $userid;
        $msg_content = 'Поздравляем, вы стали администратором группы «'.$strGroup['groupname'].'»! Проверьте.';
        $msg_tourl = tsUrl('group','show',array('id'=>$groupid));
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);


        getJson('Успешно выполнено!',$js,1);

        break;

    case "isadmindel":

        $js = intval($_GET['js']);


        $userid = intval($_POST['userid']);

        if($userid==0){
            getJson('Введен неверный ID пользователя!',$js);
        }

        if($userid==$strGroup['userid']){
            getJson('ID пользователя не может быть ID группы!',$js);
        }

        $isGroupUser = $new['group']->findCount('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));

        if($isGroupUser==0){
            getJson('Введен ID пользователя, который не принадлежит к группе!',$js);
        }

        $new['group']->update('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ),array(
            'isadmin'=>0,
        ));

        $msg_userid = '0';
        $msg_touserid = $userid;
        $msg_content = 'Ваш статус администратора в группе «'.$strGroup['groupname'].'» отменен! Проверьте.';
        $msg_tourl = tsUrl('group','show',array('id'=>$groupid));
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_tourl);


        getJson('Выполнено успешно!',$js,1);

        break;


    case "user":


        $guserid = intval($_GET['guserid']);


        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        $url = tsUrl('group','edit',array('ts'=>'user','groupid'=>$groupid,'page'=>''));


        $lstart = $page*40-40;

        $arr = array(
            'groupid'=>$groupid,
            'isadmin'=>0,
            'isfounder'=>0,
        );

        if($guserid){

            $arr = array(
                'userid'=>$guserid,
                'groupid'=>$groupid,
                'isadmin'=>0,
                'isfounder'=>0,
            );

        }

        $groupUserNum = $new['group']->findCount('group_user',$arr);

        $groupUser = $new['group']->findAll('group_user',$arr,'userid desc',null,$lstart.',40');

        if(is_array($groupUser)){
            foreach($groupUser as $key=>$item){
                $arrGroupUser[$key] = aac('user')->getOneUser($item['userid']);
                $arrGroupUser[$key]['endtime'] = $item['endtime'];
                $arrGroupUser[$key]['price'] = $item['price'];
            }
        }

        $pageUrl = pagination($groupUserNum, 40, $page, $url);

        $title = 'Участники';
        include template('edit_user');
        break;

    case "xuqi":

        $js = intval($_GET['js']);


        $userid = intval($_POST['userid']);
        $endtime = trim($_POST['endtime']);

        if($userid==0){
            getJson('Введен неверный ID пользователя!',$js);
        }


        if($endtime==''){
            getJson('Срок действия не может быть пустым!',$js);
        }

        if($endtime<date('Y-m-d')){
            getJson('Срок действия должен быть больше, чем сегодня!',$js);
        }


        $isGroupUser = $new['group']->findCount('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));

        if($isGroupUser==0){
            getJson('Пользователь не являются участником этой группы!',$js);
        }

        $new['group']->update('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ),array(
            'endtime'=>$endtime,
        ));

        getJson('Успешно выполнено!',$js,1);

        break;





}
