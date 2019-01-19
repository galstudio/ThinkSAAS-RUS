<?php
defined('IN_TS') or die('Access Denied.');

function links_html(){
	global $tsMySqlCache;
	$arrLink = fileRead('data/plugins_home_links.php');
	if($arrLink==''){
		$arrLink = $tsMySqlCache->get('plugins_home_links');
	}
	echo '<div class="card">';
	echo '<div class="card-header">Рекомендуемые ссылки</div>';
	echo '<div class="card-body">';
	foreach($arrLink as $item){
		echo '<a class="fs14 mr-3" target="_blank" href="'.$item['linkurl'].'">'.$item['linkname'].'</a> ';
	}
	echo '</div></div>';
}
addAction('home_index_footer','links_html');
