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

input.img_link_inpt {
	width: 100%;
	text-align: center;
	padding: 15px !important;
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
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'admin_tools_image_linker'))?>
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
							Image Linker
						</div>
						<div class='panel-content'>
							<p>
								The image linker was designed to work with JPG,JPEG and PNG image formats only. The image url should end with one of the mentioned extensions. For example, here is an acceptable image link because it ends in '.jpg': http://www.example.com/image.jpg
							</p>
							<p>
								<input id="image_link_field_input" placeholder="http://www.example.com/image.jpg" name="image_link_field" type="text" class="img_link_inpt" />
								<button id="image_link_generate_btn">GENERATE</button>
							</p>
							<p>
								<small>
									<span id="internal_generated_url"></span>
								</small>
							</p>
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
  	$('#image_link_generate_btn').click(function() {
  		var entered_url = $('#image_link_field_input').val();
  		get_internal_image_url(entered_url);
  	});
  });

  function get_internal_image_url(external_image_url) {
		//get request
		$.ajax({
			type: "GET",
			url: '/api/get_internal_image_url',
			data: {'image_url':external_image_url},
			success: function(data) {
  				$('#internal_generated_url').html("<strong>Permanent Internal Link: </strong> "+data);
			}
		});
	}

</script>
