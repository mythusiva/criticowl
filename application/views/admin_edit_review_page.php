<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->config->item('window_title')?> - Admin Edit Review</title>
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
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'admin_add_review'))?>
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
							Edit Review
						</div>
						<div class='panel-content form-horizontal'>
							<form id="form" method="POST" action='/admin/save_review'>
								<table>
									<tr>
										<td class='left_label <?=isset($validation['media_item_field'])?'fail_validation':'';?>'>
											<strong>Media Item</strong><br />
											<small>Make sure you find your item in the drop down, click on it to verify the selection.</small>
										</td>
										<td class='right_input'>
											<input placeholder="Search for the media title or use Unspecified" type="text" class="span3" id="search" data-provide="typeahead" data-items="5"
														 value="<?=get_key_from_value(json_decode($autocomplete_lookup,TRUE),get_last_value($user_data,'media_item',''))?>" />
											<input id="item_field" name="media_item_field" type="hidden" value="<?=get_last_value($user_data,'media_item','');?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['review_title_field'])?'fail_validation':'';?>'>
											<strong>Review Title</strong><br />
											<small>The title of the review which will be used on the preview tile and tweets.</small>
										</td>
										<td class='right_input'>
											<input placeholder="Some sort of title here ..." name="review_title_field" type="text"  
														  value="<?=get_last_value($user_data,'review_title','');?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['review_preview_text_field'])?'fail_validation':'';?>'>
											<strong>Preview Text</strong><br />
											<small>The teaser text that will appear on the preview tiles.</small>
										</td>
										<td class='right_input'>
											<textarea placeholder="Some interesting text goes here ..." name="review_preview_text_field"><?=get_last_value($user_data,'review_preview_text','');?></textarea> 
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['image_link_field'])?'fail_validation':'';?>'>
											<strong>Preview Image Link</strong><br />
											<small>This image will be used in the preview tile.</small>
										</td>
										<td class='right_input'>
											<input placeholder="http://www.somesite.com/image.jpg" name="image_link_field" type="text"  
														  value="<?=get_last_value($user_data,'review_img_link','');?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label <?=isset($validation['rating_amnt_field'])?'fail_validation':'';?>'>
											<strong>Rating</strong><br />
											<small>
												Use a decimal so that the system will be able to convert that to what ever fraction we choose to use.
												Eg. 0.80 will become 4/5. NOTE: if this not a review of the entire media, set this to 0!
											</small>
										</td>
										<td class='right_input'>
											<input placeholder="0.80 (or 0 if this not a review of entire media)" name="rating_amnt_field" type="text"  
														  value="<?=get_last_value($user_data,'rating_amnt','');?>" />
										</td>
									</tr>
									<tr>
										<td class='left_label'>
											<strong>Sources</strong><br/>
											<small>Make sure you enter comma-separated urls. You may not have any sources at which point leave this empty.</small>
										</td>
										<td class='right_input'>
											<textarea placeholder="www.example.com/something/whatever,www.example2.ca/whatever/bowwow" name="sources_field"><?=get_last_value($user_data,'sources','');?></textarea>
										</td>
									</tr>
									<tr>
										<td class='left_label'>
											<strong>Tags</strong><br/>
											<small>Make sure you enter comma-separated values.</small>
										</td>
										<td class='right_input'>
											<textarea placeholder="romantic,bring your partner,thriller,post-credits scene" name="tags_field"><?=get_last_value($user_data,'tags','');?></textarea>
										</td>
									</tr>
								</table>
								<div>
									<textarea class="ckeditor"  name="review_content_field"><?=get_last_value($user_data,'review_content','');?></textarea>
								</div>
								<br />
								<div class='blurb'>
									<p>
										By default this post will be saved as a draft and unavailable to the public to see. To immediately post this
										for the public to see please check the box below. You can also add this post as a draft right now and later enable
										it for public viewing using the edit functionality.
									</p>
									<?$is_live = get_last_value($user_data,"is_live","0");?>
									<label class='checkbox'>
										<input name="is_live"
													 type="checkbox"
													 value='1' <?=($is_live === '1')?'checked=checked':''?> />
											<i class="icon-eye-open"></i>
											<strong>Publish for the public to see</strong>
									</label>
								</div>
								<br />
								<?if(in_array(get_permissions(),array(PERMISSION_ADMIN,PERMISSION_EDITOR))):?>
								<div class='blurb'>
									<?$is_approved = get_last_value($user_data,"is_approved","0");?>
									<label class='checkbox'>
										<input name="is_approved"
													 type="checkbox"
													 value='1' <?=($is_approved === '1')?'checked=checked':''?> />
											<i class="icon-ok"></i>
											<strong>As an editor I certify that this work meets the professional standards of the CriticOwl brand.</strong>
									</label>
								</div>
								<?endif;?>
								<input name='review_fk' type='hidden' value='<?=$article_fk?>' />
							</form>	
						</div>
						<div class='panel-footer'>
							<a onclick="submit_form()" class="btn btn-success">Save</a>
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
