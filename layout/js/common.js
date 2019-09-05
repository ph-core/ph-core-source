//Ajax Validator
ajax_valid = {
	'init' : function(){
		this.action();
	},
	'action' : function(){
		var $ele = $('*[data-validt-event]');
		if($ele.length>0){
			$ele.each(function(){
				var $this = $(this);
				var action = $this.data('validt-action');
				var evt = $this.data('validt-event');
				var group = $this.data('validt-group');
				var $validt = $('.validt[data-validt-group='+group+']');

				$validt.hide();
				$(document).on(evt,'input[name='+group+']',function(e){
					var chk_var = true;
					if($(this).val()==''){
						chk_var = false;
						$validt.hide();
					}

					if(chk_var){
						$.ajax({
							'type' : 'POST',
							'url' : PH_DIR+"/lib/submit.php?sbmpage="+action,
							'cache' : false,
							'data' : $('input[name='+group+']').serialize(),
							'dataType' : 'html',
							'success' : function(data){
								if(data.indexOf('"success" :')==-1){
									alert('일시적인 오류 : '+data);
									return false;
								}
								var json = eval(data);
								var success = json[0].success;
								var opt = json[0].opt[0];

								switch(success){
									case 'error' :
										$validt.show().text(opt.msg).removeClass('checked');
										break;
									case 'ajax-validt' :
										$validt.show().text(opt.msg).addClass('checked');
										break;
									default :
										alert('일시적인 오류 : '+data);
								}
							}
						});
					}
				});
			});
		}
	}
}
$(function(){
	ajax_valid.init();
});

//Ajax Form Validator
valid = {
	'error' : function($form,opt){

		if(opt.input){
			var $inp = $('*[name='+opt.input+']',$form);
			var inp_tit = $inp.attr('title');
		}

		if($.trim(opt.err_code)=='ERR_NULL'){
			alert(inp_tit+' : 입력해 주세요.');

		}else if($.trim(opt.err_code)=='NOTMATCH_CAPTCHA'){
			alert('Captcha(스팸방지)가 올바르지 않습니다.');

		}else if($.trim(opt.msg)!=''){
			alert(opt.msg);

		}else{
			alert(inp_tit+' : 올바르게 입력해 주세요.');
		}
		if(opt.input){
			$inp.focus();
		}
		$('.plugin-captcha-img').attr('src',PH_PLUGIN_DIR+'/captcha/zmSpamFree.php?re&zsfimg='+new Date().getTime());
	},

	'success' : function($form,success,opt){
		switch(success){
			case 'alert->location' :
				if($.trim(opt.msg)!=''){
					alert(opt.msg);
				}
				window.document.location.href = opt.location;
				break;
			case 'alert->reload' :
				if($.trim(opt.msg)!=''){
					alert(opt.msg);
				}
				window.document.location.reload();
				break;
			case 'callback':
				if($.trim(opt.function)!=''){
					eval(opt.function);
				}
				break;
			case 'callback-txt':
				if($.trim(opt.element)!=''){
					var tagName = $(opt.element).prop('tagName').toLowerCase();
					if(tagName=="input" || tagName=="textarea"){
						$(opt.element).val(opt.msg);
					}else{
						$(opt.element).html(opt.msg);
					}
				}
				break;
			case 'alert->close->opener-reload':
				opener.document.location.reload();
				window.close();
				break;
			case 'ajax-load':
				if($.trim(opt.element)!=''){
					$(opt.element).load(opt.document);
				}
				break;
			case 'none':
				return false;
				break;
		}
	}
}

//Return Ajax Submit
returnAjaxSubmit = function($form,data){
	if(data.indexOf('"success" :')==-1){
		alert('일시적인 오류 : '+data);
		return false;
	}
	var json = eval(data);
	var success = json[0].success;
	var opt = json[0].opt[0];

	switch(success){
		case 'error' :
			valid.error($form,opt);
			break;

		case 'alert->location' :
		case 'alert->reload' :
		case 'callback' :
		case 'callback-txt' :
		case 'alert->close->opener-reload' :
		case 'ajax-load' :
		case 'none' :
			valid.success($form,success,opt);
			break;
		default :
			alert('일시적인 오류 : '+data);
	}
}

//Plugin : CKEditor
function ckeEditor_action(){
	$('textarea[ckeditor]').each(function(){
		var t_id = $(this).attr('id');
		var t_cont = CKEDITOR.instances[t_id].getData();
		$(this).val(t_cont);
	})
}

//Plugin : Captcha
captcha_reload = {
	'init' : function(){
		this.action();
	},
	'action' : function(){
		$('.plugin-captcha-img').attr({
			'src' : PH_PLUGIN_DIR+'/captcha/zmSpamFree.php?re&zsfimg='+new Date().getTime()
		});
	}
}
function captcha_action(){
    if($('.g-recaptcha').length>0){
        $('.g-recaptcha').each(function(){
            var $tar_ele = $('#'+$(this).data('name'));
            var org_val = $(this).find('textarea').val();
            $tar_ele.val(org_val);
        })
    }
}
$(function(){
	$(document).on('click','.plugin-captcha-img',function(e){
		e.preventDefault();
		captcha_reload.init();
	});
});

//Ajax Submit
ajaxSubmit = {
	'init' : function($form){
		this.action($form);
	},
	'action' : function($form){
        ckeEditor_action();
        captcha_action();

		var ajaxAction = $form.attr('ajax-action');

        $.ajax({
            'type' : 'POST',
            'url' : ajaxAction,
            'cache' : false,
            'async' : true,
            'data' : $form.find('input, select, textarea').serialize(),
            'dataType' : 'html',
            'beforeSend' : function(){

                $form.find('button,:button').attr('disabled',true);
            },
            'success' : function(data){
                returnAjaxSubmit($form,data);
                $form.find('button,:button').attr('disabled',false);
            }
        });
	}
}

//Ajax Submit With File
ajaxFileSubmit_val = false;
ajaxFileSubmit = {
	'init' : function($form){
		this.action($form);
	},
	'action' : function($form){
        ckeEditor_action();
        captcha_action();

		var ajaxAction = $form.attr('ajax-action');

		ajaxFileSubmit_val = true;
		$form.attr('action',ajaxAction);
		$form.ajaxForm({
			'cache' : false,
			'type' : 'POST',
			'dataType' : 'HTML',
			'beforeSend' : function(){
				$form.find('button,:button').attr('disabled',true);
			},
			'success' : function(data){
				returnAjaxSubmit($form,data);
				$form.find('button,:button').attr('disabled',false);
			}
		});
		$form.submit();
	}
}

//Ajax Form을 본문에서 찾아 Form setting
setAjaxForm = {
	'init' : function(){
		this.action();
	},
	'action' : function(){
		var $ele = {
			'doc' : $(document)
		}

		$ele.doc.on('submit','form[ajax-action]',function(e){
			var ajaxType = $(this).attr('ajax-type');

			switch(ajaxType){
				case 'multipart' :
					if(ajaxFileSubmit_val!=true){
						e.preventDefault();
						ajaxFileSubmit.init($(this));
					}
					break;
				case 'html' :
					e.preventDefault();
					ajaxSubmit.init($(this));
					break;
			}
		});
	}
}
$(function(){
	setAjaxForm.init();
});

//Cookie
function setCookie(name,value,expiredays){
	var todayDate = new Date();
	if(expiredays==null){
		expiredays = 30;
	}
	// Cookie 저장 시간 (1Day = 1)
	todayDate.setDate(todayDate.getDate()+expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}
function getCookie(name){
	var nameOfCookie = name + "=";
	var x = 0;
	while(x<=document.cookie.length){
		var y=(x+nameOfCookie.length);
		if (document.cookie.substring(x,y)==nameOfCookie){
		if ((endOfCookie=document.cookie.indexOf(";",y))==-1 )
		endOfCookie = document.cookie.length;
		return unescape(document.cookie.substring(y,endOfCookie));
	}
	x = document.cookie.indexOf(" ",x) + 1;
	if (x==0)
		break;
	}
	return "";
}
