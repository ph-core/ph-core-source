<div class="tblform">
    <form <?php echo $this->form(); ?>>
        <input type="hidden" name="p_mb_idx" value="<?php echo $mb_idx; ?>" />

        <h4>회원 이메일 인증 필요</h4>

        <fieldset>
            <table class="table_wrt">
                <tbody>
                    <tr>
                        <td>
                            <p>
                                이메일 인증이 완료되지 않은 회원입니다.<br />
                                이메일로 발송된 인증메일을 확인해 주세요.<br /><br />

                                인증메일 재발송을 원하시는 경우 아래 <strong>[인증메일 재발송]</strong> 버튼을 클릭해 주세요.
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <div class="btn-wrap">
            <button type="submit" class="btn1"><i class="fa fa-check"></i> 인증메일 재발송</button>
        </div>

    </form>
</div>
