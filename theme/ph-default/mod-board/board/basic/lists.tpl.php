<!-- top source code -->
<?php if($is_tf_source_show){ ?>
<div id="mod_board_top_source"><?php echo $top_source; ?></div>
<?php } ?>

<form <?php echo $this->form(); ?>>
	<input type="hidden" name="category" value="<?php echo $category; ?>" />
	<input type="hidden" name="page" value="<?php echo $page; ?>" />
	<input type="hidden" name="where" value="<?php echo $where; ?>" />
	<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" />
	<input type="hidden" name="board_id" value="<?php echo $board_id; ?>" />
	<input type="hidden" name="thisuri" value="<?php echo $thisuri; ?>" />

	<!-- total count -->
	<p id="boatd-total">전체 <strong><?php echo $total_cnt; ?></strong>개의 게시글</p>

	<!-- category -->
	<?php if($is_category_show){ ?>
	<div id="board-cat">
		<?php echo $print_category; ?>
	</div>
	<?php } ?>

	<!-- list -->
	<div id="board-list">
		<table>
			<colgroup>
				<?php if($is_ctr_show){ ?>
				<col style="width: 50px;" />
				<?php } ?>
				<col style="width: 60px;" />
				<col style="width: auto;" />
				<col style="width: 120px;" />
				<col style="width: 60px;" />
				<col style="width: 50px;" />
				<?php if($is_likes_show){ ?>
				<col style="width: 70px;" />
				<?php } ?>
			</colgroup>
			<thead>
				<tr>
                    <?php if($is_ctr_show){ ?>
                    <th><input type="checkbox" class="cnum_allchk" /></th>
                    <?php } ?>
                    <th>No.</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>날짜</th>
                    <th>조회</th>
                    <?php if($is_likes_show){ ?>
                    <th class="like">좋아요</td>
                    <?php } ?>
				</tr>
			</thead>

			<!-- 공지글 -->
			<tbody class="notice">
				<?php foreach($print_notice as $list){ ?>
				<tr>
					<?php if($is_ctr_show){ ?>
					<td class="chk"><input type="checkbox" name="cnum[]" value="<?php echo $list['idx']; ?>" /></td>
					<?php } ?>
					<td class="no"><strong>공지</strong></td>
					<td class="sbj">

						<a href="<?php echo $list[0]['get_link']; ?>">
							<?php echo $list[0]['secret_ico']; ?>
							<?php echo $list[0]['subject']; ?>
						</a>
						<?php echo $list[0]['file_ico']; ?>
						<?php echo $list[0]['new_ico']; ?>
						<?php echo $list[0]['hot_ico']; ?>

						<?php if($is_comment_show){ ?>
						<span class="cmt"><?php echo $list[0]['comment_cnt']; ?></span>
						<?php } ?>

					</td>
					<td><?php echo $list[0]['writer']; ?></td>
					<td class="no" title="<?php echo $list['datetime']; ?>"><?php echo $list['date']; ?></td>
					<td class="no"><?php echo $list['view']; ?></td>
					<?php if($is_likes_show){ ?>
	        <td class="no like"><strong><?php echo $list['likes_cnt']; ?></strong>/<?php echo $list['unlikes_cnt']; ?></td>
	        <?php } ?>
				</tr>
				<?php } ?>
			</tbody>

			<!-- 일반글 -->
			<tbody>
				<?php foreach($print_arr as $list){ ?>
				<tr>
	        <?php if($is_ctr_show){ ?>
					<td><input type="checkbox" name="cnum[]" value="<?php echo $list['idx']; ?>" /></td>
					<?php } ?>
					<td class="no"><?php echo $list[0]['number']; ?></td>
					<td class="sbj">

						<a href="<?php echo $list[0]['get_link']; ?>">
							<?php if($is_category_show){ ?>
							<strong class="cat"><?php echo $list['category']; ?></strong>
							<?php } ?>
							<?php echo $list[0]['secret_ico']; ?>
							<?php echo $list[0]['subject']; ?>
						</a>
						<?php echo $list[0]['file_ico']; ?>
						<?php echo $list[0]['new_ico']; ?>
						<?php echo $list[0]['hot_ico']; ?>

						<?php if($is_comment_show){ ?>
						<span class="cmt"><?php echo $list[0]['comment_cnt']; ?></span>
						<?php } ?>

					</td>
					<td><?php echo $list[0]['writer']; ?></td>
					<td class="no" title="<?php echo $list['datetime']; ?>"><?php echo $list['date']; ?></td>
					<td class="no"><?php echo $list['view']; ?></td>
					<?php if($is_likes_show){ ?>
	        <td class="no like"><strong><?php echo $list['likes_cnt']; ?></strong>/<?php echo $list['unlikes_cnt']; ?></td>
	        <?php } ?>
				</tr>
	      <?php } ?>
			</tbody>
		</table>

		<!-- no data -->
		<?php if(!$print_arr){ ?>
		<p id="board-nodata"><?php echo SET_NODATA_MSG; ?></p>
		<?php } ?>
	</div>

	<!-- paging -->
	<div id="board-paging">
		<?php echo $pagingprint; ?>
	</div>

	<!-- button -->
	<div class="btn-wrap">
		<div class="left">
			<?php echo $ctr_btn; ?>
		</div>
		<div class="right">
			<?php echo $write_btn; ?>
		</div>
	</div>
</form>

<!-- search -->
<form <?php echo $this->sch_form(); ?>>
	<input type="hidden" name="category" value="<?php echo $category; ?>" />

	<select name="where" class="where">
		<option value="subject" <?php echo $where_slted['subject']; ?>>제목</option>
		<option value="article" <?php echo $where_slted['article']; ?>>내용</option>
		<option value="writer" <?php echo $where_slted['writer']; ?>>작성자</option>
		<option value="mb_id" <?php echo $where_slted['mb_id']; ?>>회원 아이디</option>
	</select>
	<input type="text" name="keyword" class="keyword" value="<?php echo $keyword; ?>" placeholder="검색어를 입력하세요." />
	<button type="submit" class="btn1 small"><i class="fa fa-search"></i> 검색</button>
	<a href="<?php echo $cancel_link; ?>" class="btn2 small">초기화</a>
</form>

<!-- bottom source code -->
<?php if($is_tf_source_show){ ?>
<div id="mod_board_bottom_source"><?php echo $bottom_source; ?></div>
<?php } ?>
