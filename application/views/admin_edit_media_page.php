<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->config->item('window_title')?> - Admin Edit Media</title>
    <?include "header_essentials.php"?>
</head>
<style type="text/css">
.left_info_pane {
	float: left;
	width: 100%;
	font-size: 16px;
}
.right_info_pane {
	float: right;
	width: 0%;
	font-size: 16px;
}

.button_set {
	padding: 10px;
	text-align: center;
}

table {
	width: 100%;
}

td {
	padding: 15px;
}

.left_label {
	width: 30%;
}

.right_input {
	width: 60%;
	text-align: center;
}

.right_input input {
	width: 80%;
	text-align: center;
}

.right_input textarea {
	width: 80%;
	height: 180px;
}

.panel-footer {
	padding-bottom: 15px;
}

.panel-content {
	overflow-x: auto;
}

.fail_validation {
	color: red;
}
.blurb {
	margin: 0px auto;
	width: 50%;
}

</style>
<body>
	<?$config['access_level'] = 'ADMIN';?>
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'admin_edit_media'))?>
  <div class="main_body">
		<div class="main_content">
			<div id="notification_block">
				<?if(!empty($notification)):?>
				<div id="notifications" class="notification_area alert alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<?=$notification?>
				</div>
				<?endif;?>
			</div>
			<div class="content_wrapper">
				<div class="left_info_pane">
					<div class='panel panel_spacing'>
						<div class='titlebar'>
							Edit Media
						</div>
						<div class='panel-content form-horizontal'>
							<form id="form" method="POST" action='/admin/save_edited_media'>
								<table>
									<tr>
										<td class='left_label <?=isset($validation['media_title_field'])?'fail_validation':'';?>'>
											<strong>Search: </strong><br />
											<small>eg. The Dark Knight</small>
										</td>
										<td class='right_input'>
											<input type="text" id="search" data-provide="typeahead" data-items="5"
														  value="<?=get_key_from_value(json_decode($autocomplete_lookup,TRUE),get_last_value($user_data,'media_item',''))?>" />
											<input id="item_field" name="media_item_field" type="hidden" value="<?=get_last_value($user_data,'media_item','');?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['media_title_field'])?'fail_validation':'';?>'>
											<strong>Offical Title</strong><br />
											<small>eg. The Dark Knight</small>
										</td>
										<td class='right_input'>
											<input id="media_title_inpt" name="media_title_field" type="text"
														  value="<?=get_last_value($user_data,'media_title','');?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['media_release_date_field'])?'fail_validation':'';?>'>
											<strong>Offical Release Date</strong><br />
											<small>In the event that the release date is unofficial, <a href='#' onclick='set_unspecified_release_date()'>click here</a>.</small>
										</td>
										<td class='right_input'>
											<input id='media_release_date_inpt' name="media_release_date_field" type="text" class='datepicker' style="width: 30%;" 
														  value="<?=get_last_value($user_data,'media_release_date',date('Y-m-d',time()));?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['dvd_release_date_field'])?'fail_validation':'';?>'>
											<strong>DVD Release Date</strong><br />
											<small>
												If the date is unknown at this time, please <a href="#" onclick="$('#dvd_release_date_inpt').val('')">leave this blank.</a>
											</small>
										</td>
										<td class='right_input'>
											<input id="dvd_release_date_inpt" name="dvd_release_date_field" type="text" class='datepicker' style="width: 30%;" 
														  value="<?=get_last_value($user_data,'dvd_release_date',date('Y-m-d',time()));?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['bluray_release_date_field'])?'fail_validation':'';?>'>
											<strong>Blu-ray Release Date</strong><br />
											<small>
												If the date is unknown at this time, please <a href="#" onclick="$('#bluray_release_date_inpt').val('')">leave this blank.</a>
											</small>
										</td>
										<td class='right_input'>
											<input id="bluray_release_date_inpt" name="bluray_release_date_field" type="text" class='datepicker' style="width: 30%;" 
														  value="<?=get_last_value($user_data,'bluray_release_date',date('Y-m-d',time()));?>" />
										</td>
									</tr>
								</table>
								<br />
								<div class='blurb'>
									<?$is_enabled = get_last_value($user_data,"is_enabled","1");?>
									<label class='checkbox'>
										<input id="is_movie_enabled" name="is_enabled"
													 type="checkbox"
													 value='1' <?=($is_enabled === '1')?'checked=checked':''?> />
											<i class="icon-ok"></i>
											<strong>Enable this movie, make it visible so it shows up in the movies list.</strong>
									</label>
								</div>
							</form>	
						</div>
						<div class='panel-footer'>
							<a onclick="$('#form').submit()" class="btn btn-success">Save Changes</a>
							<a href="/admin" class="btn">Cancel</a>
						</div>
					</div>
				</div>
				<div class="right_info_pane">
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
	<?include "footer.php"?>
</body>

</html>

<script>
  $(document).ready(function(){
		$(".datepicker").datepicker({
						format:'yyyy-mm-dd',
						startDate: '+1d',
		});
		list = <?=$autocomplete_list?>;
		lookup = <?=$autocomplete_lookup?>;
		$('#search').typeahead({
			source: list,
			updater: function(value) {
				update_form(value);
				return value;
			},
		});
  });
	function update_form(val) {
		var id = lookup[val];
		update_media_details(id);
		$('#item_field').val(id);
	}
	
	function update_media_details(media_fk) {
		//get request
		$.ajax({
			type: "GET",
			url: '/admin/get_media_information',
			data: {'media_fk':media_fk},
			dataType: 'json',
			success: function(data) {
				if (data != 0) {
					$('#media_title_inpt').val(data.name);
					$('#media_release_date_inpt').val(data.release_date);
					$('#dvd_release_date_inpt').val(data.dvd_release_date);
					$('#bluray_release_date_inpt').val(data.bluray_release_date);
					if(data.is_enabled == 1) {
						$('#is_movie_enabled').attr('checked',true);
					} else {
						$('#is_movie_enabled').attr('checked',false);
					}
				} else { 
					alert('An error occured, please try again. Make sure you click the searched media from the results list.');
				}
			}
		});
	}
	function set_unspecified_release_date() {
		$('#media_release_date_inpt').attr('readonly','');
		$('#media_release_date_inpt').val('0000-00-00');
	}
	
</script>
