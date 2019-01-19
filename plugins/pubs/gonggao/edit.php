<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "set":
		$strGonggao = fileRead('data/plugins_pubs_gonggao.php');
		if($strGonggao==''){
			$strGonggao = $tsMySqlCache->get('plugins_pubs_gonggao');
		}
        include template('edit_set','gonggao');
		break;

	case "do":
		$title = trim($_POST['title']);
		$url = trim($_POST['url']);
		$arrData = array(
			'title'=>$title,
			'url'=>$url,
		);
		fileWrite('plugins_pubs_gonggao.php','data',$arrData);
		$tsMySqlCache->set('plugins_pubs_gonggao',$arrData);
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=gonggao&in=edit&ts=set');
		break;
}
