
function commentOpen(id,gid)
{
    $('#rcomment_'+id).toggle('fast');
}

function loveTopic(tid){

    var url = siteUrl+'index.php?app=group&ac=do&ts=topic_collect';
    $.post(url,{topicid:tid},function(rs){
        if(rs == 0){

            tsNotice('Пожалуйста, войдите в систему!');

        }else if(rs == 1){

            tsNotice('Тебе не нравится запись!')

        }else if(rs == 2){

            tsNotice('Тебе нравится эта запись, не надо повторять!');

        }else{
            window.location.reload();
        }
    });
}

function taoalbum(topicid){
    $.post(siteUrl+'index.php?app=group&ac=album&ts=topic',{'topicid':topicid},function(rs){
        if(rs==0){

            tsNotice('Сначала войдите, а затем отправляйте сообщения!');

        }else if(rs == 1){

            tsNotice('Сначала создайте альбом!');

        }else {


            tsNotice(rs);


        }
    })
}

//Ctrl+Enter

function keyRecomment(rid,tid,event)
{
    if(event.ctrlKey == true)
    {
        if(event.keyCode == 13)
            recomment(rid,tid);
        return false;
    }
}

function recomment(rid,tid,token){

    c = $('#recontent_'+rid).val();
    if(c==''){alert('Содержание ответа не может быть пустым!');return false;}
    var url = siteUrl+'index.php?app=group&ac=comment&ts=recomment';
    $('#recomm_btn_'+rid).hide();
    $.post(url,{referid:rid,topicid:tid,content:c,'token':token} ,function(rs){
        if(rs == 0)
        {

            window.location.reload();
        }else{
            $('#recomm_btn_'+rid).show();
        }
    })
}

function loadTopic(userid,page){
    var num = parseInt(page)+1;
    $("#viewmore").html('<img src="'+siteUrl+'public/images/loading.gif" />');
    $.get(siteUrl+'index.php?app=group&ac=ajax&ts=topic',{'userid':userid,'page':page},function(rs){
        if(rs==''){
            $("#viewmore").html('Нет записей для просмотра...');
        }else{
            $("#before").before(rs);
            $("#viewmore").html('<a class="btn" href="javascript:void(0);" onclick="loadTopic('+userid+','+num+')">Еще записи…</a>');
        }
    })
}

function topicAudit(topicid,token){
    $.post(siteUrl+'index.php?app=group&ac=ajax&ts=topicaudit',{'topicid':topicid,'token':token},function(rs){
        if(rs==0){

            window.location.reload();
            return false;
        }else if(rs==1){

            window.location.reload();
            return false;

        }else if(rs==2){

            tsNotice('Недопустимая операция!');

        }
    })
}

function kickedGroup(groupid,userid){
    $.post(siteUrl+'index.php?app=group&ac=kicked',{'groupid':groupid,'userid':userid},function(rs){
        if(rs=='0'){

            tsNotice('Недопустимая операция!')

        }else if(rs=='1'){

            tsNotice('Недопустимая операция!')

        }else{
            window.location.reload();
        }
    })

}

function joinGroup(groupid){
    tsPost('index.php?app=group&ac=ajax&ts=joingroup',{'groupid':groupid});
}
function exitGroup(groupid){
    tsPost('index.php?app=group&ac=ajax&ts=exitgroup',{'groupid':groupid});
}

function openXuqi(userid) {
    $("#xuqi_userid").val(userid);
    var html = $("#xuqi_html").html();
    tsNotice(html);
}

function toBook(topicid){
    var book = $('#book-text').val();
    if(topicid && book){
        $.post(siteUrl+'index.php?app=group&ac=ajax&ts=book',{'topicid':topicid,'book':book},function (rs) {
            if(rs==1){
                window.location.reload()
            }else{
                $('#book-alert').html('Лейбл не может быть пустым!');
            }
        })
    }else{
        $('#book-alert').html('Лейбл не может быть пустым!');
    }
}
