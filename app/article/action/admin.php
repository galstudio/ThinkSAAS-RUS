<?php
defined('IN_TS') or die('Access Denied.');

if (is_file('app/' . $TS_URL['app'] . '/action/admin/' . $mg . '.php')) {
	include_once 'app/' . $TS_URL['app'] . '/action/admin/' . $mg . '.php';
} else {
	qiMsg('sorry:no index!');
}
