<?php
defined('IN_TS') or die('Access Denied.');

function slide(){
	$arrSlide = aac('home')->findAll('slide',array(
        'typeid'=>0,
    ),'addtime desc');
	include template('slide','slide');
}
addAction('home_index_header','slide');
