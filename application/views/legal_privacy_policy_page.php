<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Privacy Policy | <?=$this->config->item('window_title')?></title>
    <meta name="description" content="CriticOwl's privacy policy.">
    <meta name="keywords" content="privacy,policy">
    <meta property="og:image" content="<?=get_compressed_thumbnail_url(base_url().'img/icon_logo_red_512x512.png')?>" />
    <meta property="og:title" content="Privacy Policy | <?=$this->config->item('window_title')?>" />
    <meta property="og:description" content="CriticOwl's privacy policy." />
    <meta property="og:url" content="<?=base_url()?>" />
    <meta property="og:site_name" content="<?=$this->config->item('logo_text')?>"/>
    <?include "header_essentials.php"?>
</head>
<style type="text/css">
.left_pane {
	float: left;
	width: 65%;
	font-size: 16px;
}
.right_pane {
	float: right;
	width: 32%;
	font-size: 16px;
}
.movies_list {
	text-align: right;
}
.review_block {
	display: block;
	position: relative;
}
.review {

}

.review-right {
	padding: 0px 5px;
}
.review-left {
	min-width: 200px;
}

.sub-table {
	text-align: center;
	font-size: 14px;
}

.sub-table tr:nth-of-type(odd) {
  background-color:#F4EDE8;
}
.sub-table tr:nth-of-type(even) {
  background-color:#FFEFE5;
}
</style>
<?
//pagination config
$id_recent_posts = 'recent_posts'; 
?>
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
				
				<div>
					<h1>Privacy Policy</h1>
					<p>
						We, here at CriticOwl take great importance to the privacy of our readers, therefore we have created this privacy policy which will disclose the information gathering practices on this website.
					</p>
					<p>
						We allow several companies to place ads on our webpage. These ads may contain cookies which are then collected by ad companies.
					</p>
					<p>
						Third-Party advertising companies are also used to serve ads when you visit our Web site. These companies may use information (not including your name, address, email address, or telephone number) about your visits to this and other Web sites in order to provide advertisements of goods and services that are of interest to you. 
					</p>
					<p>
						We use cookies to record user-specific information such as what pages users access, past activity on the site. The information collected is used to better the experience of the reader the next time our site is visited.
					</p>
					<p>
						The only email addresses that are collected are those that are provided to us by our readers through subscriptions and our mailing list. This information is not sold, distributed, or shown to any third party company. Readers also have the choice to opt-out of subscriptions and mailing lists.
					</p>
					<p>
						This website contains links to other sites. CriticOwl.com is not responsible for the content or privacy practices of such websites.
					</p>
					<p>
						We reserve the right to change this policy at any time. Please check this page periodically for updates or changes. Your continued use of this Web site following any updates or changes will verify your acceptance of the updates or changes. 
					</p>
					<p>
						If you have any questions about this privacy statement, the practices of this site, or your dealings with this Web site, please feel free to contact us at <a href="mailto:team@criticowl.com?Subject=CriticOwl%20Pricay%20Policy">team@criticowl.com</a> or use our contact link in the footer.
					</p>
				</div>
				
			</div>
		</div>
	</div>
	<?include "footer.php"?>
</body>
</html>
