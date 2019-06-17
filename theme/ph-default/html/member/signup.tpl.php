<div class="tblform">
    <form <?php echo $this->form(); ?>>

        <h4>회원가입</h4>

        <!-- 이용약관 동의 -->
        <fieldset class="agr-box">
            <h5>이용약관 동의</h5>
            <div class="txt">
                <?php echo $CONF['policy']; ?>
            </div>
            <label class="chk"><input type="checkbox" name="chk_policy" title="이용약관" value="checked" /> 이용약관에 동의 합니다.</label>
        </fieldset>

        <!-- 개인정보처리방침 동의 -->
        <fieldset class="agr-box">
            <h5>개인정보처리방침 동의</h5>
            <div class="txt">
                <?php echo $CONF['privacy']; ?>
            </div>
            <label class="chk"><input type="checkbox" name="chk_private" title="개인정보처리방침" value="checked"  /> 개인정보처리방침 동의 합니다.</label>
        </fieldset>

        <div class="btn-wrap">
            <a href="<?php echo PH_DOMAIN; ?>" class="btn2">취소</a>
            <button type="submit" class="btn1"><i class="fa fa-check"></i> 다음</button>
        </div>

    </form>
</div>
