<div class="tblform">
    <form <?php echo $this->form(); ?>>

        <h4>회원정보 입력</h4>

        <fieldset>
            <table class="table_wrt">
                <colgroup>
                    <col style="width: 150px;" />
                    <col style="width: auto;" />
                </colgroup>
                <tbody>
                    <tr>
                        <th><em>*</em> 아이디</th>
                        <td>
                            <input type="text" name="id" title="아이디" class="inp w50" data-validt-action="<?php echo PH_DIR; ?>/lib/valid.ajax/idchk.ajax.php" data-validt-event="keyup" data-validt-group="id" />
                            <span class="validt" data-validt-group="id"></span>
                            <span class="tbltxt">
                                · 영어, 숫자 조합으로 입력<br />
                                · 최소 5자~최대 30자 까지 입력
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><em>*</em> 이메일</th>
                        <td>
                            <input type="text" name="email" title="이메일" class="inp w100" data-validt-action="<?php echo PH_DIR; ?>/lib/valid.ajax/emailchk.ajax.php" data-validt-event="keyup" data-validt-group="email" />
                            <span class="validt" data-validt-group="email"></span>
                            <span class="tbltxt">
                                · 회원 로그인 정보 분실시 입력한 이메일로 조회 가능
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><em>*</em> 비밀번호</th>
                        <td>
                            <input type="password" name="pwd" title="비밀번호" class="inp w100" data-validt-action="<?php echo PH_DIR; ?>/lib/valid.ajax/pwdchk.ajax.php" data-validt-event="keyup" data-validt-group="pwd" />
                            <span class="validt" data-validt-group="pwd"></span>
                            <span class="tbltxt">
                                · 최소 5자~최대 30자 까지 입력
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><em>*</em> 비밀번호 확인</th>
                        <td>
                            <input type="password" name="pwd2" title="비밀번호 확인" class="inp w100" />
                            <span class="tbltxt">
                                · 비밀번호 확인을 위해 재입력
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><em>*</em> 이름</th>
                        <td>
                            <input type="text" name="name" title="이름" class="inp w50" />
                        </td>
                    </tr>
                    <tr>
                        <th><em>*</em> 성별</th>
                        <td>
                            <label><input type="radio" name="gender" title="남자" value="M" checked />남자</label>
                            <label><input type="radio" name="gender" title="여자" value="F" />여자</label>
                        </td>
                    </tr>
                    <tr>
                        <th>휴대전화</th>
                        <td>
                            <input type="text" name="phone" title="휴대전화" class="inp w100" />
                            <span class="tbltxt">
                                · 하이픈(-) 없이 숫자만 입력
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>집전화</th>
                        <td>
                            <input type="text" name="telephone" title="집전화" class="inp w100" />
                            <span class="tbltxt">
                                · 하이픈(-) 없이 숫자만 입력
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <div class="btn-wrap">
            <a href="<?php echo PH_DOMAIN; ?>" class="btn2">취소</a>
            <button type="submit" class="btn1"><i class="fa fa-check"></i> 회원가입</button>
        </div>

    </form>
</div>
