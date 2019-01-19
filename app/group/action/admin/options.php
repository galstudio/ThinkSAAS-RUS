<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//конфигурация
	case "":

		$arrOptions = $new['group']->findAll('group_options');

		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = stripslashes($item['optionvalue']);
		}

		include template("admin/options");

		break;

	case "do":

		//очистка данных
		$db->query("TRUNCATE TABLE `".dbprefix."group_options`");

		foreach($_POST['option'] as $key=>$item){

			$optionname = $key;
			$optionvalue = trim($item);

			$new['group']->create('group_options',array(

				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,

			));

		}

		$arrOptions = $new['group']->findAll('group_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}

		fileWrite('group_options.php','data',$arrOption);
		$GLOBALS['tsMySqlCache']->set('group_options',$arrOption);

        //имя приложения
        if($arrOption['appname']){
            $appkey = 'group';
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

		qiMsg('Данные сохранены!');

		break;
}
