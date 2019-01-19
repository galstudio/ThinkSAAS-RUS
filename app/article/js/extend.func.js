function recommend(articleid){
	$.post(siteUrl+'index.php?app=article&ac=recommend',{'articleid':articleid},function(rs){
		if(rs==0){

            tsNotice('Сначала войдите, а потом рекомендуйте!');

		}else if(rs == 1){

            tsNotice('Вы уже порекомендовали!');

		}else if(rs == 2){

			window.location.reload()
		}
	})
}
