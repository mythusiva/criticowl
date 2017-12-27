<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->config->item('window_title')?> - Admin Add Movie</title>
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
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'admin_add_media'))?>
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
							New Movie
						</div>
						<div class='panel-content form-horizontal'>
							<form id="form" method="POST" action='/admin/save_movie'>
								<table>
									<tr>
										<td class='left_label <?=isset($validation['media_title_field'])?'fail_validation':'';?>'>
											<strong>Offical Title</strong><br />
											<small>The title that will be used in association with reviews, articles, etc.</small>
										</td>
										<td class='right_input'>
											<input name="media_title_field" type="text" placeholder="The Dark Knight"
														  value="<?=get_last_value($user_data,'media_title','');?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['media_release_date_field'])?'fail_validation':'';?>'>
											<strong>Offical Release Date</strong><br />
											<small>In the event that the release date is unofficial, <a href='#' onclick='set_unspecified_release_date()'>click here</a>.</small>
										</td>
										<td class='right_input'>
											<input id="release_date" name="media_release_date_field" type="text" class='datepicker' style="width: 30%;" 
														  value="<?=get_last_value($user_data,'media_release_date',date('Y-m-d',time()));?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['dvd_release_date_field'])?'fail_validation':'';?>'>
											<strong>DVD Release Date</strong><br />
											<small>
												If the date is unknown at this time, please <a href="#" onclick="$('#dvd_release_date').val('')">leave this blank.</a>
											</small>
										</td>
										<td class='right_input'>
											<input id="dvd_release_date" name="dvd_release_date_field" type="text" class='datepicker' style="width: 30%;" 
														  value="<?=get_last_value($user_data,'dvd_release_date',date('Y-m-d',time()));?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['bluray_release_date_field'])?'fail_validation':'';?>'>
											<strong>Blu-ray Release Date</strong><br />
											<small>
												If the date is unknown at this time, please <a href="#" onclick="$('#bluray_release_date').val('')">leave this blank.</a>
											</small>
										</td>
										<td class='right_input'>
											<input id="bluray_release_date" name="bluray_release_date_field" type="text" class='datepicker' style="width: 30%;" 
														  value="<?=get_last_value($user_data,'bluray_release_date',date('Y-m-d',time()));?>" />
										</td>
									</tr>
								</table>
								<br />
								<div class='blurb'>
									<?$is_enabled = get_last_value($user_data,"is_enabled","1");?>
									<label class='checkbox'>
										<input name="is_enabled"
													 type="checkbox"
													 value='1' <?=($is_enabled === '1')?'checked=checked':''?> />
											<i class="icon-ok"></i>
											<strong>Enable this movie, make it visible so it shows up in the movies list.</strong>
									</label>
								</div>
							</form>	
						</div>
						<div class='panel-footer'>
							<a onclick="$('#form').submit()" class="btn btn-success">Add</a>
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
						format:'yyyy-mm-dd'
		});
	});
	function set_unspecified_release_date() {
		$('#release_date').attr('readonly','');
		$('#release_date').val('0000-00-00');
	}
</script>
