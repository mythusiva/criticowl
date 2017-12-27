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
				<th>Media Title</th>
				<th>Amazon DVD Link</th>
				<th>Amazon Blu-ray Link</th>
				<th></th>
			</tr>
			<?foreach($list as $key => $l):?>
				<tr id="media_row_<?=$key?>" class="data_row">
					<td><?=$l['name']?></td>
					<td><textarea id="txtarea_amazon_dvd_link_<?=$key?>"><?=$l['amazon_dvd_link']?></textarea></td>
					<td><textarea id="txtarea_amazon_bluray_link_<?=$key?>"><?=$l['amazon_bluray_link']?></textarea></td>
					<td class="btn-holder">
						<a href="#" data-row-id="<?=$key?>" data-media-fk="<?=$l['media_pk']?>" class="btn update_btn">update</a>
					</td>
				</tr>
			<?endforeach;?>
		</table>
	<?else:?>
		<p class='no-data'>
			Everything is up-to-date for now ...
		</p>
	<?endif;?>
</div>
<script>
	$(document).ready(function() {
		$('.update_btn').click(function() {
			var row_id = $(this).attr('data-row-id');
			var media_fk = $(this).attr('data-media-fk');
			var dvd_link = $('#txtarea_amazon_dvd_link_'+row_id).val();
			var bluray_link = $('#txtarea_amazon_bluray_link_'+row_id).val();
			update_amazon_affiliates_link(media_fk,dvd_link,bluray_link);
			$('#media_row_'+row_id).fadeOut();
		});
	});

	function update_amazon_affiliates_link(media_fk,dvd_link,bluray_link) {
		//get request
		$.post(
			'/admin/update_amazon_affiliates_links',
			{'media_fk':media_fk,'amazon_dvd_link':dvd_link,'amazon_bluray_link':bluray_link}
		);
	}
</script>