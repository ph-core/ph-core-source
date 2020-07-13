<div id="signin">
    <form <?php echo $this->form(); ?>>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />

        <h4>회원 로그인</h4>

        <fieldset class="inp-wrap">
            <input type="text" name="id" placeholder="회원 ID" title="회원 ID" class="inp" value="<?php echo $id_val; ?>" />
            <input type="password" name="pwd" placeholder="비밀번호" title="비밀번호" class="inp" />
            <label><input type="checkbox" name="save" value="checked" <?php echo $save_checked; ?> /> 회원 아이디를 저장 하겠습니다.</label>
            <button type="submit" class="sbm">로그인</button>
        </fieldset>

        <ul class="ft-btn">
            <li><a href="<?php echo PH_DIR; ?>/member/signup">신규 회원가입</a></li>
            <li><a href="<?php echo PH_DIR; ?>/member/forgot">아이디/비밀번호 찾기</a></li>
        </ul>

    </form>

    <h4 class="mt20">SNS 로그인</h4>

    <!-- Kakao login -->
    <a id="kakao-login" href="<?php echo PH_PLUGIN_DIR; ?>/snslogin/getlogin.php?get_sns=kakao&redirect=<?php echo $redirect; ?>">카카오 아이디로 로그인</a>
    <!-- Naver login -->
    <a id="naver-login" href="<?php echo PH_PLUGIN_DIR; ?>/snslogin/getlogin.php?get_sns=naver&redirect=<?php echo $redirect; ?>">네이버 아이디로 로그인</a>

    <p class="mt10">SNS 로그인시 <strong>이용약관</strong>과 <strong>개인정보처리방침</strong>에 동의하는 것으로 간주합니다.</p>
</div>
