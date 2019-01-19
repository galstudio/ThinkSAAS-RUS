<?php
defined('IN_TS') or die('Access Denied.');

function gonggao(){
	global $tsMySqlCache;
	$strGonggao = fileRead('data/plugins_pubs_gogngao.php');
	if($strGonggao==''){
		$strGonggao = $tsMySqlCache->get('plugins_pubs_gonggao');
	}
	echo '<div class="gonggao">Объявление: <a target="_blank" href="'.$strGonggao['url'].'">'.$strGonggao['title'].'</a></div>';
}
addAction('my_right_top','gonggao');
