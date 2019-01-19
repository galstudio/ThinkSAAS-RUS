<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "list":

		$arrCate = $new['article']->findAll('article_cate',array(
		    'referid'=>0,
        ),'orderid desc');

		foreach($arrCate as $key=>$item){
		    $arrCate[$key]['twocate'] = $new['article']->findAll('article_cate',array(
		        'referid'=>$item['cateid'],
            ));
        }

		include template("admin/cate_list");

		break;

	case "add":

	    $referid = intval($_GET['referid']);

		include template("admin/cate_add");

		break;

	case "add_do":



		$new['article']->create('article_cate',array(
		    'referid'=>intval($_POST['referid']),
			'catename'=>trim($_POST['catename']),
			'orderid'=>intval($_POST['orderid']),

		));


		header("Location: ".SITE_URL."index.php?app=article&ac=admin&mg=cate&ts=list");

		break;

	case "del":

		$cateid = intval($_GET['cateid']);


		$strCate = $new['article']->find('article_cate',array(
		    'cateid'=>$cateid,
        ));

		if($strCate['referid']==0){
		    $arrCate = $new['article']->findAll('article_cate',array(
		        'referid'=>$strCate['cateid'],
            ));

            foreach($arrCate as $key=>$item){
                $new['article']->update('article',array(
                    'cateid'=>$item['cateid']
                ),array(
                    'cateid'=>0,
                ));
            }

            $new['article']->delete('article_cate',array(
                'referid'=>$strCate['cateid'],
            ));

        }


        $new['article']->update('article',array(
            'cateid'=>$cateid
        ),array(
            'cateid'=>0,
        ));


		$new['article']->delete('article_cate',array(
			'cateid'=>$cateid,
		));




		qiMsg("Категория успешно удалена!");

		break;

	case "edit":

		$cateid = intval($_GET['cateid']);

		$strCate = $new['article']->find('article_cate',array(
			'cateid'=>$cateid,
		));


		include template("admin/cate_edit");

		break;

	case "edit_do":
		$cateid = intval($_POST['cateid']);
		$catename = trim($_POST['catename']);

		$new['article']->update('article_cate',array(
			'cateid'=>$cateid,
		),array(
			'catename'=>$catename,
			'orderid'=>intval($_POST['orderid']),
		));

		header("Location: ".SITE_URL."index.php?app=article&ac=admin&mg=cate&ts=list");

		break;
}
