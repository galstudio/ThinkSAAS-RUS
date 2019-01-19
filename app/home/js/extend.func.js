function selectTheme(theme){
	var date=new Date();
	var expireDays=10;
	//установка даты на 10 дней позже
	date.setTime(date.getTime()+expireDays*24*3600*1000);
	$('#tsTheme').attr('href',siteUrl+'theme/'+theme+'/style.css');
	document.cookie="tsTheme="+theme+";path=/;expire="+date.toGMTString();
}
