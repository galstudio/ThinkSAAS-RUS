<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = aac ( 'user' )->isLogin ();

$articleid = intval ( $_GET ['articleid'] );

$strArticle = $new ['article']->find ( 'article', array (
    'articleid' => $articleid
) );

if($TS_SITE['isallowdelete'] && $TS_USER ['isadmin'] == 0) tsNotice('Удаление пользователями контента отключено, свяжитесь с администратором, чтобы удалить статью!');



if ($strArticle ['userid'] == $userid || $TS_USER ['isadmin'] == 1) {
    $new ['article']->delete ( 'article', array (
        'articleid' => $articleid
    ) );
    $new ['article']->delete ( 'article_comment', array (
        'articleid' => $articleid
    ) );
    $new ['article']->delete ( 'article_recommend', array (
        'articleid' => $articleid
    ) );


    if($strArticle['isaudit']==0){
        aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strArticle ['userid']);
    }


}

tsNotice('Успешно удалено','Нажмите здесь, чтобы вернуться на главную страницу статей',tsUrl ( 'article' ));
