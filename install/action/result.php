<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$host = trim ( $_POST ['host'] );
$port = trim ( $_POST ['port'] );
$user = trim ( $_POST ['user'] );
$pwd = trim ( $_POST ['pwd'] );
$name = trim ( $_POST ['name'] );
$pre = trim ( $_POST ['pre'] );
$select_sql = trim ( $_POST ['sql'] );

define(dbprefix, $pre);

if(!function_exists('mysqli_connect') && $select_sql=='mysqli') qiMsg('Библиотека MySQLi не установлена в среде PHP!');

$arrdb = array (
		'host' => $host,
		'port' => $port,
		'user' => $user,
		'pwd' => $pwd,
		'name' => $name,
		'pre' => $pre
);

$site_title = trim ( $_POST ['site_title'] );
$site_subtitle = trim ( $_POST ['site_subtitle'] );
$site_url = trim ( $_POST ['site_url'] );
$site_pkey = trim ( $_POST ['site_pkey'] );

$email = trim ( $_POST ['email'] );
$password = trim ( $_POST ['password'] );
$username = trim ( $_POST ['username'] );

if (! preg_match ( "/^[\w_]+_$/", $pre ))
	qiMsg ( "Префикс таблицы данных не совпадает (например: ts_)" );

if ($site_title == '' || $site_subtitle == '' || $site_url == '')
	qiMsg ( "Информация о сайте не может быть пустой!" );

if ($email == '' || $password == '' || $username == '')
	qiMsg ( "Информация о пользователе не может быть пустой!" );

if (valid_email ( $email ) == false)
	qiMsg ( "Введен неверный Email!" );

include 'thinksaas/sql/'.$select_sql.'.php';

$db = new MySql ( $arrdb );
include 'thinksaas/tsApp.php';

// MySQL
include 'thinksaas/tsMySqlCache.php';
$tsMySqlCache = new tsMySqlCache ( $db );

if ($db) {

	$sql = file_get_contents ( 'install/install.sql' );
	$sql = str_replace ( 'ts_', $pre, $sql );
	$array_sql = preg_split ( "/;[\r\n]/", $sql );

	foreach ( $array_sql as $sql ) {
		$sql = trim ( $sql );
		if ($sql) {
			if (strstr ( $sql, 'CREATE TABLE' )) {
				preg_match ( '/CREATE TABLE ([^ ]*)/', $sql, $matches );
				$ret = $db->query ( $sql );
			} else {
				$ret = $db->query ( $sql );
			}
		}
	}

	$salt = md5 ( rand () );
	$userid = $db->query ( "insert into " . $pre . "user (`pwd` , `salt`,`email`) values ('" . md5 ( $salt . $password ) . "', '$salt' ,'$email');" );
	$db->query ( "insert into " . $pre . "user_info (`userid`,`username`,`email`,`isadmin`,`addtime`,`uptime`) values ('$userid','$username','$email','1','" . time () . "','" . time () . "')" );

	$db->query ( "update " . $pre . "system_options set `optionvalue`='$site_title' where `optionname`='site_title'" );
	$db->query ( "update " . $pre . "system_options set `optionvalue`='$site_subtitle' where `optionname`='site_subtitle'" );
	$db->query ( "update " . $pre . "system_options set `optionvalue`='$site_url' where `optionname`='site_url'" );
	$db->query ( "update " . $pre . "system_options set `optionvalue`='$site_url' where `optionname`='link_url'" );
	$db->query ( "update " . $pre . "system_options set `optionvalue`='$site_pkey' where `optionname`='site_pkey'" );

	$arrOptions = $db->fetch_all_assoc ( "select * from " . $pre . "system_options" );
	foreach ( $arrOptions as $item ) {
		$arrOption [$item ['optionname']] = $item ['optionvalue'];
	}

	fileWrite ( 'system_options.php', 'data', $arrOption );
    $GLOBALS['tsMySqlCache']->set ( 'system_options', $arrOption );

    //cache
    $arrCache = $db->fetch_all_assoc("select * from " . $pre . "cache");
    foreach($arrCache as $key=>$item){
        fileWrite ( $item['cachename'].'.php', 'data', $tsMySqlCache -> get($item['cachename']) );
    }

	$fp = fopen ( THINKDATA . '/config.inc.php', 'w' );
	if (! is_writable ( THINKDATA . '/config.inc.php' ))
		qiMsg ( "Файл (data/config.inc.php) недоступен для записи. Если вы используете хостинг Unix/Linux, то измените права доступа к файлу на 777. Если вы используете хостинг Windows, пожалуйста, свяжитесь с администратором, чтобы этот файл был доступен." );
	$config = "<?php\n" . "	/*\n" . "	 *Конфигурация базы данных\n" . "	 */\n" . "	\n" . "	\$TS_DB['sql']='" . $select_sql . "';\n" . "	\$TS_DB['host']='" . $host . "';\n" . "	\$TS_DB['port']='" . $port . "';\n" . "	\$TS_DB['user']='" . $user . "';\n" . "	\$TS_DB['pwd']='" . $pwd . "';\n" . "	\$TS_DB['name']='" . $name . "';\n" . "	\$TS_DB['pre']='" . $pre . "';\n" . "	define('dbprefix','" . $pre . "');\n";

	$fw = fwrite ( $fp, $config );

	$strUser ['email'] = $email;
	$strUser ['password'] = $password;

	// SESSION
	unset ( $_SESSION ['tsuser'] );
	session_destroy ();
	setcookie ( "ts_email", '', time () + 3600, '/' );
	setcookie ( "ts_uptime", '', time () + 3600, '/' );

	include 'install/html/result.html';
} else {
	include 'install/html/error.html';
}
