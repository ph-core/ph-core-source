//////////////////////////////
// layout
//////////////////////////////
//navigator
$(function(){
    $('#side .tab a').on({
        'click' : function(e){
            e.preventDefault();
            var idx = $(this).parents('li').index();
            $('#side .tab > li').eq(idx).addClass('on').siblings().removeClass('on');
            $('#gnb .menu').eq(idx).stop().fadeIn(100).siblings().hide();
        }
    });
    $('#gnb .menu > li > a').on({
        'click' : function(e){
            e.preventDefault();
            $(this).next().stop().slideToggle(100).parents('li').toggleClass('on');
        }
    });
    $('#gnb .menu > li').each(function(){
        if($(this).hasClass('active')){
            $(this).find('a').click();
        }
    });
});

//navigator active
$(function(){
    $('#side .tab a[data-tab="'+PH_MN_HREF_TYPE+'"]').click();
    if(PH_MN_HREF_HREF){
        if(PH_MN_HREF_TYPE=="mod"){
            $('#gnb .menu a[href*="/?mod='+PH_MN_HREF_MOD+'&href='+PH_MN_HREF_HREF+'"]').parents('ul').prev('a').click();
        }else{
            $('#gnb .menu a[href*="/?href='+PH_MN_HREF_HREF+'"]').parents('ul').prev('a').click();
        }
    }
});

//label active
function label_active(){
    $('label.__label').each(function(){
        $this = $(this);
        if($this.find('input').is(':checked')){
            $this.addClass('active');
        }else{
            $this.removeClass('active');
        }
    });
}
$(window).on({
    'load' : label_active
});
$(function(){
    $('label.__label').on({
        'click' : function(){
            label_active();
        }
    });
});

//orderby
$(function(){
    $('table thead th a').each(function(){
        var href = $(this).attr('href');
        if(href.indexOf('desc')!=-1){
            $(this).attr({
                'title' : '내림차순 정렬'
            });
        }else{
            $(this).attr({
                'title' : '오름차순 정렬'
            });
        }
    });
});

//UI: datepicker
$(function(){
    $('input[datepicker]').datepicker();
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년'
    });
});
$(function(){
    $nowdate = $('#list-sch input[name=nowdate]');
    $fdate = $('#list-sch input[name=fdate]');
    $tdate = $('#list-sch input[name=tdate]');

    $fdate.datepicker('option','maxDate',$nowdate.val());
    $tdate.datepicker('option','maxDate',$nowdate.val());
    $fdate.datepicker('option','onClose',function(selectedDate){
        $tdate.datepicker('option','minDate',selectedDate);
    });
    $tdate.datepicker('option','onClose',function(selectedDate){
        $fdate.datepicker('option','maxDate',selectedDate);
    });
});

//////////////////////////////
// main.tpl.php
//////////////////////////////
$(function(){
    $('#dashboard .news-wrap a.view-feed-link').on({
        'click' : function(e){
            e.preventDefault();
            var page = $('#dashboard .news-wrap input[name=page]').val();
            var idx = $(this).data('feed-idx');
            var href = $(this).data('feed-href');
            window.open(href);
            window.document.location.href = PH_MANAGE_DIR+"/?view_dash_feed="+idx+'&page='+page;
        }
    });
});

//////////////////////////////
// theme.tpl.php
//////////////////////////////
$(function(){
    $('#themeForm input[name=theme_slt]').on({
        'change' : function(e){
            e.preventDefault();
            $('#themeForm').submit();
        }
    });
});

//////////////////////////////
// sitemap.tpl.php
//////////////////////////////
var sitemap_list = {
    'init' : function(){
        this.action();
    },
    'action' : function(functions){
        var $wait_box = $('#sitemapMofidyForm .sitemap-wait').clone();
        var list_arr = new Array;
        list_arr[0] = {
            'axis' : 'y',
            'stop' : function(){
                $('#sitemapListForm input[name=type]').val('modify');
                list_refrs();
            }
        }
        var $list_ele = new Array;

        var get_sortable = function(){
            $list_ele[0] = $('#sitemapListForm .sortable');
            $list_ele[1] = $('#sitemapListForm .st-2d');
            $list_ele[2] = $('#sitemapListForm .st-3d');
            $list_ele[0].sortable(list_arr[0]).disableSelection();
            $list_ele[1].sortable(list_arr[0]).disableSelection();
            $list_ele[2].sortable(list_arr[0]).disableSelection();
        }

        var list_charlen = function(str){
            if(escape(str).length<3){
                var min = 4 - escape(str).length;
                var output = '';
                for(var i=0;i<min;i++){
                    output += '0';
                }
            }
            output = output + str;
            return output;
        }

        var request_sbm = function(){
            $('#sitemapListForm').submit();

        }

        var list_reload = function(){
            $('#sitemapListForm').load(PH_MANAGE_DIR+'/?href=sitemapList',function(){
                get_sortable();
            });
        }
        list_reload();

        var list_refrs = function(){
            var eqidx = new Array();
            var eqval = new Array();

            get_sortable();

            $list_ele[0].find('input[name="caidx[]"]').each(function(){
                var $this = $(this);
                var depth = $this.data('depth');
                if($this.data('depth')==1){
                    eqidx[0] = parseInt($this.index('input[name="caidx[]"][data-depth=1]')) + 1;
                    eqval[0] = list_charlen(eqidx[0]);
                    if(eqidx[0]!=0) $this.val(eqval[0]);
                }
                if($this.data('depth')==2){
                    eqidx[1] = parseInt($this.parents('li').index()) + 1;
                    eqval[1] = eqval[0] + list_charlen(eqidx[1]);
                    if(eqidx[1]!=0) $this.val(eqval[1]);
                }
                if($this.data('depth')==3){
                    eqidx[2] = parseInt($this.parents('li').index()) + 1;
                    eqval[2] = eqval[1] + list_charlen(eqidx[2]);
                    if(eqidx[2]!=0) $this.val(eqval[2]);
                }
            });
            $('input[name="idx[]"]').each(function(){
                if(!$(this).val()){
                    $(this).addClass('sitemap_new_added_ele');
                }else{
                    $(this).removeClass('sitemap_new_added_ele');
                }
            })
            $('#sitemapListForm input[name=new_caidx]').val($('.sitemap_new_added_ele').eq(0).next('input[name="caidx[]"]').val());
            request_sbm();
            $('#sitemapListForm input[name=type]').val('add');
        }

        var secc_modify = function(){
            alert('성공적으로 수정 되었습니다.');
            list_reload();
        }

        if(functions){
            eval(functions+'()');
            return false;
        }

        $(document).on('click','#sitemapListForm .add-1d',function(e){
            e.preventDefault();
            var html = '<div class="st-1d"><h4><a href="#" class="modify-btn"><input type="hidden" name="idx[]" value="" /><input type="hidden" name="caidx[]" value="" data-depth="1" /><input type="hidden" name="org_caidx[]" value="" />새로운 1차 카테고리</a><i class="fa fa-trash-alt st-del del-1d"></i></h4><div class="in"><ul class="st-2d"></ul><span class="st-no-cat">아직 생성된 2차 카테고리가 없습니다.</span></div><a href="#" class="st-add add-2d"><i class="fa fa-plus"></i> 2차 카테고리 추가</a></div>';
            $(html).hide().appendTo($('.sortable')).fadeIn(200,function(){
                list_refrs();
            });
        });

        $(document).on('click','#sitemapListForm .add-2d',function(e){
            e.preventDefault();
            var $this = $(this);
            var html = '<li><p><a href="#" class="modify-btn"><input type="hidden" name="idx[]" value="" /><input type="hidden" name="caidx[]" value="" data-depth="2" /><input type="hidden" name="org_caidx[]" value="" />새로운 2차 카테고리</a><i class="fa fa-plus add-3d"></i><i class="fa fa-trash-alt st-del del-2d"></i></p><ul class="st-3d"></ul></li>';
            $(html).hide().appendTo($this.parents('.st-1d').find('.st-2d')).fadeIn(200,function(){
                $this.parents('.st-1d').find('.st-2d').sortable(list_arr[0]);
                list_refrs();
            });
            $(this).parents('.st-1d').find('.st-no-cat').remove();
        });

        $(document).on('click','#sitemapListForm .add-3d',function(e){
            e.preventDefault();
            var $this = $(this);
            var html = '<li><p><a href="#" class="modify-btn"><input type="hidden" name="idx[]" value="" /><input type="hidden" name="caidx[]" value="" data-depth="3" /><input type="hidden" name="org_caidx[]" value="" />새로운 3차 카테고리</a><i class="fa fa-trash-alt st-del del-3d"></i></p></li>';
            $(html).hide().appendTo($this.parents('li').find('.st-3d')).fadeIn(200,function(){
                $this.parents('li').find('.st-3d').sortable(list_arr[0]);
                list_refrs();
            });
        });

        $(document).on('click','#sitemapListForm .del-1d',function(e){
            e.preventDefault();
            if(!confirm('삭제하는 경우 복구할 수 없습니다.\n\n그래도 진행 하시겠습니까?')){
                return false;
            }
            var $this = $(this);
            $this.parents('.st-1d').remove();
            $('#sitemapListForm input[name=type]').val('modify');
            $('#sitemapMofidyForm').empty().append($wait_box);
            list_refrs();
        });

        $(document).on('click','#sitemapListForm .del-2d',function(e){
            e.preventDefault();
            if(!confirm('삭제하는 경우 복구할 수 없습니다.\n\n그래도 진행 하시겠습니까?')){
                return false;
            }
            var $this = $(this);
            $this.parent().parent('li').remove();
            $('#sitemapListForm input[name=type]').val('modify');
            $('#sitemapMofidyForm').empty().append($wait_box);
            list_refrs();
        });

        $(document).on('click','#sitemapListForm .del-3d',function(e){
            e.preventDefault();
            if(!confirm('삭제하는 경우 복구할 수 없습니다.\n\n그래도 진행 하시겠습니까?')){
                return false;
            }
            var $this = $(this);
            $this.parent().parent('li').remove();
            $('#sitemapListForm input[name=type]').val('modify');
            $('#sitemapMofidyForm').empty().append($wait_box);
            list_refrs();
        });

        $(document).on('click','#sitemapListForm a.modify-btn',function(e){
            e.preventDefault();
            var idx = $(this).find('input[name="idx[]"]').val();
            $('#sitemapMofidyForm').hide().load(PH_MANAGE_DIR+'/?href=sitemapModify&idx='+idx).fadeIn(100);
        });
    }
}
$(function(){
    sitemap_list.init();
});

//////////////////////////////
// modifymb.tpl.php
//////////////////////////////
$(function(){
    $('#modifymbForm .delBtn').on({
        'click' : function(e){
            e.preventDefault();
            if(confirm('다시 복구할 수 없습니다.\n정말로 탈퇴 처리 하시겠습니까?')){
                $('input[name="mode"]').val('del');
                $('#modifymbForm').submit();
                $('input[name="mode"]').val('mod');
            }
        }
    });
});

//////////////////////////////
// modifypop.tpl.php
//////////////////////////////
$(function(){
    $('#modifypopForm .delBtn').on({
        'click' : function(e){
            e.preventDefault();
            if(confirm('다시 복구할 수 없습니다.\n정말로 삭제 하시겠습니까?')){
                $('input[name="mode"]').val('del');
                $('#modifypopForm').submit();
                $('input[name="mode"]').val('mod');
            }
        }
    });
});

//////////////////////////////
// modifybn.tpl.php
//////////////////////////////
$(function(){
    $('#modifybnForm .delBtn').on({
        'click' : function(e){
            e.preventDefault();
            if(confirm('다시 복구할 수 없습니다.\n정말로 삭제 하시겠습니까?')){
                $('input[name="mode"]').val('del');
                $('#modifybnForm').submit();
                $('input[name="mode"]').val('mod');
            }
        }
    });
});

//////////////////////////////
// modifytpl.tpl.php
//////////////////////////////
$(function(){
    $('#modifytplForm .delBtn').on({
        'click' : function(e){
            e.preventDefault();
            if(confirm('다시 복구할 수 없습니다.\n정말로 삭제 하시겠습니까?')){
                $('input[name="mode"]').val('del');
                $('#modifytplForm').submit();
                $('input[name="mode"]').val('mod');
            }
        }
    });
});

//////////////////////////////
// sendmail.tpl.php
//////////////////////////////
$(function(){
    $('#sendmailForm input[name=type]').on({
        'click' : function(e){
            var type = $(this).val();
            $('#sendmailForm table tr.hd-tr[data-type='+type+']').show().siblings('.hd-tr').hide();
        }
    });
});

//////////////////////////////
// blockip.tpl.php
//////////////////////////////
$(function(){
    $('#blockipDelForm .delBtn').on({
        'click' : function(e){
            e.preventDefault();
            if(confirm('다시 복구할 수 없습니다.\n정말로 삭제 하시겠습니까?')){
                var idx = $(this).data('idx');

                $('input[name="idx"]').val(idx);
                $('#blockipDelForm').submit();
                $('input[name="idx"]').val('');
            }
        }
    });
});

//////////////////////////////
// blockip.tpl.php
//////////////////////////////
$(function(){
    $('#blockmbDelForm .delBtn').on({
        'click' : function(e){
            e.preventDefault();
            if(confirm('다시 복구할 수 없습니다.\n정말로 삭제 하시겠습니까?')){
                var idx = $(this).data('idx');

                $('input[name="idx"]').val(idx);
                $('#blockmbDelForm').submit();
                $('input[name="idx"]').val('');
            }
        }
    });
});
