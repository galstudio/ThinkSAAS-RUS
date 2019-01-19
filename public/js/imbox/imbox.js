var newMessageRemind = {
	_step : 0,
	_title : document.title,
	_timer : null,
	show : function () {
		var temps = newMessageRemind._title.replace("[　　　]", "").replace("[Новое] ", "");
		newMessageRemind._timer = setTimeout(function () {
				newMessageRemind.show();
				//Cookie
				newMessageRemind._step++;
				if (newMessageRemind._step == 3) {
					newMessageRemind._step = 1
				};
				if (newMessageRemind._step == 1) {
					document.title = "[　　　]" + temps
				};
				if (newMessageRemind._step == 2) {
					document.title = "[Новое] " + temps
				};
			}, 800);
		return [newMessageRemind._timer, newMessageRemind._title];
	},
	clear : function () {
		clearTimeout(newMessageRemind._timer);
		document.title = newMessageRemind._title;
		//Cookie
	}
};
function clearNewMessageRemind() {
	newMessageRemind.clear();
}
function evdata(userid) {
	$.ajax({
		type : "GET",
		url : siteUrl + "index.php?app=message&ac=newmsg&userid=" + userid,
		success : function (msg) {
			if (msg == '0') {}
			else if (msg > 0) {
				$('#newmsg').html(msg);
				newMessageRemind.show();
			}
		}
	});
}
