<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "list":

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=article&ac=admin&mg=post&ts=list&page=';
		$lstart = $page*20-20;
		$arrArticle = $new['article']->findAll('article',null,'addtime desc',null,$lstart.',20');

		$articleNum = $new['article']->findCount('article');
		$pageUrl = pagination($articleNum, 20, $page, $url);

		include template('admin/post_list');
		break;


	case "isaudit0":

		$articleid = intval($_GET['articleid']);
		$strArticle = $new['article']->find('article',array(
			'articleid'=>$articleid,
		));

        $new['article']->update('article',array(
            'articleid'=>$articleid,
        ),array(
            'isaudit'=>0,
        ));

        $msg_userid = '0';
        $msg_touserid = $strArticle['userid'];
        $msg_content = 'Статья, которую вы добавили, была одобрена. Можете посмотреть её!';
        $msg_url = tsUrl('article','show',array('id'=>$articleid));
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_url);

        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strArticle['userid'],$TS_URL['mg']);

		qiMsg('Операция выполнена!');
		break;

    case "isaudit1":

        $articleid = intval($_GET['articleid']);
        $strArticle = $new['article']->find('article',array(
            'articleid'=>$articleid,
        ));

        $new['article']->update('article',array(
            'articleid'=>$articleid,
        ),array(
            'isaudit'=>1,
        ));

        $msg_userid = '0';
        $msg_touserid = $strArticle['userid'];
        $msg_content = 'Ваша статья не была одобрена. Проверьте её! ';
        $msg_url = tsUrl('article','show',array('id'=>$articleid));
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content,$msg_url);

        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strArticle['userid'],$TS_URL['mg']);

        qiMsg('Операция выполнена!');

        break;

	case "delete":

		$articleid = intval($_GET['articleid']);
		$strArticle = $new['article']->find('article',array(
			'articleid'=>$articleid,
		));

		if($strArticle['photo']){
			unlink('uploadfile/article/'.$strArticle['photo']);
		}

		$new['article']->delete('article',array(
			'articleid'=>$articleid,
		));

		$new['article']->delete('tag_article_index',array(
			'articleid'=>$articleid,
		));


        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strArticle['userid'],$TS_URL['mg']);

		qiMsg('Статья успешно удалена!');

		break;

	case "isrecommend":

		$articleid = intval($_GET['articleid']);
		$strArticle = $new['article']->find('article',array(
			'articleid'=>$articleid,
		));

		if($strArticle['isrecommend']==0){
			$new['article']->update('article',array(
				'articleid'=>$articleid,
			),array(
				'isrecommend'=>1,
			));
		}else{
			$new['article']->update('article',array(
				'articleid'=>$articleid,
			),array(
				'isrecommend'=>0,
			));
		}

		qiMsg('Операция выполнена!');
		break;

}
