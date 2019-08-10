//////////////////////////////
// board > list
//////////////////////////////

//관리 checkbox 전체 선택
$(function(){
    $(document).on('click','.cnum_allchk',function(){
        var chked = $(this).is(':checked');
        if(chked){
            $('input[name="cnum[]"]').prop('checked',true);
        }else{
            $('input[name="cnum[]"]').prop('checked',false);
        }
    });
});

//관리팝업
$(function(){

    var $form = '';
    var $ctrpop = '';
    var $ctrpopBG = '';

    //open
    $(document).on('click','#list-ctr-btn',function(e){
        e.preventDefault();

        var $form = $('#board-listForm');

        var cnum = $form.find(':checkbox[name="cnum[]"]:checked');
        if(cnum.length<1){
            alert('게시글을 한개 이상 선택해 주세요.');
            return false;
        }
        $('<div id="ctrpop-bg"></div>').appendTo('body');
        $('<div id="ctrpop"></div>').appendTo('body');
        $ctrpop = $('#ctrpop');
        $ctrpopBG = $('#ctrpop-bg');

        $.ajax({
            'type' : 'POST',
            'url' : MOD_BOARD_DIR+'/controller/ctrpop?OUTLOAD=1',
            'cache' : false,
            'data' : $form.serialize(),
            'dataType' : 'html',
            'success' : function(data){
                $ctrpop.html(data).fadeIn(100);
                $ctrpopBG.fadeIn(100);
            }
        });
    });

    //close
    $(document).on('click','#ctrpop .close',function(e){
        e.preventDefault();
        $ctrpop.fadeOut(100);
        $ctrpopBG.fadeOut(100);
    });

    //삭제 버튼을 클릭하는 경우
    $(document).on('click','#board_ctrpopForm #delete-btn',function(e){
        e.preventDefault();
        if(confirm("정말로 삭제 하시겠습니까?\n\n선택된 게시물이 많은 경우 시간이 다소 소요될 수 있습니다.")==true){
            $("#board_ctrpopForm input[name=type]").val("del");
            $('#board_ctrpopForm').submit();
        }
    });

    //복사 버튼을 클릭하는 경우
    $(document).on('click','#board_ctrpopForm #copy-btn',function(e){
        e.preventDefault();
        if(confirm("답글은 복사 되지 않습니다.\n계속 진행 하시겠습니까?\n\n선택된 게시물이 많은 경우 시간이 다소 소요될 수 있습니다.")==true){
            $("#board_ctrpopForm input[name=type]").val("copy");
            $('#board_ctrpopForm').submit();
        }
    });

    //이동 버튼을 클릭하는 경우
    $(document).on('click','#board_ctrpopForm #move-btn',function(e){
        e.preventDefault();
        if(confirm("답글은 부모글 없이 단독으로 이동되지 않습니다.\n계속 진행 하시겠습니까?\n\n선택된 게시물이 많은 경우 시간이 다소 소요될 수 있습니다.")==true){
            $("#board_ctrpopForm input[name=type]").val("move");
            $('#board_ctrpopForm').submit();
        }
    });
});

//작성자 정보 팝업
$(function(){

    var $form = '';
    var $mbpop = '';
    var $mbpopBG = '';

    //open
    $(document).on('click','*[data-profile]',function(e){
        e.preventDefault();

        var $form = $('#board-listForm');
        var mb_idx = $(this).data('profile');
        var board_id = $form.find('input[name=board_id]').val();

        $('<div id="mbpop-bg"></div>').appendTo('body');
        $('<div id="mbpop"></div>').appendTo('body');
        $mbpop = $('#mbpop');
        $mbpopBG = $('#mbpop-bg');

        $.ajax({
            'type' : 'GET',
            'url' : MOD_BOARD_DIR+'/controller/mbpop?OUTLOAD=1',
            'cache' : false,
            'data' : {
                'board_id' : board_id,
                'mb_idx' : mb_idx
            },
            'dataType' : 'html',
            'success' : function(data){
                $mbpop.html(data).fadeIn(100);
                $mbpopBG.fadeIn(100);
            }
        });
    });

    //close
    $(document).on('click','#mbpop .close',function(e){
        e.preventDefault();
        $mbpop.fadeOut(100);
        $mbpopBG.fadeOut(100);
    });

});

//////////////////////////////
// board > write
//////////////////////////////

//공지사항 옵션 체크시 답변알림 옵션 & 카테고리 숨김
var use_notice_opt = function($this){
    var chked = $this.is(':checked');
    if(chked){
        $('#use_email').parents('label').hide();
        $('#category').prop('disabled',true);
    }else{
        $('#use_email').parents('label').show();
        $('#category').prop('disabled',false);
    }
}
$(function(){
    var $opt = $('#use_notice');
    $opt.on({
        'click' : function(){
            use_notice_opt($opt);
        }
    });
    use_notice_opt($opt);
});

//////////////////////////////
// board > view
//////////////////////////////

//view 하단 리스트 로드
$(function(){
    if($('#board-ft-list').length>0){
        var $ftlist_wrap = $('#board-ft-list');
        var ftlist_board_id = $('#board-readForm input[name=board_id]').val();
        var ftlist_read = $('#board-readForm input[name=read]').val();
        var ftlist_page = $('#board-readForm input[name=page]').val();
        var ftlist_category = $('#board-readForm input[name=category]').val();
        var ftlist_where = $('#board-readForm input[name=where]').val();
        var ftlist_keyword = $('#board-readForm input[name=keyword]').val();
        var ftlist_thisuri = $('#board-readForm input[name=thisuri]').val();
        $ftlist_wrap.load(MOD_BOARD_DIR+'/controller/lists?OUTLOAD=1&CALL_CLASSNAME=\\Module\\Board\\lists&board_id='+ftlist_board_id+'&mode=view&read='+ftlist_read+'&page='+ftlist_page+'&category='+encodeURI(ftlist_category)+'&where='+ftlist_where+'&keyword='+ftlist_keyword+'&is_ftlist=Y&thisuri='+ftlist_thisuri);
    }
});

//view 좋아요/싫어요
$(function(){
    $(document).on('click','#board-likes .btn-likes',function(e){
        e.preventDefault();
        $form = $('#board-likes');
        $('input[name=mode]',$form).val('likes');
        $form.submit();
    });
    $(document).on('click','#board-likes .btn-unlikes',function(e){
        e.preventDefault();
        $form = $('#board-likes');
        $('input[name=mode]',$form).val('unlikes');
        $form.submit();
    });
});

//글 삭제
$(function(){
    $(document).on('click','#del-btn',function(e){
        e.preventDefault();
        var thisuri = $('#board-readForm input[name=thisuri]').val();
        if(confirm("이 글을 삭제 하시겠습니까?")){
            $('#board-readForm').attr({
                'method' : 'POST',
                'action' : PH_DOMAIN+thisuri+'?mode=delete'
            }).submit();
        }
    });
});

//Comment 로드
cmt_stat_mdf = false;

function view_cmt_load(){
    var comment_board_id = $('#board-readForm input[name=board_id]').val();
    var comment_read = $('#board-readForm input[name=read]').val();
    $('#board-comment').load(MOD_BOARD_DIR+'/controller/comment?OUTLOAD=1&board_id='+comment_board_id+'&read='+comment_read,function(){
        if($('.g-recaptcha').length<1){
            return false;
        }
        var comment_timer;
        var comment_load = function(){
            if(g_recaptcha_captcha_act>0){
                g_recaptcha_captcha(1);
            }else{
                if(comment_timer){
                    clearTimeout(comment_timer);
                }
                comment_timer = setTimeout(comment_load,200);
            }
        }
        comment_load();
    });
}
$(function(){
    view_cmt_load();
});

//Comment 작성
$(function(){
    $(document).on('click','#commentForm .sbm',function(e){
        e.preventDefault();
        $('#commentForm input[name=mode]').val('write');
        $('#commentForm input[name=cidx]').val('');
        $('#commentForm').submit();
    });
});

//Comment 삭제
$(function(){
    $(document).on("click","#cmt-delete",function(e){
        e.preventDefault();
        if(confirm("댓글을 삭제 하시겠습니까?")==true){
            var cidx = $(this).data('cmt-delete');
            $('#commentForm input[name=mode]').val('delete');
            $('#commentForm input[name=cidx]').val(cidx);
            $("#commentForm").submit();
        }
    });
});

//Comment 답글 작성
$(function(){
    var comm_re_form_idx = 0;
    var $comm_re_form;

    $(document).on('click','#cmt-reply',function(e){
        e.preventDefault();

        if(cmt_stat_mdf){
            $('li.comm-list-li .comment > p').show();
            $('#comm-re-form textarea[name=re_comment]').val('');
            cmt_stat_mdf = false;
        }

        var vis = $('> #comm-re-form',$(this).parents('li.comm-list-li')).is(':visible');

        if(comm_re_form_idx==0){
            $comm_re_form = $('#comm-re-form').html();
            comm_re_form_idx++;
        }

        if(!vis){
            $('#comm-re-form').remove();
            $('#commentForm input[name=cidx]').val($(this).data("cmt-reply"));
            $(this).parents('li.comm-list-li').append('<div id="comm-re-form">' + $comm_re_form + '</div>');
            $(this).parents('li.comm-list-li').find('#comm-re-form').show();
            if($('.g-recaptcha').length>0){
                g_recaptcha_re_captcha(1);
            }
            captcha_reload.init();
        }else{
            $('#comm-re-form').hide();
        }
    });
    $(document).on('click','#commentForm .re_sbm',function(e){
        e.preventDefault();
        if(!cmt_stat_mdf){
            $('#commentForm input[name=mode]').val('reply');
            $('#commentForm').submit();
        }
    });
});

//Comment 수정
$(function(){
    $(document).on('click','#cmt-modify',function(e){
        e.preventDefault();

        if(cmt_stat_mdf){
            $('li.comm-list-li').find('.comment').find('p').show();
            $('#comm-re-form textarea[name=re_comment]').val('');
            cmt_stat_mdf = false;
        }

        var vis = $('.comment #comm-re-form',$(this).parents('li.comm-list-li')).is(':visible');
        var comment = $('.comment > p',$(this).parents('li.comm-list-li')).text();
        if(!vis){
            $comm_re_form = $('#comm-re-form').clone();
            $('#comm-re-form').remove();
            $('#commentForm input[name=cidx]').val($(this).data("cmt-modify"));
            $('.comment > p',$(this).parents('li.comm-list-li')).hide();
            $('.comment',$(this).parents('li.comm-list-li')).append($comm_re_form);
            $('#comm-re-form',$(this).parents('li.comm-list-li')).show();
            $('#comm-re-form textarea[name=re_comment]').val(comment);
            captcha_reload.init();
            cmt_stat_mdf = true;
        }else{
            $('#comm-re-form').hide();
            $('.comment > p',$(this).parents('li.comm-list-li')).show();
            cmt_stat_mdf = false;
        }
    });
    $(document).on('click','#commentForm .re_sbm',function(e){
        e.preventDefault();
        if(cmt_stat_mdf){
            $('#commentForm input[name=mode]').val('modify');
            $('#commentForm').submit();
        }
    });
});
