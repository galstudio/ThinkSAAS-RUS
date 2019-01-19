<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user') -> isLogin();

if(aac('user')->isPublisher()==false) tsNotice('У вас нет прав на публикацию контента!');


switch ($ts) {
	case "" :
		if ($TS_APP['allowpost'] == 0 && $TS_USER['isadmin'] == 0) {
			tsNotice('Системные настройки не позволяют участникам публиковать статьи!');
		}

		$cateid = intval($_GET['cateid']);


        foreach ($arrCate as $key=>$item){
            $arrCate[$key]['two'] = $new['article']->findAll('article_cate',array(
                'referid'=>$item['cateid'],
            ));
        }


		$title = 'Добавление статьи';
		include  template('add');
		break;

	case "do" :


		$cateid = intval($_POST['cateid']);
		$title = trim($_POST['title']);
		$content = tsClean($_POST['content']);
		$gaiyao = trim($_POST['gaiyao']);
		$tag = tsClean($_POST['tag']);
		$addtime = date('Y-m-d H:i:s');

		if (intval($TS_USER['isadmin']) == 0) {
			aac('system') -> antiWord($title);
			aac('system') -> antiWord($content);
			aac('system') -> antiWord($tag);
		}

		if ($title == '' || $content == '')
			tsNotice("Заголовок и содержание статьи не могут быть пустыми!");

        $isTitle = $new['article']->findCount('article',array(
            'title'=>$title,
        ));

        if($isTitle){
            tsNotice("Статья с таким заголовком уже существует!");
        }

        if($gaiyao){
            $gaiyao = cututf8($gaiyao,0,100);
        }else{
            $gaiyao = cututf8(t($_POST['content']),0,100);
        }

		if ($TS_APP['isaudit'] == 1) {
			$isaudit = 1;
		} else {
			$isaudit = 0;
		}

        $articleid = $new['article'] -> create('article', array(
            'userid' => $userid,
            'locationid' => aac('user') -> getLocationId($userid),
            'cateid' => $cateid,
            'title' => $title,
            'content' => $content,
            'gaiyao' => $gaiyao,
            'isaudit' => $isaudit,
            'addtime' => date('Y-m-d H:i:s')
        ));

		$arrUpload = tsUpload($_FILES['photo'], $articleid, 'article', array('jpg', 'gif', 'png', 'jpeg'));
		if ($arrUpload) {
			$new['article'] -> update('article', array(
                'articleid' => $articleid
            ), array(
                'path' => $arrUpload['path'],
                'photo' => $arrUpload['url']
            ));
		}
		aac('tag') -> addTag('article', 'articleid', $articleid, $tag);

        if($isaudit==0){
            aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts']);
        }


		header("Location: " . tsUrl('article', 'show', array('id' => $articleid)));

		break;
}
