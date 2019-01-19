<?php
defined('IN_TS') or die('Access Denied.');

class weiboAdmin extends weibo{

    public function options(){
		$arrOptions = $this->findAll('weibo_options');
		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = stripslashes($item['optionvalue']);
		}
        include template("admin/options");
    }

    public function optionsdo(){
        $this->doSql("TRUNCATE TABLE `".dbprefix."weibo_options`");
        foreach($_POST['option'] as $key=>$item){
            $optionname = $key;
            $optionvalue = trim($item);
            $this->create('weibo_options',array(
                'optionname'=>$optionname,
                'optionvalue'=>$optionvalue,
            ));
        }
        $arrOptions = $this->findAll('weibo_options',null,null,'optionname,optionvalue');
        foreach($arrOptions as $item){
            $arrOption[$item['optionname']] = $item['optionvalue'];
        }
        fileWrite('weibo_options.php','data',$arrOption);
        $GLOBALS['tsMySqlCache']->set('weibo_options',$arrOption);
        qiMsg('Модификация прошла успешно!');
    }

    public function weibolist(){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $url = SITE_URL.'index.php?app=weibo&ac=admin&mg=weibolist&page=';
        $lstart = $page*20-20;
        $arrWeibo = $this->findAll('weibo',null,'addtime desc',null,$lstart.',20');
        $weiboNum = $this->findCount('weibo');
        $pageUrl = pagination($weiboNum, 20, $page, $url);
        include template("admin/weibo_list");
    }

    public function isaudit(){
        $weiboid = intval($_GET['weiboid']);
        $strWeibo = $this->find('weibo',array(
            'weiboid'=>$weiboid,
        ));
        if($strWeibo['isaudit'] == 0){
            $this->update('weibo',array(
                'weiboid'=>$weiboid,
            ),array(
                'isaudit'=>1,
            ));
        }
        if($strWeibo['isaudit'] == 1){
            $this->update('weibo',array(
                'weiboid'=>$weiboid,
            ),array(
                'isaudit'=>0,
            ));
        }
        qiMsg('Успешно выполнено!');
    }

    public function deleteweibo(){
        $weiboid=intval($_GET['weiboid']);
        $strWeibo = $this->find('weibo',array(
            'weiboid'=>$weiboid,
        ));
        unlink('uploadfile/weibo/'.$strWeibo['photo']);
        $this->delete('weibo',array(
            'weiboid'=>$weiboid,
        ));
        $this->delete('weibo_comment',array(
            'weiboid'=>$weiboid,
        ));
        qiMsg('Успешно удалено!');
    }
}
