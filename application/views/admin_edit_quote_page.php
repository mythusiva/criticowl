<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->config->item('window_title')?> - Admin Edit Quote</title>
    <?include "header_essentials.php"?>
</head>
<style type="text/css">
.left_info_pane {
	float: left;
	width: 75%;
	font-size: 16px;
}
.right_info_pane {
	float: right;
	width: 20%;
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

.right_input input,select {
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
	width: 70%;
}

</style>
<body>
	<?$config['access_level'] = 'ADMIN';?>
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'admin_quote_management'))?>
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
							Edit Quote
						</div>
						<div class='panel-content form-horizontal'>
							<form id="form" method="POST" action='/admin/save_quote'>
								<table>
									<tr>
										<td class='left_label <?=isset($validation['quotation_text_field'])?'fail_validation':'';?>'>
											<strong>Quotation</strong><br />
											<small>eg. Okay, I'm reloaded!</small>
										</td>
										<td class='right_input'>
											<input name="quotation_text_field" type="text"
														  value="<?=get_last_value($user_data,'quotation_text','');?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['author_field'])?'fail_validation':'';?>'>
											<strong>Author</strong><br />
											<small>eg. Carlito's Way</small>
										</td>
										<td class='right_input'>
											<input name="author_field" type="text"
														  value="<?=get_last_value($user_data,'author','');?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['media_item_field'])?'fail_validation':'';?>'>
											<strong>Media Item</strong><br />
											<small>If it doesn't belong to an item please use 'Unspecified'</small>
										</td>
										<td class='right_input'>
											<input type="text" class="span3" id="search" data-provide="typeahead" data-items="5"
														 value="<?=get_key_from_value(json_decode($autocomplete_lookup,TRUE),get_last_value($user_data,'media_item',''))?>" />
											<input id="item_field" name="media_item_field" type="hidden" value="<?=get_last_value($user_data,'media_item','');?>" />
										</td>
									</tr>
								</table>
								<br />
								<div class='blurb'>
									<p>
										If you would like to delete this quotation, you can simply check the box below and hit save changes.
									</p>
									<?$is_deleted = get_last_value($user_data,"is_deleted","0");?>
									<label class='checkbox'><input name="is_deleted" type="checkbox" value='1' <?=($is_deleted === '1')?'checked=checked':''?> /><strong>Delete this quotation</strong></label>
								</div>
								<input name='quotation_fk' type='hidden' value='<?=$quotation_fk?>' />
							</form>	
						</div>
						<div class='panel-footer'>
							<a onclick="submit_form()" class="btn btn-success">Save Changes</a>
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
		list = <?=$autocomplete_list?>;
		lookup = <?=$autocomplete_lookup?>;
		$('#search').typeahead({
			source: list,
		});
	});
	function submit_form() {
		var id = lookup[$('#search').val()];
		$('#item_field').val(id);
		$('#form').submit();
	}
</script>
