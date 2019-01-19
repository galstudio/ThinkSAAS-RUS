<?php
defined('IN_TS') or die('Access Denied.');
function file_list($path){
    if ($handle = opendir($path)){
        while (false !== ($file = readdir($handle))){
            if ($file != "." && $file != ".."){
                if (is_dir($path."/".$file)){
                    file_list($path."/".$file);
                }else{
					$upfile = $path."/".$file;
					$nfile = substr($path.'/'.$file,13);
					$npath = substr($path,13);
					if(abcefile($npath)==='1'){
					    #return $npath;
					    getJson($npath.'Каталог не имеет разрешений на запись, Linux разрешение 755',1,0);
                    }
					if(is_file($nfile)){
                        if(copy($upfile,$nfile)===false){
                            getJson('Не удалось перезаписать файл',1,0);
                        }
					}else{
						if(copy($upfile,$nfile)===false){
                            getJson('Не удалось обновить файл',1,0);
						}
					}
                }
            }
        }
    }
}
function abcefile($path){
    if ($handle = opendir($path)){
        while (false !== ($file = readdir($handle))){
            if ($file != "." && $file != ".."){
                if (is_dir($path."/".$file)){
                    abcefile($path."/".$file);
                }else{
					$upfile = $path."/".$file;
					if(is_file($upfile)){
						if(is_writable($upfile)==false){
							return '1';exit;
						}
					}
                }
            }
        }
    }
}
switch($ts){
	case "":
		include template('update');
		break;

 	case "iswritable":
        $msg = '';
        if(function_exists('opendir')==false) $msg .= 'Функция opendir недоступна<br />';#opendir
        if(function_exists('readdir')==false) $msg .= 'Функция readdir недоступна<br />';#readdir
        if(function_exists('copy')==false) $msg .= 'Функция copy недоступна<br />';#copy
        if(extension_loaded('Fileinfo')==false) $msg .= 'Расширение Fileinfo недоступно<br />';#Fileinfo
		if(abcefile('upgrade')) $msg .= 'Каталог upgrade не доступен для записи<br />';
		echo $msg;
		break;

    case "hand":
        $upid = intval($_GET['upid']);
        include template('update_hand');
        break;

	case "one":
		include template('update_one');
		break;

	case "two":
		include template('update_two');
		break;

	case "twodo":
		$upsql = trim($_POST['upsql']);
		if($upsql){
			$arrSql = explode('--------------------',$upsql);
			foreach($arrSql as $item){
				$item = trim($item);
				if ($item){
					$db->query($item);
				}
			}

			echo '1';exit;
		}else{

			//SQL
			echo '0';exit;
		}
		//echo '1';exit;
		break;

	case "three":
		include template('update_three');
		break;

	case "threedo":
		$upversion = trim($_GET['upversion']);
		if($upversion==''){
		    getJson('Проблема с номером версии',1,0);
        }
		$filezip = $upversion.'.zip';

		//zip
		unlink('upgrade/'.$filezip);
        delDir('upgrade/'.$upversion);
		$upfile = 'https://www.thinksaas.cn/upgrade/'.$filezip;
		//zip
		$urls=array(
			$upfile,
			$upfile,
			$upfile,
		);
		$save_to='upgrade/';
		$mh=curl_multi_init();
		foreach($urls as $i=>$url){
			//$g=$save_to.basename($url);
			$g = $save_to.$filezip;
			if(!is_file($g)){
				$conn[$i]=curl_init($url);
				$fp[$i]=fopen($g,"w");
				curl_setopt($conn[$i],CURLOPT_USERAGENT,"Mozilla/4.0(compatible; MSIE 7.0; Windows NT 6.0)");
				curl_setopt($conn[$i],CURLOPT_FILE,$fp[$i]);
				curl_setopt($conn[$i],CURLOPT_HEADER ,0);
				curl_setopt($conn[$i],CURLOPT_CONNECTTIMEOUT,60);
				curl_multi_add_handle($mh,$conn[$i]);
			}
		}
		do{
			$n=curl_multi_exec($mh,$active);
		}while($active);
		foreach($urls as $i=>$url){
			curl_multi_remove_handle($mh,$conn[$i]);
			curl_close($conn[$i]);
			fclose($fp[$i]);
		}
		curl_multi_close($mh);
		chmod('upgrade/'.$filezip,0755);
		include 'thinksaas/pclzip.lib.php';
		$archive = new PclZip('upgrade/'.$filezip);
		if ($archive->extract(PCLZIP_OPT_PATH, 'upgrade/'.$upversion,PCLZIP_OPT_REPLACE_NEWER) == 0) {
            getJson('Не удалось распаковать пакет обновления',1,0);
		}else{
			unlink('upgrade/'.$filezip);
		}
		file_list('upgrade/'.$upversion);
        delDir('upgrade/'.$upversion);
        getJson('Обновление прошло успешно',1,1);
		break;
}
