<div class="tblform">
    <form <?php echo $this->form(); ?>>
        <input type="hidden" name="mode" value="mdf" />

        <h4>회원정보 조회</h4>

        <!-- 활동정보 -->
        <fieldset>
            <h5>활동 정보</h5>
            <table class="table_wrt">
                <colgroup>
                    <col style="width: 150px;" />
                    <col style="width: auto;" />
                </colgroup>
                <tbody>
                    <tr>
                        <th>보유 포인트</th>
                        <td>
                            <strong><?php echo $mb['mb_point']; ?></strong> Point
                            <span class="tbltxt">
                                <a href="<?php echo PH_DIR; ?>/member/mypoint"><i class="fa fa-exclamation-circle"></i> 포인트 상세 내역 보기</a>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>회원 등급</th>
                        <td>
                            <?php echo $MB['type'][$mb['mb_level']]; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>회원가입일</th>
                        <td>
                            <?php echo $mb['mb_regdate']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>최근 로그인</th>
                        <td>
                            <?php echo $mb['mb_lately']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>최근 로그인 IP</th>
                        <td>
                            <?php echo $mb['mb_lately_ip']; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <!-- 기본정보 -->
        <fieldset class="mt30">
            <h5>기본 정보</h5>
            <table class="table_wrt">
                <colgroup>
                    <col style="width: 150px;" />
                    <col style="width: auto;" />
                </colgroup>
                <tbody>
                    <tr>
                        <th>아이디</th>
                        <td>
                            <?php echo $mb['mb_id']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>이메일</th>
                        <td>
                            <?php echo $mb['mb_email']; ?>

                            <?php if($mb['mb_email_chg']){ ?>
                                <span class="email-chg-guid">
                                    <strong><?php echo $mb['mb_email_chg']; ?></strong>로 이메일 변경 대기중입니다.<br />
                                    위 이메일로 발송된 인증 메일을 확인 하시면 이메일이 변경됩니다.<br /><br />
                                    <label><input type="checkbox" name="email_chg_cc" value="checked" /> 이메일 변경 취소</label>
                                </span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>이메일 변경</th>
                        <td>
                            <input type="text" name="email" title="이메일" class="inp w100" />
                            <span class="tbltxt">
                                · 이메일 변경시에만 입력 하세요.<br />
                                · 변경된 이메일로 발송되는 인증 메일 확인 후에 변경 완료됩니다.
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>비밀번호 변경</th>
                        <td>
                            <input type="password" name="pwd" title="비밀번호" class="inp w100" />
                            <span class="tbltxt">
                                · 비밀번호 변경시에만 입력 하세요.<br />
                                · 최소 5자~최대 30자 까지 입력
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>비밀번호 확인</th>
                        <td>
                            <input type="password" name="pwd2" title="비밀번호 확인" class="inp w100" />
                            <span class="tbltxt">
                                · 비밀번호를 변경하는 경우 비밀번호 확인을 위해 재입력
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><em>*</em> 이름</th>
                        <td>
                            <input type="text" name="name" title="이름" class="inp w50" value="<?php echo $mb['mb_name']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th><em>*</em> 성별</th>
                        <td>
                            <label><input type="radio" name="gender" title="남자" value="M" <?php echo $gender_chked['M']; ?> />남자</label>
                            <label><input type="radio" name="gender" title="여자" value="F" <?php echo $gender_chked['F']; ?> />여자</label>
                        </td>
                    </tr>
                    <tr>
                        <th>휴대전화</th>
                        <td>
                            <input type="text" name="phone" title="휴대전화" class="inp w100" value="<?php echo $mb['mb_phone']; ?>" />
                            <span class="tbltxt">
                                · 하이픈(-) 없이 숫자만 입력
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>집전화</th>
                        <td>
                            <input type="text" name="telephone" title="집전화" class="inp w100" value="<?php echo $mb['mb_telephone']; ?>" />
                            <span class="tbltxt">
                                · 하이픈(-) 없이 숫자만 입력
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <div class="btn-wrap">
            <div class="left">
                <button type="button" class="btn2 lvBtn"><i class="fa fa-user-times"></i> 회원탈퇴</button>
            </div>
            <div class="right">
                <button type="submit" class="btn1"><i class="fa fa-check"></i> 정보수정</button>
            </div>
        </div>

    </form>
</div>
