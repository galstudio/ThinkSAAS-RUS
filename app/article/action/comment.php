<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = aac ( 'user' )->isLogin ();

switch ($ts) {

	case "do" :

		$articleid = intval ( $_POST ['articleid'] );
		$content = tsClean ( $_POST ['content'] );

		if ($content == '')
			tsNotice ( "Содержание не может быть пустым!" );

		aac ( 'system' )->antiWord ( $content );

		$new ['article']->create ( 'article_comment', array (

				'articleid' => $articleid,
				'userid' => $userid,
				'content' => $content,
				'addtime' => time ()
		)
		 );

		$count_comment = $new ['article']->findCount ( 'article_comment', array (
				'articleid' => $articleid
		) );

		$new ['article']->update ( 'article', array (
				'articleid' => $articleid
		), array (
				'count_comment' => $count_comment
		) );

		header ( "Location: " . tsUrl ( 'article', 'show', array (
				'id' => $articleid
		) ) );

		break;

	case "delete" :

		$commentid = intval ( $_GET ['commentid'] );

		$strComment = $new ['article']->find ( 'article_comment', array (
				'commentid' => $commentid
		) );

		$strArticle = $new ['article']->find ( 'article', array (
				'articleid' => $strComment ['articleid']
		) );

		if ($userid == $strArticle ['userid'] || $TS_USER ['isadmin'] == 1) {

			$new ['article']->delete ( 'article_comment', array (
					'commentid' => $commentid
			));

            aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts'],$strComment['userid']);

			tsNotice ( 'Успешно удалено!' );
		} else {

			tsNotice ( 'Недопустимая операция!' );
		}

		break;
}
