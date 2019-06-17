//////////////////////////////
// popup
//////////////////////////////

$(function(){
    $('.ph-pop .close').on({
        'click' : function(e){
            e.preventDefault();
            $(this).parents('.ph-pop').remove();
        }
    });

    $('.ph-pop .close-today').on({
        'click' : function(e){
            e.preventDefault();
            var idx = $(this).data('pop-idx');
            setCookie("ph_pop_"+idx,1,1);
            $(this).parents('.ph-pop').remove();
        }
    });
});

//////////////////////////////
// html/member/mbinfo.php
//////////////////////////////
$(function(){
    $('form[name=mbinfoForm] .lvBtn').on({
        'click' : function(e){
            e.preventDefault();

            if(confirm("정말로 탈퇴 처리 하시겠습니까?")){
                $('form[name=mbinfoForm] input[name=mode]').val('lv');
                $('form[name=mbinfoForm]').submit();
                $('form[name=mbinfoForm] input[name=mode]').val('mdf');
            }
        }
    });
});
