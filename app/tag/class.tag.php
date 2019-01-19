<?php
defined('IN_TS') or die('Access Denied.');

class tag extends tsApp{
	public function __construct($db){
        $tsAppDb = array();
		include 'app/tag/config.php';
		//APP
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
		parent::__construct($db);
	}

	function addTag($objname,$idname,$objid,$tags){
		$objname = tsUrlCheck($objname);
		$idname = tsUrlCheck($idname);
		$objid = intval($objid);
		if($objname != '' && $idname != '' && $objid!=0 && $tags!=''){
			$tags = str_replace ( '，', ',', $tags );
			$arrTag = explode(',',$tags);
			foreach($arrTag as $item){
				$tagname = t($item);
				if(strlen($tagname) < '32' && $tagname != ''){
					$uptime = time();
					$tagcount = $this->findCount('tag',array(
						'tagname'=>$tagname,
					));
					if($tagcount == '0'){
						$tagid = $this->create('tag',array(
							'tagname'=>$tagname,
							'uptime'=>$uptime,
						));
						$tagIndexCount = $this->findCount('tag_'.$objname.'_index',array(
							$idname=>$objid,
							'tagid'=>$tagid,
						));
						if($tagIndexCount == '0'){
							$this->create("tag_".$objname."_index",array(
								$idname=>$objid,
								'tagid'=>$tagid,
							));
						}
						$tagIdCount = $this->findCount("tag_".$objname."_index",array(
							'tagid'=>$tagid,
						));
						$count_obj = "count_".$objname;
						$this->update('tag',array(
							'tagid'=>$tagid,
						),array(
							$count_obj=>$tagIdCount,
						));
					}else{
						$tagData = $this->find('tag',array(
							'tagname'=>$tagname,
						));
						$tagIndexCount = $this->findCount("tag_".$objname."_index",array(
							$idname=>$objid,
							'tagid'=>$tagData['tagid'],
						));
						if($tagIndexCount == '0'){
							$this->create("tag_".$objname."_index",array(
								$idname=>$objid,
								'tagid'=>$tagData['tagid'],
							));
						}
						$tagIdCount = $this->findCount("tag_".$objname."_index",array(
							'tagid'=>$tagData['tagid'],
						));
						$count_obj = "count_".$objname;
						$this->update('tag',array(
							'tagid'=>$tagData['tagid'],
						),array(
							$count_obj=>$tagIdCount,
							'uptime'=>$uptime,
						));
					}
				}
			}
		}
	}

	//topic tag
	function getObjTagByObjid($objname,$idname,$objid){
		$arrTagIndex = $this->findAll("tag_".$objname."_index",array(
			$idname=>$objid,
		));
		if(is_array($arrTagIndex)){
		foreach($arrTagIndex as $item){
			$strTag = $this->getOneTag($item['tagid']);
			if($strTag){
				$arrTag[] = $strTag;
			}
		}
		}
		return $arrTag;
	}

	//tagid tagname
	function getOneTag($tagid){
		$tagData = $this->find('tag',array(
			'tagid'=>$tagid,
		));
		return $tagData;
	}

	//agname tagid
	function getTagId($tagname){
		$strTag = $this->find('tag',array(
			'tagname'=>$tagname,
		));
		return intval($strTag['tagid']);
	}

	function countObjTag($objname,$tagid){
		$countObj = $this->findCount("tag_".$objname."_index",array(
			'tagid'=>$tagid,
		));
		$this->update('tag',array(
			'tagid'=>$tagid,
		),array(
			'count_'.$objname=>$countObj,
		));
	}

	//ID
	function delIndextag($objname,$idname,$objid){
		$this->delete("tag_".$objname."_index",array(
			$idname=>$objid,
		));
		return true;
	}

	//tag
	public function isTag($tagname){
		$countTag = $this->findCount('tag',array(
			'tagname'=>$tagname,
		));
		if($countTag>0){
			return true;
		}else{
			return false;
		}
	}
}
