<div id="sub-tit">
    <h2>관리자 정보 변경</h2>
    <em><i class="fa fa-exclamation-circle"></i>관리자 정보는 최고 등급의 관리자만 가능</em>
</div>

<!-- article -->
<article>
    <form <?php echo $this->form(); ?>>
        <?php echo $manage->print_hidden_inp(); ?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="2" class="tal">관리자 정보 입력</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>id</th>
                    <td>
                        <input type="text" name="id" class="inp" title="id" value="<?php echo $MB['id']; ?>" />
                        <span class="tbl_sment">영어, 숫자 조합으로 입력<br />최소 5자~최대 30자 까지 입력</span>
                    </td>
                </tr>
                <tr>
                    <th>name</th>
                    <td>
                        <input type="text" name="name" class="inp" title="name" value="<?php echo $MB['name']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>email</th>
                    <td>
                        <input type="text" name="email" class="inp" title="email" value="<?php echo $MB['email']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>password</th>
                    <td>
                        <input type="password" name="pwd" class="inp" title="password" placeholder="변경시에만 입력" />
                    </td>
                </tr>
                <tr>
                    <th>password 확인</th>
                    <td>
                        <input type="password" name="pwd2" class="inp" title="password 확인" placeholder="변경시에만 입력" />
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <button type="submit" class="btn1"><i class="fa fa-check"></i>저장</button>
            </div>
        </div>
    </form>

</article>
