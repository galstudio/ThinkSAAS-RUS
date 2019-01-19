<?php
defined('IN_TS') or die('Access Denied.');

$os = explode(" ", php_uname());
if(!function_exists("gd_info")){$gd = 'Не поддерживается, невозможно обработать изображения';}
if(function_exists(gd_info)) {  $gd = @gd_info();  $gd = $gd["GD Version"];  $gd ? '&nbsp; версия：'.$gd : '';}

$systemInfo = array(
	'server'	=> $_SERVER['SERVER_SOFTWARE'],
	'phpos'	=> PHP_OS,
	'phpversion'	=> PHP_VERSION,
	'mysql'	=> $db->getMysqlVersion(),
	'os' =>$os[0] .''.$os[1].' '.$os[3],
	'gd'=>$gd,
	'upload' =>'не более '.ini_get('post_max_size').',  максимум '.ini_get('upload_max_filesize')
);

//Получение имени домена
#$theAuthUrl = GetUrlToDomain($_SERVER['HTTP_HOST']);

include template("main");
