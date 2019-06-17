1<div id="sub-tit">
    <h2>온라인 문의 확인</h2>
    <em><i class="fa fa-exclamation-circle"></i>온라인 문의 내역 확인 및 답변</em>
</div>

<!-- article -->
<article>
    <form <?=$this->form()?>>
        <?=$manage->print_hidden_inp()?>
        <input type="hidden" name="mode" value="rep" />
        <input type="hidden" name="idx" value="<?=$view['idx']?>" />

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="2" class="tal">온라인 문의 내용</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>문의자</th>
                    <td>
                        <strong><?=$print_name?></strong>
                    </td>
                </tr>
                <tr>
                    <th>문의자 이메일</th>
                    <td>
                        <?=$view['email']?>
                        <span class="tbl_sment">위 이메일로 답변 메일이 발송됩니다.</span>
                    </td>
                </tr>
                <tr>
                    <th>문의자 연락처</th>
                    <td>
                        <?=$view['phone']?>
                    </td>
                </tr>
                <tr>
                    <th>문의 작성일</th>
                    <td>
                        <?=$view['regdate']?>
                    </td>
                </tr>
                <tr>
                    <th>답변 여부</th>
                    <td>
                        <?=$print_reply?>
                    </td>
                </tr>
                <tr>
                    <th>문의 내용</th>
                    <td>
                        <?=$view['article']?>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php if($is_reply_show){ ?>
        <table class="table1 mt20">
            <thead>
                <tr>
                    <th colspan="2" class="tal">등록된 답변 (<?=$print_reply?>)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>답변 내용</th>
                    <td class="nostyle">
                        <?=$repview['article']?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php } ?>

        <?php if(!$is_reply_show){ ?>
        <table class="table1 mt20">
            <thead>
                <tr>
                    <th colspan="2" class="tal">답변 발송</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>답변 내용</th>
                    <td>
                        <textarea id="article" name="article" title="답변 내용"></textarea>
                        <script type="text/javascript">CKEDITOR.replace('article');</script>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php } ?>

        <div class="btn-wrap">
            <div class="center">
                <a href="#" class="btn2 delBtn mr30"><i class="fa fa-trash-alt"></i>문의 내역 삭제</a>
                <a href="./?mod=<?=MOD_CONTACTFORM?>&href=lists<?=$manage->lnk_def_param()?>" class="btn1"><i class="fa fa-bars"></i>리스트</a>
                <button type="submit" class="btn1"><i class="fa fa-check"></i>답변 발송</button>
            </div>
        </div>
    </form>

</article>
