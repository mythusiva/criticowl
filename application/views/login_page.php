<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->config->item('window_title')?> - Admin Log In</title>
    <?include "header_essentials.php"?>
</head>
<style type="text/css">
.left_info_pane {
	float: left;
	width: 45%;
	font-size: 16px;
}
.right_info_pane {
	float: right;
	width: 45%;
	font-size: 16px;
}

.button_set {
	padding: 10px;
	text-align: center;
}

.input_section {
        clear: both;
        padding: 15px 0px;
}
.sub_input_section {
        clear: both;
        padding: 15px 0px;
}

</style>
<body>
	<?$config['access_level'] = 'USER';?>
	<?$this->load->view('snippets/top_bar',array('config'=>$config,'selected'=>'home'))?>
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
					<p>
						Admins, welcome to the backend!
					</p>
					<p>
						Please use the login credentials given to you when joining our team.
						You will be allowed only 3 attempts to log in. Once a completed failure has been reached your IP will be
						banned from our website completely. 
					</p>
				</div>
				<div class="right_info_pane">
					<div class="panel panel_spacing">
						<div class="titlebar">
							Members
						</div>
						<div class="panel-content">
							<form id="login_box" method="POST" action='/login/user_login' class="form-horizontal" >
								<table>
									<tr class="rows">
										<td class="columns">
											Email address:
										</td>
										<td class="columns">
											<input id="email_field" name="email" type="text" class="input-large" value="" />
										</td>
									</tr>
									<tr class="rows">
										<td class="columns">
											Password:
										</td>
										<td>
											<input id="password_field" name="pass" type="password" class="input-large" value="" />
										</td>
									</tr>
								</table>
								<div class=" panel_button_area">
									<hr />
									<button id="login_btn" class="btn btn-success">Sign in</button>
								</div>
							</div>
						</form>
					</div>
					<div style="clear: both;"></div>
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
        
                $('#login_btn').click(function() {
                        $('#login_box').submit();
                });
                $('#password_field').keypress(function(e){
                        if(e.which == 13){
                                $('#login_btn').click();
                        }
                });
                
  });
</script>
