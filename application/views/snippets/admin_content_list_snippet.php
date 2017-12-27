<style type="text/css">
.data_table {
	margin: 0px auto;
	width: 95%;
	text-align: center;
	border: 1px solid #C6C6C6;
	color: #232323;
}

.data_col_bar {
	background: #73B563;
	color: #ffffff;
	font-size: 14px;
}

.data_row {
	font-size: 13px;
}

.data_table td,th {
	padding: 5px;
}

.data_row:nth-child(even) {
	background-color:#EDEDED;
}
.no-data {
	text-align: center;
	color: #646464;
}	
</style>
<div class='form-horizontal'>
	<?if(count($list) > 0):?>
		<table class="data_table">
			<tr class="data_col_bar">
				<th>Created</th>
				<th>Modified</th>
				<th>Status</th>
				<th>Posting</th>
				<th>Media</th>
				<th>Media Title</th>
				<th>Last Edited</th>
				<th></th>
			</tr>
			<?foreach($list as $l):?>
				<tr class="data_row">
					<td><?=$l['date_posted']?></td>
					<td><?=$l['last_modified']?></td>
					<td>
						<?if($l['is_live'] && $l['is_approved']) {
							echo "<span class='label label-success'><i class='icon-ok'></i> verified</span>";
						} else if($l['is_live']) {
							echo "<span class='label label-warning'><i class='icon-eye-open'></i> published</span>";
						} else {
							echo "<span class='label'><i class='icon-eye-close'></i> draft</span>";
						}
						?>
					</td>
					<td><?=$l['label']?></td>
					<td><?=$l['media_type']?></td>
					<td><?=$l['media_name']?></td>
					<td><?=$l['first_name']?></td>
					<td class="btn-holder">
						<a href="<?=$l['edit_link_prefix'].$l['pk']?>" class="btn btn-small" data-pk="<?=$l['pk']?>">edit</a>
					</td>
				</tr>
			<?endforeach;?>
		</table>
	<?else:?>
		<p class='no-data'>
			No data available
		</p>
	<?endif;?>
</div>