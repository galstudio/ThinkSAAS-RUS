<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":
		//категория 1 уровня
		$arrCate = $new['group']->findAll('group_cate',array(
			'referid'=>0,
		));


		//группа
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('group','cate',array('page'=>''));
		$lstart = $page*20-20;
		$arrGroup = $new['group']->findAll('group',null,'isrecommend desc,count_topic desc',null,$lstart.',20');
		$groupNum = $new['group']->findCount('group');
		$pageUrl = pagination($groupNum, 20, $page, $url);



		$title = 'Категории';

		include template('cate');

		break;

	//категория 2 уровня
	case "2":
		$cateid = intval($_GET['cateid']);
		$strCate = $new['group']->find('group_cate',array(
			'cateid'=>$cateid,
		));


		$arrCate = $new['group']->findAll('group_cate',array(
			'referid'=>$cateid,
		));


		//группа
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('group','cate',array('ts'=>'2','page'=>''));
		$lstart = $page*20-20;
		$arrGroup = $new['group']->findAll('group',array(
			'cateid'=>$cateid,
		),null,null,$lstart.',20');
		$groupNum = $new['group']->findCount('group',array(
			'cateid'=>$cateid,
		));
		$pageUrl = pagination($groupNum, 20, $page, $url);




		$title = $strCate['catename'];
		include template('cate2');

		break;

	//категория 3 уровня
	case "3":

		$cateid = intval($_GET['cateid']);

		$strCate = $new['group']->find('group_cate',array(
			'cateid'=>$cateid,
		));

		//группа
		$oneCate = $new['group']->find('group_cate',array(
			'cateid'=>$strCate['referid'],
		));

		//нижняя категория
		$arrCate = $new['group']->findAll('group_cate',array(
			'referid'=>$cateid,
		));

		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('group','cate',array('ts'=>'3','page'=>''));
		$lstart = $page*20-20;
		$arrGroup = $new['group']->findAll('group',array(
			'cateid2'=>$cateid,
		),null,null,$lstart.',20');

		$groupNum = $new['group']->findCount('group',array(
			'cateid'=>$cateid,
		));
		$pageUrl = pagination($groupNum, 20, $page, $url);

		$title = $strCate['catename'];
		include template('cate3');
		break;

	case "group":

		$cateid = intval($_GET['cateid']);

		$strCate = $new['group']->find('group_cate',array(
			'cateid'=>$cateid,
		));

		$twoCate = $new['group']->find('group_cate',array(
			'cateid'=>$strCate['referid'],
		));

		$oneCate = $new['group']->find('group_cate',array(
			'cateid'=>$twoCate['referid'],
		));

		$arrGroup = $new['group']->findAll('group',array(

			'cateid3'=>$cateid,

		));


		$title = $strCate['catename'];

		include template('cate_group');

		break;

	//увязка с категорией
	case "do":

		$groupid = intval($_POST['groupid']);
		$cateid = intval($_POST['cateid']);
		$cateid2 = intval($_POST['cateid2']);
		$cateid3 = intval($_POST['cateid3']);

		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'cateid'=>$cateid,
			'cateid2'=>$cateid2,
			'cateid3'=>$cateid3,
		));

		if($cateid){
			$count_group = $new['group']->findCount('group',array(
				'cateid'=>$cateid,
			));
			$new['group']->update('group_cate',array(
				'cateid'=>$cateid,
			),array(
				'count_group'=>$count_group,
			));
		}

		if($cateid2){

			$count_group = $new['group']->findCount('group',array(
				'cateid2'=>$cateid2,
			));

			$new['group']->update('group_cate',array(
				'cateid'=>$cateid2,
			),array(
				'count_group'=>$count_group,
			));
		}

		if($cateid3){
			$count_group = $new['group']->findCount('group',array(
				'cateid3'=>$cateid3,
			));
			$new['group']->update('group_cate',array(
				'cateid'=>$cateid3,
			),array(
				'count_group'=>$count_group,
			));
		}

		tsNotice('Изменение категории прошло успешно!');

		break;

	case "two":
		$cateid = intval($_GET['cateid']);
		$arrCate = $db->fetch_all_assoc("select * from ".dbprefix."group_cate where referid='$cateid'");

		if($arrCate){
			echo '<select id="cateid2" name="cateid2">';
			echo '<option value="0">Выберите подкатегорию</option>';
			foreach($arrCate as $item){
				echo '<option value="'.$item['cateid'].'">'.$item['catename'].'</option>';
			}
			echo "</select>";
		}else{
			echo '';
		}
		break;

	case "three":
		$cateid2 = intval($_GET['cateid2']);
		$arrCate = $db->fetch_all_assoc("select * from ".dbprefix."group_cate where referid='$cateid2'");

		if($arrCate){
			echo '<select id="cateid3" name="cateid3">';
			echo '<option value="0">Выберите подкатегорию</option>';
			foreach($arrCate as $item){
				echo '<option value="'.$item['cateid'].'">'.$item['catename'].'</option>';
			}
			echo "</select>";
		}else{
			echo '';
		}
		break;

}
