<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		$arrOptions = $new['system']->findAll('system_options');
		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = stripslashes($item['optionvalue']);
		}
		$arrTime = getArrTimezone();
		$arrTheme	= tsScanDir('theme');
		include template("options");
		break;

	case "do":
		$strLogo = $new['system']->find('system_options',array(
			'optionname'=>'logo',
		));
		$db->query("TRUNCATE TABLE `".dbprefix."system_options`");
		foreach($_POST['option'] as $key=>$item){
			$optionname = $key;
			$optionvalue = trim($item);
			$new['system']->create('system_options',array(
				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,
			));
		}
		$new['system']->create('system_options',array(
			'optionname'=>'logo',
			'optionvalue'=>$strLogo['optionvalue'],
		));
		$arrOptions = $new['system']->findAll('system_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		fileWrite('system_options.php','data',$arrOption);
		$tsMySqlCache->set('system_options',$arrOption);
		if($_POST['option']['site_urltype'] == 3 || $_POST['option']['site_urltype'] == 4 || $_POST['option']['site_urltype'] == 5 || $_POST['option']['site_urltype'] == 6 || $_POST['option']['site_urltype'] == 7){
			$scriptName = explode('index.php',$_SERVER['SCRIPT_NAME']);
			//.htaccess
			$fp =  fopen(THINKROOT.'/.htaccess','w');
			if(!is_writable(THINKROOT.'/.htaccess')) qiMsg("Файл (.htaccess) недоступен для записи. Если вы используете хостинг Unix/Linux, то измените права доступа к файлу на 777. Если вы используете хостинг Windows, пожалуйста, свяжитесь с администратором, чтобы этот файл был доступен.");
			$htaccess = "RewriteEngine On\n"
					."RewriteBase ".$scriptName[0]."\n"
					."RewriteRule ^index\.php$ - [L]\n"
					."RewriteCond %{REQUEST_FILENAME} !-f\n"
					."RewriteCond %{REQUEST_FILENAME} !-d\n"
					."RewriteRule . ".$scriptName[0]."index.php [L]\n"
					."RewriteCond %{REQUEST_METHOD} ^TRACE\n"
					."RewriteRule .* - [F]";
			$fw =  fwrite($fp,$htaccess);
		}

		setcookie('tsTheme',$_POST['option']['site_theme']);
		qiMsg("Обновление системной опции успешно завершено, кещ очищен!");
		break;
}
