<div class="tblform">
    <form <?php echo $this->form(); ?>>

        <h4>
            회원 로그인 정보 찾기
            <em>
                회원가입시 등록한 이메일 주소를 입력해 주세요. <br />
                등록된 이메일로 로그인 아이디와 임시 비밀번호가 발송됩니다.
            </em>
        </h4>

        <fieldset>
            <table class="table_wrt">
                <colgroup>
                    <col style="width: 150px;" />
                    <col style="width: auto;" />
                </colgroup>
                <tbody>
                    <tr>
                        <th>이메일 주소</th>
                        <td>
                            <input type="text" name="email" title="이메일 주소" class="inp w100" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <div class="btn-wrap">
            <button type="submit" class="btn1"><i class="fa fa-check"></i> 회원정보 찾기</button>
        </div>

    </form>
</div>
