
function tsNotice(msg,title){

    $('#myModal').modal('hide');

    var chuangkou = '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <div class="modal-title" id="myModalLabel">Подсказка</div> <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button> </div> <div class="modal-body"> </div> <div class="modal-footer"> <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Закрыть</button> </div> </div> </div> </div>';

    $('body').prepend(chuangkou);

	if(title==''){
		title = 'Подсказка';
	}
	$(".modal-body").html(msg);
	$(".modal-title").html(title);
	$('#myModal').modal('show');
	//return false;
}

function qianDao(){
    if(siteUid==0){
        tsNotice('Пожалуйста, войдите снова!');
        return false;
    }else{
        $.post(siteUrl+'index.php?app=user&ac=signin',function(rs){
            if(rs==1){
                $.get(siteUrl+'index.php?app=user&ac=signin&ts=ajax',function(rs){
                    $("#qiandao").html(rs);
                })
            }else{
                tsNotice('Вход не выполнен!');
            }
        })
    }
}

function newgdcode(obj, url) {
    obj.src = url + "&nowtime=" + new Date().getTime()
}

function searchon() {
    $("#searchto").submit()
}

function follow(userid, token) {
    $.getJSON(siteUrl + "index.php?app=user&ac=follow&ts=do", {
        "userid": userid,
        "token": token
    },
    function(json) {
        if (json.status == 0) {
			tsNotice(json.msg);
        } else {
            if (json.status == 1) {
				tsNotice(json.msg);
            } else {
                if (json.status == 2) {
					tsNotice(json.msg);
                    window.location.reload()
                }
            }
        }
    })
}

function unfollow(userid, token) {
    $.getJSON(siteUrl + "index.php?app=user&ac=follow&ts=un", {
        "userid": userid,
        "token": token
    },
    function(json) {
        if (json.status == 0) {
			tsNotice(json.msg);
        } else {
            if (json.status == 1) {
				tsNotice(json.msg);
                window.location.reload()
            }
        }
    })
}

/*
 * POST, JSON
 * url 	URL。
 * datas
 */
function tsPost(url,datas){
	$.post(siteUrl+url,datas,function(rs){

        if(rs.url){

            //напоминание
            tsNotice(rs.msg+'<br />через <span class="text-danger" id="notice_daojishi">3</span> секунды вы будете перенапралены…');

            var step = 3;
            var _res = setInterval(function() {

                $('#notice_daojishi').html(step);
                step-=1;
                if(step <= 0){
                    window.location = rs.url;
                    clearInterval(_res);//setInterval
                }
            },1000);

        }else{
            tsNotice(rs.msg);
        }


	},'json')
}

jQuery(document).ready(function(){
    $('#comm-form').on('submit', function() {
        //alert(event.type);
        $('button[type="submit"]').html('Отправка…');
        $('button[type="submit"]').attr("disabled", true);

        $.ajax({
            cache: true,
            type: "POST",
            url:$(this).prop('action')+'&js=1',
            data:$(this).serialize(),
            dataType: "json",
            async: false,

            error: function(request) {
                tsNotice('Запрос не выполнен!');
                $('button[type="submit"]').removeAttr("disabled");
                $('button[type="submit"]').html('Еще раз');
            },

            success: function(rs) {

                if(rs.url){

                    //напоминание
                    tsNotice(rs.msg+'<br />через <span class="text-danger" id="notice_daojishi">3</span> секунды вы будете перенаправлены…');

                    var step = 3;
                    var _res = setInterval(function() {

                        $('#notice_daojishi').html(step);
                        step-=1;
                        if(step <= 0){
                            window.location = rs.url;
                            clearInterval(_res);//setInterval
                        }
                    },1000);

                }else{
                    tsNotice(rs.msg);
                    $('button[type="submit"]').removeAttr("disabled");
                    $('button[type="submit"]').html('Еще раз');
                }

            }
        });
        return false;
    });

});

if ($('html').hasClass('lt-ie8')) {
	var message = '<div class="alert alert-warning" style="margin-bottom:0;text-align:center;">';
	message += 'Ваша версия браузера слишком старая, чтобы правильно работать на этом сайте, пожалуйста, используйте';
	message += '<a href="https://windows.microsoft.com/ru-ru/internet-explorer/downloads/ie" target="_blank">IE8 браузер</a>、';
	message += '<a href="https://www.google.com/chrome/" target="_blank">Google Chrome</a> <strong>(рекомендуется)</strong>、';
	message += '<a href="https://www.mozilla.org/ru/firefox/" target="_blank">Firefox браузер</a>.';
	message += '</div>';

	$('body').prepend(message);
}

$(function(){
    $("#comm-form").validation();
    $("#comm-submit").on('click',function(event){
        // valid()
        //  valide(object,msg)
        //  valide(msg)
        if ($("#comm-form").valid(this,'Неполное заполнение информации!')==false){
            return false;
        }
    })
})

$(document).ready(function () {
    $('.ts-top-nav .navbar-toggle').click(function() {
        if ($(this).parents('.ts-top-nav').find('.navbar-collapse').hasClass('active')) {
            $(this).parents('.ts-top-nav').find('.navbar-collapse').removeClass('active');
        } else {
            $(this).parents('.ts-top-nav').find('.navbar-collapse').addClass('active');
        }
    });
});
