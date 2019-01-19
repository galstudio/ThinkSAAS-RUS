<?php
defined('IN_TS') or die('Access Denied.');

class system extends tsApp{
	public function __construct($db){
        $tsAppDb = array();
		include 'app/system/config.php';
		//APP
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
		parent::__construct($db);
	}

	/*
	 * true
	 * false
	 */
	public function antiWord($text,$js=0){
		$text =preg_replace("/\s|　/","",$text);
		$arrWords = $this->findAll('anti_word');
		foreach($arrWords as $key=>$item){
			$arrWord[] = $item['word'];
		}
		$strWord = '';
		$count = 1;
		if(is_array($arrWord)){
			foreach ($arrWord as $item) {
				if ($count==1) {
					$strWord .= $item;
				} else {
					$strWord .= '|'.$item;
				}
					$count++;
			}
			if($text){
				preg_match("/$strWord/i",$text, $matche1);
				if(!empty($matche1[0])){
					//tsNotice('Содержит запрещенные слова: '.$matche1[0]);
					getJson('Недопустимая операция!',$js,0);
				}
			}
			preg_match("/$strWord/i",t($text), $matche2);
			if(!empty($matche2[0])){
				//tsNotice('Содержит запрещенные слова: '.$matche2[0]);
				getJson('Недопустимая операция!',$js,0);
			}
			$text3 = @preg_replace("/[^\x{4e00}-\x{9fa5}]/iu",'',$text);
			preg_match("/$strWord/i",t($text3), $matche3);
			if(!empty($matche3[0])){
				//tsNotice('Содержит запрещенные слова: '.$matche3[0]);
				getJson('Недопустимая операция!',$js,0);
			}
			$text4 = @preg_replace("/[^\d]/iu",'',$text);
			preg_match("/$strWord/i",t($text4), $matche4);
			if(!empty($matche4[0])){
				//tsNotice('Содержит запрещенные слова: '.$matche4[0]);
				getJson('Недопустимая операция!',$js,0);
			}
		}
		return true;
	}
	//ID
	function antiUser(){
		$arrUsers = $this->findAll('anti_user');
		foreach($arrUsers as $key=>$item){
			$arrUser[] = $item['userid'];
		}
		return $arrUser;
	}
	//ip
	function antiIp(){
		$arrIps = $this->findAll('anti_ip');
		foreach($arrIps as $key=>$item){
			$arrIp[] = $item['ip'];
		}
		return $arrIp;
	}
	//APP OPTION
	function appOption($app,$option){
		$db->query("TRUNCATE TABLE `".dbprefix.$app."_options`");
		foreach($option as $key=>$item){
			$optionname = $key;
			$optionvalue = trim($item);
			$this->create($app.'_options',array(
				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,
			));
		}
		$arrOptions = $this->findAll($app.'_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		fileWrite($app.'_options.php','data',$arrOption);
		$tsMySqlCache->set($app.'_options',$arrOption);
	}

	function searchDir($path,&$data){
		if(is_dir($path)){
			$dp=dir($path);
			while($file=$dp->read()){
				if($file!='.'&& $file!='..'){
					$this->searchDir($path.'/'.$file,$data);
				}
			}
			$dp->close();
		}
		if(is_file($path)){
			$data[]=$path;
		}
	}

	function getfile($dir){
		$data=array();
		$this->searchDir($dir,$data);
		return $data;
	}
}
