//////////////////////////////
// write.tpl.php
//////////////////////////////
$(function(){
    $('#modifyBoardForm .delBtn').on({
        'click' : function(e){
            e.preventDefault();
            if(confirm('다시 복구할 수 없습니다.\n정말로 삭제 처리 하시겠습니까?')){
                $('input[name="mode"]').val('del');
                $('#modifyBoardForm').submit();
                $('input[name="mode"]').val('mod');
            }
        }
    });
});
