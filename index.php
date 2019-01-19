<?php
/**
 * ThinkSAAS
 * copyright (c) 2010-3000 ThinkSAAS All Rights Reserved
 * https://www.thinksaas.cn
 * Email:thinksaas@qq.com
 * QQ:1078700473
 * thinksaas
 */
define('IN_TS', true);
header('Content-Type: text/html; charset=UTF-8');

if (substr(PHP_VERSION, 0, 3)<5.4) {
    exit("Для работы ThinkSAAS требуется PHP 5.4 или выше!");
}

define('THINKROOT', dirname(__FILE__));
define('THINKAPP', THINKROOT . '/app');
define('THINKDATA', THINKROOT . '/data');
define('THINKSAAS', THINKROOT . '/thinksaas');
define('THINKINSTALL', THINKROOT . '/install');
define('THINKPLUGIN', THINKROOT . '/plugins');

//composer
require_once THINKROOT . '/vendor/autoload.php';
// ThinkSAAS
include THINKSAAS.'/thinksaas.php';

unset($GLOBALS);
