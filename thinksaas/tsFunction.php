<?php
defined('IN_TS') or die('Access Denied.');
/*
 * ThinkSAAS core function
 * @copyright (c) ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 * @TIME:2010-12-18
 */
use Intervention\Image\ImageManagerStatic as Image;

/**
 * AutoAppClass
 * @app APP
 * @
 * @param $app
 * @return bool
 */
function aac($app) {

	$path = THINKAPP . '/' . $app . '/';
	if (!class_exists($app)) {
		require_once $path . 'class.' . $app . '.php';
	}
	if (!class_exists($app)) {
		return false;
	}
	$obj = new $app($GLOBALS['db']);
	return $obj;
}

/**
 * @param unknown $arr
 * @param unknown $keys
 * @param string $type
 * @return multitype:unknown
 */
function array_sort($arr, $keys, $type = 'asc') {
	$keysvalue = $new_array = array();
	foreach ($arr as $k => $v) {
		$keysvalue[$k] = $v[$keys];
	}
	if ($type == 'asc') {
		asort($keysvalue);
	} else {
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k => $v) {
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}

/**
 * ThinkSAAS Notice
 * @param unknown $notice
 * @param string $button
 * @param string $url
 * @param string $isAutoGo
 */
function tsNotice($notice, $button = 'Нажмите здесь, чтобы вернуться назад', $url = 'javascript:history.back(-1);', $isAutoGo = false) {
	global $runTime;
	$title = 'Советы: ';
	include  pubTemplate('notice');
	exit();
}

/**
 * @param unknown $msg
 * @param string $button
 * @param string $url
 * @param string $isAutoGo
 */
function qiMsg($msg, $button = 'Нажмите здесь, чтобы вернуться назад', $url = 'javascript:history.back(-1);', $isAutoGo = false) {
    echo <<<EOT
<html>
<head>
EOT;
	if ($isAutoGo) {
		echo "<meta http-equiv=\"refresh\" content=\"2;url=$url\" />";
	}
	echo <<<EOT
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Админпанель</title>
<style type="text/css">
<!--
body {
font-family: Arial;
font-size: 12px;
line-height:150%;
text-align:center;
}
a{color:#555555;}
.main {
width:500px;
background-color:#FFFFFF;
font-size: 12px;
color: #666666;
margin:100px auto 0;
list-style:none;
padding:20px;
}
.main p {
line-height: 18px;
margin: 5px 20px;
font-size:14px;
}
-->
</style>
</head>
<body>
<div class="main">
<p>$msg</p>
<p><a href="$url">$button</a></p>
</div>
</body>
</html>
EOT;
	exit();
}

/**
 * @param unknown $count
 * @param unknown $perlogs
 * @param unknown $page
 * @param unknown $url
 * @param string $suffix
 * @return string
 */
function pagination($count, $perlogs, $page, $url ,$suffix = '') {

    $urlset = $GLOBALS['TS_SITE']['site_urltype'];
    if ($urlset == 3 && !strpos($url,'index.php')) {
        $suffix = '.html';
    }

    $pnums = @ceil($count / $perlogs);
    $res = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
    $re = '';
    for ($i = $page - 5; $i <= $page + 5 && $i <= $pnums; $i++) {
        if ($i > 0) {
            if ($i == $page) {
                $re .= '<li class="page-item active"><a class="page-link">' . $i . '</a></li>';
            } else {
                $re .= '<li class="page-item"><a class="page-link" href="' . $url . $i . $suffix . '">' . $i . '</a></li>';
            }
        }
    }
    if ($page > 6)
        $re = '<li class="page-item"><a class="page-link" href="' . $url . '1' . $suffix . '" title="На главную">&laquo;</a></li>' . $re;
    if ($page + 5 < $pnums)
        $re .= '<li class="page-item"><a class="page-link" href="' . $url . $pnums . $suffix . '" title="На главную">&raquo;</a></li>';

    $re .= '</ul></nav>';

    $res .= $re;

    if ($pnums <= 1)
        $res = '';
    return $res;
}

/**
 * Email
 * @param unknown $email
 * @return boolean
 */
function valid_email($email) {
	if (preg_match('/^[A-Za-z0-9]+([._\-\+]*[A-Za-z0-9]+)*@([A-Za-z0-9-]+\.)+[A-Za-z0-9]+$/', $email)) {
		return true;
	} else {
		return false;
	}
}

/**
 * склонения слов после числительных
 */

function plural_form($number, $after) {
  $cases = array (2, 0, 1, 1, 1, 2);
  echo $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}
/**
 * @param unknown $btime
 * @param unknown $etime
 * @return string
 */
function getTime($btime, $etime = null) {
	if ($etime == null)
		$etime = time();
	if ($btime < $etime) {
		$stime = $btime;
		$endtime = $etime;
	} else {
		$stime = $etime;
		$endtime = $btime;
	}
	$timec = $endtime - $stime;
	$days = intval($timec / 86400);
	$rtime = $timec % 86400;
	$hours = intval($rtime / 3600);
	$rtime = $rtime % 3600;
	$mins = intval($rtime / 60);
	$secs = $rtime % 60;
	if ($days >= 1) {
		//return $days . ' дней назад';
		return plural_form($days , array('день назад', 'дня назад', 'дней назад'));
	}
	if ($hours >= 1) {
		//return $hours . ' часов назад';
		return plural_form($hours , array('час назад', 'часа назад', 'часов назад'));
	}

	if ($mins >= 1) {
		//return $mins . ' минут назад';
		return plural_form($mins , array('минуту назад', 'минуты назад', 'минут назад'));
	}
	if ($secs >= 1) {
		//return $secs . ' секунд назад';
		return plural_form($secs , array('секунду назад', 'секунды назад', 'секунд назад'));
	}
}

/**
 * @return Ambigous <string, unknown>
 */
function getIp() {
	if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$PHP_IP = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$PHP_IP = getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$PHP_IP = getenv('REMOTE_ADDR');
	} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$PHP_IP = $_SERVER['REMOTE_ADDR'];
	}
	preg_match("/[\d\.]{7,15}/", $PHP_IP, $ipmatches);
	$PHP_IP = $ipmatches[0] ? $ipmatches[0] : 'unknown';
	return $PHP_IP;
}

/**
 * @param unknown $text
 * @return mixed
 */
function t($text) {
	$text = tsDecode($text);
	$text = preg_replace('/\[.*?\]/is', '', $text);
	$text = cleanJs($text);
	$text = preg_replace('/\s(?=\s)/', '', $text);
	$text = preg_replace('/[\n\r\t]/', ' ', $text);
	$text = str_replace('  ', ' ', $text);
	// $text = str_replace ( ' ', '', $text );
	$text = str_replace('&nbsp;', '', $text);
	$text = str_replace('&', '', $text);
	$text = str_replace('=', '', $text);
	$text = str_replace('-', '', $text);
	$text = str_replace('#', '', $text);
	$text = str_replace('%', '', $text);
	$text = str_replace('!', '', $text);
	$text = str_replace('@', '', $text);
	$text = str_replace('^', '', $text);
	$text = str_replace('*', '', $text);
	$text = str_replace('amp;', '', $text);

	$text = str_replace('position', '', $text);

	$text = strip_tags($text);
	$text = htmlspecialchars($text);
	$text = str_replace("'", "", $text);
	return $text;
}

/**
 * @param unknown $text
 * @return string
 */
function h($text) {
	$text = trim($text);
	$text = htmlspecialchars($text);
	$text = addslashes($text);
	return $text;
}

/**
 * @param unknown $string
 * @param number $start
 * @param unknown $sublen
 * @param string $append
 * @return string
 */
function cututf8($string, $start = 0, $sublen, $append = true) {
	$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
	preg_match_all($pa, $string, $t_string);
	if (count($t_string[0]) - $start > $sublen && $append == true) {
		return join('', array_slice($t_string[0], $start, $sublen)) . "...";
	} else {
		return join('', array_slice($t_string[0], $start, $sublen));
	}
}

/**
 * @return number
 */
function getmicrotime() {
	list($usec, $sec) = explode(" ", microtime());
	return (( float )$usec + ( float )$sec);
}

/**
 * @param unknown $file
 * @param unknown $dir
 * @param unknown $data
 * @param number $isphp
 * @return boolean
 */
function fileWrite($file, $dir, $data, $isphp = 1) {

	$dfile = $dir . '/' . $file;

	if ($GLOBALS['TS_CF']['memcache'] && extension_loaded('memcache')) {
		$GLOBALS['TS_MC'] -> delete(md5($dfile));
        $GLOBALS['TS_MC'] -> set(md5($dfile), $data, 0, 172800);
	}

	!is_dir($dir) ? mkdir($dir, 0777) : '';
	if (is_file($dfile))
		unlink($dfile);
	if ($isphp == 1) {
		$data = "<?php\ndefined('IN_TS') or die('Access Denied.');\nreturn " . var_export($data, true) . ";";
	}

	file_put_contents($dfile, $data);

	return true;
}

/**
 * @param unknown $dfile
 */
function fileRead($dfile) {

	if ($GLOBALS['TS_CF']['memcache'] && extension_loaded('memcache')) {

		if ($GLOBALS['TS_MC'] -> get(md5($dfile))) {
			return $GLOBALS['TS_MC'] -> get(md5($dfile));
		} else {
			if (is_file($dfile))
				return
				include $dfile;
		}
	} else {

		if (is_file($dfile))
			return
			include $dfile;
	}
}

/**
 * @param $arr
 * @param
 * @return Ambigous <string, unknown>
 */
function arr2str($arr,$fg=',') {
	$str = '';
	$count = 1;
	if (is_array($arr)) {
		foreach ($arr as $a) {
			if ($count == 1) {
				$str .= $a;
			} else {
				$str .= $fg . $a;
			}
			$count++;
		}
	}
	return $str;
}

/**
 * @param unknown $length
 * @param number $numeric
 * @return string
 */
function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' ? mt_srand(( double ) microtime() * 1000000) : mt_srand();
	$seed = base_convert(md5(print_r($_SERVER, 1) . microtime()), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for ($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}

/**
 * @param unknown $url
 * @param unknown $proxy
 * @param unknown $timeout
 * @return mixed
 */
function getHtmlByCurl($url, $proxy, $timeout) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_PROXY, $proxy);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	return $file_contents;
}

/**
 * @param unknown $size
 * @return string
 */
function format_bytes($size) {
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size >= 1024 && $i < 4; $i++)
		$size /= 1024;
	return round($size, 2) . $units[$i];
}

/**
 * @param unknown $array
 * @return array
 */
function object2array($array) {
	if (is_object($array)) {
		$array = ( array )$array;
	}
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			$array[$key] = object2array($value);
		}
	}
	return $array;
}

/**
 *
 * @param string $file
 * @param string $content
 * @param string $mod
 * @param boolean $exit
 * @return boolean
 *
 */
function isWriteFile($file, $content, $mod = 'w', $exit = TRUE) {
	if (!@$fp = @fopen($file, $mod)) {
		if ($exit) {
			exit('Файл :<br>' . $file . '<br>Нет доступа к записи!');
		} else {
			return false;
		}
	} else {
		@flock($fp, 2);
		@fwrite($fp, $content);
		@fclose($fp);
		return true;
	}
}

/**
 * @param unknown $dir
 * @return boolean
 */
function makedir($dir) {
	return is_dir($dir) or (makedir(dirname($dir)) and mkdir($dir, 0777));
}

/**
 * @param string $file
 * @return string
 * @param array $self
 *
 */
function template($file, $plugin = '', $self = '') {

	if ($plugin) {
		$tplfile = 'plugins/' . $GLOBALS['TS_URL']['app'] . '/' . $plugin . '/' . $file . '.html';
		if (!is_file($tplfile)) {
			$tplfile = 'plugins/pubs/' . $plugin . '/' . $file . '.html';
		}
		$objfile = 'cache/template/' . $plugin . '.' . $file . '.tpl.php';
	} else if ($self) {
        $tplfile ='';
		foreach ($self as $v) {
            $tplfile .= $v . '/';
			$cache = $v . '_';
		}
		$tplfile = $tplfile . $file . '.html';
		$objfile = 'cache/template/self/' . $self[3] . '.' . $file . '.tpl.php';
	} else {
		$tplfile = 'app/' . $GLOBALS['TS_URL']['app'] . '/html/' . $file . '.html';
		$objfile = 'cache/template/' . $GLOBALS['TS_URL']['app'] . '.' . $file . '.tpl.php';
	}

	if (@filemtime($tplfile) > @filemtime($objfile)) {
		require_once 'thinksaas/tsTemplate.php';
		$T = new tsTemplate();

		$T -> complie($tplfile, $objfile);
	}

	return $objfile;
}

/**
 * @param unknown $file
 * @return string
 */
function pubTemplate($file) {
	$tplfile = 'public/html/' . $file . '.html';
	$objfile = 'cache/template/public.' . $file . '.tpl.php';

	if (@filemtime($tplfile) > @filemtime($objfile)) {

		require_once 'thinksaas/tsTemplate.php';
		$T = new tsTemplate();

		$T -> complie($tplfile, $objfile);
	}

	return $objfile;
}

/**
 *
 * @param string $hook
 * @param string $actionFunc
 * @return boolearn
 *
 */
function addAction($hook, $actionFunc) {
	global $tsHooks;
	if (!@in_array($actionFunc, $tsHooks[$hook])) {
		$tsHooks[$hook][] = $actionFunc;
	}

	return true;
}

/**
 * @param string $hook
 */
function doAction($hook) {
	global $tsHooks;
	$args = array_slice(func_get_args(), 1);
	if (isset($tsHooks[$hook])) {
		foreach ($tsHooks [$hook] as $function) {
			$string = call_user_func_array($function, $args);
		}
	}

	if ($GLOBALS['TS_CF']['hook'])
		var_dump($hook);
}

function createFolders($path) {
	if (!file_exists($path)) {
		createFolders(dirname($path));
		mkdir($path, 0777);
	}
}

/**
 * @param string $dir
 * @return boolean
 */
function delDir($dir = '') {
	if (empty($dir)) {
		$dir = rtrim(RUNTIME_PATH, '/');
	}
	if (substr($dir, -1) == '/') {
		$dir = rtrim($dir, '/');
	}
	if (!file_exists($dir))
		return true;
	if (!is_dir($dir) || is_link($dir))
		return unlink($dir);
	foreach (scandir ( $dir ) as $item) {
		if ($item == '.' || $item == '..')
			continue;
		if (!delDir($dir . "/" . $item)) {
			chmod($dir . "/" . $item, 0777);
			if (!delDir($dir . "/" . $item))
				return false;
		};
	}
	return rmdir($dir);
}

/**
 * @return string
 */
function getHttpUrl() {
	$arrUri = explode('index.php', $_SERVER['REQUEST_URI']);
	$site_url = 'http://' . $_SERVER['HTTP_HOST'] . $arrUri[0];
	return $site_url;
}

/**
 * @param string $str
 * @return string
 */
function md10($str = '') {
	return substr(md5($str), 10, 10);
}

/**
 * @param unknown $file
 * @param unknown $app
 * @param unknown $w
 * @param unknown $h
 * @param string $path
 * @param string $c
 * @return void|string
 */
function tsXimg($file, $app, $w, $h, $path = '', $c = '0') {

    if (!$file) {
        return false;
    } else {

        $arrInfo = explode('/', $file);
        $name = end($arrInfo);

        $arrType = explode('.',$name);
        $type = end($arrType);



        $cpath = 'cache/' . $app . '/' . $path . '/' . md5($w . $h . $app . $name) . '.jpg';

        if (!is_file($cpath)) {

            Image::configure(array('driver' => 'gd'));//gd or imagick

            createFolders('cache/' . $app . '/' . $path);
            $dest = 'uploadfile/' . $app . '/' . $file;
            $arrImg = getimagesize($dest);

            $img = Image::make($dest);

            if ($arrImg[0] <= $w) {

                if($c){
                    if($w && $h){
                        $img->fit($w, $h);
                    }elseif($w && $h==''){
                        $img->resize($w, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                }

            } else {

                if($w && $h){
                    $img->fit($w, $h);
                }elseif($w && $h==''){
                    $img->resize($w, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

            }


            if($arrImg[0]>400 && $w>400 && in_array($type,array('jpg','jpeg','png'))){
                $watermark = Image::make('public/images/sy.png');
                $img->insert($watermark, 'bottom-left',10,10);
            }


            $img->save($cpath);

        }

        return SITE_URL . $cpath;

    }

}

/**
 * @param unknown $file
 * @param unknown $app
 * @param unknown $w
 * @param unknown $h
 * @param unknown $path
 * @return boolean
 */
function tsDimg($file, $app, $w, $h, $path) {

	$info = explode('/', $file);
	$name = $info[2];

    $cpath = 'cache/' . $app . '/' . $path . '/' . md5($w . $h . $app . $name) . '.jpg';

	unlink($cpath);

	return true;
}

/**
 * gzip
 * @param unknown $content
 * @return string
 */
function ob_gzip($content) {
	if (!headers_sent() && extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) {
		// $content = gzencode($content." \n//Эта страница сжата",9);
		$content = gzencode($content, 9);
		header("Content-Encoding: gzip");
		header("Vary: Accept-Encoding");
		header("Content-Length: " . strlen($content));
	}
	return $content;
}

/**
 * tsUrl
 * (1)index.php?app=group&ac=topic&topicid=1
 * (2)index.php/group/topic/topicid-1
 * (3)group-topic-topicid-1.html //rewrite 1
 * (4)group/topic/topicid-1 //rewrite 2
 * @param unknown $app
 * @param string $ac
 * @param unknown $params
 * @return string
 */
function tsUrl($app, $ac = '', $params = array()) {

	$urlset = $GLOBALS['TS_SITE']['site_urltype'];
    $str ='';
	if ($urlset == 1) {
		foreach ($params as $k => $v) {
			$str .= '&' . $k . '=' . $v;
		}
		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = '?ac=' . $ac;
			} else {
				$ac = '&ac=' . $ac;
			}
		}
		if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
			$url = 'index.php' . $ac . $str;
		} else {
			$url = 'index.php?app=' . $app . $ac . $str;
		}
	} elseif ($urlset == 2) {

		foreach ($params as $k => $v) {
            $str .= '/' . $k . '-' . $v;
		}
		if ($ac == '') {
			$ac = '';
		} else {
			$ac = '/' . $ac;
		}

		if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
			$url = 'index.php' . $ac . $str;
		} else {
			$url = 'index.php/' . $app . $ac . $str;
		}
	} elseif ($urlset == 3) {
		foreach ($params as $k => $v) {
			$str .= '-' . $k . '-' . $v;
		}

		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = $ac;
			} else {
				$ac = '-' . $ac;
			}
		}

		$page = strpos($str, 'page');

		if ($page) {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$url = $ac . $str;
			} else {
				$url = $app . $ac . $str;
			}
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$url = $ac . $str . '.html';
			} else {
				$url = $app . $ac . $str . '.html';
			}
		}
	} elseif ($urlset == 4) {
		foreach ($params as $k => $v) {
			$str .= '/' . $k . '-' . $v;
		}
		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = $ac;
			} else {
				$ac = '/' . $ac;
			}
		}
		if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
			$url = $ac . $str;
		} else {
			$url = $app . $ac . $str;
		}
	} elseif ($urlset == 5) {
		foreach ($params as $k => $v) {
			$str .= '/' . $k . '/' . $v;
		}
		$str = str_replace('/id', '', $str);
		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = $ac;
			} else {
				$ac = '/' . $ac;
			}
		}
		if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
			$url = $ac . $str;
		} else {
			$url = $app . $ac . $str;
		}
	} elseif ($urlset == 6) {
		foreach ($params as $k => $v) {
			$str .= '/' . $k . '/' . $v;
		}

		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = $ac;
			} else {
				$ac = '/' . $ac;
			}
		}
		if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
			$url = $ac . $str;
		} else {
			$url = $app . $ac . $str;
		}
	} elseif ($urlset == 7) {
		foreach ($params as $k => $v) {
			$str .= '/' . $k . '/' . $v;
		}
		$str = str_replace('/id', '', $str);
		if ($ac == '') {
			$ac = '';
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$ac = $ac;
			} else {
				$ac = '/' . $ac;
			}
		}

		$page = strpos($str, 'page');

		if ($page) {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				$url = $ac . $str;
			} else {
				$url = $app . $ac . $str;
			}
		} else {
			if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app']) || $GLOBALS['TS_CF']['appdomain'][$app]) {
				if ($ac == '') {
					$url = '';
				} else {
					$url = $ac . $str . '/';
				}
			} else {
				$url = $app . $ac . $str . '/';
			}
		}
	}
	if ($GLOBALS['TS_CF']['subdomain'] && in_array($app, $GLOBALS['TS_CF']['subdomain']['app'])) {
		return 'http://' . $app . '.' . $GLOBALS['TS_CF']['subdomain']['domain'] . '/' . $url;
	} elseif ($GLOBALS['TS_CF']['appdomain'][$app]) {
		return 'http://' . $GLOBALS['TS_CF']['appdomain'][$app] . '/' . $url;
	} else {
		return SITE_URL . $url;
	}
}

/**
 * reurl
 */
function reurl() {
	global $tsMySqlCache;
	$options = fileRead('data/system_options.php');

	if ($options == '') {
		$options = $tsMySqlCache -> get('system_options');
	}

	$scriptName = explode('index.php', $_SERVER['SCRIPT_NAME']);

	$rurl = substr($_SERVER['REQUEST_URI'], strlen($scriptName[0]));

	if (strpos($rurl, 'index.php?') === false  || strpos ( $rurl, '?openid=' ) == true  || strpos ( $rurl, '?from=' ) == true){

		if (preg_match('/index.php/i', $rurl)) {
			$rurl = str_replace('index.php', '', $rurl);

			$rurl = substr($rurl, 1);
			$params = $rurl;
		} else {
			$params = $rurl;
		}

		if ($rurl) {

            if($options['site_urltype'] == 2) {
                //форма: index.php/group/topic/id-1
                $params = explode('/', $params);

                foreach ($params as $p => $v) {
                    switch ($p) {
                        case 0 :
                            $_GET['app'] = $v;
                            break;
                        case 1 :
                            $_GET['ac'] = $v;
                            break;

                            // TAG
                            if ($_GET['ac'] == 'tag') {
                                $_GET['id'] = $v;
                                break;
                            }

                        default :
                            $kv = explode('-', $v);
                            if (count($kv) > 1) {
                                $_GET[$kv[0]] = $kv[1];
                            } else {
                                $_GET['params' . $p] = $kv[0];
                            }

                            break;
                    }
                }
            }elseif ($options['site_urltype'] == 3) {
				// http://localhost/group-topic-id-1.html
				$params = explode('.', $params);

				$params = explode('-', $params[0]);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
                            if($v=='?from=singlemessage' || $v=='?from=groupmessage' || $v=='?from=timeline' || $v=='?tdsourcetag=s_pctim_aiomsg' || $v=='?_wv=1031' || $v=='?tdsourcetag=s_pcqq_aiomsg') $v='home';
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							break;
						default :
							if ($v)
								$kv[] = $v;

							break;
					}
				}

				$ck = count($kv) / 2;

				if ($ck >= 2) {
					//$arrKv = array_chunk($kv, $ck);
					$arrKv = array_chunk($kv, 2);
					foreach ($arrKv as $key => $item) {
						$_GET[$item[0]] = $item[1];
					}
				} elseif ($ck == 1) {
					$_GET[$kv[0]] = $kv[1];
				} else {
				}
			} elseif ($options['site_urltype'] == 4) {
				// http://localhost/group/topic/id-1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							break;
						default :
							$kv = explode('-', $v);

							if (count($kv) > 1) {
								$_GET[$kv[0]] = $kv[1];
							} else {
								$_GET['params' . $p] = $kv[0];
							}
							break;
					}
				}
			} elseif ($options['site_urltype'] == 5) {

                $params = explode('?',$params);
                $otherParams = $params[1];
                $params = explode('/', $params[0]);
                $arrOther = explode('&',$otherParams);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							if (empty($_GET['ac']))
								$_GET['ac'] = 'index';
							break;
						case 2 :
							if ((( int )$v) > 0) {
								$_GET['id'] = $v;
								break;
							}
							// TAG
							if ($_GET['ac'] == 'tag') {
								$_GET['id'] = $v;
								break;
							}

						default :
							$_GET[$v] = $params[$p + 1];
							break;
					}
				}


                if($arrOther){
                    foreach($arrOther as $key=>$item){
                        $arrKv = explode('=',$item);
                        $_GET[$arrKv[0]] = $arrKv[1];
                    }
                }


			} elseif ($options['site_urltype'] == 6) {
				// http://localhost/group/topic/id/1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							break;
						default :
							$_GET[$v] = $params[$p + 1];
							break;
					}
				}
			} elseif ($options['site_urltype'] == 7) {
				// http://localhost/group/topic/1/
				$params = explode('/', $params);
				//var_dump($params);
				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
                            if($v=='?from=singlemessage' || $v=='?from=groupmessage' || $v=='?from=timeline' || $v=='?tdsourcetag=s_pctim_aiomsg' || $v=='?_wv=1031' || $v=='?tdsourcetag=s_pcqq_aiomsg') $v='home';
							$_GET['app'] = $v;
							break;
						case 1 :
							$_GET['ac'] = $v;
							if (empty($_GET['ac']))
								$_GET['ac'] = 'index';
							break;
						case 2 :
							if ((( int )$v) > 0) {
								$_GET['id'] = $v;
								break;
							}
							//TAG
							if ($_GET['ac'] == 'tag') {
								$_GET['id'] = $v;
								break;
							}

						default :
							$_GET[$v] = $params[$p + 1];

							break;
					}
				}
			}
		}
	}

    /*
	if ($_GET['app'] == '' && $_GET['ac'] == '' && $rurl) {
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		echo '404 page by <a href="http://www.thinksaas.cn/">www.thinksaas.cn</a>';
		exit ;
	}
    */

}

/**
 * APP
 */
function reurlsubdomain() {
	global $tsMySqlCache;
	$options = fileRead('data/system_options.php');
	if ($options == '') {
		$options = $tsMySqlCache -> get('system_options');
	}

	$scriptName = explode('index.php', $_SERVER['SCRIPT_NAME']);
	$rurl = substr($_SERVER['REQUEST_URI'], strlen($scriptName[0]));

	if (strpos($rurl, '?') == false) {

		if (preg_match('/index.php/i', $rurl)) {
			$rurl = str_replace('index.php', '', $rurl);
			$rurl = substr($rurl, 1);
			$params = $rurl;
		} else {
			$params = $rurl;
		}

		if ($rurl) {

			if ($options['site_urltype'] == 3) {
				// http://group.thinksaas.cn/topic-id-1.html
				$params = explode('.', $params);

				$params = explode('-', $params[0]);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							break;
						default :
							if ($v)
								$kv[] = $v;

							break;
					}
				}

				$ck = count($kv) / 2;

				if ($ck >= 2) {
					$arrKv = array_chunk($kv, $ck);
					foreach ($arrKv as $key => $item) {
						$_GET[$item[0]] = $item[1];
					}
				} elseif ($ck == 1) {
					$_GET[$kv[0]] = $kv[1];
				} else {
				}
			} elseif ($options['site_urltype'] == 4) {
				// http://group.thinksaas.cn/topic/id-1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							break;
						default :
							$kv = explode('-', $v);

							if (count($kv) > 1) {
								$_GET[$kv[0]] = $kv[1];
							} else {
								$_GET['params' . $p] = $kv[0];
							}
							break;
					}
				}
			} elseif ($options['site_urltype'] == 5) {
				// http://group.thinksaas.cn/topic/1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							if (empty($_GET['ac']))
								$_GET['ac'] = 'index';
							break;
						case 1 :
							if ((( int )$v) > 0) {
								$_GET['id'] = $v;
								break;
							}
						default :
							$_GET[$v] = $params[$p + 1];
							break;
					}
				}
			} elseif ($options['site_urltype'] == 6) {
				// http://group.thinksaas.cn/topic/id/1
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							break;
						default :
							$_GET[$v] = $params[$p + 1];
							break;
					}
				}
			} elseif ($options['site_urltype'] == 7) {
				// http://group.thinksaas.cn/topic/1/
				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							if (empty($_GET['ac']))
								$_GET['ac'] = 'index';
							break;
						case 1 :
							if ((( int )$v) > 0) {
								$_GET['id'] = $v;
								break;
							}
						default :
							$_GET[$v] = $params[$p + 1];
							break;
					}
				}
			} else {

				$params = explode('/', $params);

				foreach ($params as $p => $v) {
					switch ($p) {
						case 0 :
							$_GET['ac'] = $v;
							break;
						default :
							$kv = explode('-', $v);
							if (count($kv) > 1) {
								$_GET[$kv[0]] = $kv[1];
							} else {
								$_GET['params' . $p] = $kv[0];
							}
							break;
					}
				}
			}
		}
	}
}

/**
 * @param unknown $file
 * @return number
 */
function iswriteable($file) {
	if (is_dir($file)) {
		$dir = $file;
		if ($fp = fopen("$dir/test.txt", 'w')) {
			fclose($fp);
			unlink("$dir/test.txt");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	} else {
		if ($fp = fopen($file, 'a+')) {
			fclose($fp);
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

/**
 * @param unknown $dir
 */
function delDirFile($dir) {
	$arrFiles = dirList($dir, 'files');
	foreach ($arrFiles as $item) {
		unlink($dir . '/' . $item);
	}
}

/**
 * @param unknown $files	$_FILES['photo']
 * @param unknown $projectid	$userid
 * @param unknown $dir
 * @param unknown $uptypes array('jpg','png','gif')
 * @return multitype:string unknown mixed |boolean
 */
function tsUpload($files, $projectid, $dir, $uptypes) {

	if ($files['size'] > 0) {

		if(in_array('png',$uptypes) || in_array('jpg',$uptypes) || in_array('gif',$uptypes) || in_array('jpeg',$uptypes)){

            $type = getImagetype($files['tmp_name']);

            if(!in_array($type,$uptypes)){
                tsNotice('Недопустимая операция');
            }

            if($GLOBALS['TS_SITE']['photo_size']){
				$upsize = $GLOBALS['TS_SITE']['photo_size']*1048576;

				if($files ['size']>$upsize){
					tsNotice('Загружаемое изображение не может быть больше '.$GLOBALS['TS_SITE']['photo_size'].' Мб!');
				}

			}

		}

		$menu2 = intval($projectid / 1000);

		$menu1 = intval($menu2 / 1000);

		$path = $menu1 . '/' . $menu2;

		$dest_dir = 'uploadfile/' . $dir . '/' . $path;

		createFolders($dest_dir);

		//$ext = pathinfo($files['name'],PATHINFO_EXTENSION);

		$arrType = explode('.', strtolower($files['name']));

		$type = end($arrType);

		if (in_array($type, $uptypes)) {

			$name = $projectid . '.' . $type;

			$dest = $dest_dir . '/' . $name;

			unlink($dest);
			move_uploaded_file($files['tmp_name'], mb_convert_encoding($dest, "gb2312", "UTF-8"));

			chmod($dest, 0777);

			$filesize = filesize($dest);
			if (intval($filesize) > 0) {
				return array('name' => tsFilter($files['name']), 'path' => $path, 'url' => $path . '/' . $name, 'type' => $type, 'size' => tsFilter($files['size']));
			} else {
				return false;
			}
		} else {
			return false;
		}

	}
}

function tsUploadUrl($fileurl, $projectid, $dir, $uptypes) {
	$menu2 = intval($projectid / 1000);
	$menu1 = intval($menu2 / 1000);
	$path = $menu1 . '/' . $menu2;
	$dest_dir = 'uploadfile/' . $dir . '/' . $path;
	createFolders($dest_dir);
	$arrType = explode('.', $fileurl);
	$type = array_pop($arrType);
	if (in_array($type, $uptypes)) {
		$name = $projectid . '.' . $type;
		$dest = $dest_dir . '/' . $name;
		unlink($dest);
		$img = file_get_contents($fileurl);
		file_put_contents($dest, $img);
		chmod($dest, 0777);
		$filesize = filesize($dest);
		if (intval($filesize) > 0) {
			return array('name' => $name, 'path' => $path, 'url' => $path . '/' . $name, 'type' => $type, 'size' => $filesize, );
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function tsUploadCopy($dfile,$projectid, $dir){
	$menu2 = intval($projectid / 1000);
	$menu1 = intval($menu2 / 1000);
	$path = $menu1 . '/' . $menu2;
	$dest_dir = 'uploadfile/' . $dir . '/' . $path;
	createFolders($dest_dir);
	$arrType = explode('.', strtolower($dfile));
	$type = array_pop($arrType);
	$name = $projectid . '.' . $type;
	$dest = $dest_dir . '/' . $name;

	unlink($dest);
	copy($dfile, $dest);
	unlink($dfile);

	chmod($dest, 0777);
	return array(
		'path' => $path,
		'url' => $path . '/' . $name
	);
}


/**
 * @param unknown $dir
 * @param string $isDir
 * @return mixed
 */
function tsScanDir($dir, $isDir = null) {
	if ($isDir == null) {
		$dirs = array_filter(glob($dir . '/' . '*'), 'is_dir');
	} else {
		$dirs = array_filter(glob($dir . '/' . '*'), 'is_file');
	}

	foreach ($dirs as $key => $item) {
		$y = explode('/', $item);
		$arrDirs[] = array_pop($y);
	}

	return $arrDirs;
}

/**
 * @param unknown $dir
 */
function rmrf($dir) {
	foreach (glob ( $dir ) as $file) {
		if (is_dir($file)) {
			rmrf("$file/*");
			rmdir($file);
		} else {
			unlink($file);
		}
	}
}

/**
 * @param unknown $content
 * @return mixed
 */
function urlcontent($content) {
	$pattern = '/(http:\/\/|https:\/\/|ftp:\/\/)([\w:\/\.\?=&-_#]+)/is';
	$content = @preg_replace($pattern, '<a rel="nofollow" target="_blank" href="\1\2">\1\2</a>', $content);
	return $content;
}

/**
 * UTF-8
 * @param unknown $serial_str
 * @return mixed
 */
function mb_unserialize($serial_str, $t = NULL) {
    $serial_str = @preg_replace_callback('!s:(\d+):"(.*?)";!s', function( $m ){
        return 's:'.strlen($m[2]).':"'.$m[2].'";';
    }, $serial_str);
    $serial_str = str_replace("\r", "", $serial_str);
    return unserialize($serial_str);
}
/**
 * ASC
 * @param unknown $serial_str
 * @return mixed
 */
function asc_unserialize($serial_str) {
    $serial_str = @preg_replace_callback('!s:(\d+):"(.*?)";!s', function( $m ){
        return 's:'.strlen($m[2]).':"'.$m[2].';"';
    }, $serial_str);
    $serial_str = str_replace("\r", "", $serial_str);
    return unserialize($serial_str);
}

/**
 * @param unknown $projectid
 * @param unknown $dir
 * @param unknown $type
 * @return multitype:string unknown
 */
function tsXupload($projectid, $dir, $type) {
	$menu2 = intval($projectid / 1000);
	$menu1 = intval($menu2 / 1000);
	$path = $menu1 . '/' . $menu2;
	$dest_dir = 'uploadfile/' . $dir . '/' . $path;
	createFolders($dest_dir);

	$name = $projectid . '.' . $type;

	$dest = $dest_dir . '/' . $name;

	unlink($dest);

	$xmlstr = $GLOBALS[HTTP_RAW_POST_DATA];
	if (empty($xmlstr))
		$xmlstr = file_get_contents('php://input');
	$jpg = $xmlstr;
	$file = fopen($dest, "w");
	fwrite($file, $jpg);
	fclose($file);

	chmod($dest, 0777);

	return array('name' => $name, 'path' => $path, 'url' => $path . '/' . $name, 'type' => $type);
}

/**
 * @param unknown $file
 * @param unknown $data
 */
function logging($file, $data) {
	!is_dir('tslogs') ? mkdir('tslogs', 0777) : '';
	$dfile = 'tslogs/' . $file;

	$filesize = abs(filesize($dfile));

	if ($filesize > 1024000) {
		rename($dfile, $dfile . date('His'));
	}

	$fd = fopen($dfile, "a+");
	fputs($fd, $data);
	fclose($fd);
}

/**
 * @param unknown $array
 * @param unknown $userid
 */
function userlog(&$array, $userid) {
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			if (!is_array($value)) {
				$data = "UserId:" . $userid . "\n";
				$data .= "IP:" . getIp() . "\n";
				$data .= "TIME:" . date('Y-m-d H:i:s') . "\n";
				$data .= "URL:" . $_SERVER['REQUEST_URI'] . "\n";
				$data .= "DATA:" . $data . "\n";
				$data .= "--------------------------------------\n";
				logging(date('Ymd') . '-' . $userid . '.txt', $data);
			} else {
                userlog($array[$key],$userid);
			}
		}
	}
}

/**
 * @param unknown $text
 * @return mixed
 */
function cleanJs($text) {
	$text = trim($text);
	//$text = stripslashes ( $text );
	$text = @preg_replace('/<!--?.*-->/', '', $text);
	$text = @preg_replace('/<\?|\?>/', '', $text);
	// js
	$text = @preg_replace('/<script?.*\/script>/', '', $text);
	// html
	$text = @preg_replace('/<\/?(html|head|meta|link|base|body|title|style|script|form|iframe|frame|frameset|math|maction|marquee)[^><]*>/i', '', $text);
	// lang js
	while (preg_match('/(<[^><]+)(data|onmouse|onexit|onclick|onkey|onsuspend|onabort|onactivate|onafterprint|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onblur|onbounce|oncellchange|onchange|onclick|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondblclick|ondeactivate|ondrag|ondragend|ondragenter|ondragleave|ondragover|ondragstart|ondrop|onerror|onerrorupdate|onfilterchange|onfinish|onfocus|onfocusin|onfocusout|onhelp|onkeydown|onkeypress|onkeyup|onlayoutcomplete|onload|onlosecapture|onmousedown|onmouseenter|onmouseleave|onmousemove|onmouseout|onmouseover|onmouseup|onmousewheel|onmove|onmoveend|onmovestart|onpaste|onpropertychange|onreadystatechange|onreset|onresize|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onselect|onselectionchange|onselectstart|onstart|onstop|onsubmit|onunload)[^><]+/i', $text, $mat)) {
		$text = str_replace($mat[0], $mat[1], $text);
	}
	while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
		$text = str_replace($mat[0], $mat[1] . $mat[3], $text);
	}
	return $text;
}

/**
 * @param unknown $text
 * @return mixed
 */
function tsClean($text) {
	$text = stripslashes(trim($text));
	//$text = br2nl($text); //br /n

	///////XSS start
	require_once 'thinksaas/xsshtml.class.php';
	$xss = new XssHtml($text);
	$text = $xss -> getHtml();
	//$text = substr ($text, 4);//<p>
	//$text = substr ($text, 0,-5);//</p>
	///////XSS end

	//$text = html_entity_decode($text,ENT_NOQUOTES,"utf-8");//HTML
	//$text = strip_tags($text); //HTML PHP
	//$text = cleanJs ( $text );

	$text = htmlentities($text, ENT_NOQUOTES, "utf-8");
	//HTML

	return $text;
}

/*
 * tsClean
 */
function tsCleanContent($text){
    $text = stripslashes(trim($text));
    $text = htmlentities($text, ENT_NOQUOTES, "utf-8");
    return $text;
}

/*
 * @text
 * @tp
 * @url URL
 */
function tsDecode($text, $tp = 1) {
    $text = trim($text);
	$text = html_entity_decode(stripslashes($text), ENT_NOQUOTES, "utf-8");
	$text = str_replace('<br /><br />', '<br />', $text);

	$arrText = explode('_ueditor_page_break_tag_', $text);

	if ($arrText) {
		$tp = $tp - 1;
		$text = $arrText[$tp];
	}

	return $text;
}

function tsTitle($title) {
	$title = stripslashes($title);
	$title = htmlspecialchars($title);
	return $title;
}

function tsCutContent($text, $length = 50) {
	$text = cututf8(t(tsDecode($text)), 0, $length);
	return $text;
}

/*
 * tpCount()
 */
function tpCount($text) {
	$arrText = explode('_ueditor_page_break_tag_', $text);
	return count($arrText);
}

function tpPage($text, $app, $ac, $arr) {
	$tpCount = tpCount($text);
	$tpUrl = '';
	if ($tpCount > 1) {
		$tpUrl = '<div class="page">';
		for ($i = 1; $i <= $tpCount; $i++) {
			$arr['tp'] = $i;
			$tpUrl .= '<a rel="nofollow" href="' . tsUrl($app, $ac, $arr) . '">' . $i . '</a>';
		}
		$tpUrl .= '</div>';
	}
	return $tpUrl;
}

/**
 * @param unknown $str
 * @return number
 */
function count_string_len($str) {
	// return (strlen($str)+mb_strlen($str,'utf-8'))/2; //php_mbstring.dll
	$name_len = strlen($str);
	$temp_len = 0;
	for ($i = 0; $i < $name_len; ) {
		if (strpos('abcdefghijklmnopqrstvuwxyz0123456789', $str[$i]) === false) {
			$i = $i + 3;
			$temp_len += 2;
		} else {
			$i = $i + 1;
			$temp_len += 1;
		}
	}
	return $temp_len;
}

/**
 * @param unknown $value
 * @return Ambigous <string, mixed>
 */
function tsFilter($value) {
	$value = trim($value);
	//SQl
	$words = array();
	$words[] = "add ";
	$words[] = "and ";
	$words[] = "count ";
	$words[] = "order ";
	$words[] = "table ";
	$words[] = "by ";
	$words[] = "create ";
	$words[] = "delete ";
	$words[] = "drop ";
	$words[] = "from ";
	$words[] = "grant ";
	$words[] = "insert ";
	$words[] = "select ";
	$words[] = "truncate ";
	$words[] = "update ";
	$words[] = "use ";
	$words[] = "--";
	$words[] = "#";
	$words[] = "group_concat";
	$words[] = "column_name";
	$words[] = "information_schema.columns";
	$words[] = "table_schema";
	$words[] = "union ";
	$words[] = "where ";
	$words[] = "alert";
	$value = strtolower($value);
	foreach ($words as $word) {
		if (strstr($value, $word)) {
			$value = str_replace($word, '', $value);
		}
	}

	return $value;
}

function tsgpc(&$array) {
	if (is_array($array)) {
		foreach ($array as $k => $v) {
			$array[$k] = tsgpc($v);
		}
	} else if (is_string($array)) {
		//addslashes
		//$array = addslashes ( closetags($array) );
		$array = addslashes($array);
	} else if (is_numeric($array)) {
		$array = intval($array);
	}
	return $array;
}

function closetags($html) {
	preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
	$openedtags = $result[1];
	preg_match_all('#</([a-z]+)>#iU', $html, $result);
	$closedtags = $result[1];
	$len_opened = count($openedtags);
	$len_closed = count($closedtags);
	if ($len_closed == $len_opened) {
		return $html;
	}
	$openedtags = array_reverse($openedtags);
	for ($i = 0; $i < $len_opened; $i++) {
		if (!in_array($openedtags[$i], $closedtags)) {
			$html .= '</' . $openedtags[$i] . '>';
		} else {
			unset($closedtags[array_search($openedtags[$i], $closedtags)]);
		}
	}
	return $html;
}

/*
 * url
 * @parameter  $app,$ac,$ts
 */
function tsUrlCheck($parameter) {

	$parameter = trim($parameter);
	if($parameter){
        $arrStr = str_split($parameter);
        $strOk = '%-_1234567890abcdefghijklmnopqrstuvwxyz';
        foreach ($arrStr as $key => $item) {
            if (stripos($strOk, $item) === false) {
                //qiMsg(URL);
                header ( "HTTP/1.1 404 Not Found" );
                header ( "Status: 404 Not Found" );
                #header('Location: /');
                exit;
            }
        }
        return $parameter;
    }

}

function ludou_width_height($content) {
	$images = array();
	preg_match_all('/<img (.*?)\/>/', $content, $images);
	if (!empty($images)) {
		foreach ($images[1] as $index => $value) {
			$img = array();
			preg_match_all('/(width)=("[^"]*")/i', $images[1][$index], $img);

			if (!empty($img[2])) {
				$width = trim($img[2][0], '"');
				if ($width > 630) {
					$new_img = @preg_replace('/(width)=("[^"]*")/i', 'width="630"', $images[0][$index]);
					$content = str_replace($images[0][$index], $new_img, $content);

					$new_img2 = @preg_replace('/(height)=("[^"]*")/i', 'height="100%"', $new_img);
					$content = str_replace($new_img, $new_img2, $content);
				}
			}
		}
	}
	return $content;
}

/**
 * DZ
 * @param $title string
 * @param $content string
 * @param $encode string API
 * @return  array
 */
function dz_segment($title = '', $content = '', $encode = 'utf-8') {
	if ($title == '') {
		return false;
	}
	$title = rawurlencode(strip_tags($title));
	$content = strip_tags($content);
	if (strlen($content) > 2400) {
		$content = mb_substr($content, 0, 800, $encode);
	}
	$content = rawurlencode($content);
	$url = 'http://keyword.discuz.com/related_kw.html?title=' . $title . '&content=' . $content . '&ics=' . $encode . '&ocs=' . $encode;
	$xml_array = simplexml_load_file($url);
	//XML
	$result = $xml_array -> keyword -> result;
	$data = array();
	foreach ($result->item as $key => $value) {
		array_push($data, (string)$value -> kw);
	}
	if (count($data) > 0) {
		return $data;
	} else {
		return false;
	}
}

/**
 * Convert BR tags to nl
 *
 * @param string The string to convert
 * @return string The converted string
 */
function br2nl($string) {
	return @preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

function isMobile() {
	// HTTP_X_WAP_PROFILE
	if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		return true;
	}
	// via wap
	if (isset($_SERVER['HTTP_VIA'])) {
		// flase,true
		return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	}
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
		// HTTP_USER_AGENT
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
	if (isset($_SERVER['HTTP_ACCEPT'])) {
		// wml html
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
	return false;
}

/**
 * @param $msg
 * @param int $js
 * @param int $status
 * @param string $url
 * @param string $data
 * @param int $isview
 */
function getJson($msg, $js = 1, $status = 1, $url = '', $data='',$isview=0) {
    if ($js) {
        header("Content-type: application/json;charset=utf-8");
        if ($url) {
            echo json_encode(array(
                'status' => $status,
                'msg'=>$msg,
                'data' => $data,
                'url' => $url,
            ));
        } else {
            echo json_encode(array(
                'status' => $status,
                'msg'=>$msg,
                'data' => $data,
            ));
        }
        exit ;
    }
    if($isview==0){
        if($js == 0 && $url) {
            header('Location: ' . $url);
            exit ;
        } else {
            tsNotice($msg);
        }
    }
}

/*
 * @photourl
 * @projectid
 * @dir
 */
/**
 * @param $photourl
 * @param $projectid
 * @param $dir
 * @return array
 */
function tsUploadPhotoUrl($photourl, $projectid, $dir) {
	$menu2 = intval($projectid / 1000);
	$menu1 = intval($menu2 / 1000);
	$path = $menu1 . '/' . $menu2;
	$dest_dir = 'uploadfile/' . $dir . '/' . $path;
	createFolders($dest_dir);

	$arrType = explode('.', strtolower($photourl));
	$type = array_pop($arrType);

	$name = $projectid . '.' . $type;

	$dest = $dest_dir . '/' . $name;

	$img = file_get_contents($photourl);

	unlink($dest);
	file_put_contents($dest, $img);

	return array('path' => $path, 'url' => $path . '/' . $name, 'type' => $type, );

}


/**
 * @param $url
 * @return string
 */
function getdomain($url) {
    $host = strtolower ( $url );
    if (strpos ( $host, '/' ) !== false) {
        $parse = @parse_url ( $host );
        $host = $parse ['host'];
    }
    $topleveldomaindb = array ('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me','in','io','gg','co' );
    $str = '';
    foreach ( $topleveldomaindb as $v ) {
        $str .= ($str ? '|' : '') . $v;
    }

    $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
    if (preg_match ( "/" . $matchstr . "/ies", $host, $matchs )) {
        $domain = $matchs ['0'];
    } else {
        $domain = $host;
    }
    return $domain;
}


/**
 *
 * @param $phone
 * @return bool
 */
function isPhone($phone){
	//if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|19[0-9]{1}[0-9]{8}$/",$phone)){
	if(preg_match("/^1[0-9]{10}$/",$phone)){
		return true;
	}else{
		return false;
	}
}


/**
 *
 * @param string $data
 * @return array
 */
function string2array($data) {
	if ($data == '') {
		return array ();
	}
	if (is_array ( $data )) {
		return $data;
	}
	if (strpos ( $data, 'array' ) !== false && strpos ( $data, 'array' ) === 0) {
		@eval ( "\$array = $data;" );
		return $array;
	}
	return unserialize ( ($data) ); //unserialize ( new_stripslashes ( $data ) );
}

/**
 *
 * @param array $data
 * @param bool $isformdata
 * @return string
 *
 */
function array2string($data, $isformdata = 1) {
	if ($data == '') {
		return '';
	}
	if ($isformdata) {
		$data = ($data); //new_stripslashes ( $data );
	}
	return serialize ( $data );
}

/**
 * @param mixed $var
 * @param boolean $echo
 * @param string $label
 * @param boolean $strict
 * @return void|string
 */
function dump($var, $echo = true, $label = null, $strict = true) {
	$label = ($label === null) ? '' : rtrim ( $label ) . ' ';
	if (! $strict) {
		if (ini_get ( 'html_errors' )) {
			$output = print_r ( $var, true );
			$output = '<pre>' . $label . htmlspecialchars ( $output, ENT_QUOTES ) . '</pre>';
		} else {
			$output = $label . print_r ( $var, true );
		}
	} else {
		ob_start ();
		var_dump ( $var );
		$output = ob_get_clean ();
		if (! extension_loaded ( 'xdebug' )) {
			$output = preg_replace ( '/\]\=\>\n(\s+)/m', '] => ', $output );
			$output = '<pre>' . $label . htmlspecialchars ( $output, ENT_QUOTES ) . '</pre>';
		}
	}
	if ($echo) {
		echo ($output);
		return null;
	} else
		return $output;
}


/**
 * 404
 */
function ts404(){
    header ( "HTTP/1.1 404 Not Found" );
    header ( "Status: 404 Not Found" );
    $title = '404';
    include pubTemplate ( "404" );
    exit ();
}


/**
 * URL
 *
 * @param $url
 */
function tsHeaderUrl($url){
	header('Location: '.$url);
	exit;
}


/**
 * curl get url
 * @param $URL
 * @return bool|mixed
 */
function curl_get_file_contents($URL){
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($c, CURLOPT_HEADER, 1);
    curl_setopt($c, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;http://www.thinksaas.cn)');
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    curl_close($c);
    if ($contents) {return $contents;}
    else {return FALSE;}
}

/**
 * @return string
 */
function get_client_ip() {
    if(getenv('HTTP_CLIENT_IP')){
        $client_ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR')) {
        $client_ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR')) {
        $client_ip = getenv('REMOTE_ADDR');
    } else {
        $client_ip = $_SERVER['REMOTE_ADDR'];
    }
    return $client_ip;
}


/*
 * curl post
 */
function sendDataByCurl($url,$data=array()){

    $url = str_replace(' ','+',$url);
    $ch = curl_init();
    //URL
    curl_setopt($ch, CURLOPT_URL, "$url");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch,CURLOPT_TIMEOUT,60);
    // POST
    curl_setopt($ch, CURLOPT_POST, 1);
    // post
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));    //http_bulid_query()

    $output = curl_exec($ch);
    $errorCode = curl_errno($ch);
    //curl
    curl_close($ch);
    if(0 !== $errorCode) {
        return false;
    }
    return $output;

}

function getTimestamp(){
    $time = explode ( " ", microtime () );
    $time = $time [1] . ($time [0] * 1000);
    $time2 = explode ( ".", $time );
    $time = $time2 [0];
    return $time;
}

/**
 * @param type $domain
 * @return string
 */
function GetUrlToDomain($domain) {

    $arrDomain= parse_url($domain);

	//print_r($arrDomain);

    $domain = $arrDomain['path'];

    $re_domain = '';
    $domain_postfix_cn_array = array('com', 'net', 'org', 'gov', 'edu', 'com.cn', 'cn','cc','me','tv','la','net.cn','org.cn','top','wang','hk','co','pw','ren','asia','biz','gov.cn','tw','com.tw','us','tel','info','website','host','io','press','mobi','wiki','io');

    $domain = str_replace('http://','',$domain);
    $domain = str_replace('https://','',$domain);

    $array_domain = explode('.', $domain);
    $array_num = count($array_domain) - 1;
    if ($array_domain[$array_num] == 'cn') {
        if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)) {
            $re_domain = $array_domain[$array_num - 2] . '.' . $array_domain[$array_num - 1] . '.' . $array_domain[$array_num];
        } else {
            $re_domain = $array_domain[$array_num - 1] . '.' . $array_domain[$array_num];
        }
    } else {
        $re_domain = $array_domain[$array_num - 1] . '.' . $array_domain[$array_num];
    }

    $re_domain = str_replace('/','',$re_domain);

    return $re_domain;
}

function _prefilter($output) {
	$output=preg_replace("/\/\/[\S\f\t\v ]*?;[\r|\n]/", "", $output);
	$output=preg_replace("/\<\!\-\-[\s\S]*?\-\-\>/", "", $output);
	$output=preg_replace("/\>[\s]+\</", "><", $output);
	$output=preg_replace("/;[\s]+/", ";", $output);
	$output=preg_replace("/[\s]+\}/", "}", $output);
	$output=preg_replace("/}[\s]+/", "}", $output);
	$output=preg_replace("/\{[\s]+/", "{", $output);
	$output=preg_replace("/([\s]){2,}/", "$1", $output);
	$output=preg_replace("/[\s]+\=[\s]+/", "=", $output);
	return $output;
}

function cleanContentImgWH($content){
    $search = '/(<img.*?)width=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
    $content = preg_replace($search,'$1$3',$content);
    $search1 = '/(<img.*?)height=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
    $style = '/(<img.*?)style=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
    $content =  preg_replace($search1,'$1$3',$content);
    $content =  preg_replace($style,'$1$3',$content);
    return $content;
}

function getTextPhotos($text,$num=0){
    $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
    preg_match_all($pattern,$text,$match);
    $arrPhoto = $match[1];

    $count = count($arrPhoto);

    if($count>$num && $num){

        for($i=0;$i<=$num-1;$i++){
            $arrPhotos[$i]=$arrPhoto[$i];
        }

        return $arrPhotos;

    }else{
        return $arrPhoto;
    }
}

function getArrTimezone(){
    return array (
        'Pacific/Kwajalein' => '(GMT -12:00) International Date Line West',
        'Pacific/Samoa' => '(GMT -11:00) Midway Island, Samoa',
        'Pacific/Honolulu' => '(GMT -10:00) Hawaii',
        'US/Alaska' => '(GMT -9:00) Alaska',
        'US/Pacific' => '(GMT -8:00) Pacific Time (US &amp; Canada); Tijuana',
        'US/Mountain' => '(GMT -7:00) Mountain Time (US &amp; Canada)',
        'US/Arizona' => '(GMT -7:00) Arizona',
        'Mexico/BajaNorte' => '(GMT -7:00) Chihuahua, La Paz, Mazatlan',
        'US/Central' => '(GMT -6:00) Central Time (US &amp; Canada)',
        'America/Costa_Rica' => '(GMT -6:00) Central America',
        'Mexico/General' => '(GMT -6:00) Guadalajara, Mexico City, Monterrey',
        'Canada/Saskatchewan' => '(GMT -6:00) Saskatchewan',
        'US/Eastern' => '(GMT -5:00) Eastern Time (US &amp; Canada)',
        'America/Bogota' => '(GMT -5:00) Bogota, Lima, Quito',
        'US/East-Indiana' => '(GMT -5:00) Indiana (East)',
        'Canada/Eastern' => '(GMT -4:00) Atlantic Time (Canada)',
        'America/Caracas' => '(GMT -4:00) Caracas, La Paz',
        'America/Santiago' => '(GMT -4:00) Santiago',
        'Canada/Newfoundland' => '(GMT -3:30) Newfoundland',
        'Canada/Atlantic' => '(GMT -3:00) Brasilia, Greenland',
        'America/Buenos_Aires' => '(GMT -3:00) Buenos Aires, Georgetown',
        'Atlantic/Cape_Verde' => '(GMT -1:00) Cape Verde Is.',
        'Atlantic/Azores' => '(GMT -1:00) Azores',
        'Africa/Casablanca' => '(GMT 0) Casablanca, Monrovia',
        'Europe/Dublin' => '(GMT 0) Greenwich Mean Time : Dublin, Edinburgh, London',
        'Europe/Amsterdam' => '(GMT +1:00) Amsterdam, Berlin, Rome, Stockholm, Vienna',
        'Europe/Prague' => '(GMT +1:00) Belgrade, Bratislava, Budapest, Prague',
        'Europe/Paris' => '(GMT +1:00) Brussels, Copenhagen, Madrid, Paris',
        'Europe/Warsaw' => '(GMT +1:00) Sarajevo, Skopje, Warsaw, Zagreb',
        'Africa/Bangui' => '(GMT +1:00) West Central Africa',
        'Europe/Istanbul' => '(GMT +2:00) Athens, Beirut, Bucharest, Cairo, Istanbul	',
        'Asia/Jerusalem' => '(GMT +2:00) Harare, Jerusalem, Pretoria',
        'Europe/Kiev' => '(GMT +2:00) Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius',
        'Asia/Riyadh' => '(GMT +3:00) Kuwait, Nairobi, Riyadh',
        'Europe/Moscow' => '(GMT +3:00) Baghdad, Moscow, St. Petersburg, Volgograd',
        'Asia/Tehran' => '(GMT +3:30) Tehran',
        'Asia/Muscat' => '(GMT +4:00) Abu Dhabi, Muscat',
        'Asia/Baku' => '(GMT +4:00) Baku, Tbilsi, Yerevan',
        'Asia/Kabul' => '(GMT +4:30) Kabul',
        'Asia/Yekaterinburg' => '(GMT +5:00) Yekaterinburg',
        'Asia/Karachi' => '(GMT +5:00) Islamabad, Karachi, Tashkent',
        'Asia/Calcutta' => '(GMT +5:30) Chennai, Calcutta, Mumbai, New Delhi',
        'Asia/Katmandu' => '(GMT +5:45) Katmandu',
        'Asia/Almaty' => '(GMT +6:00) Almaty, Novosibirsk',
        'Asia/Dhaka' => '(GMT +6:00) Astana, Dhaka, Sri Jayawardenepura',
        'Asia/Rangoon' => '(GMT +6:30) Rangoon',
        'Asia/Bangkok' => '(GMT +7:00) Bangkok, Hanoi, Jakarta',
        'Asia/Krasnoyarsk' => '(GMT +7:00) Krasnoyarsk',
        'Asia/Hong_Kong' => '(GMT +8:00) Пекин, Чунцин, Гонконг, Урумчи',
        'Asia/Irkutsk' => '(GMT +8:00) Irkutsk, Ulaan Bataar',
        'Asia/Singapore' => '(GMT +8:00) Kuala Lumpar, Perth, Singapore, Taipei',
        'Asia/Tokyo' => '(GMT +9:00) Osaka, Sapporo, Tokyo',
        'Asia/Seoul' => '(GMT +9:00) Seoul',
        'Asia/Yakutsk' => '(GMT +9:00) Yakutsk',
        'Australia/Adelaide' => '(GMT +9:30) Adelaide',
        'Australia/Darwin' => '(GMT +9:30) Darwin',
        'Australia/Brisbane' => '(GMT +10:00) Brisbane, Guam, Port Moresby',
        'Australia/Canberra' => '(GMT +10:00) Canberra, Hobart, Melbourne, Sydney, Vladivostok',
        'Asia/Magadan' => '(GMT +11:00) Magadan, Soloman Is., New Caledonia',
        'Pacific/Auckland' => '(GMT +12:00) Auckland, Wellington',
        'Pacific/Fiji' => '(GMT +12:00) Fiji, Kamchatka, Marshall Is.',
    );
}

function getImagetype($filename){
    $file = fopen($filename, 'rb');
    $bin  = fread($file, 2);
    fclose($file);
    $strInfo  = unpack('C2chars', $bin);
    $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
    // dd($typeCode);
    $fileType = '';
    switch ($typeCode) {
        case 255216:
            $fileType = 'jpg';
            break;
        case 7173:
            $fileType = 'gif';
            break;
        case 6677:
            $fileType = 'bmp';
            break;
        case 13780:
            $fileType = 'png';
            break;
        default:
            $fileType = 'Можно загружать только определенные форматы изображения';
    }
    // if ($strInfo['chars1']=='-1' AND $strInfo['chars2']=='-40' ) return 'jpg';
    // if ($strInfo['chars1']=='-119' AND $strInfo['chars2']=='80' ) return 'png';
    return $fileType;
}

function getDirPath($projectid){
    $menu2 = intval($projectid / 1000);
    $menu1 = intval($menu2 / 1000);
    $path = $menu1 . '/' . $menu2;
    return $path;
}
