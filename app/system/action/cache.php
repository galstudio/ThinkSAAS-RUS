<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		include template('cache');
		break;

	case "delall":
		rmrf('cache/template');
		rmrf('cache/user');
		rmrf('cache/group');
		rmrf('cache/lang');
		qiMsg('Весь кеш очищен!');
		break;

	//temp
	case "deltemp":
		rmrf('cache/template');
		qiMsg('Кеш шаблонов очищен!');
		break;

	//group
	case "delgroup":
		rmrf('cache/group');
		qiMsg('Кеш групп очищен!');
		break;

	//user
	case "deluser":
		rmrf('cache/user');
		qiMsg('Кеш пользователей очищен!');
		break;

	case "dellang":
		rmrf('cache/lang');
		qiMsg('Кеш очищен!');
		break;
}
