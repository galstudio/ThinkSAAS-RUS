<?php
defined('IN_TS') or die('Access Denied.');

$arrLocation = $new['location']->findAll('location');

$title = 'Все районы';
include template('all');
