<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "word":
		$arrWord = $new['system']->findAll('anti_word',null,'id desc');
		include template('anti_word');
		break;
	case "worddo":
		$word = trim($_POST['word']);
		if($word){
			$isWord = $new['system']->findCount('anti_word',array(
				'word'=>$word,
			));
			if($isWord == 0){
				$new['system']->create('anti_word',array(
					'word'=>$word,
					'addtime'=>date('Y-m-d H:i:s'),
				));
				$arrWords = $new['system']->findAll('anti_word');
				foreach($arrWords as $key=>$item){
					$arrWord[] = $item['word'];
				}
				$strWord = '';
				$count = 1;
				if(is_array($arrWord)){
					foreach ($arrWord as $item) {
						if ($count==1) {
							$strWord .= $item;
						} else {
							$strWord .= '|'.$item;
						}
							$count++;
					}
				}
				fileWrite('system_anti_word.php','data',$strWord);
				$tsMySqlCache->set('system_anti_word',$strWord);
			}

			qiMsg('Спам-слова успешно добавлены!');
		}else{
			qiMsg('Спам-слова не могут быть пустыми!');
		}
		break;

    case "worddelall":
        $db->query("TRUNCATE ".dbprefix."anti_word");
        $arrWords = $new['system']->findAll('anti_word');
        foreach($arrWords as $key=>$item){
            $arrWord[] = $item['word'];
        }
        $strWord = '';
        $count = 1;
        if(is_array($arrWord)){
            foreach ($arrWord as $item) {
                if ($count==1) {
                    $strWord .= $item;
                } else {
                    $strWord .= '|'.$item;
                }
                $count++;
            }
        }

        fileWrite('system_anti_word.php','data',$strWord);
        $tsMySqlCache->set('system_anti_word',$strWord);
        qiMsg('Успешно удалено!');
        break;

	case "worddel":
		$id = intval($_GET['id']);
		$new['system']->delete('anti_word',array(
			'id'=>$id,
		));
		$arrWords = $new['system']->findAll('anti_word');
		foreach($arrWords as $key=>$item){
			$arrWord[] = $item['word'];
		}
		$strWord = '';
		$count = 1;
		if(is_array($arrWord)){
			foreach ($arrWord as $item) {
				if ($count==1) {
					$strWord .= $item;
				} else {
					$strWord .= '|'.$item;
				}
					$count++;
			}
		}
		fileWrite('system_anti_word.php','data',$strWord);
		$tsMySqlCache->set('system_anti_word',$strWord);
		qiMsg('Успешно удалено!');
		break;

	//IP
	case "ip":
		$arrIp = $new['system']->findAll('anti_ip',null,'addtime desc');
		include template('anti_ip');
		break;
	case "ipdo":
		$ip = trim($_POST['ip']);
		if($ip){
			$isIp = $new['system']->findCount('anti_ip',array(
				'ip'=>$ip,
			));
			if($isIp==0){
				$new['system']->create('anti_ip',array(
					'ip'=>$ip,
					'addtime'=>date('Y-m-d H:i:s'),
				));
				$arrIps = $new['system']->findAll('anti_ip');
				foreach($arrIps as $key=>$item){
					$arrIp[] = $item['ip'];
				}
				fileWrite('system_anti_ip.php','data',$arrIp);
				$tsMySqlCache->set('system_anti_ip',$arrIp);
			}
			qiMsg('IP успешно добавлен!');
		}else{
			qiMsg('IP не может быть пустым!');
		}
		break;

	case "ipdel":
		$id = intval($_GET['id']);
		$new['system']->delete('anti_ip',array(
			'id'=>$id,
		));
		$arrIps = $new['system']->findAll('anti_ip');
		foreach($arrIps as $key=>$item){
			$arrIp[] = $item['ip'];
		}
		fileWrite('system_anti_ip.php','data',$arrIp);
		$tsMySqlCache->set('system_anti_ip',$arrIp);
		qiMsg('Успешно удалено!');
		break;

	case "cloud":
		include template('anti_cloud');
		break;
    case "report":

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $url = SITE_URL.'index.php?app=system&ac=anti&ts=report&page=';
        $lstart = $page*20-20;
        $arrReport = $new['system']->findAll('anti_report',null,'addtime desc',null,$lstart.',20');
        $reportNum = $new['system']->findCount('anti_report');
        $pageUrl = pagination($reportNum, 20, $page, $url);
        include template('anti_report');
        break;

    case "reportdelete":
        $reportid = intval($_GET['reportid']);
        $new['system']->delete('anti_report',array(
           'reportid'=>$reportid,
        ));
        qiMsg('Успешно удалено!');
        break;
}
