<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//список
	case "list":

		$arrCate = $new['group']->findAll('group_cate',array(
			'referid'=>'0',
		));

		foreach($arrCate as $key=>$item){

			$arrCates[] = $item;
			$arrCates[$key]['two'] = $new['group']->findAll('group_cate',array(
				'referid'=>$item['cateid'],
			));

		}

		foreach($arrCates as $key=>$item){

			$arrCatess[] = $item;
			foreach($item['two'] as $tkey=>$titem){

				$arrCatess[$key]['two'][$tkey]['three'] = $new['group']->findAll('group_cate',array(
					'referid'=>$titem['cateid'],
				));

			}

		}

		//print_r($arrCatess);

		include template("admin/cate_list");

		break;

	//добавить
	case "add":

		$referid = intval($_GET['referid']);

		include template("admin/cate_add");

		break;

	case "add_do":

		$new['group']->create('group_cate',array(

			'catename'=>t($_POST['catename']),
			'referid'=>intval($_POST['referid']),

		));


		header("Location: ".SITE_URL."index.php?app=group&ac=admin&mg=cate&ts=list");

		break;

	//удалить
	case "del":

		$cateid = intval($_GET['cateid']);

		$groupNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group where `cateid`='$cateid'");

		if($groupNum['count(*)'] > 0){
			qiMsg("В этой категории есть группы и не может быть удалена!");
		}

		$db->query("delete from ".dbprefix."group_cate where cateid='$cateid'");


		qiMsg("Категория была успешно удалена!");

		break;

	//правка
	case "edit":

		$cateid = intval($_GET['cateid']);

		$referid = intval($_GET['referid']);

		$strCate = $db->once_fetch_assoc("select * from ".dbprefix."group_cate where cateid='$cateid'");

		if($referid){
			$arrOneCate = $new['group']->findAll('group_cate',array(
				'referid'=>0,
			));
		}

		include template("admin/cate_edit");

		break;

	case "edit_do":
		$cateid = intval($_POST['cateid']);
		$catename = t($_POST['catename']);

		$referid = intval($_POST['referid']);

		$refer = '';
		if($referid){
			$refer = ", `referid`='$referid'";
		}

		$db->query("update ".dbprefix."group_cate set `catename`='".$catename."'".$refer." where cateid='$cateid'");

		header("Location: ".SITE_URL."index.php?app=group&ac=admin&mg=cate&ts=list");

		break;
}
