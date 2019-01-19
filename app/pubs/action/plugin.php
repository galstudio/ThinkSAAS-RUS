<?php
defined('IN_TS') or die('Access Denied.');

if(is_file('plugins/'.$app.'/'.$plugin.'/'.$in.'.php')){
	require_once('plugins/'.$app.'/'.$plugin.'/'.$in.'.php');
}else{
	qiMsg('sorry:no plugin!');
}
//index.php?app=group&ac=plugin&plugin=qq&in=do
