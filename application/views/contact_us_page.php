<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contact Us | <?=$this->config->item('window_title')?></title>
    <meta name="description" content="Would you like to get in touch with CriticOwl ? Send us a message and we will be happy to reply.">
    <meta name="keywords" content="touch,email,message,contact">
    <meta property="og:image" content="<?=get_compressed_thumbnail_url(base_url().'img/icon_logo_red_512x512.png')?>" />
    <meta property="og:title" content="Contact Us | <?=$this->config->item('window_title')?>" />
    <meta property="og:description" content="Would you like to get in touch with CriticOwl ? Send us a message and we will be happy to reply." />
    <meta property="og:url" content="<?=base_url()?>" />
    <meta property="og:site_name" content="<?=$this->config->item('logo_text')?>"/>
    <?include "header_essentials.php"?>
</head>
<style type="text/css">
content_wrapper {
	width: 100%;
}
form {
	max-width: 500px;
	margin: 0px auto;
}
table {
	width: 100%;
}
.resize_addon_input_email {
	width: 280px;
}
.resize_addon_input_subject {
	width: 270px;
}
.fail_validation {
	color: red;
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
				<form action='/submit-contact-us' method="post">
					<table>
						<tbody>
							<tr>
								<h1>Contact Us</h1>
							</tr>
							<tr>
								<p>
									Click <a href="mailto:team@criticowl.com">here</a> to send us an email using your email client. <br/>
									You can also send us an email by completing the form below. 
								</p>
							</tr>
							<tr>
								<td>
									<div class="input-prepend input-block-level">
										<span class="add-on resize_addon_label <?=isset($validation['cu_from_address_field'])?'fail_validation':'';?>">From : </span>
										<input name="cu_from_address_field" required class="resize_addon_input_email" id="prependedInput" type="text" placeholder="your_email_here@email.com" value="<?=get_last_value($user_data,'from_field','')?>" />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="input-prepend input-block-level">
										<span class="add-on resize_addon_label <?=isset($validation['cu_subject_line_field'])?'fail_validation':'';?>">Subject : </span>
										<input maxlength="140" name="cu_subject_line_field" required class="resize_addon_input_subject" id="prependedInput" type="text" placeholder="Your question here ... " value="<?=get_last_value($user_data,'subject_field','');?>" />
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<textarea maxlength="4096" name="cu_message_field" required rows="15" class="input-block-level <?=isset($validation['cu_message_field'])?'fail_validation':'';?>" placeholder="Your message here ... "><?=get_last_value($user_data,'message_field','');?></textarea>	
								</td>
							</tr>
							<tr>
								<td>
									<button class="btn btn-success">Send Message</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
	<?include "footer.php"?>
</body>
</html>
