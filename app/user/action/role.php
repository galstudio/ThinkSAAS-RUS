<?php
defined('IN_TS') or die('Access Denied.');

$arrRole = $new['user']->findAll('user_role');
$title = 'Статусы';
include template('role');
