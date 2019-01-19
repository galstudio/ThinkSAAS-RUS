<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		$title = 'Общий поиск';
		break;

	case "group":
		$title = 'Поиск группы';
		break;

	case "topic":
		$title = 'Поиск записи';
		break;

	case "user":
		$title = 'Поиск пользователя';
		break;

	case "article":
		$title = 'Поиск статьи';
		break;
}
include template("index");
