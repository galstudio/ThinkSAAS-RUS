<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = aac ( 'user' )->isLogin ();

if($TS_SITE['isallowedit'] && $TS_USER ['isadmin'] == 0) tsNotice('Редактирование пользователями контента отключено, свяжитесь с администратором, чтобы отредактировать статью!');

switch ($ts) {

	case "" :

		$articleid = intval ( $_GET ['articleid'] );

		$cateid = intval ( $_GET ['cateid'] );

		$strArticle = $new ['article']->find ( 'article', array (
				'articleid' => $articleid
		) );

		if ($strArticle ['userid'] == $userid || $TS_USER ['isadmin'] == 1) {

			$strArticle['title'] = tsTitle($strArticle['title']);
			$strArticle['content'] = tsDecode($strArticle['content']);

			// TAG
			$arrTags = aac ( 'tag' )->getObjTagByObjid ( 'article', 'articleid', $articleid );
			foreach ( $arrTags as $key => $item ) {
				$arrTag [] = $item ['tagname'];
			}
			$strArticle ['tag'] = arr2str ( $arrTag );



            foreach ($arrCate as $key=>$item){
                $arrCate[$key]['two'] = $new['article']->findAll('article_cate',array(
                    'referid'=>$item['cateid'],
                ));
            }


			$title = 'Правка статьи';
			include template ( 'edit' );
		} else {

			tsNotice ( 'Недопустимая операция!' );
		}

		break;

	case "do" :

		$articleid = intval ( $_POST ['articleid'] );

		$strArticle = $new ['article']->find ( 'article', array (
				'articleid' => $articleid
		) );

		if($strArticle['userid']!=$userid && $TS_USER['isadmin']==0){
			tsNotice('Недопустимая операция!');
		}

		$cateid = intval ( $_POST ['cateid'] );
		$title = trim ( $_POST ['title'] );
		$content = tsClean ( $_POST ['content'] );
		$gaiyao = trim ( $_POST ['gaiyao'] );

		if ($TS_USER ['isadmin'] == 0) {
			aac ( 'system' )->antiWord ( $title );
			aac ( 'system' )->antiWord ( $content );
		}

		if ($title == '' || $content == '')
			qiMsg ( "Заголовок и содержание не могут быть пустыми!" );

		$new ['article']->update ( 'article', array (
			'articleid' => $articleid
		), array (
			'cateid' => $cateid,
			'title' => $title,
			'content' => $content ,
			'gaiyao' => $gaiyao
		));

		$tag = trim ( $_POST ['tag'] );
		if ($tag) {
			aac ( 'tag' )->delIndextag ( 'article', 'articleid', $articleid );
			aac ( 'tag' )->addTag ( 'article', 'articleid', $articleid, $tag );
		}

		$arrUpload = tsUpload ( $_FILES ['photo'], $articleid, 'article', array ('jpg','gif','png','jpeg' ) );
		if ($arrUpload) {
			$new ['article']->update ( 'article', array (
					'articleid' => $articleid
			), array (
					'path' => $arrUpload ['path'],
					'photo' => $arrUpload ['url']
			) );

			tsDimg ( $arrUpload ['url'], 'article', '180', '140', $arrUpload ['path'] );
		}

		header ( "Location: " . tsUrl ( 'article', 'show', array (
				'id' => $articleid
		) ) );

		break;
}
