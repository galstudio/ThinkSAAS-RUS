<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


if($strUser['locationid']){

    $strLocation = $new['location']->find('location',array(
        'locationid'=>$strUser['locationid'],
    ));

}


$title = 'Мой район';
include template('my/index');
