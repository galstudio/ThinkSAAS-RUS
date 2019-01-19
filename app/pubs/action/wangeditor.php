<?php
/**
 * Created by PhpStorm.
 * User: qiniao
 * Date: 2018/6/6
 * Time: 7:00
 */
defined('IN_TS') or die('Access Denied.');

switch($ts){
    case "photo":
        $userid = aac('user')->isLogin();
        $id = $new['pubs']->create('editor',array(
            'userid'=>$userid,
            'type'=>'photo',
            'addtime'=>time(),
        ));
        $arrUpload = tsUpload($_FILES['photo'], $id, 'editor', array('jpg', 'gif', 'png', 'jpeg'));
        if ($arrUpload) {
            $new['pubs'] -> update('editor', array(
                'id' => $id
            ), array(
                'title'=>$arrUpload['name'],
                'path' => $arrUpload['path'],
                'url' => $arrUpload['url']
            ));
            echo json_encode(array(
                'errno'=>0,
                'data'=>array(
                    0=>SITE_URL.'uploadfile/editor/'.$arrUpload['url'],
                ),
            ));
            exit();
        }else{
            $new['pubs']->delete('editor',array(
                'id'=>$id,
            ));
        }
        break;
}
