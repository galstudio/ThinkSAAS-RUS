<?php
/**
 * @copyright (c) ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 * @site:www.thinksaas.cn
 */
defined('IN_TS') or die('Access Denied.');

// основной конфигурационный файл. $TS_CF системная конфигурационная переменная
$TS_CF = include THINKROOT . '/thinksaas/config.php';
$TS_CF['info']['version'] = include 'upgrade/version.php';# информация о версии

// если режим отладки, то вывод предупреждений
if ($TS_CF['debug']) {
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
} else {
    error_reporting(0);
}

//ini_set("memory_limit","120M");

ini_set('display_errors', 'on');   // вывод ошибок

set_time_limit(0);

ini_set('session.cookie_path', '/');

//ошибка 404
if($TS_CF['urllock'] && $_SERVER['SERVER_NAME']!=$TS_CF['urllock']){
    echo '404 page';exit;
}

//session
if ($TS_CF['sessionpath']) {
    ini_set('session.save_path', THINKROOT . '/cache/sessions');
}

//загрузка базовых функций
include 'tsFunction.php';

//расчет времени выполнения
$time_start = getmicrotime();

//fileurl
if ($TS_CF['fileurl']['url']) {
    if ($_SERVER['HTTP_HOST'] === $TS_CF['fileurl']['url']) {
        echo '404 page';
        exit;
    }
}

//SESSION
if ($TS_CF['session']) {
    include 'tsSession.php';
    ini_set('session.save_handler', 'user');
    session_set_save_handler(
            array('tsSession', 'open'), array('tsSession', 'close'), array('tsSession', 'read'), array('tsSession', 'write'), array('tsSession', 'destroy'), array('tsSession', 'gc')
    );
}

session_start();

//Memcache
if ($TS_CF['memcache'] && extension_loaded('memcache')) {
    $TS_MC = Memcache::connect($TS_CF['memcache']['host'], $TS_CF['memcache']['port']);
}
//установка переменных
$install = isset($_GET['install']) ? $_GET['install'] : 'index';


//файл установочной конфигурации
if (!is_file('data/config.inc.php')) {
    include 'install/index.php';
    exit;
}

//обработка URL-маршрутизатора, поддержка приложениями домена второго уровня
if ($TS_CF['subdomain']) {
    ini_set("session.cookie_domain", '.' . $TS_CF['subdomain']['domain']);

    //независимая поддержка приложениями доменных имен
    if (array_search($_SERVER['HTTP_HOST'], $TS_CF['appdomain'])) {
        reurlsubdomain();
    } else {
        $arrHost = explode('.', $_SERVER['HTTP_HOST']);
        if ($arrHost[0] == 'www') {
            reurl();
        } else {
            reurlsubdomain();
        }
    }
} else {
    reurl();
}

//magic_quotes_gpc
if (get_magic_quotes_gpc() == 0) {
    $_GET = tsgpc($_GET);
    $_POST = tsgpc($_POST);
    $_COOKIE = tsgpc($_COOKIE);
    //$_FILES = tsgpc ( $_FILES );
}

//системные URL
$TS_URL = array(
    'app'=>isset($_GET['app']) ? tsUrlCheck($_GET['app']) : 'home',//APP
    'ac'=>isset($_GET['ac']) ? tsUrlCheck($_GET['ac']) : 'index',//Action
    'ts'=>isset($_GET['ts']) ? tsUrlCheck($_GET['ts']) : '',//ThinkSAAS
    'mg'=>isset($_GET['mg']) ? tsUrlCheck($_GET['mg']) : 'index',//Admin
    'my'=>isset($_GET['my']) ? tsUrlCheck($_GET['my']) : 'index',//личный кабинет
    'api'=>isset($_GET['api']) ? tsUrlCheck($_GET['api']) : 'index',//Api
    'plugin'=>isset($_GET['plugin']) ? tsUrlCheck($_GET['plugin']) : '',//plugin
    'in'=>isset($_GET['in']) ? tsUrlCheck($_GET['in']) : '',//plugin
    'tp'=>isset($_GET['tp']) ? tsUrlCheck($_GET['tp']) : '1',//tp
    'page'=>isset($_GET['page']) ? tsUrlCheck($_GET['page']) : '1',//page
    'js'=>isset($_GET['js']) ? tsUrlCheck($_GET['js']) : '1',//json
    'userkey'=>isset($_REQUEST['userkey']) ? tsUrlCheck($_REQUEST['userkey']) : '',//ID
);

$app = $TS_URL['app'];
$ac = $TS_URL['ac'];
$ts = $TS_URL['ts'];
$mg = $TS_URL['mg'];
$my = $TS_URL['my'];
$api = $TS_URL['api'];
$plugin = $TS_URL['plugin'];
$in = $TS_URL['in'];
$tp = $TS_URL['tp'];
$page = $TS_URL['page'];
$js = $TS_URL['js'];
$userkey = $TS_URL['userkey'];

//поддержка поддоменов
if ($TS_CF['subdomain'] && $TS_URL['app'] == 'home') {
    $TS_URL['app'] = array_search($_SERVER['HTTP_HOST'], $TS_CF['appdomain']);
    if ($TS_URL['app'] == '') {
        $arrHost = explode('.', $_SERVER['HTTP_HOST']);
        $TS_URL['app'] = $arrHost['0'];
        if ($TS_URL['app'] == 'www') {
            $TS_URL['app'] = 'home';
        }
    }
}

//файл конфигурации базы данных
include 'data/config.inc.php';

//файл конфигурации приложения
include 'app/' . $TS_URL['app'] . '/config.php';

//подключение к базе данных
include 'sql/' . $TS_DB['sql'] . '.php';
$db = new MySql($TS_DB);

//класс базы данных приложений
include 'thinksaas/tsApp.php';
//MySQL
include 'thinksaas/tsMySqlCache.php';
$tsMySqlCache = new tsMySqlCache($db);

//файл конфигурации сайта
$TS_SITE = fileRead('data/system_options.php');
if ($TS_SITE == '') {
    $TS_SITE = $tsMySqlCache -> get('system_options');
}

//тема
$tstheme = isset($_COOKIE['tsTheme']) ? tsUrlCheck($_COOKIE['tsTheme']) : $TS_SITE['site_theme'];

//навигация приложений
$TS_SITE['appnav'] = fileRead('data/system_appnav.php');
if ($TS_SITE['appnav'] == '') {
    $TS_SITE['appnav'] = $tsMySqlCache -> get('system_appnav');
}

//навигация личного кабинета
$TS_SITE['mynav'] = fileRead('data/system_mynav.php');
if ($TS_SITE['mynav'] == '') {
    $TS_SITE['mynav'] = $tsMySqlCache -> get('system_mynav');
}

//конфигурация приложения
if (is_file('data/' . $TS_URL['app'] . '_options.php')) {
    $TS_APP = fileRead('data/' . $TS_URL['app'] . '_options.php');
    if ($TS_APP == '') {
        $TS_APP = $tsMySqlCache -> get($TS_URL['app'] . '_options');
    }
    if ($TS_APP['isenable'] == '1' && $TS_URL['ac'] != 'admin') {
        tsNotice($TS_URL['app'] . "Приложение закрыто, пожалуйста, включите его! ");
    }
}

//пользовательское шифрование
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = sha1(uniqid(mt_rand(), TRUE));
}

if ($_REQUEST['token'] && $TS_SITE['istoken']) {
    if (tsFilter($_REQUEST['token']) != $_SESSION['token']) {
        tsNotice('Недопустимая операция!');
    }
}


//URL сайта
define('SITE_URL', $TS_SITE['site_url']);

//часовой пояс 
date_default_timezone_set($TS_SITE['timezone']);


//SESSION, $TS_USER
$TS_USER = isset($_SESSION['tsuser']) ? $_SESSION['tsuser'] : '';

//журналы
if ($TS_CF['logs']) {
    //журналы пользователей
    userlog($_POST, intval($TS_USER['userid']));
    userlog($_GET, intval($TS_USER['userid']));
}

//контроль доступа
if($TS_USER=='' && $TS_SITE['visitor'] == 1){
    if($app!='pubs' && $ac!='home' && $ac!='register' && $ac!='login' && $ac!='forgetpwd' && $ac!='resetpwd' && $app!='api'){
        tsHeaderUrl(tsUrl('pubs','home'));
    }
}

//ADMIN контроль доступа
if ($TS_URL['ac'] == 'admin' && $TS_USER['isadmin'] != 1 && $TS_URL['app'] != 'system') {
    tsHeaderUrl(SITE_URL);
}

//права доступа
if ($TS_USER['isadmin'] != 1 && $TS_URL['app'] == 'system' && $TS_URL['ac'] != 'login') {
    tsHeaderUrl(SITE_URL);
}

//управление настройками приложений
if ($TS_USER['isadmin'] != 1 && $TS_URL['in'] == 'edit') {
    tsHeaderUrl(SITE_URL);
}

//Email
if ($TS_SITE['isverify'] == 1 && intval($TS_USER['userid']) > 0 && $TS_URL['app'] != 'system' && $TS_URL['ac'] != 'admin') {
    $verifyUser = aac('user') -> find('user_info', array('userid' => intval($TS_USER['userid']), ));
    if (intval($verifyUser['isverify']) == 0 && $TS_URL['app'] != 'user' && $TS_USER['isadmin'] != 1) {
        tsHeaderUrl(tsUrl('user', 'verify'));
    }
}

//аватар
if ($TS_SITE['isface'] == 1 && intval($TS_USER['userid']) > 0 && $TS_URL['app'] != 'system' && $TS_URL['ac'] != 'admin') {
    $faceUser = aac('user') -> find('user_info', array('userid' => intval($TS_USER['userid']), ));
    if ($faceUser['face'] == '' && $TS_URL['app'] != 'user' && $TS_USER['isadmin'] != 1) {
        tsHeaderUrl(tsUrl('user', 'verify', array('ts' => 'face')));
    }
}

//автовход
if (intval($TS_USER['userid']) == 0 && $_COOKIE['ts_email'] && $_COOKIE['ts_autologin']) {

    $loginUserNum = aac('user') -> findCount('user_info', array('email' => $_COOKIE['ts_email'], 'autologin' => $_COOKIE['ts_autologin'], ));

    if ($loginUserNum > 0) {

        $loginUserData = aac('user') -> find('user_info', array('email' => $_COOKIE['ts_email'], ), 'userid,username,path,face,ip,isadmin,signin,uptime');

        if ($loginUserData['ip'] != getIp() && $TS_URL['app'] != 'user' && $TS_URL['ac'] != 'login') {
            tsHeaderUrl(tsUrl('user', 'login', array('ts' => 'out')));
        }

        //session
        $_SESSION['tsuser'] = array(
			'userid' => $loginUserData['userid'],
			'username' => $loginUserData['username'],
			'path' => $loginUserData['path'],
			'face' => $loginUserData['face'],
			'isadmin' => $loginUserData['isadmin'],
			'signin' => $loginUserData['signin'],
			'uptime' => $loginUserData['uptime'],
		);
        $TS_USER = $_SESSION['tsuser'];
    }
}

$tsHooks = array();


if ($TS_URL['app'] != 'system' && $TS_URL['app'] != 'pubs') {
    //общедоступные приложения
    $public_plugins = fileRead('data/pubs_plugins.php');
    if ($public_plugins == '') {
        $public_plugins = $tsMySqlCache -> get('pubs_plugins');
    }

    if ($public_plugins && is_array($public_plugins)) {
        foreach ($public_plugins as $item) {
            if (is_file('plugins/pubs/' . $item . '/' . $item . '.php')) {
                include 'plugins/pubs/' . $item . '/' . $item . '.php';
            }
        }
    }

    $active_plugins = fileRead('data/' . $TS_URL['app'] . '_plugins.php');
    if ($active_plugins == '') {
        $active_plugins = $tsMySqlCache -> get($TS_URL['app'] . '_plugins');
    }

    if ($active_plugins && is_array($active_plugins)) {
        foreach ($active_plugins as $item) {
            if (is_file('plugins/' . $TS_URL['app'] . '/' . $item . '/' . $item . '.php')) {
                include 'plugins/' . $TS_URL['app'] . '/' . $item . '/' . $item . '.php';
            }
        }
    }
}

$time_end = getmicrotime();

$runTime = $time_end - $time_start;
$TS_CF['runTime'] = number_format($runTime, 6);


//глобальные переменные
global $TS_CF,$TS_SITE,$TS_APP,$TS_USER,$TS_URL,$TS_MC,$db,$tsMySqlCache,$tstheme;

if (is_file('app/' . $TS_URL['app'] . '/class.' . $TS_URL['app'] . '.php')) {


    include_once 'app/' . $TS_URL['app'] . '/class.' . $TS_URL['app'] . '.php';
    $new[$TS_URL['app']] = new $TS_URL['app']($db);


    //action
    doAction('beforeAction');

    include 'thinksaas/common.php';


    if(is_file('app/'.$TS_URL['app'].'/action.'.$TS_URL['app'].'.php')){
        include_once 'app/'.$TS_URL['app'].'/action.'.$TS_URL['app'].'.php';
        $appAction = $TS_URL['app'].'Action';
        $newAction = new $appAction($db);
        if(!method_exists($newAction,$ac)){
            qiMsg( 'Неопределенный '.$ac.' метод!');
        }
        $newAction->$ac();
    }else{
        include 'app.php';
    }

} else {
    ts404();
}
