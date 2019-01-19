<?php
defined('IN_TS') or die('Access Denied.');

class group extends tsApp{

    public function __construct($db){
        $tsAppDb = array();
        include 'app/group/config.php';
        if($tsAppDb){
            $db = new MySql($tsAppDb);
        }

        parent::__construct($db);
    }

    function getOneGroup($groupid){
        $strGroup=$this->find('group',array(
            'groupid'=>$groupid,
        ));

        if($strGroup){
            $strGroup['groupname'] = tsTitle($strGroup['groupname']);
            $strGroup['groupdesc'] = tsTitle($strGroup['groupdesc']);

            if($strGroup['photo']){
                $strGroup['photo'] = tsXimg($strGroup['photo'],'group',200,200,$strGroup['path'],1);
            }else{
                $strGroup['photo'] = SITE_URL.'public/images/group.jpg';
            }
        }

        return $strGroup;

    }

    function getRecommendGroup($num){


        $arrGroup = $this->findAll('group',array(
            'isrecommend'=>1,
        ),'orderid asc','groupid,groupname,groupdesc,path,photo,count_user',$num);

        foreach($arrGroup as $key=>$item){
            $arrGroup[$key]['groupname'] = tsTitle($item['groupname']);
            $arrGroup[$key]['groupdesc'] = tsTitle($item['groupdesc']);

            if($item['photo']){
                $arrGroup[$key]['photo'] = tsXimg($item['photo'],'group',200,200,$item['path'],1);
            }else{
                $arrGroup[$key]['photo'] = SITE_URL.'public/images/group.jpg';
            }
        }

        return $arrGroup;

    }

    function getNewGroup($num){
        $arrNewGroups = $this->db->fetch_all_assoc("select groupid from ".dbprefix."group where `isaudit`='0' order by addtime desc limit $num");
        if(is_array($arrNewGroups)){
            foreach($arrNewGroups as $item){
                $arrNewGroup[] = $this->getOneGroup($item['groupid']);
            }
        }
        return $arrNewGroup;
    }




    //Refer
    function recomment($referid){
        $strComment = $this->find('group_topic_comment',array(
            'commentid'=>$referid,
        ));

        $strComment['content'] = tsDecode($strComment['content']);

        $strComment['user'] = aac('user')->getOneUser($strComment['userid']);
        return $strComment;
    }


    public function isTopic($topicid){

        $isTopic = $this->findCount('group_topic',array(
            'topicid'=>$topicid,
        ));

        if($isTopic > 0){

            return true;

        }else{

            return false;

        }

    }

    function isGroup($groupid){

        $isGroup = $this->findCount('group',array(
            'groupid'=>$groupid,
        ));

        if($isGroup > 0){
            return true;
        }else{
            return false;
        }
    }


    public function delTopic($topicid,$groupid){

        $this->delete('group_topic',array('topicid'=>$topicid));
        $this->delete('group_topic_edit',array('topicid'=>$topicid));
        $this->delete('group_topic_comment',array('topicid'=>$topicid));
        $this->delete('tag_topic_index',array('topicid'=>$topicid));
        $this->delete('group_topic_collect',array('topicid'=>$topicid));

        $this->delete('group_topic_attach',array('topicid'=>$topicid));

        $this->delTopicComment($topicid);

        $this->countTopic($groupid);

        return true;

    }


    public function delTopicComment($topicid){
        $arrComment = $this->findAll('group_topic_comment',array(
            'topicid'=>$topicid,
        ));

        foreach($arrComment as $item){
            $this->delComment($item['commentid']);
        }

        return true;

    }

    public function delComment($commentid){
        $strComment = $this->find('group_topic_comment',array(
            'commentid'=>$commentid,
        ));

        $this->delete('group_topic_comment',array(
            'commentid'=>$commentid,
        ));

        $this->delete('group_comment_attach',array(
            'commentid'=>$commentid,
        ));

        return true;

    }

    public function hotTopics($day,$num=10){

        $startTime = time()-($day*3600*60);
        $endTime = time();

        $arrTopic = $this->findAll('group_topic',"`addtime`>'$startTime' and `addtime` < '$endTime' and and `isaudit`='0'",'count_comment desc',null,$num);

        return $arrTopic;

    }

    public function loveTopic($topicId,$userNum){
        $strLike['num'] = $this->findCount('group_topic_collect',array(
            'topicid'=>$topicId,
        ));

        $strLike['topic']=$this->find('group_topic',array(
            'topicid'=>$topicId,
        ));

        $likeUsers = $this->findAll('group_topic_collect',array(
            'topicid'=>$topicId,
        ),'addtime desc',null,$userNum);

        foreach($likeUsers as $key=>$item){
            $strLike['user'][]=aac('user')->getOneUser($item['userid']);
        }

        return $strLike;
    }

    public function countTopic($groupid){
        $count_topic = $this->findCount('group_topic',array(
            'groupid'=>$groupid,
        ));

        $this->update('group',array(
            'groupid'=>$groupid,
        ),array(
            'count_topic'=>$count_topic,
        ));

    }

    public function getHotTopic($day){
        $startTime = time()-($day*3600*60);

        $endTime = time();

        $arr = "`addtime`>'$startTime' and `count_view`>'0' and `addtime`<'$endTime'";

        $arrTopic = $this->findAll('group_topic',$arr,'addtime desc','topicid,title,count_view,count_comment',10);
        foreach($arrTopic as $key=>$item){
            $arrTopic[$key]['title']=tsTitle($item['title']);
        }

        return $arrTopic;

    }

    public function getRecommendTopic($groupid=null,$num=20){
        if($groupid){
            $arr = array(
                'groupid'=>$groupid,
                'isrecommend'=>1,
            );
        }else{
            $arr = array(
                'isrecommend'=>1,
            );
        }
        $arrTopic = $this->findAll('group_topic',$arr,'addtime desc','topicid,title',$num);

        foreach($arrTopic as $key=>$item){
            $arrTopic[$key]['title']=tsTitle($item['title']);
        }

        return $arrTopic;

    }

    public function isGroupCreater($groupid,$userid){
        $isCreater = $this->findCount('group',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));

        if($isCreater){
            return true;
        }else{
            return false;
        }
    }

    public function isGroupAdmin($groupid,$userid){
        $isAdmin = $this->findCount('group_user',array(
            'userid'=>$userid,
            'groupid'=>$groupid,
            'isadmin'=>1,
        ));
        if($isAdmin){
            return true;
        }else{
            return false;
        }
    }

    public function isGroupUser($groupid,$userid){
        $countGroupUser = $this->findCount('group_user',array(
            'groupid'=>$groupid,
            'userid'=>$userid,
        ));
        if($countGroupUser){
            return true;
        }else{
            return false;
        }
    }

    public function getTopicAttach($topicid){
        $arrAttachId = $this->findAll('group_topic_attach',array(
            'topicid'=>$topicid,
        ));
        if($arrAttachId){
            foreach ($arrAttachId as $key=>$item){
                $arrIds[] = $item['attachid'];
            }
            $attachids = arr2str($arrIds);
            $arrAttach = $this->findAll('attach',"`attachid` in ($attachids)",'addtime desc');
        }else{
            $arrAttach = '';
        }
        return $arrAttach;
    }

    public function getCommentAttach($commentid){
        $arrAttachId = $this->findAll('group_comment_attach',array(
            'commentid'=>$commentid,
        ));
        if($arrAttachId){
            foreach ($arrAttachId as $key=>$item){
                $arrIds[] = $item['attachid'];
            }
            $attachids = arr2str($arrIds);
            $arrAttach = $this->findAll('attach',"`attachid` in ($attachids)",'addtime desc');
        }else{
            $arrAttach = '';
        }
        return $arrAttach;
    }

    public function __destruct(){

    }

}
