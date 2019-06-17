<div class="tblform">

	<h4><?php echo $MB['name']; ?>님의 포인트 내역</h4>

	<div class="mypoint">
		회원님은 현재 <strong><?php echo $total_point; ?></strong> 포인트 보유 중입니다.
	</div>

	<table class="table listtbl">
		<colgroup>
			<col style="width: 70px;" />
			<col style="width: 70px;" />
			<col style="width: 70px;" />
			<col style="width: auto;" />
			<col style="width: 200px;" />
		</colgroup>
		<thead>
			<tr>
				<th>No.</th>
				<th>적립</th>
				<th>차감</th>
				<th class="tal">포인트 변동 사유</th>
				<th>변동일</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach($print_arr as $list){ ?>
			<tr>
				<td class="no"><?php echo $list['no']; ?></td>
				<td><?php echo $list['p_in']; ?></td>
				<td><?php echo $list['p_out']; ?></td>
				<td class="tal"><?php echo $list['memo']; ?></td>
				<td><?php echo $list['regdate']; ?></td>
			</tr>
			<?php } ?>

			<?php if(!$print_arr){ ?>
			<tr>
				<td colspan="5"><?php echo SET_NODATA_MSG; ?></td>
			</tr>
			<?php } ?>

		</tbody>
	</table>

	<?php echo $pagingprint; ?>

</div>
