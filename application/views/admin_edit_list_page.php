<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->config->item('window_title')?> - Admin <?=$list_title?></title>
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

</style>
<body>
	<?
		$config['access_level'] = 'ADMIN';
		if($list_type === POST_NEWS) {
			$selected_menu = 'admin_add_news';
		} else if($list_type === POST_ARTICLE) {
			$selected_menu = 'admin_add_article';
		} else if($list_type === POST_REVIEW) {
			$selected_menu = 'admin_add_review';
		}
	?>
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>$selected_menu))?>
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
							<?=$list_title?>
						</div>
						<div class='panel-content'>
							<?=$this->load->view('snippets/admin_content_list_snippet',array('list'=>$list),TRUE);?>
						</div>
						<div class='panel-footer'>
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
  });
</script>
