function sendweibo(){
	var content = $("#weibocontent").val();
	if(content==''){
		tsNotice('Содержание не может быть пустым!');return false;
	}
	$("#weibosend").attr('disabled','disabled');
	$.post(siteUrl+'index.php?app=weibo&ac=ajax&ts=add',{'content':content},function(rs){
		if(rs==0){
			tsNotice('Войдите и отправьте сообщение!');
		}else if(rs==1){
			tsNotice('Содержание не может быть пустым!');
		}else if(rs==2){
			$("#weibocontent").val('');
			$("#weibosend").removeAttr('disabled');
			weibolist();
		}
	});
}

function weibolist(){
	$.get(siteUrl+'index.php?app=weibo&ac=ajax&ts=list',function(rs){
		$("#weibolist").html(rs);
	})
}
