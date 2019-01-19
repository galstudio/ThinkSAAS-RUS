<?php
defined('IN_TS') or die('Access Denied.');

class photo extends tsApp{

	public function __construct($db){
        $tsAppDb = array();
		include 'app/photo/config.php';
		//APP
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
		parent::__construct($db);
	}

	//getPhotoForApp
	function getPhotoForApp($photoid){
		$strPhoto = $this->db->once_fetch_assoc("select * from ".dbprefix."photo where photoid='$photoid'");
		return $strPhoto;
	}

	function getSamplePhoto($photoid){
		$strPhoto = $this->db->once_fetch_assoc("select path,photourl from ".dbprefix."photo where photoid='$photoid'");
		return $strPhoto;
	}

	public function isPhoto($photoid){
		$photoNum = $this->findCount('photo',array(
			'photoid'=>$photoid,
		));

		if($photoNum > 0){
			return true;
		}else{
			return false;
		}

	}

	public function deletePhotoAlbum($albumid){

			$this->delete('photo_album',array(
				'albumid'=>$albumid,
			));

			$arrPhoto = $this->findAll('photo',array(
				'albumid'=>$albumid,
			));

			foreach($arrPhoto as $key=>$item){
				unlink('uploadfile/photo/'.$item['photourl']);
			}

			$this->delete('photo',array(
				'albumid'=>$albumid,
			));
	}
}
