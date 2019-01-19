function sendbox(userid){

$("#msgbox").html("Загрузка сообщения…")
$("#sendbox").html("Отправка сообщения…")

	$.ajax({
		type: "GET",
		url:  siteUrl+"index.php?app=message&ac=msgbox&userid="+userid,
		success: function(msg){
			$('#msgbox').html(msg);

			var msgbox=document.getElementById('msgbox');
			if(msgbox.scrollHeight>msgbox.offsetHeight) msgbox.scrollTop=msgbox.scrollHeight-msgbox.offsetHeight+20;

		}
	});

	$.ajax({
		type: "GET",
		url:  siteUrl+"index.php?app=message&ac=sendbox&userid="+userid,
		success: function(msg){
			$('#sendbox').html(msg);
		}
	});
}

function sendmsg(userid,touserid){
	var content = $("#boxcontent").val();
	if(content == ''){
		alert("Пожалуйста, введите текст сообщения, которое вы хотите отправить!");return false;
	}

	$("#boxcontent").attr("value",'');
	$("#sendbutton").css('display','none');
	$("#loading").css('display','block');


	$.ajax({
		type: "POST",
		url: siteUrl+"index.php?app=message&ac=sendmsg",
		data: "userid="+userid+"&touserid="+touserid+"&content="+content,
		beforeSend: function(){},
		success: function(result){
			if(result == '1'){
				$("#loading").css('display','none');
				$("#sendbutton").css('display','block');

				window.location.reload();

			}

		}
	});
}

function systembox(userid){
	$("#sendbox").html("");
	$("#msgbox").html("Загрузка системных сообщений…")
	$.ajax({
		type: "GET",
		url:  siteUrl+"index.php?app=message&ac=systembox&userid="+userid,
		success: function(msg){
			$('#msgbox').html(msg);
			var msgbox=document.getElementById('msgbox');
			if(msgbox.scrollHeight>msgbox.offsetHeight) msgbox.scrollTop=msgbox.scrollHeight-msgbox.offsetHeight+20;

		}
	});
}
