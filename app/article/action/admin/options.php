<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		$arrOptions = $new['article']->findAll('article_options');

		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = stripslashes($item['optionvalue']);
		}

		include template("admin/options");

		break;

	case "do":

		$db->query("TRUNCATE TABLE `".dbprefix."article_options`");

		foreach($_POST['option'] as $key=>$item){

			$optionname = $key;
			$optionvalue = trim($item);

			$new['article']->create('article_options',array(

				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,

			));

		}

		$arrOptions = $new['article']->findAll('article_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}

		fileWrite('article_options.php','data',$arrOption);
		$GLOBALS['tsMySqlCache']->set('article_options',$arrOption);

        if($arrOption['appname']){
            $appkey = 'article';
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

		qiMsg('Изменения успешно внесены!');

		break;
}
