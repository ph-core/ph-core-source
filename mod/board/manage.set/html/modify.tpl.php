<div id="sub-tit">
    <h2>게시판 정보 관리</h2>
    <em><i class="fa fa-exclamation-circle"></i>게시판 정보 확인 및 관리</em>
</div>

<!-- article -->
<article>
    <form <?=$this->form()?>>
        <?=$manage->print_hidden_inp()?>
        <input type="hidden" name="mode" value="mod" />
        <input type="hidden" name="idx" value="<?=$write['idx']?>" />

        <?=$print_target[0]?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="2" class="tal">게시판 기본 설정</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>게시판 id</th>
                    <td>
                        <strong><?=$write['id']?></strong>
                        <span class="tbl_sment">게시판 id는 변경 불가합니다.</span>
                    </td>
                </tr>
                <tr>
                    <th>게시판 title</th>
                    <td>
                        <input type="text" name="title" class="inp w50p" title="게시판 title" value="<?=$write['title']?>" />
                        <span class="tbl_sment">브라우저 title에 노출됨</span>
                    </td>
                </tr>
                <tr>
                    <th>게시판 theme</th>
                    <td>
                        <select name="theme" class="inp">
                            <?=$board_theme?>
                        </select>
                        <span class="tbl_sment">게시판 theme 설치 경로: (/theme/<?=PH_THEME?>/mod-<?=MOD_BOARD?>/board/)</span>
                    </td>
                </tr>
                <tr>
                    <th>카테고리 사용</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_category" value="Y" <?=$use_category['Y']?> /> 사용</label>
                        <label><input type="radio" name="use_category" value="N" <?=$use_category['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>카테고리 설정</th>
                    <td>
                        <input type="text" name="category" class="inp w50p" title="카테고리 설정" value="<?=$write['category']?>" />
                        <span class="tbl_sment">파이프(|)로 구분하여 카테고리 설정 (ex: 카테고리1|카테고리2|카테고리3)</span>
                    </td>
                </tr>
                <tr>
                    <th>하단 리스트 사용</th>
                    <td>
                        <table class="table1">
                            <tbody>
                                <tr>
                                    <th>PC</th>
                                    <td>
                                        <label class="mr10"><input type="radio" name="use_list" value="Y" <?=$use_list['Y']?> /> 사용</label>
                                        <label><input type="radio" name="use_list" value="N" <?=$use_list['N']?> /> 사용안함</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td>
                                        <label class="mr10"><input type="radio" name="m_use_list" value="Y" <?=$m_use_list['Y']?> /> 사용</label>
                                        <label><input type="radio" name="m_use_list" value="N" <?=$m_use_list['N']?> /> 사용안함</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <span class="tbl_sment">글읽기 화면 하단에 리스트 노출 여부</span>
                    </td>
                </tr>
                <tr>
                    <th>목록 글 갯수</th>
                    <td>
                        <table class="table1">
                            <tbody>
                                <tr>
                                    <th>PC</th>
                                    <td>
                                        <input type="text" name="list_limit" class="inp" title="목록 글 갯수" value="<?=$write['list_limit']?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td>
                                        <input type="text" name="m_list_limit" class="inp" title="모바일 목록 글 갯수" value="<?=$write['m_list_limit']?>" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <span class="tbl_sment">설정한 글 갯수 초과시 Paging으로 페이지 나눔</span>
                    </td>
                </tr>
                <tr>
                    <th>리스트 제목 글자수</th>
                    <td>
                        <table class="table1">
                            <tbody>
                                <tr>
                                    <th>PC</th>
                                    <td>
                                        <input type="text" name="sbj_limit" class="inp" title="리스트 제목 글자수" value="<?=$write['sbj_limit']?>" /> 글자
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td>
                                        <input type="text" name="m_sbj_limit" class="inp" title="모바일 리스트 제목 글자수" value="<?=$write['m_sbj_limit']?>" /> 글자
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <span class="tbl_sment">설정한 글자수 초과시 '···' 로 글자 자름</span>
                    </td>
                </tr>
                <tr>
                    <th>리스트 본문 글자수</th>
                    <td>
                        <table class="table1">
                            <tbody>
                                <tr>
                                    <th>PC</th>
                                    <td>
                                        <input type="text" name="txt_limit" class="inp" title="리스트 제목 글자수" value="<?=$write['txt_limit']?>" /> 글자
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td>
                                        <input type="text" name="m_txt_limit" class="inp" title="모바일 리스트 제목 글자수" value="<?=$write['m_txt_limit']?>" /> 글자
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <span class="tbl_sment">설정한 글자수 초과시 '···' 로 글자 자름</span>
                    </td>
                </tr>
                <tr>
                    <th>좋아요/싫어요 사용</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_likes" value="Y" <?=$use_likes['Y']?> /> 사용</label>
                        <label><input type="radio" name="use_likes" value="N" <?=$use_likes['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>답글 사용</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_reply" value="Y" <?=$use_reply['Y']?> /> 사용</label>
                        <label><input type="radio" name="use_reply" value="N" <?=$use_reply['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>코멘트 사용</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_comment" value="Y" <?=$use_comment['Y']?> /> 사용</label>
                        <label><input type="radio" name="use_comment" value="N" <?=$use_comment['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>비밀글 사용</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_secret" value="Y" <?=$use_secret['Y']?> /> 사용</label>
                        <label><input type="radio" name="use_secret" value="N" <?=$use_secret['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>비밀글 기본 사용</th>
                    <td>
                        <label class="mr10"><input type="radio" name="ico_secret_def" value="Y"  <?=$ico_secret_def['Y']?>/> 비밀글 기본 사용</label>
                        <label><input type="radio" name="ico_secret_def" value="N" <?=$ico_secret_def['N']?> /> 사용안함</label>
                        <span class="tbl_sment">글 작성시 비밀글이 기본으로 작성 되도록 설정</span>
                    </td>
                </tr>
                <tr>
                    <th>첨부파일1 사용</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_file1" value="Y" <?=$use_file1['Y']?> /> 사용</label>
                        <label><input type="radio" name="use_file1" value="N" <?=$use_file1['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>첨부파일2 사용</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_file2" value="Y" <?=$use_file2['Y']?> /> 사용</label>
                        <label><input type="radio" name="use_file2" value="N" <?=$use_file2['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>첨부파일 용량제한</th>
                    <td>
                        <input type="text" name="file_limit" class="inp" title="첨부파일 용량제한" value="<?=$write['file_limit']?>" /> byte
                        <span class="tbl_sment">byte 단위로 입력. (1M = 1048576)<br />현재 서버에서 설정 가능한 최대 값: <strong><?=ini_get('upload_max_filesize')?></strong></span>
                    </td>
                </tr>
                <tr>
                    <th>글작성 최소 글자수</th>
                    <td>
                        <input type="text" name="article_min_len" class="inp" title="글작성 최소 글자수" value="<?=$write['article_min_len']?>" /> 글자
                        <span class="tbl_sment">글 작성시 본문 글자수가 설정한 값에 미달하는 경우 글 등록 불가</span>
                    </td>
                </tr>
                <tr>
                    <th>관리자 피드 소식</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_mng_feed" value="Y" <?=$use_mng_feed['Y']?> /> 사용</label>
                        <label><input type="radio" name="use_mng_feed" value="N" <?=$use_mng_feed['N']?> /> 사용안함</label>
                        <span class="tbl_sment">활성화 하는 경우 manage 메인 화면 최근 피드에서 새로운 소식 알림</span>
                    </td>
                </tr>
                <tr>
                    <th>상단 출력 내용</th>
                    <td>
                        <textarea id="top_source" name="top_source" title="상단 출력 내용"><?=$write['top_source']?></textarea>
                        <script type="text/javascript">CKEDITOR.replace('top_source',{'height':150});</script>
                    </td>
                </tr>
                <tr>
                    <th>하단 출력 내용</th>
                    <td>
                        <textarea id="bottom_source" name="bottom_source" title="하단 출력 내용"><?=$write['bottom_source']?></textarea>
                        <script type="text/javascript">CKEDITOR.replace('bottom_source',{'height':150});</script>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <a href="./?mod=<?=MOD_BOARD?>&href=lists<?=$manage->lnk_def_param()?>" class="btn1"><i class="fa fa-bars"></i>리스트</a>
                <button type="submit" class="btn1"><i class="fa fa-check"></i>저장</button>
            </div>
        </div>

        <?=$print_target[1]?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="2" class="tal">권한 설정</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>게시판 관리 권한</th>
                    <td>
                        <select name="ctr_level" class="inp">
                            <?php for($i=1;$i<=10;$i++){ ?>
                            <option value="<?=$i?>" <?php if($i==$write['ctr_level']){ echo "selected"; }?>><?=$i?> (<?=$MB['type'][$i]?>)</option>
                            <?php } ?>
                        </select>
                        이상
                        <span class="tbl_sment">관리 권한이 적용되는 경우 회원에게 모든 권한이 부여됨</span>
                    </td>
                </tr>
                <tr>
                    <th>리스트 접근 권한</th>
                    <td>
                        <select name="list_level" class="inp">
                            <?php for($i=1;$i<=10;$i++){ ?>
                            <option value="<?=$i?>" <?php if($i==$write['list_level']){ echo "selected"; }?>><?=$i?> (<?=$MB['type'][$i]?>)</option>
                            <?php } ?>
                        </select>
                        이상
                    </td>
                </tr>
                <tr>
                    <th>조회 권한</th>
                    <td>
                        <select name="read_level" class="inp">
                            <?php for($i=1;$i<=10;$i++){ ?>
                            <option value="<?=$i?>" <?php if($i==$write['read_level']){ echo "selected"; }?>><?=$i?> (<?=$MB['type'][$i]?>)</option>
                            <?php } ?>
                        </select>
                        이상
                    </td>
                </tr>
                <tr>
                    <th>글작성 권한</th>
                    <td>
                        <select name="write_level" class="inp">
                            <?php for($i=1;$i<=10;$i++){ ?>
                            <option value="<?=$i?>" <?php if($i==$write['write_level']){ echo "selected"; }?>><?=$i?> (<?=$MB['type'][$i]?>)</option>
                            <?php } ?>
                        </select>
                        이상
                    </td>
                </tr>
                <tr>
                    <th>비밀글 작성 권한</th>
                    <td>
                        <select name="secret_level" class="inp">
                            <?php for($i=1;$i<=10;$i++){ ?>
                            <option value="<?=$i?>" <?php if($i==$write['secret_level']){ echo "selected"; }?>><?=$i?> (<?=$MB['type'][$i]?>)</option>
                            <?php } ?>
                        </select>
                        이상
                    </td>
                </tr>
                <tr>
                    <th>코멘트 작성 권한</th>
                    <td>
                        <select name="comment_level" class="inp">
                            <?php for($i=1;$i<=10;$i++){ ?>
                            <option value="<?=$i?>" <?php if($i==$write['comment_level']){ echo "selected"; }?>><?=$i?> (<?=$MB['type'][$i]?>)</option>
                            <?php } ?>
                        </select>
                        이상
                    </td>
                </tr>
                <tr>
                    <th>답글 작성 권한</th>
                    <td>
                        <select name="reply_level" class="inp">
                            <?php for($i=1;$i<=10;$i++){ ?>
                            <option value="<?=$i?>" <?php if($i==$write['reply_level']){ echo "selected"; }?>><?=$i?> (<?=$MB['type'][$i]?>)</option>
                            <?php } ?>
                        </select>
                        이상
                    </td>
                </tr>
                <tr>
                    <th>글삭제 권한</th>
                    <td>
                        <select name="delete_level" class="inp">
                            <?php for($i=1;$i<=10;$i++){ ?>
                            <option value="<?=$i?>" <?php if($i==$write['delete_level']){ echo "selected"; }?>><?=$i?> (<?=$MB['type'][$i]?>)</option>
                            <?php } ?>
                        </select>
                        이상
                        <span class="tbl_sment">작성 권한보다 삭제 권한의 숫자가 낮으면 회원이 자신의 글을 삭제할 수 없음</span>
                    </td>
                </tr>
                <tr>
                    <th>조회 point</th>
                    <td>
                        <input type="text" name="read_point" class="inp" title="조회 point" value="<?=$write['read_point']?>" /> point
                        <span class="tbl_sment">글 조회시 설정한 point 만큼 조정<br />음수(-)를 입력하는 경우 포인트가 차감. point가 부족한 경우 조회 불가</span>
                    </td>
                </tr>
                <tr>
                    <th>글작성 point</th>
                    <td>
                        <input type="text" name="write_point" class="inp" title="글작성 point" value="<?=$write['write_point']?>" /> point
                        <span class="tbl_sment">글 작성시 설정한 point 만큼 조정<br />음수(-)를 입력하는 경우 포인트가 차감. point가 부족한 경우 작성 불가</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <a href="./?mod=<?=MOD_BOARD?>&href=lists<?=$manage->lnk_def_param()?>" class="btn1"><i class="fa fa-bars"></i>리스트</a>
                <button type="submit" class="btn1"><i class="fa fa-check"></i>저장</button>
            </div>
        </div>

        <?=$print_target[2]?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="2" class="tal">아이콘 출력 설정</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>첨부파일 아이콘</th>
                    <td>
                        <label class="mr10"><input type="radio" name="ico_file" value="Y" <?=$ico_file['Y']?> /> 사용</label>
                        <label><input type="radio" name="ico_file" value="N" <?=$ico_file['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>비밀글 아이콘</th>
                    <td>
                        <label class="mr10"><input type="radio" name="ico_secret" value="Y" checked <?=$ico_secret['Y']?> /> 사용</label>
                        <label><input type="radio" name="ico_secret" value="N" <?=$ico_secret['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>NEW 아이콘</th>
                    <td>
                        <label class="mr10"><input type="radio" name="ico_new" value="Y" <?=$ico_new['Y']?> /> 사용</label>
                        <label><input type="radio" name="ico_new" value="N" <?=$ico_new['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>NEW 아이콘 설정</th>
                    <td>
                        <input type="text" name="ico_new_case" class="inp" title="NEW 아이콘 설정" value="<?=$write['ico_new_case']?>" /> 초
                        <span class="tbl_sment">설정한 시간이 지나면 NEW 아이콘 노출하지 않음</span>
                    </td>
                </tr>
                <tr>
                    <th>HOT 아이콘</th>
                    <td>
                        <label class="mr10"><input type="radio" name="ico_hot" value="Y" <?=$ico_hot['Y']?> /> 사용</label>
                        <label><input type="radio" name="ico_hot" value="N" <?=$ico_hot['N']?> /> 사용안함</label>
                    </td>
                </tr>
                <tr>
                    <th>HOT 아이콘 설정</th>
                    <td>
                        <table class="table1 mb10">
                            <tbody>
                                <tr>
                                    <th>좋아요 수</th>
                                    <td>
                                        <input type="text" name="ico_hot_case_1" class="inp" title="HOT 아이콘 설정(좋아요 수)" value="<?=$write['ico_hot_case_1']?>" /> 이상
                                    </td>
                                </tr>
                                <tr>
                                    <th>글조회 수</th>
                                    <td>
                                        <input type="text" name="ico_hot_case_2" class="inp" title="HOT 아이콘 설정(글조회 수)" value="<?=$write['ico_hot_case_2']?>" /> 이상
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <label><input type="radio" name="ico_hot_case_3" value="AND" <?=$ico_hot_case_3['AND']?> /> 좋아요 수와 글조회 수 모두 충족하는 경우</label><br />
                        <label><input type="radio" name="ico_hot_case_3" value="OR" <?=$ico_hot_case_3['OR']?> /> 좋아요 수와 글조회 수 중 하나 이상 충족하는 경우</label>
                    </td>
                </tr>

            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <a href="./?mod=<?=MOD_BOARD?>&href=lists<?=$manage->lnk_def_param()?>" class="btn1"><i class="fa fa-bars"></i>리스트</a>
                <button type="submit" class="btn1"><i class="fa fa-check"></i>저장</button>
            </div>
        </div>

        <?=$print_target[3]?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="3" class="tal">여분필드 (conf_1 ~ conf_10)</th>
                </tr>
                <tr>
                    <th class="tal">필드명</th>
                    <th class="tal">필드 설명</th>
                    <th class="tal">저장 값</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i=1;$i<=10;$i++){ ?>
                <tr>
                    <th>conf_<?=$i?></th>
                    <td>
                        <input type="text" name="conf_exp[]" class="inp w100p" value="<?=$write['conf_'.$i.'_exp']?>" />
                    </td>
                    <td>
                        <input type="text" name="conf_<?=$i?>" class="inp w100p" value="<?=$write['conf_'.$i]?>" />
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <a href="#" class="btn2 delBtn mr30"><i class="fa fa-trash-alt"></i>게시판 삭제</a>
                <a href="./?mod=<?=MOD_BOARD?>&href=lists<?=$manage->lnk_def_param()?>" class="btn1"><i class="fa fa-bars"></i>리스트</a>
                <button type="submit" class="btn1"><i class="fa fa-check"></i>저장</button>
            </div>
        </div>
    </form>

</article>
