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
				<th>Quote</th>
				<th>Author</th>
				<th></th>
			</tr>
			<?foreach($list as $l):?>
				<tr class="data_row">
					<td><?=$l['date_created']?></td>
					<td><?=$l['quote']?></td>
					<td><?=$l['author']?></td>
					<td class="btn-holder">
						<a href="<?='/admin/edit_quote/'.$l['quotation_pk']?>" class="btn btn-small" data-pk="<?=$l['quotation_pk']?>">edit</a>
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