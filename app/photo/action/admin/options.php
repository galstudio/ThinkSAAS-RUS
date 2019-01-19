<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		$arrOptions = $new['photo']->findAll('photo_options');
		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = stripslashes($item['optionvalue']);
		}
		include template("admin/options");
		break;
	case "do":
		$db->query("TRUNCATE TABLE `".dbprefix."photo_options`");
		foreach($_POST['option'] as $key=>$item){
			$optionname = $key;
			$optionvalue = trim($item);
			$new['photo']->create('photo_options',array(
				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,
			));
		}
		$arrOptions = $new['photo']->findAll('photo_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		fileWrite('photo_options.php','data',$arrOption);
		$GLOBALS['tsMySqlCache']->set('photo_options',$arrOption);
        //APP
        if($arrOption['appname']){
            $appkey = 'photo';
            $appname = $arrOption['appname'];
            $arrNav = include 'data/system_appnav.php';
            if(is_array($arrNav)){
                $arrNav[$appkey] = $appname;
            }else{
                $arrNav = array(
                    $appkey=>$appname,
                );
            }
            fileWrite('system_appnav.php','data',$arrNav);
            $GLOBALS['tsMySqlCache']->set('system_appnav',$arrNav);
        }
		qiMsg('Модификация прошла успешно!');
		break;
}
