<!-- top source code -->
<div id="mod_board_top_source"><?php echo $top_source; ?></div>

<form <?php echo $this->form(); ?>>
	<input type="hidden" name="board_id" value="<?php echo $board_id; ?>" />
	<input type="hidden" name="wrmode" value="<?php echo $wrmode; ?>" />
	<input type="hidden" name="read" value="<?php echo $read; ?>" />
	<input type="hidden" name="page" value="<?php echo $page; ?>" />
	<input type="hidden" name="where" value="<?php echo $where; ?>" />
	<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" />
	<input type="hidden" name="category_ed" value="<?php echo $category; ?>" />
	<input type="hidden" name="use_html" value="Y" />
	<input type="hidden" name="thisuri" value="<?php echo $thisuri; ?>" />
	<div id="board-write">
		<h3><?php echo $write_title; ?></h3>

		<table>
			<colgroup>
				<col style="width: 100px;" />
				<col style="width: auto;" />
			</colgroup>
			<tbody>

				<?php if($is_category_show){ ?>
				<tr>
					<th>카테고리</th>
					<td>
						<select name="category" id="category" class="inp w100">
							<?php echo $category_option; ?>
						</select>
					</td>
				</tr>
				<?php } ?>

				<tr>
					<th>
						옵션
					</th>
					<td>
						<?php echo $opt_notice; ?> <?php echo $opt_secret; ?> <?php echo $opt_return_email; ?>
					</td>
				</tr>

				<?php if($is_writer_show){ ?>
				<tr>
					<th>
						작성자
					</th>
					<td>
						<input type="text" name="writer" title="작성자" value="<?php echo $write['writer']; ?>" maxlength="8" class="inp w100" />
					</td>
				</tr>
				<?php } ?>

				<?php if($is_pwd_show){ ?>
				<tr>
					<th>
						비밀번호
					</th>
					<td>
						<input type="password" name="password" title="비밀번호" value="<?php echo $write['pwd']; ?>" maxlength="20" class="inp w100" />
					</td>
				</tr>
				<?php } ?>

				<?php if($is_email_show){ ?>
				<tr>
					<th>
						이메일주소
					</th>
					<td>
						<input type="text" name="email" title="이메일주소" value="<?php echo $write['email']; ?>" class="inp w100" />
					</td>
				</tr>
				<?php } ?>

				<tr>
					<td colspan="2" class="subject">
						<input type="text" name="subject" title="제목" class="inp wfull" value="<?php echo $write['subject']; ?>" maxlength="100" placeholder="제목을 입력하세요." />
					</td>
				</tr>

				<tr>
					<td colspan="2" class="article">
						<textarea name="article" id="article" title="내용" ckeditor><?php echo $write['article']; ?></textarea>
						<script type="text/javascript">CKEDITOR.replace('article');</script>
					</td>
				</tr>

				<?php if($is_file_show[1]){ ?>
				<tr>
					<th>
						첨부파일
					</th>
					<td>
						<input type="file" name="file1" title="첨부파일1" /><span class="bytetxt">(<?php echo $print_filesize; ?> 까지 첨부 가능)</span>
					</td>
				</tr>
				<? } ?>

				<?php if($is_filename_show[1]){ ?>
				<tr>
					<th>
						첨부된 파일
					</th>
					<td>
						<span class="uploaded"><?php echo $uploaded_file[1]; ?></span>
						<label><input type="checkbox" name="file1_del" value="checked" />삭제</label>
					</td>
				</tr>
				<? } ?>

				<?php if($is_file_show[2]){ ?>
				<tr>
					<th>
						첨부파일2
					</th>
					<td>
						<input type="file" name="file2" title="첨부파일2" /><span class="bytetxt">(<?php echo $print_filesize; ?> 까지 첨부 가능)</span>
					</td>
				</tr>
				<? } ?>

				<?php if($is_filename_show[2]){ ?>
				<tr>
					<th>
						첨부된 파일2
					</th>
					<td>
						<span class="uploaded"><?php echo $uploaded_file[2]; ?></span>
						<label><input type="checkbox" name="file2_del" value="checked">삭제</label>
					</td>
				</tr>
				<? } ?>

				<?php if($is_captcha_show){ ?>
				<tr>
					<th>스팸방지</th>
					<td>
						<?php echo $captcha; ?>
					</td>
				</tr>
				<? } ?>

			</tbody>
		</table>
	</div>

	<!-- button -->
	<div class="btn-wrap">
		<div class="left">
			<?php echo $cancel_btn; ?>
		</div>
		<div class="right">
			<button type="submit" class="btn1"><i class="fa fa-check"></i> 작성 완료</button>
		</div>
	</div>

</form>

<!-- bottom source code -->
<div id="mod_board_bottom_source"><?php echo $bottom_source; ?></div>
