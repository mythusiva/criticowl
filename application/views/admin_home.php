<style type="text/css">
.left_pane_admin {
	float: left;
	width: 15%;
	font-size: 16px;
}
.right_pane_admin {
	float: right;
	width: 80%;
	font-size: 16px;
}

.button_set {
	padding: 10px;
	text-align: center;
}

</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->config->item('window_title')?> - Admin Home</title>
    <?include "header_essentials.php"?>
</head>
<body>
	<?$config['access_level'] = 'ADMIN';?>
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'admin_home'))?>
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
				<div class="left_pane_admin">
					<div class='panel panel_spacing'>
						<div class='titlebar'>
							Quick Links
						</div>
						<div class='panel-content form-horizontal'>
							<div>
								Articles
							</div>
							<div>
								<a href="/admin/add_article">New Article</a><br />
								<a href="/admin/edit_article">Edit Article</a>
							</div>
							<div>
								Reviews
							</div>
							<div>
								<a href="/admin/add_review">New Review</a><br />
								<a href="/admin/edit_review">Edit Review</a>
							</div>
							<!-- <div>
								Affiliates
							</div>
							<div>
								<a href="/admin/affiliates_amazon">Amazon</a>
							</div> -->
						</div>
						<div class='panel-footer'>
						</div>
					</div>
				</div>
				<div class="right_pane_admin">
					<div class='panel panel_spacing'>
						<div class='titlebar'>
							Recent Activity
						</div>
						<div class='panel-content'>
							<p>
								Shows the last 25 edited/updated items, ordered by last modified date.
							</p>
							<?=$this->load->view('snippets/admin_content_list_snippet',array('list'=>$list),TRUE);?>
						</div>
						<div class='panel-footer'>
						</div>
					</div>
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
