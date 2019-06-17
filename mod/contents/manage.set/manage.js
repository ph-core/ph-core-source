//////////////////////////////
// view.tpl.php
//////////////////////////////
$(function(){
    $('#modifyContentsForm .delBtn').on({
        'click' : function(e){
            e.preventDefault();
            if(confirm('다시 복구할 수 없습니다.\n정말로 삭제 처리 하시겠습니까?')){
                $('input[name="mode"]').val('del');
                $('#modifyContentsForm').submit();
                $('input[name="mode"]').val('mod');
            }
        }
    });
});
