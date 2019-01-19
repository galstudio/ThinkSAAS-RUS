<?php
defined('IN_TS') or die('Access Denied.');

$userid= intval($_GET['userid']);

$strTouser = aac('user')->getOneUser($userid);

$title = 'Отправить сообщение';

include template("sendbox");
