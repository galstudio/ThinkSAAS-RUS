<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "list":
		$arrApps = tsScanDir('plugins');
		foreach($arrApps as $key=>$item){
		    $arrAppsAbout[$item] = fileRead('app/'.$item.'/about.php');
        }
		$apps = tsFilter($_GET['apps']);
		$arrPlugins = tsScanDir('plugins/'.$apps);
		foreach($arrPlugins as $key=>$item){
			if(is_file('plugins/'.$apps.'/'.$item.'/about.php')){
				$arrPlugin[$key]['name'] = $item;
				$arrPlugin[$key]['about'] = require_once 'plugins/'.$apps.'/'.$item.'/about.php';
			}
		}
		$app_plugins = fileRead('data/'.$apps.'_plugins.php');
		if($app_plugins==''){
			$app_plugins = $tsMySqlCache->get($apps.'_plugins');
		}
		include template("plugin_list");
		break;

	case "do":
		$apps = tsFilter($_GET['apps']);
		$isused =  intval($_GET['isused']);
		$pname = tsFilter($_GET['pname']);
		$app_plugins = fileRead('data/'.$apps.'_plugins.php');
		if($app_plugins==''){
			$app_plugins = $tsMySqlCache->get($apps.'_plugins');
		}

		if($isused == '0'){
			$pkey = array_search($pname,$app_plugins);
			unset($app_plugins[$pkey]);
			fileWrite($apps.'_plugins.php','data',$app_plugins);
			$tsMySqlCache->set($apps.'_plugins',$app_plugins);
			qiMsg("Плагин успешно отключен!");
		}elseif($isused == '1'){
			array_push($app_plugins,$pname);
			if(file_exists('plugins/'.$apps.'/'.$pname.'/install.sql')){
				$sql=file_get_contents('plugins/'.$apps.'/'.$pname.'/install.sql');
				$sql=str_replace('ts_',''.dbprefix.'',$sql);
				$ret=$db->query($sql);
				 if($ret=='1')
				 {
					fileWrite($apps.'_plugins.php','data',$app_plugins);
					$tsMySqlCache->set($apps.'_plugins',$app_plugins);
					$msg='Плагин успешно включен!';
				 }else{
					 $msg=$ret;
					 }
				}else{
					fileWrite($apps.'_plugins.php','data',$app_plugins);
					$tsMySqlCache->set($apps.'_plugins',$app_plugins);
					$msg='Плагин успешно включен!';
				}
			qiMsg($msg);
		}
		break;

	case "delete":
		$apps = tsUrlCheck($_GET['apps']);
		$pname = tsUrlCheck($_GET['pname']);
		delDir('plugins/'.$apps.'/'.$pname);
		qiMsg('Плагин удален!');
		break;
}
