<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$arrCate = $new ['article']->findAll ( 'article_cate',array(
    'referid'=>0,
),'orderid desc');
