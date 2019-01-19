<?php
defined('IN_TS') or die('Access Denied.');

$arrScoreUser = $new['user']->getScoreUser(10);
$arrFollowUser = $new['user']->getFollowUser(10);
$arrHotUser = $new['user']->getHotUser(10);
$arrNewUser = $new['user']->getNewUser(10);
$title = 'Пользователь';
$sitekey = $TS_APP['appkey'];
$sitedesc = $TS_APP['appdesc'];
include template('index');
