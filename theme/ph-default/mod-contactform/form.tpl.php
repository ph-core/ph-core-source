<div class="tblform">
    <form <?php echo $this->form(); ?>>

        <h4>고객 문의</h4>

        <fieldset>
            <table class="table_wrt">
                <colgroup>
                    <col style="width: 150px;" />
                    <col style="width: auto;" />
                </colgroup>
                <tbody>

                    <?php
                    //회원인 경우 회원 이름 출력
                    if(IS_MEMBER){
                    ?>

                    <tr>
                        <th>회원 이름</th>
                        <td>
                            <input type="hidden" name="name" value="<?php echo $MB['name']; ?>" />
                            <?php echo $MB['name']; ?>
                        </td>
                    </tr>

                    <?php
                    //회원이 아닌 경우 이름 입력 input 출력
                    }else{
                    ?>

                    <tr>
                        <th><em>*</em> 문의자 이름</th>
                        <td>
                            <input type="text" name="name" title="문의자 이름" class="inp w50" />
                        </td>
                    </tr>

                    <?php } ?>

                    <tr>
                        <th><em>*</em> 이메일</th>
                        <td>
                            <input type="text" name="email" title="이메일" value="<?php echo $MB['email']; ?>" class="inp w100" />
                            <span class="tbltxt">
                                · 문의에 대한 답변은 이메일로 발송 됩니다.<br />
                                · 정확하게 입력해 주시기 바랍니다.
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><em>*</em> 연락처</th>
                        <td>
                            <input type="text" name="phone" title="연락처" value="<?php echo $MB['phone']; ?>" class="inp w100" />
                            <span class="tbltxt">
                                · 하이픈(-) 없이 입력 하세요.
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><em>*</em> 문의 내용</th>
                        <td>
                            <textarea name="article" title="문의 내용"></textarea>
                        </td>
                    </tr>

                    <?php
                    //회원이 아닌 경우 스팸방지 코드 보임
                    if(!IS_MEMBER){
                    ?>

                    <tr>
                        <th><em>*</em> 스팸방지 코드</th>
                        <td>
                            <?php echo $captcha; ?>
                        </td>
                    </tr>

                    <?php } ?>

                </tbody>
            </table>
        </fieldset>

        <div class="btn-wrap">
            <a href="<?php echo PH_DOMAIN; ?>" class="btn2">취소</a>
            <button type="submit" class="btn1">문의하기</button>
        </div>

    </form>
</div>
