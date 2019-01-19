<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "set":
		$arrData = fileRead('data/plugins_pubs_wordad.php');
		if($arrData==''){
			$arrData = $tsMySqlCache->get('plugins_pubs_wordad');
		}
        include template('edit','wordad');
		break;

	case "do":
		$arrTitle = $_POST['title'];
		$arrUrl = $_POST['url'];
		foreach($arrTitle as $key=>$item){
			$title = trim($item);
			$url = trim($arrUrl[$key]);
			if($title && $url){
				$arrData[] = array(
					'title'	=> $title,
					'url'	=> $url,
				);
			}
		}
		fileWrite('plugins_pubs_wordad.php','data',$arrData);
		$tsMySqlCache->set('plugins_pubs_wordad',$arrData);
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=wordad&in=edit&ts=set');
		break;
}
